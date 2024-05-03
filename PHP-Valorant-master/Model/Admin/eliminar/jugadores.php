<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un ID para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_usuario = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para eliminar el registro con el ID especificado
    $query = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('jugador eliminado correctamente');</script>";
        echo "<script>window.location.href = '../visualizar/jugadores.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error al eliminar el jugador');</script>";
    }
}
?>