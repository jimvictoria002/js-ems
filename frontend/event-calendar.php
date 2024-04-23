<?php
session_start();

$title = 'Calendar';
require "../connection.php";
require "./partials/header.php";
$active = 'calendar';
require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 ">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Event calendar</p>
            <button onclick=""
                class="toggle-create px-6 py-2 self-center md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl mb-5">Create
                new event</button>
            <?php
            require "./components/render-calendar.php";
            ?>
        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])):
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>


    <div class="w-full px-3 fixed inset-0 z-40 hidden" id="create-event-modal">
        <div class="w-[80%] max-h-[35rem] overflow-auto mx-auto md:mt-28 mt-10 bg-white rounded-lg p-3 mb-64 z-50 px-5">
            <div class="flex items-start w-full justify-between">
                <h1 class="my-3 md:my-6 text-xl md:text-3xl font-semibold  text-green-950">Create new event</h1>
                <i
                    class="fa-solid fa-x md:text-xl m-3 cursor-pointer hover:text-red-600 transition-default toggle-create"></i>
            </div>


            <form action="../backend/create/create_event.php" method="POST" class="mt-2" id="create-event"
                enctype="multipart/form-data">

                <div class="event-attributes flex items-start gap-y-3 md:gap-y-6 gap-[3%] flex-wrap">
                    <!-- Title -->

                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <label for="" class="font-semibold">Event title<span class="text-red-700">*</span></label>
                        <input type="text" placeholder="Enter title"
                            class="p-1 border active:border-green-950 rounded-sm w-full" name="title">
                    </div>

                    <!-- Description -->
                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <label for="" class="font-semibold">Description</label>
                        <input type="text" placeholder="Enter description"
                            class="p-1 border active:border-green-950 rounded-sm w-full" name="description">
                    </div>

                    <!-- Venue -->
                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <div class="w-full flex justify-between">
                            <label for="" class="font-semibold">Venue<span class="text-red-700">*</span></label>
                            <div class="flex items-center ">
                                <input type="checkbox" name="venue-type" id="venue-option" class="cursor-pointer">
                                <label for="venue-option" class="text-sm ml-1 cursor-pointer">Add venue</label>
                            </div>
                        </div>
                        <?php
                        $query = "SELECT * FROM venue ORDER BY venue ";
                        $result = $conn->query($query);
                        ?>
                        <select name="venue" id="select-venue"
                            class="p-1 border active:border-green-950 rounded-sm w-full ">
                            <option value="">--</option>
                            <?php while ($venue = $result->fetch_assoc()): ?>
                                <option value="<?= $venue['v_id'] ?>"><?= $venue['venue'] ?></option>
                            <?php endwhile; ?>

                        </select>
                        <div>
                            <input type="text" name="input-venue" id="input-venue"
                                class=" hidden p-1 border active:border-green-950 rounded-sm w-full"
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
                                    $('#select-venue').val('');
                                }
                            })
                        </script>
                    </div>

                    <!-- Event image -->
                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <div class="flex justify-between">
                            <label for="event_img" class="font-semibold">Event image<span
                                    class="text-red-700">*</span></label>
                            <p class="text-green-700 text-xs mt-1">Maximum of 10MB<span class="text-red-700">*</span>
                            </p>
                        </div>

                        <input type="file" id="event_img" name="event_img" accept="image/*"
                            class="p-1 border active:border-green-950 rounded-sm w-full text-sm cursor-pointer"
                            onchange="previewImage(event)">
                        <p id="view-note" class="text-sm text-green-700 font-bold mt-2 hidden cursor-pointer">View image
                        </p>

                        <div id="image-preview" class="mt-2 hidden">
                        </div>
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
                                            $('#view-note').show();
                                            const img = document.createElement('img');
                                            img.src = e.target.result;
                                            img.alt = 'Event Image Preview';
                                            img.style.maxWidth = '100%';

                                            imagePreview.innerHTML = '';
                                            imagePreview.appendChild(img);
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        $('#view-note').hide();
                                        $('#image-preview').hide();

                                    }
                                } else {
                                    $('#view-note').hide();
                                    $('#image-preview').hide();
                                }


                            }

                            $('#view-note').on('click', function () {
                                $('#image-preview').slideToggle('fast');
                            })
                        </script>
                    </div>

                    <!-- End time -->
                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <label for="" class="font-semibold">Start date/time<span class="text-red-700">*</span></label>
                        <input type="datetime-local" class="p-1 border active:border-green-950 rounded-sm w-full"
                            name="start_datetime">
                    </div>

                    <!-- Start time -->
                    <div class="w-full text-sm md:text-base  sm:w-[47%] lg:w-[31%] flex flex-col">
                        <label for="" class="font-semibold">End date/time<span class="text-red-700">*</span></label>
                        <input type="datetime-local" class="p-1 border active:border-green-950 rounded-sm w-full"
                            name="end_datetime">
                    </div>









                </div>
            </form>

            <script>
                $(document).ready(function () {

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
                            url: "../backend/validator/check_conflict.php",
                            data: {
                                start_datetime: start_datetime,
                                end_datetime: end_datetime,
                                v_id: value
                            },
                            async: false, // Set async to false to wait for the response
                            success: function (response) {
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
                            event_img: 'required',
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

            <div class="w-full flex justify-end my-4 pr-5 mb-5">
                <button onclick="$('#create-event').submit();"
                    class="px-6 py-2 self-end md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl">Create</button>
            </div>

        </div>
        <div class="toggle-create bg-gray-700 opacity-40 fixed inset-0 -z-50"></div>
    </div>


    <script>
        $('.toggle-create').on('click', function () {
            $('#create-event-modal').fadeToggle('fast');
        })
    </script>

</main>

<?php
require "./partials/footer.php";
?>