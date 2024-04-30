<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student')) {
    header('Location: dashboard.php');
    exit;
}


$title = 'My events';
require "../connection.php";
require "./partials/header.php";
$active = 'myevents';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">My events</p>

            <?php

            $user_id = $_SESSION['user_id'];


            $query = "SELECT 
                        v.venue, e.* 
                      FROM 
                        events e 
                      LEFT JOIN venue v ON
                         e.v_id = v.v_id 
                      WHERE e.created_by = $user_id AND 
                            e.creator_access = '$access'
                      ORDER BY 
                        e.created_at DESC;";




            $result = $conn->query($query);

            $total_data = $result->num_rows;


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


            ?>
            <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
                <button onclick="" class="toggle-create  px-6 py-2 self-center mr-5 md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl mb-5">Create
                    new event <i class="fa-solid fa-plus ml-1"></i></button>
            <?php endif; ?>
            <div class="table-container w-full overflow-auto border p-7 " id="pending-tbl">

                <?php if ($total_data > 0) : ?>
                    <table class="w-full min-w-[34rem] ">
                        <tr>
                            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Title
                            </th>
                            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Date/Time
                            </th>
                            <th rowspan="2" class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Venue
                            </th>
                            <th rowspan="2" class="text-center border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Status
                            </th>
                            <th colspan="2" rowspan="" class="text-center border border-green-800 font-semibold py-1 px-3  text-base md:text-lg text-white bg-main ">Action
                            </th>
                        </tr>
                        <tr>

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

                                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base whitespace-nowrap capitalize">

                                    <?php if ($event['status'] == 'pending') : ?>
                                        <p class="px-5 py-2 bg-orange-700 text-white text-center rounded-md font-semibold"><?= $event['status'] ?> <i class="fa-regular fa-hourglass-half"></i></p>
                                    <?php else : ?>
                                        <p class="px-5 py-2 bg-green-500 text-white text-center rounded-md font-semibold"><?= $event['status'] ?> <i class="fa-solid fa-check-circle"></i></p>
                                    <?php endif; ?>

                                </td>

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
                                let creatorAccess = event.creatorAccess;


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

                                $('#approve-btn').hide();
                                $('#edit-btn').off('click');

                                if ('<?= $_SESSION['access'] ?>' != 'admin' && '<?= $access ?>' != 'staff') {
                                    if (creatorId != '<?= $_SESSION['user_id'] ?>' && creatorAccess != '<?= $_SESSION['access'] ?>') {
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
                <?php else : ?>
                    <p>You don't have any events</p>
                <?php endif; ?>


            </div>


        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>

    <?php
    require "./components/modals/view-event-modal.php";
    ?>

    <?php
    require "./components/modals/create-event-modal.php";
    ?>



</main>

<?php
require "./partials/footer.php";
?>