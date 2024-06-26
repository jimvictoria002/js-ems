<script src='../node_modules/@fullCalendar/core/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/daygrid/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/interaction/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/list/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/multimonth/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/timegrid/index.global.min.js'></script>

<div id="calendarContainer" class=" rounded-md w-full" style=" overflow: auto;">

    <div id="calendar" class="min-w-[40rem] border p-6"></div>
</div>


<?php

$query = "SELECT v.venue, e.* FROM events e LEFT JOIN venue v ON e.v_id = v.v_id WHERE e.status = 'approved'";
$result = $conn->query($query);

$events = [];

while ($event = $result->fetch_assoc()) {


    $feedback = [];


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
        } else {
            $form = $r_form->fetch_assoc();
            $form_id = $form['f_id'];
            $user_id = $_SESSION['user_id'];
            $access = $_SESSION['access'];
            $q_rf = "SELECT * FROM response_form rf WHERE rf.event_id = $event_id AND rf.f_id = $form_id  AND response_id = $user_id AND respondent = '$access'";
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
            }
        }
    } else {
        $feedback['allow'] = false;
    }

    if ($start_date == $end_date) {
        $viewDate = $start_date . ' ' . $start_time  . ' - ' . $end_time;
    } else {
        $viewDate = $start_date . ' ' . $start_time  . ' - ' . $end_date . ' ' . $end_time;
    }

    // $q_ea = "SELECT * FROM event_access WHERE event_id = $event_id";
    // $r_eq = $conn->query($q_ea);
    $hasAccess = (($event['created_by'] == $_SESSION['user_id'] && $event['creator_access'] == $access) || ($_SESSION['access'] == 'admin' || $_SESSION['access'] == 'staff'));

    // if (!$hasAccess) {
    //     while ($event_acess = $r_eq->fetch_assoc()) {
    //         $hasAccess = ($event_acess['user_id'] == $_SESSION['user_id'] && $event_acess['access'] == $_SESSION['access']);
    //         if ($hasAccess) {
    //             break;
    //         }
    //     }
    // }



    $events[] = [
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
        'hasAccess' => $hasAccess,
        'stime' => $start_time,
        'etime' => $end_time,
        'color' => '#2E6B45'
    ];
}


?>

<script>
    let events = <?php echo json_encode($events); ?>;
    console.log(events)

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            aspectRatio: 2,
            contentHeight: 800,
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            events: events,
            eventClick: function(e) {
                let event_id = e.event.id;
                let title = e.event.title;
                let description = e.event.extendedProps.description;
                let eventImg = e.event.extendedProps.eventImg;
                let venue = e.event.extendedProps.venue;
                let viewDate = e.event.extendedProps.viewDate;
                let hasAccess = e.event.extendedProps.hasAccess;

                let creatorId = e.event.extendedProps.creatorId;
                let allow = e.event.extendedProps.feedback.allow;

                let feedbackBtn = $('#feedback-btn');
                $('#edit-btn').off('click');
                feedbackBtn.off('click');
                if (allow) {
                    console.log(allow);
                    feedbackBtn.show();
                    feedbackBtn.removeClass('bg-orange-500 hover:bg-orange-400 text-white bg-yellow-400 hover:bg-yellow-300 transition-default text-white ')
                    feedbackBtn.addClass('bg-main hover:bg-green-700 text-white')
                    feedbackBtn.html('Send feedback');
                    feedbackBtn.click(function() {
                        $.ajax({
                            url: '../backend/create/create_response_form.php',
                            type: 'POST',
                            data: {
                                event_id: event_id
                            },
                            success: function(r_f_id) {

                                console.log(r_f_id)

                                window.location = "form.php?r_f_id=" + r_f_id;

                            }
                        });
                    })

                } else {
                    let status = e.event.extendedProps.feedback.status;
                    if (status == 'done') {
                        let r_f_id = e.event.extendedProps.feedback.r_f_id;
                        console.log('done')
                        feedbackBtn.show();
                        feedbackBtn.removeClass('bg-main hover:bg-green-700 text-white')
                        feedbackBtn.removeClass('bg-orange-500 hover:bg-orange-400 text-white')
                        feedbackBtn.addClass('bg-yellow-400 hover:bg-yellow-300 transition-default text-white ')
                        feedbackBtn.html('View feedback');
                        feedbackBtn.click(function() {
                            window.location = "form.php?r_f_id=" + r_f_id;
                        })

                    } else if (status == 'not_done') {
                        let r_f_id = e.event.extendedProps.feedback.r_f_id;
                        console.log('not_done')
                        feedbackBtn.show();

                        feedbackBtn.removeClass('bg-main hover:bg-green-700 text-white bg-yellow-400 hover:bg-yellow-300 transition-default text-white ')
                        feedbackBtn.addClass('bg-orange-500 hover:bg-orange-400 text-white')
                        feedbackBtn.html('Resume evaluation');

                        feedbackBtn.click(function() {
                            window.location = "form.php?r_f_id=" + r_f_id;
                        })



                    } else {
                        console.log('not allow')
                        feedbackBtn.hide();
                    }

                }



                $('#view-event-modal').fadeToggle('fast');
                $('#event-title').text(title);

                if (description != '') {
                    $('#event-description-parent').show();
                    $('#event-description').text(description);
                    $('#event-venue').parent().removeClass('mt-3');


                } else {
                    $('#event-description-parent').hide();
                    $('#event-description').text(description);
                    $('#event-venue').parent().addClass('mt-3');

                }

                $('#event-venue').text(venue);
                $('#view-date').text(viewDate);
                $('#view-img').prop('src', (eventImg))


                if (hasAccess) {
                    $('#edit-btn').show();
                    $('#edit-btn').on('click', function() {
                        window.location = "edit_event.php?event_id=" + event_id;
                    })

                } else {
                    $('#edit-btn').hide();
                }





            },
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: 'short',
            },
            eventContent: function(arg) {
                return {
                    html: '<span class="event-con p-1  py-1 w-full text-xs"><div class="w-full flex justify-between flex-col"><div class="flex"><b class="pr-1 inline-flex items-center text-xs">' + arg.event.extendedProps.stime + '<div class="rounded-full w-1 h-1 inline-block mx-1 bg-yellow-400"></div></b><p class="event-con text-sm">' + arg.event.title + '</p></div> <b class="px-1 event-con self-end text-xs">' + arg.event.extendedProps.etime + '</b></div>' + arg.event.extendedProps.venue + '</span>'
                };
            },
            eventBackgroundColor: '#2E6B45'
        });
        calendar.render();
    });
</script>

<div class="bg-yellow-400 hover:bg-yellow-300"></div>

<!-- html: '<div class="w-full p-1 flex py-1 justify-between event-con"><div><b class="pr-1 inline-flex items-center ">' + arg.event.extendedProps.stime+ '<div class="rounded-full w-1 h-1 inline-block mx-1 bg-yellow-400"></div></b>' + arg.event.title+ '</div> <b class="px-1">' + arg.event.extendedProps.etime+ '</b></div>'+ arg.event.extendedProps.venue -->