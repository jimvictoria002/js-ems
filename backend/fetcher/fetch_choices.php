<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $q_id = $_POST['q_id'];

    $query = "SELECT * FROM choices WHERE q_id = $q_id;";
    $result = $conn->query($query);


    echo 'deleted';
}



