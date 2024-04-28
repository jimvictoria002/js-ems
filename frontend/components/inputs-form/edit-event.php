<div class="w-full px-3">
    <div class="w-full bg-white rounded-lg p-6 mt-2 mb-64">

        <h1 class="text-lg md:text-3xl font-semibold text-green-950">Edit event </h1>
        <?php if ($event['status'] != 'approved'): ?>
            <div class="flex justify-start my-7 md:my-10">
                <div class="flex flex-col">
                    <p class="text-lg text-orange-700  ml-1">This event is still on pending wait for admin approval</p>
                    <!-- <h1
                        class="p-1 tex-sm md:text-lg rounded-lg mt-1 font-semibold inline-block  <?= $event['status'] == 'approved' ? 'bg-green-700' : 'bg-orange-700' ?>  text-white capitalize px-7">
                        <?= $event['status'] ?>
                    </h1> -->
                </div>

            </div>
        <?php endif; ?>


        <form action="../backend/update/update_event.php" class="mt-4" method="POST" class="mt-2" id="create-event"
            enctype="multipart/form-data">

            <div class="event-attributes flex items-start gap-y-6 gap-[3%] flex-wrap">
                <!-- Title -->

                <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                    <label for="" class="font-semibold">Event title<span class="text-red-700">*</span></label>
                    <input type="text" placeholder="Enter title"
                        class="form-input p-1 border active:border-green-950 rounded-sm w-full"
                        value="<?= $event['title'] ?>" name="title">
                </div>

                <!-- Description -->
                <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                    <label for="" class="font-semibold">Description</label>
                    <input type="text" placeholder="Enter description"
                        class=" form-input p-1 border active:border-green-950 rounded-sm w-full"
                        value="<?= $event['description'] ?>" name="description">
                </div>

                <!-- Venue -->
                <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                    <div class="w-full flex justify-between">
                        <label for="" class="font-semibold">Venue<span class="text-red-700">*</span></label>
                        <div class="flex items-center ">
                            <input type="checkbox" name="venue-type" id="venue-option" class="cursor-pointer">
                            <label for="venue-option" class="form-input text-sm ml-1 cursor-pointer">Add venue</label>
                        </div>
                    </div>
                    <?php
                    $query = "SELECT * FROM venue ORDER BY venue ";
                    $result = $conn->query($query);
                    ?>
                    <select name="venue" id="select-venue"
                        class="form-input p-1 border active:border-green-950 rounded-sm w-full ">
                        <option value="">--</option>
                        <?php while ($venue = $result->fetch_assoc()): ?>
                            <option value="<?= $venue['v_id'] ?>" <?= ($venue['v_id'] == $event['v_id'] ? 'selected' : '') ?>>
                                <?= $venue['venue'] ?>
                            </option>
                        <?php endwhile; ?>

                    </select>


                    <div>
                        <input type="text" name="input-venue" id="input-venue"
                            class="form-input hidden p-1 border active:border-green-950 rounded-sm w-full"
                            placeholder="Enter venue">
                    </div>

                    <script>
                        $('#venue-option').on('change', function () {
                            let state = $(this).prop('checked');
                            if (state) {
                                $('#select-venue').hide();
                                $('#input-venue').show();
                                $('#input-venue').val('');
                            } else {
                                $('#input-venue').hide();
                                $('#select-venue').show();
                            }
                        })
                    </script>
                </div>

                <!-- Event image -->
                <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col p-3 border  rounded-md">
                    <div class="flex justify-between">
                        <label for="event_img" class="font-semibold">Uploaded image<span
                                class="text-red-700">*</span></label>
                        <p class="text-green-700 text-xs mt-1">Maximum of 10MB<span class="text-red-700">*</span></p>
                    </div>

                    <div id="image-preview" class="mt-2 ">
                        <img src="<?= '../uploads/event_img/' . $event['event_img'] ?>" alt="event_img"
                            class="max-w-full" id="event-img-preview">
                    </div>

                    <!-- <p id="view-note" class="text-sm text-green-700 font-bold mt-2  cursor-pointer">View image</p> -->

                    <input type="file" id="event_img" name="event_img" accept="image/*"
                        class="form-input p-1 border mt-3 active:border-green-950 rounded-sm w-full text-sm cursor-pointer"
                        onchange="previewImage(event)">


                    <!-- Image preview script -->
                    <script>
                        function previewImage(event) {
                            const imagePreview = document.getElementById('image-preview');
                            if (event.target.files[0]) {

                                const file = event.target.files[0];
                                const maxSize = 10 * 1024 * 1024;
                                if (file.size > maxSize) {
                                    alert('Image size exceeds the maximum limit of 10MB.');
                                    event.target.value = null;
                                    imagePreview.innerHTML = '';
                                    return;
                                }

                                if (file && file.type.startsWith('image/')) {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        const img = document.getElementById('event-img-preview');
                                        img.src = e.target.result;
                                        img.alt = 'event_img';

                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    document.getElementById('event-img-preview').src = '<?= '../uploads/event_img/' . $event['event_img'] ?>';

                                }
                            } else {
                                document.getElementById('event-img-preview').src = '<?= '../uploads/event_img/' . $event['event_img'] ?>';
                            }


                        }

                        $('#view-note').on('click', function () {
                            $('#image-preview').slideToggle();
                        })
                    </script>

                </div>

                <!-- End time -->
                <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                    <label for="" class="font-semibold">Start date/time<span class="text-red-700">*</span></label>
                    <input type="datetime-local" class="form-input p-1 border active:border-green-950 rounded-sm w-full"
                        value="<?= $event['start_datetime'] ?>" name="start_datetime">
                </div>

                <!-- Start time -->
                <div class="w-full text-sm md:text-base  sm:w-[47%] lg:w-[31%] flex flex-col">
                    <label for="" class="font-semibold">End date/time<span class="text-red-700">*</span></label>
                    <input type="datetime-local" class="form-input p-1 border active:border-green-950 rounded-sm w-full"
                        value="<?= $event['end_datetime'] ?>" name="end_datetime">
                </div>
                <input type="hidden" name="event_id" value="<?= $event_id ?>">
            </div>

            <div class="w-full flex gap-y-10 flex-col-reverse md:flex-row  justify-end my-4 pr-5 mb-5 md:gap-10">

                <?php if ($access == 'admin' || $access == 'staff'): ?>
                    <?php if ($event['status'] == 'pending'): ?>

                        <button type="button"
                            onclick="if($('#create-event').valid())if(confirm('Do you really want to approve this event?'))$('#approve-<?= $event_id ?>').submit();"
                            class="px-8 py-2  self-start md:text-base text-sm bg-green-500 hover:bg-green-400  cursor-pointer transition-default text-white font-semibold rounded-xl">Approve
                            <i class="fa-solid fa-check"></i></button>
                    <?php else: ?>
                        <button type="button"
                            onclick="if($('#create-event').valid())if(confirm('Do you really want to Unapprove this event?'))$('#approve-<?= $event_id ?>').submit();"
                            class="px-8 py-2  self-start md:text-base text-sm bg-orange-500 hover:bg-orange-400  cursor-pointer transition-default text-white font-semibold rounded-xl">Remove
                            approval</button>
                    <?php endif; ?>

                <?php endif; ?>

                <div class="flex self-end gap-10">
                    <button onclick="$('#create-event').submit();" disabled
                        class="px-8 py-2 self-end md:text-base text-sm bg-green-800 cursor-not-allowed opacity-70 transition-default text-white font-semibold rounded-xl"
                        id="upt-btn">Update</button>
                    <button type="button"
                        onclick="if(confirm('Do you really want to delete this event?'))$('#delete-event').submit();"
                        class="px-8 py-2 self-end md:text-base text-sm bg-red-700 hover:bg-red-600 cursor-pointer  transition-default text-white font-semibold rounded-xl">Delete</button>
                </div>

            </div>
        </form>



        <?php if ($event['status'] == 'pending'): ?>

            <form action="../backend/update/approve_event.php" method="POST" id="approve-<?= $event_id ?>">
                <input type="hidden" name="event_id" value="<?= $event_id ?>">
                <input type="hidden" name="status" value="approved">
            </form>
        <?php else: ?>
            <form action="../backend/update/approve_event.php" method="POST" id="approve-<?= $event_id ?>">
                <input type="hidden" name="event_id" value="<?= $event_id ?>">
                <input type="hidden" name="status" value="pending">
            </form>
        <?php endif; ?>

        <form action="../backend/delete/delete_event.php" method="post" id="delete-event">
            <input type="hidden" name="event_id" value="<?= $event_id ?>">
        </form>


        <script>
            $(document).ready(function () {

                $('.form-input').on('change', function () {
                    $("#upt-btn").prop('disabled', false)
                    $("#upt-btn").addClass('hover:bg-green-700')
                    $("#upt-btn").removeClass('opacity-70')
                    $("#upt-btn").removeClass('cursor-not-allowed')

                })

                $.validator.addMethod("greaterThan", function (value, element, params) {
                    var startDate = new Date($('[name="' + params[0] + '"]').val());
                    var endDate = new Date(value);
                    return startDate < endDate;
                }, "This field must be greater than start date/time.");

                $.validator.addMethod("checkConflict", function (value, element, params) {
                    var start_datetime = $('[name="' + params[0] + '"]').val();
                    var end_datetime = $('[name="' + params[1] + '"]').val();
                    if (start_datetime == '' || end_datetime == '') {
                        return true; // If start or end datetime is empty, no conflict check needed
                    }

                    var isValid = false; // Initialize isValid flag

                    $.ajax({
                        type: "POST",
                        url: "../backend/validator/check_conflict_ignore.php",
                        data: {
                            start_datetime: start_datetime,
                            end_datetime: end_datetime,
                            v_id: value,
                            event_id: <?= $event_id ?>
                        },
                        async: false, // Set async to false to wait for the response
                        success: function (response) {
                            console.log(response);
                            isValid = (response === 'false'); // Update isValid based on response
                        }
                    });

                    return isValid; // Return isValid flag
                }, "The venue is not available on that date/time");


                $('#create-event').validate({
                    errorPlacement: function (error, element) {
                        if (element.is(":input")) {
                            error.addClass("text-red-700 text-sm ");
                            error.appendTo(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    rules: {
                        title: 'required',
                        venue: {
                            required: true,
                            checkConflict: ['start_datetime', 'end_datetime']
                        },
                        'input-venue': 'required',
                        start_datetime: {
                            required: true
                        },
                        end_datetime: {
                            required: true,
                            greaterThan: ['start_datetime']
                        }
                    },
                    messages: {
                    }
                });
            });

        </script>

        <?php require "evaluation-form.php"; ?>

        <?php
        if (isset($_SESSION['success'])):
            require "./components/success-message.php";
            unset($_SESSION['success']);
        endif;
        ?>

    </div>

</div>