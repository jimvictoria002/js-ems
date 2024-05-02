<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $access = $_SESSION['access'];

    $stmt = $conn->prepare("CALL insert_response(?, ?, ?, @r_f_id)");

    $stmt->bind_param("iss", $user_id, $event_id, $access);

    $stmt->execute();

    $result = $conn->query("SELECT @r_f_id as r_f_id");
    $row = $result->fetch_assoc();
    $r_f_id = $row['r_f_id'];

    if ($access == 'teacher') {
        $firstname = $_SESSION['first_name'];
        $lastname = $_SESSION['last_name'];
        $middlename = $_SESSION['middle_name'];
        $email = $_SESSION['email'];
    } else {
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        $middlename = $_SESSION['middlename'];
        $email = $_SESSION['email'];
    }

    $query = "INSERT INTO respondent_data (r_f_id, firstname, middlename, lastname, email)
              VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($query);
    $stmt2->bind_param("issss", $r_f_id, $firstname, $middlename, $lastname, $email);
    $stmt2->execute();

    $stmt2->close();

    echo $r_f_id;
}
