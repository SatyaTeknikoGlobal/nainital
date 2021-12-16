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
        $this->load->library('csvimport');
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
                            'batchID' => $this->input->post('batchID'),
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
                        $student_info = $this->db->query("select student.studentID,COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.name, student.username, student.email, student.address, student.classID, student.sectionID, student.roll_no, student.phone, student.image, parent.name as parent,student.is_active FROM student LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN parent on student.parentID = parent.parentID where student.schoolID = $schoolID AND student.studentID = $studentID AND (student.is_active = 'Y' OR student.is_active = 'D')")->row();
                        if (count($student_info)) {
                            $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                            $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
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
                        $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                        $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
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
                    $student_info = $this->db->query("select student.studentID, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.name, student.username, student.email, student.address, classes.class, section.section, student.roll_no, student.phone, student.image, parent.name as parent,student.is_active FROM student LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.studentID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D')")->row();
                    $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                    $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
                    $this->data['student_info'] = $student_info;
                    $this->data['title'] = "Student Information";
                    $this->data['subview'] = "member/view_student";
                    $this->load->view("layout",$this->data);
                }else{
                    $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                    $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
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
                        $name = $this->input->post('name');
                        $phone = $this->input->post('phone');
                        $email = $this->input->post('email');
                        $address = $this->input->post('address');
                        $is_active = $this->input->post('is_active');
                        $this->db->where(array('teacherID'=>$teacherID,'schoolID'=>$schoolID));
                        $this->db->update('teacher',array('name'=>$name,'phone'=>$phone,'email'=>$email,'address'=>$address,'is_active'=>$is_active));
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
                array( 'db' => 'image',
                    'dt' => 0,
                    'formatter' => function( $d, $row ) {
                        if (is_null($d) || $d == ""){
                            return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                        }else{
                            return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                        }

                    }
                ),
                array( 'db' => 'name','dt' => 1 ),
                array( 'db' => 'username','dt' => 2 ),
                array( 'db' => 'email','dt' => 3 ),
                array( 'db' => 'phone', 'dt' => 4 ),
                array( 'db' => 'teacherID',
                    'dt' => 5,
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
    public function list_students($param1="",$param2="",$param3="")
    {
        if ($_SESSION['role']=="school" || $_SESSION['role']=="teacher")
        {
            if ($param1 == 'null'){
                $param1 = "";
            }
            if ($param2 == 'null'){
                $param2 = "";
            }
            if ($param3 == 'null'){
                $param3 = "";
            }
            if ($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->members_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            if ($param1 == "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, student.school_studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, student.school_studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 == "") {
                $table = "(select student.studentID, student.school_studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 == "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, student.school_studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, student.school_studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 != "") {
                $table = "(select student.studentID, student.school_studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }
            $primaryKey = 'studentID';
            if ($_SESSION['role'] == "school") {
                $columns = array(
                    array( 'db' => 'image',
                        'dt' => 0,
                        'formatter' => function( $d, $row ) {
                            if (is_null($d)){
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                            }else{
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                            }

                        }
                    ),
                    array( 'db' => 'name','dt' => 1 ),
                    array( 'db' => 'batch','dt' => 2 ),
                    array( 'db' => 'class','dt' => 3 ),
                    array( 'db' => 'section','dt' => 4 ),
                    array( 'db' => 'roll_no','dt' => 5 ),
                    array( 'db' => 'school_studentID','dt' => 6 ),
                    array( 'db' => 'parent', 'dt' => 7 ),
                    array( 'db' => 'phone', 'dt' => 8 ),
                    array( 'db' => 'is_active',
                           'dt' => 9,
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
                           'dt' => 10,
                           'formatter' => function( $d, $row){
                            $student_edit_button = '<a class = "btn btn-success" href = "'.base_url("members/index/edit/$d").'">Edit</a>';
                            return $student_edit_button;
                        }
                    )                    
                );
            }elseif ($_SESSION['role']=="teacher") {
                $columns = array(
                    array( 'db' => 'image',
                        'dt' => 0,
                        'formatter' => function( $d, $row ) {
                            if (is_null($d)){
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                            }else{
                                return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                            }

                        }
                    ),
                    array( 'db' => 'name','dt' => 1 ),
                    array( 'db' => 'batch','dt' => 2 ),
                    array( 'db' => 'class','dt' => 3 ),
                    array( 'db' => 'section','dt' => 4 ),
                    array( 'db' => 'roll_no','dt' => 5 ),
                    array( 'db' => 'username','dt' => 6 ),
                    array( 'db' => 'parent', 'dt' => 7 ),
                    array( 'db' => 'studentID',
                           'dt' => 8,
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
    public function check_username()
    {
        if ($_SESSION['role'] == "school") {
            if ($_POST){
                $username = $this->input->post('username');
                $check = $this->db->get_where('student',array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    echo "success";
                }
            }
        }
    }
    public function change_username($param1 = "")
    {
        if ($_SESSION['role'] == "school") {
            if ($_POST && $param1 != ""){
                $username = $this->input->post('username');
                $check = $this->db->get_where('student',array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    $this->db->where(array('studentID'=>$param1,'schoolID'=>$_SESSION['loginUserID']));
                    $query = $this->db->update('student',array('username'=>$username));
                    if ($query){
                        echo "Username has been changed to ".$username;
                    }else{
                        echo "failure";
                    }
                }
            }
        }
    }
    public function check_teacher_username()
    {
        if ($_SESSION['role'] == "school") {
            if ($_POST){
                $username = $this->input->post('username');
                $check = $this->db->get_where('teacher',array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    echo "success";
                }
            }
        }
    }
    public function check_staff_username()
    {
        if ($_SESSION['role'] == "school") {
            if ($_POST){
                $username = $this->input->post('username');
                $check = $this->db->get_where('other_staff',array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    echo "success";
                }
            }
        }
    }
    public function change_teacher_username($param1 = "")
    {
        if ($_SESSION['role'] == "school") {
            if ($_POST && $param1 != ""){
                $username = $this->input->post('username');
                $check = $this->db->get_where('teacher',array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    $this->db->where(array('teacherID'=>$param1,'schoolID'=>$_SESSION['loginUserID']));
                    $query = $this->db->update('teacher',array('username'=>$username));
                    if ($query){
                        echo "Username has been changed to ".$username;
                    }else{
                        echo "failure";
                    }
                }
            }
        }
    }
    public function change_staff_username($param1 = "")
    {
        if ($_SESSION['role'] == "school") {
            if ($_POST && $param1 != ""){
                $username = $this->input->post('username');
                $check = $this->db->get_where('other_staff',array('username'=>$username))->row();
                if (count($check)){
                    echo "failure";
                }else{
                    $this->db->where(array('staffID'=>$param1,'schoolID'=>$_SESSION['loginUserID']));
                    $query = $this->db->update('other_staff',array('username'=>$username));
                    if ($query){
                        echo "Username has been changed to ".$username;
                    }else{
                        echo "failure";
                    }
                }
            }
        }
    }
    public function list_staff()
    {
        $loginID = $_SESSION['loginUserID'];
        if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher") {
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->members_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            $table = "(select other_staff.staffID,other_roles.role as role,other_staff.schoolID,other_staff.name,other_staff.username,other_staff.email,other_staff.phone,other_staff.image  FROM other_staff LEFT JOIN other_roles ON other_staff.roleID = other_roles.roleID where other_staff.schoolID = $schoolID )as table1";
            $primaryKey = 'staffID';
            $columns = array(
                array( 'db' => 'image',
                    'dt' => 0,
                    'formatter' => function( $d, $row ) {
                        if (is_null($d) || $d == ""){
                            return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/default.png'.">";
                        }else{
                            return "<img class='img-rounded' height = '70px' src=".base_url('uploads/images').'/'.$d.">";
                        }

                    }
                ),
                array( 'db' => 'name','dt' => 1 ),
                array( 'db' => 'username','dt' => 2 ),
                array( 'db' => 'email','dt' => 3 ),
                array( 'db' => 'phone', 'dt' => 4 ),
                array( 'db' => 'role', 'dt' => 5 ),
                array( 'db' => 'staffID',
                    'dt' => 6,
                    'formatter' => function( $d, $row ) {
                        if ($_SESSION['role']== "school") {
                            return "<a href=".base_url('members/non_teaching/edit').'/'.$d." class = 'btn btn-success' target='_blank'>Edit</a>";
                        }elseif ($_SESSION['role'] == "teacher") {
                            return "<a href=".base_url('members/non_teaching/view').'/'.$d." class = 'btn btn-success' target='_blank'>View</a>";
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
    public function non_teaching($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role']=="teacher"){
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
                if ($param1 == "edit" && $param2 != "") {
                    $staffID = $param2;
                    if ($_POST) {
                        $name = $this->input->post('name');
                        $phone = $this->input->post('phone');
                        $email = $this->input->post('email');
                        $address = $this->input->post('address');
                        $roleID = $this->input->post('roleID');
                        $is_active = $this->input->post('is_active');
                        $this->db->where(array('staffID'=>$staffID,'schoolID'=>$schoolID));
                        $this->db->update('other_staff',array('name'=>$name,'phone'=>$phone,'email'=>$email,'address'=>$address,'roleID'=>$roleID,'is_active'=>$is_active));
                        redirect(base_url("members/non_teaching"));
                    }else{
                        $select = "other_staff.staffID,other_staff.name,other_staff.address,other_staff.email,other_staff.phone,COALESCE(NULLIF(other_staff.image,''),'default.png') as image,other_staff.username,other_staff.roleID,other_staff.is_active,other_roles.role";
                        $this->db->select($select);
                        $this->db->join('other_roles','other_staff.roleID = other_roles.roleID','LEFT');
                        $teacher_info = $this->db->get_where('other_staff',array('other_staff.staffID'=>$staffID,'other_staff.schoolID'=>$schoolID))->row();
                        if (count($teacher_info)) {
                            $this->db->select("other_roles.roleID,other_roles.role");
                            $this->data['roles'] = $this->db->get_where('other_roles',array('schoolID'=>$schoolID))->result();
                            $this->data['teacher_info'] = $teacher_info;
                            $this->data['title'] = "Edit Non Teaching Staff";
                            $this->data['subview'] = "member/edit_staff";
                            $this->load->view("layout",$this->data);
                        }else{
                            echo "Permission Denied";
                        }
                    }
                }elseif($param1 == "add"){
                    if ($_POST){
                        $_POST['password'] = $this->signin_m->hash($this->input->post('password'));
                        $check = $this->db->get_where('other_staff',array('username'=>$this->input->post('username')))->row();
                        if (count($check)){
                            echo " ! Username Has Been Taken Please Try Again with Different Username";
                        }else{
                            $_POST['schoolID'] = $schoolID;
                            $_POST['school_code'] = $_SESSION['code'];
                            $_POST['added_on'] = date("Y-m-d H:i:s");
                            $this->db->insert('other_staff',$_POST);
                            $insert_id = $this->db->insert_id();
                            if ($insert_id){
                                echo "success";
                            }else{
                                echo " ! Failure Please Try Again or Contact Our support Team";
                            }
                        }
                    }else{
                        $this->data['roles'] = $this->db->get_where('other_roles',array('schoolID'=>$schoolID))->result();
                        $this->data['title'] = "ADD Non Teaching Staff";
                        $this->data['subview'] = "member/add_staff";
                        $this->load->view("layout",$this->data);
                    }
                }else{
                    $this->data['title'] = "Non Teaching Staff";
                    $this->data['subview'] = "member/others";
                    $this->load->view("layout",$this->data);
                }
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->members_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
                if ($param1 == "view" && $param2 != "") {
                    $staffID = $param2;
                    $select = "other_staff.staffID,other_staff.name,other_staff.address,other_staff.email,other_staff.phone,COALESCE(NULLIF(other_staff.image,''),'default.png') as image,other_staff.username,other_staff.roleID,other_staff.is_active,other_roles.role";
                    $this->db->select($select);
                    $this->db->join('other_roles','other_staff.roleID = other_roles.roleID','LEFT');
                    $teacher_info = $this->db->get_where('other_staff',array('other_staff.staffID'=>$staffID,'other_staff.schoolID'=>$schoolID))->row();
                    if (count($teacher_info)) {
                        $this->db->select("other_roles.roleID,other_roles.role");
                        $this->data['roles'] = $this->db->get_where('other_roles',array('schoolID'=>$schoolID))->result();
                        $this->data['teacher_info'] = $teacher_info;
                        $this->data['title'] = "$teacher_info->name";
                        $this->data['subview'] = "member/view_staff";
                        $this->load->view("layout",$this->data);
                    }else{
                        echo "Permission Denied";
                    }
                }else{
                    $this->data['title'] = "Non Teaching Staff";
                    $this->data['subview'] = "member/others";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function role_non_teaching($param1 = "",$param2 = "")
    {
        if ($_SESSION['role']=="school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($param1 == "edit" && $param2 != "" && $_POST){
                $role = $this->input->post('role');
                $permission = $this->input->post('permission');
                $permission = implode(",",$permission);
                $this->db->where(array('schoolID'=>$schoolID,'roleID'=>$param2));
                $this->db->update('other_roles',array('role'=>$role,'modules'=>$permission,'modified_on'=>date("Y-m-d H:i:s")));
                redirect(base_url("members/role_non_teaching"));
            }elseif ($param1 == "add" && $_POST){
                $role = $this->input->post('role');
                $permission = implode(",",$this->input->post('permission'));
                $this->db->insert('other_roles',array('schoolID'=>$schoolID,'role'=>$role,'modules'=>$permission,'added_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s")));
                redirect(base_url("members/role_non_teaching"));
            }else{
                $school_subscription = $this->db->query("SELECT `modules` FROM `school_subscription_history` WHERE `schoolID` = '$schoolID' ORDER BY `historyID` DESC")->row();
                $this->data['modules'] = $this->db->query("SELECT moduleID,module_name as name FROM modules WHERE FIND_IN_SET(moduleID,'$school_subscription->modules')")->result();
                $this->data['roles'] = $this->db->get_where('other_roles',array('schoolID'=>$schoolID))->result();
                $this->data['title'] = "Non Teaching Staff Roles";
                $this->data['subview'] = "role/list";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function student_bulk_import()
    {
        if ($_SESSION['role']=="school"){
            $schoolID = $_SESSION['schoolID'];
            $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
            $this->data['classes'] = $this->db->get_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
            $this->data['title'] = "Student Bulk Upload";
            $this->data['subview'] = "member/student_bulk";
            $this->load->view("layout",$this->data);
        }
    }
    public function student_bulk_import_section_wise()
    {
        if ($_SESSION['role']=="school" && $_POST){
            $schoolID = $_SESSION['schoolID'];
            $batchID = $this->input->post('batchID');
            $classID = $this->input->post('classID');
            $sectionID = $this->input->post('sectionID');
            $target_path = "uploads/"; // replace this with the path you are going to save the file to
            $target_dir = "uploads/";
            if(is_array($_FILES))
            {
                foreach($_FILES as $fileKey => $fileVal)
                {
                    $imagename = basename($_FILES["imported_file"]["name"]);
                    $extension = substr(strrchr($_FILES['imported_file']['name'], '.'), 1);
                    if (strtolower($extension == "csv")){
                        $actual_image_name = "student".time().".".$extension;
                        move_uploaded_file($_FILES["imported_file"]["tmp_name"],$target_path.$actual_image_name);
                        $file_path =  $target_path.$actual_image_name;
                        if ($this->csvimport->get_array($file_path)) {
                            $csv_array = $this->csvimport->get_array($file_path);
                            $unupload[]=array('StudentID','Name','Date_of_birth','Roll','Phone','Email','Address','City','State','Pin_code','Password','Remark');
                            foreach ($csv_array as $row) {
                                $school_studentID = trim($row["StudentID"]);
                                if (empty($school_studentID)){
                                    $unupload[] = array(
                                        "StudentID" => $row["StudentID"],
                                        "Name" => $row["Name"],
                                        "Date_of_birth" => $row["Date_of_birth"],
                                        "Roll" => $row["Roll"],
                                        "Phone" => $row["Phone"],
                                        "Email" => $row["Email"],
                                        "Address" => $row["Address"],
                                        "City" => $row["City"],
                                        "State" => $row["State"],
                                        "Pin_code" => $row["Pin_code"],
                                        "Password" => $row["Password"],
                                        "remark" => "StudentID is Mandatory"
                                    );
                                }else{
                                    $check_username = $this->db->get_where('student',array('username'=>$row["StudentID"]))->row();
                                    if (count($check_username)){
                                        if ($check_username->schoolID == $schoolID){
                                            $unupload[] = array(
                                                "StudentID" => $row["StudentID"],
                                                "Name" => $row["Name"],
                                                "Date_of_birth" => $row["Date_of_birth"],
                                                "Roll" => $row["Roll"],
                                                "Phone" => $row["Phone"],
                                                "Email" => $row["Email"],
                                                "Address" => $row["Address"],
                                                "City" => $row["City"],
                                                "State" => $row["State"],
                                                "Pin_code" => $row["Pin_code"],
                                                "Password" => $row["Password"],
                                                "remark" => "User with this studentID Already Exist in your School"
                                            );
                                        }else{
                                            $unupload[] = array(
                                                "StudentID" => $row["StudentID"],
                                                "Name" => $row["Name"],
                                                "Date_of_birth" => $row["Date_of_birth"],
                                                "Roll" => $row["Roll"],
                                                "Phone" => $row["Phone"],
                                                "Email" => $row["Email"],
                                                "Address" => $row["Address"],
                                                "City" => $row["City"],
                                                "State" => $row["State"],
                                                "Pin_code" => $row["Pin_code"],
                                                "Password" => $row["Password"],
                                                "remark" => "This studentID is not available."
                                            );
                                        }
                                    }else{
                                        $dob = str_replace('/','-',$row["Date_of_birth"]);
                                        $array = array(
                                            "school_studentID" => $school_studentID,
                                            'schoolID'=>$schoolID,
                                            "username" => $row["StudentID"],
                                            "school_code" => $_SESSION['code'],
                                            "name" => $row["Name"],
                                            "dob" => date("Y-m-d",strtotime($dob)),
                                            "classID" => $classID,
                                            "sectionID" => $sectionID,
                                            "roll_no" => $row["Roll"],
                                            "phone" => $row["Phone"],
                                            "email" => $row["Email"],
                                            "batchID"=>$batchID,
                                            "address" => $row["Address"].", ".$row["City"].", ".$row["State"].", ".$row["Pin_code"],
                                            "password" => $this->signin_m->hash($row["Password"]),
                                            "subscription_status" => 'Y',
                                            'is_active' => 'Y',
                                            'concessionID' => 0,
                                            'parentID'=>0,
                                            'registeredby'=>'bulk',
                                            "start_date" => date('Y-m-d'),
                                            "end_date" => date('Y-m-d', strtotime('+1 year')),
                                            "added_on" => date('Y-m-d H:i:s')
                                        );
                                        $this->db->insert('student',$array);
                                        $studentID = $this->db->insert_id();
                                        $array2 = array(
                                            'studentID'=>$studentID,
                                            'schoolID'=>$schoolID,
                                            'school_code'=>$_SESSION['code'],
                                            'amount'=>0,
                                            'is_paid'=>'Y',
                                            "start_date" => date('Y-m-d'),
                                            "end_date" => date('Y-m-d', strtotime('+1 year')),
                                            "added_on" => date('Y-m-d H:i:s')
                                        );
                                        $this->db->insert('student_subscription_history',$array2);
                                    }
                                }
                            }
                            $filename = "Unuploaded Students";
                            //$csv = $this->export_csv($unuploaded_report,$filename);
                            header("Content-type: application/csv");
                            header("Content-Disposition: attachment; filename=\"$filename".".csv\"");
                            header("Pragma: no-cache");
                            header("Expires: 0");

                            $handle = fopen('php://output', 'w');

                            foreach ($unupload as $un) {
                                fputcsv($handle, $un);
                            }

                            fclose($handle);
                            /*$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                            redirect(base_url("user/customer"));*/

                        } else{
                            echo "Something went wrong. Please try again later.";
                        }
                    }else{
                        echo "Please Upload a valid file";
                    }

                }
            }else{
                echo "Please Upload a file";
            }
        }
    }
    public function teacher_bulk_import()
    {
        if ($_SESSION['role']=="school"){
            $this->data['title'] = "Teacher Bulk Upload";
            $this->data['subview'] = "member/teacher_bulk";
            $this->load->view("layout",$this->data);
        }
    }
    public function teacher_bulk_import_csv()
    {
        if ($_SESSION['role']=="school" && $_FILES){
            $schoolID = $_SESSION['schoolID'];
            $target_path = "uploads/"; // replace this with the path you are going to save the file to
            $target_dir = "uploads/";
            if(is_array($_FILES))
            {
                foreach($_FILES as $fileKey => $fileVal)
                {
                    $imagename = basename($_FILES["imported_file"]["name"]);
                    $extension = substr(strrchr($_FILES['imported_file']['name'], '.'), 1);
                    if (strtolower($extension == "csv")){
                        $actual_image_name = "teacher".time().".".$extension;
                        move_uploaded_file($_FILES["imported_file"]["tmp_name"],$target_path.$actual_image_name);
                        $file_path =  $target_path.$actual_image_name;
                        if ($this->csvimport->get_array($file_path)) {
                            $csv_array = $this->csvimport->get_array($file_path);
                            $unupload[]=array('TeacherID','Name','Phone','Email','Address','City','State','Pin_code','Password','Remark');
                            foreach ($csv_array as $row) {
                                $school_studentID = trim($row["TeacherID"]);
                                if (empty($school_studentID)){
                                    $unupload[] = array(
                                        "TeacherID" => $row["TeacherID"],
                                        "Name" => $row["Name"],
                                        "Phone" => $row["Phone"],
                                        "Email" => $row["Email"],
                                        "Address" => $row["Address"],
                                        "City" => $row["City"],
                                        "State" => $row["State"],
                                        "Pin_code" => $row["Pin_code"],
                                        "Password" => $row["Password"],
                                        "remark" => "TeacherID is Mandatory"
                                    );
                                }else{
                                    $check_username = $this->db->get_where('teacher',array('username'=>$school_studentID))->row();
                                    if (count($check_username)){
                                        if ($check_username->schoolID == $schoolID){
                                            $unupload[] = array(
                                                "TeacherID" => $row["TeacherID"],
                                                "Name" => $row["Name"],
                                                "Phone" => $row["Phone"],
                                                "Email" => $row["Email"],
                                                "Address" => $row["Address"],
                                                "City" => $row["City"],
                                                "State" => $row["State"],
                                                "Pin_code" => $row["Pin_code"],
                                                "Password" => $row["Password"],
                                                "remark" => "User with this teacherID Already Exist in your School"
                                            );
                                        }else{
                                            $unupload[] = array(
                                                "TeacherID" => $row["TeacherID"],
                                                "Name" => $row["Name"],
                                                "Phone" => $row["Phone"],
                                                "Email" => $row["Email"],
                                                "Address" => $row["Address"],
                                                "City" => $row["City"],
                                                "State" => $row["State"],
                                                "Pin_code" => $row["Pin_code"],
                                                "Password" => $row["Password"],
                                                "remark" => "This teacherID is not available."
                                            );
                                        }
                                    }else{
                                        $array = array(
                                            'schoolID'=>$schoolID,
                                            "username" => $school_studentID,
                                            "school_code" => $_SESSION['code'],
                                            "name" => $row["Name"],
                                            "phone" => $row["Phone"],
                                            "email" => $row["Email"],
                                            "address" => $row["Address"].", ".$row["City"].", ".$row["State"].", ".$row["Pin_code"],
                                            "password" => $this->signin_m->hash($row["Password"]),
                                            'is_active' => 'Y',
                                            'registeredby'=>'bulk',
                                            'authorize_role'=>'teacher',
                                            'approved_on'=>date('Y-m-d H:i:s'),
                                            "added_on" => date('Y-m-d H:i:s')
                                        );
                                        $this->db->insert('teacher',$array);
                                    }
                                }
                            }
                            $filename = "Unuploaded Teachers";
                            //$csv = $this->export_csv($unuploaded_report,$filename);
                            header("Content-type: application/csv");
                            header("Content-Disposition: attachment; filename=\"$filename".".csv\"");
                            header("Pragma: no-cache");
                            header("Expires: 0");

                            $handle = fopen('php://output', 'w');

                            foreach ($unupload as $un) {
                                fputcsv($handle, $un);
                            }

                            fclose($handle);
                            /*$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                            redirect(base_url("user/customer"));*/

                        } else{
                            echo "Something went wrong. Please try again later.";
                        }
                    }else{
                        echo "Please Upload a valid file";
                    }

                }
            }else{
                echo "Please Upload a file";
            }
        }
    }
}