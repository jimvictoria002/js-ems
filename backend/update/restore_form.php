<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $f_id = $_POST['f_id'];

    $query = "UPDATE forms SET status = 'active' WHERE f_id = $f_id";
    $result = $conn->query($query);

    $query = "SELECT title FROM forms WHERE f_id = $f_id ";
    $result = $conn->query($query);
    $title = $result->fetch_assoc()['title'];
    $_SESSION['success'] = "".$title." has been restored";
}
