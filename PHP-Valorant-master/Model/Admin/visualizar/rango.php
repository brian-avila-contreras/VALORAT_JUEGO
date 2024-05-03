<?php include "../template/header.php"; ?>
<?php
require_once ("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para seleccionar los datos de la tabla rango
$query = "SELECT * FROM rango";
$stmt = $con->query($query);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="text-center">Tabla de Rangos</h2>
<div class="table-responsive text-center">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Rango</th>
                <th>Nombre</th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>{$row['id_rango']}</td>";
                echo "<td>{$row['nombre']}</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto']) . "' width='100' height='100'></td>";
                echo "<td>
                            <div class='text-center'>
                                <a href='../actualizar/rango.php?id={$row['id_rango']}' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='../eliminar/rango.php?id={$row['id_rango']}' class='btn btn-danger btn-sm'>Eliminar</a>
                            </div>
                        </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include "../template/footer.php"; ?>