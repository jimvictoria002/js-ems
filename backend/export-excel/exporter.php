<?php
require "../../connection.php";
require "../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $data = get_data($event_id);
    $columns = $data['columns'];
    $spreadsheet = new Spreadsheet();
    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers using the column names
    $col_count = 1;
    foreach ($columns as $index => $columnName) {
        // Assuming $columnName is a string representing the column name
        if ($index != 'responses') {
            $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count) . '1';
            $sheet->setCellValue($cellCoordinate, $columnName);
            $col_count++;
        } else {
            foreach ($columnName as $i => $value) {
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count) . '1';
                $sheet->setCellValue($cellCoordinate, $value);
                $col_count++;
            }
        }
    }

    // Apply different styles to header and data cells
    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
            'wrapText' => true,
        ],
    ];

    $dataStyle = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_TOP,
            'wrapText' => true,
        ],
    ];

    // Apply styles to header cells
    $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count - 1) . '1';
    $sheet->getStyle($headerRange)->applyFromArray($headerStyle);

    // Populate data into the spreadsheet
    $row = 2; // Start from the second row
    foreach ($data['data'] as $rowData) {
        $col_count = 1;
        foreach ($columns as $key => $value) {
            if ($key != 'responses') {
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count) . $row;
                $sheet->setCellValue($cellCoordinate, $rowData[$value]);
                $col_count++;
            } else {
                foreach ($value as $responseKey) {
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count) . $row;
                    $sheet->setCellValue($cellCoordinate, $rowData['responses'][$responseKey]);
                    $col_count++;
                }
            }
        }
        $row++;
    }

    // Auto-fit column width
    foreach(range('A',$sheet->getHighestDataColumn()) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    // Apply styles to data cells
    $dataRange = 'A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count - 1) . ($row - 1);
    $sheet->getStyle($dataRange)->applyFromArray($dataStyle);

    $query = "SELECT title FROM events WHERE event_id = $event_id";
    $r_event = $conn->query($query);
    $title = $r_event->fetch_assoc()['title'];

    $fileName = $title. ' responses.xlsx';

    // Save the spreadsheet
    $writer = new Xlsx($spreadsheet);
    $writer->save($fileName);

    // Redirect to the generated spreadsheet or download it
    header("Location: $fileName");
}


function get_data($event_id)
{
    global  $conn;


    $q_f_id = "SELECT f_id FROM events WHERE event_id = $event_id";
    $res_f_id = $conn->query($q_f_id)->fetch_assoc();
    $f_id = $res_f_id['f_id'];

    $columns = ['Respondent', 'Name', 'Email'];

    $q_cols = "SELECT * FROM questionnaire WHERE f_id = $f_id";
    $r_cols = $conn->query($q_cols);

    while ($cols = $r_cols->fetch_assoc()) {
        $columns['responses'][] = $cols['question'];
    }




    $data_to_export = [
        "columns" => $columns
    ];

    $query = "SELECT * FROM response_form WHERE event_id = $event_id AND is_done = 'yes' ";

    $result = $conn->query($query);

    while ($response = $result->fetch_assoc()) {
        $respondent = $response['respondent'];
        $r_f_id = $response['r_f_id'];
        $response_id = $response['response_id'];
        $my_responses = [];

        switch ($respondent) {
            case 'teacher':
                $query = "SELECT * FROM scheduling_system.teacher WHERE id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['first_name'];
                $middlename = $user['middle_name'];
                $lastname = $user['last_name'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'student':
                $query = "SELECT * FROM sis.students WHERE std_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'parent':
                $query = "SELECT * FROM sis.parent WHERE id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $email = $user['email'];
                $fullname = $user['fullname'];

                break;
            case 'staff':
                $query = "SELECT * FROM users WHERE user_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'admin':
                $query = "SELECT * FROM users WHERE user_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;
            case 'guest':
                $query = "SELECT * FROM guest WHERE guest_id = $response_id";
                $r_user = $conn->query($query);
                $user = $r_user->fetch_assoc();
                $firstname = $user['firstname'];
                $middlename = $user['middlename'];
                $lastname = $user['lastname'];
                $email = $user['email'];
                $fullname = $firstname .  ($middlename ? ' ' . $middlename : '') . ' ' . $lastname;

                break;

            default:
                return "Invalid";
                break;
        }

        $query = "SELECT
                    q.q_id,
                    q.question,
                    (
                    SELECT
                        answer
                    FROM
                        response r
                    WHERE
                        r.q_id = q.q_id AND r.r_f_id = $r_f_id
                    ) AS answer,
                q.type,
                q.required
                FROM
                    (
                    SELECT
                        q.q_id,
                        q.question,
                        q.type,
                        q.required
                    FROM
                        questionnaire q
                    WHERE
                        q.f_id = $f_id
                    ORDER BY
                        q.created_at
                ) AS q";

        $r_responses = $conn->query($query);
        while ($responses = $r_responses->fetch_assoc()) {

            $answer = $responses['answer'];
            $type = $responses['type'];
            $question = $responses['question'];

            if ($type  == 'radio') {
                $query = "SELECT choice_name FROM choices WHERE c_id = $answer";
                $r_choice = $conn->query($query);
                $choice = $r_choice->fetch_assoc();
                if(isset($choice['choice_name'])){
                    $selected = $choice['choice_name'];
                }else{
                    $selected = '';
                }
                $my_responses[$question] = $selected;
            } else {
                $my_responses[$question] = $answer;
            }
        }

        $data_to_export['data'][] = [
            'Name' => $fullname,
            'Respondent' => ucfirst($respondent),
            'Email' => $email,
            'responses' => $my_responses,
        ];
    }

    return $data_to_export;
}
