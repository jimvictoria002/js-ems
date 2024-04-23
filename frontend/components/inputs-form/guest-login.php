<form action="../backend/auth/check_login.php" method="post"
    class="w-full rounded-3xl md:rounded-none   flex flex-col justify-center items-center "
    id="">

    
    <div class="flex flex-col w-full mb-7">
        <label for="firstname" class=" mb-2 font-semibold capitalize">firstname<span class="text-red-700">*</span></label>
        <div class="relative">
            <input type="text" name="firstname" class="w-full  px-2 py-2 border rounded-md focus:outline-none "
                placeholder="Enter firstname">

        </div>
    </div>

    <div class="flex flex-col w-full mb-7">
        <label for="middlename" class=" mb-2 font-semibold capitalize">middlename </label>
        <div class="relative">
            <input type="text" name="middlename" class="w-full  px-2 py-2 border rounded-md focus:outline-none "
                placeholder="Enter middlename">

        </div>
    </div>
    <div class="flex flex-col w-full mb-7">
        <label for="lastname" class=" mb-2 font-semibold capitalize">lastname <span class="text-red-700">*</span></label>
        <div class="relative">
            <input type="text" name="lastname" class="w-full  px-2 py-2 border rounded-md focus:outline-none "
                placeholder="Enter lastname">

        </div>
    </div>

    <div class="flex flex-col w-full mb-7">
        <label for="email" class=" mb-2 font-semibold capitalize">email <span class="text-red-700">*</span></label>
        <div class="relative">
            <input type="text" name="email" class="w-full  px-2 py-2 border rounded-md focus:outline-none "
                placeholder="Enter email">

        </div>
    </div>


    <button type="submit"
        class=" bg-main hover:bg-green-700 font-semibold text-white transition-all ease duration-300 w-36 text-base text-s-bg rounded-sm py-1 self-center text-center mt-5">Login</button>
   

    <script>

        $(document).ready(function () {

            $('#login-form').validate({
                rules: {
                    username: 'required',
                    password: 'required'
                },
                messages: {
                    username: 'Username is required',
                    password: 'Password is required'
                }
            });


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