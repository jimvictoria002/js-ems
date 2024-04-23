<?php
include ('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $access = $_POST['access'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE (username=? OR email=?) AND access = ?");
    $stmt->bind_param("sss", $username, $username, $access);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            foreach ($row as $key => $value) {
                $_SESSION[$key] = $value;
            }
            echo "correct";
        } else {
           echo 'invalid';
        }
    } else {
        echo 'invalid';

    }
}