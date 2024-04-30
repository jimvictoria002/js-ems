<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$user_id = $_SESSION['user_id'];

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student')) {
    header('Location: dashboard.php');
    exit;
}

require "../connection.php";

if($access == 'admin' || $access == 'staff'){
    $query = "SELECT * FROM feedbacks f ORDER BY f.end_datetime DESC";

}else{
    $query = "SELECT * FROM feedbacks f WHERE created_by = '$user_id' AND creator_access = '$access' ORDER BY f.end_datetime DESC";

}

$result = $conn->query($query);

$total_data = $result->num_rows;

$data = [];

while ($feedback = $result->fetch_assoc()) {
    $data[] = $feedback;
}



$title = 'Feedbacks';
require "../connection.php";
require "./partials/header.php";
$active = 'feedback';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Feedbacks</p>
            <div class="w-full overflow-auto  p-6 border ">
                <?php if($total_data): ?>
                <table class="w-full border border-gray-400" id="example">
                    <thead>
                        <tr>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" >Event</th>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" >Decription</th>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" >Form title</th>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" >Total feedbacks</th>
                            <th class="text-center border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>You don't have any feedbacks</p>
                <?php endif ?>

            </div>

            <script>
                var feedbacks = <?= json_encode($data) ?>

                $('#example').DataTable({
                    data: feedbacks,
                    ordering: false,
                    paging: true,
                    pageLength: 10,
                    info: true,
                    columns: [{
                            data: 'title',
                            className: 'border whitespace-nowrap !py-5 !px-2 !text-start'
                        },
                        {
                            data: 'description',
                            className: 'border  !py-5 !px-2 !text-start'
                        },
                        {
                            data: 'form_title',
                            className: 'border whitespace-nowrap !py-5 !px-2 !text-start',
                            searchable: false
                        },
                        {
                            data: 'feedback_count',
                            className: 'border whitespace-nowrap !py-5 !px-2 !text-start',
                            searchable: false
                        },
                        {
                            data: 'event_id',
                            className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                            render: function(data, type, row) {
                                return `
                                    <a href="view-feedback.php?event_id=${data}" class="px-4 py-2  self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
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

                function viewEvent(id) {
                    console.log(id)
                }
            </script>

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


</main>

<?php
require "./partials/footer.php";
?>