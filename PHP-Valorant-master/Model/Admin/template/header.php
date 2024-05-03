<?php
include ("../../../Config/validarSesion.php");
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="../assets/images/favicon-32x32.png" type="image/png" />
    <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="../assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="../assets/css/pace.min.css" rel="stylesheet" />
    <script src="../assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <link href="../assets/css/app.css" rel="stylesheet">
    <link href="../assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="../assets/css/dark-theme.css" />
    <link rel="stylesheet" href="../assets/css/semi-dark.css" />
    <link rel="stylesheet" href="../assets/css/header-colors.css" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">


    <link rel="apple-touch-icon" sizes="60x60" href="../assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/manifest.json">
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
                    <img src="../assets/images/valorant.jpg" class="logo-icon" alt="logo icon">
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
                    <a href="../index.php">
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
                        <li> <a href="../visualizar/jugadores.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/jugadores.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/roles.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/roles.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/estado.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/estado.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/agentes.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/agentes.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/rango.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/rango.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
                        </li>
                    </ul>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="fas fa-toolbox"></i>
                        </div>
                        <div class="menu-title">Tipo_Armas</div>
                    </a>
                    <ul>
                    <ul>
                        <li> <a href="../visualizar/tarmas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/tarmas.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/mapas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/mapas.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/armas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar</a>
                        </li>
                        <li> <a href="../crear/armas.php"><i class="bx bx-right-arrow-alt"></i>Crear</a>
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
                        <li> <a href="../visualizar/estadisticas.php"><i class="bx bx-right-arrow-alt"></i>Visualizar ataques</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">Otros</li>
                <li>
                    <a href="../visualizar/perfil.php" >
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
                            <img src="../assets/images/avatar-admin.jpg" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?php echo $_SESSION['jugador']['nombre']; ?></p>
                                <p class="designattion mb-0">Admin</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../visualizar/perfil.php"><i
                                        class="bx bx-user"></i><span>Perfil</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="../../../Config/validarSesion.php?logout=true"><i
                                        class='bx bx-log-out-circle'></i><span>Cerrar sesión</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="container mt-4">