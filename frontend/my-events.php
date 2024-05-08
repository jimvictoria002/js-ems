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
                    'status' => $event['status'],
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
            <div class="table-container w-full overflow-auto p-7 border">


                <?php if ($total_data  > 0) : ?>
                    <table class="w-full min-w-[34rem] " id="my-events-tbl">
                        <thead>

                            <tr>
                                <th class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Title
                                </th>
                                <th class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Venue
                                </th>
                                <th class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Status
                                </th>
                                <th class="text-center border border-green-800 font-semibold py-1 px-3  text-base md:text-lg text-white bg-main ">Action
                                </th>
                            </tr>
                        </thead>

                        <?php while ($p_event = $result->fetch_assoc()) :

                            $creator_id = $p_event['created_by'];
                            $sub_pending = getEventInfo($p_event);


                            $pending_events[] = $sub_pending;
                        ?>

                        <?php endwhile; ?>


                        <script>
                            let pendingEvents = <?= json_encode($pending_events) ?>;
                            console.log(pendingEvents)
                            $('#my-events-tbl').DataTable({
                                data: pendingEvents,
                                ordering: false,
                                paging: true,
                                pageLength: 10,
                                info: true,
                                columns: [{
                                        data: 'title',
                                        className: 'py-2 px-3 border text-start text-sm md:text-base'
                                    },
                                    {
                                        data: 'venue',
                                        className: 'py-2 px-3 border !text-start text-sm md:text-base'
                                    },
                                    {
                                        data: null,
                                        className: 'py-2 px-3 border !text-center text-sm md:text-base',
                                        render: function(data, type, row) {
                                            let buttons = '';

                                            // console.log(data.status)

                                            if(data.status == 'pending'){
                                                buttons = `
                                                <p class="w-10 h-10  mx-auto flex justify-center items-center rounded-full  bg-orange-700 text-white text-center  font-semibold"><i class="fa-regular fa-hourglass-half"></i></p>`;
                                            }else{
                                                buttons = `
                                                <p class="w-10 h-10  mx-auto flex justify-center items-center rounded-full  bg-green-500 text-white text-center  font-semibold"> <i class="fa-solid fa-check-circle"></i></p>
                                               `;
                                            }

                                            return `${buttons}`;
                                        }
                                    },
                                    {
                                        data: null,
                                        className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                                        render: function(data, type, row) {

                                            let buttons = '';

                                            let v_id = data.v_id;
                                            let event_id = data.id;
                                            let title = data.title;
                                            let venue = data.venue;
                                            let created_by = data.created_by;
                                            let creator_access = data.creator_access;
                                            let total_in_use = data.total_in_use;



                                            // console.log(v_id)
                                            // console.log(created_by)
                                            // console.log(creator_access)

                                            buttons += `
                                        
                                                <button onclick="viewEvent(${JSON.stringify(data).replace(/"/g, '&quot;')})" class="w-28 py-1.5 mx-1 self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                                                    View
                                                </button>
                                            `;


                                            buttons += ` <button type="button" onclick="deleteEvent('${event_id}')" class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>`;



                                            return `${buttons}`;
                                        }
                                    },
                                ],

                                rowCallback: function(row, data, index) {
                                    if (index % 2 === 0) {
                                        $(row).addClass('bg-gray-50');
                                    }
                                    $(row).addClass('hover:bg-gray-100');

                                }
                            });


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

                                $('#approve-btn').off('click');
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

                                if ('<?= $access ?>' == 'admin' || '<?= $access ?>' == 'staff') {
                                    $('#approve-btn').show();

                                    $('#approve-btn').on('click', function() {
                                        approveEvent(event_id, v_id);
                                    });

                                }


                            }


                            function deleteEvent(event_id) {
                                $.ajax({
                                    type: "POST",
                                    url: "../backend/fetcher/fetch_get_event.php",
                                    data: {
                                        event_id: event_id
                                    },
                                    success: function(response) {

                                        let data = JSON.parse(response);


                                        let title = data.title;

                                        if (confirm("Do you really want to delete the " + title)) {
                                            $.ajax({
                                                type: "POST",
                                                url: "../backend/delete/delete_event.php",
                                                data: {
                                                    event_id: event_id
                                                },
                                                success: function(response) {
                                                    window.location = "";
                                                }
                                            });
                                        }

                                    }
                                });

                            }
                        </script>

                    </table>
                <?php else : ?>
                    <p>No event created</p>
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
    if (isset($_SESSION['failed'])) :
        require "./components/failed-message.php";
        unset($_SESSION['failed']);
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