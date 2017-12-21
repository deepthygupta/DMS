<?php
session_start();
if ($_SESSION['login_user'] == '') {
    header("Location:login.php");
}

require_once 'methods.php';
require_once 'connect.php';

$error_msg = "";
$success_message = "";

if ($_POST) {
    $check_validity = checkLeaveValidity($_POST);
    $pending_request = checkPendingRequest($_POST);
    if ($pending_request) {
        $error_msg = "Your leave request is already pending";
    } else {
        if ($check_validity) {
            $success = submitLeaveRequest($_POST);
            if ($success) {
                $success_message = "Leave submitted successfully";
            } else {
                $error_msg = "Error on submitting leave request";
            }
        } else {
            $error_msg = "You can't apply for leave on this date";
        }
    }
}
$leave_details = getLeaveRequestDetails($_SESSION['emp_id']);
$leave_count = count($leave_details);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Qpro | Request Leave</title>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">

        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">

        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">

        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">

        <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

        <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require_once 'header.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Request Leave
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Request Leave</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-info">
                                <?php if (isset($error_msg) && $error_msg != "") { ?>
                                    <div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?php echo $error_msg; ?>
                                    </div>
                                <?php } ?>    
                                <?php if (isset($success_message) && $success_message != "") { ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?php echo $success_message; ?>
                                    </div>
                                <?php } ?>    
                                <form class="form-horizontal" method="post" name="request_form">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label">Reason</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="reason" tabindex="1" autocomplete="off" required="">
                                                <input type="hidden" name="emp_id" value="<?php echo $_SESSION['emp_id']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label">Category</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="category" tabindex="2">
                                                    <option value="Sick">Sick</option>
                                                    <option value="Casual">Casual</option>
                                                    <option value="Extra">Extra</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label">Type</label>
                                            <div class="col-sm-10">
                                                <input type="radio" id="single" name="day_count"  value="single" tabindex="3">Single
                                                <input type="radio" id="multiple" name="day_count"  value="multiple" tabindex="4">Multiple
                                            </div>
                                        </div>
                                        <div class="form-group" id="datepicker_div">
                                            <label  class="col-sm-2 control-label" id="label_div">Starting From</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="start_date" id="datepicker" class="form-control" required="" autocomplete="off" tabindex="4">  
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="radio" id="from_full" name="from_type" checked="" value="F" tabindex="5">  Full Day
                                                <input type="radio" id="from_half" name="from_type" value="H" tabindex="6">  Half Day
                                            </div>
                                        </div>
                                        <div class="form-group" id="datepicker1_div">
                                            <label  class="col-sm-2 control-label">Ending On</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="end_date" id="datepicker1" class="form-control" autocomplete="off" tabindex="7">  
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="radio" id="to_full" name="to_type" checked="" value="F" tabindex="8">  Full Day
                                                <input type="radio" id="to_half" name="to_type" value="H" tabindex="9">  Half Day
                                            </div>
                                        </div>               
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info pull-left" tabindex="10">Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="content-header">
                    <h1>
                        Requested Leave Details
                    </h1>                    
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">          
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>                                               
                                                <th>Reason for Leave</th>
                                                <th>Category</th>
                                                <th>Leave Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($leave_count) {
                                                for ($i = 0; $i < $leave_count; $i++) {
                                                    ?>
                                                    <tr>                                                   
                                                        <td><?php echo $leave_details[$i]['reason']; ?></td>
                                                        <td><?php echo $leave_details[$i]['category']; ?></td>
                                                        <td><?php echo $leave_details[$i]['leave_date']; ?></td>
                                                        <td><?php echo $leave_details[$i]['status']; ?></td>        
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                    <tr><td colspan="3" style="text-align: center;"><h3>No Requests Found</h3><td></tr>    
                                            <?php } ?>
                                        </tbody>          
                                    </table>
                                </div>                              
                            </div>
                        </div>
                    </div>
                </section>
            </div>           
        </div>

        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- FastClick -->
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>

        <script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap datepicker -->
        <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- bootstrap color picker -->
        <script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <!-- bootstrap time picker -->
        <script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <!-- SlimScroll -->
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#datepicker_div').hide();
                $('#datepicker1_div').hide();

                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('#datepicker1').datepicker({
                    autoclose: true
                });

                $("#single").click(function () {
                    $('#datepicker_div').show();
                    $('#datepicker1_div').hide();
                    $('#label_div').text("Select Leave Date");
                });
                $("#multiple").click(function () {
                    $('#datepicker_div').show();
                    $('#datepicker1_div').show();
                    $("#datepicker1").attr("required", "true");
                    $('#label_div').text("Leave Starting From");
                });
            });
        </script>  
        <style>
            .datepicker table tr td:first-child {
                background: red !important;
                border-color: red !important;
                color: white !important;
            }
        </style>
    </body>
</html>
