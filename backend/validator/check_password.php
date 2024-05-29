<?php
require '../../connection.php';

if (isset($_POST['current_password'])) {
    session_start();
    $current_password = $_POST['current_password'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT password FROM users WHERE user_id = $user_id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    if (password_verify($current_password, $hashedPassword)) {
        echo "true";
    } else {
        echo "false";
    }
}
