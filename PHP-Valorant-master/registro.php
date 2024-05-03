<?php
session_start();
require_once("Config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $id_agente = $_POST['avatar'];
    $puntos_rango = 0;
    $puntos_salud = 100;
    $id_rango = 1;
    $id_estado = 2;
    $id_rol = 2;
    $ultima_conexion = date('Y-m-d H:i:s');

    try {
        $db = new Database();
        $conn = $db->conectar();

        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $id_count = $stmt->fetchColumn();

        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $user_count = $stmt->fetchColumn();

        // Verificar si el correo electrónico ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $email_count = $stmt->fetchColumn();

        if ($id_count > 0) {
            echo "<script>alert('El id del usuario ya se encuentra registrado'); window.location='registro.php';</script>";
        } elseif ($user_count > 0) {
            echo "<script>alert('El nombre del usuario ya esta en uso'); window.location='registro.php';</script>";
        } elseif ($email_count > 0) {
            echo "<script>alert('El correo electronico ya se encuentra en uso'); window.location='registro.php';</script>";
        } else {
            // Insertar usuario si no existe ni el nombre de usuario ni el correo electrónico
            $stmt = $conn->prepare("INSERT INTO usuarios (id_usuario, nombre, correo, contraseña, puntos_salud, puntos_rango, id_agente, id_estado, id_rol, id_rango, ultima_conexion) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([$id, $nombre, $correo, $password, $puntos_salud, $puntos_rango, $id_agente, $id_estado, $id_rol, $id_rango, $ultima_conexion]);

            echo "<script>alert('Se ha registrado correctamente. Su cuenta será activada en un plazo de 10 a 15 minutos'); window.location='login.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener datos de agentes para el select
try {
    $db = new Database();
    $conn = $db->conectar();
    $stmt = $conn->query("SELECT id_agente, nombre, foto FROM agentes");
    $agentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <link rel="apple-touch-icon" sizes="60x60" href="Assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="Assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="Assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="Assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="Assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="Assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="Assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="Assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="Assets/favicon/manifest.json">

    <title>Valorant - Iniciar Sesión</title>
    <link rel="stylesheet" href="Assets/css/registro2.css">
</head>

<body>
    <!-- Inicio opciones -->
    <div id="opciones">
        <a href="index.php" class="volver-link">
            <i class="fas fa-sign-out-alt"></i>
        </a>
        <button id="pauseButton" class="audio">
            <i id="audioIcon" class="fas fa-volume-up" onclick="pausarAudio()"></i>
        </button>
        <audio id="miAudio" autoplay loop>
            <source src="Assets/audio/carga.mp3" type="audio/mpeg">
        </audio>

    </div>

    <!-- Inicio Login -->
    <div class="login">
        <div class="header">
            <div class="center">

                <div class="clear"></div>
            </div>
            <!-- Inicio Form registro -->

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="center">
                    <div class="form-login">
                        <h2>Registrate</h2>
                        <input type="number" id="id" name="id" placeholder="ID del usuario" required><br><br>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre de Usuario - Nickname" required><br><br>
                        <input type="correo" id="correo" name="correo" placeholder="Correo Electronico" required><br><br>
                        <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" required><br><br>
                        <select id="avatar" name="avatar" required onchange="mostrarImagen()">
                            <option value="">Seleccione un agente</option>
                            <?php foreach ($agentes as $agente) : ?>
                                <option value="<?php echo $agente['id_agente']; ?>"><?php echo $agente['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>



                        <div class="button">
                            <button class="button">
                                <a href=""><i class="fas fa-arrow-alt-circle-right"></i></a>
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
                <a href="login.php">
                    <p>Ya tienes Una cuenta</p>
                </a>
            </div>
        </footer>
    </div>
    <!-- Bnner -->
    <div class="banner" name="Banner">
        <div class="agente" id="agenteSeleccionado">

        </div>
    </div>
</body>
<!-- agentes -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var miAudio = document.getElementById("miAudio");
        var audioIcon = document.getElementById("audioIcon");

        setTimeout(function() {
            miAudio.play();
            miAudio.volume = miAudio.volume * 0.5; // Establecer el volumen 
        }, 500); // Retraso de 0.5 segundos (500 milisegundos)

        audioIcon.addEventListener("click", function() {
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

    function mostrarImagen() {
        var avatarSelect = document.getElementById("avatar");
        var agenteSeleccionado = document.getElementById("agenteSeleccionado");
        var idAgente = avatarSelect.value; // Obtener el ID del agente seleccionado

        // Limpiar contenido anterior
        agenteSeleccionado.innerHTML = '';

        // Buscar el agente seleccionado en la lista de agentes
        <?php foreach ($agentes as $agente) : ?>
            if ('<?php echo $agente['id_agente']; ?>' === idAgente) { // Comprobar si el ID del agente coincide
                // Crear imagen y establecer atributos
                var img = document.createElement("img");
                img.src = 'data:image/jpeg;base64,' + '<?php echo base64_encode($agente["foto"]); ?>'; // Campo foto como BLOB
                img.alt = '<?php echo $agente["nombre"]; ?>';
                img.width = 200; // Tamaño deseado de la imagen
                img.height = 200;

                // Agregar imagen al div
                agenteSeleccionado.appendChild(img);
            }
        <?php endforeach; ?>
    }
</script>

</html>