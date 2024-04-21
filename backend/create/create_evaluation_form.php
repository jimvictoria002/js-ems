<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $event_id = $_POST['event_id'];

    $query = "INSERT INTO forms (event_id) VALUES ($event_id);";
    $result = $conn->query($query);

    $form_id = $conn->insert_id;

    $query = "INSERT INTO questionnaire (f_id, question, type) VALUES ($form_id, NULL, 'radio');";
    $result = $conn->query($query);

    $question_id = $conn->insert_id;

    $query = "INSERT INTO choices (q_id, choice_name) VALUES ($question_id, NULL );";
    $result = $conn->query($query);

    $choice_id = $conn->insert_id;

    echo $form_id;
}



