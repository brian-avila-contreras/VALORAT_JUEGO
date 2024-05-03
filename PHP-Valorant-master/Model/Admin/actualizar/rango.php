<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    // Sanitizar los datos del formulario para evitar inyección de SQL
    $id_rango = filter_var($_POST['id_rango'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);

    // Verificar si el ID ya está en uso
    $sql_check_id = "SELECT COUNT(*) FROM rango WHERE id_rango = :id_rango AND id_rango != :id";
    $stmt_check_id = $con->prepare($sql_check_id);
    $stmt_check_id->execute(array(':id_rango' => $id_rango, ':id' => $_POST['id']));
    $id_exists = $stmt_check_id->fetchColumn();

    if ($id_exists) {
        echo "<script>alert('El ID ya está en uso. Por favor, elija otro ID.');</script>";
        echo "<script>window.location.href = '../visualizar/rango.php';</script>";
        exit();
    }

    // Procesar la imagen si se ha subido
    if ($_FILES['imagen']['size'] > 0) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    } else {
        // Si no se ha subido una imagen, mantener la imagen existente
        $query_imagen = "SELECT foto FROM rango WHERE id_rango = :id_rango";
        $stmt_imagen = $con->prepare($query_imagen);
        $stmt_imagen->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);
        $stmt_imagen->execute();
        $imagen = $stmt_imagen->fetchColumn();
    }

    // Consulta SQL para actualizar el registro con el ID especificado
    $query = "UPDATE rango SET id_rango = :id_rango, nombre = :nombre, foto = :imagen WHERE id_rango = :id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);
    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Registro actualizado correctamente');</script>";
        // Redireccionar a la página actual después de actualizar el registro
        echo "<script>window.location.href = '../visualizar/rango.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar el registro');</script>";
    }
}

// Obtener el ID del rango a actualizar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_rango = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para seleccionar los datos del rango con el ID especificado
    $query = "SELECT * FROM rango WHERE id_rango = :id_rango";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un ID, redireccionar a alguna página de gestión de errores
    header("Location: error.php");
    exit();
}
?>

<!-- Formulario de actualización -->
<?php include "../template/header.php"; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Actualizar Rangos</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id_rango']; ?>">
                <div class="form-group">
                    <label for="id_rango">ID Rango:</label>
                    <input type="number" class="form-control" id="id_rango" name="id_rango" value="<?php echo $row['id_rango']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>">
                </div>
                <!-- Visualizar la imagen actual -->
                <div class="form-group">
                    <label>Imagen Actual:</label><br>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto']); ?>" width="100" height="100" alt="Imagen actual">
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" class="form-control-file" id="imagen" name="imagen">
                </div>

                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                </div>
            </form>
        </div>
    </div>
</div>
<?php include "../template/footer.php"; ?>
