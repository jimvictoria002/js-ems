<?php
$query = "SELECT * FROM events e 
LEFT JOIN users u ON e.created_by = u.user_id 
LEFT JOIN venue v ON e.v_id = v.v_id 
WHERE e.status = 'pending'
ORDER BY e.created_at DESC;";
$result = $conn->query($query);



?>

<div class="table-container w-full overflow-auto" id="pending-tbl">
    <table class="w-full min-w-[34rem] ">
        <tr>
            <th class="text-start font-semibold py-4 px-3  text-white bg-main">Title</th>
            <th class="text-start font-semibold py-4 px-3  text-white bg-main">Date</th>
            <th class="text-start font-semibold py-4 px-3  text-white bg-main">Venue</th>
            <th class="text-start font-semibold py-4 px-3  text-white bg-main">Creator</th>
            <th colspan="3" class="text-center font-semibold py-4 px-3  text-white bg-main">Action</th>
        </tr>
        <?php while ($event = $result->fetch_assoc()): ?>
            <tr class=" main-tr">

                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base ">
                    <?= $event['title'] ?>
                </td>
                <td class="py-4 px-3 border text-start text-sm md:text-base  whitespace-nowrap">
                    <?= date('M d, Y g:ia', strtotime($event['start_datetime'])) ?>
                </td>


                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base ">
                    <?= $event['venue'] ?>
                </td>

                <td rowspan="2" class="py-5 px-3 border text-start text-sm md:text-base whitespace-nowrap">
                    <?= $event['firstname'][0] . '. ' . $event['lastname'] ?>
                </td>
                <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <form action="../backend/update/approve_event.php" method="POST" id="approve-<?= $event['event_id'] ?>">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <input type="hidden" name="status" value="approved">

                    </form>
                    <button
                        onclick="if(confirm('Do you really want to approve this event?'))$('#approve-<?= $event['event_id'] ?>').submit();"
                        class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-green-500 hover:bg-green-400  cursor-pointer transition-default text-white font-semibold rounded-xl"
                        id="upt-btn">Approve <i class="fa-solid fa-check"></i></button>
                </td>
                <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <button onclick="window.location='edit_event.php?event_id=<?= $event['event_id'] ?>'"
                        class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400  cursor-pointer transition-default text-white font-semibold rounded-xl"
                        id="upt-btn">View <div class="fa-solid fa-eye"></div></button>
                </td>
                <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <form action="../backend/delete/delete_event.php" method="POST" id="delete-<?= $event['event_id'] ?>">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    </form>
                    <button type="button"
                        onclick="if(confirm('Do you really want to delete this event?'))$('#delete-<?= $event['event_id'] ?>').submit();"
                        class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-red-700 hover:bg-red-600 cursor-pointer  transition-default text-white font-semibold rounded-xl"
                        id="upt-btn">Delete</button>
                </td>


            </tr>

            <tr class="next-tr ">

                <td class="py-4 px-3 border text-start text-sm md:text-base  whitespace-nowrap">
                    <?= date('M d, Y g:ia', strtotime($event['end_datetime'])) ?>
                </td>
            </tr>

        <?php endwhile; ?>

    </table>
</div>