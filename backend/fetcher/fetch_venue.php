<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $query = "SELECT * FROM venue";
    $result = $conn->query($query);
    $options = '<option value="">--</option>';


    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $options .= '<option value="' . $row['p_id'] . '">' . $row['p_code'] . ($row['major_code'] ? ' - ' . $row['major_code'] : '') . ' - ' . $row['description'] . '</option>';

        }
    }

    echo $options;


}



