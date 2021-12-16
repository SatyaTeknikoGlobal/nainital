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

class Exam extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("exam_m");
        $this->load->library('SSP');
        $this->load->model("fcm_m");
    }
    public function index()
    {
        if($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST) {
                foreach ($this->input->post('classes') as $classID) {
                    $exam_date = date('Y-m-d', strtotime($this->input->post('exam_date')));
                    $section = $this->db->query("SELECT GROUP_CONCAT(`sectionID`) as `sectionarray` FROM section where `classID`='$classID' AND `schoolID` = '$schoolID' AND `is_active` = 'Y' group by `classID`")->row();
                    if (count($section)){
                        $select = "exam.examID";
                        $check = $this->exam_m->get_single_row('exam', array('schoolID' => $schoolID, 'exam_class' =>$classID,'exam_date'=>$exam_date),$select);
                        if (count($check)){
                            $exam_array = array(
                                'exam_title' => $this->input->post('exam_title'),
                                'exam_note' => $this->input->post('exam_desc'),
                                'exam_status' => 1,
                                'exam_class' => $classID,
                                'exam_section' => $section->sectionarray,
                                'on_date' => date("Y-m-d H:i:s")
                            );
                            $this->db->where(array('examID'=>$check->examID));
                            $this->db->update('exam',$exam_array);
                        }else{
                            $exam_array = array(
                                'schoolID' => $schoolID,
                                'exam_title' => $this->input->post('exam_title'),
                                'exam_note' => $this->input->post('exam_desc'),
                                'exam_status' => 1,
                                'exam_class' => $classID,
                                'exam_section' => $section->sectionarray,
                                'exam_date' => $exam_date,
                                'on_date' => date("Y-m-d H:i:s")
                            );
                            $this->db->insert('exam',$exam_array);
                        }
                    }
                }
                redirect(base_url('exam'));
            }else{
                $this->data['classes'] = $this->exam_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                $this->data['title'] = "Exams";
                $this->data['subview'] = "exam/index";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function list_exam()
    {
        if($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            $table = "(SELECT exam.examID, exam.exam_title, exam.exam_note, exam.exam_status, CONCAT(`exam`.`examID`,',' , `exam`.`exam_status`) as id_status, exam.exam_date, exam.on_date, classes.class FROM `exam` LEFT JOIN classes ON exam.exam_class = classes.classID WHERE exam.schoolID='$schoolID')as table1";

            $primaryKey = 'examID';
            if ($_SESSION['role'] == "school") {
                $columns = array(
                    array('db' => 'examID', 'dt' => 0),
                    array('db' => 'exam_title', 'dt' => 1),
                    array('db' => 'exam_note', 'dt' => 2),
                    array('db' => 'exam_date',
                        'dt' => 3,
                        'formatter' => function ($d, $row) {
                            return date('d M y',strtotime($d));
                        }
                    ),
                    array('db' => 'class', 'dt' => 4),
                    array('db' => 'id_status',
                        'dt' => 5,
                        'formatter' => function ($d, $row) {
                            $array = explode(",",$d);
                            $d = $array[0];
                            if ($array[1] == 1){
                                $student_edit_button = '<p><a class = "btn btn-primary" title="Add Result" href = "' . base_url("exam/add_result/$d") . '"><i class="fa fa-plus"></i></a>&nbsp;<a class = "btn btn-success" title="Announce Result" onclick="return confirm(\'Are you sure want to announce the result of this exam ?\');" href = "' . base_url("exam/announce/$d") . '"><i class="fa fa-volume-up"></i></a></p>';
                            }else{
                                $student_edit_button = '<p><a class = "btn btn-primary" title="Add Result" href = "' . base_url("exam/add_result/$d") . '"><i class="fa fa-plus"></i></a>&nbsp;<a class = "btn btn-success" title="Delay Result" onclick="return confirm(\'Are you sure want to delay the result of this exam ?\');" href = "' . base_url("exam/delay/$d") . '"><i class="fa fa-volume-off"></i></a></p>';
                            }

                            return $student_edit_button;
                        }
                    ),
                    array('db' => 'examID',
                        'dt' => 6,
                        'formatter' => function ($d, $row) {
                            $student_edit_button = '<p><a class = "btn btn-danger" title="Edit Exam" href = "' . base_url("exam/edit/$d") . '"><i class="fa fa-edit"></i></a>&nbsp;<a class = "btn btn-success" title="Cancel Exam" onclick="return confirm(\'Are you sure want to delete this ?\');" href = "' . base_url("exam/delete/$d") . '"><i class="fa fa-times"></i></a>&nbsp;<a class = "btn btn-primary" title="Exam Routine" href = "' . base_url("exam/exam_routine/$d") . '" target=_blank><i class="fa fa-arrow-alt-circle-right"></i></a></p>';
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
    }
    public function add_result($param="")
    {
        if ($param != ""){
            if ($_SESSION['role'] == "school"){
                $examID = $param;
                $schoolID = $_SESSION['loginUserID'];
                $exam = $this->exam_m->get_single_row('exam',array('examID'=>$examID,'schoolID'=>$schoolID));
                $this->data['exam'] = $exam;
                $this->data['classes'] = $this->exam_m->get_single_row('classes',array('classID'=>$exam->exam_class,'schoolID'=>$schoolID));
                $this->data['section'] = explode(',',$exam->exam_section);
                $this->data['title'] = "ADD Exams Result";
                $this->data['subview'] = "exam/add_result";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function get_subjects()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                $sectionID = $this->input->post('sectionID');
                $select = "subjectID,subject_name,subject_code";
                $subject = $this->exam_m->get_multiple_row('subject',array('sectionID'=>$sectionID,'schoolID'=>$schoolID,'is_active'=>'Y'),$select);
                $response[] = array('result'=>'success','subject'=>$subject);
                echo json_encode($response);
            }
        }
    }
    public function check_result()
    {
        if ($_SESSION['role']== "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                $sectionID = $this->input->post('sectionID');
                $subject = $this->input->post('subject');
                $examID = $this->input->post('examID');
                $student = $this->db->query("SELECT student.studentID,student.name,student.roll_no,student.sectionID,exam_result.exam_result_id,exam_result.examID,exam_result.subject,exam_result.mark_obtain,exam_result.total_mark from student LEFT JOIN exam_result ON (student.studentID = exam_result.studentID AND exam_result.subject = '$subject' AND exam_result.examID = '$examID') WHERE student.sectionID = '$sectionID' AND student.schoolID = $schoolID AND student.is_active = 'Y' ORDER BY student.roll_no")->result();
                $response[] = array('result'=>'success','student'=>$student);
                echo json_encode($response);
            }
        }
    }
    public function add_student_result()
    {
        if ($_SESSION['role']== "school") {
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST) {
                $examID = $this->input->post("examID");
                $total_marks = $this->input->post("total_marks");
                $subject = $this->input->post("subject");
                $inputs = $this->input->post("marks");
                $explode = explode("$" , $inputs);
                foreach ($explode as $key => $value) {
                    if($value == "") {
                        break;
                    } else {
                        $ar_exp = explode(":", $value);
                        $ex_array= array('studentID'=>$ar_exp[0],'mark_obtain'=>$ar_exp[1],'examID'=>$examID,'subject'=>$subject,'total_mark'=>$total_marks,'on_date'=>date("Y-m-d H:i:s"));
                        $check = $this->exam_m->get_single_row('exam_result',array('examID'=>$examID,'studentID'=>$ar_exp[0],'subject'=>$subject));
                        if (count($check)){
                            $this->db->where(array('examID'=>$examID,'studentID'=>$ar_exp[0],'subject'=>$subject));
                            $this->db->update('exam_result',$ex_array);
                        }else{
                            $this->db->insert('exam_result',$ex_array);
                        }
                    }
                }
                echo "success";
            }
        }
    }
    public function delete($param = "")
    {
        if (strtolower($_SESSION['role']) == 'school')
        {
            if ($param != "")
            {
                $schoolID = $_SESSION['loginUserID'];
                $examID = $param;
                $this->db->delete('exam',array('examID'=>$examID,'schoolID'=>$schoolID));
            }
        }
        redirect(base_url('exam/index'));
    }
    public function announce($param = "")
    {
        if (strtolower($_SESSION['role']) == 'school')
        {
            if ($param != "")
            {
                $schoolID = $_SESSION['loginUserID'];
                $examID = $param;
                $this->db->where(array('examID'=>$examID,'schoolID'=>$schoolID));
                $this->db->update('exam',array('exam_status'=>0));
                $exam_info = $this->db->get_where('exam',array('examID'=>$examID,'schoolID'=>$schoolID))->row();
                $classID = $exam_info->exam_class;
                $sections = $exam_info->exam_section;
                $section_array = explode(",",$sections);
                foreach ($section_array as $sectionID){
                    $this->db->select('studentID');
                    $members = $this->db->get_where('student',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'is_active'=>'Y'))->result();
                    foreach ($members as $m){
                        $this->db->select('device_token');
                        $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                        foreach ($device_info as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"Exam Result","msg":"Result of '.$exam_info->exam_title.' exam has been announced.","type":"exam","role":"student"}';
                            $data1 = array("m" => $data1);
                            $this->fcm_m->fcmNotification($device_token, $data1);
                        }
                        $data2 = array(
                            "schoolID"=>$schoolID,
                            "userID"=>$m->studentID,
                            "role"=>"student",
                            "title" => "Exam Result",
                            "notification"=>"Result of $exam_info->exam_title exam has been announced.",
                            "status"=>1,
                            "added_on"=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('notification',$data2);
                    }
                }
            }
        }
        redirect(base_url('exam/index'));
    }
    public function delay($param = "")
    {
        if (strtolower($_SESSION['role']) == 'school')
        {
            if ($param != "")
            {
                $schoolID = $_SESSION['loginUserID'];
                $examID = $param;
                $this->db->where(array('examID'=>$examID,'schoolID'=>$schoolID));
                $this->db->update('exam',array('exam_status'=>1));
                $exam_info = $this->db->get_where('exam',array('examID'=>$examID,'schoolID'=>$schoolID))->row();
                $classID = $exam_info->exam_class;
                $sections = $exam_info->exam_section;
                $section_array = explode(",",$sections);
                foreach ($section_array as $sectionID){
                    $this->db->select('studentID');
                    $members = $this->db->get_where('student',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'is_active'=>'Y'))->result();
                    foreach ($members as $m){
                        $this->db->select('device_token');
                        $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                        foreach ($device_info as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"Exam Result","msg":"Result of '.$exam_info->exam_title.' exam has been delayed.","type":"exam","role":"student"}';
                            $data1 = array("m" => $data1);
                            $not = $this->fcm_m->fcmNotification($device_token, $data1);
                            //var_dump($not);
                        }
                        $data2 = array(
                            "schoolID"=>$schoolID,
                            "userID"=>$m->studentID,
                            "role"=>"student",
                            "title" => "Exam Result",
                            "notification"=>"Result of $exam_info->exam_title exam has been delayed.",
                            "status"=>1,
                            "added_on"=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('notification',$data2);
                    }
                }
            }
        }
        redirect(base_url('exam/index'));
    }
    public function edit($param = "")
    {
        if (strtolower($_SESSION['role']) == 'school')
        {
            if ($param != "")
            {
                if ($_POST){
                    $exam_details = $this->db->get_where('exam',array('examID'=>$param,'schoolID'=>$_SESSION['loginUserID']))->row();
                    $section = $this->db->get_where('section',array('classID'=>$exam_details->exam_class,'schoolID'=>$_SESSION['loginUserID']))->result();
                    $sectionID = array();
                    foreach ($section as $s){
                        array_push($sectionID,$s->sectionID);
                    }
                    $exam_title = $this->input->post('exam_title');
                    $exam_desc = $this->input->post('exam_desc');
                    $exam_date = date("Y-m-d",strtotime($this->input->post('exam_date')));
                    $this->db->where(array('examID'=>$param,'schoolID'=>$_SESSION['loginUserID']));
                    $this->db->update('exam',array('exam_title'=>$exam_title,'exam_note'=>$exam_desc,'exam_date'=>$exam_date,'exam_section'=>implode(",",$sectionID)));
                    redirect('exam/index');
                }else{
                    $this->db->join('classes','exam.exam_class = classes.classID','LEFT');
                    $this->data['exam'] = $this->db->get_where('exam',array('exam.examID'=>$param,'exam.schoolID'=>$_SESSION['loginUserID']))->row();
                    $this->data['title'] = "Edit Exam";
                    $this->data['subview'] = "exam/edit";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function result()
    {
        if (strtolower($_SESSION['role']) == 'school' || strtolower($_SESSION['role']) == 'teacher')
        {
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->exam_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            $this->data['classes'] = $this->exam_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
            $this->data['title'] = "Exam Result";
            $this->data['subview'] = "exam/select_student";
            $this->load->view("layout",$this->data);
        }
    }
    public function result_view($param = "")
    {
        if (strtolower($_SESSION['role']) == 'school' || strtolower($_SESSION['role']) == 'teacher')
        {
            if($_SESSION['role'] == "school"){
                $schoolID = $_SESSION['loginUserID'];
            }elseif ($_SESSION['role'] == "teacher"){
                $school = $this->exam_m->get_single_row('teacher',array('teacherID'=>$_SESSION['loginUserID']),'schoolID');
                $schoolID = $school->schoolID;
            }
            if ($param != "")
            {
                $this->db->select("student.studentID,student.name,student.image,classes.classID,section.sectionID,classes.class,section.section");
                $this->db->join('classes','student.classID = classes.classID','LEFT');
                $this->db->join('section','student.sectionID = section.sectionID','LEFT');
                $student = $this->db->get_where('student',array('student.studentID'=>$param,'student.schoolID'=>$schoolID))->row();
                $exam = array();
                $exam_result = array();
                $subject = array();
                if (count($student)){
                    $exam = $this->db->query("SELECT `examID`, `exam_title`, `exam_date` FROM `exam` WHERE `schoolID`='$schoolID' AND `exam_class`='$student->classID' AND FIND_IN_SET('$student->sectionID',`exam_section`) ORDER BY exam.exam_date ASC ")->result();
                    $exam_result = $this->db->query("SELECT * from exam_result where exam_result.studentID = $param")->result();
                    $subject = $this->db->query("SELECT subject_name FROM `subject` WHERE sectionID = $student->sectionID AND schoolID = $schoolID AND is_active = 'Y' ORDER BY subject_name ASC")->result();
                }
                $this->data['subject'] = $subject;
                $this->data['student'] = $student;
                $this->data['exam'] = $exam;
                $this->data['exam_result'] = $exam_result;
                $this->data['title'] = "View Result";
                $this->data['subview'] = "exam/result";
                $this->load->view("layout",$this->data);

            }
        }
    }
    public function exam_routine($param1 = "")
    {
        if ($_SESSION['role'] == 'school' && $param1 != "" ){
            $schoolID = $_SESSION['schoolID'];
            $examID = $param1;
            if ($_POST){
                $subjectID = $this->input->post('subjectID');
                $from_date = date("Y-m-d H:i:s",strtotime($this->input->post('from_date')));
                $to_date = date("Y-m-d H:i:s",strtotime($this->input->post('to_date')));
                $array = array(
                    'examID'=>$examID,
                    'subjectID'=>$subjectID,
                    'from_date'=>$from_date,
                    'to_date'=>$to_date,
                    'added_on'=>date("Y-m-d H:i:s"),
                    'modified_on'=>date("Y-m-d H:i:s")
                );
                $this->db->insert('exam_routine',$array);
                redirect(base_url("exam/exam_routine/$examID"));
            }else{
                $this->db->join('classes','exam.exam_class = classes.classID','LEFT');
                $exam_info = $this->db->get_where('exam',array('examID'=>$examID,'exam.schoolID'=>$schoolID))->row();
                if (count($exam_info)){
                    $exam_class = $exam_info->exam_class;
                }else{
                    $exam_class = 0;
                }
                $this->data['subjects']= $this->db->get_where('subject',array('schoolID'=>$schoolID,'classID'=>$exam_class))->result();
                $this->db->join('subject','exam_routine.subjectID = subject.subjectID','LEFT');
                $this->data['routine'] = $this->db->get_where('exam_routine',array('schoolID'=>$schoolID,'examID'=>$examID))->result();
                $this->data['exam'] = $exam_info;
                $this->data['title'] = "Exam Routine for $exam_info->exam_title ( $exam_info->class )";
                $this->data['subview'] = "exam/routine";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function exam_routine_action($param1 = "", $param2 = "",$param3 = "")
    {
        if ($_SESSION['role'] == 'school' && $param1 != "" && $param2 != "" && $param3 != ""){
            $examID = $param3;
            $schoolID = $_SESSION['schoolID'];
            $eroutineID = $param2;
            if($param1 == "edit" && $param2 != "" && $_POST)
            {
                $subjectID = $this->input->post('subjectID');
                $from_date = date("Y-m-d H:i:s",strtotime($this->input->post('from_date')));
                $to_date = date("Y-m-d H:i:s",strtotime($this->input->post('to_date')));
                $array = array(
                    'examID'=>$examID,
                    'subjectID'=>$subjectID,
                    'from_date'=>$from_date,
                    'to_date'=>$to_date,
                    'modified_on'=>date("Y-m-d H:i:s")
                );
                $this->db->where(array('eroutineID'=>$eroutineID));
                $this->db->update('exam_routine',$array);
                redirect(base_url("exam/exam_routine/$examID"));
            }elseif ($param1 == "delete"){
                $this->db->where(array('eroutineID'=>$eroutineID));
                $this->db->delete('exam_routine');
                redirect(base_url("exam/exam_routine/$examID"));
            }
        }
    }
    public function result_bulk_upload($param1 = "")
    {
        if($_SESSION['role']=='school' && $_POST && $param1 != ""){
            $schoolID = $_SESSION['schoolID'];
            $examID = $param1;
            $total_marks = $this->input->post("max");
            $subject = $this->input->post("subject");
            $sectionID = $this->input->post("sectionID");
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
                            foreach ($csv_array as $row) {
                                $this->db->select('studentID');
                                $student = $this->db->get_where('student', array('sectionID' => $sectionID, 'schoolID' => $schoolID, 'roll_no' => $row["Roll_no"]))->row();
                                if (count($student)) {
                                    $studentID = $student->studentID;
                                    $array = array(
                                        "examID" => $examID,
                                        "studentID" => $studentID,
                                        "total_mark" => $total_marks,
                                        "subject" => $subject,
                                        "mark_obtain" => $row["marks"],
                                        "on_date" => date("Y-m-d H:i:s")
                                    );
                                    $check = $this->exam_m->get_single_row('exam_result', array('examID' => $examID, 'studentID' => $studentID, 'subject' => $subject));
                                    if (count($check)) {
                                        $this->db->where(array('examID' => $examID, 'studentID' => $studentID, 'subject' => $subject));
                                        $this->db->update('exam_result', $array);
                                    } else {
                                        $this->db->insert('exam_result', $array);
                                    }
                                }
                            }
                            redirect(base_url("exam/index"));
                        }else{
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
