<?php
require_once '../Config/validar_sesion.php';
require_once '../Config/conexion.php';

try {
    // Conexión a la base de datos
    $dbClass = new Database();
    $db = $dbClass->conectar();

    // Verificar si se han enviado los datos necesarios
    if (isset($_POST['id_mapa'], $_POST['id_atacante'], $_POST['id_atacado'])) {
        // Obtener los datos necesarios
        $id_mapa = $_POST['id_mapa'];
        $id_atacante = $_POST['id_atacante'];
        $id_atacado = $_POST['id_atacado'];

        // Actualizar el estado del jugador atacante a 3
        $sql_update_estado = "UPDATE usuarios SET id_estado = 3 WHERE id_usuario = :id_atacante";
        $stmt_update_estado = $db->prepare($sql_update_estado);
        $stmt_update_estado->bindParam(':id_atacante', $id_atacante, PDO::PARAM_INT);
        $stmt_update_estado->execute();

        // Obtener información del jugador atacante
        $sql_info_atacante = "SELECT u.nombre, a.foto AS foto_agente, r.foto AS foto_rango, r.id_rango FROM usuarios u
                              INNER JOIN agentes a ON u.id_agente = a.id_agente
                              INNER JOIN rango r ON u.id_rango = r.id_rango
                              WHERE u.id_usuario = :id_atacante";
        $stmt_atacante = $db->prepare($sql_info_atacante);
        $stmt_atacante->bindParam(':id_atacante', $id_atacante, PDO::PARAM_INT);
        $stmt_atacante->execute();
        $info_atacante = $stmt_atacante->fetch(PDO::FETCH_ASSOC);

        // Obtener información del jugador atacado
        $sql_info_atacado = "SELECT u.nombre, a.foto AS foto_agente, r.nombre AS nombre_rango, r.id_rango, r.foto AS foto_rango 
                     FROM usuarios u
                     INNER JOIN agentes a ON u.id_agente = a.id_agente
                     INNER JOIN rango r ON u.id_rango = r.id_rango
                     WHERE u.id_usuario = :id_atacado";
        $stmt_atacado = $db->prepare($sql_info_atacado);
        $stmt_atacado->bindParam(':id_atacado', $id_atacado, PDO::PARAM_INT);
        $stmt_atacado->execute();
        $info_atacado = $stmt_atacado->fetch(PDO::FETCH_ASSOC);


        // Obtener las armas del mismo rango que el jugador atacante
        $sql_armas = "SELECT id_arma, nombre, foto, balas, daño FROM armas WHERE id_rango <= :id_rango";
        $stmt_armas = $db->prepare($sql_armas);
        $stmt_armas->bindParam(':id_rango', $info_atacante['id_rango'], PDO::PARAM_INT);
        $stmt_armas->execute();
        $armas = $stmt_armas->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Si no se reciben los datos necesarios, mostrar un mensaje de error
        echo "Error: Datos insuficientes para iniciar el combate.";
    }
} catch (PDOException $e) {
    echo "Error al cargar la página: " . $e->getMessage();
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
    <title>Combate</title>
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

        .contenedor {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .jugador {
            width: 200px;
            margin: 10px;
            text-align: center;
        }

        .imagen-jugador {
            width: 100%;
            height: auto;
            border: 2px solid white;
            border-radius: 5px;
        }

        .imagen-jugador-activo {
            width: 100%;
            height: auto;

            border: 2px solid white;
            border-radius: 5px;

        }

        #contador {
            display: none;
        }

        #imgarma img {
            max-width: 200px;
            height: auto;
        }

        .armaform select {
            width: 300px;
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
            transition: all 0.2s;
        }
        .armaform select:hover{
            background-color: rgb(238, 90, 90);
            color:white; 
            transition: all 0.2s;

        }

        .imagen-rango {
            width: 40%;
            height: auto;
            margin-top: -15%;
        }

        #dispararBtn {

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

        #dispararBtn:hover {
            background-color: white;
            color:rgb(238, 90, 90); 
            transition: all 0.2s;
        }

        .tiempo {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #tiempo {
            width: 30%;
            height: 100%;
            border: 1px solid white;
            color: white;
            background-color: rgb(238, 90, 90);
            font-size: 25px;
            border-radius: 5px;
            font-family: "Anton", sans-serif;
            margin-bottom: 5px;

        }
        .btn-volver {
            position: absolute;
            top: 10px;
            right: 25px;
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
    </style>
</head>

<body>
    <video autoplay loop muted id="video-background">
        <source src="../video/videoclove.mp4" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>

    <div class="tiempo">
        <div id="tiempo"></div>
    </div>
    <div class='contenedor'>

        <div class='jugador'>
            <h2><?php echo $info_atacante['nombre']; ?></h2>
            <h2>(Tú)</h2>
            <img src='data:image/jpeg;base64,<?php echo base64_encode($info_atacante['foto_agente']); ?>' alt='<?php echo $info_atacante['nombre']; ?>' class='imagen-jugador-activo' id="imagenj">
            <img src='data:image/jpeg;base64,<?php echo base64_encode($info_atacante['foto_rango']); ?>' alt='<?php echo $info_atacante['nombre']; ?>' class='imagen-rango'>
        </div>
        <div class="armaform">
            <!-- Selector de arma -->
            <form id='formDisparar' action='procesar_combatir.php' method='post'>
                <label for="id_arma">Selecciona un arma:</label>
                <select name="id_arma" id="id_arma">
                    <option value="">Selecciona un arma</option>
                    <?php foreach ($armas as $arma) : ?>
                        <option value='<?php echo $arma['id_arma']; ?>' data-img="<?php echo 'data:image/jpeg;base64,' . base64_encode($arma['foto']); ?>"><?php echo $arma['nombre']; ?> - Daño: <?php echo $arma['daño']; ?>- Balas: <?php echo $arma['balas']; ?></option>
                    <?php endforeach; ?>
                </select>
                <!-- Agregar campos ocultos para enviar los datos necesarios -->
                <input type="hidden" name="id_atacante" value="<?php echo $id_atacante; ?>">
                <input type="hidden" name="id_atacado" value="<?php echo $id_atacado; ?>">
                <input type="hidden" name="id_mapa" value="<?php echo $id_mapa; ?>">
                <!-- Agregar campo oculto para enviar el estado del atacante -->
                <input type="hidden" name="id_estado" id="id_estado" value="3">
                <input type="submit" id="dispararBtn" value="Disparar">
                <div id="contador"></div>

            </form>
        </div>
        <!-- Div para mostrar la imagen del arma seleccionada -->
        <div id="imgarma">

        </div>
        <!-- Información del jugador atacado -->
        <div class='jugador'>
            <h2><?php echo $info_atacado['nombre']; ?></h2>
            <img src='data:image/jpeg;base64,<?php echo base64_encode($info_atacado['foto_agente']); ?>' alt='<?php echo $info_atacado['nombre']; ?>' class='imagen-jugador'>
            <img src='data:image/jpeg;base64,<?php echo base64_encode($info_atacado['foto_rango']); ?>' alt='<?php echo $info_atacado['nombre']; ?>' class='imagen-rango'>
        </div>
        
    </div>

    <a href="index.php" class="btn btn-danger btn-volver">Volver</a>
    <script>
        // Función para redireccionar a index.php
        function redirectToIndex() {
            window.location.href = 'index.php';
        }

        // Contador regresivo de 5 minutos
        var tiempoRestante = 5 * 60; // 5 minutos en segundos
        var intervaloContador = setInterval(function() {
            tiempoRestante--;
            if (tiempoRestante <= 0) {
                clearInterval(intervaloContador);
                redirectToIndex(); // Redireccionar cuando el tiempo haya terminado
            } else {
                var minutos = Math.floor(tiempoRestante / 60);
                var segundos = tiempoRestante % 60;
                var tiempoFormato = minutos.toString().padStart(2, '0') + ':' + segundos.toString().padStart(2, '0');
                document.getElementById('tiempo').innerHTML = "Tiempo restante de partida: " + tiempoFormato;
            }
        }, 1000);

        var selectArma = document.getElementById("id_arma");
        var divImgArma = document.getElementById("imgarma");

        selectArma.addEventListener("change", function() {
            var selectedOption = selectArma.options[selectArma.selectedIndex];
            var imgSrc = selectedOption.getAttribute("data-img");
            divImgArma.innerHTML = "<img src='" + imgSrc + "' alt=''>";
        });

        var idEstado = document.getElementById("id_estado").value;
if (idEstado === "3") {
    document.getElementById("dispararBtn").style.display = "none";
    document.getElementById("contador").style.display = "block";
    // Configurar el tiempo de espera en milisegundos (15 segundos = 15000 ms)
    var tiempoEspera = 15000;
    // Mostrar el contador regresivo
    var contador = tiempoEspera / 1000; // convertir a segundos
    var intervalo = setInterval(function() {
        contador--;
        document.getElementById("contador").innerHTML = "Siguiente turno en: " + contador + " segundos";
        if (contador <= 0) {
            clearInterval(intervalo);
            document.getElementById("dispararBtn").style.display = "block";
            document.getElementById("contador").style.display = "none";
            document.getElementById("contador").innerHTML = "";
            // Actualizar el estado del jugador atacante a 4 después de 15 segundos
            document.getElementById("id_estado").value = "4";
        }
    }, 1000);
}

    </script>

</body>

</html>