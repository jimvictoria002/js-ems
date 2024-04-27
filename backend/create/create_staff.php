<?php
require "../../connection.php";
require "../mailer.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    if (isset($_POST['token'])) {
        $token = $_POST['token'];

        $query = "DELETE FROM ems2.`verification_token` WHERE token = '$token';";
        $result = $conn->query($query);
        echo $result;
    }

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $username = $_POST['username'];
    $password = password_hash('12345678', PASSWORD_DEFAULT);
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $password;
    $email = $_POST['email'];
    $is_verify = 'yes';
    $access = 'staff';
    $user_img = 'default-img';

    $query = "INSERT INTO `users`(`firstname`, `middlename`, `lastname`, `email`, `user_img`, `access`, `is_verify`, `username`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssssss', $firstname, $middlename, $lastname, $email, $user_img, $access, $is_verify, $username, $password);


    $stmt->execute();

    $affected_rows = $stmt->affected_rows;

    if ($affected_rows > 0) {
        if (isset($_POST['password'])) {

            $user_id = $conn->insert_id;
            $query = "SELECT * FROM users WHERE user_id = $user_id";
            $result = $conn->query($query);
            $user = $result->fetch_assoc();
            foreach ($user as $key => $value) {
                $_SESSION[$key] = $value;
            }
            $_SESSION['success'] = "Staff created successfully";
            header('Location: ../../frontend/event-calendar.php');
        } else {
            $_SESSION['success'] = "Staff created successfully";
            header('Location: ../../frontend/event-calendar.php');
        }
    } else {
        echo 'Error';
    }
}
