<?php


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

// Obtener el ID del usuario en sesión
$id_usuario = $_SESSION['jugador']['id_usuario'];

// Consulta SQL para obtener los datos del jugador en sesión
$sql = "SELECT u.id_usuario, u.nombre AS nombre_usuario, u.puntos_salud, u.puntos_rango, 
               u.ultima_conexion, a.nombre AS nombre_agente, a.foto AS foto_agente, 
               a.tarjeta AS tarjeta_agente, r.nombre AS nombre_rango, r.foto AS foto_rango,
               u.mensaje
        FROM usuarios u
        LEFT JOIN agentes a ON u.id_agente = a.id_agente
        LEFT JOIN rango r ON u.id_rango = r.id_rango
        WHERE u.id_usuario = :id_usuario";

try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el jugador en sesión
    if (!$usuario) {
        echo "El jugador en sesión no fue encontrado en la base de datos.";
        exit;
    }
} catch (PDOException $e) {
    echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #sidebar {
            position: fixed;
            top: 0;
            right: -250px;
            width: 150px;
            height: 100%;
            background: gray;
            transition: all 0.3s;
            margin-top: 60px;
            color: white;
        }

        #sidebar.active {
            right: 0;
            /* Cambia a la derecha */
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li {
            padding: 10px;
            font-size: 1.2em;
            color: white;
        }

        #sidebar ul li:hover {
            background: #555;
        }

        /* Estilo para la imagen pequeña del agente en el menú lateral */
        #sidebar .agent-image {
            width: 50px;
            height: 50px;

            margin-bottom: 10px;
            border-radius: 0;
            /* Cambia la forma de la imagen a cuadrada */
        }

        /* Estilo para la imagen del agente en la parte superior derecha */
        #agent-icon {
            padding: 5px;
            position: fixed;
            top: 70px;
            right: 0;
            width: 50px;
            height: 100%;
            background-color: #555;
            cursor: pointer;
            /* Asegura que esté sobre el contenido */
        }

        #agent-icon:hover .datos {
            visibility: visible;
            transition: all 0.2s;
        }

        #agente {
            width: 100%;
            height: auto;
        }

        .datos {
            width: 250px;
            height: 150px;
            margin-left: -650%;
            margin-top: -80%;
            border-bottom: 2px solid white;
            visibility: hidden;
            transition: all 0.2s;
            z-index: 99999;
        }

        .agentes {
            width: 100%;
            height: 40%;
            background-color: rgba(255, 255, 255, 0.82);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2px;
            text-align: center;



        }

        .agentes ul {
            text-align: center;
            margin-left: -10%;
            margin-top: 5%;
        }

        .agentes li {
            list-style: none;

        }

        .agentes img {
            margin-top: -25%;
            width: 20%;
            height: auto;
            border: 1px solid black;
        }

        .rango {
            width: 100%;
            height: 40%;
            background-color: rgba(26, 25, 25, 0.721);
            display: flex;
            justify-content: space-around;
            align-items: center;
            color: white;



        }

        .rango img {
            width: 20%;
            height: auto;
        }

        .info {
            width: 100%;
            height: 20%;
            background-color: rgba(0, 247, 255, 0.886);
            display: flex;
            justify-content: space-around;
            align-items: center;
            color: white;

        }

        .info a {
            color: white;
            text-decoration: none;

        }

       
    </style>

</head>

<body>
   
    <div id="agent-icon">
        <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_agente']) ?>" alt="Foto del agente" class="user" id="agente">
        <div class="datos">
            <div class="agentes">
                <ul>
                    <li><a href="#"></a><b> <?= $usuario['nombre_usuario'] ?></b> #LAN</a></li>
                    <li style="font-size:12px;">De Categoria</li>
                </ul>
                <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_agente']) ?>" alt="Foto del agente" class="user" id="ag">

            </div>
            <div class="info">
                <i class="fa-solid fa-users"></i>
                <a href="">Disponible</a>
            </div>
            <div class="rango">
                <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_rango']) ?>" alt="Foto del agente" class="img-fluid rounded-circle agent-image">
                <?= $usuario['nombre_rango'] ?>
            </div>
        </div>
    </div>
    <!-- Menú lateral -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_agente']) ?>" alt="Foto del agente" class="agent-image">
            <?= $usuario['nombre_usuario'] ?>
        </div>
        <ul class="list-unstyled components">
            <li>
                Salud: <?= $usuario['puntos_salud'] ?>%
            </li>
            <li>
                Puntos de Rango: <?= $usuario['puntos_rango'] ?>Pts
                <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_rango']) ?>" alt="Foto del agente" class="img-fluid rounded-circle agent-image">
            </li>
            <li>
                Rango: <?= $usuario['nombre_rango'] ?>
            </li>
            <li>
                mensajes: <?= $usuario['mensaje'] ?>
            </li>
        </ul>
    </nav>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Función para desplegar/ocultar el menú lateral al hacer clic en la imagen del agente o en cualquier lado del contenedor #agent-icon
            $('#agent-icon, #sidebar').on('click', function(e) {
                $('#sidebar').toggleClass('active');
                // Ocultar la imagen del agente en la parte superior derecha cuando se despliega el menú
                $('#agente,#agent-icon').toggle();
                e.stopPropagation(); // Evita que el clic en el menú propague al cuerpo y cierre el menú
            });

            // Función para cerrar el menú lateral al hacer clic en cualquier zona fuera del menú
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#sidebar').length && !$(e.target).is('#agente')) {
                    $('#sidebar').removeClass('active');
                    // Mostrar la imagen del agente en la parte superior derecha cuando se cierra el menú
                    $('#agente,#agent-icon').show();
                }
            });
        });
    </script>
</body>

</html>