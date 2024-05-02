<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}


require "../connection.php";



$currentMonth = date('n');
$currentYear = date('Y');

// $start_at = $currentMonth > 6 ? 6 : 0;
// $end_at = $currentMonth > 6 ? 11 : 5;

$monthly_event_data = [];

$months = [
    'January', 'February', 'March', 'April', 'May', 'June', 'July',
    'August', 'September', 'October', 'November', 'December'
];



for ($i = 0; $i <= 11; $i++) {
    $q_m_e = "SELECT
            COUNT(*) AS total
        FROM
            `events` e
        WHERE
            YEAR(e.start_datetime) = '$currentYear' AND MONTH(e.start_datetime) = '" . ($i + 1) . "'
        GROUP BY
            MONTH(e.start_datetime)";

    $r_m_e = $conn->query($q_m_e);
    $row = $r_m_e->fetch_assoc();
    $total = isset($row['total']) ? $row['total'] : 0;

    $monthly_event_data[$months[$i]] = $total;
}


$monthly_response_sent = [];

for ($i = 0; $i <= 11; $i++) {
    $q_m_e = "SELECT
            COUNT(*) AS total
        FROM
            `response_form` rf
        WHERE
            YEAR(rf.created_at) = '$currentYear' AND MONTH(rf.created_at) = '" . ($i + 1) . "'
        GROUP BY
            MONTH(rf.created_at)";

    $r_m_e = $conn->query($q_m_e);
    $row = $r_m_e->fetch_assoc();
    $total = isset($row['total']) ? $row['total'] : 0;

    $monthly_response_sent[$months[$i]] = $total;
}


$incoming_events = [];
$past_events = [];

$title = 'Dashboard';

