<?php
$con = mysqli_connect("localhost", "root", "", "crm");

if(!$con) { 
    die("Connection Error: " . mysqli_connect_error()); 
}
?>