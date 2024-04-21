<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $c_id = $_POST['c_id'];

    $query = "DELETE FROM choices WHERE c_id = $c_id;";
    $result = $conn->query($query);


    echo 'deleted';
}



