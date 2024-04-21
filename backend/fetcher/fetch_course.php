<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $dept_id = isset($_GET['dept_id']) ? $_GET['dept_id'] : '';

    $query = "SELECT p.p_id, p.p_code, m.major_code, p.description FROM programs p LEFT JOIN majors m ON p.major_id = m.major_id  WHERE p.dept_id = '$dept_id'";


    if (!$result = $conn->query($query)) {
        $options = '<option value="">--</option>';
        echo $options;
        exit;
    }


    if ($result->num_rows > 0) {
        $options = '<option value="">--</option>';

        while ($row = $result->fetch_assoc()) {

            $options .= '<option value="' . $row['p_id'] . '">' . $row['p_code'] . ($row['major_code'] ? ' - ' . $row['major_code'] : '') . ' - ' . $row['description'] . '</option>';

        }


    } else {
        $options = '<option value="">--</option>';

    }

    echo $options;

}



