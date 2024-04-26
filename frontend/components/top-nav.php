<nav class="w-full sticky top-0 flex justify-between z-30   text-green-950  backdrop-blur-sm" id="top-nav">
    <div class="flex items-center mx-3">
        <label for="toggle-nav"><i class="fa-solid fa-bars text-3xl md:text-4xl  cursor-pointer transition-default" id="burger"></i></label>
        <input type="checkbox" name="" id="toggle-nav" class="hidden shadow-sm">
        <div class="flex items-center pl-5">
            <img src="../ems-logo.png" alt="lu-logo" class="w-10 md:w-12 h-10 md:h-12">
            <p class="text-lg md:text-2xl font-semibold py-4 px-2 hidden md:block">Event Management</p>
            <p class="text-lg md:text-2xl font-semibold py-4 px-2 md:hidden block">EMS</p>

        </div>
    </div>

    <div class=" flex items-center md:my-3 mr-4 relative cursor-pointer">
        <div class="profile-toggle flex-col items-end hidden md:flex">
            <?php if ($_SESSION['access'] == 'parent') : ?>
                <p class="text-lg pl-5 whitespace-nowrap"><?= $_SESSION['fullname'] ?></p>
            <?php elseif ($_SESSION['access'] == 'teacher') : ?>
                <p class="text-lg"><?= $_SESSION['first_name'][0] . '. ' . $_SESSION['last_name'] ?></p>
            <?php else : ?>
                <p class="text-lg"><?= $_SESSION['firstname'][0] . '. ' . $_SESSION['lastname'] ?></p>
            <?php endif; ?>
            <p class="text-sm font-bold capitalize"><?= $_SESSION['access'] ?></p>

        </div>
        <div>
            <i class="fa-solid fa-user-circle text-4xl md:text-5xl profile-toggle text-green-950 pl-3"></i>
        </div>
        <div class="absolute top-full shadow-lg right-0 bg-white hidden" id="profile-drop">
            <div class="flex items-center md:py-3 gap-3 pr-2 relative w-48 py-4 md:w-64 justify-end !cursor-default">
                <div class="flex-col items-end flex">
                    <?php if ($_SESSION['access'] == 'parent') : ?>
                        <p class="text-lg pl-5 whitespace-nowrap"><?= $_SESSION['fullname'] ?></p>
                    <?php elseif ($_SESSION['access'] == 'teacher') : ?>
                        <p class="text-lg"><?= $_SESSION['first_name'][0] . '. ' . $_SESSION['last_name'] ?></p>
                    <?php else : ?>
                        <p class="text-lg"><?= $_SESSION['firstname'][0] . '. ' . $_SESSION['lastname'] ?></p>
                    <?php endif; ?>
                    <p class="text-sm font-bold capitalize"><?= $_SESSION['access'] ?></p>
                </div>

                <div>
                    <i class="fa-solid fa-user-circle text-4xl md:text-5xl text-green-950"></i>
                </div>
            </div>
            <?php if ($_SESSION['access'] == 'admin') : ?>
                <a href="#" class="block text-end py-3 md:py-4 pr-4 text-sm md:text-base hover:bg-gray-200">Profile</a>
                <a href="#" class="block text-end py-3 md:py-4 pr-4 text-sm md:text-base hover:bg-gray-200">Change
                    password</a>
            <?php endif; ?>
            <a href="../backend/auth/logout.php" class="block text-end py-3 md:py-4 pr-4 text-sm md:text-base hover:bg-gray-200">Logout</a>
        </div>
    </div>

    <script>
        $('.profile-toggle').on('click', function() {
            $('#profile-drop').fadeToggle();
        })
    </script>
</nav>