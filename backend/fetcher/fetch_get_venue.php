<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $v_id = $_POST['v_id'];


    $query = "SELECT * FROM venue WHERE v_id = $v_id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    echo json_encode($row);


}



