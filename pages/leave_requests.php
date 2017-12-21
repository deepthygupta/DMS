<?php
session_start();
if ($_SESSION['login_user'] == '') {
    header("Location:login.php");
}
require_once 'methods.php';
require_once 'connect.php';

$leave = getLeaveRequestDetails();
$count = count($leave);


if (isset($_POST['entry_id']) && isset($_POST['action'])) {
    $update_result = updateRequestStatus($_POST['entry_id'], $_POST['action']);
    if ($update_result) {
        echo "success";
        return;
    } else {
        echo "failed";
        return;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>QPro | Leave Requests</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require_once 'header.php'; ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Pending Leave Requests
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Pending Leave Requests</a></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">          
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Employee Name</th>
                                                <th>Reason for Leave</th>
                                                <th>Category</th>
                                                <th>Leave Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($i = 0; $i < $count; $i++) { ?>
                                                <tr>
                                                    <td><?php echo $leave[$i]['username']; ?></td>
                                                    <td><?php echo $leave[$i]['full_name']; ?></td>
                                                    <td><?php echo $leave[$i]['reason']; ?></td>
                                                    <td><?php echo $leave[$i]['category']; ?></td>
                                                    <td><?php echo $leave[$i]['leave_date']; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger" onclick="processRequest(<?php echo $leave[$i]['id']; ?>, 'reject')">Deny</button>
                                                        <button type="button" class="btn btn-primary" onclick="processRequest(<?php echo $leave[$i]['id']; ?>, 'approve')">Approve</button>
                                                    </td>                                                
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
        <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <script src="../dist/js/adminlte.min.js"></script>
        <script src="../dist/js/demo.js"></script>
        <script>
                    function processRequest(value, action) {
                        if (value && action) {
                            $.ajax({
                                url: 'leave_requests.php',
                                cache: false,
                                data: 'entry_id=' + value + '&action=' + action,
                                type: 'POST',
                                success: function (response) {
                                    window.location.href = "leave_requests.php";
                                }
                            });
                        }
                    }
        </script>
    </body>
</html>
