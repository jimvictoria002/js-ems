<?php
require '../../connection.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $query = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "true";
    } else {
        echo "false";
    }
} else {
    echo "true";
}
