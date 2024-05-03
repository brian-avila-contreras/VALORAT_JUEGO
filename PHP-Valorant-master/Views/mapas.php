<?php

session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['jugador'])) {
    header("Location: ../../../../valorant/login.php");
    exit;
}

// Incluir el archivo de conexión a la base de datos
require_once '../Config/conexion.php';

// Crear una instancia de la clase Database
$dbClass = new Database();
$db = $dbClass->conectar();

// Obtener el ID del usuario que quiere unirse a un mapa
if (isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    // Consulta SQL para obtener el rango del usuario
    $sql_rango_usuario = "SELECT id_rango FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $db->prepare($sql_rango_usuario);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $rango_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consulta SQL para obtener los mapas con el mismo rango del usuario
    $sql_mapas = "SELECT id_mapa, nombre, foto, jugador1_id, jugador2_id, jugador3_id, jugador4_id, jugador5_id, id_rango FROM mapa WHERE id_rango = :id_rango";
    $stmt = $db->prepare($sql_mapas);
    $stmt->bindParam(':id_rango', $rango_usuario['id_rango'], PDO::PARAM_INT);
    $stmt->execute();
    $mapas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta SQL para obtener los mapas a los que el usuario ya está unido
    $sql_mapas_unidos = "
        SELECT id_mapa
        FROM (
            SELECT id_mapa, jugador1_id AS jugador_id FROM mapa
            UNION
            SELECT id_mapa, jugador2_id AS jugador_id FROM mapa
            UNION
            SELECT id_mapa, jugador3_id AS jugador_id FROM mapa
            UNION
            SELECT id_mapa, jugador4_id AS jugador_id FROM mapa
            UNION
            SELECT id_mapa, jugador5_id AS jugador_id FROM mapa
        ) AS mapas_unidos
        WHERE jugador_id = :id_usuario";
    $stmt = $db->prepare($sql_mapas_unidos);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $mapas_unidos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Verificar si se presionó el botón de unirse al mapa
    if (isset($_POST['id_mapa'])) {
        $id_mapa = $_POST['id_mapa'];

        // Verificar si el usuario ya está unido al mapa
        if (in_array($id_mapa, $mapas_unidos)) {
            echo "Ya estás unido a este mapa.";
        } else {
            // Consulta SQL para verificar si hay espacio disponible en el mapa
            $sql_verificar_espacio = "SELECT jugador1_id, jugador2_id, jugador3_id, jugador4_id, jugador5_id FROM mapa WHERE id_mapa = :id_mapa";
            $stmt = $db->prepare($sql_verificar_espacio);
            $stmt->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
            $stmt->execute();
            $mapa = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si hay espacio en el mapa
            $campo_jugador = null;
            foreach ($mapa as $key => $value) {
                if (is_null($value)) {
                    $campo_jugador = $key;
                    break;
                }
            }

            if (is_null($campo_jugador)) {
                echo "Lo siento, la sala está llena. Por favor, prueba con otra.";
            } else {
                // Actualizar el campo correspondiente con el ID del usuario
                $sql_unirse_mapa = "UPDATE mapa SET $campo_jugador = :id_usuario WHERE id_mapa = :id_mapa";
                $stmt = $db->prepare($sql_unirse_mapa);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
                $stmt->execute();

                // Eliminar jugadores con id_rango diferente al del mapa
                $sql_eliminar_jugadores = "UPDATE mapa SET jugador1_id = NULL WHERE id_mapa = :id_mapa AND jugador1_id IN (SELECT jugador_id FROM (SELECT jugador1_id AS jugador_id FROM mapa UNION SELECT jugador2_id AS jugador_id FROM mapa UNION SELECT jugador3_id AS jugador_id FROM mapa UNION SELECT jugador4_id AS jugador_id FROM mapa UNION SELECT jugador5_id AS jugador_id FROM mapa) AS jugadores WHERE jugador_id != :id_usuario AND jugador_id IS NOT NULL)";
                $stmt = $db->prepare($sql_eliminar_jugadores);
                $stmt->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                echo "Te has unido al mapa exitosamente.";
            }
        }
    }
} else {
    echo "<script>alert('Error: No se recibió el ID del usuario.'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <title>Mapas Valorant</title>
    <style>
        body {
            text-align: center;
            color: white;
            font-family: "Anton", sans-serif;
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

            display: flex;
            flex-wrap: wrap;
            /* Permitir que los elementos se envuelvan en una nueva fila */
            justify-content: space-evenly;
            align-items: center;
            width: 100%;
        }

        .mapas {
            flex: 0 0 30%;
            /* Ancho de cada mapa, ajustado para que tres mapas se muestren por fila */
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.3);
            /* Color de fondo transparente para cada mapa */
            border-radius: 10px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            /* Cambia la dirección del flexbox a columna */
            align-items: center;
            /* Centra los elementos en el eje vertical */
        }

        .mapas h2 {
            margin-bottom: 5px;
        }

        .mapas img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 10px;
            border: 2px solid white;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .mapas p {
            margin-bottom: 10px;
        }

        /* Estilos para los botones */
        .mapas form {
            display: flex;
            /* Utiliza flexbox para alinear los botones */
        }

        .mapas button {
            height: 100%;
            border: 1px solid white;
            color: white;
            background-color: rgb(238, 90, 90);
            font-size: 25px;
            border-radius: 5px;
            font-family: "Anton", sans-serif;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .mapas button:hover {
            background-color: white;
            color: rgb(238, 90, 90);
            transition: all 0.2s;
        }

        .mapas button:last-child {
            margin-right: 0;
            /* Elimina el margen derecho del último botón */
        }

        h2 {
            color: white;
            font-family: "Anton", sans-serif;
        }

        .btn-volver {
            position: absolute;
            top: 10px;
            right: 25px;
            background-color: red;
            /* Botón naranja */
            border: none;
            background-color: white;
            color: rgb(238, 90, 90);
        }

        .btn-volver:hover {
            background-color: rgb(238, 90, 90);
            /* Naranja más oscuro al pasar el mouse */
        }
    </style>
</head>

<body>
    <video autoplay loop muted id="video-background">
        <source src="../video/videoclove.mp4" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>
    <h1>Mapas Disponibles</h1>
    <?php include 'volver.php'; ?>
    <?php foreach ($mapas as $mapa) : ?>
        <div class="contenedor" id="contenido">
            <div class="mapas">

                <h2><?= $mapa['nombre'] ?></h2>
                <img src="data:image/jpeg;base64,<?= base64_encode($mapa['foto']) ?>" alt="Foto del mapa"><br>

                <!-- Mostrar el número de jugadores unidos -->
                <p>Número de jugadores unidos: <?= count(array_filter($mapa)) - 4 ?>/5</p>

                <!-- Mostrar el botón "Unirse" o el mensaje "Ya estás unido a este mapa" según corresponda -->
                <?php if (in_array($mapa['id_mapa'], $mapas_unidos)) : ?>
                    <form action="sala.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <input type="hidden" name="id_mapa" value="<?= $mapa['id_mapa'] ?>">
                        <button type="submit">Ingresar a la sala</button>
                    </form>
                    <form action="abandonar_mapa.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <input type="hidden" name="id_mapa" value="<?= $mapa['id_mapa'] ?>">
                        <button type="submit">Abandonar mapa</button>
                    </form>
                <?php else : ?>
                    <form action="" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <input type="hidden" name="id_mapa" value="<?= $mapa['id_mapa'] ?>">
                        <button type="submit">Unirse al mapa</button>
                    </form>
                <?php endif; ?>
                <hr>
            </div>
        </div>
       
    <?php endforeach; ?>
</body>

</html>