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

class Teacher extends MY_Controller
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
            $this->data['title'] = "Teachers";
            $this->data['subview'] = "member/teacher";
            $this->load->view("layout",$this->data);
        }
    }
    public function list_teacher()
    {
        if ($_SESSION['role'] == "admin") {
            $table = "(select `teacher`.`teacherID`,`teacher`.`school_code`,`teacher`.`username`,`teacher`.`name`,`teacher`.`is_active`,`teacher`.`email`,teacher.phone,teacher.image,teacher.added_on FROM teacher order by teacher.added_on desc)as table1";
            $primaryKey = 'teacherID';
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
                array( 'db' => 'username','dt' => 2 ),
                array( 'db' => 'name','dt' => 3 ),
                array( 'db' => 'email','dt' => 4),
                array( 'db' => 'phone','dt' => 5),
                array( 'db' => 'added_on',
                    'dt' => 6,
                    'formatter' => function( $d, $row ) {
                        return date("Y-m-d",strtotime($d));
                    }
                ),
                array( 'db' => 'is_active','dt' => 7),
                array( 'db' => 'teacherID','dt' => 8)
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
}