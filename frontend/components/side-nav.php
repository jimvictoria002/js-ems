<div class="side-nav pt-10 bg-main inline-flex z-[45] flex-col h-screen text-md md:text-xl text-white sticky top-0">
    <a href="dashboard.php" class="nav-anchor <?= ($active == 'dashboard' ? 'active' : '') ?>  flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2  px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center">
            <i class="fa-solid fa-table-columns text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            Dashboard
        </div>
    </a>
    <div class=" active flex items-start mt-5 gap-2  transition-default mx-2  px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center toggle-event-drop cursor-pointer">
            <i class="fa-solid fa-calendar-days text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            <p class="whitespace-nowrap toggle-event-drop cursor-pointer">Event <i class="fa-solid fa-caret-down ml-4"></i></p>
            <ul id="event-drop-down">
                <li class="nav-anchor <?= ($active == 'calendar' ? 'active' : '') ?> block text-base my-2 font-semibold whitespace-nowrap"> <a href="./event-calendar.php" class="block">Event calendar</a> </li>

                <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
                    <li class="nav-anchor  <?= ($active == 'myevents' ? 'active' : '') ?>  block text-base my-2 font-semibold whitespace-nowrap"> <a href="./my-events.php" class="block">My events</a> </li>
                <?php endif; ?>
                <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
                    <li class="nav-anchor  <?= ($active == 'form' ? 'active' : '') ?> block text-base my-2 font-semibold whitespace-nowrap"> <a href="./my-form.php" class="block"><?php if ($access == 'admin' || $access == 'staff') : ?>
                                Forms

                            <?php else : ?>
                                My forms

                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($access == 'admin' || $access == 'staff') : ?>
                    <li class="nav-anchor  <?= ($active == 'pending' ? 'active' : '') ?>  block text-base my-2 font-semibold whitespace-nowrap"> <a href="./pending-events.php" class="block">Pending events</a> </li>
                <?php endif; ?>

                <?php if ($access == 'admin'  || $access == 'staff') : ?>
                    <li class="nav-anchor  <?= ($active == 'venue' ? 'active' : '') ?>  block text-base my-2 font-semibold whitespace-nowrap"> <a href="./venue.php" class="block">Venue</a> </li>
                <?php endif; ?>

            </ul>
            <script>
                $('.toggle-event-drop').on('click', function() {
                    $('#event-drop-down').slideToggle();
                })
            </script>
        </div>
    </div>
    <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
        <a href="feedbacks.php" class="nav-anchor <?= ($active == 'feedback' ? 'active' : '') ?> flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2  px-0 md:px-1 py-2 rounded-lg">
            <div class="icons w-8 h-8 flex justify-center items-center">
                <i class="fas fa-envelope-open-text text-xl md:text-2xl"></i>
            </div>
            <div class="to-hide mr-6 md:mr-10 font-semibold ">
                Feedbacks
            </div>
        </a>
    <?php endif; ?>

    <?php if ($access == 'admin') : ?>
        <a href="users.php" class="nav-anchor  <?= ($active == 'staff' ? 'active' : '') ?> flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2  px-0 md:px-1 py-2 rounded-lg">
            <div class="icons w-8 h-8 flex justify-center items-center">
                <i class="fa-solid fa-users text-xl md:text-2xl"></i>
            </div>
            <div class="to-hide mr-6 md:mr-10 font-semibold ">
                Staffs
            </div>
        </a>
    <?php endif; ?>

    <?php if ($access == 'admin' || $access == 'teacher' || $access == 'staff' || $access == 'student') : ?>
        <a href="restore.php" class="nav-anchor <?= ($active == 'restore' ? 'active' : '') ?> flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2  px-0 md:px-1 py-2 rounded-lg">
            <div class="icons w-8 h-8 flex justify-center items-center">
                <i class="fas fa-trash-can-arrow-up text-xl md:text-2xl"></i>
            </div>
            <div class="to-hide mr-6 md:mr-10 font-semibold ">
                Restore
            </div>
        </a>
    <?php endif; ?>

    <!-- <div
        class="nav-anchor flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2  px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center">
            <i class="fa-solid fa-user-circle text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            Guest
        </div>
    </div> -->
</div>