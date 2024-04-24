<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $r_f_id = $_POST['r_f_id'];

    $query = "UPDATE response_form SET is_done = 'yes' WHERE r_f_id = ?";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("i", $r_f_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success'] = "Form submitted successfully";
        header("Location:../../frontend/event-calendar.php");
    } else {
        echo '0'; 
    }
}