require "./partials/header.php";
$active = 'dashboard';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50 px-3">
    <?php
    require "./components/top-nav.php";
    ?>

    <style>
        .dt-start{
            display: none !important;
        }
    </style>

    <div class="w-full flex mt-2 flex-col bg-white p-4 mb-40">
        <p class="text-xl font-semibold md:text-3xl my-2 mt-3">Dashboard</p>


        <!-- Search -->
        <div class="self-end  flex flex-col  my-3 relative" id="seach-container">
            <?php

            $query = "SELECT event_id, title, event_img, v.venue, e.end_datetime FROM events e INNER JOIN venue v ON e.v_id = v.v_id WHERE e.status = 'approved' ORDER BY e.end_datetime DESC";
            $result = $conn->query($query);
            $data_to_search = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data_to_search[] = [
                        'id' => $row['event_id'],
                        'title' => $row['title'],
                        'label' => $row['title'],
                    ];

                    $currentDate = strtotime(date("Y-m-d H:i:s"));
                    $end_date = strtotime($row['end_datetime']);

                    if ($currentDate < $end_date) {
                        $incoming_events[] = [
                            'event_id' => $row['event_id'],
                            'title' => $row['title'],
                            'venue' => $row['venue'],
                            'event_img' => $row['event_img'],
                        ];
                    } else {
                        $past_events[] = [
                            'event_id' => $row['event_id'],
                            'title' => $row['title'],
                            'venue' => $row['venue'],
                            'event_img' => $row['event_img'],
                        ];
                    }
                }
            }


            ?>
            <script src="../node_modules/jquery-ui/jquery-ui.min.js"></script>
            <link rel="stylesheet" href="../node_modules/jquery-ui/jquery-ui.css">

            <input type="text" id="autocomplete-input" placeholder="Search event.." autocomplete="off" class="p-1 border md:text-lg">

            <script>
                var data_to_search = <?= json_encode($data_to_search) ?>;
                // console.log(data_to_search);
                $(function() {
                    $("#autocomplete-input").autocomplete({
                        source: function(request, response) {
                            var term = request.term.toLowerCase();
                            var regex = new RegExp(term, "i");

                            var filtered = $.grep(data_to_search, function(item) {
                                return regex.test(item.label.toLowerCase());
                            });

                            response(filtered.slice(0, 5));
                        },
                        minLength: 0,
                        select: function(event, ui) {
                            var selectedValue = ui.item.label;
                            var selectedId = ui.item.id;
                            var event_data;

                            fetch_view(selectedId);

                        },
                        appendTo: "#seach-container"
                    });

                    $("#autocomplete-input").click(function() {
                        $(this).autocomplete("search", "");
                    });
                });

                function fetch_view(selectedId) {
                    $.ajax({
                        type: "GET",
                        url: "../backend/fetcher/fetch_selected.php",
                        data: {
                            event_id: selectedId
                        },
                        success: function(response) {
                            // console.log(response)
                            viewEvent(JSON.parse(response))
                        }
                    });
                }

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
                    let allow = event.feedback.allow;


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
                    };

                    let feedbackBtn = $('#feedback-btn');
                    feedbackBtn.off('click');

                    console.log(allow);


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
                        let status = event.feedback.status;
                        if (status == 'done') {
                            let r_f_id = event.feedback.r_f_id;
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
                            let r_f_id = event.feedback.r_f_id;
                            console.log('not_done')
                            feedbackBtn.show();

                            feedbackBtn.removeClass('bg-main hover:bg-green-700 text-white bg-yellow-400 hover:bg-yellow-300 transition-default text-white ')
                            feedbackBtn.addClass('bg-orange-500 hover:bg-orange-400 text-white')
                            feedbackBtn.html('Resume evaluation');

                            feedbackBtn.click(function() {
                                window.location = "form.php?r_f_id=" + r_f_id;
                            })



                        } else {
                            console.log(status)
                            feedbackBtn.hide();
                        }

                    }




                }
            </script>

        </div>

        <?php if (!($access == 'guest' || $access == 'parent')) : ?>

            <div class="flex w-full gap-[3%] mb-10 flex-wrap gap-y-10 my-4">
                <a href="event-calendar.php" class="flex shadow-md  rounded w-full sm:w-[47.3%] md:w-[31.3%] flex-col">
                    <div class="flex flex-col bg-main p-3 relative overflow-hidden">
                        <p class="text-xl z-10 lg:text-2xl text-white font-bold">
                            Event calendar
                        </p>

                        <i class="fa-solid fa-calendar-days  text-5xl lg:text-6xl text-white self-end"></i>

                    </div>
                    <div class="flex p-3 text-[#33272a] bg-[#fffcfa]">
                        <p>
                            View event as a calendar
                        </p>
                    </div>
                </a>
                <a href="my-events.php" class="flex shadow-md  rounded w-full sm:w-[47.3%] md:w-[31.3%] flex-col">
                    <div class="flex flex-col bg-main p-3 relative overflow-hidden">
                        <p class="text-xl z-10 lg:text-2xl text-white font-bold">
                            My events
                        </p>

                        <i class="fa-solid fa-calendar-check  text-5xl lg:text-6xl text-white self-end"></i>

                    </div>
                    <div class="flex p-3 text-[#33272a] bg-[#fffcfa]">
                        <p>
                            View your created events
                        </p>
                    </div>
                </a>
                <a href="feedbacks.php" class="flex shadow-md  rounded w-full sm:w-[47.3%] md:w-[31.3%] flex-col">
                    <div class="flex flex-col bg-main p-3 relative overflow-hidden">
                        <p class="text-xl z-10 lg:text-2xl text-white font-bold">
                            Feedbacks
                        </p>

                        <i class="fa-solid fa-envelope-open-text  text-5xl lg:text-6xl text-white self-end"></i>

                    </div>
                    <div class="flex p-3 text-[#33272a] bg-[#fffcfa]">
                        <p>
                            My events feedbacks
                        </p>
                    </div>
                </a>

            </div>

        <?php endif; ?>



        <div class="flex flex-col md:flex-row w-full gap-[3%] mb-10">
            <div class="incoming-container w-full md:w-[49%] p-3 border overflow-auto">
                <p class="text-2xl  my-3 font-semibold">Past events</p>
                <div class="w-full overflow-auto  p-2 border ">
                    <?php if (count($past_events) > 0) : ?>
                        <table class="w-full border border-gray-400" id="past">
                            <thead>
                                <tr>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Image</th>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Event</th>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Venue</th>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>No incoming events</p>
                    <?php endif ?>

                </div>

                <script>
                    var past_events = <?= json_encode($past_events) ?>;
                    console.log(past_events);

                    $('#past').DataTable({
                        data: past_events,
                        ordering: false,
                        paging: true,
                        lengthMenu: [5, 10, 15, 20],
                        pageLength: 5,

                        info: true,
                        columns: [{
                                data: 'event_img',
                                className: 'border  !py-2 !px-2 !text-start',
                                render: function(data, type, row) {
                                    return `
                                    <img src="../uploads/event_img/${data}" alt="event-img" width="100" height="100" class="max-h-16 max-w-16">
                                    `;
                                }
                            },
                            {
                                data: 'title',
                                className: 'border  !py-2 !px-2 !text-start'
                            },
                            {
                                data: 'venue',
                                className: 'border  !py-2 !px-2 !text-start',
                                searchable: false
                            },
                            {
                                data: 'event_id',
                                className: 'border  !py-2 !px-2 !text-center',
                                render: function(data, type, row) {
                                    return `
                                    <button onclick="fetch_view(${data})" class="px-3 py-1  self-end md:text-sm text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-sm" id="upt-btn">
                                        View
                                    </button>
                                    `;
                                }
                            }
                        ],

                        rowCallback: function(row, data, index) {
                            if (index % 2 === 0) {
                                $(row).addClass('bg-gray-50');
                            }
                            $(row).addClass('hover:bg-gray-100');

                        }
                    });
                </script>
            </div>
            <div class="incoming-container w-full md:w-[49%]  p-3 border overflow-auto">
                <p class="text-2xl  my-3 font-semibold">Incoming events</p>
                <div class="w-full overflow-auto  p-2 border ">
                    <?php if (count($incoming_events) > 0) : ?>
                        <table class="w-full border border-gray-400" id="incoming_events_tbl">
                            <thead>
                                <tr>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Image</th>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Event</th>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Venue</th>
                                    <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>No incoming events</p>
                    <?php endif ?>

                </div>

                <script>
                    var incoming_events = <?= json_encode(array_reverse($incoming_events)) ?>;
                    console.log(incoming_events);

                    $('#incoming_events_tbl').DataTable({
                        data: incoming_events,
                        ordering: false,
                        paging: true,
                        lengthMenu: [5, 10, 15, 20],
                        pageLength: 5,

                        info: true,
                        columns: [{
                                data: 'event_img',
                                className: 'border  !py-2 !px-2 !text-start',
                                render: function(data, type, row) {
                                    return `
                                    <img src="../uploads/event_img/${data}" alt="event-img" width="100" height="100" class="max-h-16 max-w-16">
                                    `;
                                }
                            },
                            {
                                data: 'title',
                                className: 'border  !py-2 !px-2 !text-start'
                            },
                            {
                                data: 'venue',
                                className: 'border  !py-2 !px-2 !text-start',
                                searchable: false
                            },
                            {
                                data: 'event_id',
                                className: 'border  !py-2 !px-2 !text-center',
                                render: function(data, type, row) {
                                    return `
                                    <button onclick="fetch_view(${data})" class="px-3 py-1  self-end md:text-sm text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-sm" id="upt-btn">
                                        View
                                    </button>
                                    `;
                                }
                            }
                        ],

                        rowCallback: function(row, data, index) {
                            if (index % 2 === 0) {
                                $(row).addClass('bg-gray-50');
                            }
                            $(row).addClass('hover:bg-gray-100');

                        }
                    });
                </script>
            </div>
        </div>
        <!-- Line graps -->
        <div class="w-full flex flex-col">
            <div class="w-full flex flex-col gap-y-10">
                <div class="w-full  border p-4">
                    <p class="text-xl md:text-2xl mb-3">Monthly created events </p>
                    <div class="w-full overflow-auto">

                        <div class="min-w-[50rem]">
                            <canvas id="myChart" height="300"></canvas>

                        </div>

                    </div>
                </div>
                <div class="w-full  border p-4">
                    <p class="text-xl md:text-2xl mb-3">Monthly feedbacks response </p>
                    <div class="w-full overflow-auto">
                        <div class="min-w-[50rem]">
                            <canvas id="myChart2" height="300"></canvas>

                        </div>

                    </div>
                </div>
                <script>
                    const ctx = document.getElementById('myChart');

                    const monthlyData = <?= json_encode($monthly_event_data) ?>;




                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(monthlyData),
                            datasets: [{
                                label: '# of events',
                                data: Object.values(monthlyData),
                                borderWidth: 3
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: false
                                },
                                datalabels: {
                                    color: '#000',
                                }
                            },
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grace: 3,
                                },
                            }
                        }
                    });

                    const monthlyResponseSent = <?= json_encode($monthly_response_sent) ?>;

                    const ctx2 = document.getElementById('myChart2');

                    new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: Object.keys(monthlyResponseSent),
                            datasets: [{
                                label: '# of reponse',
                                data: Object.values(monthlyResponseSent),
                                borderWidth: 3
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: false
                                },
                                datalabels: {
                                    color: '#000',
                                }
                            },
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grace: 3,
                                },
                            }
                        }
                    });
                </script>
            </div>
        </div>


    </div>


    <?php
    require "./components/modals/view-event-modal.php";
    ?>


    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>

</main>

<?php
require "./partials/footer.php";
?>