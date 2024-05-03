<?php
require_once("../../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();
?>

<?php include "../template/header.php"; ?>
<section class="section profile">
    <div class="row">
        <div class="col-xl-4">

            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <h1><?php echo $_SESSION['jugador']['nombre']; ?></h1>

                    <?php
                    // Verificar si el jugador ha iniciado sesión
                    if (isset($_SESSION['jugador']['nombre'])) {
                        // Obtén el nombre del jugador de la sesión
                        $nombreJugador = $_SESSION['jugador']['nombre'];

                        // Realiza la consulta SQL para obtener el rol del jugador actual
                        $consulta = "SELECT roles.nombre FROM usuarios 
                INNER JOIN roles ON usuarios.id_rol = roles.id_rol 
                WHERE usuarios.nombre = '$nombreJugador'";

                        // Ejecuta la consulta
                        $resultado = $con->query($consulta);

                        // Verifica si se encontraron resultados
                        if ($resultado && $resultado->rowCount() > 0) {
                            // Itera sobre los resultados
                            while ($fila = $resultado->fetch()) {
                                echo '<h4>' . $fila["nombre"] . '</h4>';
                            }
                        } else {
                            // Si no se encontraron resultados, muestra un mensaje predeterminado
                            echo '<h3>Valor predeterminado</h3>';
                        }
                    } else {
                        // Si no hay sesión iniciada, muestra un mensaje de error o redirige al jugador a iniciar sesión
                        echo '<h3>Error: Sesión no iniciada</h3>';
                    }
                    ?>

                </div>
            </div>

        </div>

        <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#profile-overview">Datos Personales</button>
                        </li>


                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#profile-change-password">Contraseña</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">Importante: Política de Recuperación de Cuenta</h5>
                            <p class="small fst-italic">Como administrador de esta plataforma, es crucial que tengas en
                                cuenta
                                que en el caso de que olvides información relevante para acceder a tu cuenta, el proceso
                                de
                                recuperación puede ser complicado o incluso imposible. Para garantizar la seguridad y la
                                integridad
                                de los datos, hemos implementado medidas de seguridad estrictas que limitan el acceso a
                                la cuenta
                                en caso de pérdida de datos de identificación.</p>

                            <h5 class="card-title">Detalles del perfil</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Nombre:</div>
                                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['jugador']['nombre']; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Correo:</div>
                                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['jugador']['correo']; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Estado:</div>
                                <div class="col-lg-9 col-md-8">
                                    <?php
                                    // Verificar si el jugador ha iniciado sesión
                                    if (isset($_SESSION['jugador']['nombre'])) {
                                        // Obtén el nombre del jugador de la sesión
                                        $nombreJugador = $_SESSION['jugador']['nombre'];

                                        // Realiza la consulta SQL para obtener el estado del jugador actual
                                        $consulta = "SELECT estado.nombre FROM usuarios 
                                            INNER JOIN estado ON usuarios.id_estado = estado.id_estado 
                                    WHERE usuarios.nombre = '$nombreJugador'";
                                        // Ejecuta la consulta
                                        $resultado = $con->query($consulta);

                                        // Verifica si se encontraron resultados
                                        if ($resultado && $resultado->rowCount() > 0) {
                                            // Itera sobre los resultados
                                            while ($fila = $resultado->fetch()) {
                                                echo '<h8>' . $fila["nombre"] . '</h8>';
                                            }
                                        } else {
                                            // Si no se encontraron resultados, muestra un mensaje predeterminado
                                            echo '<h3>Valor predeterminado</h3>';
                                        }
                                    } else {
                                        // Si no hay sesión iniciada, muestra un mensaje de error o redirige al jugador a iniciar sesión
                                        echo '<h3>Error: Sesión no iniciada</h3>';
                                    }
                                    ?>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Rol:</div>
                                <div class="col-lg-9 col-md-8"><?php
                                // Verificar si el jugador ha iniciado sesión
                                if (isset($_SESSION['jugador']['nombre'])) {
                                    // Obtén el nombre del jugador de la sesión
                                    $nombreJugador = $_SESSION['jugador']['nombre'];

                                    // Realiza la consulta SQL para obtener el rol del jugador actual
                                    $consulta = "SELECT roles.nombre FROM usuarios 
                                        INNER JOIN roles ON usuarios.id_rol = roles.id_rol 
                                        WHERE usuarios.nombre = '$nombreJugador'";
                                    // Ejecuta la consulta
                                    $resultado = $con->query($consulta);

                                    // Verifica si se encontraron resultados
                                    if ($resultado && $resultado->rowCount() > 0) {
                                        // Itera sobre los resultados
                                        while ($fila = $resultado->fetch()) {
                                            echo '<h8>' . $fila["nombre"] . '</h8>';
                                        }
                                    } else {
                                        // Si no se encontraron resultados, muestra un mensaje predeterminado
                                        echo '<h3>Valor predeterminado</h3>';
                                    }
                                } else {
                                    // Si no hay sesión iniciada, muestra un mensaje de error o redirige al jugador a iniciar sesión
                                    echo '<h3>Error: Sesión no iniciada</h3>';
                                }
                                ?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Última Conexión:</div>
                                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['jugador']['ultima_conexion']; ?>
                                </div>
                            </div>

                        </div>


                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <form>

                            <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Actualizar Contraseña</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                                    </div>
                                </div>


                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<?php include "../template/footer.php"; ?>