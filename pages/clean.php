<?php
session_start();
require 'connect.php';
$query1 = "DELETE FROM `employees_list` WHERE user_type != 'admin'";
$query2 = "DELETE FROM `employee_profile` WHERE user_type != 'admin'";
$result1 = mysqli_query($GLOBALS['conn'], $query1);
$result2 = mysqli_query($GLOBALS['conn'], $query2);
if ($result1 && $result2) {
    session_destroy();
    header("Location:login.php");
} else {
    echo "Cleanup Action Failed";
}


