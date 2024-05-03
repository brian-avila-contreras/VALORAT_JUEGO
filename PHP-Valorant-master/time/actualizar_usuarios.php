// probar con 2 minutos 
//require_once("../Config/conexion.php");

// Crear una instancia de la clase Database para obtener la conexión PDO
//$database = new Database();
//$pdo = $database->conectar();

// Obtener la fecha y hora actual
//$hora_actual = date("Y-m-d H:i:s");

// Consulta para obtener todos los usuarios
//$sql = "SELECT id_usuario, ultima_conexion FROM usuarios";
//$stmt = $pdo->query($sql);

// Iterar sobre los usuarios y verificar/actualizar el estado según sea necesario
//while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //$id_usuario = $row['id_usuario'];
    //$ultima_conexion = $row['ultima_conexion'];

    // Calcular la diferencia de tiempo en minutos entre la última conexión y la hora actual
    //$diferencia_minutos = (strtotime($hora_actual) - strtotime($ultima_conexion)) / 60;

    // Verificar si han pasado más de 2 minutos
    //if ($diferencia_minutos > 2) {
        // Actualizar el estado del usuario a 2
        //$sql_update = "UPDATE usuarios SET id_estado = 2 WHERE id_usuario = :id_usuario";
        //$stmt_update = $pdo->prepare($sql_update);
        //$stmt_update->bindParam(':id_usuario', $id_usuario);
        //$stmt_update->execute();
    //}
//}

// Aquí va el código para simular la tarea cron

// Ruta a tu script que actualiza los usuarios
//$url = "http://localhost/valorant/time/actualizar_usuarios.php";

// Realiza una solicitud HTTP a tu propio servidor local
//$response = file_get_contents($url);

// Muestra la respuesta (puedes omitir esto si no necesitas verla)
//echo $response;

<?php
require_once("../Config/conexion.php");

// Crear una instancia de la clase Database para obtener la conexión PDO
$database = new Database();
$pdo = $database->conectar();

// Obtener la fecha y hora actual
$hora_actual = date("Y-m-d H:i:s");

// Definir el número de días límite
$dias_limite = 10;

// Consulta para obtener todos los usuarios
$sql = "SELECT id_usuario, ultima_conexion FROM usuarios";
$stmt = $pdo->query($sql);

// Iterar sobre los usuarios y verificar/actualizar el estado según sea necesario
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id_usuario = $row['id_usuario'];
    $ultima_conexion = $row['ultima_conexion'];

    // Calcular la diferencia de tiempo en días entre la última conexión y la hora actual
    $diferencia_dias = (strtotime($hora_actual) - strtotime($ultima_conexion)) / (60 * 60 * 24);

    // Verificar si han pasado más de 10 días
    if ($diferencia_dias > $dias_limite) {
        // Actualizar el estado del usuario a 2
        $sql_update = "UPDATE usuarios SET id_estado = 2 WHERE id_usuario = :id_usuario";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':id_usuario', $id_usuario);
        $stmt_update->execute();
    }
}

// Aquí va el código para simular la tarea cron

// Ruta a tu script que actualiza los usuarios
$url = "http://localhost/valorant/time/actualizar_usuarios.php";

// Realiza una solicitud HTTP a tu propio servidor local
$response = file_get_contents($url);

// Muestra la respuesta (puedes omitir esto si no necesitas verla)
echo $response;
?>
