<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $q_id = $_POST['q_id'];

    $query = "INSERT INTO choices (q_id, choice_name) VALUES ($q_id, NULL );";
    $result = $conn->query($query);

    $choice_id = $conn->insert_id;

    echo $choice_id;
}



