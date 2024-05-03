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
    $id_agente = $_POST['avatar'];
    $puntos_rango = $_POST['puntos_rango'];
    $id_estado = $_POST['estado'];
    $id_rol = $_POST['rol'];
    $id_rango = $_POST['rango'];

    // Verificar si se proporcionó una nueva contraseña
    if (!empty($_POST['contraseña'])) {
        $password = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); 
        $update_password = true;
    } else {
        // No se proporcionó una nueva contraseña
        $update_password = false;
    }

    try {
        $db = new Database();
        $conn = $db->conectar();

        // Construir la consulta de actualización
        if ($update_password) {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, contraseña = ?, puntos_rango = ?, id_agente = ?, id_estado = ?, id_rol = ?, id_rango = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre, $correo, $password, $puntos_rango, $id_agente, $id_estado, $id_rol, $id_rango, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, correo = ?, puntos_rango = ?, id_agente = ?, id_estado = ?, id_rol = ?, id_rango = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre, $correo, $puntos_rango, $id_agente, $id_estado, $id_rol, $id_rango, $id]);
        }

        echo "<script>alert('Usuario actualizado correctamente'); window.location='../visualizar/jugadores.php';</script>";
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Obtener datos del usuario a actualizar
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $db = new Database();
        $conn = $db->conectar();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Obtener datos de agentes para el select
        $stmt = $conn->query("SELECT id_agente, nombre, foto FROM agentes");
        $agentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener datos de estados para el select
        $stmt = $conn->query("SELECT id_estado, nombre FROM estado");
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener datos de roles para el select
        $stmt = $conn->query("SELECT id_rol, nombre FROM roles");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener datos de rango para el select
        $stmt = $conn->query("SELECT id_rango, nombre, foto FROM rango");
        $rangos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID de usuario no proporcionado'); window.location='../visualizar/jugadores.php';</script>";
    exit();
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Actualizar Usuario</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $usuario['id_usuario']; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña">
                </div>
                
                <div class="form-group">
                    <label for="puntos_rango">Puntos de rango:</label>
                    <input type="number" class="form-control" id="puntos_rango" name="puntos_rango" value="<?php echo $usuario['puntos_rango']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="rol" class="form-control" required>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?php echo $rol['id_rol']; ?>" <?php if ($rol['id_rol'] == $usuario['id_rol']) echo 'selected'; ?>><?php echo $rol['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" class="form-control" required>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?php echo $estado['id_estado']; ?>" <?php if ($estado['id_estado'] == $usuario['id_estado']) echo 'selected'; ?>><?php echo $estado['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="avatar">Agente:</label>
                    <select id="avatar" name="avatar" class="form-control" required onchange="mostrarImagenAgente()">
                        <?php foreach ($agentes as $agente): ?>
                            <option value="<?php echo $agente['id_agente']; ?>" data-foto="<?php echo base64_encode($agente['foto']); ?>" <?php if ($agente['id_agente'] == $usuario['id_agente']) echo 'selected'; ?>><?php echo $agente['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="fotoAgente">
                    <?php if (!empty($usuario['id_agente'])): ?>
                        <label>Imagen actual:</label>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($usuario['foto']); ?>" alt="Imagen actual" width="200" height="200">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="rango">Rango:</label>
                    <select id="rango" name="rango" class="form-control" required onchange="mostrarImagenRango()">
                        <?php foreach ($rangos as $rango): ?>
                            <option value="<?php echo $rango['id_rango']; ?>" data-foto="<?php echo base64_encode($rango['foto']); ?>" <?php if ($rango['id_rango'] == $usuario['id_rango']) echo 'selected'; ?>><?php echo $rango['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="fotoRango">
                    <?php if (!empty($usuario['id_rango'])): ?>
                        <label>Imagen actual:</label>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($usuario['foto']); ?>" alt="Imagen actual" width="200" height="200">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </form>
        </div>
    </div>
</div>

<script>
    function mostrarImagenAgente() {
        var avatarSelect = document.getElementById("avatar");
        var fotoAgente = document.getElementById("fotoAgente");
        var fotoData = avatarSelect.options[avatarSelect.selectedIndex].getAttribute("data-foto");

        // Limpiar contenido anterior
        fotoAgente.innerHTML = '';

        // Si no hay agente seleccionado, salir
        if (!fotoData) return;

        // Crear imagen y establecer atributos
        var img = document.createElement("img");
        img.src = 'data:image/jpeg;base64,' + fotoData;
        img.alt = avatarSelect.options[avatarSelect.selectedIndex].text;
        img.width = 200; // Tamaño deseado de la imagen
        img.height = 200;

        // Agregar imagen al div
        fotoAgente.appendChild(img);
    }

    function mostrarImagenRango() {
        var rangoSelect = document.getElementById("rango");
        var fotoRango = document.getElementById("fotoRango");
        var fotoData = rangoSelect.options[rangoSelect.selectedIndex].getAttribute("data-foto");

        // Limpiar contenido anterior
        fotoRango.innerHTML = '';

        // Si no hay rango seleccionado, salir
        if (!fotoData) return;

        // Crear imagen y establecer atributos
        var img = document.createElement("img");
        img.src = 'data:image/jpeg;base64,' + fotoData;
        img.alt = rangoSelect.options[rangoSelect.selectedIndex].text;
        img.width = 200; // Tamaño deseado de la imagen
        img.height = 200;

        // Agregar imagen al div
        fotoRango.appendChild(img);
    }

    // Mostrar la imagen actual al cargar la página
    mostrarImagenAgente();
    mostrarImagenRango();
</script>

<?php include "../template/footer.php"; ?>
