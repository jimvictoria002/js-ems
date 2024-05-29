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

$additional_cond = "";
$form_cond = "";
if (($access == 'admin' || $access == 'staff')) {
    $form_cond = "|| status = 'permanent_delete'";
    $additional_cond = "|| e.status = 'permanent_delete'";
}







$query = "SELECT v.venue, e.* FROM events e LEFT JOIN venue v ON e.v_id = v.v_id WHERE (e.status = 'deleted' $additional_cond )";


$result = $conn->query($query);

$total_data = $result->num_rows;

$data = [];

while ($feedback = $result->fetch_assoc()) {
    $data[] = $feedback;
}



$title = 'Feedbacks';
require "../connection.php";
require "./partials/header.php";
$active = 'restore';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <?php if ($access == 'admin' || $access == 'staff') : ?>
                <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Data restoration</p>

            <?php else : ?>
                <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Data restoration</p>

            <?php endif; ?>
            <div class="w-full overflow-auto  p-6 border ">
                <select id="restore-type" class="self-end p-1 px-2 rounded-md border active:border-green-950  text-lg">
                    <option value="event">Event</option>

                    <option value="form">Form</option>
                </select>
                <script>
                    $(document).ready(function () {
                        $('#restore-type').on('change', function(){
                            let value = $(this).val();
                            if(value == 'event'){
                                $('#event-restore-cont').fadeIn('fast');
                                $('#form-restore').css('width', '100%');
                                $('#form-restore-cont').fadeOut('fast');
                            }else{
                                $('#event-restore-cont').fadeOut('fast');
                                $('#form-restore-cont').fadeIn('fast');
                                $('#form-restore').css('width', '100%');
                            }
                        })
                    });
                </script>
                <div class="flex flex-col w-full">

                    <!-- Event -->
                    <div class="w-full" id="event-restore-cont">
                        <?php if ($total_data) : ?>
                            <table class="w-full border border-gray-400 restore-tbl" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Title</th>
                                        <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Decription</th>
                                        <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Venue</th>
                                        <th class="text-center border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p class="mt-7">You don't have any deleted event</p>
                        <?php endif ?>
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
                                        className: 'border  !py-5 !px-2 !text-start opac '
                                    },
                                    {
                                        data: 'description',
                                        className: 'border  !py-5 !px-2 !text-start opac '
                                    },
                                    {
                                        data: 'venue',
                                        className: 'border  !py-5 !px-2 !text-start opac ',
                                    },
                                    {
                                        data: null,
                                        className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                                        render: function(data, type, row) {
                                            return `
                                    <button onclick="restoreEvent(${data.event_id})" class="w-28 py-1.5 opacity-90 self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                                        Restore
                                        
                                    </button>
                                    <button type="button" onclick="permanentDelete('${data.event_id}')" class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer <?= ($access == 'admin' || $access == 'staff') ? "!hidden" : '' ?>  transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>
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

                            function restoreEvent(event_id) {

                                if (confirm('Do you really want to restore the event?')) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../backend/update/restore_event.php",
                                        data: {
                                            event_id: event_id
                                        },
                                        success: function(response) {


                                            // console.log(event_id);
                                            if ("<?= $access ?>" == 'admin' || "<?= $access ?>" == 'staff') {
                                                window.location = "pending-events.php";

                                            } else {
                                                window.location = "my-events.php";

                                            }

                                        }
                                    });
                                }
                            }

                            function permanentDelete(event_id) {

                                if (confirm('Do you really want to permanently delete the event?')) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../backend/delete/delete_event.php",
                                        data: {
                                            event_id: event_id,
                                            hard: true,
                                            reqAjx: true,

                                        },
                                        success: function(response) {


                                            // console.log(response);
                                            window.location = "";



                                        }
                                    });
                                }
                            }
                        </script>
                    </div>


                    <!-- Forms -->
                    <div class="w-full" id="form-restore-cont" style="display: none;">
                        <?php
                        $query = "SELECT * FROM forms WHERE (status = 'deleted' $form_cond)";
                        $result = $conn->query($query);
                        $form_total_data = $result->num_rows;
                        $form_data = [];
                        while ($form = $result->fetch_assoc()) {
                            $form_data[] = $form;
                        }
                        ?>
                        <?php if ($form_total_data) : ?>
                            <table class="w-full border border-gray-400 restore-tbl" id="form-restore">
                                <thead>
                                    <tr>
                                        <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Title</th>
                                        <th class="text-start border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Decription</th>
                                        <th class="text-center border border-green-800 imp-font-sans font-semibold py-4 px-3  text-base md:text-lg text-white bg-main">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p class="mt-7">You don't have any deleted form</p>
                        <?php endif ?>
                        <script>
                            var formData = <?= json_encode($form_data) ?>

                            $('#form-restore').DataTable({
                                data: formData,
                                ordering: false,
                                paging: true,
                                pageLength: 10,
                                info: true,
                                columns: [{
                                        data: 'title',
                                        className: 'border  !py-5 !px-2 !text-start opac '
                                    },
                                    {
                                        data: 'description',
                                        className: 'border  !py-5 !px-2 !text-start opac '
                                    },
                                    {
                                        data: null,
                                        className: 'border whitespace-nowrap !py-5 !px-2 !text-center',
                                        render: function(data, type, row) {
                                            return `
                                            <button onclick="restoreForm(${data.f_id})" class="w-28 py-1.5 opacity-90 self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer transition-default text-white font-semibold rounded-xl" id="upt-btn">
                                            Restore
                                        
                                            </button>
                                            <button type="button" onclick="permanentDeleteForm('${data.f_id}')" class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer <?= ($access == 'admin' || $access == 'staff') ? "!hidden" : '' ?>  transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>
                                            
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

                            function restoreForm(f_id) {

                                if (confirm('Do you really want to restore the form?')) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../backend/update/restore_form.php",
                                        data: {
                                            f_id: f_id
                                        },
                                        success: function(response) {


                                            // console.log(event_id);
                                            if ("<?= $access ?>" == 'admin' || "<?= $access ?>" == 'staff') {
                                                window.location = "my-form.php";

                                            } else {
                                                window.location = "my-form.php";

                                            }

                                        }
                                    });
                                }
                            }

                            function permanentDeleteForm(f_id) {

                                if (confirm('Do you really want to permanently delete the event?')) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../backend/delete/delete_form.php",
                                        data: {
                                            f_id: f_id,
                                            hard: true,
                                            reqAjx: true,

                                        },
                                        success: function(response) {


                                            // console.log(response);
                                            window.location = "";



                                        }
                                    });
                                }
                            }
                        </script>
                    </div>


                </div>


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


</main>

<?php
require "./partials/footer.php";
?>