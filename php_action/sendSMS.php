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
    // return "False";
    global $connect;
    $sql1 = "SELECT * FROM `users` WHERE id = '$uid'";
    $result1 = $connect->query($sql1);
    $row1 = $result1->fetch_assoc();
    $number = $row1['mobile'];
    if ($number != '' && validating($number) != 'false') {
        $number = '+1' . validating($number);
        // return sendSMS1($number, $message);
        // return sendSMS1('+923036208276', $message);
        // return $number;
        try {
            return sendSMS1($number, $message);
            // return sendSMS1('+923036208276', $message);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        return "Not Valid";
    }
}


// echo send_sms(97, 'Trigger exception in a "try" block Demo Message'). '<br />';
// try {
//     echo send_sms(91, '');
//     //code...
// } catch (Exception $e) {
//     // throw $th;
//     echo $e->getMessage();
// }











function sendSMS1($to, $messageBody)
{
    // return "true";
    $sid = $_ENV['sid'];
    $token = $_ENV['token'];
    $client = new Client($sid, $token);
    $message = $client->messages->create(
        // '+14014197449', // client number
        // '+923036208276', // my number,
        $to,
        [
            // 'from' => '+12405129760',
            'from' => '+18883361334', // paid toll free #
            'body' => $messageBody,
        ]
    );
    if (!$message->sid) {
        // throw new Exception("Message Faild to sent!");
        return "false";
    } else {
        return "true";
    }
}

// sendSMS1('+14014197449' , 'Trigger exception in a "try" block Demo Message');
// sendSMS1('+15086176936' , 'Trigger exception in a "try" block Demo Message');

// echo sendSMS1('+1617-335-5966' , 'Trigger exception in a "try" block Demo Message');
// echo sendSMS1('+1323-675-5905' , 'Trigger exception in a "try" block Demo Message');


//trigger exception in a "try" block
// try {
//     sendSMS('+1(401)-419-7449');
// }

// //catch exception
// catch (Exception $e) {
//     echo $e->getMessage();
// }
