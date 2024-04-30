<?php
require "../connection.php";
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    $q_f_id = "SELECT f_id FROM events WHERE event_id = $event_id";
    $res_f_id = $conn->query($q_f_id)->fetch_assoc();
    $f_id = $res_f_id['f_id'];

    $columns = ['name', 'type', 'email'];

    $q_cols = "SELECT * FROM questionnaire WHERE f_id = $f_id";
    $r_cols = $conn->query($q_cols);

    while ($cols = $r_cols->fetch_assoc()) {
        $columns[] = $cols['question'];
    }




    $data_to_export = [
        "columns" => $columns
    ];

    $query = "SELECT * FROM response_form WHERE event_id = $event_id AND is_done = 'yes'";

    $result = $conn->query($query);

    while ($response = $result->fetch_assoc()) {
        $respondent = $response['respondent'];
        $r_f_id = $response['r_f_id'];
        $response_id = $response['response_id'];
        $my_responses = [];

        switch ($respondent) {
            case 'teacher':
                $query = "SELECT * FROM scheduling_system.teacher WHERE id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['first_name'];
                $middlename = $user['middle_name'];
                $lastname = $user['last_name'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'student':
                $query = "SELECT * FROM sis.students WHERE std_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'parent':
                $query = "SELECT * FROM sis.parent WHERE id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $email = $user['email'];
                $fullname = $user['fullname'];

                break;
            case 'staff':
                $query = "SELECT * FROM users WHERE user_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'admin':
                $query = "SELECT * FROM users WHERE user_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'guest':
                $query = "SELECT * FROM guest WHERE guest_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;

            default:
                return "Invalid";
                break;
        }

        $query = "SELECT
        q.q_id,
        q.question,
        (
        SELECT
            answer
        FROM
            response r
        WHERE
            r.q_id = q.q_id AND r.r_f_id = $r_f_id
    ) AS answer,
    q.type,
    q.required
    FROM
        (
        SELECT
            q.q_id,
            q.question,
            q.type,
            q.required
        FROM
            questionnaire q
        WHERE
            q.f_id = $f_id
        ORDER BY
            q.created_at
    ) AS q";

        $r_responses = $conn->query($query);
        while ($responses = $r_responses->fetch_assoc()) {

            $answer = $responses['answer'];
            $type = $responses['type'];
            $question = $responses['question'];

            if ($type  == 'radio') {
                $query = "SELECT choice_name FROM choices WHERE c_id = $answer";
                $r_choice = $conn->query($query);
                $choice = $r_choice->fetch_assoc()['choice_name'];
                $my_responses[$question] = $choice;
            } else {
                $my_responses[$question] = $answer;
            }
        }

        $data_to_export['data'][] = [
            'fullname' => $fullname,
            'type' => $respondent,
            'email' => $email,
            'responses' => $my_responses,
        ];
    }
}

?>

<script>
    var data = <?= json_encode($data_to_export) ?>;
    console.log(data)
</script>