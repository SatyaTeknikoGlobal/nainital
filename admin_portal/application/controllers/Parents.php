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

class Parents extends MY_Controller
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
            $this->data['title'] = "Students";
            $this->data['subview'] = "member/student";
            $this->load->view("layout",$this->data);
        }
    }
    public function list_student()
    {
        if ($_SESSION['role'] == "admin") {
            $table = "(select `student`.`studentID`,`student`.`school_code`,`student`.`username`,`student`.`name`,`student`.`is_active`,`student`.`email`,student.phone,classes.class,section.section,student.image,student.added_on,student.subscription_status FROM student left join classes on student.classID = classes.classID left join section on student.sectionID = section.sectionID order by student.added_on desc)as table1";
            $primaryKey = 'studentID';
            $columns = array(
                array( 'db' => 'studentID','dt' => 0 ),
                array( 'db' => 'image',
                    'dt' => 1,
                    'formatter' => function( $d, $row ) {
                        if (is_null($d) || $d == ""){
                            return "<img class='img-rounded' height = '70px' src=".base_url1('uploads/images').'/default.png'.">";
                        }else{
                            return "<img class='img-rounded' height = '70px' src=".base_url1('uploads/images').'/'.$d.">";
                        }

                    }
                ),
                array( 'db' => 'school_code','dt' => 2 ),
                array( 'db' => 'username','dt' => 3 ),
                array( 'db' => 'name','dt' => 4 ),
                array( 'db' => 'class', 'dt' => 5 ),
                array( 'db' => 'section', 'dt' => 6 ),
                array( 'db' => 'phone','dt' => 7),
                array( 'db' => 'added_on',
                    'dt' => 8,
                    'formatter' => function( $d, $row ) {
                        return date("Y-m-d",strtotime($d));
                    }
                ),
                array( 'db' => 'is_active','dt' => 9),
                array( 'db' => 'subscription_status','dt' => 10),
                array( 'db' => 'studentID','dt' => 11)
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