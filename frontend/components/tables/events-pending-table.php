<?php

$user_id = $_SESSION['user_id'];

$add_condition = $access != 'admin' ? "" : '';



if ($access == 'teacher') {
    $query = "SELECT v.venue, e.* FROM events e 
    LEFT JOIN venue v ON e.v_id = v.v_id 
    WHERE e.status = 'pending' AND e.created_by = $user_id AND e.creator_access = '$access'
    ORDER BY e.created_at DESC;";
} else if ($access == 'student') {
    $query = "SELECT v.venue, e.* FROM events e 
    LEFT JOIN venue v ON e.v_id = v.v_id 
    WHERE e.status = 'pending' AND e.created_by = $user_id AND e.creator_access = '$access'
    ORDER BY e.created_at DESC;";
} else {
    $query = "SELECT  v.venue, e.* FROM events e 
    LEFT JOIN venue v ON e.v_id = v.v_id 
    WHERE e.status = 'pending' 
    ORDER BY e.created_at DESC;";
}




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

$pending_events = [];


?>

<div class="table-container w-full overflow-auto p-7 border">


    <?php if ($total_data  > 0) : ?>
        <table class="w-full min-w-[34rem] " id="pending-tbl">
            <thead>

                <tr>
                    <th class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Title
                    </th>
                    <th class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Venue
                    </th>
                    <th class="text-start border border-green-800 font-semibold py-4 px-3  text-base md:text-lg text-white bg-main ">Creator
                    </th>
                    <th class="text-center border border-green-800 font-semibold py-1 px-3  text-base md:text-lg text-white bg-main ">Action
                    </th>
                </tr>
            </thead>

            <?php while ($p_event = $result->fetch_assoc()) :

                $creator_id = $p_event['created_by'];
                $sub_pending = getEventInfo($p_event);
                if ($p_event['creator_access'] == 'teacher') {
                    $q_creator = "SELECT * FROM hrms.employees t WHERE t.id = $creator_id";
                    $r_creator = $conn->query($q_creator);
                    $creator = $r_creator->fetch_assoc();
                    $creator_name =  $creator['firstname'][0] . '. ' . $creator['lastname']  .  ' - ' . ucfirst($p_event['creator_access']);
                } else if ($p_event['creator_access'] == 'student') {
                    $q_creator = "SELECT * FROM sis.students s WHERE s.std_id = $creator_id";
                    $r_creator = $conn->query($q_creator);
                    $creator = $r_creator->fetch_assoc();
                    $creator_name =  $creator['firstname'][0] . '. ' . $creator['lastname'] .  ' - ' . ucfirst($p_event['creator_access']);
                } else {
                    $q_creator = "SELECT * FROM users u WHERE u.user_id = $creator_id";
                    $r_creator = $conn->query($q_creator);
                    $creator = $r_creator->fetch_assoc();
                    $creator_name =  $creator['firstname'][0] . '. ' . $creator['lastname'];
                }


                $sub_pending['creator_name'] = $creator_name;

                $pending_events[] = $sub_pending;
            ?>

            <?php endwhile; ?>


            <script>
                let pendingEvents = <?= json_encode($pending_events) ?>;
                $('#pending-tbl').DataTable({
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
                            data: 'creator_name',
                            className: 'py-2 px-3 border !text-start text-sm md:text-base'
                        },
                        {
                            data: null,
                            className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                            render: function(data, type, row) {

                                let buttons = '';

                                let v_id = data.v_id;
                                let venue = data.venue;
                                let created_by = data.created_by;
                                let creator_access = data.creator_access;
                                let total_in_use = data.total_in_use;



                                // console.log(v_id)
                                // console.log(created_by)
                                // console.log(creator_access)

                                buttons += `
                                <button onclick="approveEvent('${data.start}', '${data.end}', '${data.v_id}', '${data.title}', '${data.id}')" class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm bg-green-500 hover:bg-green-400  cursor-pointer transition-default text-white mr- font-semibold rounded-xl" id="upt-btn"> Approve</button>
                        
                                    <button onclick='viewEvent(` + JSON.stringify(data) + `)' class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 6 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                                        View
                                    </button>
                                `;

                                if (total_in_use > 0) {
                                    buttons += `<button type="button" disabled class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm  bg-red-700  opacity-30 transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>`;
                                } else {
                                    buttons += ` <button type="button" onclick="deleteVenue(${v_id})" class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>`;
                                }


                                return `${buttons}
                                    `;
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
                            approveEvent(start_datetime, end_datetime, v_id, title, event_id);
                        });

                    }


                }

                function approveEvent(start_datetime, end_datetime, v_id, title, event_id) {
                    // console.log(start_datetime)
                    // console.log(end_datetime)
                    // console.log(v_id)
                    // console.log(title)

                    let conflict;

                    checkConflict(start_datetime, end_datetime, v_id)
                        .then(function(result) {
                            conflict = result;
                            if (conflict) {
                                if (confirm(`Do you really want to approve ${title}`)) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../backend/update/approve_event.php",
                                        data: {
                                            status: 'approved',
                                            reqajx: 'reqajx',
                                            event_id: event_id,
                                        },
                                        success: function(response) {
                                            window.location = '';
                                        },
                                        error: function(xhr, status, error) {
                                        }
                                    });
                                }
                            } else {
                                alert('The venue is not available in that date/time \n\nPlease change the date/time to approve this event');
                            }
                        })
                        .catch(function(error) {
                            alert('Something wrong' + error);
                        });


                }

                function checkConflict(start_datetime, end_datetime, v_id) {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            type: "POST",
                            url: "../backend/validator/check_conflict.php",
                            data: {
                                start_datetime: start_datetime,
                                end_datetime: end_datetime,
                                v_id: v_id
                            },
                            success: function(response) {
                                resolve(response == 'false');
                            },
                            error: function(xhr, status, error) {
                                reject(error);
                            }
                        });
                    });
                }
            </script>

        </table>
    <?php else : ?>
        <p>No pending events</p>
    <?php endif; ?>
</div>