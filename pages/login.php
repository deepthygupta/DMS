<?php
session_start();

if ($_POST) {
    require_once 'methods.php';
    require_once 'connect.php';

    $error_msg = "";
    if ($_POST['username'] == "") {
        $error_msg = "You must enter username";
    } else if ($_POST['password'] == "") {
        $error_msg = "You must enter password";
    } else {
        $login_id = checkUserLoginDetails($_POST);
        if ($login_id) {
            $_SESSION['login_user'] = $_POST['username'];
            $_SESSION['emp_id'] = $login_id;
            header("Location:index.php");
        } else {
            $error_msg = "Invalid Login Details";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>QPro | Log in</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="../plugins/iCheck/square/blue.css">

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><img src="../images/logo.png"/></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg"><h3>Login</h3></p>

                <?php if (isset($error_msg) && $error_msg !="") { ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <?php echo $error_msg; ?>
                    </div>
                <?php } ?>              

                <form name="login_form" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" name="username" class="form-control" placeholder="User Name" required="" autocomplete="off" tabindex="1">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder="Password" required="" autocomplete="off" tabindex="2">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">      
                        <div class="col-xs-4">
                            <button type="submit" name="login_submit" value="submit" class="btn btn-primary btn-block btn-flat" tabindex="3">Sign In</button>
                        </div>
                    </div>
                </form>

                <a href="password.php">I forgot my password</a><br>
                <a href="register.php" class="text-center">Register new employee</a>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

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
                $("button").click(function () {
                    $("#msgspan").fadeOut("slow");
                });
            });
        </script>
    </body>
</html>
