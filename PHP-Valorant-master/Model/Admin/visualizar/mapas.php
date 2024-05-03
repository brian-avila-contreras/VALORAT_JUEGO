<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para obtener los datos de la tabla mapa
$query_mapa = "SELECT * FROM mapa";
$stmt_mapa = $con->prepare($query_mapa);
$stmt_mapa->execute();
$mapas = $stmt_mapa->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Aquí empieza tu estructura -->
<?php include "../template/header.php"; ?>
<div class="container mt-5">
    <h2 class="text-center">Tabla de Mapas</h2>
    <div class="table-responsive text-center">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID Mapa</th>
                    <th>Nombre</th>
                    <th>Foto</th>
                    <th>Jugador 1</th>
                    <th>Jugador 2</th>
                    <th>Jugador 3</th>
                    <th>Jugador 4</th>
                    <th>Jugador 5</th>
                    <th>ID de Rango</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($mapas as $mapa) {
                    echo "<tr>";
                    echo "<td>{$mapa['id_mapa']}</td>";
                    echo "<td>{$mapa['nombre']}</td>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($mapa['foto']) . "' alt='Foto del mapa' style='max-width: 100px;'></td>";
                    echo "<td>{$mapa['jugador1_id']}</td>";
                    echo "<td>{$mapa['jugador2_id']}</td>";
                    echo "<td>{$mapa['jugador3_id']}</td>";
                    echo "<td>{$mapa['jugador4_id']}</td>";
                    echo "<td>{$mapa['jugador5_id']}</td>";
                    echo "<td>{$mapa['id_rango']}</td>";
                    echo "<td>
                        <div class='text-center'>
                            <a href='../actualizar/mapas.php?id={$mapa['id_mapa']}' class='btn btn-primary btn-sm'>Editar</a>
                            <a href='../eliminar/mapas.php?id={$mapa['id_mapa']}' class='btn btn-danger btn-sm'>Eliminar</a>
                        </div>
                    </td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../template/footer.php"; ?>