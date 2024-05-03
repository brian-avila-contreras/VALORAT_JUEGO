<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    // Sanitizar los datos del formulario para evitar inyección de SQL
    $id_tp_arma = filter_var($_POST['id_tp_arma'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);

    // Verificar si ya existe otro tipo de arma con el mismo id_tp_arma
    $sql_check_id = "SELECT COUNT(*) FROM tipo_arma WHERE id_tp_arma = :id_tp_arma AND id_tp_arma != :id";
    $stmt_check_id = $con->prepare($sql_check_id);
    $stmt_check_id->execute(array(':id_tp_arma' => $id_tp_arma, ':id' => $_POST['id']));
    $id_exists = $stmt_check_id->fetchColumn();

    if ($id_exists) {
        echo "<script>alert('Ya existe otro tipo de arma con ese ID. Por favor, elige otro número de ID.'); window.location='../visualizar/tarmas.php';</script>";
        exit();
    }

    // Consulta SQL para actualizar el registro con el ID especificado
    $query = "UPDATE tipo_arma SET id_tp_arma = :id_tp_arma, NOMBRE = :nombre WHERE id_tp_arma = :id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_tp_arma', $id_tp_arma, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Tipo de arma actualizado correctamente');</script>";
        // Redireccionar a la página de visualización de tipos de arma después de actualizar el registro
        echo "<script>window.location.href = '../visualizar/tarmas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar el tipo de arma');</script>";
    }
}

// Obtener el ID del tipo de arma a actualizar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_tp_arma = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para seleccionar los datos del tipo de arma con el ID especificado
    $query = "SELECT * FROM tipo_arma WHERE id_tp_arma = :id_tp_arma";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_tp_arma', $id_tp_arma, PDO::PARAM_INT);
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
            <h2 class="text-center">Actualizar Tipo de Arma</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id_tp_arma']; ?>">
                <div class="form-group">
                    <label for="id_tp_arma">ID Tipo de Arma:</label>
                    <input type="number" class="form-control" id="id_tp_arma" name="id_tp_arma" value="<?php echo $row['id_tp_arma']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $row['NOMBRE']; ?>">
                </div>

                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
