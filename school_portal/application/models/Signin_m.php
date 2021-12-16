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
        $user = $this->db->get_where($table, array("username" => $username, "password" => $this->hash($password)));
        $alluserdata = $user->row();
        if(count($alluserdata)){
            if ($table == 'school_registration')
            {
                $loginuserID = $alluserdata->schoolID;
                $role = "school";
            }
            elseif ($table == 'teacher')
            {
                $loginuserID = $alluserdata->teacherID;
                $role = "teacher";
            }
            if($alluserdata->image == "")
            {
                $image = "default.png";
            }else{
                $image = $alluserdata->image;
            }
            $school_details = $this->db->get_where('school_config',array('schoolID'=>$alluserdata->schoolID))->row();
            if (is_null($school_details->logo) || $school_details->logo == ""){
                $logo = "erplogo.png";
            }else{
                $logo = $school_details->logo;
            }
            $data = array(
                "loginUserID" => $loginuserID,
                "schoolID" => $alluserdata->schoolID,
                "code" => $alluserdata->school_code,
                "name" => $alluserdata->name,
                "email" => $alluserdata->email,
                "mobile" => $alluserdata->phone,
                "username" => $alluserdata->username,
                "image" =>$image,
                "role" => $role,
                "loggedin" => TRUE,
                "logo" =>$logo
            );
            $this->session->set_userdata($data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function support_login($schoolID,$code)
    {
        $code1 = $this->hash($schoolID);
        if ($code == $code1){
            $user = $this->db->get_where('school_registration', array("schoolID" => $schoolID));
            $alluserdata = $user->row();
            if(count($alluserdata)){
                $school_details = $this->db->get_where('school_config',array('schoolID'=>$alluserdata->schoolID))->row();
                if (is_null($school_details->logo) || $school_details->logo == ""){
                    $logo = "erplogo.png";
                }else{
                    $logo = $school_details->logo;
                }
                if($alluserdata->image == "")
                {
                    $image = "default.png";
                }else{
                    $image = $alluserdata->image;
                }
                $data = array(
                    "loginUserID" => $alluserdata->schoolID,
                    "schoolID"=> $alluserdata->schoolID,
                    "code" => $alluserdata->school_code,
                    "name" => $alluserdata->name,
                    "email" => $alluserdata->email,
                    "mobile" => $alluserdata->phone,
                    "username" => $alluserdata->username,
                    "image" =>$image,
                    "role" => 'school',
                    "loggedin" => TRUE,
                    "logo" =>$logo
                );
                $this->session->set_userdata($data);
                return TRUE;
            } else {
                return FALSE;
            }
        }else {
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
        if ($table == "other_staff"){
            $this->db->where(array('staffID'=>$ID,'schoolID'=>$schoolID));
            $this->db->update($table,array('password'=>$this->hash($password)));
            return TRUE;
        }else{
            $this->db->where(array($table.'ID'=>$ID,'schoolID'=>$schoolID));
            $this->db->update($table,array('password'=>$this->hash($password)));
            return TRUE;
        }
    }
}