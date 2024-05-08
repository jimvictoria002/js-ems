<?php

require "../../connection.php";
require "../mailer.php";


function is_connected()
{
    $url = 'https://www.google.com';
    $headers = @get_headers($url);

    if ($headers && strpos($headers[0], '200')) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();




    //File handling
    $uploadDir = '../../uploads/event_img/';

    $fileName = basename($_FILES['event_img']['name']);

    $targetFile = $uploadDir . $fileName;

    $fileCount = 1;

    while (file_exists($targetFile)) {
        $fileInfo = pathinfo($fileName);
        $fileName = $fileInfo['filename'] . '_' . $fileCount . '.' . $fileInfo['extension'];
        $targetFile = $uploadDir . $fileName;
        $fileCount++;
    }

    if (!move_uploaded_file($_FILES["event_img"]["tmp_name"], $targetFile)) {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }


    // Venue verify
    if (isset($_POST['venue-type'])) {
        $user_id = $_SESSION['user_id'];
        $access = $_SESSION['access'];
        $venue = $_POST['input-venue'];

        $query = "INSERT INTO venue (venue, created_by, creator_access) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($query);

        $stmt->bind_param("sis", $venue, $user_id, $access);

        $stmt->execute();
        $_POST['venue'] = $conn->insert_id;
    }




    //Insertion
    $title = $_POST['title'];
    $description = $_POST['description'];
    $v_id = $_POST['venue'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $created_by = $_SESSION['user_id'];
    $fileNameToStore = $fileName;

    $status = $_SESSION['access'] == 'admin' || $_SESSION['access'] == 'staff' ? 'approved' : 'pending';
    $access = $_SESSION['access'];

    $query = "INSERT INTO `events` 
    (`title`, `description`, `event_img`, `start_datetime`, `end_datetime`, `v_id`, `status`, `created_by`, `creator_access`) 
    VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("sssssssis", $title, $description, $fileNameToStore, $start_datetime, $end_datetime, $v_id, $status, $created_by, $access);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {

        $event_id = $conn->insert_id;

        if (!($access == 'admin' || $access == 'staff')) {
            if (isset($_POST['notify'])) {

                if (is_connected()) {
                    if ($_SESSION['access'] == 'teacher') {
                        $requested_by = $_SESSION['first_name'] .  ' ' . $_SESSION['last_name'] . ' - ' . ucfirst($_SESSION['access']);
                    } else {
                        $requested_by = $_SESSION['firstname'] .  ' ' . $_SESSION['lastname'] . ' - ' . ucfirst($_SESSION['access']);
                    }


                    $query = "SELECT venue from venue WHERE v_id = $v_id";
                    $r_venue = $conn->query($query);
                    $venue = $r_venue->fetch_assoc()['venue'];

                    $query = "SELECT email from users WHERE access = 'admin'";
                    $r_email = $conn->query($query);
                    $email = $r_email->fetch_assoc()['email'];


                    $mail->addAddress("$email");
                    $mail->Subject = "$requested_by | Request an Event";
                    ob_start();
                    require "../email-format/request-body.php";
                    $mail->Body = ob_get_clean();

                    $mail->isHTML(true);

                    if ($mail->send()) {
                        $_SESSION['success'] = "Invitation sent to $email";
                        header('Location: ../../frontend/users.php');
                    } else {
                        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                    }
                } else {
                    $_SESSION['failed'] = "Internet Error: Notification didn't sent";
                    header("Location:" . $_SERVER['HTTP_REFERER']);
                    exit;
                    return;
                }
            }
        }




        $_SESSION['success'] = 'Created successfuly';
        if ($status == 'approved') {
            header("Location:../../frontend/event-calendar.php");
        } else {
            header("Location:../../frontend/my-events.php");
        }
    } else {
        echo "Error inserting event: " . $conn->error;
    }
}
