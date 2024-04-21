<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $fields = isset($_GET['fields']) ? $_GET['fields'] : [];

    if ($fields) {
        $fields = json_decode($fields, true);

    }

    $conditions = [];

    foreach ($fields as $key => $value) {
        $conditions[] = "$key = $value";
    }


    $condition = 'WHERE ' . implode(" AND ", $conditions);

    if ($condition == 'WHERE ') {
        $condition = '';
    }


    $query = "SELECT * FROM student_view  $condition ORDER BY lastname";
    $table_rows = '';
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()):
        $name = $row['lastname'] . ', ' . $row['firstname'] . ($row['middlename'] ? ' ' . $row['middlename'] . '.' : '');
        $class_group = $row['dept_code'] . ' - ' . $row['p_code'] . ($row['major_code'] ? ' - ' . $row['major_code'] : '') . ' - ' . $row['year_level'];
        ob_start();

        ?>

        <tr>
            <td class="border px-3 py-2"><?= $row['std_id'] ?></td>
            <td class="border px-3 py-2"><?= $name ?></td>
            <td class="border px-3 py-2"><?= $class_group ?></td>
            <td class="border px-3 py-2"><input type="checkbox" name="students[]" value="<?= $row['std_id'] ?>"
                    id="<?= $row['std_id'] ?>" checked>
                <label for="<?= $row['std_id'] ?>">Attendee</label>
            </td>
        </tr>



        <?php
        $table_rows .= ob_get_clean();

    endwhile;

    echo $table_rows;

}