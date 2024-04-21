<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $q_id = $_POST['q_id'];

    $query = "DELETE FROM questionnaire WHERE q_id = $q_id;";
    $result = $conn->query($query);


    echo 'deleted';
}



