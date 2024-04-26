<?php
function generateToken($length)
{
    $bytes = ceil($length * 3 / 4);

    $randomBytes = random_bytes($bytes);

    $token = base64_encode($randomBytes);

    $token = str_replace(['+', '/', '='], '', $token);

    return substr($token, 0, $length);
}
require "../../connection.php";
require "../mailer.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $email = $_POST['email'];

    $token = generateToken(65);

    $query = "SELECT * FROM verification_token WHERE token = '$token'";
    $result = $conn->query($query);

    while ($result->num_rows > 0) {
        $token = generateToken(65);
        $query = "SELECT * FROM verification_token WHERE token = '$token'";
        $result = $conn->query($query);
    }

    $query = "INSERT INTO `verification_token`(`token`,  `email`) VALUES ('$token','$email')";
    $result = $conn->query($query);

    $query = "INSERT INTO `users`(`email`) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();

    $affected_rows = $stmt->affected_rows;

    if ($affected_rows > 0) {
        $mail->addAddress("$email"); 
        $mail->Subject = 'Staff Invitation';

        ob_start();
        require "../email-format/invitation-body.php";
        $mail->Body = ob_get_clean(); 

        $mail->isHTML(true);

        if($mail->send()) {
            $_SESSION['success'] = "$email Invited successfully";
           header('Location: ../../frontend/users.php');
        } else {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'Error';
    }
}
