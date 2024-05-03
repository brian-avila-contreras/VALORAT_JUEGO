<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un ID para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_rango = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para eliminar el registro con el ID especificado
    $query = "DELETE FROM rango WHERE id_rango = :id_rango";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_rango', $id_rango, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Registro eliminado correctamente');</script>";
        echo "<script>window.location.href = '../visualizar/rango.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al eliminar el registros');</script>";
    }
}
?>