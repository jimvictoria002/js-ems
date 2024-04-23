<?php

$user_id = $_SESSION['user_id'];

$add_condition = $access != 'admin' ? "AND e.created_by = $user_id" : '';

$query = "SELECT * FROM events e 
LEFT JOIN users u ON e.created_by = u.user_id 
LEFT JOIN venue v ON e.v_id = v.v_id 
WHERE e.status = 'pending' $add_condition
ORDER BY e.created_at DESC;";




$result = $conn->query($query);



?>

<div class="table-container w-full overflow-auto" id="pending-tbl">
    <table class="w-full min-w-[34rem] ">
        <tr>
            <th rowspan="2"
                class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-lg">Title
            </th>
            <th rowspan="2"
                class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-lg">Date/Time
            </th>
            <th rowspan="2"
                class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-lg">Venue
            </th>
            <th rowspan="2"
                class="text-start border border-green-800 font-semibold py-4 px-3  text-white bg-main text-lg">Creator
            </th>
            <th colspan="<?= $access == 'admin' ? '3' : '2' ?>" rowspan=""
                class="text-center border border-green-800 font-semibold py-1 px-3  text-white bg-main text-lg">Action
            </th>
        </tr>
        <tr>
            <?php if ($access == 'admin'): ?>
                <th class="text-center border border-green-800  font-semibold py-1 px-3  text-white bg-main">Approve</th>
            <?php endif; ?>

            <th class="text-center border border-green-800  font-semibold py-1 px-3  text-white bg-main">View</th>
            <th class="text-center border border-green-800  font-semibold py-1 px-3  text-white bg-main">Delete</th>
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
                <?php if ($access == 'admin'): ?>

                    <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                        <form action="../backend/update/approve_event.php" method="POST" id="approve-<?= $event['event_id'] ?>">
                            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                            <input type="hidden" name="status" value="approved">

                        </form>
                        <button
                            onclick="checkConflict('<?= $event['start_datetime'] ?>', '<?= $event['end_datetime'] ?>', '<?= $event['v_id'] ?>', 'approve-<?= $event['event_id'] ?>')"
                            class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-green-500 hover:bg-green-400  cursor-pointer transition-default text-white font-semibold rounded-xl"
                            id="upt-btn"> <i class="fa-solid fa-check"></i></button>
                    </td>
                <?php endif ?>

                <td rowspan="2" class="py-5 pzx-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <button onclick="window.location='edit_event.php?event_id=<?= $event['event_id'] ?>'"
                        class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-sky-700 hover:bg-sky-400  cursor-pointer transition-default text-white font-semibold rounded-xl"
                        id="upt-btn">
                        <div class="fa-solid fa-eye"></div>
                    </button>
                </td>
                <td rowspan="2" class="py-5 px-3 border text-center text-sm md:text-base whitespace-nowrap">
                    <form action="../backend/delete/delete_event.php" method="POST" id="delete-<?= $event['event_id'] ?>">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    </form>
                    <button type="button"
                        onclick="if(confirm('Do you really want to delete this event?'))$('#delete-<?= $event['event_id'] ?>').submit();"
                        class="px-8 py-2 mx-auto self-end md:text-base text-sm bg-red-700 hover:bg-red-600 cursor-pointer  transition-default text-white font-semibold rounded-xl"
                        id="upt-btn"><i class="fa-solid fa-trash"></i></button>
                </td>


            </tr>

            <tr class="next-tr ">

                <td class="py-4 px-3 border text-start text-sm md:text-base  whitespace-nowrap">
                    <?= date('M d, Y g:ia', strtotime($event['end_datetime'])) ?>
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