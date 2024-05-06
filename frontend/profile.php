<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$access = $_SESSION['access'];

if (!($access == 'admin' || $access == 'staff')) {
    header('Location: dashboard.php');
    exit;
}


$title = 'My profile';
require "../connection.php";
require "./partials/header.php";
$active = 'profile';

require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <form action="../backend/update/update_profile.php" method="POST" class="mt-2" id="update-profile" enctype="multipart/form-data">

                <div class="event-attributes flex items-center justify-center gap-y-3 md:gap-y-10 gap-[3%] flex-wrap">

                    <div class="w-full lg:w-[40%] md:w-[80%] flex items-center justify-center flex-col my-[2rem]">
                        <p class="text-xl font-semibold md:text-3xl self-start my-3">My profile</p>
                        <div class="w-full border  p-10 flex flex-col">

                            <div class=" flex justify-center items-start gap-[3%] w-60 mb-3 self-center">
                                <div class="w-full text-sm md:text-base flex flex-col p-3 border  rounded-md">
                                    <div class="flex justify-between">
                                        <label for="event_img" class="font-semibold">Image</label>
                                    </div>

                                    <div id="image-preview" class="mt-2 border overflow-hidden flex justify-center items-center p-3">
                                        <img src="<?= '../uploads/user_img/' . $_SESSION['user_img'] ?>" alt="event_img" class="w-32  h-32  rounded-full" id="event-img-preview">
                                    </div>

                                    <!-- <p id="view-note" class="text-sm text-green-700 font-bold mt-2  cursor-pointer">View image</p> -->
                                    <!-- Image preview script -->
                                    <script>
                                        function previewImage(event) {
                                            const imagePreview = document.getElementById('image-preview');
                                            if (event.target.files[0]) {

                                                const file = event.target.files[0];

                                                if (file && file.type.startsWith('image/')) {
                                                    const reader = new FileReader();
                                                    reader.onload = function(e) {
                                                        const img = document.getElementById('event-img-preview');
                                                        img.src = e.target.result;
                                                        img.alt = 'event_img';

                                                    };
                                                    reader.readAsDataURL(file);
                                                } else {
                                                    document.getElementById('event-img-preview').src = '<?= '../uploads/user_img/' . $_SESSION['user_img'] ?>';

                                                }
                                            } else {
                                                document.getElementById('event-img-preview').src = '<?= '../uploads/user_img/' . $_SESSION['user_img'] ?>';
                                            }


                                        }

                                        $('#view-note').on('click', function() {
                                            $('#image-preview').slideToggle();
                                        })
                                    </script>
                                    <input type="file" id="event_img" name="user_img" accept="image/*" class="form-input p-1 border mt-3 active:border-green-950 rounded-sm w-full text-sm cursor-pointer" onchange="previewImage(event)">




                                </div>
                            </div>
                            <div class="flex flex-col gap-y-5">
                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <label for="firstname" class="font-semibold capitalize">Firstname</label>
                                    <input type="text" placeholder="Enter firstname" id="firstname" value="<?= $_SESSION['firstname'] ?>" class="p-1 border active:border-green-950 rounded-sm w-full" name="firstname">
                                </div>
                                <!-- Description -->
                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <label for="middlename" class="font-semibold capitalize">middlename</label>
                                    <input type="text" placeholder="Enter middlename" id="middlename" value="<?= $_SESSION['middlename'] ?>" class="p-1 border active:border-green-950 rounded-sm w-full" name="middlename">
                                </div>

                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <label for="lastname" class="font-semibold capitalize">lastname</label>
                                    <input type="text" placeholder="Enter lastname" id="lastname" value="<?= $_SESSION['lastname'] ?>" class="p-1 border active:border-green-950 rounded-sm w-full" name="lastname">
                                </div>

                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <label for="email" class="font-semibold capitalize">email</label>
                                    <input type="text" placeholder="Enter email" id="email" value="<?= $_SESSION['email'] ?>" class="p-1 border active:border-green-950 rounded-sm w-full" name="email">
                                </div>
                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <label for="username" class="font-semibold capitalize">username</label>
                                    <input type="text" placeholder="Enter username" id="username" value="<?= $_SESSION['username'] ?>" class="p-1 border active:border-green-950 rounded-sm w-full" name="username">
                                </div>

                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <div class="flex justify-between w-full">
                                        <label for="password" class="font-semibold capitalize">password</label>
                                        <label for="password" class="text-sm ">Change your password here</label>

                                    </div>
                                    <input type="password" placeholder="Enter password" id="password" class="p-1 border active:border-green-950 rounded-sm w-full" name="password">
                                    <label for="password" class="text-xs self-end font-semibold text-green-800">Must 8 characters long</label>

                                </div>

                                <div class="w-full text-sm md:text-base  flex flex-col">
                                    <label for="" class=" text-sm mb-1">Input your current password to save changes</label>
                                    <label for="current_password" class="font-semibold capitalize">Current password</label>
                                    <input type="password" placeholder="Enter current password" id="current_password" class="p-1 border active:border-green-950 rounded-sm w-full" name="confirmPassword">
                                </div>

                                <button type="submit" class="toggle-create  px-6 py-2 self-end mr-5 md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl mt-2">Save changes</button>
                            </div>
                        </div>


                    </div>
                    <!-- Title -->










                </div>
            </form>
        </div>

        <script>
            $('#update-profile').validate({
                onkeyup: false,
                onfocusout: false,
                errorPlacement: function(error, element) {
                    error.addClass("text-red-700 text-sm font-semibold");
                    error.insertAfter(element);
                },
                rules: {
                    firstname: {
                        required: true
                    },
                    lastname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "../backend/validator/check_email_ignore.php",
                            type: "post",
                            data: {
                                email: function() {
                                    return $("#email").val();
                                }
                            }
                        }
                    },
                    username: {
                        required: true,
                        minlength: 5,
                        remote: {
                            url: "../backend/validator/check_username_ignore.php",
                            type: "post",
                            data: {
                                username: function() {
                                    return $("#username").val();
                                }
                            }
                        }
                    },
                    password: {
                        minlength: 8
                    },
                    confirmPassword: {
                        required: true,
                        minlength: 8,
                        remote: {
                            url: "../backend/validator/check_password.php",
                            type: "post",
                            data: {
                                current_password: function() {
                                    return $("#current_password").val();
                                }
                            }
                        }
                    }
                },
                messages: {
                    email: {
                        email: "Please enter a valid email address",
                        remote: "This email is existing"
                    },
                    username: {
                        minlength: "Username must be at least 5 characters long",
                        remote: "This username is not available"
                    },
                    password: {
                        minlength: "Password must be at least 8 characters long"
                    },
                    confirmPassword: {
                        minlength: "Password must be at least 8 characters long",
                        remote: "Wrong password"
                    }
                },
                submitHandler: function(form) {
                    $('#create-btn').prop('disabled', true);
                    $('#create-btn').css('opacity', '.30');
                    form.submit();
                }
            });
        </script>

    </div>

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