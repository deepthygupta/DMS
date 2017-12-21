<?php
session_start();
if ($_SESSION['login_user'] == '') {
    header("Location:login.php");
}

require_once 'methods.php';
require_once 'connect.php';

$error_msg = "";
$success_message = "";

if (isset($_POST['date_submit'])) {
    $report = getLeaveReport($_POST, $_SESSION['emp_id'], $_SESSION['user_type']);
    $report_count = count($report);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Qpro | Leave Report</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require_once 'header.php'; ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Leave Report
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"> Leave Report</li>
                    </ol>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-info">
                                <?php if (isset($error_msg) && $error_msg != "") { ?>
                                    <div class="alert alert-warning alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?php echo $error_msg; ?>
                                    </div>
                                    <?php
                                }
                                if (isset($success_message) && $success_message != "") {
                                    ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?php echo $success_message; ?>
                                    </div>
                                <?php } ?>    
                                <form class="form-horizontal" method="post" name="date_filter">
                                    <div class="box-body">                                       
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" id="label_div">From Date</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="from_date" id="datepicker" class="form-control" required="" autocomplete="off" tabindex="1">  
                                            </div>                                           
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label">To Date</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="to_date" id="datepicker1" class="form-control" autocomplete="off" tabindex="2" required="">
                                            </div>
                                        </div>               
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" name="date_submit" id="date_submit" class="btn btn-info pull-left" tabindex="3">Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <div id="date_filter_div">
                    <section class="content-header">
                        <h1>
                            Leave Report between  <?php echo $_POST['from_date']; ?> and <?php echo $_POST['to_date']; ?> 
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
                                                if ($report_count) {
                                                    for ($i = 0; $i < $report_count; $i++) {
                                                        ?>
                                                        <tr>                                                   
                                                            <td><?php echo $report[$i]['reason']; ?></td>
                                                            <td><?php echo $report[$i]['category']; ?></td>
                                                            <td><?php echo $report[$i]['leave_date']; ?></td>
                                                            <td><?php echo $report[$i]['status']; ?></td>        
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
        </div>

        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <script src="../dist/js/adminlte.min.js"></script>
        <script src="../dist/js/demo.js"></script>
        <script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('#datepicker1').datepicker({
                    autoclose: true
                });
                $("#date_submit").click(function () {
                    $("#date_filter_div").show();
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
