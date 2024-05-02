<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

if ($_SESSION['access'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}


$title = 'Users';
require "../connection.php";
require "./partials/header.php";
$active = 'staff';
require "./components/side-nav.php";

$query = "SELECT 
            vt.token, vt.email 
        FROM verification_token vt";
$r_invited = $conn->query($query);

$query = "SELECT 
           * 
        FROM 
            users u
        WHERE u.is_verify = 'yes' AND u.access ='staff'";
$r_users = $conn->query($query);
?>
<main class="w-full overflow-auto  h-screen z-50 ">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Staff management</p>
            <button onclick="" class="toggle-create-staff  px-6 py-2 self-center mr-5 md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl mb-5">Add staff<i class="fa-solid fa-plus ml-1"></i></button>
            <?php
            ?>

            <?php if ($r_invited->num_rows > 0) : ?>
                <!-- Invited container -->
                <div class="table-container w-full overflow-auto mb-20 px-5 border rounded-md" id="pending-tbl">
                    <p class="text-xl font-semibold md:text-2xl my-4">Invited staff</p>
                    <table class="w-full min-w-[34rem] ">
                        <tr>
                            <th class="text-start border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Link</th>
                            <th class="text-start border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Sent to</th>

                            <th class="text-center border border-green-800 font-semibold py-2 px-3  text-white bg-main text-base md:text-lg">
                                Action</th>
                        </tr>

                        <?php while ($user = $r_invited->fetch_assoc()) : ?>
                            <tr class=" main-tr hover:bg-gray-200">

                                <td class="py-5 px-3 border text-start text-sm md:text-base">

                                    <a href="http://localhost:8080/ems2/frontend/staff-registration.php?verification_token=<?= $user['token'] ?>" target="_blank" class="underline">View registration link</a>
                                </td>

                                <td class="py-5 px-3 border text-start text-sm md:text-base ">
                                    <?= $user['email'] ?>
                                </td>

                                <td class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">

                                    <form action="../backend/delete/delete_invitation.php" method="post" id="<?= $user['token'] ?>"><input type="hidden" name="token" value="<?= $user['token'] ?>"></form>

                                    <button type="button" onclick="if(confirm('Do you really want to cancel the invitation?')) $('#<?= $user['token'] ?>').submit();" class="px-6 py-2 mx-auto self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Cancel invitation <i class="fa-solid fa-ban"></i></button>

                                </td>

                            </tr>

                        <?php endwhile; ?>


                    </table>
                </div>
            <?php endif; ?>

            <!-- Staffs container -->
            <div class="table-container w-full overflow-auto mb-20 px-5 border rounded-md" id="pending-tbl">
                <p class="text-xl font-semibold md:text-2xl my-4">Staffs</p>
                <table class="w-full min-w-[34rem] mb-10">
                    <tr>
                        <th rowspan="1" class="text-center border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Image</th>
                        <th rowspan="1" class="text-start border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Firstname</th>
                        <th rowspan="1" class="text-start border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Middlename</th>
                        <th rowspan="1" class="text-start border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Lastname</th>
                        <th rowspan="1" class="text-start border border-green-800 font-semibold py-3 px-3  text-white bg-main text-base md:text-lg">Email</th>
                        <th colspan="1" class="text-center border border-green-800 font-semibold py-2 px-3  text-white bg-main text-base md:text-lg">
                            Action</th>
                    </tr>


                    <?php while ($user = $r_users->fetch_assoc()) : ?>
                        <tr class=" main-tr hover:bg-gray-200">
                            <td class="py-5 px-3 border text-sm md:text-base">
                                <img src="../uploads/user_img/<?= $user['user_img'] ?>" alt="staff-img" class="min-w-16 min-h-16 w-16 h-16 m-auto rounded-full">


                            </td>
                            <td class="py-5 px-3 border text-start text-sm md:text-base">
                                <?= $user['firstname'] ?>
                            </td>

                            <td class="py-5 px-3 border text-start text-sm md:text-base">
                                <?= $user['middlename'] ?>
                            </td>

                            <td class="py-5 px-3 border text-start text-sm md:text-base">
                                <?= $user['lastname'] ?>
                            </td>

                            <td class="py-5 px-3 border text-start text-sm md:text-base ">
                                <?= $user['email'] ?>
                            </td>


                            <td class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                                <form action="../backend/delete/delete_user.php" method="post" id="user<?= $user['user_id'] ?>"><input type="hidden" name="user_id" value="<?= $user['user_id'] ?>"></form>

                                <button type="button" onclick="if(confirm('Do you really want to delete the staff?')) $('#user<?= $user['user_id'] ?>').submit();" class="px-6 py-2 mx-auto self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Delete<i class="fa-solid fa-trast"></i></button>

                            </td>

                        </tr>

                    <?php endwhile; ?>


                </table>
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

    <div class="w-full px-3 fixed inset-0 z-40  hidden" id="create-staff-modal">
        <div class=" bg-white rounded-lg z-50 px-5 w-[20rem] md:w-[25rem] max-h-[90vh] overflow-auto py-5 pb-0 fixed top-[50%] left-[50%] flex flex-col gap-2" style="transform: translate(-50%, -50%);" id="invitation-form">

            <div class="flex justify-between items-start">
                <p class="text-xl md:text-2xl font-semibold mb-2 md:mb-3 text-green-950">Adding staff</p>

                <i class="fa-solid fa-x toggle-create-staff text-xl hover:text-red-700 cursor-pointer"></i>
            </div>
            <select id="adding-type" class="self-end p-1 border active:border-green-950 rounded-sm text-sm">
                <option value="invite">Invite staff</option>

                <option value="add">Create staff</option>
            </select>
            <!-- Invitation container -->
            <form action="../backend/create/invite_staff.php" method="POST" class="flex flex-col gap-y-2  mb-9" id="invite-form">

                <label for="" class="font-semibold">Email<span class="text-red-700">*</span></label>
                <input type="text" placeholder="Enter email" class="p-1 border active:border-green-950 rounded-sm w-full" name="email" id="email">

                <button class=" px-6 py-1.5 md:py-1 self-end md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-md mt-2 mr-2" id="invite-btn">Invite <i class="fa-solid fa-plus "></i></button>
            </form>


            <!-- Create staff -->

            <form action="../backend/create/create_staff.php" method="POST" class="flex flex-col gap-y-3" id="create-form" style="display: none;">
                <div class="flex flex-col ">
                    <label for="firstname" class="font-semibold capitalize">firstname<span class="text-red-700">*</span></label>
                    <input type="text" placeholder="Enter firstname" class="p-1 border active:border-green-950 rounded-sm w-full" name="firstname" id="firstname">
                </div>
                <div class="flex flex-col ">
                    <label for="middlename" class="font-semibold capitalize">middlename</label>
                    <input type="text" placeholder="Enter middlename" class="p-1 border active:border-green-950 rounded-sm w-full" name="middlename" id="middlename">
                </div>
                <div class="flex flex-col ">
                    <label for="lastname" class="font-semibold capitalize">lastname<span class="text-red-700">*</span></label>
                    <input type="text" placeholder="Enter lastname" class="p-1 border active:border-green-950 rounded-sm w-full" name="lastname" id="lastname">
                </div>
                <div class="flex flex-col ">
                    <label for="" class="font-semibold">Email<span class="text-red-700">*</span></label>
                    <input type="text" placeholder="Enter email" class="p-1 border active:border-green-950 rounded-sm w-full" name="email" id="email2">
                </div>
                <div class="flex flex-col ">
                    <div class="flex justify-between items-end">

                        <label for="username" class="font-semibold capitalize">Username<span class="text-red-700">*</span></label>
                        <label for="confirm password" class="text-xs text-main font-semibold pr-2">Must be 5 characters long</label>
                    </div>
                    <input type="text" placeholder="Enter username" class="p-1 border active:border-green-950 rounded-sm w-full" name="username" id="username">
                </div>

                <div class="flex flex-col ">
                    <p class="text-xs text-main font-semibold">The default password is 12345678 </p>
                </div>


                <button class=" px-6 py-1.5 md:py-1 self-end md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-md mt-2 mr-2  mb-5" id="create-btn">Add <i class="fa-solid fa-plus "></i></button>
            </form>

        </div>
        <div class="toggle-create-staff bg-gray-700 opacity-30 fixed inset-0 -z-50"></div>
    </div>


    <script>
        $('#adding-type').on('change', function() {
            let toWhat = $(this).val();
            if (toWhat == 'invite') {
                $('#invite-form').slideToggle();
                $('#create-form').slideToggle();
            } else {
                $('#invite-form').slideToggle();
                $('#create-form').slideToggle();
            }
        });
        $("#invite-form").validate({
            onkeyup: false,
            onfocusout: false,
            errorPlacement: function(error, element) {
                error.addClass("text-red-700 text-sm font-semibold");
                error.insertAfter(element);
            },
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "../backend/validator/check_email.php",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email").val();
                            }
                        }
                    }
                }
            },
            messages: {
                email: {
                    required: "Please enter the email",
                    email: "Please enter a valid email address",
                    remote: "This email is existing"
                }
            },
            submitHandler: function(form) {
                $('#invite-btn').prop('disabled', true);
                $('#invite-btn').css('opacity', '.30');
                form.submit();
            }
        });

        $('#create-form').validate({
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
                        url: "../backend/validator/check_email.php",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email2").val();
                            }
                        }
                    }
                },
                username: {
                    required: true,
                    minlength: 5,
                    remote: {
                        url: "../backend/validator/check_username.php",
                        type: "post",
                        data: {
                            username: function() {
                                return $("#username").val();
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
                }
            },
            submitHandler: function(form) {

                if ($('#create-form').validate()) {
                    $('#create-btn').prop('disabled', true);
                    $('#create-btn').css('opacity', '.30');
                    form.submit();
                }
            }
        });
        $('.toggle-create-staff').on('click', function() {
            $('#create-staff-modal').fadeToggle('fast');
        })
    </script>


</main>

<?php
require "./partials/footer.php";
?>