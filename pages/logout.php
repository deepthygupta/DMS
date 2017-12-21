<?php
session_start();
if (isset($_SESSION['login_user'])) {
    unset($_SESSION);    
}
header("Location:login.php");