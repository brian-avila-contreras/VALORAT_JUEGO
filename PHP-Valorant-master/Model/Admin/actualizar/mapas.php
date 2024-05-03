<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_mapa'])) {
    // Obtener los datos del formulario
    $id_mapa = $_POST['id_mapa'];
    $nombre = $_POST['nombre'];
    $id_rango = $_POST['id_rango'];

    // Verificar si se ha enviado una nueva foto
    if ($_FILES['foto']['size'] > 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        $query_update = "UPDATE mapa SET nombre = :nombre, foto = :foto, id_rango = :id_rango WHERE id_mapa = :id_mapa";
    } else {
        $query_update = "UPDATE mapa SET nombre = :nombre, id_rango = :id_rango WHERE id_mapa = :id_mapa";
    }

    // Preparar la consulta de actualización
    $stmt_update = $con->prepare($query_update);
    $stmt_update->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt_update->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);
    $stmt_update->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);

    // Si se ha enviado una nueva foto, bindear el parámetro correspondiente
    if (isset($foto)) {
        $stmt_update->bindParam(':foto', $foto, PDO::PARAM_LOB);
    }

    // Ejecutar la consulta de actualización
    if ($stmt_update->execute()) {
        echo "<script>alert('Mapa actualizado exitosamente');</script>";
        echo "<script>window.location='../visualizar/mapas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar el mapa');</script>";
    }
}

// Obtener el ID del mapa de la URL
$id_mapa = $_GET['id'] ?? null;

// Verificar si se ha proporcionado un ID válido
if (!$id_mapa) {
    echo "<script>alert('ID de mapa no válido');</script>";
    echo "<script>window.location='../visualizar/mapas.php';</script>";
    exit();
}

// Consulta SQL para obtener los datos del mapa
$query_mapa = "SELECT * FROM mapa WHERE id_mapa = :id_mapa";
$stmt_mapa = $con->prepare($query_mapa);
$stmt_mapa->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
$stmt_mapa->execute();
$mapa = $stmt_mapa->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró un mapa con el ID proporcionado
if (!$mapa) {
    echo "<script>alert('No se encontró un mapa con el ID proporcionado');</script>";
    echo "<script>window.location='../visualizar/mapas.php';</script>";
    exit();
}

// Consulta SQL para obtener los datos de la tabla rango
$query_rango = "SELECT id_rango, nombre FROM rango";
$stmt_rango = $con->prepare($query_rango);
$stmt_rango->execute();
$rangos = $stmt_rango->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Aquí empieza tu estructura -->
<?php include "../template/header.php"; ?>
<div class="container mt-5">
    <h2 class="text-center">Actualizar Mapa</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_mapa" value="<?php echo $mapa['id_mapa']; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $mapa['nombre']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto">
                    <?php if ($mapa['foto']) { ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($mapa['foto']); ?>" alt="Foto del mapa" style="max-width: 100px;">
                    <?php } ?>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
