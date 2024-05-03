<?php
session_start();

if (!isset($_SESSION['jugador'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}
?>
