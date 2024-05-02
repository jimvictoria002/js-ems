<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {


    $event_id = $_GET['event_id'];

    $event_info = getEventInfo($event_id);

    echo json_encode($event_info);
}
function getEventInfo($event_id)
{
    session_start();
    global $_SESSION;
    global $conn;
    $user_id = $_SESSION['user_id'];


    $query = "SELECT v.venue, e.* FROM events e 
    LEFT JOIN venue v ON e.v_id = v.v_id 
    WHERE e.status = 'approved' AND e.event_id = $event_id
    ORDER BY e.created_at DESC;";


    $result = $conn->query($query);
    $event = $result->fetch_assoc();



    $start_datetime = $event['start_datetime'];
    $end_datetime = $event['end_datetime'];

    $start_date = date("F d, Y", strtotime($start_datetime));
    $end_date = date("F d, Y", strtotime($end_datetime));
    $start_time = date("g:ia", strtotime($start_datetime));
    $end_time = date("g:ia", strtotime($end_datetime));

    $event_id = $event['event_id'];
    $f_id = $event['f_id'];

    $q_form = "SELECT * FROM forms WHERE f_id = '$f_id'";
    $r_form = $conn->query($q_form);

    //Check form if exists
    if ($r_form->num_rows > 0) {

        $currentDateTime = strtotime(date('Y-m-d H:i:s'));

        $eventEndDateTime = strtotime($end_datetime);

        //Check date if current
        if ($currentDateTime < $eventEndDateTime) {
            $feedback['allow'] = false;
            $feedback['status'] = 'define';

        } else {
            $form = $r_form->fetch_assoc();
            $form_id = $form['f_id'];
            $user_id = $_SESSION['user_id'];
            $access = $_SESSION['access'];
            $q_rf = "SELECT * FROM response_form rf WHERE rf.event_id = $event_id  AND response_id = $user_id AND respondent = '$access'";
            $r_rf = $conn->query($q_rf);

            //Check user if already response
            if ($r_rf->num_rows > 0) {
                $rf = $r_rf->fetch_assoc();
                $status = $rf['is_done'];
                $feedback['r_f_id'] = $rf['r_f_id'];

                //Check user if done
                if ($status == 'yes') {
                    $feedback['status'] = 'done';
                } else {
                    $feedback['status'] = 'not_done';
                }
            } else {
                $feedback['allow'] = true;
                $feedback['form_id'] = $form_id;
                $feedback['status'] = 'define';

            }
        }
    } else {
        $feedback['allow'] = false;
        $feedback['status'] = 'define';

    }

    if ($start_date == $end_date) {
        $viewDate = $start_date . ' ' . $start_time  . ' - ' . $end_time;
    } else {
        $viewDate = $start_date . ' ' . $start_time  . ' - ' . $end_date . ' ' . $end_time;
    }



    return [
        'id' => $event_id,
        'title' => $event['title'],
        'description' => $event['description'],
        'start' => $start_datetime,
        'end' => $end_datetime,
        'eventImg' => '../uploads/event_img/' . $event['event_img'],
        'venue' => $event['venue'],
        'feedback' => $feedback,
        'viewDate' => $viewDate,
        'creatorId' => $event['created_by'],
        'stime' => $start_time,
        'etime' => $end_time,
        'color' => '#2E6B45'
    ];
}
