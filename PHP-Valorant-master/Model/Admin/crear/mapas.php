
<?php include "../template/header.php"; ?>
<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para obtener los datos de los usuarios incluyendo el id_rango
$query_usuarios = "SELECT id_usuario, nombre, id_rango FROM usuarios WHERE id_estado = 1 AND id_rol = 2";
$stmt_usuarios = $con->prepare($query_usuarios);
$stmt_usuarios->execute();
$usuarios = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

// Consulta SQL para obtener los datos de la tabla rango
$query_rango = "SELECT id_rango, nombre, foto FROM rango";
$stmt_rango = $con->prepare($query_rango);
$stmt_rango->execute();
$rangos = $stmt_rango->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se ha enviado un formulario para crear un mapa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $foto = file_get_contents($_FILES['foto']['tmp_name']); // Obtener el contenido binario del archivo
    $id_rango = $_POST['id_rango'];

    // Insertar un nuevo mapa en la base de datos
    $query_insertar_mapa = "INSERT INTO mapa (nombre, foto, jugador1_id, jugador2_id, jugador3_id, jugador4_id, jugador5_id, id_rango) 
                            VALUES (:nombre, :foto, :jugador1_id, :jugador2_id, :jugador3_id, :jugador4_id, :jugador5_id, :id_rango)";
    $stmt_insertar_mapa = $con->prepare($query_insertar_mapa);
    $stmt_insertar_mapa->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt_insertar_mapa->bindParam(':foto', $foto, PDO::PARAM_LOB); // Usar PARAM_LOB para datos de tipo longblob

    // Definir los jugadores como NULL si no se proporcionan en el formulario
    $jugadores = array('jugador1_id', 'jugador2_id', 'jugador3_id', 'jugador4_id', 'jugador5_id');
    foreach ($jugadores as $jugador) {
        if (isset($_POST[$jugador]) && !empty($_POST[$jugador])) {
            $stmt_insertar_mapa->bindParam(":$jugador", $_POST[$jugador], PDO::PARAM_INT);
        } else {
            $stmt_insertar_mapa->bindValue(":$jugador", NULL, PDO::PARAM_NULL);
        }
    }
    $stmt_insertar_mapa->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);

    // Ejecutar la consulta de inserción del mapa
    if ($stmt_insertar_mapa->execute()) {
        // Obtener el id del mapa recién insertado
        $id_mapa = $con->lastInsertId();

        // Consulta para obtener el id_rango del mapa insertado
        $query_mapa_rango = "SELECT id_rango FROM mapa WHERE id_mapa = :id_mapa";
        $stmt_mapa_rango = $con->prepare($query_mapa_rango);
        $stmt_mapa_rango->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
        $stmt_mapa_rango->execute();
        $mapa_rango = $stmt_mapa_rango->fetch(PDO::FETCH_ASSOC);

        // Verificar los jugadores seleccionados y eliminar los mapas con jugadores que tienen id_rango diferente
        foreach ($jugadores as $jugador) {
            if (!empty($_POST[$jugador])) {
                $id_jugador = $_POST[$jugador];

                // Consulta para obtener el id_rango del jugador
                $query_jugador_rango = "SELECT id_rango FROM usuarios WHERE id_usuario = :id_jugador";
                $stmt_jugador_rango = $con->prepare($query_jugador_rango);
                $stmt_jugador_rango->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
                $stmt_jugador_rango->execute();
                $jugador_rango = $stmt_jugador_rango->fetch(PDO::FETCH_ASSOC);

                // Si el id_rango del jugador es diferente al id_rango del mapa, eliminar el mapa
                if ($jugador_rango['id_rango'] != $mapa_rango['id_rango']) {
                    $query_eliminar_mapa = "DELETE FROM mapa WHERE id_mapa = :id_mapa";
                    $stmt_eliminar_mapa = $con->prepare($query_eliminar_mapa);
                    $stmt_eliminar_mapa->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
                    $stmt_eliminar_mapa->execute();
                    
                    // Mostrar mensaje de error
                    echo "<script>alert('Error: Uno de los jugadores seleccionados tiene un rango diferente al del mapa. El mapa ha sido eliminado.');</script>";
                    
                    // Redireccionar a la página de creación de mapa
                    echo "<script>alert('revisa el numero que tienen el nombre del jugador. este debe considir con rango que seleccionas anterior mente.'); window.location='../crear/mapas.php';</script>";
                    exit();
                }
            }
        }

        // Si no hay jugadores con id_rango diferente, redirigir a la página de visualización de mapas
        echo "<script>alert('Creado Exitosamente Un Nuevo Mapa'); window.location='../visualizar/mapas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al crear el mapa');</script>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Crear Mapa</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                    <label for="id_rango">ID de Rango:</label>
                    <select class="form-control" id="id_rango" name="id_rango" onchange="habilitarJugadores()" required>
                        <option value="">Seleccionar ID de Rango</option>
                        <?php foreach ($rangos as $rango) { ?>
                            <option value="<?php echo $rango['id_rango']; ?>"><?php echo $rango['id_rango'] . ' - ' . $rango['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php for ($i = 1; $i <= 5; $i++) { ?>
    <div class="form-group">
        <label for="jugador<?php echo $i; ?>_id">ID Jugador <?php echo $i; ?>:</label>
        <select class="form-control" id="jugador<?php echo $i; ?>_id" name="jugador<?php echo $i; ?>_id" disabled>
            <option value="">Seleccionar ID de Jugador <?php echo $i; ?></option>
            <?php foreach ($usuarios as $usuario) { ?>
                <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['id_usuario'] . ' - ' . $usuario['nombre'] . ' - ' . $usuario['id_rango']; ?></option>
            <?php } ?>
        </select>
    </div>
<?php } ?>

                <button type="submit" class="btn btn-primary">Crear</button>
            </form>
        </div>
    </div>
</div>

<script>
    function habilitarJugadores() {
        var rangoSeleccionado = document.getElementById("id_rango").value;
        var jugadores = document.querySelectorAll('[id^="jugador"]');
        if (rangoSeleccionado !== "") {
            for (var i = 0; i < jugadores.length; i++) {
                jugadores[i].removeAttribute("disabled");
            }
        } else {
            for (var i = 0; i < jugadores.length; i++) {
                jugadores[i].disabled = true;
            }
        }
    }
</script>
<?php include "../template/footer.php"; ?>