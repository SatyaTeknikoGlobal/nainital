<?php
ob_start();
use libraries\PHPMailer\PHPMailer\PHPMailer;
use libraries\PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if ($_POST) {
    $mailto = "anisha@teknikoglobal.com";
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $interest = $_POST['interest'];
    $message = $_POST['message'];
    $example1 = $_POST['example1'];
    $uid = md5(uniqid(time()));

    $subject = "Enquiry of ".$name;
    $from_name = "Tekniko Global";
    $message = "Name : $name \r\nPhone : $phone\r\nEmail : $email \r\nInterest : $interest\r\nMessage : $message" ;
    $html_message = "<p><b>Name : </b>$name</p>
    <p><b>Phone : </b>$phone</p>
    <p><b>Email : </b>$email</p>
    <p><b>Interest : </b>$interest</p>
    <p><b>Message : </b>$message</p>";

    $mail = new PHPMailer(true);                              
    try {
        //Server settings
        $mail->SMTPDebug = 3;                                
        $mail->isSMTP(true);                                    
        $mail->Host = 'gains.teknikoglobal.com'; 
        $mail->SMTPAuth = true;                              
        $mail->Username = 'mansha@teknikoglobal.com';             
        $mail->Password = 'Password@1';                    
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;                               

        //Recipients
        $mail->setFrom('info@teknikoglobal.com', 'Tekniko Global');
        $mail->addAddress($mailto);  
        $mail->AddCC($email_1);   
        $mail->addReplyTo($email, $name);

        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = $subject;
        $mail->Body    = $html_message;
        $mail->AltBody = $message;
        $mail->send();

        header('Location: thanku.php');
         
    } catch (Exception $e) {
       // echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
    
    header('Location: ' . base_url("home/thankyou")); 
    

}

?>
