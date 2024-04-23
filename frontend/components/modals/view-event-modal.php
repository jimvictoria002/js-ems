<div class="w-full px-3 fixed inset-0 z-40 hidden " id="view-event-modal">
    <div
        class="w-[90%] max-h-[90vh]  md:max-h-[75vh] overflow-auto mx-auto md:mt-28 mt-10 pb-12 bg-white rounded-lg p-3 mb-64 z-50 px-5">
        <div class="flex items-start w-full justify-between">
            <div class="w-full flex flex-col my-3 md:my-6">
                <h1 class=" text-2xl md:text-4xl font-semibold  text-green-950" >View event
                </h1>
            </div>

            <i
                class="fa-solid fa-x md:text-xl m-3 cursor-pointer hover:text-red-600 transition-default toggle-create-view"></i>
        </div>

        <div class="flex flex-col md:flex-row w-full gap-7 border p-8">
            <div class="img-container w-full max-w-[30rem] md:max-w-[20rem]">
                <img src="../uploads/event_img/431075091_420591497141585_8112965689675004794_n.png" alt="event-img"
                    class="w-full block" id="view-img">
            </div>
            <div class="w-full flex flex-col justify-between">
                <div class="w-full">
                    <h1 class=" text-2xl md:text-4xl font-semibold  text-green-950" id="event-title">Event title
                    </h1>
                    <div class="flex flex-col py-2 mt-1 md:mt-4" id="event-description-parent">
                        <p class="text-sm font-semibold">Description</p>
                        <p id="event-description">Lorem ipsum dolor, sit amet consectetur adipisicing elit. A,</p>
                    </div>
                    <div class="flex flex-col py-2"">
                        <p class=" text-sm font-semibold">Venue</p>
                        <p id="event-venue">Rizal Recreation Center</p>
                    </div>

                    <div class="flex flex-col py-2"">
                    <p class=" text-sm font-semibold" id="">Date/Time</p>
                        <p id="view-date">February 19, 2024 12:00PM - February 20, 2024 12:00PM</p>
                    </div>

                </div>

                <button type="button" id="edit-btn"
                    class="px-8 py-2  self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400  cursor-pointer transition-default text-white font-semibold rounded-xl">Edit</button>
            </div>

        </div>


    </div>
    <div class="toggle-create-view bg-gray-700 opacity-30 fixed inset-0 -z-50"></div>
</div>


<script>
    $('.toggle-create-view').on('click', function () {
        $('#view-event-modal').fadeToggle('fast');
    })
</script>