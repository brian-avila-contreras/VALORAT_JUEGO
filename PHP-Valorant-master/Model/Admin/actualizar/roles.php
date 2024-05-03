<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    // Sanitizar los datos del formulario para evitar inyección de SQL
    $id_rol = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);

    // Consulta SQL para actualizar el registro con el ID especificado
    $query = "UPDATE roles SET nombre = :nombre WHERE id_rol = :id_rol";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Rol actualizado correctamente');</script>";
        // Redireccionar a la página de visualización de roles después de actualizar el registro
        echo "<script>window.location.href = '../visualizar/roles.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar el rol');</script>";
    }
}

// Obtener el ID del rol a actualizar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_rol = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para seleccionar los datos del rol con el ID especificado
    $query = "SELECT * FROM roles WHERE id_rol = :id_rol";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
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
            <h2 class="text-center">Actualizar Rol</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id_rol']; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>">
                </div>

                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
