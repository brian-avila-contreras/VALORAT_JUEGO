<?php
require_once '../Config/validar_sesion.php';
require_once '../Config/conexion.php';

// Función para simular el azar y calcular el daño
function calcularDanio($arma)
{
    // Generar un número aleatorio para determinar la zona de impacto
    $zona_impacto = rand(1, 3); // 1: cabeza, 2: piernas/pies, 3: otras zonas

    // Calcular el daño según la zona de impacto y el arma seleccionada
    switch ($zona_impacto) {
        case 1: // Cabeza (doble daño)
            $danio = $arma['daño'] * 2;
            $zona = "cabeza";
            break;
        case 2: // Piernas/pies (mitad del daño)
            $danio = $arma['daño'] / 2;
            $zona = "piernas/pies";
            break;
        default: // Otras zonas (daño normal)
            $danio = $arma['daño'];
            $zona = "otras zonas";
            break;
    }

    return array("danio" => $danio, "zona" => $zona);
}

try {
    // Conexión a la base de datos
    $dbClass = new Database();
    $db = $dbClass->conectar();

    // Verificar si se han enviado los datos necesarios
    if (isset($_POST['id_mapa'], $_POST['id_atacante'], $_POST['id_atacado'], $_POST['id_arma'])) {
        // Obtener los datos necesarios
        $id_mapa = $_POST['id_mapa'];
        $id_atacante = $_POST['id_atacante'];
        $id_atacado = $_POST['id_atacado'];
        $id_arma = $_POST['id_arma'];

        // Obtener información del arma seleccionada
        $sql_info_arma = "SELECT daño, tipo_arma, nombre FROM armas WHERE id_arma = :id_arma";
        $stmt_arma = $db->prepare($sql_info_arma);
        $stmt_arma->bindParam(':id_arma', $id_arma, PDO::PARAM_INT);
        $stmt_arma->execute();
        $info_arma = $stmt_arma->fetch(PDO::FETCH_ASSOC);

        // Calcular el daño realizado y la zona de impacto
        $resultadoDanio = calcularDanio($info_arma);
        $danio_realizado = $resultadoDanio['danio'];
        $zona_impacto = $resultadoDanio['zona'];

        // Definir los puntos a agregar según el tipo de arma y la zona de impacto
        $puntos_agregados = 0;
        switch ($info_arma['tipo_arma']) {
            case 1: // Tipo 1: Agregar 1 punto sin importar la zona de impacto
                $puntos_agregados = 1;
                break;
            case 2: // Tipo 2: Agregar 2 puntos sin importar la zona de impacto
                $puntos_agregados = 2;
                break;
            case 3: // Tipo 3: Agregar 10 puntos sin importar la zona de impacto
                $puntos_agregados = 10;
                break;
            case 4: // Tipo 4: Agregar 20 puntos sin importar la zona de impacto
                $puntos_agregados = 20;
                break;
        }

        // Verificar el tipo de zona de impacto y sumar los puntos adicionales según corresponda
        if ($zona_impacto === "cabeza" && $danio_realizado >= 100) {
            $puntos_agregados += 75;
            $mensaje_adicional = "¡Has eliminado a $nombre_atacado!";
        } elseif ($zona_impacto !== "cabeza" && $danio_realizado >= 100) {
            $puntos_agregados += 5;
            $mensaje_adicional = "¡Ganas 5 puntos adicionales por el daño causado!";
        } else {
            $mensaje_adicional = "";
        }

        // Insertar detalles del combate en la tabla detalles_usuarios
        $sql_insert_combate = "INSERT INTO detalles_usuarios (daño_realizado, id_jugador_atacante, id_jugador_atacado, fecha, id_arma, id_mapa) 
                               VALUES (:danio_realizado, :id_atacante, :id_atacado, NOW(), :id_arma, :id_mapa)";
        $stmt_insert_combate = $db->prepare($sql_insert_combate);
        $stmt_insert_combate->bindParam(':danio_realizado', $danio_realizado, PDO::PARAM_INT);
        $stmt_insert_combate->bindParam(':id_atacante', $id_atacante, PDO::PARAM_INT);
        $stmt_insert_combate->bindParam(':id_atacado', $id_atacado, PDO::PARAM_INT);
        $stmt_insert_combate->bindParam(':id_arma', $id_arma, PDO::PARAM_INT);
        $stmt_insert_combate->bindParam(':id_mapa', $id_mapa, PDO::PARAM_INT);
        $stmt_insert_combate->execute();

        // Obtener el nombre del jugador atacado
        $sql_nombre_atacado = "SELECT nombre FROM usuarios WHERE id_usuario = :id_atacado";
        $stmt_nombre_atacado = $db->prepare($sql_nombre_atacado);
        $stmt_nombre_atacado->bindParam(':id_atacado', $id_atacado, PDO::PARAM_INT);
        $stmt_nombre_atacado->execute();
        $nombre_atacado = $stmt_nombre_atacado->fetch(PDO::FETCH_ASSOC)['nombre'];

        // Obtener los puntos de rango y salud del atacado
        $sql_info_atacado = "SELECT puntos_rango, puntos_salud FROM usuarios WHERE id_usuario = :id_atacado";
        $stmt_atacado = $db->prepare($sql_info_atacado);
        $stmt_atacado->bindParam(':id_atacado', $id_atacado, PDO::PARAM_INT);
        $stmt_atacado->execute();
        $info_atacado = $stmt_atacado->fetch(PDO::FETCH_ASSOC);
        $puntos_rango_atacado = $info_atacado['puntos_rango'];
        $puntos_salud_atacado = $info_atacado['puntos_salud'];

        // Obtener los puntos de rango del atacante
        $sql_info_atacante = "SELECT puntos_rango FROM usuarios WHERE id_usuario = :id_atacante";
        $stmt_atacante = $db->prepare($sql_info_atacante);
        $stmt_atacante->bindParam(':id_atacante', $id_atacante, PDO::PARAM_INT);
        $stmt_atacante->execute();
        $info_atacante = $stmt_atacante->fetch(PDO::FETCH_ASSOC);
        $puntos_rango_atacante = $info_atacante['puntos_rango'];

        // Calcular los nuevos puntos de rango del atacante
        $nuevos_puntos_rango_atacante = $puntos_rango_atacante + $puntos_agregados;

        // Calcular los nuevos puntos de salud del atacado
        $nuevos_puntos_salud_atacado = $puntos_salud_atacado - $danio_realizado;

        // Actualizar los puntos de rango del atacante en la base de datos
        $sql_update_atacante = "UPDATE usuarios SET puntos_rango = :puntos_rango WHERE id_usuario = :id_atacante";
        $stmt_update_atacante = $db->prepare($sql_update_atacante);
        $stmt_update_atacante->bindParam(':puntos_rango', $nuevos_puntos_rango_atacante, PDO::PARAM_INT);
        $stmt_update_atacante->bindParam(':id_atacante', $id_atacante, PDO::PARAM_INT);
        $stmt_update_atacante->execute();



        $sql_update_atacado = "UPDATE usuarios SET puntos_salud = :puntos_salud WHERE id_usuario = :id_atacado";
        $stmt_update_atacado = $db->prepare($sql_update_atacado);
        $stmt_update_atacado->bindParam(':puntos_salud', $nuevos_puntos_salud_atacado, PDO::PARAM_INT);
        $stmt_update_atacado->bindParam(':id_atacado', $id_atacado, PDO::PARAM_INT);
        $stmt_update_atacado->execute();

        $mensaje_atacado = "¡Has sido atacado, recibiste $danio_realizado % en la zona $zona_impacto, contraataca!";
        $sql_insert_mensaje = "UPDATE usuarios SET mensaje = :mensaje WHERE id_usuario = :id_atacado";
        $stmt_insert_mensaje = $db->prepare($sql_insert_mensaje);
        $stmt_insert_mensaje->bindParam(':mensaje', $mensaje_atacado, PDO::PARAM_STR);
        $stmt_insert_mensaje->bindParam(':id_atacado', $id_atacado, PDO::PARAM_INT);
        $stmt_insert_mensaje->execute();



        // Mostrar un mensaje de alerta y redireccionar a index.php
        echo "<script>alert('¡Has infligido $danio_realizado de daño a $nombre_atacado en la $zona_impacto con el arma " . $info_arma['nombre'] . "! $mensaje_adicional'); window.location.href = 'index.php';</script>";
    } else {
        // Si no se reciben los datos necesarios, mostrar un mensaje de error
        echo "<script>alert('Error: Datos insuficientes para procesar el combate'); window.location.href = 'index.php';</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('Error al procesar el combate: " . $e->getMessage() . "'); window.location.href = 'index.php';</script>";
}
