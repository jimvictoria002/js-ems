<?php $access = $_SESSION['access']; ?>

<!doctype html>
<html class="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <script src="../node_modules/chart.js/dist/chart.umd.js"></script>
  <script src="../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
  <link rel="stylesheet" href="../node_modules/datatables.net-dt/css/dataTables.dataTables.min.css">
  <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>
  <script src="../node_modules/jquery-validation/dist/additional-methods.min.js"></script>
  <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.css">
  <script src="../src/addition.js"></script>
  <link rel="shortcut icon" href="../ems-logo.png" type="image/x-icon">
  <link href="../src/output.css" rel="stylesheet">
  <link href="../src/addition.css" rel="stylesheet">
  <title><?= $title ?></title>
</head>

<body class="flex items-start w-full bg-green-50 !overflow-hidden">