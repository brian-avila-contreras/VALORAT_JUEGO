<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../Config/conexion.php';

    // Obtener el correo electrónico proporcionado por el usuario
    $correo = $_POST["correo"];

    // Verificar si el correo electrónico tiene un formato válido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // El correo electrónico no tiene un formato válido
        echo "<script>alert('Por favor, ingrese un correo electrónico válido.'); window.location.href='../Email/recuperar.php?accion=registro';</script>";
        exit; // Detener la ejecución del script
    }

    // Continuar si el correo electrónico tiene un formato válido
    $db = new Database();
    $pdo = $db->conectar();

    // Verificar si el correo electrónico existe en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Generar una nueva contraseña aleatoria
        $nueva_contraseña = bin2hex(random_bytes(16));

        // Encriptar la nueva contraseña
        $contraseña_encriptada = password_hash($nueva_contraseña, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $sql = "UPDATE usuarios SET contraseña = :contrasena WHERE correo = :correo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':contrasena', $contraseña_encriptada);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        // Enviar la nueva contraseña por correo electrónico
        $mensaje = "Estimado usuario,\r\n\r\nHemos recibido su solicitud de recuperación de contraseña. Su nueva contraseña ha sido generada exitosamente.\r\n\r\nNueva contraseña: $nueva_contraseña\r\n\r\nPor favor, le recomendamos cambiar esta contraseña por una que le resulte más segura una vez que haya iniciado sesión en su cuenta.\r\n\r\nAtentamente,\r\nEl equipo de soporte técnico";
        $asunto = "Recuperación de Contraseña - Valorant";
        $headers = "From: Valorant Support <tecnelectrics@gmail.com>\r\n";
        if (mail($correo, $asunto, $mensaje, $headers)) {
            echo "<script>alert('Se ha enviado una nueva contraseña al correo electrónico proporcionado.'); window.location.href='../login.php?accion=registro';</script>";
        } else {
            echo "<script>alert('Error al enviar el correo electrónico. Por favor, inténtelo de nuevo más tarde.'); window.location.href='../Email/recuperar.php?accion=registro';</script>";
        }
    } else {
        echo "<script>alert('No se encontró ninguna cuenta asociada a este correo electrónico.'); window.location.href='../Email/recuperar.php?accion=registro';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <title>Valorant - Rqecuperar Contraseña</title>
    <link rel="stylesheet" href="../Assets/css/login1.css">
</head>

<body>
    <!-- Inicio opciones -->
    <div id="opciones">
        <a href="../index.php" class="volver-link">
            <i class="fas fa-sign-out-alt"></i>
        </a>
        <button id="pauseButton" class="audio">
            <i id="audioIcon" class="fas fa-volume-up" onclick="pausarAudio()"></i>
        </button>
        <audio id="miAudio" autoplay loop>
            <source src="../Assets/audio/carga.mp3" type="audio/mpeg">
        </audio>

    </div>

    <!-- Inicio Login -->
    <div class="login">
        <div class="header">
            <div class="center">
                <img src="../Assets/img/logo.png" alt="Logo Valorant">
                <div class="clear"></div>
            </div>
            <!-- Inicio Form recuperar -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="center">
                    <div class="form-login">
                        <h2>Recupera Tu Contraseña</h2>
                        <input type="text" id="correo" name="correo" placeholder="Correo electronico" required>
                        <span><input type="checkbox" id="continuar" name="continuar"><label
                                for="continuar">Recordar</label></span>
                        <div class="button">
                            <button class="button">
                                <a href="" value="Recuperar Contraseña"><i
                                        class="fas fa-arrow-alt-circle-right"></i></a>
                            </button>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
        <!-- Inicio Footer -->
        <footer>
            <div class="settings">
                <a href="../login.php">
                    <p>Iniciar Sesion</p>
                </a>
                <a href="../registro.php">
                    <p>Crea una Cuenta?</p>
                </a>
            </div>
        </footer>
    </div>
    <!-- Bnner -->
    <div class="banner" name="Banner"></div>
</body>
<!-- audios -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var miAudio = document.getElementById("miAudio");
        var audioIcon = document.getElementById("audioIcon");

        setTimeout(function () {
            miAudio.play();
            miAudio.volume = miAudio.volume * 0.5; // Establecer el volumen 
        }, 500); // Retraso de 0.5 segundos (500 milisegundos)

        audioIcon.addEventListener("click", function () {
            if (miAudio.paused) {
                miAudio.play();
                audioIcon.classList.remove("fa-volume-off");
                audioIcon.classList.add("fa-volume-up");
            } else {
                miAudio.pause();
                audioIcon.classList.remove("fa-volume-up");
                audioIcon.classList.add("fa-volume-off");
            }
        });
    });

</script>

</html>