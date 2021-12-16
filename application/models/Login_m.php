<?php
/**
 * Created by PhpStorm.
 * User: kshit
 * Date: 2019-04-02
 * Time: 18:37:40
 */

class Login_m extends MY_Model
{
    function __construct() {
        parent::__construct();
    }

    function hash($string) {
        return parent::hash($string);
    }

     public function generate_random_password($length = 4) {
        $numbers = range('1','9');
        $final_array = array_merge($numbers);
        //$final_array = array_merge($numbers);
        $password = '';

        while($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }

        return $password;
    }

    public function generate_random_password2($length = 6) {
        $numbers = range('0','9');
        $alphabets = range('A','Z');
        $final_array = array_merge($alphabets,$numbers);
        $password = '';
        while($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }
        return $password;
    }

    // public function user_login($mobile)
    // {
    //     $validate = $this->db->get_where('users',array('mobile'=>'+91'.$mobile))->row();
    //     if(!empty($validate)){
    //         $otp = $this->generate_random_password();
    //         $message = $otp." is your authentication code.";
    //         $this->send_sms($mobile,$message);
    //         $data = array(
    //             "loginUserID" => $validate->ID,
    //             "name" => $validate->name,
    //             "email" => $validate->email,
    //             "mobile" => $validate->mobile,
    //             "cityID" => $validate->cityID,//new
    //             "user_login" => FALSE
    //         );
        
    //         $this->session->unset_userdata('panel');
    //         $this->session->set_userdata($data);
    //         $_SESSION['user_login_otp'] = $otp;
    //         //return TRUE;
    //         return $otp;
    //     }else{
    //         return false;
    //     }
    // }

    // public function user_login($mobile)
    // {
    //     $validate = $this->db->get_where('users',array('mobile'=>'+91'.$mobile))->row();
    //     if(!empty($validate)){
    //         $otp = $this->generate_random_password();
    //         $message = $otp." is your authentication code.";
    //         $this->send_sms($mobile,$message);
    //         $data = array(
    //             "loginUserID" => $validate->ID,
    //             "user_login" => FALSE
    //         );
        
    //         $this->session->unset_userdata('panel');
    //         $this->session->set_userdata($data);
    //         $_SESSION['user_login_otp'] = $otp;
    //         return TRUE;
    //         //return $otp;
    //     }
    //     else{
    //         $array = array();
    //         $array['referral_code'] = $this->generate_referral_code($mobile);
    //         $array['mobile'] = '+91'.$mobile;
    //         $array['image'] = 'default.jpg';
    //         $array['wallet'] = 0;
    //         $array['status'] = 'Y';
    //         $array['added_on'] = date("Y-m-d H:i:s");
    //         $array['updated_on'] = date("Y-m-d H:i:s");
    //         $this->db->insert('users',$array);
    //         $userID = $this->db->insert_id();

    //         $otp = $this->generate_random_password();
    //         $message = $otp." is your authentication code.";
    //         $this->send_sms($mobile,$message);
    //         $data = array(
    //             "loginUserID" =>$userID,
    //             "user_login" => TRUE
    //         );
    //         $this->session->set_userdata($data);
    //         $_SESSION['user_login_otp'] = $otp;
    //         return TRUE;
    //     }
    // }

    
    public function user_login($mobile)

    {

        $validate = $this->db->get_where('users',array('mobile'=>$mobile))->row();

        if(!empty($validate)){

            $otp = $this->generate_random_password();

            $message = $otp." is your authentication code.";

           $this->send_sms($mobile,$message);

            $data = array(

                "loginUserID" => $validate->ID,

                "name" => $validate->name,

                "mobile" => $validate->mobile,

                "user_login" => FALSE

            );

        

           $this->session->unset_userdata('panel');

            $this->session->set_userdata($data);

            $_SESSION['user_login_otp'] = $otp;

            //return TRUE;

            /*return 'success';*/

         return $otp;

          //echo $otp;

        }else{

            return false;

        }

    }
    public function send_sms($mobile, $message){
        $sender = "AROGYA";
        $message = urlencode($message);
    
        $msg = "sender=".$sender."&route=4&country=91&message=".$message."&mobiles=".$mobile."&authkey=284738AIuEZXRVCDfj5d26feae";
           
        $ch = curl_init('http://api.msg91.com/api/sendhttp.php?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
            //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $result = curl_close($ch);
        return $res;
    }
    
    public function otp($mobile)
    {
        $otp = $this->generate_random_password();
        $message = $otp." is your authentication code.";
        $this->send_sms($mobile,$message);
        $_SESSION['user_register_otp'] = $otp;
        return TRUE;
    }

    public function register_user()
    {
        if (isset($_SESSION['register_info'])){
            $array = $_SESSION['register_info'];
            $user_name = $array['name'];
            $array['status'] = 'Y';
            $array['added_on'] = date("Y-m-d H:i:s");
            $array['updated_on'] = date("Y-m-d H:i:s");
            $this->db->insert('users',$array);
            $userID = $this->db->insert_id();
            unset($_SESSION['register_info']);
            $validate = $this->db->get_where('users',array('ID'=>$userID))->row();
            if(!empty($validate)) {
                $data = array(
                    "loginUserID" => $validate->ID,
                    "name" => $validate->name,
                    "email" => $validate->email,
                    "mobile" => $validate->mobile,
                    "user_login" => TRUE
                );
                $this->session->set_userdata($data);
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}