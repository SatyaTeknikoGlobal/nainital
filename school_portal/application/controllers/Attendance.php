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

class Attendance extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("configuration_m");
        $this->load->library('SSP');
    }
    public function index()
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher") {
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->configuration_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            $select = "default_attendance";
            $default = $this->configuration_m->get_single_row('school_config',array('schoolID'=>$schoolID),$select);
            if (count($default)) {
                if ( is_null($default->default_attendance)) {
                    echo "Please Set default attendance type in School Configuration first";
                }else{
                    $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                    $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
                    $this->data['default_attendance'] = $default->default_attendance;
                    $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                    $this->data['title'] = "Student Attendence";
                    $this->data['subview'] = "attendance/student";
                    $this->load->view("layout",$this->data);
                }
                
            }else{
                echo "Please Do School Configuration first";
            }
        }
        
    }
    public function list_students_att($param1="",$param2="",$param3="")
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
                $school = $this->configuration_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            if ($param1 == "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 == "") {
                $table = "(select student.studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 == "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 != "") {
                $table = "(select student.studentID, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }
            $primaryKey = 'studentID';
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
                        $student_edit_button = '<a class = "btn btn-success" target="_blank" href = "'.base_url("attendance/student_view/$d").'">View</a>';
                        return $student_edit_button;
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
    public function teacher_attendance($param1 = "" , $param2 = "")
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher") {
            if ($param1 == "view" && $param2 != "") {
                if ($_SESSION['role'] == "teacher" && $_SESSION['loginUserID'] == $param2)
                {
                    $teacherID = $param2;
                    $monthyear = date("m-Y");
                    $month = date('Y-m');
                    $first_date = $month."-01";
                    $last_date = $month."-".date("t",strtotime($first_date));
                    $select = "schoolID,name";
                    $teacher = $this->configuration_m->get_single_row('teacher',array('teacherID'=>$teacherID),$select);
                    if ($_POST){
                        $monthyear = date("m-Y",strtotime($this->input->post('monthyear')));
                        $month = date("Y-m",strtotime($this->input->post('monthyear')));
                        $first_date = $month."-01";
                        $last_date = $month."-".date("t",strtotime($first_date));
                        $select = "monthyear ,a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12,a13,a14,a15,a16,a17,a18,a19,a20,a21,a22,a23,a24,a25,a26,a27,a28,a29,a30,a31";
                        $attendance = $this->configuration_m->get_single_row('teacher_attendance',array('teacherID'=>$_SESSION['loginUserID'],'monthyear'=>$monthyear,'schoolID'=>$teacher->schoolID),$select);
                        $t = date('t',strtotime($monthyear));
                        $holiday = $this->db->get_where('holiday',array('schoolID'=>$teacher->schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
                        $response[] = array('result'=>'success','end'=>$t,'yearmonth'=>date("Y-m",strtotime($this->input->post('monthyear'))),'attendance'=>$attendance,'holiday'=>$holiday);
                        echo json_encode($response);
                    }else{
                        $this->data['holiday'] = $this->db->get_where('holiday',array('schoolID'=>$teacher->schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
                        $select = "monthyear ,a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12,a13,a14,a15,a16,a17,a18,a19,a20,a21,a22,a23,a24,a25,a26,a27,a28,a29,a30,a31";
                        $this->data['attendance'] = $this->configuration_m->get_single_row('teacher_attendance',array('teacherID'=>$_SESSION['loginUserID'],'monthyear'=>$monthyear,'schoolID'=>$teacher->schoolID),$select);
                        $this->data['title'] = $teacher->name." Attendence";
                        $this->data['subview'] = "attendance/show_teacher_attendance";
                        $this->load->view("layout",$this->data);
                    }
                }elseif ($_SESSION['role'] == "school")
                {
                    $teacherID = $param2;
                    $monthyear = date("m-Y");
                    $month = date('Y-m');
                    $first_date = $month."-01";
                    $last_date = $month."-".date("t",strtotime($first_date));
                    $select = "schoolID,name";
                    $teacher = $this->configuration_m->get_single_row('teacher',array('teacherID'=>$teacherID),$select);
                    if ($_POST){
                        $monthyear = date("m-Y",strtotime($this->input->post('monthyear')));
                        $month = date("Y-m",strtotime($this->input->post('monthyear')));
                        $first_date = $month."-01";
                        $last_date = $month."-".date("t",strtotime($first_date));
                        $select = "monthyear ,a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12,a13,a14,a15,a16,a17,a18,a19,a20,a21,a22,a23,a24,a25,a26,a27,a28,a29,a30,a31";
                        $attendance = $this->configuration_m->get_single_row('teacher_attendance',array('teacherID'=>$teacherID,'monthyear'=>$monthyear,'schoolID'=>$_SESSION['loginUserID']),$select);
                        $t = date('t',strtotime($monthyear));
                        $holiday = $this->db->get_where('holiday',array('schoolID'=>$teacher->schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
                        $response[] = array('result'=>'success','end'=>$t,'yearmonth'=>date("Y-m",strtotime($this->input->post('monthyear'))),'attendance'=>$attendance,'holiday'=>$holiday);
                        echo json_encode($response);
                    }else{
                        $this->data['holiday'] = $this->db->get_where('holiday',array('schoolID'=>$teacher->schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
                        $select = "monthyear ,a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12,a13,a14,a15,a16,a17,a18,a19,a20,a21,a22,a23,a24,a25,a26,a27,a28,a29,a30,a31";
                        $this->data['attendance'] = $this->configuration_m->get_single_row('teacher_attendance',array('teacherID'=>$teacherID,'monthyear'=>$monthyear,'schoolID'=>$_SESSION['loginUserID']),$select);
                        $this->data['title'] = $teacher->name." Attendence";
                        $this->data['subview'] = "attendance/show_teacher_attendance";
                        $this->load->view("layout",$this->data);
                    }
                }else{
                    echo "Permission Denied";
                }
            } else {
                $this->data['title'] = "Teacher Attendence";
                $this->data['subview'] = "attendance/teacher";
                $this->load->view("layout", $this->data);
            }
        }
    }
    public function list_teacher()
    {
        $loginID = $_SESSION['loginUserID'];
        if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher") {
            if ($_SESSION['role'] == "school") {
                $table = "(select teacherID,schoolID,name,email,username,phone,image  FROM teacher where schoolID = $loginID AND is_active = 'Y')as table1";
        }elseif ($_SESSION['role'] == "teacher") {
                $table = "(select teacherID,schoolID,name,email,username,phone,image  FROM teacher where teacherID =  $loginID)as table1";
        }
            $primaryKey = 'teacherID';
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
                array( 'db' => 'username','dt' => 2 ),
                array( 'db' => 'email','dt' => 3 ),
                array( 'db' => 'phone', 'dt' => 4 ),
                array( 'db' => 'teacherID',
                    'dt' => 5,
                    'formatter' => function( $d, $row ) {
                        return "<a href=".base_url('attendance/teacher_attendance/view').'/'.$d." class = 'btn btn-success' target='_blank'>View Attendance</a>";
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
    public function student_view($param1="")
    {
		if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher") {
		    if ($param1 != "")
            {
                $schoolID = $_SESSION['schoolID'];
                $select = "default_attendance";
                $default = $this->configuration_m->get_single_row('school_config',array('schoolID'=>$schoolID),$select);
                $select = "classID,sectionID,name";
                $student = $this->configuration_m->get_single_row('student',array('studentID'=>$param1),$select);

                if (count($default)) {
                    $month = date('Y-m');
                    $first_date = $month."-01";
                    $last_date = $month."-".date("t",strtotime($first_date));
                    if ( is_null($default->default_attendance)) {
                        echo "Please Set default attendance type in School Configuration first";
                    }else{
                        if ($_POST) {
                            $subjectID = $this->input->post('subjectID');
                            $monthyear = date("m-Y",strtotime($this->input->post('monthyear')));
                            $month = date('Y-m',strtotime($this->input->post('monthyear')));
                            $first_date = $month."-01";
                            $last_date = $month."-".date("t",strtotime($first_date));
                            $select = "monthyear ,a1,a2,a3,a4,a5,a6,a7,a8,a9,a10,a11,a12,a13,a14,a15,a16,a17,a18,a19,a20,a21,a22,a23,a24,a25,a26,a27,a28,a29,a30,a31";
                            $array1 = array('schoolID'=>$schoolID,'studentID'=>$param1,'subjectID'=>$subjectID,'monthyear'=>$monthyear);
                            $attendance = $this->configuration_m->get_single_row('student_attendance',$array1,$select);
                            $t = date('t',strtotime($monthyear));
                            $holiday = $this->db->get_where('holiday',array('schoolID'=>$schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
                            $response[] = array('result'=>'success','end'=>$t,'yearmonth'=>date("Y-m",strtotime($this->input->post('monthyear'))),'attendance'=>$attendance,'holiday'=>$holiday);
                            echo json_encode($response);
                        }else{
                            $subjectID = 0;
                            $monthyear = date("m-Y");
                            $array1 = array('schoolID'=>$schoolID,'studentID'=>$param1,'subjectID'=>$subjectID,'monthyear'=>$monthyear);
                            $this->data['attendance'] = $this->configuration_m->get_single_row('student_attendance',$array1);
                            $this->data['default_attendance'] = $default->default_attendance;
                            $select = "subjectID,subject_name,subject_code";
                            $this->data['holiday'] = $this->db->get_where('holiday',array('schoolID'=>$schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
                            $array1 = array('schoolID'=>$schoolID,'classID'=>$student->classID,'sectionID'=>$student->sectionID,'is_active'=>"Y");
                            $this->data['subjects'] = $this->configuration_m->get_multiple_row('subject',$array1,$select);
                            $this->data['title'] = $student->name." Attendence";
                            $this->data['subview'] = "attendance/show_attendance";
                            $this->load->view("layout",$this->data);
                        }

                    }

                }else{
                    echo "Please Do School Configuration first";
                }
            }else{
                echo "Please Enter correct url";
            }

        }else{
		    echo "Permission Denied";
        }
    }
    public function groups($param1 = '',$param2 = ''){
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $groupID = $param2;
                if ($_POST)
                {
                    $array2 = array('group_name'=>$this->input->post('group_name'),
                        'latitude'=>$this->input->post('latitude'),
                        'longitude'=>$this->input->post('longitude'),
                        'modified_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->where(array('groupID'=>$groupID));
                    $this->db->update('student_group',$array2);
                    redirect(base_url('attendance/groups'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array('schoolID'=>$_SESSION['loginUserID'],
                        'group_name'=>$this->input->post('group_name'),
                        'latitude'=>$this->input->post('latitude'),
                        'longitude'=>$this->input->post('longitude'),
                        'added_on' => date("Y-m-d H:i:s"),
                        'modified_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('student_group',$array2);
                    redirect(base_url('attendance/groups'));
                }else{
                    $this->data['groups'] = $this->configuration_m->get_multiple_row('student_group',$array1);
                    $this->data['title'] = "Location Groups";
                    $this->data['subview'] = "attendance/groups";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function allocate_group()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST)
            {
                $this->db->where(array('studentID'=>$this->input->post('studentID'),'schoolID'=>$schoolID));
                $query = $this->db->update('student',array('groupID'=>$this->input->post('groupID')));
                if($query){
                    echo "success";
                }else{
                    echo "failed";
                }
            }else{
                $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                $this->data['batch'] = $this->db->get('batch',array('schoolID'=>$schoolID))->result();
                $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                $this->data['title'] = "Allocate Group (Student)";
                $this->data['subview'] = "attendance/allocate_group";
                $this->load->view("layout",$this->data);
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function list_students($param1="",$param2="",$param3="")
    {
        if ($_SESSION['role']=="school")
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
            $schoolID = $_SESSION['loginUserID'];
            $select = "groupID,group_name";
            $groups = $this->configuration_m->get_multiple_row('student_group',array('schoolID'=>$schoolID),$select);
            if ($param1 == "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 == "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.is_active,'0'))as is_active FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }
            //$table = "(select CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.groupID,'0')) as groups,student.studentID,student.name,student.roll_no,student.phone,student.image,parent.name as parent FROM student LEFT JOIN parent on student.parentID = parent.parentID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2)as table1";
            $primaryKey = 'studentID';
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
                array( 'db' => 'parent', 'dt' => 6 ),
                array( 'db' => 'phone', 'dt' => 7 ),
                array( 'db' => 'groups',
                    'dt' => 8,
                    'formatter' => function( $d, $row) use ($groups){
                        $groups_array = explode(",", $d);
                        //$d = $concession_array[1];
                        $select1 = "<select class='form-control allocate_concession' onchange='alert_selected(".$groups_array[0].")'  id = ".$groups_array[0].">";
                        $select2 = "</select>";
                        $option = "<option value='0'>none</option>";
                        foreach ($groups as $con) {
                            if ($con->groupID == $groups_array[1]) {
                                $option = $option."<option selected = 'selected' value='".$con->groupID."'>".$con->group_name."</option>";
                            }else{
                                $option = $option."<option value='".$con->groupID."'>".$con->group_name."</option>";
                            }

                        }
                        return $select1.$option.$select2;
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
    public function list_teacher_group()
    {
        if ($_SESSION['role'] == "school") {
            $loginID = $_SESSION['loginUserID'];
            $schoolID = $_SESSION['loginUserID'];
            $select = "groupID,group_name";
            $groups = $this->configuration_m->get_multiple_row('student_group',array('schoolID'=>$schoolID),$select);
            $table = "(select CONCAT(COALESCE(teacher.teacherID,'0'),',',COALESCE(teacher.groupID,'0')) as groups,teacherID,schoolID,name,email,username,phone,image  FROM teacher where schoolID = $loginID AND is_active = 'Y')as table1";
            $primaryKey = 'teacherID';
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
                array( 'db' => 'username','dt' => 2 ),
                array( 'db' => 'email','dt' => 3 ),
                array( 'db' => 'phone', 'dt' => 4 ),
                array( 'db' => 'groups',
                    'dt' => 5,
                    'formatter' => function( $d, $row) use ($groups){
                        $groups_array = explode(",", $d);
                        //$d = $concession_array[1];
                        $select1 = "<select class='form-control allocate_concession' onchange='alert_selected(".$groups_array[0].")'  id = ".$groups_array[0].">";
                        $select2 = "</select>";
                        $option = "<option value='0'>none</option>";
                        foreach ($groups as $con) {
                            if ($con->groupID == $groups_array[1]) {
                                $option = $option."<option selected = 'selected' value='".$con->groupID."'>".$con->group_name."</option>";
                            }else{
                                $option = $option."<option value='".$con->groupID."'>".$con->group_name."</option>";
                            }

                        }
                        return $select1.$option.$select2;
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
    public function teacher_allocate_group()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST)
            {
                $this->db->where(array('teacherID'=>$this->input->post('teacherID'),'schoolID'=>$schoolID));
                $query = $this->db->update('teacher',array('groupID'=>$this->input->post('groupID')));
                if($query){
                    echo "success";
                }else{
                    echo "failed";
                }
            }else{
                $this->data['title'] = "Allocate Group (Teacher)";
                $this->data['subview'] = "attendance/allocate_group_teacher";
                $this->load->view("layout",$this->data);
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function list_staff_group()
    {
        if ($_SESSION['role'] == "school") {
            $loginID = $_SESSION['loginUserID'];
            $schoolID = $_SESSION['loginUserID'];
            $select = "groupID,group_name";
            $groups = $this->configuration_m->get_multiple_row('student_group',array('schoolID'=>$schoolID),$select);
            $table = "(select CONCAT(COALESCE(other_staff.staffID,'0'),',',COALESCE(other_staff.groupID,'0')) as groups,staffID,schoolID,name,email,username,phone,image  FROM other_staff where schoolID = $loginID)as table1";
            $primaryKey = 'staffID';
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
                array( 'db' => 'username','dt' => 2 ),
                array( 'db' => 'email','dt' => 3 ),
                array( 'db' => 'phone', 'dt' => 4 ),
                array( 'db' => 'groups',
                    'dt' => 5,
                    'formatter' => function( $d, $row) use ($groups){
                        $groups_array = explode(",", $d);
                        //$d = $concession_array[1];
                        $select1 = "<select class='form-control allocate_concession' onchange='alert_selected(".$groups_array[0].")'  id = ".$groups_array[0].">";
                        $select2 = "</select>";
                        $option = "<option value='0'>none</option>";
                        foreach ($groups as $con) {
                            if ($con->groupID == $groups_array[1]) {
                                $option = $option."<option selected = 'selected' value='".$con->groupID."'>".$con->group_name."</option>";
                            }else{
                                $option = $option."<option value='".$con->groupID."'>".$con->group_name."</option>";
                            }

                        }
                        return $select1.$option.$select2;
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
    public function staff_allocate_group()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST)
            {
                $this->db->where(array('staffID'=>$this->input->post('staffID'),'schoolID'=>$schoolID));
                $query = $this->db->update('other_staff',array('groupID'=>$this->input->post('groupID')));
                if($query){
                    echo "success";
                }else{
                    echo "failed";
                }
            }else{
                $this->data['title'] = "Allocate Group (Non Teaching)";
                $this->data['subview'] = "attendance/allocate_group_staff";
                $this->load->view("layout",$this->data);
            }
        }else{
            echo "Permission Denied";
        }
    }
}