<?php 	

$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "carshop";



// $localhost = "server60";
// $username = "binfarooq_laughing_albattani";
// $password = "ZD5dc}KVTxtD";
// $dbname = "binfarooq_laughing_albattani";


// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

?>