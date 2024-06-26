<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    if(!($_SESSION['access'] == 'admin' || $_SESSION['access'] == 'staff')){
        $status = "pending";
    }else{
        $status = "approved";

    }


    //File handling

    //Venue verify
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

    if ($_FILES['event_img']['name']) {
        echo "Has image </br>";
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




        //Insertion
        $title = $_POST['title'];
        $description = $_POST['description'];
        $v_id = $_POST['venue'];
        $start_datetime = $_POST['start_datetime'];
        $end_datetime = $_POST['end_datetime'];
        $event_id = $_POST['event_id'];
        $fileNameToStore = $fileName;

        $query = "SELECT event_img FROM events WHERE event_id = $event_id";
        $result = $conn->query($query);

        $fileNameToDelete = $result->fetch_assoc()['event_img'];

        if (file_exists($uploadDir . $fileNameToDelete)) {
            // Attempt to delete the file
            if (unlink($uploadDir . $fileNameToDelete)) {
                echo "File deleted successfully.";
            } else {
                echo "Error deleting file.";
            }
        }

        


        $query = "UPDATE events SET 
                title = ?, 
                description = ?, 
                event_img = ?, 
                start_datetime = ?, 
                end_datetime = ?, 
                v_id = ?,
                status = ? 
                WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssisi", $title, $description, $fileNameToStore, $start_datetime, $end_datetime, $v_id, $status, $event_id);
        $stmt->execute();

        if ($stmt->execute()) {

            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['success'] = 'Update successfuly';

            header("Location: $referrer");
        } else {
            echo "Error updating event event: " . $conn->error;
        }
    } else {



        //Insertion
        $title = $_POST['title'];
        $description = $_POST['description'];
        $start_datetime = $_POST['start_datetime'];
        $end_datetime = $_POST['end_datetime'];
        $v_id = $_POST['venue'];
        $event_id = $_POST['event_id'];



        $query = "UPDATE events SET 
            title = ?, 
            description = ?, 
            start_datetime = ?, 
            end_datetime = ?, 
            v_id = ?,
            status = ?
            WHERE event_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssisi", $title, $description, $start_datetime, $end_datetime, $v_id, $status, $event_id);

        if ($stmt->execute()) {

            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['success'] = 'Update successfuly';
            header("Location: $referrer");
        } else {
            echo "Error updating event event: " . $conn->error;
        }
    }
}
