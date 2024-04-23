<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $v_id = $_POST['v_id'];

    $query = "DELETE FROM venue WHERE v_id = $v_id;";
    $result = $conn->query($query);


    echo 'deleted';
}



