<script src='../node_modules/@fullCalendar/core/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/daygrid/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/interaction/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/list/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/multimonth/index.global.min.js'></script>
<script src='../node_modules/@fullCalendar/timegrid/index.global.min.js'></script>

<div class="p-5 ">
    <div class="bg-white p-4">
        <h3 class="text-xl font-semibold md:text-3xl my-8 mt-3">Event calendar</h3>

        <div id="calendarContainer" class=" rounded-md w-full" style=" overflow: auto;">

            <div id="calendar" class="min-w-[40rem] border p-6"></div>
        </div>
    </div>

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
        'venue' => $event['venue'],
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
                window.location = "edit_event.php?event_id=" + e.event.id;
            },
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: 'short',
            },
            eventContent: function (arg) {
                return {
                    html: '<span class="event-con w-full"><div class="w-full p-1 flex py-1 justify-between flex-col"><div class="flex"><b class="pr-1 inline-flex items-center ">' + arg.event.extendedProps.stime + '<div class="rounded-full w-1 h-1 inline-block mx-1 bg-yellow-400"></div></b><p class="event-con">' + arg.event.title + '</p></div> <b class="px-1 event-con self-end">' + arg.event.extendedProps.etime + '</b></div>' + arg.event.extendedProps.venue + '</span>'
                };
            }
        });
        calendar.render();
    });
</script>

<!-- html: '<div class="w-full p-1 flex py-1 justify-between event-con"><div><b class="pr-1 inline-flex items-center ">' + arg.event.extendedProps.stime+ '<div class="rounded-full w-1 h-1 inline-block mx-1 bg-yellow-400"></div></b>' + arg.event.title+ '</div> <b class="px-1">' + arg.event.extendedProps.etime+ '</b></div>'+ arg.event.extendedProps.venue -->