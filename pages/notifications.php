<?php
session_start();
if ($_SESSION['login_user'] == '') {
    header("Location:login.php");
}

require_once 'methods.php';
require_once 'connect.php';

$error_msg = "";
$success_message = "";

$notifications = getAllNotifications();
$count = count($notifications);

if (isset($_POST['submit'])) {
    $success = addNotification($_POST);
    if ($success) {
        $success_message = "Notification added successfully";
    } else {
        $error_msg = "Error on adding notification";
    }
}
if (isset($_POST['not_id'])) {
    $delete = deleteNotification($_POST['not_id']);
    if ($delete) {
        echo 1;
        return;
    } else {
        echo 0;
        return;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Qpro | Notifications</title>
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
                        Notifications
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Notifications</li>
                    </ol>
                </section>
                <section class="content">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Notifications</h3>
                                </div> 
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
                                <form class="form-horizontal" method="post" name="add_notification">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label">Notification</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="notification" tabindex="1" autocomplete="off" required="">
                                            </div>
                                        </div>                                                                                     
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" name="submit" class="btn btn-info pull-left" tabindex="3">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">          
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Notification</th>
                                                <th>Status</th>
                                                <th>Added Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < $count; $i++) { ?>
                                                <tr>
                                                    <td><?php echo $i + 1; ?></td>
                                                    <td><?php echo $notifications[$i]['notification']; ?></td>
                                                    <td><?php echo $notifications[$i]['status']; ?></td>
                                                    <td><?php echo $notifications[$i]['added_date']; ?></td>
                                                    <td><button type="button" class="btn btn-danger" onclick="deleteNotification(<?php echo $notifications[$i]['id']; ?>)">Delete</button></td>
                                                </tr>
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
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <script src="../dist/js/adminlte.min.js"></script>
        <script src="../dist/js/demo.js"></script>
        <script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>   
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script>
            function deleteNotification(value) {
                if (value) {
                    $.ajax({
                        url: 'notifications.php',
                        cache: false,
                        data: 'not_id=' + value,
                        type: 'POST',
                        success: function (response) {
                            window.location.href = "notifications.php";
                        }
                    });
                }
            }
        </script>
    </body>
</html>
