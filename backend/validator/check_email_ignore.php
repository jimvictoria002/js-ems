<?php
require '../../connection.php';

if (isset($_POST['email'])) {
    session_start();
    $email = $_POST['email'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM users WHERE email = ? AND user_id != ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "false";
    } else {
        echo "true";
    }
} else {
    echo "false";
}
