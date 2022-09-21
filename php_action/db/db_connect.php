<?php 	

$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "carshop";


// $localhost = "127.0.0.1";
// $username = "u674299917_onedealeruser";
// $password = "L#[k5?x7y";
// $dbname = "u674299917_onedealersys";



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