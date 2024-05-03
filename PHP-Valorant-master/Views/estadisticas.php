<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['jugador'])) {
    header("Location: ../../../../valorant/login.php");
    exit;
}

// Obtener el ID del usuario en sesión
$id_usuario = $_SESSION['jugador']['id_usuario'];

// Verificar si se recibió el ID del jugador por POST
$id_jugador = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : $id_usuario;

// Incluir el archivo de conexión a la base de datos
require_once '../Config/conexion.php';

// Definir el número de resultados por página
$resultados_por_pagina = 3;

// Obtener el número de la página actual para la tabla de ataques donde el usuario es el atacante
$pagina_actual_atacante = isset($_GET['pagina_atacante']) ? $_GET['pagina_atacante'] : 1;

// Obtener el número de la página actual para la tabla de ataques donde el usuario es el atacado
$pagina_actual_atacado = isset($_GET['pagina_atacado']) ? $_GET['pagina_atacado'] : 1;

// Calcular el índice del primer resultado en la página para la tabla de ataques donde el usuario es el atacante
$indice_inicio_atacante = ($pagina_actual_atacante - 1) * $resultados_por_pagina;

// Calcular el índice del primer resultado en la página para la tabla de ataques donde el usuario es el atacado
$indice_inicio_atacado = ($pagina_actual_atacado - 1) * $resultados_por_pagina;

// Consultas SQL para obtener los datos de la tabla detalles_usuarios donde el id_usuario coincide con id_jugador_atacante, con paginación
$sql_atacante = "SELECT du.id_detalle, du.daño_realizado, 
               usu.nombre AS jugador_atacante, usu.id_agente AS id_agente_atacante,
               jug.nombre AS jugador_atacado, jug.id_agente AS id_agente_atacado,
               du.fecha, arma.foto AS foto_arma, 
               mapa.nombre AS nombre_mapa,
               mapa.foto AS foto_mapa, -- Agregamos la columna foto_mapa
               a_agente.foto AS foto_agente_atacante,
               a_agredido.foto AS foto_agente_atacado
        FROM detalles_usuarios du
        LEFT JOIN usuarios usu ON du.id_jugador_atacante = usu.id_usuario
        LEFT JOIN usuarios jug ON du.id_jugador_atacado = jug.id_usuario
        LEFT JOIN armas arma ON du.id_arma = arma.id_arma
        LEFT JOIN mapa mapa ON du.id_mapa = mapa.id_mapa
        LEFT JOIN agentes a_agente ON usu.id_agente = a_agente.id_agente
        LEFT JOIN agentes a_agredido ON jug.id_agente = a_agredido.id_agente
        WHERE du.id_jugador_atacante = :id_jugador
        LIMIT $indice_inicio_atacante, $resultados_por_pagina";

// Consultas SQL para obtener los datos de la tabla detalles_usuarios donde el id_usuario coincide con id_jugador_atacado, con paginación
$sql_atacado = "SELECT du.id_detalle, du.daño_realizado, 
            usu.nombre AS jugador_atacante, usu.id_agente AS id_agente_atacante,
            jug.nombre AS jugador_atacado, jug.id_agente AS id_agente_atacado,
            du.fecha, arma.foto AS foto_arma, 
            mapa.nombre AS nombre_mapa,
            mapa.foto AS foto_mapa, -- Agregamos la columna foto_mapa
            a_agente.foto AS foto_agente_atacante,
            a_agredido.foto AS foto_agente_atacado
        FROM detalles_usuarios du
        LEFT JOIN usuarios usu ON du.id_jugador_atacante = usu.id_usuario
        LEFT JOIN usuarios jug ON du.id_jugador_atacado = jug.id_usuario
        LEFT JOIN armas arma ON du.id_arma = arma.id_arma
        LEFT JOIN mapa mapa ON du.id_mapa = mapa.id_mapa
        LEFT JOIN agentes a_agente ON usu.id_agente = a_agente.id_agente
        LEFT JOIN agentes a_agredido ON jug.id_agente = a_agredido.id_agente
        WHERE du.id_jugador_atacado = :id_jugador
        LIMIT $indice_inicio_atacado, $resultados_por_pagina";

