<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $f_id = $_POST['f_id'];

    $query = "DELETE FROM forms WHERE f_id = $f_id;";
    $result = $conn->query($query);


    echo 'deleted';
}



