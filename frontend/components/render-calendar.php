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

$query = "SELECT * FROM events e LEFT JOIN venue v ON e.v_id = v.v_id WHERE e.status = 'approved'";
$result = $conn->query($query);

$events = [];

while ($event = $result->fetch_assoc()) {
    $events[] = [
        'id' => $event['event_id'],
        'title' => $event['title'],
        'description' => $event['description'],
        'start' => $event['start_datetime'],
        'end' => $event['end_datetime'],
        'eventImg' => '../uploads/event_img/' . $event['event_img'],
        'venue' => $event['venue'],
        'viewDate' => (
            date("F d, Y", strtotime($event['start_datetime'])) == date("F d, Y", strtotime($event['end_datetime']))
            ? date("F d, Y", strtotime($event['start_datetime'])) . ' ' . date("g:ia", strtotime($event['start_datetime'])) . ' - ' . date("g:ia", strtotime($event['end_datetime']))
            : date("F d, Y", strtotime($event['start_datetime'])) . ' ' . date("g:ia", strtotime($event['start_datetime'])) . ' - ' . date("F d, Y", strtotime($event['end_datetime'])) . ' ' . date("g:ia", strtotime($event['end_datetime']))
        ),
        'creatorId' => $event['created_by'],
        'stime' => date("g:ia", strtotime($event['start_datetime'])),
        'etime' => date("g:ia", strtotime($event['end_datetime'])),
        'color' => '#2E6B45'
    ];
}


?>

<script>
    let events = <?php echo json_encode($events); ?>;

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            aspectRatio: 2,
            contentHeight: 800,
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay,list'
            },
            events: events,
            eventClick: function (e) {
                let event_id = e.event.id;
                let title = e.event.title;
                let description = e.event.extendedProps.description;
                let eventImg = e.event.extendedProps.eventImg;
                let venue = e.event.extendedProps.venue;
                let viewDate = e.event.extendedProps.viewDate;

                let creatorId = e.event.extendedProps.creatorId;


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

                console.log(creatorId)
                console.log(<?= $_SESSION['user_id'] ?>)

                if ('<?= $_SESSION['access'] ?>' != 'admin') {
                    if (creatorId != '<?= $_SESSION['user_id'] ?>') {
                        $('#edit-btn').hide();
                    } else {
                        $('#edit-btn').show();
                        $('#edit-btn').on('click', function () {
                            window.location = "edit_event.php?event_id=" + event_id;
                        })
                    }
                } else {
                    $('#edit-btn').on('click', function () {
                        window.location = "edit_event.php?event_id=" + event_id;
                    })
                }





                // window.location = "edit_event.php?event_id=" + e.event.id;
            },
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: 'short',
            },
            eventContent: function (arg) {
                return {
                    html: '<span class="event-con p-1  py-1 w-full text-xs"><div class="w-full flex justify-between flex-col"><div class="flex"><b class="pr-1 inline-flex items-center text-xs">' + arg.event.extendedProps.stime + '<div class="rounded-full w-1 h-1 inline-block mx-1 bg-yellow-400"></div></b><p class="event-con text-sm">' + arg.event.title + '</p></div> <b class="px-1 event-con self-end text-xs">' + arg.event.extendedProps.etime + '</b></div>' + arg.event.extendedProps.venue + '</span>'
                };
            }
        });
        calendar.render();
    });
</script>

<!-- html: '<div class="w-full p-1 flex py-1 justify-between event-con"><div><b class="pr-1 inline-flex items-center ">' + arg.event.extendedProps.stime+ '<div class="rounded-full w-1 h-1 inline-block mx-1 bg-yellow-400"></div></b>' + arg.event.title+ '</div> <b class="px-1">' + arg.event.extendedProps.etime+ '</b></div>'+ arg.event.extendedProps.venue -->