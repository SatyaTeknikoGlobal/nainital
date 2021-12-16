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
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher") {
            if ($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];

            }elseif ($_SESSION['role']=="teacher"){
                $school = $this->dashboard_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            $this->data['students_count'] = $this->db->query("SELECT count(studentID) as student FROM student where schoolID = $schoolID AND (is_active = 'Y' OR is_active = 'D')")->row();
            $this->data['teachers_count'] = $this->db->query("SELECT count(teacherID) as teacher FROM teacher where schoolID = $schoolID AND (is_active = 'Y' OR is_active = 'D')")->row();
            $this->data['tstudents_count'] = $this->db->query("SELECT count(studentID) as student FROM student where schoolID = $schoolID AND (is_active = 'Y' OR is_active = 'D' OR is_active = 'N')")->row();
            $this->data['tteachers_count'] = $this->db->query("SELECT count(teacherID) as teacher FROM teacher where schoolID = $schoolID AND (is_active = 'Y' OR is_active = 'D' OR is_active = 'N')")->row();
            $t = 'a'.date("j");
            $monthyear = date("m-Y");
            $this->data['students_present'] = $this->db->query("SELECT COUNT(DISTINCT(`studentID`)) as attendance FROM `student_attendance` WHERE `monthyear` = '$monthyear' AND $t = 'P' AND `schoolID` = '$schoolID'")->row();
            $this->data['teachers_present'] = $this->db->query("SELECT COUNT(DISTINCT(`teacherID`)) as attendance FROM `teacher_attendance` WHERE `monthyear` = '$monthyear' AND $t = 'P' AND `schoolID` = '$schoolID'")->row();
            $date = date("Y-m-d");
            $past = date("Y-m-d",strtotime("-6 day", strtotime($date))); 
            $graph_stu = array();
            while ( $past <= $date) {
                $my = date("m-Y",strtotime($past));
                $col = 'a'.date("j",strtotime($past));
                $day = date("M d",strtotime($past));
                $q5 = $this->db->query("SELECT COUNT(DISTINCT(`studentID`)) as attendance FROM `student_attendance` WHERE `monthyear` = '$my' AND $col = 'P' AND `schoolID` = '$schoolID'")->row();
                $graph_stu[]=array("day"=>$day, "count"=>$q5->attendance);
                $past = date("Y-m-d",strtotime("+1 day", strtotime($past)));
            }
            $graph_teacher = array();
            $date = date("Y-m-d");
            $past = date("Y-m-d",strtotime("-6 day", strtotime($date))); 

            while ( $past <= $date) {
                $my = date("m-Y",strtotime($past));
                $col = 'a'.date("j",strtotime($past));
                $day = date("M d",strtotime($past));
                $q5 = $this->db->query("SELECT COUNT(DISTINCT(`teacherID`)) as attendance FROM `teacher_attendance` WHERE `monthyear` = '$my' AND $col = 'P' AND `schoolID` = '$schoolID'")->row();
                $graph_teacher[]=array("day"=>$day, "count"=>$q5->attendance);
                $past = date("Y-m-d",strtotime("+1 day", strtotime($past)));
            }
            $parents = $this->db->query("SELECT COUNT(studentID) AS count FROM student WHERE schoolID = '$schoolID' AND parentID != '' AND parentID IS NOT NULL AND parentID != 0")->row();
            $holidays = $this->db->get_where('holiday',array('schoolID'=>$schoolID))->result();
            $announcements = $this->db->get_where('notice',array('schoolID'=>$schoolID))->result();
            $other_data = array('parents'=>$parents->count,'holidays'=>$holidays,'announcements'=>$announcements);
            $this->data['others'] = json_encode($other_data,true);
            $this->data['student_graph'] = $graph_stu;
            $this->data['teacher_graph'] = $graph_teacher;
            $this->db->select("school_config.school_name,school_registration.school_code,school_config.email,school_config.phone");
            $this->db->join('school_config','school_registration.schoolID = school_config.schoolID','LEFT');
            $this->data['school_info'] = $this->db->get_where('school_registration',array('school_registration.schoolID'=>$schoolID))->row();
            $this->data['title'] = "Dashboard";
            $this->data['subview'] = "dashboard/index";
            $this->load->view("layout",$this->data);
        }else{
            echo "Permission Denied";
        }
    }
    public function my_profile()
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher") {
            $path = base_url('uploads/images/');
            if (strtolower($_SESSION['role']) == "school"){
                $table = 'school_registration';
                $column = 'schoolID';
                $userID = $_SESSION['loginUserID'];
                $this->db->select("school_registration.school_code,CONCAT('".$path."',COALESCE(NULLIF(school_registration.image, ''),'default.png')) as image,school_registration.username,school_registration.address as address,school_registration.address as school_address,school_registration.name,school_registration.phone,school_registration.email,school_config.school_name,school_config.principal_name,school_config.email as school_email,school_config.phone as school_phone,school_config.school_prefix");
                $this->db->join('school_config','school_registration.schoolID = school_config.schoolID');
                $this->data['profile'] = $this->db->get_where('school_registration',array('school_registration.schoolID'=>$userID))->row();
                $this->data['role'] = "School";
            }elseif (strtolower($_SESSION['role']) == "teacher"){
                $table = 'teacher';
                $column = 'teacherID';
                $userID = $_SESSION['loginUserID'];
                $this->db->select("teacher.school_code,CONCAT('".$path."',COALESCE(NULLIF(teacher.image, ''),'default.png')) as image,teacher.username,teacher.address,teacher.name,teacher.phone,teacher.email,school_registration.address as school_address,school_config.school_name,school_config.principal_name,school_config.email as school_email,school_config.phone as school_phone,school_config.school_prefix");
                $this->db->join('school_config','teacher.schoolID = school_config.schoolID');
                $this->db->join('school_registration','teacher.schoolID = school_registration.schoolID');
                $this->data['profile'] = $this->db->get_where('teacher',array('teacher.teacherID'=>$userID))->row();
                $this->data['role'] = "Teacher";
            }
            if ($_POST){
                $current_password = $this->input->post('current_password');
                $new_password = $this->input->post('new_password');
                $confirm_password = $this->input->post('confirm_password');
                $check_current = $this->db->get_where($table,array($column=>$userID,'password'=>$this->signin_m->hash($current_password)))->row();
                if (count($check_current)){
                    if ($new_password == $confirm_password){
                        $this->db->where(array($column=>$userID));
                        $this->db->update($table,array('password'=>$this->signin_m->hash($confirm_password)));
                        $this->data['success'] = "Password updated successfully";
                        $this->data['title'] = "MY PROFILE";
                        $this->data['subview'] = "dashboard/profile";
                        $this->load->view("layout",$this->data);
                    }else{
                        $this->data['alert'] = "! New & Confirm Password Mismatch";
                        $this->data['title'] = "MY PROFILE";
                        $this->data['subview'] = "dashboard/profile";
                        $this->load->view("layout",$this->data);
                    }
                }else{
                    $this->data['alert'] = "! Current Password Mismatch";
                    $this->data['title'] = "MY PROFILE";
                    $this->data['subview'] = "dashboard/profile";
                    $this->load->view("layout",$this->data);
                }
            }else{
                $this->data['title'] = "MY PROFILE";
                $this->data['subview'] = "dashboard/profile";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function holiday_list()
    {
        if ($_POST){
            $schoolID = $_SESSION['schoolID'];
            $monthyear = date("m-Y",strtotime($this->input->post('monthyear')));
            $month = date('Y-m',strtotime($this->input->post('monthyear')));
            $first_date = $month."-01";
            $last_date = $month."-".date("t",strtotime($first_date));
            $t = date('t',strtotime($monthyear));
            $holiday = $this->db->get_where('holiday',array('schoolID'=>$schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
            $response[] = array('result'=>'success','end'=>$t,'yearmonth'=>date("Y-m",strtotime($this->input->post('monthyear'))),'holiday'=>$holiday);
            echo json_encode($response);
        }
    }
    public function check_username()
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher") {
            if ($_SESSION['role'] == "school"){
                $table = "school_registration";
            }elseif ($_SESSION['role']=="teacher"){
                $table = "teacher";
            }
            if ($_POST){
                $username = $this->input->post('username');
                $check = $this->db->get_where($table,array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    echo "success";
                }
            }
        }
    }
    public function change_username()
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher") {
            if ($_SESSION['role'] == "school"){
                $table = "school_registration";
            }elseif ($_SESSION['role']=="teacher"){
                $table = "teacher";
            }
            if ($_POST){
                $username = $this->input->post('username');
                $check = $this->db->get_where($table,array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    $this->db->where(array($_SESSION['role'].'ID'=>$_SESSION['loginUserID']));
                    $query = $this->db->update($table,array('username'=>$username));
                    if ($query){
                        $_SESSION['username'] = $username;
                        echo "Your username has been changed to ".$username;
                    }else{
                        echo "failure";
                    }
                }
            }
        }
    }
}