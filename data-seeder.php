<?php

require "./connection.php";



// insertResponse('student', 271, 12312);
// insertResponse('student', 266, 64, $offset = 64);




function insertResponse($respondent, $event_id, $limit, $offset = 0)
{

    switch ($respondent) {
        case 'teacher':
            $query = "SELECT id as response_id, first_name as firstname, middle_name as middlename, last_name as lastname, email as email FROM scheduling_system.teacher LIMIT $limit";
            break;
        case 'student':

            $query = "SELECT std_id as response_id, firstname as firstname, middlename as middlename, lastname as lastname, email as email FROM sis.students LIMIT $limit OFFSET $offset";

            break;
        case 'parent':
            $query = "SELECT id as response_id, firstname as firstname, middlename as middlename, lastname as lastname, email as email FROM sis.parent LIMIT $limit";
            break;

        default:
            return "Invalid respondent";
            break;
    }

    global $conn;


    $result = $conn->query($query);

    while ($res = $result->fetch_assoc()) {
        $response_id = $res['response_id'];
        $firstname = $res['firstname'];
        $middlename = $res['middlename'];
        $lastname = $res['lastname'];
        $email = $res['email'];

        $query = "SELECT f_id FROM events WHERE event_id = $event_id";
        $result_f_id = $conn->query($query);
        $row_f_id = $result_f_id->fetch_assoc()['f_id'];




        $query = "INSERT INTO 
                    response_form (event_id, f_id, respondent, response_id, is_done)
                  VALUES
                    ($event_id, $row_f_id , '$respondent', $response_id, 'yes')";
        $conn->query($query);

        $r_f_id = $conn->insert_id;

        $query = "INSERT INTO 
                    respondent_data (r_f_id, firstname, middlename, lastname, email)
                  VALUES
                    ($r_f_id, '$firstname', '$middlename', '$lastname', '$email')";
        $conn->query($query);


        $query = "SELECT f_id FROM events e WHERE e.event_id = $event_id";
        $re_f_id = $conn->query($query);
        $f_id = $re_f_id->fetch_assoc()['f_id'];

        $q_questionnaire = "SELECT * FROM questionnaire WHERE f_id = $f_id";
        $r_questionnaire = $conn->query($q_questionnaire);

        while ($questionnaire = $r_questionnaire->fetch_assoc()) {
            $q_id = $questionnaire['q_id'];
            $type = $questionnaire['type'];
            $required = $questionnaire['required'];

            // if ($required == 'yes') {
            if ($type == 'radio') {
                $choice_arr = [];

                $q_choices = "SELECT * FROM choices WHERE q_id = $q_id";
                $r_choices = $conn->query($q_choices);

                while ($choice = $r_choices->fetch_assoc()) {
                    $choice_arr[] = $choice['c_id'];
                }

                $randomKey = array_rand($choice_arr);

                $answer = $choice_arr[$randomKey];
                print_r($choice_arr);
                echo "</br>";
                echo $answer;

                echo "</br>";
                echo "</br>";
            } else {
                $choice_arr = [
                    'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
                    'Consequuntur magni quia voluptatibus!',
                    'Nisi adipisci placeat accusantium laboriosam, '
                ];

                $randomKey = array_rand($choice_arr);

                $answer = $choice_arr[$randomKey];
            }
            $query = "INSERT INTO 
                        response (r_f_id, q_id, answer)
                      VALUES ($r_f_id, $q_id, '$answer')";
            $conn->query($query);
            // }
        }
    }
}
