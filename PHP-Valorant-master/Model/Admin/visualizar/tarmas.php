<?php include "../template/header.php"; ?>

<?php
    require_once("../../../Config/conexion.php");
    $DataBase = new Database;
    $con = $DataBase->conectar();

    // Consulta SQL para seleccionar los datos de la tabla de tipo armas
    $query = "SELECT * FROM tipo_arma";
    $stmt = $con->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h2 class="text-center">Tipo de Armas</h2>
    <div class="table-responsive text-center">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID_Tipo_Arma</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>{$row['id_tp_arma']}</td>";
                    echo "<td>{$row['NOMBRE']}</td>";
                    echo "<td>
                        <div class='text-center'>
                            <a href='../actualizar/tarmas.php?id={$row['id_tp_arma']}' class='btn btn-primary btn-sm'>Editar</a>
                            <a href='../eliminar/tarmas.php?id={$row['id_tp_arma']}' class='btn btn-danger btn-sm'>Eliminar</a>
                        </div>
                    </td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>
    </div>

<?php include "../template/footer.php"; ?>