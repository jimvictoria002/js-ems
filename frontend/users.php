<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

$title = 'Users';
require "../connection.php";
require "./partials/header.php";
$active = 'users';
require "./components/side-nav.php";
?>
<main class="w-full overflow-auto  h-screen z-50 ">
    <?php
    require "./components/top-nav.php";
    ?>
    <div class="p-5 mb-40">
        <div class="bg-white p-4 w-full flex flex-col">
            <p class="text-xl font-semibold md:text-3xl my-8 mt-3">Users</p>
            <button onclick="" class="toggle-invite-staff  px-6 py-2 self-center mr-5 md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-xl mb-5">Invite staff<i class="fa-solid fa-plus ml-1"></i></button>
            <?php
            ?>
        </div>

    </div>

    <?php
    if (isset($_SESSION['success'])) :
        require "./components/success-message.php";
        unset($_SESSION['success']);
    endif;
    ?>

    <div class="w-full px-3 fixed inset-0 z-40 hidden " id="invite-staff-modal">
        <form action="../backend/create/invite_staff.php" method="POST" class=" bg-white rounded-lg z-50 px-5 w-[25rem] py-5 fixed top-[35%] left-[50%] flex flex-col gap-2" style="transform: translate(-50%, -50%);" id="invitation-form">
            <p class="text-2xl md:text-3xl font-semibold mb-2 md:mb-3 text-green-950">Invite staff</p>
            <label for="" class="font-semibold">Email<span class="text-red-700">*</span></label>
            <input type="text" placeholder="Enter email" class="p-1 border active:border-green-950 rounded-sm w-full" name="email" id="email">

            <button class=" px-6 py-1.5 md:py-1 self-end md:text-base text-sm bg-green-800 hover:bg-green-700 transition-default text-white font-semibold rounded-md mt-2 mr-2" id="invite-btn">Invite <i class="fa-solid fa-plus "></i></button>
        </form>
        <div class="toggle-invite-staff bg-gray-700 opacity-30 fixed inset-0 -z-50"></div>
    </div>


    <script>
        $("#invitation-form").validate({
            errorPlacement: function(error, element) {
                error.addClass("text-red-700");
                error.insertAfter(element); // Place the error label after the input element
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
            submitHandler:function(form){
                $('#invite-btn').prop('disabled', true);
                $('#invite-btn').css('opacity', '.30');
                form.submit();
            }
        });
        $('.toggle-invite-staff').on('click', function() {
            $('#invite-staff-modal').fadeToggle('fast');
        })
    </script>


</main>

<?php
require "./partials/footer.php";
?>