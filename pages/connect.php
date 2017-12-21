<?php

$conn = mysqli_connect("localhost", "root", "", "employee");
if($conn){
    $GLOBALS['conn'] = $conn;
}else{
 echo "================Invalid DB Connection================";
}