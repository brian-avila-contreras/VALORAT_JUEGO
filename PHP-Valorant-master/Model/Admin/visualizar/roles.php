<?php include "../template/header.php"; ?>

<?php
    require_once("../../../Config/conexion.php");
    $DataBase = new Database;
    $con = $DataBase->conectar();

    // Consulta SQL para seleccionar los datos de la tabla de roles
    $query = "SELECT * FROM roles";
    $stmt = $con->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h2 class="text-center">Tabla de Jugadores</h2>
    <div class="table-responsive text-center">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID Rol</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>{$row['id_rol']}</td>";
                    echo "<td>{$row['nombre']}</td>";
                    echo "<td>
                        <div class='text-center'>
                            <a href='../actualizar/roles.php?id={$row['id_rol']}' class='btn btn-primary btn-sm'>Editar</a>
                            <a href='../eliminar/roles.php?id={$row['id_rol']}' class='btn btn-danger btn-sm'>Eliminar</a>
                        </div>
                    </td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>
    </div>

<?php include "../template/footer.php"; ?>