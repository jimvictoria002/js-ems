<?php

require "../../connection.php";

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
        $venue = $_POST['input-venue'];
        $query = "INSERT INTO venue (venue) VALUES ('$venue');";
        $conn->query($query);
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

    $status = $_SESSION['access'] == 'admin' ? 'approved' : 'pending';


    $query = "INSERT INTO `events` 
    (`title`, `description`, `event_img`, `start_datetime`, `end_datetime`, `v_id`, `status`, `created_by`) 
    VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("sssssssi", $title, $description, $fileNameToStore, $start_datetime, $end_datetime, $v_id, $status, $created_by);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $event_id = $conn->insert_id;
        $_SESSION['success'] = 'Created successfuly';
        header("Location:../../frontend/edit_event.php?event_id=$event_id");
    } else {
        echo "Error inserting event: " . $conn->error;
    }




}
