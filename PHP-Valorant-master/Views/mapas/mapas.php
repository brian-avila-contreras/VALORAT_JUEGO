<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['jugador'])) {
    header("Location: ../../../../valorant/login.php");
    exit;
}

// Incluir el archivo de conexión a la base de datos
require_once '../../Config/conexion.php';

// Crear una instancia de la clase Database
$dbClass = new Database();
$db = $dbClass->conectar();

// Consulta SQL para seleccionar todos los mapas con sus detalles
$query = "SELECT id_mapa, nombre, foto FROM mapa";
$result = $db->query($query);

// Verificar si se recuperaron los datos correctamente
if ($result && $result->rowCount() > 0) {
    // Asignar los resultados a la variable $mapas
    $mapas = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no se recuperaron resultados, asignar un array vacío a $mapas
    $mapas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--favicon-->
    <link rel="apple-touch-icon" sizes="60x60" href="../../Assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../Assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../Assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../Assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../Assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../Assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../Assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../Assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../../Assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../Assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../Assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../Assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../Assets/favicon/manifest.json">

    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mapas de Valorant</title>
    <style>
        /* Estilos CSS inspirados en Valorant */
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: "Anton", sans-serif;
            color: #fff; /* Texto blanco */
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

        #contenido-container {
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            color: #fff;
        }

        .map-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .map-card {
    width: 300px; /* Incremento del 50% respecto al ancho original */
    margin: 15px; /* Incremento del 50% respecto al margen original */
    padding: 10px;
    background-color: #121212;
    border: 2px solid #ffffff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s;
    filter: brightness(0.5); /* Oscurecer inicialmente */
}

        .map-card:hover {
            transform: translateY(-5px);
            filter: brightness(1); /* Iluminar al pasar el mouse */
        }

        .map-card img {
            width: 100%;
            border-radius: 5px;
            border: 1px solid white;
        }

        .map-card h3 {
            margin-top: 10px;
            text-align: center;
            font-size: 18px;
        }

        .btn-volver {
            position: absolute;
            top: 10px;
            right: 25px;
            background-color: red; /* Botón naranja */
            border: none;
            background-color: white;
            color:rgb(238, 90, 90); 
        }

        .btn-volver:hover {
            background-color: rgb(238, 90, 90); /* Naranja más oscuro al pasar el mouse */
        }
    </style>
</head>
<body>
    <video autoplay loop muted id="video-background">
        <source src="../../video/videoclove.mp4" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>

    <h1>Mapas de Valorant</h1>
    <div class="map-container">
        <!-- Aquí se insertarían los mapas con PHP -->
        <?php foreach ($mapas as $mapa): ?>
            <div class="map-card dark-mode">
                <?php
                    // Decodificar la imagen desde el blob
                    $imagen_base64 = base64_encode($mapa['foto']);
                    $imagen_data = base64_decode($imagen_base64);
                ?>
                <img src="data:image/jpeg;base64,<?php echo $imagen_base64; ?>" alt="<?php echo $mapa['nombre']; ?>">
                <h3><?php echo $mapa['nombre']; ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="javascript:history.back()" class="btn btn-danger btn-volver">Volver</a>

    <script>
        // JavaScript para cambiar la clase al pasar el mouse
        const mapCards = document.querySelectorAll('.map-card');

        mapCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.remove('dark-mode');
            });

            card.addEventListener('mouseleave', () => {
                card.classList.add('dark-mode');
            });
        });
    </script>
</body>
</html>
