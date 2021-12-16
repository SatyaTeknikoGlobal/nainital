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
class Members extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("members_m");
        $this->load->library('SSP');
    }

    public function index($param1 = "",$param2 = ""){
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher"){
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
                if ($param1 == "edit" && $param2 != "") {
                    $studentID = $param2;
                    if ($_POST) {
                        $array1 = array(
                            'email' => $this->input->post('email'),
                            'classID' => $this->input->post('classID'),
                            'sectionID' => $this->input->post('sectionID'),
                            'roll_no' => $this->input->post('roll_no'),
                            'address' => $this->input->post('address'),
                            'is_active' => $this->input->post('is_active')
                        );
                        $this->db->where(array('studentID'=>$studentID,'schoolID'=>$schoolID));
                        $this->db->update('student',$array1);
                        redirect(base_url("members/index"));
                    }else{
                        $student_info = $this->db->query("select student.studentID, student.name, student.username, student.email, student.address, student.classID, student.sectionID, student.roll_no, student.phone, student.image, parent.name as parent,student.is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID where student.schoolID = $schoolID AND student.studentID = $studentID AND (student.is_active = 'Y' OR student.is_active = 'D')")->row();
                        if (count($student_info)) {
                            $this->data['classes'] = $this->members_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                            $this->data['section'] = $this->members_m->get_multiple_row('section',array('schoolID'=>$schoolID,'classID'=>$student_info->classID,'is_active'=>'Y'));
                            $this->data['student_info'] = $student_info;
                            $this->data['title'] = "Edit Student";
                            $this->data['subview'] = "member/edit_student";
                            $this->load->view("layout",$this->data);
                        }else{
                            echo "Permission Denied";
                        }                        
                    }
                }else{
                    if ($_POST)
                    {
                        $this->db->where(array('studentID'=>$this->input->post('studentID'),'schoolID'=>$schoolID));
                        $query = $this->db->update('student',array('is_active'=>$this->input->post('is_active')));
                        if($query){
                            echo "success";
                        }else{
                            echo "failed";
                        }
                    }else{
                        $this->data['classes'] = $this->members_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                        $this->data['title'] = "Students";
                        $this->data['subview'] = "member/student";
                        $this->load->view("layout",$this->data);
                    }
                }
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->members_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
                if ($param1 == "view" && $param2 != "") {
                    $student_info = $this->db->query("select student.studentID, student.name, student.username, student.email, student.address, classes.class, section.section, student.roll_no, student.phone, student.image, parent.name as parent,student.is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.studentID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D')")->row();
                    $this->data['student_info'] = $student_info;
                    $this->data['title'] = "Student Information";
                    $this->data['subview'] = "member/view_student";
                    $this->load->view("layout",$this->data);
                }else{
                    $this->data['classes'] = $this->members_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                    $this->data['title'] = "Students";
                    $this->data['subview'] = "member/student";
                    $this->load->view("layout",$this->data);
                }
            }
            
        }
    }
    public function teacher($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher"){
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
                if ($param1 == "edit" && $param2 != "") {
                    $teacherID = $param2;
                    if ($_POST) {
                        $phone = $this->input->post('phone');
                        $email = $this->input->post('email');
                        $address = $this->input->post('address');
                        $is_active = $this->input->post('is_active');
                        $this->db->where(array('teacherID'=>$teacherID,'schoolID'=>$schoolID));
                        $this->db->update('teacher',array('phone'=>$phone,'email'=>$email,'address'=>$address,'is_active'=>$is_active));
                        redirect(base_url("members/teacher"));
                    }else{
                        $select = "teacherID,name,address,email,phone,image,username,authorize_role,is_active";
                        $teacher_info = $this->members_m->get_single_row('teacher',array('teacherID'=>$teacherID,'schoolID'=>$schoolID),$select);
                        if (count($teacher_info)) {
                            $this->data['teacher_info'] = $teacher_info;
                            $this->data['title'] = "Edit Teacher";
                            $this->data['subview'] = "member/edit_teacher";
                            $this->load->view("layout",$this->data);
                        }else{
                            echo "Permission Denied";
                        }                        
                    }
                }else{
                    $this->data['title'] = "Teachers";
                    $this->data['subview'] = "member/teacher";
                    $this->load->view("layout",$this->data);
                }
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->members_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
                if ($param1 == "view" && $param2 != "") {
                    $teacherID = $param2;
                    $select = "teacherID,name,address,email,phone,image,username,authorize_role";
                    $teacher_info = $this->members_m->get_single_row('teacher',array('teacherID'=>$teacherID,'schoolID'=>$schoolID,'is_active'=>'Y'),$select);
                    if (count($teacher_info)) {
                        $this->data['teacher_info'] = $teacher_info;
                        $this->data['title'] = "Teacher Information";
                        $this->data['subview'] = "member/view_teacher";
                        $this->load->view("layout",$this->data);
                    }else{
                        echo "Permission Denied";
                    }
                }else{
                    $this->data['title'] = "Teachers";
                    $this->data['subview'] = "member/teacher";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function list_teacher()
    {
        $loginID = $_SESSION['loginUserID'];
        if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher") {
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->members_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            $table = "(select teacherID,schoolID,name,username,email,phone,image  FROM teacher where schoolID = $schoolID AND (is_active = 'Y' OR is_active = 'D'))as table1";
            $primaryKey = 'teacherID';
            $columns = array(
                array( 'db' => 'teacherID','dt' => 0 ),
                array( 'db' => 'image',
                    'dt' => 1,
                    'formatter' => function( $d, $row ) {
                        if (is_null($d) || $d == ""){
                            return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                        }else{
                            return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                        }

                    }
                ),
                array( 'db' => 'name','dt' => 2 ),
                array( 'db' => 'username','dt' => 3 ),
                array( 'db' => 'email','dt' => 4 ),
                array( 'db' => 'phone', 'dt' => 5 ),
                array( 'db' => 'teacherID',
                    'dt' => 6,
                    'formatter' => function( $d, $row ) {
                        if ($_SESSION['role']== "school") {
                            return "<a href=".base_url('members/teacher/edit').'/'.$d." class = 'btn btn-success' target='_blank'>Edit</a>";
                        }elseif ($_SESSION['role'] == "teacher") {
                            return "<a href=".base_url('members/teacher/view').'/'.$d." class = 'btn btn-success' target='_blank'>View</a>";
                        } 
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
    public function list_students($param1="",$param2="")
    {
        if ($_SESSION['role']=="school" || $_SESSION['role']=="teacher")
        {
            $schoolID = $_SESSION['loginUserID'];
            if ($param1 == "" && $param2 == "") {
                $table = "(select student.studentID, student.name, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "") {
                $table = "(select student.studentID, student.name, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "") {
                $table = "(select student.studentID, student.name, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }
            $primaryKey = 'studentID';
            if ($_SESSION['role'] == "school") {
                $columns = array(
                    array( 'db' => 'studentID','dt' => 0 ),
                    array( 'db' => 'image',
                        'dt' => 1,
                        'formatter' => function( $d, $row ) {
                            if (is_null($d)){
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                            }else{
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                            }

                        }
                    ),
                    array( 'db' => 'name','dt' => 2 ),
                    array( 'db' => 'class','dt' => 3 ),
                    array( 'db' => 'section','dt' => 4 ),
                    array( 'db' => 'roll_no','dt' => 5 ),
                    array( 'db' => 'parent', 'dt' => 6 ),
                    array( 'db' => 'phone', 'dt' => 7 ),
                    array( 'db' => 'is_active',
                           'dt' => 8,
                           'formatter' => function( $d, $row){
                            $status_array = explode(",", $d);
                                $select1 = "<select class='form-control' onchange='alert_selected(".$status_array[0].")'  id = ".$status_array[0].">";
                                $select2 = "</select>";
                                if ($status_array[1] == "Y") {
                                    $option = "<option value='Y' selected = 'selected'>Active</option><option value='D'>Suspend</option>";
                                }else{
                                    $option = "<option value='Y'>Active</option><option selected = 'selected' value='D'>Suspend</option>";
                                }
                                return $select1.$option.$select2;
                            }
                    ),
                    array( 'db' => 'studentID',
                           'dt' => 9,
                           'formatter' => function( $d, $row){
                            $student_edit_button = '<a class = "btn btn-success" href = "'.base_url("members/index/edit/$d").'">Edit</a>';
                            return $student_edit_button;
                        }
                    )                    
                );
            }elseif ($_SESSION['role']=="teacher") {
                $columns = array(
                    array( 'db' => 'studentID','dt' => 0 ),
                    array( 'db' => 'image',
                        'dt' => 1,
                        'formatter' => function( $d, $row ) {
                            if (is_null($d)){
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                            }else{
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                            }

                        }
                    ),
                    array( 'db' => 'name','dt' => 2 ),
                    array( 'db' => 'class','dt' => 3 ),
                    array( 'db' => 'section','dt' => 4 ),
                    array( 'db' => 'roll_no','dt' => 5 ),
                    array( 'db' => 'parent', 'dt' => 6 ),
                    array( 'db' => 'studentID',
                           'dt' => 7,
                           'formatter' => function( $d, $row){
                            $student_edit_button = '<a class = "btn btn-success" href = "'.base_url("members/index/view/$d").'">View</a>';
                            return $student_edit_button;
                        }
                    )                    
                );
            }
            
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