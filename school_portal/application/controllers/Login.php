<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
	function __construct () {
		parent::__construct();
		$this->load->model("signin_m");
	}

	public function index()
	{
		$this->signin_m->loggedin() == FALSE || redirect(base_url('dashboard/index'));
		$data['title'] = "Login" ;
		$this->load->view('login/index', $data);
	}
    public function support_school($schoolID,$code)
    {
        $userlogin = $this->signin_m->support_login($schoolID,$code);
        if($userlogin)
        {
            redirect(base_url("dashboard/index"));
        } else {
            echo "Please Try again later";
        }
    }
	public function check_login()
	{
	    $role     = $this->input->post('role');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$userlogin = $this->signin_m->signin($role,$username,$password);
		if($userlogin)
		{
			echo "Success";
		} else {


            echo "Failed";
			
		}
	}

	public function logout()
	{
		$this->signin_m->signout();
		redirect(base_url());
	}

	public function reset()
	{
		if ($_POST) {
			$userID = $_SESSION['loginUserID'];
			$username = $_SESSION['username'];
			$password = $this->input->post('password');
			$check = $this->signin_m->checkoldpsw($username,$password);
			if ($check) {
				$new_password = $this->input->post('new_password');
				$repeat_password = $this->input->post('repeat');
				if ($new_password == $repeat_password) {
					$this->signin_m->reset($userID,$new_password);
					$this->logout();
				}else{
					echo "New Password and repeat password must be same";
				}
			}else{
				echo "current Password Incorrect";
			}
			
		}else{
			$this->data['username'] = $_SESSION['username'];
			$this->data["subview"] = "login/reset";
			$this->data["title"] = "Reset Password";
			$this->load->view('_layout_main', $this->data);
		}
	}

}