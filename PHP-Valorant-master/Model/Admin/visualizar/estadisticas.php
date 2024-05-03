<?php include "../template/header.php"; ?>

<?php
require_once ("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para obtener los datos de la tabla detalles_usuarios
$query = "SELECT du.id_detalle, du.daño_realizado, u1.nombre as nombre_atacante, u2.nombre as nombre_atacado, du.fecha, 
                a.foto as foto_arma, m.foto as foto_mapa
        FROM detalles_usuarios du 
        LEFT JOIN usuarios u1 ON du.id_jugador_atacante = u1.id_usuario
        LEFT JOIN usuarios u2 ON du.id_jugador_atacado = u2.id_usuario
        LEFT JOIN armas a ON du.id_arma = a.id_arma
        LEFT JOIN mapa m ON du.id_mapa = m.id_mapa";
$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="text-center">Detalles de Ataques</h2>
<div class="table-responsive text-center">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Detalle</th>
                <th>Daño Realizado</th>
                <th>Jugador Atacante</th>
                <th>Jugador Atacado</th>
                <th>Fecha</th>
                <th>Arma</th>
                <th>Mapa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>{$row['id_detalle']}</td>";
                echo "<td>{$row['daño_realizado']}</td>";
                echo "<td>{$row['nombre_atacante']}</td>";
                echo "<td>{$row['nombre_atacado']}</td>";
                echo "<td>{$row['fecha']}</td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto_arma']) . "' width='100' height='100'></td>";
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto_mapa']) . "' width='100' height='100'></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include "../template/footer.php"; ?>