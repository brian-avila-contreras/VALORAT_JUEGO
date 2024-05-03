<?php
require_once '../Config/validar_sesion.php';
require_once '../Config/conexion.php';

try {
    // Conexión a la base de datos
    $dbClass = new Database();
    $db = $dbClass->conectar();

    // Obtener el ID del mapa
    if (isset($_POST['id_mapa'])) {
        $id_mapa = $_POST['id_mapa'];

        // Consulta SQL para obtener información de los jugadores en la sala
        $sql_info_sala = "SELECT mapa.*, usuarios.id_usuario, usuarios.nombre AS nombre_jugador, usuarios.puntos_salud, usuarios.id_rango, agentes.tarjeta AS tarjeta_agente, rango.foto AS foto_rango
                          FROM mapa
                          LEFT JOIN usuarios ON mapa.jugador1_id = usuarios.id_usuario OR
                                               mapa.jugador2_id = usuarios.id_usuario OR
                                               mapa.jugador3_id = usuarios.id_usuario OR
                                               mapa.jugador4_id = usuarios.id_usuario OR
                                               mapa.jugador5_id = usuarios.id_usuario
                          LEFT JOIN agentes ON usuarios.id_agente = agentes.id_agente
                          LEFT JOIN rango ON usuarios.id_rango = rango.id_rango
                          WHERE id_mapa = :id_mapa";
        $stmt = $db->prepare($sql_info_sala);
        $stmt->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
        $stmt->execute();
        $jugadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si se encontraron jugadores en la sala
        if ($jugadores) {
            // Iterar sobre los jugadores para verificar el rango
            foreach ($jugadores as $jugador) {
                // Verificar si el jugador tiene un rango diferente al del mapa
                if ($jugador['id_rango'] !== $jugadores[0]['id_rango'] or $jugador['puntos_salud'] <= 0) {
                    // Iterar sobre los campos de jugador para encontrar el campo con el ID del jugador
                    foreach (['jugador1_id', 'jugador2_id', 'jugador3_id', 'jugador4_id', 'jugador5_id'] as $campo_jugador) {
                        // Si el ID del jugador está en el campo actual, actualizarlo a NULL
                        if ($jugador['id_usuario'] == $jugador[$campo_jugador]) {
                            $sql_actualizar_campo = "UPDATE mapa SET $campo_jugador = NULL WHERE id_mapa = :id_mapa";
                            $stmt_actualizar = $db->prepare($sql_actualizar_campo);
                            $stmt_actualizar->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
                            $stmt_actualizar->execute();
                            break; // Detener la iteración sobre los campos de jugador
                        }
                    }
                }
            }

            // Obtener el índice del jugador activo
            $indice_activo = array_search($_SESSION['jugador']['id_usuario'], array_column($jugadores, 'id_usuario'));
            // Obtener la cantidad total de jugadores
            $total_jugadores = count($jugadores);

            // Calcular el índice medio de los jugadores
            $indice_medio = floor($total_jugadores / 2);

            // Calcular el índice del jugador activo en el medio
            $indice_en_medio = $indice_activo - $indice_medio;

            // Si el índice en medio es negativo, ajustarlo al principio de la lista
            if ($indice_en_medio < 0) {
                $jugadores_en_medio = array_slice($jugadores, 0, $total_jugadores + $indice_en_medio);
                $jugadores_al_final = array_slice($jugadores, $total_jugadores + $indice_en_medio);
                $jugadores_ordenados = array_merge($jugadores_al_final, $jugadores_en_medio);
            } else {
                // Obtener los jugadores al principio y al final
                $jugadores_al_principio = array_slice($jugadores, 0, $indice_en_medio);
                $jugadores_en_medio = array_slice($jugadores, $indice_en_medio, $total_jugadores - $indice_en_medio);
                $jugadores_ordenados = array_merge($jugadores_en_medio, $jugadores_al_principio);
            }
            echo "<div class='contenido'>";
            include 'nav2.php';
            echo "<div class='contenedor'>";

            // Calculamos la cantidad total de jugadores
            $total_jugadores = count($jugadores);

            // Si no hay jugadores, mostramos solo al jugador activo
            if ($total_jugadores === 0) {
                echo "<div class='jugador'>";
                echo "<img src='data:image/jpeg;base64," . base64_encode($_SESSION['jugador']['tarjeta_agente']) . "' alt='{$_SESSION['jugador']['nombre_jugador']}' class='imagen-jugador-activo'>";
                echo "<p>Rango: {$_SESSION['jugador']['nombre_rango']}</p>";
                echo "</div>";
            } else {
                // Ordenar los jugadores según los puntos de salud de forma descendente
                usort($jugadores_ordenados, function ($a, $b) {
                    return $b['puntos_salud'] - $a['puntos_salud'];
                });

                // Mostrar los jugadores y asignarles números de posición
                $posicion = 1; // Inicializamos la variable de posición
                $puntos_salud_anterior = null; // Variable para comparar puntos de salud
                foreach ($jugadores_ordenados as $jugador) {
                    echo "<div class='jugador'>";
                    // Verificar si los puntos de salud son diferentes del jugador anterior
                    if ($jugador['puntos_salud'] !== $puntos_salud_anterior) {
                        echo "<h2>{$jugador['nombre_jugador']} ($posicion)</h2>"; // Mostrar la posición solo si los puntos de salud son diferentes
                    } else {
                        echo "<h2>{$jugador['nombre_jugador']}</h2>"; // No mostrar posición si los puntos de salud son iguales
                    }
                    $puntos_salud_anterior = $jugador['puntos_salud']; // Actualizar puntos de salud anterior

                    if ($jugador['id_usuario'] === $_SESSION['jugador']['id_usuario']) {
                        echo "<b>(TÚ)</b>";
                        echo "<img src='data:image/jpeg;base64," . base64_encode($jugador['tarjeta_agente']) . "' alt='{$jugador['nombre_jugador']}' class='imagen-jugador-activo'>";
                    } else {
                        echo "<img src='data:image/jpeg;base64," . base64_encode($jugador['tarjeta_agente']) . "' alt='{$jugador['nombre_jugador']}' class='imagen-jugador'>";
                    }
                    // Mostrar la foto del rango en lugar del nombre
                    echo "<img src='data:image/jpeg;base64," . base64_encode($jugador['foto_rango']) . "' alt='{$jugador['nombre_jugador']}' class='imagen-rango'>";
                    echo "</div>";
                    $posicion++; // Incrementar posición para el siguiente jugador
                }


                // Mostramos los espacios vacíos al final si es necesario
                $espacios_vacios = 5 - $total_jugadores;
                for ($i = 0; $i < $espacios_vacios; $i++) {
                    echo "<div class='jugador' style='position: relative; z-index: 0; margin-left:-20px;'>";
                    echo "<div style='background-color: transparent; width: 150px; height: 300px; border: 2px solid white; display: flex; justify-content: center; align-items: center;'>";
                    echo '<i class="fas fa-plus" style="font-size: 24px;"></i>';
                    echo "</div>";
                    echo "</div>";
                }
            }

            echo "</div>";
            echo "</div>";



            // Verificar la salud del jugador atacante
            $id_atacante = $_SESSION['jugador']['id_usuario'];
            $sql_salud_atacante = "SELECT puntos_salud FROM usuarios WHERE id_usuario = :id_atacante";
            $stmt_salud = $db->prepare($sql_salud_atacante);
            $stmt_salud->bindParam(':id_atacante', $id_atacante, PDO::PARAM_INT);
            $stmt_salud->execute();
            $salud_atacante = $stmt_salud->fetchColumn();

            // Imprimir mensaje si el jugador atacante tiene 0 de salud
            if ($salud_atacante <= 0) {
                echo "<div id='mensajeSalud'><script>document.getElementById('mensajeSalud').innerText = 'Cuentas con 0% de salud. Por favor, abandona el mapa y restaura tus puntos de salud.';</script></div>";
            } else {
                echo "<a href='index.php' class='btn btn-danger btn-volver'>Volver</a>";
                echo "<div id='btnCombatirDiv'><button id='btnCombatir'>Combatir</button>";


                // Formulario para seleccionar jugador a atacar (oculto inicialmente)
                echo "<form id='formCombatir' action='combatir.php' method='post' style='display:none;'>";
                echo "<select name='id_atacado'>";
                foreach ($jugadores as $jugador) {
                    if ($jugador['id_usuario'] !== $_SESSION['jugador']['id_usuario']) {
                        echo "<option value='{$jugador['id_usuario']}' data-puntos-salud='{$jugador['puntos_salud']}'>{$jugador['nombre_jugador']}</option>";
                    }
                }
                echo "</select>";
                echo "<input type='hidden' name='id_atacante' value='{$_SESSION['jugador']['id_usuario']}'>";
                echo "<input type='hidden' name='id_mapa' value='$id_mapa'>"; // Agregado el campo oculto para el id_mapa
                echo "<button type='submit' id='atacar'>Atacar</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No hay jugadores en la sala.";
        }
    } else {
        echo "Error: No se recibió el ID del mapa.";
    }
} catch (PDOException $e) {
    echo "Error al cargar la sala: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugadores en la sala</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://kit.fontawesome.com/7fd910d257.js" crossorigin="anonymous"></script>

    <!--favicon-->
    <link rel="apple-touch-icon" sizes="60x60" href="../Assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../Assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../Assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../Assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../Assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../Assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../Assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../Assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../Assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../Assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../Assets/favicon/manifest.json">
    
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <style>
        .btn-volver {
            position: absolute;
            background-color: rgb(238, 90, 90);
            color: white;
            border: 2px solid rgb(238, 90, 90);
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-volver:hover {
            background-color: white;
            color: rgb(238, 90, 90);
        }

        #btnCombatirDiv {
            display: flex;
            width: 100%;
            height: 10%;
            margin-top: 3%;
            justify-content: center;
            align-items: center;
            font-family: "Anton", sans-serif;
        }

        #btnCombatir {
            width: 12%;
            height: 100%;
            border: 1px solid white;
            color: white;
            background-color: rgb(238, 90, 90);
            font-size: 25px;
            border-radius: 15px;
            margin-right: 2%;
            font-family: "Anton", sans-serif;
            transition: all 0.2s;

        }

        #btnCombatir:hover {
            transition: all 0.2s;

        }


        #atacar {

            height: 100%;
            border: 1px solid white;
            color: white;
            background-color: rgb(238, 90, 90);
            font-size: 25px;
            border-radius: 15px;
            font-family: "Anton", sans-serif;

        }

        #atacar:hover {
            transition: all 0.2s;

        }

        #formCombatir {
            margin-top: 1%;
        }


        #formCombatir select {
            width: 200px;
            height: 40px;
            border: 1px solid rgb(238, 90, 90);
            border-radius: 5px;
            background-color: white;
            color: rgb(238, 90, 90);
            font-size: 16px;
            padding: 5px;
            outline: none;
            margin-right: 25px;
            font-family: "Anton", sans-serif;
            text-align: center;
        }

        #formCombatir select:hover {
            transition: all 0.2s;

        }

        /* Estilo para las opciones del select */
        #formCombatir select option {
            background-color: white;
            color: rgb(238, 90, 90);
            font-size: 16px;
            font-family: "Anton", sans-serif;
            text-align: center;
        }

        #formCombatir select option:hover {
            color: white;
            transition: all 0.2s;
        }


        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
            /* Selecciona una fuente legible */
        }

        #video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
        }

        #contenido {
            position: relative;
            z-index: 1;
            color: white;
            /* Color del texto sobre el video */
            padding: 20px;
            /* Añade un espacio alrededor del contenido */
        }

        .contenedor {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            color: white;
            font-family: "Anton", sans-serif;
            margin-top: -2%;
        }

        .jugador {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .imagen-jugador {
            margin-bottom: 10px;
            width: 60%;
            height: auto;
            border: 2px solid white;
            margin-left: -20px;
        }

        .imagen-rango {
            margin-top: -10%;
            width: 20%;
            margin-left: -20px;
        }

        .imagen-jugador-activo {
            width: 70%;
            border: 2px solid white;
            height: auto;
            margin-left: -20px;


        }
    </style>
</head>

<body>
    <video autoplay loop muted id="video-background">
        <source src="../video/videoclove.mp4" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>
    <script>
        document.getElementById('btnCombatir').addEventListener('click', function () {
            // Mostrar el formulario de combate
            document.getElementById('formCombatir').style.display = 'block';
        });

        // Verificar la salud del usuario atacado al enviar el formulario
        document.getElementById('formCombatir').addEventListener('submit', function (event) {
            var selectedOption = this.querySelector('select[name="id_atacado"] option:checked');
            var puntosSaludAtacado = parseInt(selectedOption.getAttribute('data-puntos-salud'));
            var puntosSaludAtacante = <?php echo $salud_atacante; ?>;
            if (puntosSaludAtacado <= 0) {
                event.preventDefault(); // Evitar enviar el formulario
                alert('El jugador seleccionado tiene 0% de salud. Por favor, selecciona otro jugador.');
            }
            if (puntosSaludAtacante <= 0) {
                event.preventDefault(); // Evitar enviar el formulario
                alert('Cuentas con 0% de salud. Por favor, abandona el mapa y restaura tus puntos de salud.');
            }
        });
    </script>
</body>

</html>