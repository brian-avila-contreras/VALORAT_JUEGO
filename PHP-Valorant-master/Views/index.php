<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['jugador'])) {
    header("Location: ../login.php");
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
               a.tarjeta AS tarjeta_agente, r.nombre AS nombre_rango, r.foto AS foto_rango
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

    // Actualizar el ID de rango según los puntos de rango
    $puntos_rango = $usuario['puntos_rango'];
    if ($puntos_rango > 2250) {
        $id_rango = 9;
    } elseif ($puntos_rango > 2000) {
        $id_rango = 8;
    } elseif ($puntos_rango > 1750) {
        $id_rango = 7;
    } elseif ($puntos_rango > 1500) {
        $id_rango = 6;
    } elseif ($puntos_rango > 1250) {
        $id_rango = 5;
    } elseif ($puntos_rango > 1000) {
        $id_rango = 4;
    } elseif ($puntos_rango > 750) {
        $id_rango = 3;
    } elseif ($puntos_rango > 500) {
        $id_rango = 2;
    } else {
        $id_rango = 1;
    }

    // Actualizar el ID de rango en la base de datos
    $sql_update = "UPDATE usuarios SET id_rango = :id_rango WHERE id_usuario = :id_usuario";
    $stmt_update = $db->prepare($sql_update);
    $stmt_update->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);
    $stmt_update->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt_update->execute();
} catch (PDOException $e) {
    echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    exit;
}

// Botón "Restaurar Salud"
$boton_restaurar_salud = '';
if ($usuario['puntos_salud'] <= 0) {
    $boton_restaurar_salud = '<div class="salud"><form action="restaurar_salud.php" method="post">
                                    <input type="hidden" name="id_usuario" value="' . $usuario['id_usuario'] . '">
                                    <button type="submit"><i class="fa-solid fa-diamond"></i> Restaurar Salud</button>
                                </form></div>';
}

// Actualizar contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nueva_contraseña"])) {
    // Obtener la nueva contraseña desde el formulario
    // Verificar si se proporcionó una nueva contraseña
if (!empty($_POST['nueva_contraseña'])) {
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $contraseña_encriptada = password_hash($nueva_contraseña, PASSWORD_DEFAULT); 
    $update_password = true;
} else {
    // No se proporcionó una nueva contraseña
    $update_password = false;
}

try {
    // Crear una instancia de la clase Database y establecer la conexión
    $db = new Database();
    $conn = $db->conectar();

    // Consulta SQL para actualizar la contraseña solo si se proporcionó una nueva
    if ($update_password) {
        $sql_update_contraseña = "UPDATE usuarios SET contraseña = ? WHERE id_usuario = ?";
        $stmt_update_contraseña = $conn->prepare($sql_update_contraseña);
        $stmt_update_contraseña->bindParam(1, $contraseña_encriptada, PDO::PARAM_STR);
        $stmt_update_contraseña->bindParam(2, $id_usuario, PDO::PARAM_INT);
        $stmt_update_contraseña->execute();
        // Avisa al usuario que la contraseña ha sido actualizada
        echo "<script>alert('¡La contraseña ha sido actualizada correctamente!'); window.location='index.php';</script>";

    }
} catch (PDOException $e) {
    echo 'Error al ejecutar la consulta: ' . $e->getMessage();
    exit;
}

}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
    <script src="https://kit.fontawesome.com/7fd910d257.js" crossorigin="anonymous"></script>
    <style>
    /* Estilos para el botón de contraseña */
    .btn-contrasena {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 99;
        background-color: #ff4654;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-contrasena:hover {
        background-color: #ff3b47;
    }

    /* Estilos para la ventana modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #1f1f1f;
        margin: 20% auto;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-width: 400px;
        color: black; /* Cambio de color a negro */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: white;
        text-decoration: none;
        cursor: pointer;
    }

    /* Estilos para el formulario dentro de la ventana modal */
    #form-contraseña {
        margin-top: 20px;
    }

    #form-contraseña input[type="password"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    #form-contraseña input[type="submit"] {
        background-color: #ff4654;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    #form-contraseña input[type="submit"]:hover {
        background-color: #ff3b47;
    }
</style>

</head>

<body>
    <video autoplay loop muted id="video-background">
        <source src="../video/videoclove.mp4" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>
    <div class="contenido">
        <!-- Barra de navegación -->
        <?php include 'nav2.php'; ?>
        <?php include 'nav.php'; ?>
        <!-- Botones de acción -->
        <div class="botones">
            <ul>
                <li>
                    <form action="mapas.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit" id="jugar"><i class="fa-solid fa-diamond"></i> Jugar</button>
                    </form>
                </li>
                <li>
                    <?= $boton_restaurar_salud ?>
                </li>
                <li>
                    <form action="estadisticas.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit"><i class="fa-solid fa-diamond"></i> Carrera</button>
                    </form>
                </li>
                <li>
                    <form action="agentes/agentes.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit"> <i class="fa-solid fa-diamond"></i> Agentes</button>
                    </form>
                </li>
                <li>
                    <form action="mapas/mapas.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit"><i class="fa-solid fa-diamond"></i> Mapas</button>
                    </form>
                </li>
                <li>
                    <form action="armas/armas.php" method="post">
                        <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                        <button type="submit"><i class="fa-solid fa-diamond"></i> Armas</button>
                    </form>
                </li>
            </ul>
        </div>
        <!-- Botón "Contraseña" -->
        <button id="btn-contrasena" class="btn-contrasena">Contraseña</button>
<!-- Ventana modal para actualizar contraseña -->
<div id="modal-contrasena" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 style="text-align: center;">Actualizar Contraseña</h2>
        <p style="text-align: center;">Por favor, introduce una nueva contraseña. Asegúrate de que sea segura y fácil de recordar.</p>
        <form id="form-contraseña" action="" method="post">
            <label for="nueva_contraseña">Nueva Contraseña:</label>
            <input type="password" id="nueva_contraseña" name="nueva_contraseña" required>
            <input type="submit" value="Guardar">
        </form>
    </div>
</div>


    </div>
    <script>
        // JavaScript para abrir y cerrar la ventana modal
        var btnContrasena = document.getElementById("btn-contrasena");
        var modal = document.getElementById("modal-contrasena");
        var span = document.getElementsByClassName("close")[0];
        var formContrasena = document.getElementById("form-contrasena");

        btnContrasena.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Enviar el formulario al hacer clic en el botón "Guardar"
        formContrasena.onsubmit = function(event) {
            event.preventDefault(); // Evitar que se envíe el formulario de forma tradicional
            var formData = new FormData(formContrasena);
            fetch(formContrasena.action, {
                method: formContrasena.method,
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Mostrar mensaje de éxito o error
                modal.style.display = "none"; // Cerrar la ventana modal
            })
            .catch(error => console.error('Error al actualizar la contraseña:', error));
        }
    </script>
</body>

</html>