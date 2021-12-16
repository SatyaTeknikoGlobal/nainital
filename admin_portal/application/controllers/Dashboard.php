<?php

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
class Dashboard extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("dashboard_m");
    }

    public function index(){

        if (strtolower($_SESSION['role']) == "admin") {

            $this->data['schools']= $this->db->query("SELECT count(*) as school_count from school_registration")->row();
            $this->data['active_schools']= $this->db->query("SELECT count(*) as school_count from school_registration where subscription_status = 'Y' AND is_active='Y'")->row();
            $this->data['student']= $this->db->query("SELECT count(*) as count from student")->row();
            $this->data['active_student']= $this->db->query("SELECT count(*) as count from student where is_active='Y'")->row();
            $this->data['teacher']= $this->db->query("SELECT count(*) as count from teacher")->row();
            $this->data['active_teacher']= $this->db->query("SELECT count(*) as count from teacher where is_active='Y'")->row();
            $this->data['parent']= $this->db->query("SELECT count(*) as count from parent")->row();
            $this->data['active_parent']= $this->db->query("SELECT count(*) as count from parent where is_active='Y'")->row();
            $this->data['title'] = "Dashboard";
            $this->data['subview'] = "dashboard/index";
            $this->load->view("layout",$this->data);
        }else{
            echo "Permission Denied";
        }
    }
    public function my_profile()
    {
        if (strtolower($_SESSION['role']) == "admin") {
            $path = base_url1('uploads/images/');
            if (strtolower($_SESSION['role']) == "admin"){
                $userID = $_SESSION['loginUserID'];
                $this->db->select("name,email,phone,designation,added_on,modified_on,email as username,CONCAT('".$path."',COALESCE(NULLIF(admin.image, ''),'default.png')) as image");
                $this->data['profile'] = $this->db->get_where('admin',array('admin.adminID'=>$userID))->row();
                $this->data['role'] = "Admin";
            }
            $this->data['title'] = "MY PROFILE";
            $this->data['subview'] = "dashboard/profile";
            $this->load->view("layout",$this->data);
        }
    }
}