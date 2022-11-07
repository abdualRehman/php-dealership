<?php 	

// // Required if your environment does not handle autoloading
require_once realpath(__DIR__ . '/../../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dotenv->load(); // safeLoad for avoid exceptions



$localhost = $_ENV['localhost'];
$username = $_ENV['username'];
$password = $_ENV['password'];
$dbname = $_ENV['dbname'];


// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

?>