<div class="flex items-center justify-center md:flex-row flex-col w-[100%] lg:py-0 ">
    <div class="flex justify-center w-full md:w-[70%]  rounded-lg  overflow-hidden shadow-lg">

        <form action="./user_credentials/check_login.php" method="post" class=" w-full shadow-md px-6 flex flex-col py-4 justify-center items-center border rounded-lg bg-white" id="login">


            <?php if (isset($_SESSION['change_success'])) { ?>
                <p class="text-white py-3 bg-green-400 rounded-md font-semibold px-2 w-full">
                    <?= $_SESSION['change_success'] ?>
                </p>
            <?php unset($_SESSION['change_success']);
            } ?>
            <div class="flex mb-3 py-2 lg:py-3 self-start">
                <p class="text-3xl text-s-head font-semibold">Guest sign in</p>
            </div>
            <div class="flex flex-col w-full mb-7">
                <label for="firstname" class=" mb-2 font-semibold text-sm">Firstname <span class="text-red-700">*</span></label>
                <div class="relative">
                    <input type="text" name="firstname" id="firstname" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter firstname">

                </div>
            </div>

            <div class="flex flex-col w-full mb-7">
                <label for="middlename" class=" mb-2 font-semibold text-sm capitalize">middlename</label>
                <div class="relative">
                    <input type="text" name="middlename" id="middlename" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter middlename">

                </div>
            </div>

            <div class="flex flex-col w-full mb-7">
                <label for="lastname" class=" mb-2 font-semibold text-sm capitalize">lastname <span class="text-red-700">*</span></label>
                <div class="relative">
                    <input type="text" name="lastname" id="lastname" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter lastname">

                </div>
            </div>

            

            <div class="flex flex-col w-full mb-7">
                <label for="email" class=" mb-2 font-semibold text-sm capitalize">email <span class="text-red-700">*</span></label>
                <div class="relative">
                    <input type="text" name="email" id="email" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Enter email">

                </div>
            </div>

            <!-- <div class="flex flex-col w-full mb-7">
                <label for="kind" class=" mb-2 font-semibold text-sm capitalize">Kind of guest <span class="text-red-700">*</span></label>
                <div class="relative">
                    <input type="text" name="kind" id="kind" class="w-full  px-2 py-2 border rounded-md focus:outline-none " placeholder="Please specify guest kind">

                </div>
            </div> -->



            <button type="submit" class=" bg-main hover:bg-green-800 text-white transition-all ease duration-300 w-36 text-base text-s-bg rounded-sm py-1 self-center text-center mt-5" id="login-btn">Sign in</button>
            <div class="flex flex-col items-start self-start mt-5">
                <a href="index.php" class="hover:underline hover:text-s-head text-sm">Back to homepage</a>

            </div>
        </form>
    </div>
</div>
<script>
    $('#login').validate({

        rules: {
            firstname: 'required',
            lastname: 'required',
            email: 'required',
            kind: 'required',
            email: {
                required: true,
                email: true 
            }

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
            password: 'Password is required',
            kind: 'Please specify guest kind'
        },
        submitHandler: function(form) {

            $('#login-btn').prop('disabled', true);

            $.ajax({
                url: '../backend/auth/check_login.php',
                type: 'POST',
                data: {
                    firstname: function() {
                        return $('#firstname').val();
                    },
                    middlename: function() {
                        return $('#middlename').val();
                    },
                    lastname: function() {
                        return $('#lastname').val();
                    },
                    email: function() {
                        return $('#email').val();
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
</script>