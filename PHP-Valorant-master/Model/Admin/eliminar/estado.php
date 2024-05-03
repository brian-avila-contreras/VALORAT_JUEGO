<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un ID para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_estado = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para eliminar el registro con el ID especificado
    $query = "DELETE FROM estado WHERE id_estado = :id_estado";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Estado eliminado correctamente');</script>";
        echo "<script>window.location.href = '../visualizar/estado.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al eliminar el estado');</script>";
    }
}
?>