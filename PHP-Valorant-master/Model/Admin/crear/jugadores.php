<?php include "../template/header.php"; ?>

<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Procesar el formulario cuando se envía
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
            echo "<script>alert('El id del usuario ya se encuentra registrado'); window.location='../crear/jugadores.php';</script>";
        } elseif ($user_count > 0) {
            echo "<script>alert('El nombre del usuario ya esta en uso'); window.location='../crear/jugadores.php';</script>";
        } elseif ($email_count > 0) {
            echo "<script>alert('El correo electronico ya se encuentra en uso'); window.location='../crear/jugadores.php';</script>";
        } else {
            // Insertar usuario si no existe ni el nombre de usuario ni el correo electrónico
            $stmt = $conn->prepare("INSERT INTO usuarios (id_usuario, nombre, correo, contraseña, puntos_salud, puntos_rango, id_agente, id_estado, id_rol, id_rango, ultima_conexion) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([$id, $nombre, $correo, $password, $puntos_salud, $puntos_rango, $id_agente, $id_estado, $id_rol, $id_rango, $ultima_conexion]);

            echo "<script>alert('Se ha registrado correctamente'); window.location='../visualizar/jugadores.php';</script>";
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Crear Usuario</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="id">ID:</label>
                    <input type="text" class="form-control" id="id" name="id" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                </div>
                <div class="form-group">
                    <label for="avatar">Agente:</label>
                    <select id="avatar" name="avatar" class="form-control" required onchange="mostrarImagen()">
                        <option value="">Seleccione un agente</option>
                        <?php foreach ($agentes as $agente): ?>
                            <option value="<?php echo $agente['id_agente']; ?>"><?php echo $agente['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="agenteSeleccionado"></div>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </form>
        </div>
    </div>
</div>

<script>
    function mostrarImagen() {
        var avatarSelect = document.getElementById("avatar");
        var agenteSeleccionado = document.getElementById("agenteSeleccionado");
        var idAgente = avatarSelect.value; // Obtener el ID del agente seleccionado

        // Limpiar contenido anterior
        agenteSeleccionado.innerHTML = '';

        // Buscar el agente seleccionado en la lista de agentes
        <?php foreach ($agentes as $agente): ?>
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

<?php include "../template/footer.php"; ?>