<?php
require_once 'db/core.php';
require_once 'sendEmail.php';
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



function send_sms($uid, $message, $url = null)
{
    // return "False";
    global $connect;
    $sql1 = "SELECT * FROM `users` WHERE id = '$uid'";
    $result1 = $connect->query($sql1);
    $row1 = $result1->fetch_assoc();
    $htmlContent = '';
    if ($row1) {
        $number = $row1['mobile'];
        $name = $row1['username'];
        $email = $row1['email'];

        $siteurl = $url ? $url : $_SESSION['siteurl'];
        $notificationHTMLContent = sendEmailToNotify($name, $message, $siteurl);

        if (smtp_mailer($email, 'Notification Alert - One Dealers', $notificationHTMLContent)) {
            return "true";
        } else {
            return "false";
        }
    }


    // code for sending SMS - working
    // if ($number != '' && validating($number) != 'false') {
    //     $number = '+1' . validating($number);
    //     // return sendSMS1($number, $message);
    //     // return sendSMS1('+923036208276', $message);
    //     // return $number;
    //     try {
    //         return sendSMS1($number, $message);
    //         // return sendSMS1('+923036208276', $message);
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // } else {
    //     return "Not Valid";
    // }
}


// echo send_sms(60, 'New Registration Problem  for {$customerName} – {$problem}') . '<br />';
// try {
//     echo send_sms(91, '');
//     //code...
// } catch (Exception $e) {
//     // throw $th;
//     echo $e->getMessage();
// }

function sendEmailToNotify($name, $message, $url)
{
    $htmlContent = "";
    $htmlContent .= ' 
         <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Notification Alert</title>
            </head>

            <body style="margin: 0; padding: 40px; background-color: #bfe5ff; text-align: center; font-family: Arial, sans-serif;">
                <div style="max-width: 600px; background-color: #fff; margin: 60px auto; padding: 40px 30px; border-radius: 8px;">
                    <h1 style="font-size: 28px; color: #000; margin-bottom: 20px; font-weight: bold; text-align: left;">Notification Alert</h1>
                    <hr style="border: 0; border-bottom: 1px solid #E1E2E6; margin-bottom: 30px;">
                    <p style="font-size: 16px; color: #9095A2; text-align: left; margin-bottom: 20px;">Hi, ' . $name . '!<br>
                        ' . $message . '</p>
                    <p style="font-size: 16px; color: #9095A2; text-align: left; margin-bottom: 30px;">Please tap the button below to login.</p>
                    <a href="' . $url . '" style="display: inline-block; background-color: #34D399; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 24px; font-weight: bold; text-transform: uppercase;">Click to Login</a>
                </div>
            </body>

        </html>';

    return $htmlContent;
}





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
