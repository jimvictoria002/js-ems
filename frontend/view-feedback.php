<?php
require "../connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

// if ($_SESSION['access'] != 'admin') {
//     header('Location: dashboard.php');
//     exit;
// }

if (!isset($_GET['event_id'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

if (!is_numeric($_GET['event_id'])) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$event_id = $_GET['event_id'];

$query = "SELECT 
           * 
        FROM 
            feedbacks
        WHERE event_id = $event_id";
$result = $conn->query($query);

if ($result->num_rows < 1) {
    header('HTTP/1.0 404 NOT FOUND');
    exit;
}

$event = $result->fetch_assoc();
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

$title = 'Feedbacks';
require "../connection.php";
require "./partials/header.php";
$active = 'feedback';
require "./components/side-nav.php";

$query = "SELECT rf.respondent, COUNT(rf.r_f_id) as total FROM `response_form` rf WHERE rf.event_id = $event_id GROUP BY rf.respondent";




?>
<main class="w-full overflow-auto  h-screen z-50 ">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="w-full bg-white flex justify-center">

        <div class="p-5 mb-40 flex flex-col items-center w-full bg-white md:w-[70%]">
            <div class="flex flex-col shadow-lg p-5 rounded-md border mt-5 w-full my-2 mb-10">
                <p class="text-2xl font-semibold md:text-3xl "><?= $event['form_title'] ?></p>
                <p class="text-lg mt-2">
                    <?= $event['form_description']  ?>
                </p>
                <div class="flex flex-col mt-10">
                    <table>
                        <tr>
                            <th class="text-center font-semibold">Total response</th>
                            <th class="text-center font-semibold">Export as excel</th>
                        </tr>
                        <tr>
                            <td class="text-center"><?= $event['feedback_count']    ?></td>
                            <td class="text-center p-3"><a href="../backend/export-excel/exporter.php?event_id=<?= $event_id ?>" class="bg-green-700 hover:bg-green-600 transition-default rounded-lg font-semibold text-white px-10 py-2 ">Export <i class="fa-solid fa-file-excel"></i></a></td>
                        </tr>
                        <tr>
                            <td class="text-center pt-10">
                                <input type="radio" name="view" class="view-radio hidden change-view" id="view" value="summarize" checked>
                                <label class="font-semibold relative view-label cursor-pointer" for="view">Summarize</label>
                            </td>
                            <td class="text-center pt-10">
                                <input type="radio" name="view" class="view-radio hidden change-view" id="individual" value="Individual">
                                <label class="font-semibold relative view-label cursor-pointer" for="individual">Individual</label>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <script>
                $('.change-view').on('change', function(){
                    $('#summarize-container').fadeToggle();
                    $('#individual-container').fadeToggle();
                    $('#individual-table').css('width', '100%');
                })
            </script>


            <div class="w-full flex flex-col "  id="summarize-container">
                <!-- Respondent div -->

                <div class="flex flex-col w-full bg-white my-12  shadow-lg border p-4 md:p-10">
                    <!-- <p class="text-2xl md:text-3xl m-4">Feedbacks</p> -->
                    <div class="flex justify-center items-center flex-col p-4  overflow-auto bg-white w-full self-center ">
                        <p class="text-lg md:text-xl self-start">Respondents</p>
                        <p class="text-sm md:text-base font-semibold self-start my-3"> <?= $event['feedback_count'] ?> Responses</p>


                        <div class="min-w-[200px] md:w-[400px] md:h-[400px] w-[300px] h-[300px]  flex justify-center fle-col items-center" style="overflow: auto;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
                <script>
                    <?php
                    $respondent_type_colors = [
                        'Teacher' => '#218838',
                        'Student' => '#dc3545',
                        'Parent' => '#17a2b8',
                        'Administrator' => 'gray',
                        'Staff' => '#229494',
                        'Guest' => 'royalblue'
                    ];
                    $r_respondent = $conn->query($query);
                    $data = [];

                    while ($row = $r_respondent->fetch_assoc()) {


                        $data[$row['respondent']] = $row['total'];
                    }
                    ?>
                    var data = <?= json_encode($data) ?>;


                    var labels = Object.keys(data);
                    var totals = Object.values(data);

                    var bgColor = [];

                    labels.forEach(element => {
                        switch (element) {
                            case 'student':
                                bgColor.push('rgba(33, 136, 56, 0.6)');
                                break;
                            case 'teacher':
                                bgColor.push('rgba(220, 53, 69, 0.2)');
                                break;
                            case 'staff':
                                bgColor.push('rgba(34, 148, 148, 0.2)');
                                break;
                            case 'admin':
                                bgColor.push('rgba(128, 128, 128, 0.2)');
                                break;
                            case 'guest':
                                bgColor.push('rgba(65, 105, 225, 0.2)');
                                break;
                            case 'parent':
                                bgColor.push('rgba(255, 255, 0, 0.2)');
                                break;
                            default:
                                break;
                        }
                    });

                    labels = Object.keys(data).map(function(label) {
                        return label.charAt(0).toUpperCase() + label.slice(1);
                    });


                    const ctx = document.getElementById('myChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Responses',
                                data: totals,
                                backgroundColor: bgColor,
                                borderColor: bgColor,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            layout: {
                                padding: 0
                            },
                            plugins: {
                                labels: {
                                    position: 'outside',
                                    textMargin: 6,
                                    render: (ctx) => {
                                        if (ctx.value < 10) {
                                            return ctx.value
                                        }

                                    }
                                },
                                legend: {
                                    position: 'right',
                                    margin: {
                                        top: 10,
                                        right: 20,
                                        bottom: 10,
                                        left: 100
                                    }
                                },
                                datalabels: {
                                    color: '#000',
                                    formatter: (value) => {
                                        if (value < 10) {
                                            return '';
                                        }
                                    }

                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                </script>

                <!-- Questionnaire divs -->
                <?php
                $f_id = $event['f_id'];
                $query = "SELECT * FROM questionnaire WHERE f_id = $f_id";
                $result = $conn->query($query);
                $count = 3;
                ?>

                <?php while ($question = $result->fetch_assoc()) :
                    $count++;
                    $data = [];
                    $q_id = $question['q_id'];
                    $total_response = 0;
                ?>
                    <?php if ($question['type'] == 'radio') : ?>
                        <?php
                        $q_choices = "SELECT * FROM choices c WHERE c.q_id = $q_id ";
                        $r_choices = $conn->query($q_choices);
                        ?>
                        <?php while ($choice = $r_choices->fetch_assoc()) :
                            $c_id = $choice['c_id'];
                            $q_response = "SELECT
                                            COUNT(*) AS total
                                        FROM
                                            response r
                                        INNER JOIN response_form rf ON
                                            r.r_f_id = rf.r_f_id
                                        WHERE
                                            r.q_id = $q_id  AND r.answer = $c_id AND rf.event_id = $event_id
                                        GROUP BY
                                            r.answer";
                            $r_response = $conn->query($q_response);
                            $response = $r_response->fetch_assoc();

                            $total = isset($response['total']) ? $response['total'] : 0;

                            $data[$choice['choice_name']] = $total;
                            $total_response += $total;

                        ?>




                        <?php endwhile; ?>
                        <!-- Radio -->

                        <div class="flex flex-col w-full bg-white my-12  shadow-lg border p-4 md:p-10">
                            <!-- <p class="text-2xl md:text-3xl m-4">Feedbacks</p> -->
                            <div class="flex justify-center items-center flex-col p-4  overflow-auto bg-white w-full self-center ">
                                <p class="text-lg md:text-xl self-start"><?= $question['question'] ?></p>
                                <p class="text-sm md:text-base font-semibold self-start my-3 "> <?= $total_response ?> Responses</p>

                                <div class="w-full overflow-auto">
                                    <div class="min-w-[500px]  w-full flex justify-start md:justify-center items-center  ">
                                        <canvas id="question-<?= $count ?>" height="200"></canvas>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <script>
                            var data = <?= json_encode($data) ?>;
                            const ctx<?= $count ?> = document.getElementById('question-<?= $count ?>');
                            ctx<?= $count ?>.height = 300;

                            var label3s<?= $count ?> = Object.keys(data);
                            label3s<?= $count ?>.forEach((e, index, array) => {
                                if (e.length > 20) {
                                    array[index] = e.split(' ');
                                }
                            });



                            new Chart(ctx<?= $count ?>, {
                                type: 'bar',
                                data: {
                                    labels: label3s<?= $count ?>,
                                    datasets: [{
                                        label: 'Number of response',
                                        data: Object.values(data),
                                        borderWidth: 1,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.6)', // Red
                                            'rgba(54, 162, 235, 0.6)', // Blue
                                            'rgba(255, 206, 86, 0.6)', // Yellow
                                            'rgba(75, 192, 192, 0.6)', // Green
                                            'rgba(153, 102, 255, 0.6)', // Purple
                                            'rgba(255, 159, 64, 0.6)', // Orange
                                            'rgba(255, 0, 0, 0.6)', // Bright Red
                                            'rgba(0, 255, 0, 0.6)', // Bright Green
                                            'rgba(0, 0, 255, 0.6)', // Bright Blue
                                            'rgba(255, 255, 0, 0.6)', // Bright Yellow
                                            'rgba(255, 0, 255, 0.6)', // Magenta
                                            'rgba(0, 255, 255, 0.6)', // Cyan
                                            'rgba(128, 0, 0, 0.6)', // Maroon
                                            'rgba(0, 128, 0, 0.6)', // Green (Dark)
                                            'rgba(0, 0, 128, 0.6)', // Navy
                                            'rgba(128, 128, 0, 0.6)', // Olive
                                            'rgba(128, 0, 128, 0.6)', // Purple (Dark)
                                            'rgba(0, 128, 128, 0.6)', // Teal
                                            'rgba(192, 192, 192, 0.6)', // Silver
                                            'rgba(128, 128, 128, 0.6)', // Gray
                                            'rgba(255, 165, 0, 0.6)', // Orange (Dark)
                                            'rgba(0, 255, 127, 0.6)', // Spring Green
                                            'rgba(0, 139, 139, 0.6)', // Dark Cyan
                                            'rgba(139, 0, 0, 0.6)', // Dark Red
                                            'rgba(139, 0, 139, 0.6)', // Dark Magenta
                                            'rgba(255, 20, 147, 0.6)', // Deep Pink
                                            'rgba(0, 250, 154, 0.6)', // Medium Spring Green
                                            'rgba(50, 205, 50, 0.6)', // Lime Green
                                            'rgba(255, 140, 0, 0.6)', // Dark Orange
                                            'rgba(0, 128, 0, 0.6)', // Green (Dark)
                                            'rgba(0, 0, 128, 0.6)' // Navy
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)', // Red
                                            'rgba(54, 162, 235, 1)', // Blue
                                            'rgba(255, 206, 86, 1)', // Yellow
                                            'rgba(75, 192, 192, 1)', // Green
                                            'rgba(153, 102, 255, 1)', // Purple
                                            'rgba(255, 159, 64, 1)', // Orange
                                            'rgba(255, 0, 0, 1)', // Bright Red
                                            'rgba(0, 255, 0, 1)', // Bright Green
                                            'rgba(0, 0, 255, 1)', // Bright Blue
                                            'rgba(255, 255, 0, 1)', // Bright Yellow
                                            'rgba(255, 0, 255, 1)', // Magenta
                                            'rgba(0, 255, 255, 1)', // Cyan
                                            'rgba(128, 0, 0, 1)', // Maroon
                                            'rgba(0, 128, 0, 1)', // Green (Dark)
                                            'rgba(0, 0, 128, 1)', // Navy
                                            'rgba(128, 128, 0, 1)', // Olive
                                            'rgba(128, 0, 128, 1)', // Purple (Dark)
                                            'rgba(0, 128, 128, 1)', // Teal
                                            'rgba(192, 192, 192, 1)', // Silver
                                            'rgba(128, 128, 128, 1)', // Gray
                                            'rgba(255, 165, 0, 1)', // Orange (Dark)
                                            'rgba(0, 255, 127, 1)', // Spring Green
                                            'rgba(0, 139, 139, 1)', // Dark Cyan
                                            'rgba(139, 0, 0, 1)', // Dark Red
                                            'rgba(139, 0, 139, 1)', // Dark Magenta
                                            'rgba(255, 20, 147, 1)', // Deep Pink
                                            'rgba(0, 250, 154, 1)', // Medium Spring Green
                                            'rgba(50, 205, 50, 1)', // Lime Green
                                            'rgba(255, 140, 0, 1)', // Dark Orange
                                            'rgba(0, 128, 0, 1)', // Green (Dark)
                                            'rgba(0, 0, 128, 1)' // Navy
                                        ]
                                    }]
                                },
                                options: {
                                    layout: {
                                        padding: 0
                                    },
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
                                            grace: 1,
                                        },
                                        x: {
                                            ticks: {
                                                font: {
                                                    size: 16, // Adjust font size here
                                                },
                                            }
                                        }
                                    },
                                },
                                plugins: [ChartDataLabels]
                            });
                        </script>
                    <?php else :
                        $messages = [];
                        $q_response = "SELECT
                                        r.answer
                                    FROM
                                        response r
                                    INNER JOIN response_form rf ON
                                        r.r_f_id = rf.r_f_id
                                    WHERE
                                        r.q_id = $q_id  AND rf.event_id = $event_id
                                    ";
                        $r_response = $conn->query($q_response);

                        while ($response = $r_response->fetch_assoc()) {
                            $total = isset($response['answer']) || !empty($response['answer']) ? $response['answer'] : '';

                            $messages[] = $total;
                            $total_response++;
                        }
                    ?>
                        <!-- Message div -->
                        <div class="flex flex-col w-full bg-white my-12  shadow-lg border p-4 md:p-10">
                            <!-- <p class="text-2xl md:text-3xl m-4">Feedbacks</p> -->
                            <div class="flex justify-center items-center flex-col p-4  overflow-auto bg-white w-full self-center ">
                                <p class="text-lg md:text-xl self-start"><?= $question['question'] ?></p>
                                <p class="text-sm md:text-base font-semibold self-start my-3 "> <?= $total_response ?> Responses</p>

                                <div class="w-full overflow-auto max-h-[18rem] my-7">
                                    <?php
                                    foreach ($messages as $message) {
                                    ?>
                                        <p class="self-start py-4 opacity-80 border-b"><?= $message ?></p>
                                    <?php
                                    }
                                    ?>
                                </div>


                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>

            </div>

            <div class="w-full md:w-[120%] overflow-auto" style="display: none;" id="individual-container">
                <div class=" w-full bg-white my-12  shadow-lg border p-4 overflow-auto">

                    <table class="!w-full border border-gray-400 self-start" id="individual-table">
                        <thead>
                            <tr>
                                <th class="text-start border border-green-800 imp-font-sans font-semibold py-1 px-3  text-base md:text-lg text-white bg-main">Respondent</th>
                                <th class="text-start border border-green-800 imp-font-sans font-semibold py-1 px-3  text-base md:text-lg text-white bg-main">Name</th>
                                <th class="text-start border border-green-800 imp-font-sans font-semibold py-1 px-3  text-base md:text-lg text-white bg-main">Email</th>
                                <th class="text-start border border-green-800 imp-font-sans font-semibold py-1 px-3  text-base md:text-lg text-white bg-main">Response at</th>
                                <th class="text-center border border-green-800 imp-font-sans font-semibold py-1 px-3  text-base md:text-lg text-white bg-main">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- <p>You don't have any feedbacks</p> -->

                    <?php
                    $query = "SELECT * FROM response_form WHERE event_id = $event_id AND is_done = 'yes'";

                    $result = $conn->query($query);

                    $data = [];

                    while ($response = $result->fetch_assoc()) {
                        $respondent = $response['respondent'];
                        $r_f_id = $response['r_f_id'];
                        $response_id = $response['response_id'];
                        $created_at = $response['created_at'];
                        $my_responses = [];

                        $timestamp = strtotime($created_at);

                        $formatted_date = date('M d, Y', $timestamp);

                        $formatted_time = date('g:iA', $timestamp);

                        $response_at = $formatted_date . ' ' . $formatted_time;

                        switch ($respondent) {
                            case 'teacher':
                                $query = "SELECT * FROM scheduling_system.teacher WHERE id = $response_id";
                                $r_user = $conn->query($query);
                                $user = $r_user->fetch_assoc();
                                $firstname = $user['first_name'];
                                $middlename = $user['middle_name'];
                                $lastname = $user['last_name'];
                                $email = $user['email'];
                                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                                break;
                            case 'student':
                                $query = "SELECT * FROM sis.students WHERE std_id = $response_id";
                                $r_user = $conn->query($query);
                                $user = $r_user->fetch_assoc();
                                $firstname = $user['firstname'];
                                $middlename = $user['middlename'];
                                $lastname = $user['lastname'];
                                $email = $user['email'];
                                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                                break;
                            case 'parent':
                                $query = "SELECT * FROM sis.parent WHERE id = $response_id";
                                $r_user = $conn->query($query);
                                $user = $r_user->fetch_assoc();
                                $email = $user['email'];
                                $fullname = $user['fullname'];

                                break;
                            case 'staff':
                                $query = "SELECT * FROM users WHERE user_id = $response_id";
                                $r_user = $conn->query($query);
                                $user = $r_user->fetch_assoc();
                                $firstname = $user['firstname'];
                                $middlename = $user['middlename'];
                                $lastname = $user['lastname'];
                                $email = $user['email'];
                                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                                break;
                            case 'admin':
                                $query = "SELECT * FROM users WHERE user_id = $response_id";
                                $r_user = $conn->query($query);
                                $user = $r_user->fetch_assoc();
                                $firstname = $user['firstname'];
                                $middlename = $user['middlename'];
                                $lastname = $user['lastname'];
                                $email = $user['email'];
                                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                                break;
                            case 'guest':
                                $query = "SELECT * FROM guest WHERE guest_id = $response_id";
                                $r_user = $conn->query($query);
                                $user = $r_user->fetch_assoc();
                                $firstname = $user['firstname'];
                                $middlename = $user['middlename'];
                                $lastname = $user['lastname'];
                                $email = $user['email'];
                                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                                break;

                            default:
                                return "Invalid";
                                break;
                        }


                        $data[] = [
                            "type" => ucfirst($respondent),
                            "fullname" => $fullname,
                            "email" => $email,
                            "response_at" => $response_at,
                            "r_f_id" => $r_f_id
                        ];
                    }

                    // print_r($data);

                    ?>
                    <script>
                        var feedbacks = <?= json_encode($data) ?>;

                        $('#individual-table').DataTable({
                            data: feedbacks,
                            ordering: false,
                            paging: true,
                            pageLength: 10,
                            info: true,
                            columns: [{
                                    data: 'type',
                                    className: 'border whitespace-nowrap !py-5 !px-4 !text-start'
                                },
                                {
                                    data: 'fullname',
                                    className: 'border  !py-5 !px-4 !text-start whitespace-nowrap'
                                },
                                {
                                    data: 'email',
                                    className: 'border whitespace-nowrap !py-5 !px-4 !text-start',
                                    searchable: true
                                },
                                {
                                    data: 'response_at',
                                    className: 'border whitespace-nowrap !py-5 !px-4 !text-start',
                                    searchable: false
                                },
                                {
                                    data: 'r_f_id',
                                    className: 'border whitespace-nowrap !py-5 !px-6 !text-center',
                                    render: function(data, type, row) {
                                        return `
                    <a href="form.php?r_f_id=${data}" class="px-4 py-2  self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                        View
                    </a>
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


        </div>

    </div>


    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;

    if (isset($_SESSION['failed'])) :
        require "./components/failed-message.php";
        unset($_SESSION['failed']);
    endif;
    ?>


</main>

<?php
require "./partials/footer.php";
?>