<?php 
session_start();
if ($_SESSION['login_user'] == '') {
    header("Location:login.php");
}
require_once 'methods.php';
require_once 'connect.php';

$user_data = getEmployeeDetails();
$count = count($user_data);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>QPro | Employee Details</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">

        <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
        <!-- Google Font -->
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php require_once 'header.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Employee Details
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Employee Details</a></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">          
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>User Name</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>Photo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for($i=0; $i<$count; $i++){ ?>
                                            <tr>
                                                <td><?php echo $user_data[$i]['emp_id']; ?></td>
                                                <td><a href="profile.php?emp_id=<?php echo $user_data[$i]['emp_id']; ?>"><?php echo $user_data[$i]['username']; ?></a></td>
                                                <td><?php echo $user_data[$i]['name']; ?></td>
                                                <td><?php echo $user_data[$i]['designation']; ?></td>
                                                <td><?php echo $user_data[$i]['email']; ?></td>
                                                <td><?php echo $user_data[$i]['contact']; ?></td>
                                                <td><img src="../<?php echo $user_data[$i]['photo']; ?>" style="width: 150px;height: 100px;"/></td>
                                                
                                            </tr>
                                            <?php } ?>
                                        </tbody>                                            
                                        <!--<tfoot>
                                            <tr>
                                                <th>Rendering engine</th>
                                                <th>Browser</th>
                                                <th>Platform(s)</th>
                                                <th>Engine version</th>
                                                <th>CSS grade</th>
                                            </tr>
                                        </tfoot>-->
                                    </table>
                                </div>                              
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery 3 -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- SlimScroll -->
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>
        <!-- page script -->
        <script>
            $(function () {
                $('#example1').DataTable()
                $('#example2').DataTable({
                    'paging': true,
                    'lengthChange': false,
                    'searching': false,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false
                })
            })
        </script>
    </body>
</html>