// Consulta SQL para obtener el total de resultados para la tabla de ataques donde el usuario es el atacante
$sql_total_atacante = "SELECT COUNT(*) AS total FROM detalles_usuarios WHERE id_jugador_atacante = :id_jugador";

// Consulta SQL para obtener el total de resultados para la tabla de ataques donde el usuario es el atacado
$sql_total_atacado = "SELECT COUNT(*) AS total FROM detalles_usuarios WHERE id_jugador_atacado = :id_jugador";

try {
    // Crear una instancia de la clase Database
    $dbClass = new Database();
    $db = $dbClass->conectar();

    // Preparar la consulta para los datos paginados donde el id_usuario coincide con id_jugador_atacante
    $stmt_atacante = $db->prepare($sql_atacante);
    $stmt_atacante->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmt_atacante->execute();
    $estadisticas_atacante = $stmt_atacante->fetchAll(PDO::FETCH_ASSOC);

    // Preparar la consulta para los datos paginados donde el id_usuario coincide con id_jugador_atacado
    $stmt_atacado = $db->prepare($sql_atacado);
    $stmt_atacado->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmt_atacado->execute();
    $estadisticas_atacado = $stmt_atacado->fetchAll(PDO::FETCH_ASSOC);

    // Realizar consultas para obtener el total de resultados
    $stmt_total_atacante = $db->prepare($sql_total_atacante);
    $stmt_total_atacante->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmt_total_atacante->execute();
    $total_resultados_atacante = $stmt_total_atacante->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt_total_atacado = $db->prepare($sql_total_atacado);
    $stmt_total_atacado->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmt_total_atacado->execute();
    $total_resultados_atacado = $stmt_total_atacado->fetch(PDO::FETCH_ASSOC)['total'];

    // Calcular el total de páginas para la tabla de ataques donde el usuario es el atacante
    $total_paginas_atacante = ceil($total_resultados_atacante / $resultados_por_pagina);

    // Calcular el total de páginas para la tabla de ataques donde el usuario es el atacado
    $total_paginas_atacado = ceil($total_resultados_atacado / $resultados_por_pagina);
} catch (PDOException $e) {
    echo 'Error al obtener las estadísticas: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
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

        #contenido {
            position: relative;
            z-index: 1;
            color: white;
            /* Color del texto sobre el video */
            padding: 20px;
            /* Añade un espacio alrededor del contenido */
        }

        #contenido-container {
            max-height: 100vh;
            /* Altura máxima del contenedor */
            overflow-y: scroll;
            /* Agregamos la barra de desplazamiento vertical */
            margin: 0 auto;
            /* Centramos el contenedor horizontalmente */
            padding: 20px;
            /* Añadimos un espacio alrededor del contenido */
        }

        h1 {
            text-align: center;
            font-size: 36px;
            padding-top: 30px;
            color: #fff
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            border: 2px solid rgb(238, 90, 90);
            text-align: center;
            margin-bottom: 30px;
            color: white;
            transition: all 0.2s;

        }

        th,
        td {
            border: 1px solid rgb(238, 90, 90);
            /* Morado estilo Valorant */
            padding: 10px;
            text-align: center;
            /* Centramos el contenido de las celdas */
        }

        th {
            background-color: white;
            color: rgb(238, 90, 90);
            transition: all 0.2s;
        }

        tr:nth-child(even) {
            background-color: #382e4d;
            /* Morado oscuro estilo Valorant */
        }

        

        .img-small {
            width: 50%;
            /* Establecer un ancho fijo para todas las imágenes pequeñas */
            height: auto;
            border-radius: 50%;
            margin-right: 10px;
        }

        .img-large {
            width: 80%;
            height: auto;
            border-radius: 10px;
            margin-right: 10px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .btn-pagination {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: white;
            color: rgb(238, 90, 90);
            transition: all 0.2s;
            border: 1px solid rgb(238, 90, 90);
        }

        .btn-pagination:hover {
            background-color: rgb(238, 90, 90);
            color: white;
            transition: all 0.2s;

        }


        .btn-volver {
            position: absolute;
            top: 10px;
            right: 25px;
            background-color: white;
            color: rgb(238, 90, 90);
            transition: all 0.2s;
        }
    </style>
</head>

<body>
    <div id="contenido-container"> <!-- Agregamos un nuevo contenedor -->
        <video autoplay loop muted id="video-background">
            <source src="../video/videoclove.mp4" type="video/mp4">
            Tu navegador no soporta videos HTML5.
        </video>
        <div>
            <h1>Estadísticas de mis ataques</h1>

            <!-- Tabla de ataques donde el usuario es el atacante -->
            <table>
                <thead>
                    <tr>
                        <th>Daño Realizado</th>
                        <th>Jugador Atacante</th>
                        <th>Jugador Atacado</th>
                        <th>Fecha</th>
                        <th>Arma</th>
                        <th>Mapa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estadisticas_atacante as $estadistica) : ?>
                        <tr>
                            <td><?= $estadistica['daño_realizado'] . '%' ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_agente_atacante']) ?>" alt="<?= $estadistica['jugador_atacante'] ?>" class="img-small"><?= $estadistica['jugador_atacante'] ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_agente_atacado']) ?>" alt="<?= $estadistica['jugador_atacado'] ?>" class="img-small"><?= $estadistica['jugador_atacado'] ?></td>
                            <td><?= $estadistica['fecha'] ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_arma']) ?>" class="img-large"></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_mapa']) ?>" class="img-large"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Fin de la tabla de ataques donde el usuario es el atacante -->

            <!-- Mostrar botones de paginación para la tabla de ataques donde el usuario es el atacante -->
            <div class="pagination">
                <?php if ($pagina_actual_atacante > 1) : ?>
                    <a href="?pagina_atacante=<?= $pagina_actual_atacante - 1 ?>" class="btn btn-pagination">Anterior</a>
                <?php endif; ?>
                <a href="?pagina_atacante=<?= min($pagina_actual_atacante + 1, $total_paginas_atacante) ?>" class="btn btn-pagination">Siguiente</a>
            </div>
            <!-- Fin de la paginación para la tabla de ataques donde el usuario es el atacante -->
            <h1>Estadísticas en contra de mí</h1>
            <!-- Tabla de ataques donde el usuario es el atacado -->
            <table>
                <thead>
                    <tr>
                        <th>Daño Realizado</th>
                        <th>Jugador Atacante</th>
                        <th>Jugador Atacado</th>
                        <th>Fecha</th>
                        <th>Arma</th>
                        <th>Mapa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estadisticas_atacado as $estadistica) : ?>
                        <tr>
                            <td><?= $estadistica['daño_realizado'] . '%' ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_agente_atacante']) ?>" alt="<?= $estadistica['jugador_atacante'] ?>" class="img-small"><?= $estadistica['jugador_atacante'] ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_agente_atacado']) ?>" alt="<?= $estadistica['jugador_atacado'] ?>" class="img-small"><?= $estadistica['jugador_atacado'] ?></td>
                            <td><?= $estadistica['fecha'] ?></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_arma']) ?>" class="img-large"></td>
                            <td><img src="data:image/jpeg;base64,<?= base64_encode($estadistica['foto_mapa']) ?>" class="img-large"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Fin de la tabla de ataques donde el usuario es el atacado -->

            <!-- Mostrar botones de paginación para la tabla de ataques donde el usuario es el atacado -->
            <div class="pagination">
                <?php if ($pagina_actual_atacado > 1) : ?>
                    <a href="?pagina_atacado=<?= $pagina_actual_atacado - 1 ?>" class="btn btn-pagination">Anterior</a>
                <?php endif; ?>
                <a href="?pagina_atacado=<?= min($pagina_actual_atacado + 1, $total_paginas_atacado) ?>" class="btn btn-pagination">Siguiente</a>
            </div>
            <!-- Fin de la paginación para la tabla de ataques donde el usuario es el atacado -->

            <a href="index.php" class="btn btn-danger btn-volver">Volver</a>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>