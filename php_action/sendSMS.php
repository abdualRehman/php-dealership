<?php
require_once 'db/core.php';
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load(); // safeLoad for avoid exceptions

use Twilio\Rest\Client;



function validating($phone)
{
    $justNums = preg_replace("/[^0-9]/", '', $phone);
    if (strlen($justNums) == 11) $justNums = preg_replace("/^1/", '', $justNums);

    if (strlen($justNums) == 10) {
        return $justNums;
    } else {
        return "false";
    }
}
// echo validating("401-301-1942");



function send_sms($uid, $message)
{
    global $connect;
    $sql1 = "SELECT * FROM `users` WHERE id = '$uid'";
    $result1 = $connect->query($sql1);
    $row1 = $result1->fetch_assoc();
    $number = $row1['mobile'];
    if ($number != '' && validating($number) != 'false') {
        $comNumber = '+1' . validating($number);
        
        echo $comNumber;

        // if(sendSMS1($comNumber, $message)){
        //     echo $comNumber;
        // }
        // return sendSMS1($comNumber, $message);
    } else {
        return "Not Valid";
    }
}


// echo send_sms(76, '');











function sendSMS1($to, $messageBody)
{
    $sid = $_ENV['sid'];
    $token = $_ENV['token'];
    $client = new Client($sid, $token);
    $message = $client->messages->create(
        // '+14014197449', // client number
        // '+923036208276', // my number,
        $to,
        [
            'from' => '+12405129760',
            'body' => $messageBody,
        ]
    );
    if (!$message->sid) {
        return "false";
        throw new Exception("Message Faild to sent!");
    } else {
        return "true";
    }
}


//trigger exception in a "try" block
// try {
//     sendSMS('+1(401)-419-7449');
// }

// //catch exception
// catch (Exception $e) {
//     echo $e->getMessage();
// }
