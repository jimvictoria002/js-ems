<?php

$user_id = $_SESSION['user_id'];

$add_condition = $access != 'admin' ? "" : '';



if ($access == 'teacher') {
    $query = "SELECT * FROM events e 
    LEFT JOIN venue v ON e.v_id = v.v_id 
    WHERE e.status = 'pending' AND e.created_by = $user_id
    ORDER BY e.created_at DESC;";
} else {
    $query = "SELECT * FROM events e 
    LEFT JOIN venue v ON e.v_id = v.v_id 
    WHERE e.status = 'pending' 
    ORDER BY e.created_at DESC;";
}




$result = $conn->query($query);


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
        'stime' => $start_time,
        'etime' => $end_time,
        'color' => '#2E6B45'
    ];
}


?>

<div class="table-container w-full overflow-auto" id="pending-tbl">
    <table class="w-full min-w-[34rem] ">
        <tr>
            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Title
            </th>
            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Date/Time
            </th>
            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Venue
            </th>
            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Creator
            </th>
            <th colspan="<?= $access == 'admin' ? '3' : '2' ?>" rowspan="" class="text-center border border-green-800 font-semibold py-1 px-3  text-base md:text-lg text-white bg-main ">Action
            </th>
        </tr>
        <tr>
            <?php if ($access == 'admin') : ?>
                <th class="text-center border border-green-800  font-semibold py-1 px-3  text-md md:text-base text-white bg-main">Approve</th>
            <?php endif; ?>

            <th class="text-center border border-green-800  font-semibold py-1 px-3  text-sm md:text-base text-white bg-main">View</th>
            <th class="text-center border border-green-800  font-semibold py-1 px-3  text-sm md:text-base text-white bg-main">Delete</th>
        </tr>
        <?php while ($event = $result->fetch_assoc()) : ?>
            <tr class=" main-tr">

                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base ">
                    <?= $event['title'] ?>
                </td>
                <td class="py-4 px-3 border text-start text-sm md:text-base  whitespace-nowrap">
                    <?= date('M d, Y g:ia', strtotime($event['start_datetime'])) ?>
                </td>


                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base ">
                    <?= $event['venue'] ?>
                </td>

                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base whitespace-nowrap">

                    <?php
                    $creator_id = $event['created_by'];
                    if ($event['creator_access'] == 'admin') {
                        $q_creator = "SELECT * FROM users u WHERE u.user_id = $creator_id";
                        $r_creator = $conn->query($q_creator);
                        $creator = $r_creator->fetch_assoc();
                        echo $creator['firstname'][0] . ' ' . $creator['lastname'];
                    } else if ($event['creator_access'] == 'teacher') {
                        $q_creator = "SELECT * FROM scheduling_system.teacher t WHERE t.id = $creator_id";
                        $r_creator = $conn->query($q_creator);
                        $creator = $r_creator->fetch_assoc();
                        echo $creator['first_name'][0] . ' ' . $creator['last_name'];
                    }

                    ?>
                </td>
                <?php if ($access == 'admin') : ?>

                    <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                        <form action="../backend/update/approve_event.php" method="POST" id="approve-<?= $event['event_id'] ?>">
                            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                            <input type="hidden" name="status" value="approved">

                        </form>
                        <button onclick="checkConflict('<?= $event['start_datetime'] ?>', '<?= $event['end_datetime'] ?>', '<?= $event['v_id'] ?>', 'approve-<?= $event['event_id'] ?>', '<?= $event['title'] ?>')" class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-green-500 hover:bg-green-400  cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn"> <i class="fa-solid fa-check"></i></button>
                    </td>
                <?php endif ?>

                <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <button onclick="viewEvent(<?= htmlspecialchars(json_encode(getEventInfo($event))) ?>)" class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                        <div class="fa-solid fa-eye"></div>
                    </button>
                </td>

                <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <form action="../backend/delete/delete_event.php" method="POST" id="delete-<?= $event['event_id'] ?>">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    </form>
                    <button type="button" onclick="if(confirm('Do you really want to delete this event?'))$('#delete-<?= $event['event_id'] ?>').submit();" class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-red-700 hover:bg-red-600 cursor-pointer  transition-default text-white font-semibold rounded-xl" id="upt-btn"><i class="fa-solid fa-trash"></i></button>
                </td>


            </tr>

            <tr class="next-tr ">

                <td class="py-4 px-3 border text-start text-sm md:text-base  whitespace-nowrap">
                    <?= date('M d, Y g:ia', strtotime($event['end_datetime'])) ?>
                </td>
            </tr>

        <?php endwhile; ?>


        <script>
            function viewEvent(event) {
                let event_id = event.id;
                let title = event.title;
                let description = event.description;
                let eventImg = event.eventImg;
                let venue = event.venue;
                let viewDate = event.viewDate;
                let start_datetime = event.start;
                let end_datetime = event.end;
                let v_id = event.v_id;

                let creatorId = event.creatorId;


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

                $('#approve-btn').off('click');
                $('#edit-btn').off('click');

                if ('<?= $_SESSION['access'] ?>' != 'admin') {
                    if (creatorId != '<?= $_SESSION['user_id'] ?>') {
                        $('#edit-btn').hide();
                    } else {
                        $('#edit-btn').show();
                        $('#edit-btn').on('click', function() {
                            window.location = "edit_event.php?event_id=" + event_id;
                        })
                    }
                } else {
                    $('#edit-btn').show();
                    $('#edit-btn').on('click', function() {
                        window.location = "edit_event.php?event_id=" + event_id;
                    })
                }


                $('#approve-btn').show();

                $('#approve-btn').on('click', function() {
                    checkConflict(start_datetime, end_datetime, v_id, `approve-${event_id}`, title)
                });






            }

            function checkConflict(start_datetime, end_datetime, v_id, to_submit, title) {

                $.ajax({
                    type: "POST",
                    url: "../backend/validator/check_conflict.php",
                    data: {
                        start_datetime: start_datetime,
                        end_datetime: end_datetime,
                        v_id: v_id

                    },
                    success: function(response) {
                        if (response == 'false') {
                            if (confirm(`Do you really want to approve the ${title}?`)) {
                                $('#' + to_submit).submit();
                            }
                        } else {


                            alert('The venue is not available on that date/time');
                        }
                    }
                });
            }
        </script>

    </table>
</div>