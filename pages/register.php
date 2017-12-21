<?php
if ($_POST) {
    require_once 'methods.php';
    require_once 'connect.php';

    $error_msg = "";
    $success_message = "";

    $max_date = date("Y-m-d");

    if ($_POST['username'] == "" || $_POST['first_name'] == "" || $_POST['last_name'] == "" || $_POST['designation'] == "" || $_POST['password'] == "" || $_POST['confirm'] == "") {
        $error_msg = "All fields are required. Please try again. ";
    } else {
        if ($_POST['password'] != $_POST['confirm']) {
            $error_msg = "Password Mismatch";
        }
        $username_exist = checkUserNameExist($_POST['username']);
        if ($username_exist) {
            $error_msg = "Username already exist!!";
        } else {
            $result = confirmUserRegister($_POST);
            if ($result) {
                $success_message = "User registration successful";
            } else {
                $error_msg = "Error on user registration";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> QPro | Registration</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">      
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="../plugins/iCheck/square/blue.css">
        <!-- Google Font -->
        <link rel="stylesheet" href="../bower_components/bootstrap-daterangepicker/daterangepicker.css">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="register-logo">
                <a href="#"><img src="../images/logo.png"/></a>
            </div>

            <div class="register-box-body">
                <p class="login-box-msg"><h3>Register New Employee</h3></p>
                <?php if (isset($error_msg) && $error_msg != "") { ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?php echo $error_msg; ?>
                    </div>
                <?php } ?>

                <?php if (isset($success_message) && $success_message != "") { ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $success_message; ?>
                    </div>                  
                <?php } ?>

                <form name="register_form" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" name="username" class="form-control" placeholder="Username*" required="" autocomplete="off" tabindex="1">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="first_name" class="form-control" placeholder="First name*" required="" autocomplete="off" tabindex="2">
                        <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="last_name" class="form-control" placeholder="Last name*" required="" autocomplete="off" tabindex="3">
                        <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="designation" class="form-control" placeholder="Designation*" required="" autocomplete="off" tabindex="4">
                        <span class="glyphicon glyphicon-education form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback"> 
                        <input type="text" name="join_date" id="datepicker" class="form-control" placeholder="Join Date*" required="" autocomplete="off" tabindex="4">  
                        <span class="glyphicon glyphicon-education form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder="Password*" required="" autocomplete="off" tabindex="5">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="confirm" class="form-control" placeholder="Confirm password*" required="" autocomplete="off" tabindex="6">
                        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">         
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block btn-flat" tabindex="7">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <a href="login.php" class="text-center">I already have a membership</a>
            </div>
            <!-- /.form-box -->
        </div>
        <!-- /.register-box -->

        <!-- jQuery 3 -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="../plugins/iCheck/icheck.min.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            });
            $(document).ready(function () {
//Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                })
            });
        </script>

        <!-- jQuery 3 -->
        <script src="../bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Select2 -->
        <script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- InputMask -->
        <script src="../plugins/input-mask/jquery.inputmask.js"></script>
        <script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
        <!-- date-range-picker -->
        <script src="../bower_components/moment/min/moment.min.js"></script>
        <script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap datepicker -->
        <script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- bootstrap color picker -->
        <script src="../bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
        <!-- bootstrap time picker -->
        <script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <!-- SlimScroll -->
        <script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <!-- iCheck 1.0.1 -->
        <script src="../plugins/iCheck/icheck.min.js"></script>
        <!-- FastClick -->
        <script src="../bower_components/fastclick/lib/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>
    </body>
</html>
