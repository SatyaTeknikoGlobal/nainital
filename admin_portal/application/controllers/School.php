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
class School extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("dashboard_m");
        $this->load->library('SSP');
    }

    public function index(){
        if (strtolower($_SESSION['role']) == "admin"){
            $this->data['title'] = "Schools";
            $this->data['subview'] = "member/school";
            $this->load->view("layout",$this->data);
        }
    }
    public function list_school()
    {
        if ($_SESSION['role'] == "admin") {
            $table = "(select `school_registration`.`schoolID`,`school_registration`.`school_code`,`school_registration`.`name` as admin,`school_registration`.`email`,school_registration.phone,school_registration.image,school_registration.added_on,school_registration.subscription_status,school_config.school_name as school  FROM school_registration left join school_config on school_registration.schoolID = school_config.schoolID order by school_registration.added_on desc)as table1";
            $primaryKey = 'schoolID';
            $columns = array(
                array( 'db' => 'image',
                    'dt' => 0,
                    'formatter' => function( $d, $row ) {
                        if (is_null($d) || $d == ""){
                            return "<img class='img-rounded' height = '70px' src=".base_url1('uploads/images').'/default.png'.">";
                        }else{
                            return "<img class='img-rounded' height = '70px' src=".base_url1('uploads/images').'/'.$d.">";
                        }
                    }
                ),
                array( 'db' => 'school_code','dt' => 1 ),
                array( 'db' => 'school','dt' => 2 ),
                array( 'db' => 'admin','dt' => 3 ),
                array( 'db' => 'email', 'dt' => 4 ),
                array( 'db' => 'phone', 'dt' => 5 ),
                array( 'db' => 'added_on',
                    'dt' => 6,
                    'formatter' => function( $d, $row ) {
                        return date("Y-m-d",strtotime($d));
                    }
                ),
                /*array( 'db' => 'subscription_status','dt' => 7),*/
                array( 'db' => 'schoolID',
                    'dt' => 7,
                    'formatter' => function( $d, $row ) {
                        return "<a class='btn btn-success' title='view school' target='_blank' href='".base_url('school/view/').$d."'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;<a title='visit portal' class='btn btn-primary' target='_blank' href='".base_url1('login/support_school/').$d.'/'.$this->signin_m->hash($d)."'><i class='fa fa-sign-in-alt'></i></a>";
                    }
                )
            );
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db'   => $this->db->database,
                'host' => $this->db->hostname
            );
            $response = SSP::complex_kshitij( $_GET, $sql_details, $table, $primaryKey, $columns);
            echo json_encode($response);
        }

    }
    public function view($param = ''){
        if (strtolower($_SESSION['role']) == "admin"){
            if ($param != ''){
                $path = base_url1('uploads/images/');
                $schoolID = $param;
                $this->db->select("school_config.*,school_registration.name as admin_name,school_registration.phone as admin_phone, school_registration.email as admin_email,school_registration.address as admin_address,school_registration.school_code,school_registration.username,school_registration.subscription_status, CONCAT('".$path."',COALESCE(NULLIF(school_registration.image, ''),'default.png')) as image");
                $this->db->join('school_config','school_registration.schoolID = school_config.schoolID','LEFT');
                $school = $this->db->get_where('school_registration',array('school_registration.schoolID'=>$schoolID))->row();
                $this->data['student']= $this->db->query("SELECT count(*) as count from student where schoolID=$schoolID")->row();
                $this->data['active_student']= $this->db->query("SELECT count(*) as count from student where schoolID=$schoolID AND is_active='Y'")->row();
                $this->data['teacher']= $this->db->query("SELECT count(*) as count from teacher where schoolID=$schoolID")->row();
                $this->data['active_teacher']= $this->db->query("SELECT count(*) as count from teacher where schoolID=$schoolID AND is_active='Y'")->row();
                $this->data['parent']= $this->db->query("SELECT count(*) as count from student where schoolID=$schoolID AND parentID is not null AND parentID != ''")->row();
                $this->data['active_parent']= $this->db->query("SELECT count(*) as count from student LEFT join parent on student.parentID = parent.parentID where student.parentID is not null AND student.schoolID=$schoolID AND student.is_active = 'Y' AND parent.is_active='Y'")->row();
                $this->data['school'] = $school;
                $this->data['title'] = "$school->school_name";
                $this->data['subview'] = "member/school_view";
                $this->load->view("layout",$this->data);
            }
        }
    }
}