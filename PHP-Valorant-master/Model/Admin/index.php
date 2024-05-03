<?php
include ("../../Config/validarSesion.php");
?>
<?php
require_once("../../Config/conexion.php");
$DataBase = new Database;
$con = $DataBase->conectar();

// Consulta SQL para obtener el recuento de usuarios activos y deshabilitados
$query = "SELECT 
            SUM(CASE WHEN id_estado = 1 THEN 1 ELSE 0 END) AS num_activos,
            SUM(CASE WHEN id_estado = 2 THEN 1 ELSE 0 END) AS num_deshabilitados
        FROM usuarios";

// Ejecutar la consulta
$resultado = $con->query($query);

// Verificar si la consulta fue exitosa
if ($resultado) {
    // Obtener el resultado
    $fila = $resultado->fetch(PDO::FETCH_ASSOC);
    $num_activos = $fila['num_activos'];
    $num_deshabilitados = $fila['num_deshabilitados'];
} else {
    echo "Error en la consulta: " . $con->errorInfo()[2];
    exit();
}

// Consulta SQL para obtener el número de agentes
$query_agentes = "SELECT COUNT(*) AS num_agentes FROM agentes";

// Ejecutar la consulta de agentes
$resultado_agentes = $con->query($query_agentes);

// Verificar si la consulta de agentes fue exitosa
if ($resultado_agentes) {
    // Obtener el número de agentes
    $fila_agentes = $resultado_agentes->fetch(PDO::FETCH_ASSOC);
    $num_agentes = $fila_agentes['num_agentes'];
} else {
    echo "Error en la consulta de agentes: " . $con->errorInfo()[2];
    exit();
}
// Consulta SQL para obtener el número de mapas
$query_mapas = "SELECT COUNT(*) AS num_mapas FROM mapa";

// Ejecutar la consulta de mapas
$resultado_mapas = $con->query($query_mapas);

// Verificar si la consulta de mapas fue exitosa
if ($resultado_mapas) {
    // Obtener el número de mapas
    $fila_mapas = $resultado_mapas->fetch(PDO::FETCH_ASSOC);
    $num_mapas = $fila_mapas['num_mapas'];
} else {
    echo "Error en la consulta de mapas: " . $con->errorInfo()[2];
    exit();
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="assets/css/dark-theme.css" />
    <link rel="stylesheet" href="assets/css/semi-dark.css" />
    <link rel="stylesheet" href="assets/css/header-colors.css" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">


    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Valorant - Panel Administrador </title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="assets/images/valorant.jpg" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Valorant</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="index.php">
                        <div class="parent-icon"><i class="fas fa-desktop"></i>
                        </div>
                        <div class="menu-title">Panel Administrador</div>
                    </a>
                </li>
                
                <li class="menu-label">Usuarios</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fas fa-users"></i>

                        </div>
                        <div class="menu-title">Jugadores</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/jugadores.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/jugadores.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fas fa-user-tag"></i>

                        </div>
                        <div class="menu-title">Roles</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/roles.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/roles.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-toggle-on"></i>
                        </div>
                        <div class="menu-title">Estados</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/estado.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/estado.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-user-astronaut"></i>
                        </div>
                        <div class="menu-title">Agentes</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/agentes.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/agentes.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-star"></i>
                        </div>
                        <div class="menu-title">Rangos</div>
                    </a>
                    <ul>
                    <ul>
                        <li> <a href="visualizar/rango.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/rango.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                    </ul>
                </li><li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-toolbox"></i>
                        </div>
                        <div class="menu-title">Tipo_Armas</div>
                    </a>
                    <ul>
                    <ul>
                        <li> <a href="visualizar/tarmas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/tarmas.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                    </ul>
                </li>
                <li class="menu-label">Mapas & Estadisticas</li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-map"></i>
                        </div>
                        <div class="menu-title">Mapas</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/mapas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/mapas.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-tools"></i>
                        </div>
                        <div class="menu-title">Armas</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/armas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="crear/armas.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="menu-title">Estadisticas</div>
                    </a>
                    <ul>
                        <li> <a href="visualizar/estadisticas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar ataques</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">Otros</li>
                <li>
                    <a href="visualizar/perfil.php" >
                        <div class="parent-icon"><i class="fas fa-user"></i>
                        </div>
                        <div class="menu-title">Perfil</div>
                    </a>
                </li>
            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="search-bar flex-grow-1">

                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="assets/images/avatar-admin.jpg" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?php echo $_SESSION['jugador']['nombre']; ?></p>
                                <p class="designattion mb-0">Admin</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="visualizar/perfil.php"><i
                                        class="bx bx-user"></i><span>Perfil</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" onclick="confirmarCerrarSesion()"><i
                                        class='bx bx-log-out-circle'></i><span>Cerrar sesión</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Mapas Disponibles</p>
                                        <h4 class="my-1 text-info"><?php echo $num_mapas; ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                        <i class="fas fa-flag-checkered"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Jugadores activos</p>
                                        <h4 class="my-1 text-danger"><?php echo $num_activos; ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Jugadores inactivos</p>
                                        <h4 class="my-1 text-warning"><?php echo $num_deshabilitados; ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                                        <i class="fas fa-user-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-0 border-3 border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Agentes Disponibles</p>
                                        <h4 class="my-1 text-success"><?php echo $num_agentes; ?></h4>
                                    </div>
                                    <div
                                        class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                        <i class="fas fa-user-astronaut"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
                <!--end row-->

                <div class="row">
            
                    
                </div>
                <!--end row-->
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2024. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Personalizar</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <h6 class="mb-0">Estilos de panel</h6>
            <hr />
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
                    <label class="form-check-label" for="lightmode">Claro</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
                    <label class="form-check-label" for="darkmode">Oscuro</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
                    <label class="form-check-label" for="semidark">Semi-oscuro</label>
                </div>
            </div>
            <hr />
            <div class="form-check">
                <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                <label class="form-check-label" for="minimaltheme">Tema mínimalista</label>
            </div>
            <hr />
            <h6 class="mb-0">Colores del encabezado</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator headercolor1" id="headercolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor2" id="headercolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor3" id="headercolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor4" id="headercolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor5" id="headercolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor6" id="headercolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor7" id="headercolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor8" id="headercolor8"></div>
                    </div>
                </div>
            </div>
            <hr />
            <h6 class="mb-0">Colores de la barra lateral</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
    <script src="assets/js/index.js"></script>
    <!--app JS-->
    <script src="assets/js/app.js"></script>
    <script>
        function confirmarCerrarSesion() {
            // Mostrar una alerta con dos botones: Aceptar y Cancelar
            if (confirm("¿Estás seguro de cerrar la sesión?")) {
                // Si el usuario hace clic en Aceptar, redirige a la página de validación de sesión
                window.location.href = "../../Config/validarSesion.php?logout=true";
            }
            // Si el usuario hace clic en Cancelar, no hace nada
        }
    </script>
</body>

</html>