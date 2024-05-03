<?php include "../template/header.php"; ?>

<?php
    require_once("../../../Config/conexion.php");
    $DataBase = new Database;
    $con = $DataBase->conectar();

    // Consulta SQL para seleccionar los datos de la tabla de usuarios
    $query = "SELECT * FROM usuarios";
    $stmt = $con->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h2 class="text-center">Tabla de Jugadores</h2>
    <div class="table-responsive text-center">
        <table class="table table-bordered table-striped d-inline-block">
            <thead class="thead-dark">
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Puntos de Salud</th>
                    <th>Puntos de Rango</th>
                    <th>ID Agente</th>
                    <th>ID Estado</th>
                    <th>ID Rol</th>
                    <th>ID Rango</th>
                    <th>Última Conexión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Consulta SQL para seleccionar los datos de la tabla de usuarios con la foto del agente
                $query = "SELECT usuarios.*, agentes.foto AS foto_agente, estado.nombre AS nombre_estado, roles.nombre AS nombre_rol, rango.nombre AS nombre_rango, rango.foto AS foto_rango
                        FROM usuarios 
                        LEFT JOIN agentes ON usuarios.id_agente = agentes.id_agente
                        LEFT JOIN estado ON usuarios.id_estado = estado.id_estado
                        LEFT JOIN roles ON usuarios.id_rol = roles.id_rol
                        LEFT JOIN rango ON usuarios.id_rango = rango.id_rango
                        WHERE usuarios.id_rol != 1";
                $stmt = $con->query($query);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>{$row['id_usuario']}</td>";
                    echo "<td>{$row['nombre']}</td>";
                    echo "<td>{$row['correo']}</td>";
                    echo "<td>{$row['puntos_salud']}</td>";
                    echo "<td>{$row['puntos_rango']}</td>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto_agente']) . "' alt='Foto del Agente' style='width: 50px; height: auto;'></td>";
                    echo "<td>{$row['nombre_estado']}</td>";
                    echo "<td>{$row['nombre_rol']}</td>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['foto_rango']) . "' alt='Foto del Rango' style='width: 50px; height: auto;'></td>";
                    echo "<td>{$row['ultima_conexion']}</td>";
                    echo "<td>
                        <div class='text-center'>
                            <a href='../actualizar/jugadores.php?id={$row['id_usuario']}' class='btn btn-primary btn-sm'>Editar</a>
                            <a href='../eliminar/jugadores.php?id={$row['id_usuario']}' class='btn btn-danger btn-sm'>Eliminar</a>
                        </div>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<?php include "../template/footer.php"; ?>