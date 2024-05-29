<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $answer = $_POST['answer'];
    $q_id = $_POST['q_id'];
    $r_f_id = $_POST['r_f_id'];

    // Check if the record exists
    $query_select = "SELECT q_id FROM response WHERE q_id = ? AND r_f_id = ?";
    $stmt_select = $conn->prepare($query_select);
    $stmt_select->bind_param("ii", $q_id, $r_f_id);
    $stmt_select->execute();
    $stmt_select->store_result();

    if ($stmt_select->num_rows > 0) {
        // Record exists, so update it
        $query_update = "UPDATE response SET answer = ? WHERE q_id = ? AND r_f_id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("sii", $answer, $q_id, $r_f_id);
        $stmt_update->execute();

        if ($stmt_update->affected_rows > 0) {
            echo '1'; // Updated successfully
        } else {
            echo '0'; // Update failed
        }
    } else {
        // Record doesn't exist, so insert it
        $query_insert = "INSERT INTO response (answer, q_id, r_f_id) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("sii", $answer, $q_id, $r_f_id);
        $stmt_insert->execute();

        if ($stmt_insert->affected_rows > 0) {
            echo '1'; // Inserted successfully
        } else {
            echo '0'; // Insert failed
        }
    }


} else {
    echo "Invalid request method";
}
?>
