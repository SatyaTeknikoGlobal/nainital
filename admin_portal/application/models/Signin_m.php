<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	MENTOR ERP
| -----------------------------------------------------
| AUTHOR:			Kshitij Kumar Singh
| -----------------------------------------------------
| EMAIL:			kshitij.singh@teknikoglobal.com
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY TEKNIKOGLOBAL
| -----------------------------------------------------
| WEBSITE:			https://www.teknikoglobal.com
| -----------------------------------------------------
*/


class signin_m extends MY_Model
{
    function __construct() {
        parent::__construct();
    }

    function hash($string) {
        return parent::hash($string);
    }

    public function signin($table,$username,$password)
    {
        //$user = $this->db->get_where($table, array("email" => $username, "password" => $this->hash($password)));
         $user = $this->db->get_where($table, array("email" => $username, "password" => $password));


        $alluserdata = $user->row();
       
       /* if($alluserdata->image == "" || is_null($alluserdata->image))
        {
            $image = "default.png";
        }else{
            $image = $alluserdata->image;
        }*/
        if(!empty($alluserdata) ){
            $data = array(
                "loginUserID" => $alluserdata->adminID,
                "name" => $alluserdata->name,
                "email" => $alluserdata->email,
                "mobile" => $alluserdata->phone,
                "image" =>'default.png',
                "role" => $alluserdata->role,
                "loggedin" => TRUE
            );
            $this->session->set_userdata($data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkoldpsw($username,$password)
    {
        $table = 'admin';
        $user = $this->db->get_where($table, array("username" => $username, "password" => $this->hash($password)))->row();
        if (count($user)) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function signout() {
        $this->session->sess_destroy();
    }

    public function loggedin() {
        return (bool) $this->session->userdata("loggedin");
    }

    public function reset($table,$ID,$schoolID,$password) {
        $this->db->where(array($table.'ID'=>$ID,'schoolID'=>$schoolID));
        $this->db->update($table,array('password'=>$this->hash($password)));
        return TRUE;
    }
}