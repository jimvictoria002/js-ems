<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $data = $_GET['data'];

    echo json_encode(getEventInfo($data));

}


function getEventInfo($event)
{



    $start_datetime = $event['start_datetime'];
    $end_datetime = $event['end_datetime'];

    $start_date = date("F d, Y", strtotime($start_datetime));
    $end_date = date("F d, Y", strtotime($end_datetime));
    $start_time = date("g:ia", strtotime($start_datetime));
    $end_time = date("g:ia", strtotime($end_datetime));

    if ($start_date == $end_date) {
        $viewDate = $start_date . ' ' . $start_time  . ' - ' . $end_time;
    } else {
        $viewDate = $start_date . ' ' . $start_time  . ' - ' . $end_date . ' ' . $end_time;
    }

    return  [
        'id' => $event['event_id'],
        'title' => $event['title'],
        'description' => $event['description'],
        'start' => $start_datetime,
        'end' => $end_datetime,
        'eventImg' => '../uploads/event_img/' . $event['event_img'],
        'venue' => $event['venue'],
        'viewDate' => $viewDate,
        'v_id' => $event['v_id'],
        'creatorId' => $event['created_by'],
        'creatorAccess' => $event['creator_access'],
        'stime' => $start_time,
        'etime' => $end_time,
        'color' => '#2E6B45'
    ];
}