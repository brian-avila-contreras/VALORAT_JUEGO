<?php include "../template/header.php"; ?>
<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];

    // Consulta SQL para insertar un nuevo estado en la tabla
    $sql = "INSERT INTO estado (nombre) VALUES (:nombre)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Estado creado');</script>";
        // Redireccionar a la página de visualización de estados
        echo "<script>window.location='../visualizar/estado.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al crear el estado');</script>";
    }
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Crear Estado</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Agregar">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
