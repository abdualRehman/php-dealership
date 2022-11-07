<?php
// error_reporting(E_ERROR | E_PARSE);
// require __DIR__."/../vendor/autoload.php";
include(__DIR__."/../smtp/PHPMailerAutoload.php");
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load(); // safeLoad for avoid exceptions

if (!isset($_SESSION)) session_start();



// echo smtp_mailer('rehmanali00708@gmail.com','Testing','Testing this Email...');
function smtp_mailer($to, $subject, $msg)
{
    $mail = new PHPMailer();
    // $mail->SMTPDebug=3;
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.titan.email";
    $mail->Port = "465";
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = $_ENV['from_email'];
    // $mail->Username = "support@1dealersystem.com";
    // $mail->Password = 'ioxWTr4zJc';
    $mail->Password = $_ENV['from_password'];
    $mail->SetFrom($_ENV['from_email']);
    // $mail->SetFrom("support@1dealersystem.com");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if (!$mail->Send()) {
        // echo $mail->ErrorInfo;
        return false;
    } else {
        // echo 'Sent';
        return true;
    }
}







function sendEmail($email, $name, $type, $generatePassword = '')
{
    $to = $email;
    $from = "roger@1dealersystem.com";
    $subject = '';
    $htmlContent = '';

    if ($type == 'register') {

        $currentTime = date("Y-m-d h:i:s"); //current time
        $expire_in = strtotime('+24 hours', strtotime($currentTime));
        $encodedtime = urlencode(base64_encode($expire_in));

        $encodedemail = urlencode(base64_encode($email));
        $encodedtype = urlencode(base64_encode("reset"));

        $siteurl = $_SESSION['siteurl'];
        $url = $siteurl . '/resetPassword.php?id=' . $encodedemail . '&actype=' . $encodedtype . '&exp=' . $encodedtime;

        $subject = "Thank you for your interest in 1 Dealer System";
        $htmlContent = ' 
            <html> 
                <body> 
                    <p> Hello <b> ' . $name . ' </b>,</p>
                    <h3>Thank you for joining 1 Dealer System.</h3>


                    <p> We’d like to confirm that your account was created successfully. Your temporary account password is mentioned below: <br />
                    <strong>' . $generatePassword . '</strong>
                    </p>
                    <h4>Click the link below to change your password: </h4>
                    <a href="' . $url . '" target="_blank" >' . $url . '</a>
                    <br />
                    <br />

                    <p>If you experience any issues logging into your account, reach out to us at: <strong> ' . $from . ' </strong></p>
                    <p>This Link is valid only for: <strong>24 hours</strong></p>
                    
                    
                    <p> <b> Best, <br/>
                    Roger Dalomba <br/>
                    President <br/>
                    1 Dealer System </b></p>
                </body> 
            </html>';
    } else if ($type == 'forgot') {

        $currentTime = date("Y-m-d h:i:s"); //current time
        $expire_in = strtotime('+15 minutes', strtotime($currentTime));
        $encodedtime = urlencode(base64_encode($expire_in));

        $encodedemail = urlencode(base64_encode($email));
        $encodedtype = urlencode(base64_encode("forgot"));

        $siteurl = $_SESSION['siteurl'];
        $url = $siteurl . '/resetPassword.php?id=' . $encodedemail . '&actype=' . $encodedtype . '&exp=' . $encodedtime;


        $subject = "Reset your 1 Dealer System's Password?";
        $htmlContent = ' 
            <html> 
                <body> 
                    <p> Hello <b> ' . $name . '</b>,</p>
                    <h3>You recently requested to reset the password for your 1 Dealer System account.</h3>

                    <h4>Click the button below to proceed:</h4>
                    <a href="' . $url . '" target="_blank" >' . $url . '</a>
                    <br />
                    <br />

                    <p>If you did not request a password reset, please ignore this email or reply to let us know. <br /> 
                    <strong>Note:</strong> This password reset link is only valid for the next 15 minutes.</p>


                    <p>Thanks</p>
                    
                    <p> <b> Best, <br />
                    Roger Dalomba <br />
                    President <br />
                    1 Dealer System </b> </p>
                </body> 
            </html>';
    }



    if (smtp_mailer($to, $subject, $htmlContent)) {
        return "true";
    } else {
        return "false";
    }
    // Set content-type header for sending HTML email 
    // $headers = "MIME-Version: 1.0" . "\r\n";
    // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // // Additional headers 
    // $headers .= 'From:' . $from . '\r\n';

    // // return $htmlContent;

    // // Send email 
    // if (mail($to, $subject, $htmlContent, $headers)) {
    //     return "true";
    // } else {
    //     return "false";
    //     // return "true";
    // }
}

// sendEmail('contactrehmanali@gmail.com' , 'Rehman' , 'register' , '1233jdwia');
// echo "send";

