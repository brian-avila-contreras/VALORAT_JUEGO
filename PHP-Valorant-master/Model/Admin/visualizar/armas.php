<?php include "../template/header.php"; ?>

<?php
require_once ("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para obtener los datos de la tabla de armas
$query_armas = "SELECT a.*, ta.nombre AS nombre_tipo_arma 
                FROM armas a
                INNER JOIN tipo_arma ta ON a.tipo_arma = ta.id_tp_arma";
$stmt_armas = $con->prepare($query_armas);
$stmt_armas->execute();
$armas = $stmt_armas->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="text-center">Tabla de Armas</h2>
<div class="table-responsive text-center">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Foto</th>
                <th>Balas</th>
                <th>Daño</th>
                <th>Foto de Rango</th>
                <th>Tipo de Arma</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($armas as $arma) {
                // Consulta SQL para obtener la foto del rango
                $query_rango = "SELECT foto FROM rango WHERE id_rango = :id_rango";
                $stmt_rango = $con->prepare($query_rango);
                $stmt_rango->bindParam(':id_rango', $arma['id_rango']);
                $stmt_rango->execute();
                $foto_rango = $stmt_rango->fetchColumn();

                echo "<tr>";
                echo "<td>{$arma['id_arma']}</td>";
                echo "<td>{$arma['nombre']}</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($arma['foto']) . "' width='100' height='100'></td>";
                echo "<td>{$arma['balas']}</td>";
                echo "<td>{$arma['daño']}</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($foto_rango) . "' width='100' height='100'></td>"; // Mostrar la foto del rango
                echo "<td>{$arma['nombre_tipo_arma']}</td>";
                echo "<td>
                <div class='text-center'>
                    <a href='../actualizar/armas.php?id={$arma['id_arma']}' class='btn btn-primary btn-sm'>Editar</a>
                    <a href='../eliminar/armas.php?id={$arma['id_arma']}' class='btn btn-danger btn-sm'>Eliminar</a>
                </div>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>

    </table>
</div>

<?php include "../template/footer.php"; ?>
