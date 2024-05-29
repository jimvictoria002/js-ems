<?php

require "../../connection.php";



$query = "SELECT * FROM feedbacks f ORDER BY f.end_datetime DESC";
$result = $conn->query($query);

$data = [];

while ($feedback = $result->fetch_assoc()) {
    $data[] = $feedback;
}
echo json_encode($data);

