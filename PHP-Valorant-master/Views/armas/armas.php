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

// Consulta SQL para seleccionar todas las armas con sus detalles
$query = "SELECT id_arma, nombre, foto, balas, daño FROM armas";
$result = $db->query($query);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armas</title>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

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
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        .card {
            background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
            border: 2px solid white; /* Borde naranja */
            border-radius: 10px;
            margin-bottom: 20px;
            transition: all 0.3s ease; /* Transición suave */
            width: 100%;
            height: 90%;
            text-align: center;
        }

        .card:hover {
            transform: scale(1.05); /* Agrandar un poco la carta al pasar el mouse */
        }

        .card-title {
            color: rgb(238, 90, 90); /* Título naranja */
        }

        .card-body {
            padding: 20px;
            opacity: 0; /* Ocultar por defecto */
            transition: opacity 0.3s ease; /* Transición suave */
        }
        .card img{
            width: 100%;
            height: auto;
        }

        .card:hover .card-body {
            opacity: 1; /* Mostrar al pasar el mouse */
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

    <div id="contenido-container">
        <div class="container">
            <h1 class="text-center mb-4">Armas Disponibles</h1>
            <div class="row">
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto']); ?>" class="card-img-top" alt="<?php echo $row['nombre']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                            <p class="card-text">Balas: <?php echo $row['balas']; ?></p>
                            <p class="card-text">Daño: <?php echo $row['daño']; ?></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <br>
            <a href="javascript:history.back()" class="btn btn-danger btn-volver">Volver</a>
        </div>
    </div>

    <!-- Agregar el script de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Cerrar la conexión a la base de datos
$db = null;
?>
