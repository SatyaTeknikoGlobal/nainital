<?php
/*
| -----------------------------------------------------
| PRODUCT NAME: 	Tekniko School
| -----------------------------------------------------
| AUTHOR:			Kshitij Kumar Singh
| -----------------------------------------------------
| EMAIL:			kshitij.singh@teknikoglobal.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY TEKNIKOGLOBAL
| -----------------------------------------------------
| WEBSITE:			https://www.teknikoglobal.com
| -----------------------------------------------------
*/
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>School | <?=$title;?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url("assets")?>/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="<?=base_url("assets")?>/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!-- Calendar CSS -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/calendar/dist/fullcalendar.css" rel="stylesheet" />
    <!-- animation CSS -->
    <link href="<?=base_url("assets")?>/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url("assets")?>/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?=base_url("assets")?>/css/colors/default.css" id="theme" rel="stylesheet">
    <link href="<?=base_url("assets")?>/plugins/bower_components/datatables/media/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url("assets")?>/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Color picker plugins css -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/jquery-asColorPicker-master/dist/css/asColorPicker.css" rel="stylesheet">
    <!-- Date picker plugins css -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="<?=base_url("assets")?>/plugins/bower_components/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?=base_url("assets")?>/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?=base_url("assets")?>/plugins/bower_components/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?=base_url("assets")?>/plugins/bower_components/custom-select/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?=base_url("assets")?>/plugins/bower_components/jquery/dist/jquery.min.js"></script>

    <style>
        .fc-present {
            background: #bf8c8c !important;
        }
        .fc-absent {
            background: red !important;
        }
        .fc-holiday {
            background: yellow !important;
        }
        .non_clickable{
            pointer-events: stroke;
            cursor: default;
        }
        .datepicker.datepicker-dropdown{
            z-index: 1000!important;
        }
    </style>

</head>
<body class="fix-header">
<?php include "components/page_header.php";?>
<?php include $subview.".php";?>
<?php include "components/page_footer.php";?>
</body>
</html>
