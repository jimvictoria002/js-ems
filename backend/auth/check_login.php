<?php
include('../../connection.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $access = $_POST['access'];

    if ($access == 'parent') {
        $stmt = $conn->prepare("SELECT * FROM scheduling_system.parent WHERE parent_id=? OR email=?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'teacher') {
        $stmt = $conn->prepare("SELECT * FROM scheduling_system.teacher WHERE personnel_id=? OR email=?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'admin') {
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username=? OR email=?) AND access = ?");
        $stmt->bind_param("sss", $username, $username, $access);
        $stmt->execute();
        $result = $stmt->get_result();
    } else if ($access == 'guest') {
    } else if ($access == 'student') {
    } else {
        echo 'invalid';
    }



    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();


        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            foreach ($row as $key => $value) {
                if ($key == 'id') {
                    $key = 'user_id';
                }
                $_SESSION[$key] = $value;
            }
            $_SESSION['access'] = $access;
            echo "correct";
        } else {
            echo 'invalid';
        }
    } else {
        echo 'invalid';
    }
}
