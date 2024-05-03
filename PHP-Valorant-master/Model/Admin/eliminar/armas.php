<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un ID para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_arma = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para eliminar el registro con el ID especificado
    $query = "DELETE FROM armas WHERE id_arma = :id_arma";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_arma', $id_arma, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Arma eliminado correctamente');</script>";
        echo "<script>window.location.href = '../visualizar/armas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al eliminar el arma');</script>";
    }
}
?>