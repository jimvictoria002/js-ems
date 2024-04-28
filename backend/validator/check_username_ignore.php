<?php
require '../../connection.php';

if (isset($_POST['username'])) {

    session_start();

    $username = $_POST['username'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM users WHERE username = ? AND user_id != ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $username, $user_id);
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
