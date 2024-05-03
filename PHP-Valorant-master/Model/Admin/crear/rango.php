<?php include "../template/header.php"; ?>
<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_rango = $_POST["id_rango"]; // Recibe el id_rango ingresado manualmente
    $nombre = $_POST["nombre"];
    $foto = file_get_contents($_FILES['foto']['tmp_name']);

    // Verificar si el id_rango ya existe
    $sql_check_id = "SELECT COUNT(*) FROM rango WHERE id_rango = :id_rango";
    $stmt_check_id = $con->prepare($sql_check_id);
    $stmt_check_id->execute(array(':id_rango' => $id_rango));
    $id_exists = $stmt_check_id->fetchColumn();

    if ($id_exists) {
        echo "<script>alert('El ID de rango ya existe. Por favor, elige otro.'); window.location='../visualizar/rango.php';</script>";
        exit();
    }

    $sql = "INSERT INTO rango (id_rango, nombre, foto) VALUES (:id_rango, :nombre, :foto)";
    $stmt = $con->prepare($sql);

    $stmt->execute(array(':id_rango' => $id_rango, ':nombre' => $nombre, ':foto' => $foto));

    // Redireccionar a la página actual para actualizar la tabla
    echo "<script>alert('Rango creado.'); window.location='../visualizar/rango.php';</script>";
    exit();
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Crear Rango</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id_rango">ID Rango:</label>
                    <input type="number" class="form-control" name="id_rango" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto:</label>
                    <input type="file" class="form-control-file" name="foto" accept="image/jpeg" required>
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Agregar">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
