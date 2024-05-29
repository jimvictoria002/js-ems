<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $user_id = $_SESSION['user_id'];
    $creator_access = $_SESSION['access'];

    $query = "INSERT INTO forms (creator_id,creator_access, status) VALUES ($user_id,'$creator_access', 'not_done');";
    $result = $conn->query($query);

    $form_id = $conn->insert_id;

    // $query = "UPDATE events SET f_id = $form_id WHERE event_id = $event_id";
    // $result = $conn->query($query);

    $query = "INSERT INTO questionnaire (f_id, question, type) VALUES ($form_id, NULL, 'radio');";
    $result = $conn->query($query);

    $question_id = $conn->insert_id;

    $query = "INSERT INTO choices (q_id, choice_name) VALUES ($question_id, NULL );";
    $result = $conn->query($query);

    $choice_id = $conn->insert_id;

    // header('Location: ../../frontend/create-form.php?f_id='.$form_id);

    echo json_encode([
        'f_id' => $form_id
    ]);

}
