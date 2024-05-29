<div class="flex items-center justify-center md:flex-row flex-col w-[100%] lg:py-0 my-[10rem] ">
    <div class="flex justify-center w-full md:w-[70%] min-w-[25rem] rounded-lg  overflow-hidden shadow-lg">

        <form action="./user_credentials/check_login.php" method="post" class="w-full shadow-md px-6 flex flex-col py-16 justify-center items-center border rounded-lg bg-white" id="login">


            <?php if (isset($_SESSION['change_success']) ) { ?>
                <p class="text-white py-3 bg-green-400 rounded-md font-semibold px-2 w-full">
                    <?= $_SESSION['change_success'] ?>
                </p>
            <?php unset($_SESSION['change_success']);
            } ?>
            <div class="flex mb-3 py-2 lg:py-3 self-start">
                <p class="text-3xl text-s-head font-semibold">Staff login</p>
            </div>
            <div class="flex flex-col w-full mb-7">
                <label for="Username" class=" mb-2 font-semibold text-sm">Username</label>
                <div class="relative">
                    <input type="text" name="username" id="username" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter username">

                </div>
            </div>

            <div class="flex flex-col w-full">
                <label for="Username" class=" mb-2 font-semibold text-sm">Password</label>
                <div class="relative" id="passwordContainer">

                    <input type="password" name="password" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter password" autocomplete="new-password" id="passwordField">
                </div>
                <div class="flex w-full mt-5 ml-0.5">
                    <input type="checkbox" id="eye" onclick="togglePasswordVisibility()" class="mr-1 text-xs">
                    <label for="eye" class="text-sm">Show password</label>
                </div>
            </div>
            <button type="submit" class=" bg-main hover:bg-green-800 text-white transition-all ease duration-300 w-36 text-base text-s-bg rounded-sm py-1 self-center text-center mt-5" id="login-btn">Login</button>
            <div class="flex flex-col items-start w-full mt-5">
                <a href="forgot_password.php" class="hover:underline hover:text-s-head text-sm">Forgot
                    password?</a>
                <a href="index.php" class="hover:underline mt-7 hover:text-s-head text-sm">Back to homepage</a>

            </div>
            
        </form>
    </div>
</div>
<script>
    $('#login').validate({

        rules: {
            username: 'required',
            password: 'required'

        },
        errorPlacement: function(error, element) {
            $(element).parent().append(error.addClass('text-red-700 text-sm font-semibold'))
            $(element).addClass('border-red-600')
        },
        success: function(label, element) {
            $(element).removeClass('border-red-600')
        },
        messages: {
            username: 'Username is required',
            password: 'Password is required'
        },
        submitHandler: function(form) {

            $('#login-btn').prop('disabled', true);

            $.ajax({
                url: '../backend/auth/check_login.php',
                type: 'POST',
                data: {
                    username: function() {
                        return $('#username').val();
                    },
                    password: function() {
                        return $('#passwordField').val();
                    },
                    access: '<?= $access ?>'
                },
                success: function(response) {
                    console.log(response);

                    if (response != 'correct') {
                        var errors = {};
                        errors['username'] = 'Invalid credentials';
                        $('#login').validate().showErrors(errors);
                        $('#login-btn').prop('disabled', false);

                    } else {
                        window.location = 'dashboard.php';
                    }

                },
                error: function(xhr, status, error) {

                }
            });
            return false;
        }
    });


    function togglePasswordVisibility() {
        var passwordField = document.getElementById("passwordField");
        var eyeIcon = document.getElementById("eye");

        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>