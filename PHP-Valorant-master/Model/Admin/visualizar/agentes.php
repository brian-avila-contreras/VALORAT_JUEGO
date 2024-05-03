<?php include "../template/header.php"; ?>
<?php
require_once ("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para seleccionar los datos de la tabla agentes
$query = "SELECT * FROM agentes";
$stmt = $con->query($query);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="text-center">Tabla de Agentes</h2>
<div class="table-responsive text-center">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Agente</th>
                <th>Nombre</th>
                <th>Foto</th>
                <th>Tarjeta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>{$row['id_agente']}</td>";
                echo "<td>{$row['nombre']}</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto']) . "' width='100' height='100'></td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['tarjeta']) . "' width='100' height='100'></td>";
                echo "<td>
                            <div class='text-center'>
                                <a href='../actualizar/agentes.php?id={$row['id_agente']}' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='../eliminar/agentes.php?id={$row['id_agente']}' class='btn btn-danger btn-sm'>Eliminar</a>
                            </div>
                        </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include "../template/footer.php"; ?>