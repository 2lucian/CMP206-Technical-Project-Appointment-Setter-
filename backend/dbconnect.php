<?php
//Step 1 Declare Variables
$servername = "localhost"; 
$username = "root"; 
$password = ""; //Usually Blank
$dbname = "appointment_setter"; //Enter Database Name

//Step 2 Create Connection
$conn = mysqli_connect($servername, $username, $password, $dbname); 

//Step 3 Test Connection 
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
?>