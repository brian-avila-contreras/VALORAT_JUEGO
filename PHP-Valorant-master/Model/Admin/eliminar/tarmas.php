<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Verificar si se ha enviado un ID para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id_tp_arma = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Consulta SQL para eliminar el registro con el ID especificado
    $query = "DELETE FROM tipo_arma WHERE id_tp_arma = :id_tp_arma";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':id_tp_arma', $id_tp_arma, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('tipo de arma eliminado correctamente');</script>";
        echo "<script>window.location.href = '../visualizar/tarmas.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error');</script>";
    }
}
?>