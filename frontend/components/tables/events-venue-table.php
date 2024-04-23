<?php
$query = "SELECT
v.v_id,
v.venue,
COUNT(e.event_id) AS total_in_use
FROM
venue v
LEFT JOIN
events e ON v.v_id = e.v_id 
GROUP BY
v.v_id, v.venue
ORDER BY total_in_use DESC;
";
$result = $conn->query($query);



?>
<script>

    function updateVenue(venue, v_id) {
        var new_venue = window.prompt("Update venue \n\n!Note: Updating the venue will affect events that use it.", venue);
        if(!new_venue || new_venue == ''){
            return;
        }
        if (new_venue != venue) {
            $.ajax({
                type: "POST",
                url: "../backend/update/update_venue.php",
                data: {
                    venue: new_venue,
                    v_id: v_id
                },
                success: function (response) {
                    if (response == '1') {
                        $('#to-change-td-' + v_id).html(new_venue);
                    }
                }
            });
        }

    }

    function deleteVenue(v_id, e) {
        
      
        if (confirm('Do you really want to delete the venue?')) {
            $.ajax({
                type: "POST",
                url: "../backend/delete/delete_venue.php",
                data: {
                    v_id: v_id
                },
                success: function (response) {
                    $(e).parent().parent().remove();
                }
            });
        }

    }
</script>

<div class="table-container w-full overflow-auto" id="pending-tbl">
    <table class="w-full min-w-[34rem] ">
        <tr>
            <th rowspan="2"
                class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-lg">Venue
            </th>
            <th rowspan="2"
                class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-lg">Total in
                use</th>

            <th colspan="2"
                class="text-center border border-green-800 font-semibold py-2 px-3  text-white bg-main text-lg">
                Action</th>
        </tr>

        <tr>
            <th class="text-center border border-green-800 font-semibold py-1 px-3  text-white bg-main text-lg">Edit
            </th>
            <th class="text-center border border-green-800 font-semibold py-1 px-3  text-white bg-main text-lg">Delete
            </th>

        </tr>
        <?php while ($venue = $result->fetch_assoc()): ?>
            <tr class=" main-tr hover:bg-gray-200">

                <td class="py-5 px-3 border text-start text-sm md:text-base" id="to-change-td-<?= $venue['v_id'] ?>">
                    <?= $venue['venue'] ?>
                </td>

                <td class="py-5 px-3 border text-start text-sm md:text-base ">
                    <?= $venue['total_in_use'] ?>
                </td>


                <td class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <button onclick="updateVenue($('#to-change-td-<?= $venue['v_id'] ?>').text().trim(),<?= $venue['v_id'] ?>)"
                        class="px-6 py-2 mx-auto self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400  cursor-pointer transition-default text-white font-semibold rounded-xl"
                        id="upt-btn">
                        <div class="fa-solid text-lg fa-pen-to-square"></div>
                    </button>
                </td>
                <td class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">

                    <?php if($venue['total_in_use'] > 0 ): ?>
                    <button type="button"
                        disabled
                        class="px-6 py-2 mx-auto self-end md:text-base text-sm  bg-red-700  opacity-30 transition-default text-white font-semibold rounded-xl"
                        id="upt-btn"><i class="fa-solid fa-trash"></i></button>
                    <?php else: ?>
                        <button type="button"
                        onclick="deleteVenue(<?= $venue['v_id'] ?>, this)"
                        class="px-6 py-2 mx-auto self-end md:text-base text-sm  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl"
                        id="upt-btn"><i class="fa-solid fa-trash"></i></button>
                    <?php endif; ?>

                </td>

            </tr>

        <?php endwhile; ?>


        <script>
            function checkConflict(start_datetime, end_datetime, v_id, to_submit) {

                $.ajax({
                    type: "POST",
                    url: "../backend/validator/check_conflict.php",
                    data: {
                        start_datetime: start_datetime,
                        end_datetime: end_datetime,
                        v_id: v_id

                    },
                    success: function (response) {
                        if (response == 'false') {
                            if (confirm('Do you really want to approve the event?')) {
                                $('#' + to_submit).submit();
                            }
                        } else {


                            alert('The venue is not available on that date/time');
                        }
                    }
                });
            }
        </script>

    </table>
</div>