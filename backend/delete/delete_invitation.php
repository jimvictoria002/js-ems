<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $token = $_POST['token'];

    $query = "DELETE FROM verification_token WHERE token = '$token';";
    $result = $conn->query($query);

    session_start();
    $_SESSION['success'] = "Invitation cancelled successfully";
    
    header('Location:'.$_SERVER['HTTP_REFERER']);
}



