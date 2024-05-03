<?php
// Incluir el archivo de conexión a la base de datos
require_once '../Config/conexion.php';

// Verificar si se recibieron los datos del formulario
if (isset($_POST['id_usuario'], $_POST['id_mapa'])) {
    $id_usuario = $_POST['id_usuario'];
    $id_mapa = $_POST['id_mapa'];

    try {
        // Crear una instancia de la clase Database
        $dbClass = new Database();
        $db = $dbClass->conectar();

        // Obtener la fila del mapa en la que el usuario está unido
        $sql_obtener_mapa = "SELECT jugador1_id, jugador2_id, jugador3_id, jugador4_id, jugador5_id FROM mapa WHERE id_mapa = :id_mapa";
        $stmt = $db->prepare($sql_obtener_mapa);
        $stmt->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
        $stmt->execute();
        $mapa = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario está unido al mapa
        if (in_array($id_usuario, $mapa)) {
            // Eliminar el ID del usuario de los campos en la tabla mapa
            foreach ($mapa as $campo => $valor) {
                if ($valor == $id_usuario) {
                    $sql_actualizar_campo = "UPDATE mapa SET $campo = NULL WHERE id_mapa = :id_mapa";
                    $stmt = $db->prepare($sql_actualizar_campo);
                    $stmt->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            echo "<script>alert('Te has retirado de la sala exitosamente.'); window.location='index.php';</script>";
        } else {
            echo "No estabas unido a esta sala.";
        }
    } catch (PDOException $e) {
        echo "Error al abandonar la sala: " . $e->getMessage();
    }
} else {
    echo "Error: No se recibieron los datos del formulario.";
}
?>
