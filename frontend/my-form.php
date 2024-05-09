<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$access = $_SESSION['access'];
$user_id = $_SESSION['user_id'];

if (!($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student')) {
    header('Location: dashboard.php');
    exit;
}

$additional_contion = '';
$event_add_cond = '';

if (!($access == 'admin' || $access == 'staff')) {
    $additional_contion = "f.creator_access = '$access' AND f.creator_id = $user_id AND";
    $event_add_cond = "e.created_by = $user_id AND 
    e.creator_access = '$access' AND";
}


$title = 'My forms';
require "../connection.php";
require "./partials/header.php";
$active = 'form';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <?php if ($access == 'admin' || $access == 'staff') : ?>
                <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Forms</p>

            <?php else : ?>
                <p class="text-xl font-semibold md:text-3xl my-8 mt-3">My forms</p>

            <?php endif; ?>

            <?php

            $user_id = $_SESSION['user_id'];


            $query = "SELECT
                        f.*,
                        COUNT(e.event_id) as total_in_use
                    FROM
                        `forms` f
                    LEFT JOIN events e ON 
                        f.f_id = e.f_id
                    WHERE
                     $additional_contion (f.status = 'active' || f.status = 'not_done')
                    GROUP BY 
                        f.f_id
                    ORDER BY 
                        f.created_at DESC";




            $result = $conn->query($query);

            $total_data = $result->num_rows;




            ?>
            <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
                <button type="button" class="  px-6 py-2 self-center mr-5 md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl mb-6" id="create-form-btn" onclick="createEvaluationForm()">Create form <i class="fa-solid fa-plus text-yellow-300"></i></button>
            <?php endif; ?>
            <div class="table-container w-full overflow-auto p-7 border">


                <?php if ($total_data  > 0) : ?>
                    <table class="w-full min-w-[34rem] " id="my-events-tbl">
                        <thead>

                            <tr>
                                <th class="text-start border border-green-800 font-semibold !py-1 px-3  text-base md:text-lg text-white bg-main ">Title
                                </th>
                                <th class="text-start border border-green-800 font-semibold !py-1 px-3  text-base md:text-lg text-white bg-main ">Total in use
                                </th>
                                <th class="text-center border border-green-800 font-semibold py-1 px-3  text-base md:text-lg text-white bg-main ">Action
                                </th>
                            </tr>
                        </thead>

                        <?php
                        $my_forms = [];
                        while ($p_event = $result->fetch_assoc()) :

                            $my_forms[] = $p_event;
                        ?>

                        <?php endwhile; ?>


                        <script>
                            let my_forms = <?= json_encode($my_forms) ?>;
                            console.log(my_forms)
                            $('#my-events-tbl').DataTable({
                                data: my_forms,
                                ordering: false,
                                paging: true,
                                pageLength: 10,
                                info: true,
                                columns: [{
                                        data: 'title',
                                        className: 'py-2 px-3 border text-start text-sm md:text-base'
                                    },
                                    {
                                        data: null,
                                        className: 'py-2 px-3 border !text-start text-sm md:text-base',
                                        render: function(data, type, row) {
                                            return `

                                                <p>
                                                ${data.total_in_use} events <span class="text-blue-700 font-semibold cursor-pointer" onclick="viewEvent(${data.f_id})"> view</span>
                                                </p>
                                            
                                            `;
                                        }
                                    },
                                    {
                                        data: null,
                                        className: 'border whitespace-nowrap !py-3 !px-2 !text-center',
                                        render: function(data, type, row) {

                                            let buttons = '';

                                            let f_id = data.f_id;

                                            console.log()

                                            // console.log(v_id)
                                            // console.log(created_by)
                                            // console.log(creator_access)

                                            buttons += `
                                            <button class="w-28 toggle-attach-event-set py-1.5 mx-1 self-end md:text-base text-sm bg-green-500 hover:bg-green-400 cursor-pointer transition-default my-2 text-white font-semibold inline-block rounded-xl" onclick="attachSet(${f_id})" id="upt-btn"> Attach</button>
                                   
                                                <a href="create-form.php?f_id=${f_id}" class="w-28 py-1.5 mx-1 self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400 cursor-pointer inline-block transition-default text-white font-semibold rounded-xl" id="upt-btn">
                                                    Edit
                                                </a>
                                            `;


                                            buttons += ` <button type="button" onclick="deleteForm(${f_id})" class=" w-28 py-1.5  mx-1 self-end md:text-base text-sm inline-block  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete</button>`;



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
                        </script>

                    </table>
                <?php else : ?>
                    <p>You don't have any evaluation form</p>
                <?php endif; ?>
            </div>


        </div>

    </div>

    <div class="w-full px-3 fixed inset-0 z-40  hidden" id="attach-event-modal">
        <div class=" bg-white rounded-lg z-50 px-5 w-[20rem] md:w-[25rem] max-h-[90vh] overflow-auto py-5 pb-0 fixed top-[50%] left-[50%] flex flex-col gap-2" style="transform: translate(-50%, -50%);" id="invitation-form">

            <div class="flex justify-between items-start">
                <p class="text-xl md:text-2xl font-semibold mb-2 md:mb-3 text-green-950">Attach to event</p>

                <i class="fa-solid fa-x toggle-attach-event text-xl hover:text-red-700 cursor-pointer"></i>
            </div>
            <!-- Invitation container -->
            <form action="../backend/update/link_form.php" method="POST" class="flex flex-col gap-y-2  mb-9" id="attach-form">

                <label for="" class="font-semibold">Select your event<span class="text-red-700">*</span></label>
                <input type="hidden" name="f_id" id="f_id">
                <select name="event_id" id="event_id" class="p-1 border active:border-green-950 rounded-sm w-full">

                    <?php
                    $query = "SELECT 
                                event_id,
                                title
                            FROM 
                                events e 
                            WHERE  $event_add_cond
                                  e.f_id IS NULL AND (e.status = 'pending' OR e.status = 'approved')
                            ORDER BY 
                                e.created_at DESC";
                    $result = $conn->query($query);
                    if ($result->num_rows > 0) {

                        while ($event = $result->fetch_assoc()) :
                    ?>

                            <option value="<?= $event['event_id'] ?>"><?= $event['title'] ?></option>

                    <?php endwhile;
                    } else {
                        echo "<option value=''>No available event</option>";
                    } ?>
                </select>

                <button class=" px-6 py-1.5 md:py-1 self-end md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-md mt-2 mr-2" id="invite-btn">Attach <i class="fa-solid fa-plus "></i></button>
            </form>

        </div>
        <div class="toggle-attach-event bg-gray-700 opacity-30 fixed inset-0 -z-50"></div>
    </div>

    <div class="w-full px-3 fixed inset-0 z-40 hidden" id="attached-event-modal">
        <div class=" bg-white rounded-lg z-50 px-5 w-[20rem] md:w-[25rem] max-h-[90vh] overflow-auto py-5 fixed top-[50%] left-[50%] flex flex-col gap-2 pb-2" style="transform: translate(-50%, -50%);" id="invitation-form">

            <div class="flex justify-between items-start">
                <p class="text-xl md:text-2xl font-semibold mb-2 md:mb-3 text-green-950">Attached events</p>
                <i class="fa-solid fa-x toggle-attached-event text-xl hover:text-red-700 cursor-pointer"></i>
            </div>
            <table class="w-full mb-4" id="event-attached-tbl">
                <tr>
                    <td class=" my-3 border-b border-b-gray-400 text-lg">CCS Olympics</td>
                    <td class=" text-center border-b border-b-gray-400">
                        <button type="button" class=" w-28 py-1.5 my-3 border-b  mx-1 self-end md:text-base text-sm inline-block  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Detach</button>
                    </td>
                </tr>

            </table>
        </div>
        <div class="toggle-attached-event bg-gray-700 opacity-30 fixed inset-0 -z-50"></div>
    </div>

    <script>
        $('.toggle-attach-event').on('click', function() {
            $('#attach-event-modal').fadeToggle();
        });
        $('.toggle-attached-event').on('click', function() {
            $('#attached-event-modal').fadeToggle();
        });

        function viewEvent(f_id) {
            $.ajax({
                type: "POST",
                url: "../backend/fetcher/fetch_event_from.php",
                data: {
                    f_id: f_id
                },
                success: function(response) {
                    $('#event-attached-tbl').html(response);
                    $('#attached-event-modal').fadeToggle();

                }
            });
        }

        function detachEvent(e, event_id) {
            if (confirm(`Your about to detached the event. \n\nTo retrieve the event responses just attach the event again`)) {

                $.ajax({
                    type: "POST",
                    url: "../backend/update/unlink_form.php",
                    data: {
                        event_id: event_id
                    },
                    success: function(response) {
                        if (response == 'unlinked') {
                            // $(e).parent().parent().remove();
                            window.location = "";
                        } else {
                            console.log(response)
                        }
                        // $('#attached-event-modal').fadeToggle();
                    }
                });
            }

        }

        function attachSet(f_id) {
            $('#attach-event-modal').fadeToggle();
            $('#f_id').val(f_id);
        }

        $('#attach-form').validate({
            rules: {
                event_id: 'required'
            },
            errorPlacement: function(error, element) {
                // Add your custom class to the error label
                error.addClass('text-red-700 text-sm font-semibold');
                // Place the error label wherever you want, for example, after the element
                error.insertAfter(element);
            }
        })

        async function requestFormCreation() {
            $('#create-form-btn').css('opacity', '.3');
            try {
                const response = await $.ajax({
                    url: "../backend/create/create_evaluation_form.php",
                    method: 'POST',
                });
                let res = JSON.parse(response);
                return res.f_id;
            } catch (error) {
                // Handle error
                console.error('Error:', error);
                throw error;
            }
        }


        async function createEvaluationForm() {
            window.location = "create-form.php?f_id=" + await requestFormCreation();
        }


        async function attachedEvent(f_id, event_id) {

        }

        function deleteForm(f_id) {

            if (confirm("Please confirm your intention to delete the form. \n\nDeleting the form will result in the removal of all associated events. \n\nTo retrieve the event responses, you will need to restore the form and re-attach the event again. \n\nAre you sure you want to proceed with the deletion?")) {
                $.ajax({
                    type: "POST",
                    url: "../backend/delete/delete_form.php",
                    data: {
                        f_id: f_id
                    },
                    success: function(response) {


                        // console.log(response);
                        window.location = "my-form.php";

                    }
                });
            }
        }
    </script>

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



</main>

<?php
require "./partials/footer.php";
?>