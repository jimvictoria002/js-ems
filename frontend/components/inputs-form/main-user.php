<form action="../backend/auth/check_login.php" method="post"
    class="w-full rounded-3xl md:rounded-none   flex flex-col justify-center items-center " id="login">


    <div class="flex flex-col w-full mb-7">
        <label for="Username" class=" mb-2 font-semibold">Username/Email</label>
        <div class="relative">
            <input type="text" name="username" id="username"
                class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter username">

        </div>

        <p class="mt-1 ml-1 hidden" style="color: rgb(150, 31, 31);">
            <?php echo $_SESSION['invalid']; ?>
        </p>
    </div>

    <div class="flex flex-col w-full">
        <label for="Username" class=" mb-2 font-semibold">Password</label>
        <div class="relative" id="passwordContainer">

            <input type="password" name="password" class="w-full  px-2 py-2 border rounded-md focus:outline-none "
                placeholder="Enter password" autocomplete="new-password" id="passwordField">
        </div>
        <div class="flex w-full mt-5 ml-0.5">
            <input type="checkbox" id="eye" onclick="togglePasswordVisibility()" class="mr-1 text-xs">
            <label for="eye" class="text-sm">Show password</label>
        </div>
    </div>
    <button type="submit"
        class=" bg-main hover:bg-green-700 font-semibold text-white transition-all ease duration-300 w-36 text-base text-s-bg rounded-sm py-1 self-center text-center mt-5">Login</button>
    <div class="flex flex-col w-full mt-5">
        <a href="forgot_password.php" class="hover:underline hover:text-s-head text-sm">Forgot
            password?</a>
    </div>

    <script>


        $('#login').validate({
            rules: {
                username: 'required',
                password: 'required'

            },
            messages: {
                username: 'Username is required',
                password: 'Password is required'
            },
            submitHandler: function (form) {

                $.ajax({
                    url: '../backend/auth/check_login.php',
                    type: 'POST',
                    data: {
                        username: function () {
                            return $('#username').val();
                        },
                        password: function () {
                            return $('#passwordField').val();
                        },
                        access: function () {
                            return $('#access').val();
                        }
                    },
                    success: function (response) {
                        console.log(response);

                        if (response != 'correct') {
                            var errors = {};
                            errors['username'] = 'Invalid credentials';
                            $('#login').validate().showErrors(errors);
                        }else{
                            window.location = 'dashboard.php';
                        }

                    },
                    error: function (xhr, status, error) {

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
</form>