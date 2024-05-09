<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $f_id = $_POST['f_id'];


    $query = "SELECT * FROM events WHERE f_id = $f_id";
    $result = $conn->query($query);



    ob_flush();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $event_id = $row['event_id'];
?>
            <tr>
                <td class=" my-3 border-b border-b-gray-400 text-lg"><?= $row['title']  ?></td>
                <td class=" text-center border-b border-b-gray-400" onclick="detachEvent(this, <?= $event_id  ?>)">
                    <button type="button" class=" w-28 py-1.5 my-3 border-b  mx-1 self-end md:text-base text-sm inline-block  bg-red-700 hover:bg-red-600 cursor-pointer   transition-default text-white font-semibold rounded-xl" id="upt-btn">Detach</button>
                </td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td class=" my-3 border-b border-b-gray-400 text-lg">No event attached event</td>
            
        </tr>
<?php
    }


    $result = ob_get_clean();

    echo $result;
}
