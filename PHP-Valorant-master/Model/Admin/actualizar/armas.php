<?php include "../template/header.php"; ?>

<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_arma']) && !empty($_POST['id_arma'])) {
    // Sanitizar los datos del formulario para evitar inyección de SQL
    $id_arma = filter_var($_POST['id_arma'], FILTER_SANITIZE_NUMBER_INT);
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);

    // Procesar la imagen si se ha subido
    if ($_FILES['foto']['size'] > 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    } else {
        // Si no se ha subido una imagen, mantener la imagen existente
        $query_imagen = "SELECT foto FROM armas WHERE id_arma = ?";
        $stmt_imagen = $con->prepare($query_imagen);
        $stmt_imagen->bindParam(1, $id_arma, PDO::PARAM_INT);
        $stmt_imagen->execute();
        $foto = $stmt_imagen->fetchColumn();
    }

    $balas = $_POST['balas'];
    $daño = $_POST['daño'];
    $id_rango = $_POST['id_rango']; // Nuevo campo
    $id_tipo_arma = $_POST['id_tipo_arma']; // Nuevo campo

    // Consulta SQL para actualizar el registro con el ID especificado
    $query = "UPDATE armas SET nombre = ?, foto = ?, balas = ?, daño = ?, id_rango = ?, tipo_arma = ? WHERE id_arma = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $foto, PDO::PARAM_LOB);
    $stmt->bindParam(3, $balas, PDO::PARAM_INT);
    $stmt->bindParam(4, $daño, PDO::PARAM_INT);
    $stmt->bindParam(5, $id_rango, PDO::PARAM_INT);
    $stmt->bindParam(6, $id_tipo_arma, PDO::PARAM_INT);
    $stmt->bindParam(7, $id_arma, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Arma actualizado correctamente');</script>";
        // Redireccionar a la página actual después de actualizar el registro
        echo "<script>window.location.href = '../visualizar/armas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al actualizar el arma');</script>";
    }
}

// Obtener el ID del arma a actualizar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_arma = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para seleccionar los datos del arma con el ID especificado
    $query = "SELECT * FROM armas WHERE id_arma = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id_arma, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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

    // Verificar si se encontró un registro con el ID especificado
    if (!$row) {
        // Si no se encuentra un registro, redireccionar a alguna página de gestión de errores
        header("Location: ../visualizar/armas.php");
        exit();
    }
} else {
    // Si no se proporciona un ID, redireccionar a alguna página de gestión de errores
    header("Location: ../visualizar/armas.php");
    exit();
}
?>

<!-- Formulario de actualización -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Actualizar Arma</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_arma" value="<?php echo $row['id_arma']; ?>">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" required>
                </div>
                <!-- Visualizar la imagen actual -->
                <div class="form-group">
                    <label>Imagen Actual:</label><br>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['foto']); ?>" width="100" height="100" alt="Imagen actual">
                </div>
                <div class="form-group">
                    <label for="foto">Actualizar Foto:</label>
                    <input type="file" class="form-control-file" id="foto" name="foto">
                </div>
                <div class="form-group">
                    <label for="balas">Balas:</label>
                    <input type="number" class="form-control" id="balas" name="balas" value="<?php echo $row['balas']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="daño">Daño:</label>
                    <input type="number" class="form-control" id="daño" name="daño" value="<?php echo $row['daño']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_rango">Rango:</label>
                    <select class="form-control" id="id_rango" name="id_rango" required>
                        <option value="">Seleccione un rango</option>
                        <?php foreach ($rangos as $rango): ?>
                            <option value="<?php echo $rango['id_rango']; ?>" <?php echo ($rango['id_rango'] == $row['id_rango']) ? 'selected' : ''; ?>>
                                <?php echo $rango['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_tipo_arma">Tipo de Arma:</label>
                    <select class="form-control" id="id_tipo_arma" name="id_tipo_arma" required>
                        <option value="">Seleccione un tipo de arma</option>
                        <?php foreach ($tipos_arma as $tipo_arma): ?>
                            <option value="<?php echo $tipo_arma['id_tp_arma']; ?>" <?php echo ($tipo_arma['id_tp_arma'] == $row['tipo_arma']) ? 'selected' : ''; ?>>
                                <?php echo $tipo_arma['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../template/footer.php"; ?>
