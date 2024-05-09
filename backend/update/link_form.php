<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $f_id = $_POST['f_id'];
    $event_id = $_POST['event_id'];


    $query = "UPDATE events SET f_id = $f_id WHERE event_id = $event_id";
    $result = $conn->query($query);
    $_SESSION['success'] = 'Form attached successfuly';
    header('location:../../frontend/my-form.php');
}
