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

class Configuration extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("configuration_m");
        $this->load->model("fcm_m");
    }
    public function index(){
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            $get_conf = $this->configuration_m->get_single_row('school_config',$array1);

            if (count($get_conf)){
                if ($_POST)
                {
                    $this->db->where(array('configID'=>$get_conf->configID));
                    $this->db->update('school_config',$_POST);
                    redirect(base_url("configuration/index"));
                }else{
                    $this->data['is_config'] = "Y";
                    $this->data['configuration'] = $get_conf;
                    $this->data['title'] = "School Setup";
                    $this->data['subview'] = "school/index";
                    $this->load->view("layout",$this->data);
                }
            }else{
                $this->data['is_config'] = "N";
                $this->data['title'] = "School Setup";
                $this->data['subview'] = "school/index";
                $this->load->view("layout",$this->data);
            }

        }

    }
    public function update_logo()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];

            if ($_FILES){
                $target_path = "uploads/logo/";
                foreach($_FILES as $fileKey => $fileVal)
                {
                    $imagename = basename($_FILES["logo"]["name"]);
                    $extension = substr(strrchr($_FILES['logo']['name'], '.'), 1);
                    list($width, $height) = getimagesize($_FILES["logo"]["tmp_name"]);
                    $actual_image_name = "logo".time().$schoolID.".".$extension;
                    /*if ($extension == "png" || $extension == "PNG"){
                        if ($width == $height){*/
                            $check = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                            if (count($check)){
                                if (!is_null($check->logo) || $check->logo != "")
                                {
                                    unlink("uploads/logo/$check->logo");
                                }
                            }
                            move_uploaded_file($_FILES["logo"]["tmp_name"],$target_path.$actual_image_name);
                            $this->db->where(array('schoolID'=>$schoolID));
                            $this->db->update('school_config',array('logo'=>$actual_image_name));
                            $_SESSION['logo'] = $actual_image_name;

                      /*  }
                    }*/
                }
            }
        }
        redirect(base_url("configuration/index"));
    }
    public function batch($param1 = '',$param2 = ''){
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $batchID = $param2;
                if ($_POST)
                {
                    $array2 = array(
                        'is_active'=>$this->input->post('is_active')
                    );
                    $this->db->where(array('batchID'=>$batchID));
                    $this->db->update('batch',$array2);
                    redirect(base_url('configuration/batch'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array('schoolID'=>$_SESSION['loginUserID'],
                        'start_year'=>$this->input->post('start_year'),
                        'end_year'=>$this->input->post('end_year'),
                        'is_active'=>$this->input->post('is_active'),
                        'added_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('batch',$array2);
                    redirect(base_url('configuration/batch'));
                }else{
                    $this->data['batch'] = $this->configuration_m->get_multiple_row('batch',$array1);
                    $this->data['title'] = "Batches (SESSIONS)";
                    $this->data['subview'] = "school/batch";
                    $this->load->view("layout",$this->data);
                }
            }


        }

    }
    public function classes($param1 = '',$param2 = ''){
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $classID = $param2;
                if ($_POST)
                {
                    $array2 = array('class'=>$this->input->post('class'),
                        'is_active'=>$this->input->post('is_active')
                    );
                    $this->db->where(array('classID'=>$classID));
                    $this->db->update('classes',$array2);
                    redirect(base_url('configuration/classes'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array('schoolID'=>$_SESSION['loginUserID'],
                        'class'=>$this->input->post('class'),
                        'is_active'=>$this->input->post('is_active'),
                        'added_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('classes',$array2);
                    redirect(base_url('configuration/classes'));
                }else{
                    $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',$array1);
                    $this->data['title'] = "Classes";
                    $this->data['subview'] = "school/classes";
                    $this->load->view("layout",$this->data);
                }
            }


        }

    }
    public function section($param1 = '',$param2 = ''){
        if ($_SESSION['role'] == "school"){
            $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',array('schoolID'=>$_SESSION['loginUserID'],'is_active'=>"Y"),'classID,class');
            $this->data['teacher'] = $this->configuration_m->get_multiple_row('teacher',array('schoolID'=>$_SESSION['loginUserID'],'is_active'=>"Y"),'teacherID,name');
            $array1 = array('section.schoolID'=>$_SESSION['loginUserID']);

            if ($param1 == 'edit' && $param2 != '')
            {
                $sectionID = $param2;
                if ($_POST)
                {
                    $array2 = array(
                        'classID'=>$this->input->post('classID'),
                        'section'=>$this->input->post('section'),
                        'class_teacherID'=>$this->input->post('class_teacherID'),
                        'is_active'=>$this->input->post('is_active')
                    );
                    $this->db->where(array('sectionID'=>$sectionID));
                    $this->db->update('section',$array2);
                    redirect(base_url('configuration/section'));
                }else{
                    $this->data['section'] = $this->configuration_m->get_single_row('section',array('sectionID'=>$sectionID));
                    $this->data['title'] = "Update Section";
                    $this->data['subview'] = "school/section";
                    $this->load->view("layout",$this->data);
                }

            }else{
                if ($_POST)
                {
                    $array2 = array('section.schoolID'=>$_SESSION['loginUserID'],
                        'classID'=>$this->input->post('classID'),
                        'section'=>$this->input->post('section'),
                        'class_teacherID'=>$this->input->post('class_teacherID'),
                        'is_active'=>$this->input->post('is_active'),
                        'added_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('section',$array2);
                    redirect(base_url('configuration/section'));
                }else{
                    $this->data['section'] = $this->configuration_m->get_section($array1);
                    $this->data['title'] = "Section";
                    $this->data['subview'] = "school/section";
                    $this->load->view("layout",$this->data);
                }
            }


        }

    }
    public function slots($param1 = '',$param2 = '')
    {
        if ($_SESSION['role'] == "school"){
            $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',array('schoolID'=>$_SESSION['loginUserID'],'is_active'=>"Y"),'classID,class');
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $slotID = $param2;
                if ($_POST)
                {
                    $start = strtotime($this->input->post('b_start_time'));
                    $b_min = $this->input->post('b_slot_duration')*$this->input->post('b_lacture');
                    $b_end_time = date("H:i",strtotime("+$b_min minutes" , $start));
                    $b_min = $b_min + $this->input->post('lunch_duration');
                    $a_start_time = date("H:i",strtotime("+$b_min minutes" , $start));
                    $b_min = $b_min + ($this->input->post('a_slot_duration')*$this->input->post('a_lacture'));
                    $a_end_time = date("H:i",strtotime("+$b_min minutes" , $start));
                    $array2 = array('schoolID'=>$_SESSION['loginUserID'],
                        'b_slot_duration'=>$this->input->post('b_slot_duration'),
                        'b_start_time'=>date("H:i",strtotime($this->input->post('b_start_time'))),
                        'b_end_time'=>$b_end_time,
                        'a_slot_duration'=>$this->input->post('a_slot_duration'),
                        'a_start_time'=>$a_start_time,
                        'a_end_time'=>$a_end_time,
                        'is_active'=>$this->input->post('is_active'),
                        'added_on'=>date("Y-m-d H:i:s")
                    );
                    $this->db->select('classID');
                    $slot_info = $this->db->get_where('slots',array('slotID'=>$slotID))->row();
                    $classID = $slot_info->classID;
                    $this->db->select('studentID');
                    $members = $this->db->get_where('student',array('classID'=>$classID,'is_active'=>'Y'))->result();
                    foreach ($members as $m){
                        $this->db->select('device_token');
                        $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                        foreach ($device_info as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"School Timing","msg":"There is a change in school timing.","type":"routine","role":"student"}';
                            $data1 = array("m" => $data1);
                            $this->fcm_m->fcmNotification($device_token, $data1);
                        }
                        $data2 = array(
                            "schoolID"=>$_SESSION['loginUserID'],
                            "userID"=>$m->studentID,
                            "role"=>"student",
                            "title" => "School Timing",
                            "notification"=>"There is a change in school timing",
                            "status"=>1,
                            "added_on"=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('notification',$data2);
                    }
                    $this->db->where(array('slotID'=>$slotID));
                    $this->db->update('slots',$array2);
                    redirect(base_url('configuration/slots'));
                }else{
                    $this->db->select('slots.*,classes.class as class_name');
                    $this->db->join('classes','slots.classID = classes.classID','LEFT');
                    $this->data['slot'] = $this->db->get_where('slots',array('slotID'=>$slotID))->row();
                    $this->data['title'] = "Update Slot";
                    $this->data['subview'] = "school/edit_slot";
                    $this->load->view("layout",$this->data);
                }

            }elseif ($param1 == 'add'){
                if ($_POST)
                {
                    $start = strtotime($this->input->post('b_start_time'));
                    $b_min = $this->input->post('b_slot_duration')*$this->input->post('b_lacture');
                    $b_end_time = date("H:i",strtotime("+$b_min minutes" , $start));
                    $b_min = $b_min + $this->input->post('lunch_duration');
                    $a_start_time = date("H:i",strtotime("+$b_min minutes" , $start));
                    $b_min = $b_min + ($this->input->post('a_slot_duration')*$this->input->post('a_lacture'));
                    $a_end_time = date("H:i",strtotime("+$b_min minutes" , $start));
                    foreach ($this->input->post('classes') as $classID){
                        $check = $this->db->get_where('slots',array('classID'=>$classID))->row();
                        if (count($check)){
                            $array2 = array('schoolID'=>$_SESSION['loginUserID'],
                                'b_slot_duration'=>$this->input->post('b_slot_duration'),
                                'b_start_time'=>date("H:i",strtotime($this->input->post('b_start_time'))),
                                'b_end_time'=>$b_end_time,
                                'a_slot_duration'=>$this->input->post('a_slot_duration'),
                                'a_start_time'=>$a_start_time,
                                'a_end_time'=>$a_end_time,
                                'is_active'=>$this->input->post('is_active'),
                                'added_on'=>date("Y-m-d H:i:s")
                                );
                            $this->db->where(array('classID'=>$classID));
                            $this->db->update('slots',$array2);
                        }else{
                            $array2 = array('schoolID'=>$_SESSION['loginUserID'],
                                'b_slot_duration'=>$this->input->post('b_slot_duration'),
                                'b_start_time'=>date("H:i",strtotime($this->input->post('b_start_time'))),
                                'b_end_time'=>$b_end_time,
                                'a_slot_duration'=>$this->input->post('a_slot_duration'),
                                'a_start_time'=>$a_start_time,
                                'a_end_time'=>$a_end_time,
                                'classID'=>$classID,
                                'is_active'=>$this->input->post('is_active'),
                                'added_on'=>date("Y-m-d H:i:s")
                            );
                           $this->db->insert('slots',$array2);
                        }

                    }
                    redirect(base_url('configuration/slots'));

                }else{

                    $this->data['title'] = "ADD Slot";
                    $this->data['subview'] = "school/add_slot";
                    $this->load->view("layout",$this->data);
                }
            }else{
                $this->data['slots'] = $this->configuration_m->get_slots(array('slots.schoolID'=>$_SESSION['loginUserID']));
                $this->data['title'] = "Slots";
                $this->data['subview'] = "school/slots";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function routine()
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
            $this->data['title'] = "Routine";
            $this->data['subview'] = "school/routine";
            $this->load->view("layout",$this->data);
        }
    }
    public function update_routine($day = "",$lecture = "",$classID = "",$sectionID = "")
    {
        if ($_SESSION['role'] == "school")
        {
            if ($_POST) {
                if ($day != "" && $lecture != "" && $classID != "" && $sectionID != "") {
                    $schoolID = $_SESSION['loginUserID'];
                    $subjectID = $this->input->post('subjectID');
                    $teacherID = $this->input->post('teacherID');
                    $array = array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'day'=>$day,'lecture'=>$lecture);
                    $select = "routineID";
                    $check = $this->configuration_m->get_single_row('routine',$array,$select);
                    if (count($check)) {
                        $this->db->where($array);
                        $this->db->update('routine',array('subjectID'=>$subjectID,'teacherID'=>$teacherID,'modified_on'=>date("Y-m-d H:i:s")));
                    }else{
                        $this->db->insert('routine',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'day'=>$day,'lecture'=>$lecture,'subjectID'=>$subjectID,'teacherID'=>$teacherID,'added_on'=>date("Y-m-d H:i:s"),'modified_on'=>date("Y-m-d H:i:s")));
                    }
                    $this->db->select('device_token');
                    $device_info = $this->db->get_where('user_login',array('userID'=>$teacherID,'role'=>'teacher'))->result();
                    foreach ($device_info as $d){
                        $device_token = $d->device_token;
                        $data1 = '{"notification_type":"text","title":"Routine Updated","msg":"There is a change in routine.","type":"routine","role":"teacher"}';
                        $data1 = array("m" => $data1);
                        $this->fcm_m->fcmNotification($device_token, $data1);
                    }
                    $data2 = array(
                        "schoolID"=>$_SESSION['loginUserID'],
                        "userID"=>$teacherID,
                        "role"=>"teacher",
                        "title" => "Routine Updated",
                        "notification"=>"There is a change in routine",
                        "status"=>1,
                        "added_on"=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert('notification',$data2);
                    $this->db->select('studentID');
                    $members = $this->db->get_where('student',array('classID'=>$classID,'sectionID'=>$sectionID,'is_active'=>'Y'))->result();
                    foreach ($members as $m){
                        $this->db->select('device_token');
                        $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                        foreach ($device_info as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"Routine Updated","msg":"There is a change in routine.","type":"routine","role":"student"}';
                            $data1 = array("m" => $data1);
                            $this->fcm_m->fcmNotification($device_token, $data1);
                        }
                        $data2 = array(
                            "schoolID"=>$_SESSION['loginUserID'],
                            "userID"=>$m->studentID,
                            "role"=>"student",
                            "title" => "Routine Updated",
                            "notification"=>"There is a change in routine",
                            "status"=>1,
                            "added_on"=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('notification',$data2);
                    }
                    echo "success";
                }
            }
            
        }
    }
    public function get_sectionbyclass()
    {
        if ($_POST){
            $schoolID = $_SESSION['loginUserID'];
            $classID = $this->input->post('classID');
            $section = $this->db->get_where('section',array('classID'=>$classID , 'schoolID'=>$schoolID))->result();
            echo json_encode($section);
        }
    }
    public function routine_getbysectionclass()
    {
        if ($_POST)
        {
            $schoolID = $_SESSION['loginUserID'];
            $classID = $this->input->post('classID');
            $sectionID = $this->input->post('sectionID');
            $slot_array = array();
            $subject_select = "subjectID,subject_name,subject_code";
            $subject = $this->configuration_m->get_multiple_row('subject',array('sectionID'=>$sectionID,'classID'=>$classID , 'schoolID'=>$schoolID,'is_active'=>'Y'),$subject_select);
            $teacher_select = "teacherID,name";
            $teacher = $this->configuration_m->get_multiple_row('teacher',array('schoolID'=>$schoolID,'is_active'=>'Y'),$teacher_select);
            $slot = $this->db->get_where('slots',array('classID'=>$classID , 'schoolID'=>$schoolID))->row();
            if (count($slot))
            {
                $b_start_time = $slot->b_start_time;
                $b_end_time = $slot->b_end_time;
                $b_slot_duration = $slot->b_slot_duration;
                $a_start_time = $slot->a_start_time;
                $a_end_time = $slot->a_end_time;
                $a_slot_duration = $slot->a_slot_duration;
                $count = 0;
                while (strtotime("+$b_slot_duration minutes" , strtotime($b_start_time)) <= strtotime($b_end_time))
                {
                    $count++;
                    $start_time = $b_start_time;
                    $b_start_time = date("H:i",strtotime("+$b_slot_duration minutes" , strtotime($b_start_time)));
                    $slot_array[] = array('lecture'=>$count,'start_time'=>$start_time,'end_time'=>$b_start_time,'type'=>'before');
                }
                $count++;
                $slot_array[] = array('lecture'=>$count,'start_time'=>$b_end_time,'end_time'=>$a_start_time,'type'=>'interval');
                while (strtotime("+$a_slot_duration minutes" , strtotime($a_start_time)) <= strtotime($a_end_time))
                {
                    $count++;
                    $start_time = $a_start_time;
                    $a_start_time = date("H:i",strtotime("+$a_slot_duration minutes" , strtotime($a_start_time)));
                    $slot_array[] = array('lecture'=>$count,'start_time'=>$start_time,'end_time'=>$a_start_time,'type'=>'after');
                }

            }
            $days = array('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY');
            $this->db->select('routine.routineID,routine.day,routine.lecture,routine.teacherID,routine.subjectID,teacher.name as teacher_name,subject.subject_name');
            $this->db->join('teacher','routine.teacherID = teacher.teacherID','LEFT');
            $this->db->join('subject','routine.subjectID = subject.subjectID','LEFT');
            $routine_get = $this->db->get_where('routine',array('routine.sectionID'=>$sectionID,'routine.classID'=>$classID ,'routine.schoolID'=>$schoolID))->result();
            $response[] = array('response'=>'success','slots'=>$slot_array,'routine'=>$routine_get,'days'=>$days,'subjects'=>$subject,'teachers'=>$teacher);
            print_r(json_encode($response));
        }
    }
    public function subject()
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            $this->data['classes'] = $this->configuration_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
            $this->data['title'] = "Subject";
            $this->data['subview'] = "school/subject";
            $this->load->view("layout",$this->data);
        }
    }
    public function get_subjects()
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            $classID = $this->input->post('classID');
            $sectionID = $this->input->post('sectionID');
            $array1 = array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID);
            $subject = $this->configuration_m->get_multiple_row('subject',$array1);
            echo json_encode($subject);
        }

    }
    public function add_subject()
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            $classID = $this->input->post('add_classID');
            $sectionID = $this->input->post('add_sectionID');
            $subject_name = $this->input->post('subject_name');
            $subject_code = $this->input->post('subject_code');
            $type = $this->input->post('type');
            $status = $this->input->post('is_active');
            $target_path = "uploads/subject/"; // replace this with the path you are going to save the file to
            $target_dir = "uploads/subject/";
            $actual_image_name = "default.pdf";
            if(file_exists($_FILES["syllabus"]["tmp_name"]))
            {
                $target_file = $target_dir . basename($_FILES["syllabus"]["name"]);
                foreach($_FILES as $fileKey => $fileVal)
                {
//                    $imagename = basename($_FILES["syllabus"]["name"]);
                    $extension = substr(strrchr($_FILES['syllabus']['name'], '.'), 1);
                    if ($extension == "pdf" || $extension == "PDF")
                    {
                        $actual_image_name = "syllabus".time().".".$extension;
                        move_uploaded_file($_FILES["syllabus"]["tmp_name"],$target_path.$actual_image_name);
//                        $this->db->where(array('subjectID'=>$subjectID));
//                        $this->db->update('subject',array('syllabus'=>$actual_image_name));
                    }

                }

            }
            foreach ($sectionID as $sectionID){
                $this->db->insert('subject',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'subject_name'=>$subject_name,'subject_code'=>$subject_code,'type'=>$type,'syllabus'=>$actual_image_name,'is_active'=>$status,'added_on'=>date("Y-m-d H:i:s")));
            }
            redirect(base_url('configuration/subject'));

        }
    }
    public function update_subject($param = "")
    {
        if ($_SESSION['role'] == "school")
        {
            if ($param != "")
            {
                $subjectID = $param;
                $schoolID = $_SESSION['loginUserID'];
                $subject_name = $this->input->post('subject_name');
                $subject_code = $this->input->post('subject_code');
                $type = $this->input->post('type');
                $status = $this->input->post('is_active');
                $this->db->where(array('subjectID'=>$subjectID));
                $this->db->update('subject',array('schoolID'=>$schoolID,'subject_name'=>$subject_name,'subject_code'=>$subject_code,'type'=>$type,'is_active'=>$status));

                $target_path = "uploads/subject/"; // replace this with the path you are going to save the file to
                $target_dir = "uploads/subject/";
                if(file_exists($_FILES["syllabus"]["tmp_name"]))
                {
                    foreach($_FILES as $fileKey => $fileVal)
                    {
                        $extension = substr(strrchr($_FILES['syllabus']['name'], '.'), 1);
                        if ($extension == "pdf" || $extension == "PDF")
                        {
                            $actual_image_name = "syllabus".time().".".$extension;
                            move_uploaded_file($_FILES["syllabus"]["tmp_name"],$target_path.$actual_image_name);
                            $this->db->where(array('subjectID'=>$subjectID));
                            $this->db->update('subject',array('syllabus'=>$actual_image_name));
                        }

                    }

                }
                echo "success";
                //redirect(base_url("configuration/subject"));

            }
        }

    }
    public function get_students()
    {
        if ($_SESSION['role'] == "school" || $_SESSION['role'] == "teacher")
        {
            if ($_POST) {
                $sectionID = $this->input->post('sectionID');
                $classID = $this->input->post('classID');
                $array = array('student.sectionID'=>$sectionID,'student.classID'=>$classID,'student.is_active'=>"Y");
                $students = $this->configuration_m->get_students_atten('student',$array);
                echo json_encode($students);

            }
        }
    }
    public function reset_psw()
    {
        if ($_SESSION['role'] == "school") {
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST) {
                $role = $this->input->post("role");
                if ($role == "teacher" || $role == "student" || $role == "other_staff") {
                    $id = $this->input->post("id");
                    $new_password = $this->input->post("new_password");
                    $confirm_password = $this->input->post("confirm_password");
                    if ($new_password != "" && $confirm_password != "" && $new_password == $confirm_password) {
                        $change = $this->signin_m->reset($role,$id,$schoolID,$new_password);
                        if ($change) {
                            echo "success";
                        }else{
                            echo "failure";
                        }
                    }
                }
            }
        }
    }
    public function contact_list($param1 = "",$param2="")
    {
        if (strtolower($_SESSION['role']=='school'))
        {
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $contactID = $param2;
                if ($_POST)
                {
                    $array2 = array(
                        'name'=>$this->input->post('name'),
                        'designation'=>$this->input->post('designation'),
                        'phone'=>$this->input->post('phone'),
                        'email'=>$this->input->post('email'),
                        'is_active'=>$this->input->post('is_active')
                    );
                    $this->db->where(array('contactID'=>$contactID));
                    $this->db->update('school_contacts',$array2);
                    redirect(base_url('configuration/contact_list'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array(
                        'schoolID'=>$_SESSION['loginUserID'],
                        'name'=>$this->input->post('name'),
                        'designation'=>$this->input->post('designation'),
                        'phone'=>$this->input->post('phone'),
                        'email'=>$this->input->post('email'),
                        'is_active'=>$this->input->post('is_active'),
                        'added_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('school_contacts',$array2);
                    redirect(base_url('configuration/contact_list'));
                }else{
                    $this->data['contact'] = $this->configuration_m->get_multiple_row('school_contacts',$array1);
                    $this->data['title'] = "Contact Directory";
                    $this->data['subview'] = "school/contact";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function holiday($param1 = "",$param2="")
    {
        if (strtolower($_SESSION['role'])=='school'){
            $schoolID = $_SESSION['schoolID'];
            if ($param1 == 'edit' && $param2 != "" && $_POST){
                $holidayID = $param2;
                $array1 = array(
                    'title'=>$this->input->post('title'),
                    'description'=>$this->input->post('description'),
                    'date'=>date("Y-m-d",strtotime($this->input->post('date'))),
                    'status'=>$this->input->post('status'),
                    'modified_on'=>date("Y-m-d H:i:s")
                );
                $this->db->where(array('holidayID'=>$holidayID,'schoolID'=>$schoolID));
                $this->db->update('holiday',$array1);
                redirect(base_url("configuration/holiday"));
            }elseif($param1 == 'delete' && $param2 != ""){
                $holidayID = $param2;
                $this->db->where(array('holidayID'=>$holidayID,'schoolID'=>$schoolID));
                $this->db->delete('holiday');
                redirect(base_url("configuration/holiday"));
            }else{
                if ($_POST){
                    $from = strtotime($this->input->post('from'));
                    $to = strtotime($this->input->post('to'));
                    while ($from <= $to){
                        $array1 = array(
                            'schoolID'=>$schoolID,
                            'title'=>$this->input->post('title'),
                            'description'=>$this->input->post('description'),
                            'date'=>date("Y-m-d",$from),
                            'status'=>$this->input->post('status'),
                            'added_on'=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('holiday',$array1);
                        $from = $from + 86400;
                    }
                    redirect(base_url("configuration/holiday"));
                }else{
                    $this->db->order_by('date');
                    $this->data['holidays'] = $this->db->get_where('holiday',array('schoolID'=>$schoolID))->result();
                    $this->data['title'] = "Holidays";
                    $this->data['subview'] = "school/holiday";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            redirect(base_url('dashboard/index'));
        }
    }
    public function announcement($param1 = "",$param2="")
    {
        if (strtolower($_SESSION['role'])=='school'){
            $schoolID = $_SESSION['schoolID'];
            if ($param1 == 'edit' && $param2 != "" && $_POST){
                $noticeID = $param2;
                $array1 = array(
                    'title'=>$this->input->post('title'),
                    'notice'=>$this->input->post('notice')
                );
                $this->db->where(array('noticeID'=>$noticeID,'schoolID'=>$schoolID));
                $this->db->update('notice',$array1);
                redirect(base_url("configuration/announcement"));
            }elseif($param1 == 'delete' && $param2 != ""){
                $noticeID = $param2;
                $this->db->where(array('noticeID'=>$noticeID,'schoolID'=>$schoolID));
                $this->db->delete('notice');
                redirect(base_url("configuration/announcement"));
            }else{
                if ($_POST){
                    $array1 = array(
                        'schoolID'=>$schoolID,
                        'title'=>$this->input->post('title'),
                        'notice'=>$this->input->post('notice'),
                        'added_on'=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert('notice',$array1);
                    redirect(base_url("configuration/announcement"));
                }else{
                    $this->db->order_by('noticeID','DESC');
                    $this->data['notice'] = $this->db->get_where('notice',array('schoolID'=>$schoolID))->result();
                    $this->data['title'] = "Announcements / Notice";
                    $this->data['subview'] = "school/announcements";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            redirect(base_url('dashboard/index'));
        }
    }
}