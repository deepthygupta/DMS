<?php
session_start();
if ($_SESSION['login_user'] == '') {
    header("Location:login.php");
}

require_once 'methods.php';
require_once 'connect.php';

$error_msg = "";
$success_message = "";

if (isset($_GET['emp_id'])) {
    $user_data = getUserCompleteProfile($_GET['emp_id']);
} else {
    $user_data = getUserCompleteProfile($_SESSION['emp_id']);
}

$experience = getExperienceFromJoinDate($user_data['join_date']);


if ($_POST) {

    if ($_POST['address'] == "" || $_POST['first_name'] == "" || $_POST['last_name'] == "" || $_POST['email'] == "" || $_POST['contact'] == "" || $_POST['designation'] == "") {
        $error_msg = "All fields are required. Please try again. ";
        $success_message = "";
    } else {

        if ($_FILES) {
            $total = count($_FILES['photo']['name']);
            $target = array("C://xampp/htdocs/DMS/images/profile/", "C://xampp/htdocs/DMS/resume/", "C://xampp/htdocs/DMS/documents/");
            for ($i = 0; $i < $total; $i++) {

                $tmpFilePath = $_FILES['photo']['tmp_name'][$i];
                if ($tmpFilePath != "") {
                    $newFilePath = $target[$i] . $_FILES['photo']['name'][$i];
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $update = updateUploadedDocuments($_FILES, $_POST['emp_id']);
                    }
                }
            }
        }
        $result = updateUserProfile($_POST);
        if ($result) {
            $success_message = "Profile Updated SUccessfully";
            header("Location: profile.php");
        } else {
            $error_msg = "Error on updating profile ";
            header("Location: profile.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> QPro | Employee Profile</title>
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

        <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

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
                        User Profile
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>        
                        <li class="active">User profile</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <img class="profile-user-img img-responsive img-circle" src="../<?php echo $user_data['photo']; ?>">

                                    <h3 class="profile-username text-center"><?php echo $user_data['first_name'] . " " . $user_data['last_name']; ?></h3>

                                    <p class="text-muted text-center"><?php echo $user_data['designation']; ?></p>

                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Projects Worked</b> <a class="pull-right">50</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Text1</b> <a class="pull-right">Data1</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Text2</b> <a class="pull-right">Data2</a>
                                        </li>
                                    </ul>

                                    <a href="#" class="btn btn-primary btn-block"><b>Button1</b></a>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->                           
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#settings" data-toggle="tab">Update</a></li>
                                    <li><a href="#activity" data-toggle="tab">Uploaded Documents</a></li> 

                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane"  id="settings">
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
                                        <form class="form-horizontal" name="update_form" enctype="multipart/form-data" method="post">
                                            <div class="form-group">
                                                <label  class="col-sm-2 control-label">First Name</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="first_name" placeholder="First Name" tabindex="1" autocomplete="off" value="<?php echo $user_data['first_name']; ?>">
                                                    <input type="hidden" name="username" value="<?php echo $user_details['username']; ?>">
                                                    <input type="hidden" name="emp_id" value="<?php echo $user_details['emp_id']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-2 control-label">Last Name</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" tabindex="2" autocomplete="off" value="<?php echo $user_data['last_name']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-2 control-label">Designation</label>

                                                <div class="col-sm-10">
                                                    <select class="form-control" name="designation" tabindex="3">
                                                        <?php if ($user_data['designation'] != '') { ?>
                                                            <option value="<?php echo $user_data['designation']; ?>"><?php echo $user_data['designation']; ?></option>
                                                        <?php } else { ?>
                                                            <option value="">Select Designation</option>
                                                        <?php } ?>
                                                        <option value="Developer">Developer</option>
                                                        <option value="UI Developer">UI Developer</option>
                                                        <option value="Project Manager">Project Manager</option>
                                                        <option value="Graphics Designer">Graphics Designer</option>
                                                        <option value="Marketing Executive">Marketing Executive</option>
                                                        <option value="General Manager">General Manager</option>
                                                        <?php if ($user_type == 'admin') { ?>
                                                            <option value="CEO">CEO</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control" name="email" placeholder="Email" tabindex="4" autocomplete="off" value="<?php echo $user_data['email']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail" class="col-sm-2 control-label">Contact Number</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="contact" placeholder="Contact Number" tabindex="5" autocomplete="off" value="<?php echo $user_data['contact']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Address</label>

                                                <div class="col-sm-10">
                                                    <textarea class="form-control" name="address" tabindex="6" placeholder="Enter Address Here...">
                                                        <?php echo $user_data['address']; ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-2 control-label">Profile Image</label>

                                                <div class="col-sm-10">
                                                    <input type="file" name="photo[]" class="form-control" tabindex="7">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-2 control-label">Resume</label>

                                                <div class="col-sm-10">
                                                    <input type="file" name="photo[]" class="form-control" tabindex="8" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-sm-2 control-label">Other Documents</label>

                                                <div class="col-sm-10">
                                                    <input type="file" name="photo[]" class="form-control" tabindex="9">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Join Date</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="join_date" readonly="true" tabindex="10" value="<?php echo $user_data['join_date']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Experience</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="experience" readonly="true" tabindex="11" value="<?php echo $experience; ?>">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-danger" tabindex="12">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="activity">  
                                        <div class="post">        
                                            <div class="row margin-bottom">
                                                <div class="col-sm-6">
                                                     <label  class="col-sm-2 control-label">Resume</label>
                                                    <?php if ($user_data['resume'] != "resume/") { ?>
                                                        <img class="img-responsive" src="../<?php echo $user_data['resume']; ?>" alt="Photo">
                                                    <?php } else { ?>
                                                        <img class="img-responsive" src="../resume/no_doc.jpg" alt="Photo">
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-6">
                                                     <label  class="col-sm-2 control-label">Documents</label>
                                                    <?php if ($user_data['documents'] != "documents/") { ?>
                                                        <img class="img-responsive" src="../<?php echo $user_data['documents']; ?>" alt="Photo">
                                                    <?php } else { ?>
                                                        <img class="img-responsive" src="../documents/no_doc.jpg" alt="Photo">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>        

            <!-- jQuery 3 -->
            <script src="../bower_components/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap 3.3.7 -->
            <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
            <!-- FastClick -->
            <script src="../bower_components/fastclick/lib/fastclick.js"></script>
            <!-- AdminLTE App -->
            <script src="../dist/js/adminlte.min.js"></script>
            <!-- AdminLTE for demo purposes -->
            <script src="../dist/js/demo.js"></script>
    </body>
</html>
