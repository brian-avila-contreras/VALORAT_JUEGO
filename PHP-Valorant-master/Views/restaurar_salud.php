<?php
session_start();

// Verificar si la sesión está activa
if (!isset($_SESSION['jugador'])) {
    header("Location: ../../../../valorant/login.php");
    exit;
}

// Incluir el archivo de conexión a la base de datos
require_once '../Config/conexion.php';

// Obtener el ID del usuario en sesión
$id_usuario = $_SESSION['jugador']['id_usuario'];

// Consulta SQL para actualizar los puntos de vida del jugador
$sql = "UPDATE usuarios SET puntos_salud = 100 WHERE id_usuario = :id_usuario";

try {
    // Crear una instancia de la clase Database
    $dbClass = new Database();
    $db = $dbClass->conectar();

    // Preparar la consulta
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Redireccionar de vuelta al perfil del usuario
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    echo 'Error al restaurar la salud: ' . $e->getMessage();
    exit;
}
?>
