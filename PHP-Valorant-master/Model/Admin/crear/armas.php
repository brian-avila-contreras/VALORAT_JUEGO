<?php include "../template/header.php"; ?>

<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para obtener los datos de la tabla de rangos
$query_rangos = "SELECT id_rango, nombre FROM rango";
$stmt_rangos = $con->prepare($query_rangos);
$stmt_rangos->execute();
$rangos = $stmt_rangos->fetchAll(PDO::FETCH_ASSOC);

// Consulta SQL para obtener los tipos de arma
$query_tipos_arma = "SELECT id_tp_arma, nombre FROM tipo_arma";
$stmt_tipos_arma = $con->prepare($query_tipos_arma);
$stmt_tipos_arma->execute();
$tipos_arma = $stmt_tipos_arma->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
    $balas = $_POST['balas'];
    $daño = $_POST['daño'];
    $id_rango = $_POST['id_rango'];
    $id_tipo_arma = $_POST['id_tipo_arma']; // Agregado para obtener el tipo de arma

    try {
        $stmt = $con->prepare("INSERT INTO armas (nombre, foto, balas, daño, id_rango, tipo_arma) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $foto, PDO::PARAM_LOB);
        $stmt->bindParam(3, $balas, PDO::PARAM_INT);
        $stmt->bindParam(4, $daño, PDO::PARAM_INT);
        $stmt->bindParam(5, $id_rango, PDO::PARAM_INT);
        $stmt->bindParam(6, $id_tipo_arma, PDO::PARAM_INT); // Agregado para insertar el tipo de arma
        $stmt->execute();

        echo "<script>alert('Arma creada correctamente'); window.location='../visualizar/armas.php';</script>";
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Crear Arma</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" required>
                </div>
                <div class="form-group">
                    <label for="balas">Balas:</label>
                    <input type="number" class="form-control" id="balas" name="balas" required>
                </div>
                <div class="form-group">
                    <label for="daño">Daño:</label>
                    <input type="number" class="form-control" id="daño" name="daño" required>
                </div>
                <div class="form-group">
                    <label for="id_rango">Rango:</label>
                    <select class="form-control" id="id_rango" name="id_rango" required>
                        <option value="">Seleccione un rango</option>
                        <?php foreach ($rangos as $rango): ?>
                            <option value="<?php echo $rango['id_rango']; ?>"><?php echo $rango['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_tipo_arma">Tipo de Arma:</label>
                    <select class="form-control" id="id_tipo_arma" name="id_tipo_arma" required>
                        <option value="">Seleccione un tipo de arma</option>
                        <?php foreach ($tipos_arma as $tipo_arma): ?>
                            <option value="<?php echo $tipo_arma['id_tp_arma']; ?>"><?php echo $tipo_arma['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Crear Arma</button>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
