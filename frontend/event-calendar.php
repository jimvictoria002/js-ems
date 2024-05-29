<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$title = 'Calendar';
require "../connection.php";
require "./partials/header.php";
$active = 'calendar';
require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50 ">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Event calendar</p>
            <div class="flex flex-col w-full">
                <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
                    <button onclick="" class="toggle-create  px-6 py-2 self-center mr-5 md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl">Create
                        new event <i class="fa-solid fa-plus ml-1"></i></button>
                <?php endif; ?>
                <!-- Search -->
                <div class="self-end flex flex-col my-3 relative md:mt-3 mt-7" id="seach-container">
                    <?php

                    $query = "SELECT event_id, title FROM events WHERE status = 'approved' ORDER BY end_datetime DESC";
                    $result = $conn->query($query);
                    $data_to_search = [];

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $data_to_search[] = [
                                'id' => $row['event_id'],
                                'title' => $row['title'],
                                'label' => $row['title'],
                            ];
                        }
                    }


                    ?>
                    <script src="../node_modules/jquery-ui/jquery-ui.min.js"></script>
                    <link rel="stylesheet" href="../node_modules/jquery-ui/jquery-ui.css">
                    <div class="flex items-center gap-3">
                        <label for="">Search:</label>
                        <input type="text" id="autocomplete-input" placeholder="Search event.." autocomplete="off" class="p-1 border md:text-lg">

                    </div>
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

                                    $.ajax({
                                        type: "GET",
                                        url: "../backend/fetcher/fetch_selected.php",
                                        data: {
                                            event_id: selectedId
                                        },
                                        success: function(response) {
                                            // console.log(JSON.parse(response))
                                            viewEvent(JSON.parse(response))
                                        }
                                    });

                                },
                                appendTo: "#seach-container"
                            });

                            $("#autocomplete-input").click(function() {
                                $(this).autocomplete("search", "");
                            });
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
            </div>

            <?php
            require "./components/render-calendar.php";
            ?>

        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>

    <?php
    require "./components/modals/create-event-modal.php";
    ?>

    <?php
    require "./components/modals/view-event-modal.php";
    ?>


</main>

<?php
require "./partials/footer.php";
?>