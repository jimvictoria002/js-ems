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
    $sheet = $spreadsheet->getActiveSheet();

    $col_count = 1;
    foreach ($columns as $index => $columnName) {
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

    $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count - 1) . '1';
    $sheet->getStyle($headerRange)->applyFromArray($headerStyle);

    $row = 2;
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

    foreach (range('A', $sheet->getHighestDataColumn()) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $dataRange = 'A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_count - 1) . ($row - 1);
    $sheet->getStyle($dataRange)->applyFromArray($dataStyle);

   
    $query = "SELECT e.title  FROM events e INNER JOIN forms f ON e.f_id = f.f_id WHERE e.event_id = $event_id";
    $r_event = $conn->query($query);
    $title = $r_event->fetch_assoc()['title'];
    $title = substr($title, 0, 100); 

    $title = preg_replace('/[^A-Za-z0-9\-]/', '_', $title); 

    $fileName = addslashes($title) . '_responses.xlsx';
    $writer = new Xlsx($spreadsheet);
    $writer->save($fileName);

    header("Location: $fileName");
}


function get_data($event_id)
{
    global  $conn;


    $q_f_id = "SELECT f_id FROM events WHERE event_id = $event_id";
    $res_f_id = $conn->query($q_f_id)->fetch_assoc();
    $f_id = $res_f_id['f_id'];

    $columns = ['Respondent', 'Firstname', 'Middlename', 'Lastname', 'Email'];

    $q_cols = "SELECT * FROM questionnaire WHERE f_id = $f_id";
    $r_cols = $conn->query($q_cols);

    while ($cols = $r_cols->fetch_assoc()) {
        $columns['responses'][] = $cols['question'];
    }




    $data_to_export = [
        "columns" => $columns
    ];

    $query = "SELECT * FROM response_form rf INNER JOIN respondent_data rd ON rf.r_f_id = rd.r_f_id WHERE event_id = $event_id and f_id = $f_id AND is_done = 'yes' ";

    $result = $conn->query($query);

    while ($response = $result->fetch_assoc()) {
        $respondent = $response['respondent'];
        $r_f_id = $response['r_f_id'];
        $response_id = $response['response_id'];
        $firstname = $response['firstname'];
        $middlename = $response['middlename'];
        $lastname = $response['lastname'];
        $email = $response['email'];
        $my_responses = [];

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
                if (isset($choice['choice_name'])) {
                    $selected = $choice['choice_name'];
                } else {
                    $selected = '';
                }
                $my_responses[$question] = $selected;
            } else {
                $my_responses[$question] = $answer;
            }
        }

        $data_to_export['data'][] = [
            'Firstname' => $firstname,
            'Middlename' => $middlename,
            'Lastname' => $lastname,
            'Respondent' => ucfirst($respondent),
            'Email' => $email,
            'responses' => $my_responses,
        ];
    }

    return $data_to_export;
}
