<?php
include ('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    session_destroy();
    $_SESSION = [];
    header('Location: ../../frontend/login.php');
}