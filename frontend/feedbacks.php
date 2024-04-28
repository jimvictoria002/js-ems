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

                <table class="w-full border border-gray-400" id="example">
                    <thead>
                        <tr>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" style="font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;">Title</th>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" style="font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;">Decription</th>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" style="font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;">Venue</th>
                            <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" style="font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;">Total feedbacks</th>
                            <th class="text-center border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main" style="font-family: ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>

            <script>
                var feedbacks; // Declare the variable outside the scope

                $.get("../backend/fetcher/fetch_feedbacks.php", function(data) {
                    feedbacks = JSON.parse(data);

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
                                data: 'venue',
                                className: 'border whitespace-nowrap !py-5 !px-2 !text-start',
                                searchable: false
                            },
                            {
                                data: 'feedback_count',
                                className: 'border whitespace-nowrap !py-5 !px-2 !text-start',
                                searchable: false
                            },
                            { // Action button column
                                data: 'event_id',
                                className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                                render: function(data, type, row) {
                                    return `
                                    <button onclick="viewEvent(${data})" class="px-4 py-2  self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                                        <div class="fa-solid fa-eye"></div>
                                    </button>
                                    <button onclick="editEvent(${data})" class="px-4 py-2 ml-6 self-end md:text-base text-sm bg-red-700 hover:bg-red-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="edit-btn">
                        <div class="fa-solid fa-trash"></div>
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