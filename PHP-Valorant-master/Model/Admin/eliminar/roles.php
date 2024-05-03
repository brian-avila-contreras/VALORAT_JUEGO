<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un ID para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_rol = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para eliminar el registro con el ID especificado
    $query = "DELETE FROM roles WHERE id_rol = :id_rol";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Rol eliminado correctamente');</script>";
        echo "<script>window.location.href = '../visualizar/roles.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al Rol el estado');</script>";
    }
}
?>