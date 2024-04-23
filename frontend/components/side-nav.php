<div class="side-nav pt-10 bg-main inline-flex z-[45] flex-col h-screen text-md md:text-xl text-white sticky top-0">
    <div
        class="nav-anchor flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2 px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center">
            <i class="fa-solid fa-table-columns text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            Dashboard
        </div>
    </div>
    <div
        class=" active flex items-start mt-5 gap-2  transition-default mx-2 px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center toggle-event-drop cursor-pointer">
            <i class="fa-solid fa-calendar-days text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            <p class="whitespace-nowrap toggle-event-drop cursor-pointer" >Event <i class="fa-solid fa-caret-down"></i></p>
            <ul id="event-drop-down">
                <li  class="nav-anchor <?= ($active == 'calendar' ? 'active' : '') ?> block text-base my-2 font-semibold whitespace-nowrap"> <a href="./event-calendar.php" class="block">- Event calendar</a> </li>
                <li  class="nav-anchor <?= ($active == 'pending' ? 'active' : '') ?>  block text-base my-2 font-semibold whitespace-nowrap"> <a href="./pending-events.php" class="block">- Pending events</a> </li>
                <li  class="nav-anchor <?= ($active == 'venue' ? 'active' : '') ?>  block text-base my-2 font-semibold whitespace-nowrap"> <a href="./venue.php" class="block">- Venue</a> </li>
            </ul>
            <script>
                $('.toggle-event-drop').on('click', function(){
                    $('#event-drop-down').slideToggle();
                })
            </script>
        </div>
    </div>
    <div
        class="nav-anchor flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2 px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center">
            <i class="fas fa-envelope-open-text text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            Feedbacks
        </div>
    </div>
    <div
        class="nav-anchor flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2 px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center">
            <i class="fa-solid fa-users text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            Users
        </div>
    </div>
    <!-- <div
        class="nav-anchor flex items-start mt-5 gap-2 cursor-pointer transition-default mx-2 px-0 md:px-1 py-2 rounded-lg">
        <div class="icons w-8 h-8 flex justify-center items-center">
            <i class="fa-solid fa-user-circle text-xl md:text-2xl"></i>
        </div>
        <div class="to-hide mr-6 md:mr-10 font-semibold ">
            Guest
        </div>
    </div> -->
</div>