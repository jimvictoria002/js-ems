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

    function is_connected()
    {
        $url = 'https://www.google.com'; // Use a reliable website
        $headers = @get_headers($url);

        if ($headers && strpos($headers[0], '200')) {
            return true; // Connected to the internet
        } else {
            return false; // Not connected to the internet
        }
    }

    // Example usage:
    if (!is_connected()) {
        $_SESSION['failed'] = "No internet connection";
        header("Location:" . $_SERVER['HTTP_REFERER']);
        exit;
        return;
    }


    $email = $_POST['email'];

    $token = generateToken(65);

    $query = "SELECT * FROM verification_token WHERE token = '$token'";
    $result = $conn->query($query);

    while ($result->num_rows > 0) {
        $token = generateToken(65);
        $query = "SELECT * FROM verification_token WHERE token = '$token'";
        $result = $conn->query($query);
    }

    $query = "INSERT INTO `verification_token`(`token`,  `email`) VALUES (?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $token, $email);
    $stmt->execute();

    $affected_rows = $stmt->affected_rows;

    if ($affected_rows > 0) {
        $mail->addAddress("$email");
        $mail->Subject = 'Staff Invitation';

        ob_start();
        require "../email-format/invitation-body.php";
        $mail->Body = ob_get_clean();

        $mail->isHTML(true);

        if ($mail->send()) {
            $_SESSION['success'] = "$email Invited successfully";
            header('Location: ../../frontend/users.php');
        } else {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'Error';
    }
}
