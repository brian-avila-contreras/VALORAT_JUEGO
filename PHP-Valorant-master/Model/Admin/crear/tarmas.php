<?php include "../template/header.php"; ?>
<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tp_arma = $_POST["id_tp_arma"];
    $nombre = $_POST["nombre"];

    // Verificar si ya existe un tipo de arma con el mismo id_tp_arma
    $sql_check_id = "SELECT COUNT(*) FROM tipo_arma WHERE id_tp_arma = :id_tp_arma";
    $stmt_check_id = $con->prepare($sql_check_id);
    $stmt_check_id->execute(array(':id_tp_arma' => $id_tp_arma));
    $id_exists = $stmt_check_id->fetchColumn();

    if ($id_exists) {
        echo "<script>alert('Ya existe un tipo de arma con ese ID. Por favor, elige otro número de ID.'); window.location='../visualizar/tarmas.php';</script>";
        exit();
    }

    // Insertar el nuevo tipo de arma si el id_tp_arma no existe aún
    $sql = "INSERT INTO tipo_arma (id_tp_arma, NOMBRE) VALUES (:id_tp_arma, :nombre)";
    $stmt = $con->prepare($sql);

    $stmt->execute(array(':id_tp_arma' => $id_tp_arma, ':nombre' => $nombre));

    echo "<script>alert('Tipo de arma creado.'); window.location='../visualizar/tarmas.php';</script>";
    exit();
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Crear Tipo de Arma</h2>
            <form method="post">
                <div class="form-group">
                    <label for="id_tp_arma">ID Tipo de Arma:</label>
                    <input type="number" class="form-control" name="id_tp_arma" required>
                </div>
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
