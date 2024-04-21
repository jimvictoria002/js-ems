<div class="w-full px-3">
    <h1 class="mt-7 md:mt-10 text-2xl md:text-3xl font-bold  text-green-950">Create new event</h1>
    <form action="#" class="bg-white rounded-lg p-3 mt-2">
        <div class="event-attributes flex items-start gap-y-6 gap-[3%] flex-wrap">
            <!-- Title -->

            <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                <label for="" class="font-semibold">Event title<span class="text-red-700">*</span></label>
                <input type="text" placeholder="Enter title"
                    class="p-1 border active:border-green-950 rounded-sm w-full" name="event_title">
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
                        <input type="checkbox" id="venue-option" class="cursor-pointer">
                        <label for="venue-option" class="text-sm ml-1 cursor-pointer">Select exisiting venue</label>
                    </div>
                </div>

                <select name="venue" id="select-venue"
                    class="p-1 border active:border-green-950 rounded-sm w-full hidden">
                    <option value="">--</option>
                    <option value="1">Oreta</option>
                    <option value="2">NB sa likod</option>
                    <option value="3">Multi purpose</option>
                </select>
                <div>
                    <input type="text" name="venue" id="input-venue"
                        class="p-1 border active:border-green-950 rounded-sm w-full" placeholder="Enter venue">
                </div>

                <script>
                    $('#venue-option').on('change', function () {
                        let state = $(this).prop('checked');
                        if (state) {
                            $('#select-venue').show();
                            $('#input-venue').hide();
                            $('#input-venue').val('');
                        } else {
                            $('#input-venue').show();
                            $('#select-venue').hide();
                            $('#select-venue').val('');
                        }
                    })
                </script>
            </div>

            <!-- Event image -->
            <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                <div class="flex justify-between">
                    <label for="event_img" class="font-semibold">Event image</label>
                    <p class="text-green-700 text-xs mt-1">Maximum of 10MB<span class="text-red-700">*</span></p>
                </div>

                <input type="file" id="event_img" name="event_img" accept="image/*"
                    class="p-1 border active:border-green-950 rounded-sm w-full text-sm cursor-pointer"
                    onchange="previewImage(event)">
                <p id="view-note" class="text-sm text-green-700 font-bold mt-2 hidden cursor-pointer">View image</p>

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

            <?php require "evaluation-form.php"; ?>

            <!-- Attendee container -->
            <!-- <div class="w-full flex flex-col items-start">
                <p class="text-2xl font-semibold">Attendee<span class="text-red-700">*</span></p>
                <div class="flex items-center py-4">
                    <input type="checkbox" id="all-students" class="attendee-selector cursor-pointer">
                    <label for="all-students" class="text-sm ml-1 cursor-pointer">Select all students</label>
                    <script>
                        $('#all-students').on('change', function () {
                            let state = $(this).prop('checked');
                            if (state) {
                                $('#attendee-select').slideUp('fast');
                            } else {
                                $('#attendee-select').slideDown('fast');

                            }
                        })
                    </script>
                </div>

                <div class="w-full  flex flex-wrap gap-[3%] gap-y-8" id="attendee-select">

                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <label for="" class="font-semibold">Departments</label>
                        <?php
                        $query = "SELECT * FROM departments";
                        $result = $conn->query($query);
                        ?>
                        <select name="department" id="select-department"
                            class="attendee-selector p-1 border active:border-green-950 rounded-sm w-full">
                            <option value="">--</option>
                            <?php while ($department = $result->fetch_assoc()): ?>
                                <option value="<?= $department['dept_id'] ?>">
                                    <?= $department['dept_code'] . ' - ' . $department['description'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <p class="text-green-700 text-xs mt-2">If not applicable, leave blank. </p>
                    </div>

                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <div class="flex items-center justify-between">
                            <label for="" class="font-semibold">Programs</label>
                            <p class="text-xs text-green-700">Select department first</p>
                        </div>

                        <select name="program" id="select-program"
                            class="attendee-selector p-1 border active:border-green-950 rounded-sm w-full">
                            <option value="">--</option>
                        </select>
                        <p class="text-green-700 text-xs mt-2">If not applicable, leave blank. </p>
                    </div>

                    <div class="w-full text-sm md:text-base sm:w-[47%] lg:w-[31%] flex flex-col">
                        <label for="" class="font-semibold">Year level</label>

                        <select name="year_level" id="select-year-level"
                            class="attendee-selector p-1 border active:border-green-950 rounded-sm w-full">
                            <option value="">--</option>
                            <option value="1">1st year</option>
                            <option value="2">2nd year</option>
                            <option value="3">3rd year</option>
                            <option value="4">4th year</option>
                        </select>
                        <p class="text-green-700 text-xs mt-2">If not applicable, leave blank. </p>
                    </div>

                    <script>
                        $('#select-department').on('change', function () {
                            let dept_id = $(this).val();
                            $('#select-program').val('');

                            $.ajax({
                                type: "GET",
                                url: "../backend/fetcher/fetch_course.php",
                                data: {
                                    dept_id: dept_id
                                },
                                success: function (response) {
                                    $('#select-program').html(response);
                                }
                            });
                        });

                        $('.attendee-selector').on('change', function () {
                            let name = $(this).prop('name');

                            if (name != '') {
                                let dept_id = $('#select-department').val();
                                let p_id = $('#select-program').val();
                                let year_level = $('#select-year-level').val();
                                let fields = {};

                                if (dept_id !== '') fields['dept_id'] = dept_id;
                                if (p_id !== '') fields['p_id'] = p_id;
                                if (year_level !== '') fields['year_level'] = year_level;

                                if (Object.keys(fields).length === 0) {
                                    $('#all-students').prop('checked', true);
                                    $('#attendee-select').slideUp('fast');
                                }


                                $.ajax({
                                    type: "GET",
                                    url: "../backend/fetcher/fetch_students.php",
                                    data: {
                                        fields: JSON.stringify(fields)
                                    },
                                    success: function (response) {
                                        $('#tr-container').html(response)
                                        $('#view-attendee').slideDown();
                                    }
                                });
                            } else {
                                let state = $(this).prop('checked');
                                if (state) {
                                    $.ajax({
                                        type: "GET",
                                        url: "../backend/fetcher/fetch_students.php",
                                        data: {
                                            fields: {}
                                        },
                                        success: function (response) {
                                            $('#tr-container').html(response)
                                            $('#view-attendee').slideDown();
                                        }
                                    });
                                } else {
                                    $('#tr-container').html('')
                                    $('#view-attendee').slideUp();
                                }

                            }
                        })
                    </script>
                </div>

                <p id="view-attendee"
                    class="hidden show-table-con text-base  text-green-700 font-bold my-5 cursor-pointer ">
                    View
                    attendee
                </p>

                <div id="table-container" class=" w-full hidden">
                    <table class="w-full">
                        <tr>
                            <th class="border font-semibold text-start px-3 py-2">Student number</th>
                            <th class="border font-semibold text-start px-3 py-2">Student name</th>
                            <th class="border font-semibold text-start px-3 py-2">Class group</th>
                            <th class="border font-semibold text-start px-3 py-2">Action</th>
                        </tr>
                        <tbody id="tr-container">

                        </tbody>
                    </table>
                    <p class=" show-table-con text-base ml-auto text-end text-green-700 font-bold my-5 cursor-pointer ">
                        Hide attendee
                    </p>
                </div>

                <script>
                    $('.show-table-con').on('click', function () {
                        $('#table-container').slideToggle();
                    })
                </script>

            </div> -->



        </div>
    </form>


</div>