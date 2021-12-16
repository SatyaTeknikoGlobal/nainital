<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("home_m");

    }

    public function index()
    {
        $this->data['page_name'] = 'home';
        $this->data['title'] = 'Home';
      $this->load->view('index',$this->data);

    }

     public function enquiry()
    {
        if ($_POST)
        {
            $data = $_POST;
            $name = $data['name'];
            $phone = $data['phone'];
            $email = $data['email'];
            $message = $data['message'];

        $array = array(
            "name" =>  $name,
            "phone" => $phone,
            "email" =>$email,
            "message" => $message,
            "added_on" => date('Y-m-d H:i:s'),
        );
        $this->db->insert('enquiry',$array);

        $email = 'anisha@teknikoglobal.com';
        $subject = "Enquiry of ".$name;
        $message1 =  "<p><b>Name : </b>$name</p>
                        <p><b>Phone : </b>$phone</p>
                        <p><b>Email : </b>$email</p>
                        <p><b>message : </b>$message</p>";
               
        

        $this->send_mail($email,$subject,$message1);


        redirect('success');
    }
    else{

        $this->index();

    }
}
   
   
 public function send_mail($email,$subject,$message1){
    $to = $email;
    $from = 'anisha@teknikoglobal.com';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.'ArogyPro'."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    if(mail($to, $subject, $message, $headers)){
        echo 'Your mail has been sent successfully.';
    } else{
        echo 'Unable to send email. Please try again.';
    }
}

public function success()
    {
        $this->data['page_name'] = 'thankyou';
        $this->data['title'] = 'Success';
      $this->load->view('index',$this->data);

    }
}