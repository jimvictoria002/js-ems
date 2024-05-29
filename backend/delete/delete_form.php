<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $f_id = $_POST['f_id'];
    $hard_delete = isset($_POST['hard']);

    if ($hard_delete) {

        $query = "UPDATE forms f SET status = 'permanent_delete' WHERE f.f_id = $f_id;";
        $result = $conn->query($query);
    } else {
        $query = "UPDATE events e SET f_id = NULL WHERE e.f_id = $f_id;";

        $result = $conn->query($query);

        $query = "UPDATE forms f SET status = 'deleted' WHERE f.f_id = $f_id;";
        $result = $conn->query($query);
    }



    session_start();
    $_SESSION['success'] = "Form deleted successfuly";


    echo 'deleted';
}
