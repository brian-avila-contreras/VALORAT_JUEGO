<?php include "../template/header.php"; ?>

<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

$query = "SELECT * FROM posiciones";
$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="text-center">Tabla de Posiciones</h2>
<div class="table-responsive text-center">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Posición</th>
                <th>Jugador 1</th>
                <th>Jugador 2</th>
                <th>Jugador 3</th>
                <th>Jugador 4</th>
                <th>Jugador 5</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?php echo $row['id_posicion']; ?></td>
                    <td><?php echo $row['id_jugador1']; ?></td>
                    <td><?php echo $row['id_jugador2']; ?></td>
                    <td><?php echo $row['id_jugador3']; ?></td>
                    <td><?php echo $row['id_jugador4']; ?></td>
                    <td><?php echo $row['id_jugador5']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../template/footer.php"; ?>
