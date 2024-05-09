<?php
include ('../../connection.php');
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = $_POST['email'];

    

    $expiresAt = new DateTime();
    $expiresAt->modify('+30 minutes');
    function generateToken($length)
    {
        $bytes = ceil($length * 3 / 4);

        $randomBytes = random_bytes($bytes);

        $token = base64_encode($randomBytes);

        $token = str_replace(['+', '/', '='], '', $token);

        return substr($token, 0, $length);
    }

    $token = generateToken(65);


    $query = "SELECT * FROM password_reset_token WHERE token = '$token'";
    $result = $conn->query($query);

    while ($result->num_rows > 0) {
        $token = generateToken(65);
        $query = "SELECT * FROM password_reset_token WHERE token = '$token'";
        $result = $conn->query($query);
    }

    $query =
        "INSERT INTO `password_reset_token`(`token`,  `email`)
     VALUES ('$token','$email')";

    require '../mailer.php';

    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Password reset';
    $mail->Body = '
                This is your password reset link <br>
                <a href="http://localhost:8080/ems2/frontend/reset_password.php?token=' . $token . '">Password reset link</a>
            ';

    if ($mail->send()) {
        echo 'Email sent successfully!';
        $conn->query($query);
        session_start();
        $_SESSION['email_sent'] = "Password reset link sent";
        header("location: ../../frontend/forgot_password.php");
        exit;
    } else {
        session_start();
        $_SESSION['email_error'] = "Connection error";
        header("location: ../../frontend/forgot_password.php");
        exit;
    }


}