<?php
include('../../connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $email = $_POST['email'];
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";
    
    if (!$conn->query($query)) {
        echo "error";
    } else {
        $query = "DELETE FROM password_reset_token WHERE email = '$email'";
        $conn->query($query);
        session_start();
        $_SESSION['success'] = "Password reset successfully";
        header("location:../../frontend/index.php");
    }
}