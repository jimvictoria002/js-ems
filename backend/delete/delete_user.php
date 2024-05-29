<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_POST['user_id'];

    $query = "DELETE FROM users WHERE user_id = '$user_id';";
    $result = $conn->query($query);

    session_start();
    $_SESSION['success'] = "Staff deleted successfully";
    
    header('Location:'.$_SERVER['HTTP_REFERER']);
}



