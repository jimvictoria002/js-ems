<?php
include ('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    $logout_route = $_SESSION['access'];
    session_destroy();
    $_SESSION = [];
    header('Location: ../../frontend/login.php?access='.$logout_route);
}