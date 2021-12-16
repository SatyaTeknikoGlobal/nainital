<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -----------------------------------------------------
| PRODUCT NAME:     MENTOR ERP
| -----------------------------------------------------
| AUTHOR:           SUNIL THAKUR (http://sunilthakur.in)
| -----------------------------------------------------
| EMAIL:            cuel.skt@gmail.in
| -----------------------------------------------------
| COPYRIGHT:        RESERVED BY TEKNIKOGLOBAL
| -----------------------------------------------------
| WEBSITE:          https://www.teknikoglobal.com
| -----------------------------------------------------
*/

class Webservice extends CI_Controller {
    function __construct () {
        parent::__construct();
        $this->load->model("webservice_m");
    }
    public function hash($string) {
        return hash("sha512", $string . config_item("encryption_key"));
    }
    public function generate_random_password($length = 10) {
        $numbers = range('0','9');
        $alphabets = range('A','Z');

        //$additional_characters = array('_','.');
        $final_array = array_merge($alphabets,$numbers);
        //$final_array = array_merge($numbers);
        $password = '';

        while($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }

        return $password;
    }
    public function send_otp()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $mobile = $data['phone'];
        $otp = $data['otp'];

        $message = $otp." is your authentication code to register.";

        $message = urlencode($message);
        $result = $this->send_sms($mobile,$message);
        
        // $dd = 'loginID=vibhastek&password=123456&mobile='.$mobile.'&text='.$message.'&senderid=MENTOR&route_id=2&Unicode=0';
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_RETURNTRANSFER => 1,
        //     CURLOPT_URL => 'http://198.24.149.4/API/pushsms.aspx?'.$dd,
        // ));
        // $result = curl_exec($curl);

        $response[] = array('result'=>'success','otp'=>$otp, 'res'=>$result);
        echo json_encode($response);
    }
    public function send_sms($mobile, $message){
        $sender = "GRNMAN";
        $message = urlencode($message);

        $msg = "sender=".$sender."&route=4&country=91&message=".$message."&mobiles=".$mobile."&authkey=284738AIuEZXRVCDfj5d26feae";

        $ch = curl_init('http://api.msg91.com/api/sendhttp.php?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $result = curl_close($ch);
        return $res;
    }
    // public function send_sms($mobile, $message){
    //     $message = urlencode($message);
    //     $dd = 'loginID=vibhastek&password=123456&mobile='.$mobile.'&text='.$message.'&senderid=MENTOR&route_id=2&Unicode=0';
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_RETURNTRANSFER => 1,
    //         CURLOPT_URL => 'http://198.24.149.4/API/pushsms.aspx?'.$dd,
    //     ));
    //     $result = curl_exec($curl);
    //     return $result;
    // }
    #Register
    public function register_school()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $password = $data['password'];
        if (in_array('deviceID',$data)){
            $deviceID = $data['deviceID'];
            $deviceToken = $data['deviceToken'];
            $deviceType = $data['deviceType'];
        }else{
            $deviceID = "";
            $deviceToken = "";
            $deviceType = "";
        }
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $address = (isset($data['address']))?$data['address']:'';
        $school_code = strtoupper(substr($name, 0,2)).$this->generate_random_password(5);
        $array = array(
            'school_code' => $school_code,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'username' => $school_code,
            'password' => $this->hash($password),
            'subscription_status' => 'N',
            'is_active' => 'Y',
            'is_config_setup' => 'N',
            'is_approved' => 'Y',
            'added_on' => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s')
        );
        //check before insert
        $schoolID = $this->webservice_m->table_insert('school_registration',$array);
        if((int)$schoolID)
        {
            if ($deviceID != "" && $deviceToken != "" && $deviceType != ""){
                $this->update_device($schoolID,$schoolID,'school',$deviceType,$deviceID,$deviceToken,$ip_address);
            }
            $message = "Thank You For registering your school with us.\n Your 'School Code/username' is $school_code and 'Password' is $password .";
            $this->send_sms($phone, $message);
            $response[] = array('result'=>'success','id'=>$schoolID,'name'=>$name, 'email'=>$email, 'phone' => $phone, 'school_code'=>$school_code, 'schoolID'=>$schoolID, 'username'=>$school_code,'image'=>base_url('uploads/images/default.png'));
            //sent thankyou sms and email
        } else {
            $response[] = array('result'=>'failure', 'message' => 'Invalid Details');
        }
        echo json_encode($response);
    }
    public function validate_schoolcode()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $school_code = $data['school_code'];
        $this->db->select('school_registration.schoolID,school_registration.school_code,COALESCE(NULLIF(school_config.school_name, ""),"") as school_name');
        $this->db->join('school_config','school_registration.schoolID = school_config.schoolID','LEFT');
        $validate = $this->db->get_where('school_registration',array('school_code'=>$school_code,'is_active'=>'Y'))->row(); 
        if (count($validate)) {
            $class_check = $this->db->get_where('classes',array('schoolID'=>$validate->schoolID,'is_active'=>'Y'))->row();
            $section_check = $this->db->get_where('section',array('schoolID'=>$validate->schoolID,'is_active'=>'Y'))->row();
            if (count($class_check) && count($section_check)) {
                $response[] = array('result'=>'success','schoolID'=>$validate->schoolID,'school_code'=>$validate->school_code,'school_name'=>$validate->school_name);
            }else{
                $response[] = array('result'=>'class or section not configured','schoolID'=>$validate->schoolID,'school_code'=>$validate->school_code,'school_name'=>$validate->school_name);
            }
            
        }else{
            $response[] = array('result'=>'not valid');
        }
        echo json_encode($response);
    }
    public function register_other()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $type = strtolower($data['type']);
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $password = $data['password'];
        $address = (isset($data['address']))?$data['address']:'';
        $code = (isset($data['code']))?$data['code']:'';
        $username = strtoupper(substr($name, 0,2)).$this->generate_random_password(5);
        if (in_array('batchID',$data)){
            $batchID = $data['batchID'];
        }else{
            $batchID = "";
        }
        //get school ID
        $schoolID = $data['schoolID'];
        if (in_array('deviceID',$data)){
            $deviceID = $data['deviceID'];
            $deviceToken = $data['deviceToken'];
            $deviceType = $data['deviceType'];
        }else{
            $deviceID = "";
            $deviceToken = "";
            $deviceType = "";
        }
        $ip_address = $_SERVER['REMOTE_ADDR'];
        if ($type == "student") {
            $classID = $data['classID'];
            $sectionID = $data['sectionID'];
            $roll_no = $data['roll_no'];
            $dob = str_replace("/", "-", $data['dob']);
            $array = array(
                'schoolID' => $schoolID,
                'school_code' => strtoupper($code),
                'name' => $name,
                'classID' => $classID,
                'sectionID' => $sectionID,
                'roll_no' => $roll_no,
                'address' => $address,
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
                'password' => $this->hash($password),
                'dob' => date("Y-m-d",strtotime($dob)),
                'is_active' => 'N',
                'batchID' => $batchID,
                'concessionID' => 0,
                'added_on' => date('Y-m-d H:i:s')
            );
        }elseif($type == "parent"){
            $array = array(
                'schoolID' => $schoolID,
                'school_code' => strtoupper($code),
                'name' => $name,
                'address' => $address,
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
                'password' => $this->hash($password),
                'is_active' => 'Y',
                'added_on' => date('Y-m-d H:i:s')
            );
        }else{
            $array = array(
                'schoolID' => $schoolID,
                'school_code' => strtoupper($code),
                'name' => $name,
                'address' => $address,
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
                'password' => $this->hash($password),
                'is_active' => 'N',
                'added_on' => date('Y-m-d H:i:s')
            );
        }
        
        if($type == 'teacher')
        {
            $array['registeredby'] = 'app';
            $array['authorize_role'] = 'teacher';
            $array['approved_on'] = date('Y-m-d H:i:s');
        } elseif($type == 'student') {
            $array['registeredby'] = 'app';
            $array['academicID'] = 0;
            $array['subscription_status'] = 'N';
        }
        //check before insert
        $userID = $this->webservice_m->table_insert($type,$array);
        if((int)$userID)
        {
            if ($type != 'parent'){
                $device_info = $this->db->get_where('user_login',array('userID'=>$schoolID,'role'=>'school'))->result();
                foreach ($device_info as $d){
                    $device_token = $d->device_token;
                    $data1 = '{"notification_type":"text","title":"Approval","msg":"A new request for '.$type.' has been arrived.","type":"approval","role":"school"}';
                    $data1 = array("m" => $data1);
                    $this->fcmNotification($device_token, $data1);
                }
                $data2 = array(
                    "schoolID"=>$schoolID,
                    "userID"=>$schoolID,
                    "role"=>"school",
                    "title" => "Approval",
                    "notification"=>"A new request for $type has been arrived.",
                    "status"=>1,
                    "added_on"=>date("Y-m-d H:i:s")
                );
                $this->db->insert('notification',$data2);
            }
            if ($deviceID != "" && $deviceToken != "" && $deviceType != ""){
                $this->update_device($schoolID,$userID,$type,$deviceType,$deviceID,$deviceToken,$ip_address);
            }
            $message = "Thank You For registering as ".strtoupper($type)." with us.\n Your 'Username' is $username and 'Password' is $password .";
            $this->send_sms($phone, $message);
            $response[] = array('result'=>'success','schoolID' => $schoolID, 'school_code'=>$code, 'userID'=>$userID, 'username'=>$username);
            //sent thankyou sms and email
        } else {
            $response[] = array('result'=>'failure', 'message' => 'Invalid Details');
        }
        echo json_encode($response);
    }
    public function school_config()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $school_name = $data['school_name'];
        $school_prefix = $data['school_prefix'];
        $principal_name = $data['principal_name'];
        $principal_phone = $data['principal_phone'];
        $principal_email = $data['principal_email'];
        $attendance_type = strtolower($data['attendance_type']);
        $default_academic_year = $data['academic_year'];
        $fees_fine_config = $data['fees_fine_config'];
        $fees_fine_amt = $data['fees_fine_amt'];
        $per_day_fine = 10;
        $per_student_subscription_amount = 1;
        if ($fees_fine_config == "monthly") {
            $fees_fine_config = "month";
        }
        $array = array(
            'schoolID' => $schoolID,
            'school_name' => $school_name,
            'school_prefix' => $school_prefix,
            'default_attendance' => $attendance_type,
            'default_academic_year' => $default_academic_year,
            'principal_name' => $principal_name,
            'email' => $principal_email,
            'phone' => $principal_phone,
            'per_day_fine' => $per_day_fine,
            'per_student_subscription_amount' => $per_student_subscription_amount,
            'fees_fine_amt' => $fees_fine_amt,
            'fees_fine_config' => $fees_fine_config,
            'last_modified_on' => date('Y-m-d H:i:s')
        );
        $array1 = array(
            'schoolID' => $schoolID,
            'school_name' => $school_name,
            'school_prefix' => $school_prefix,
            'default_attendance' => $attendance_type,
            'default_academic_year' => $default_academic_year,
            'principal_name' => $principal_name,
            'email' => $principal_email,
            'phone' => $principal_phone,
            'per_day_fine' => $per_day_fine,
            'fees_fine_amt' => $fees_fine_amt,
            'fees_fine_config' => $fees_fine_config,
            'last_modified_on' => date('Y-m-d H:i:s')
        );
        $school_code = $this->webservice_m->get_single_table('school_registration',array('schoolID'=>$schoolID));
        $check = $this->webservice_m->get_single_table('school_config',array('schoolID' => $schoolID));
        if(count($check)>0)
        {
            $this->webservice_m->table_update('school_registration',array('is_config_setup'=>'Y'),array('schoolID'=>$schoolID));
            $id = $this->webservice_m->table_update('school_config',$array1,array('configID'=>$check->configID));
        } else {
            $this->webservice_m->table_update('school_registration',array('is_config_setup'=>'Y'),array('schoolID'=>$schoolID));
            $id = $this->webservice_m->table_insert('school_config',$array);
        }
        if((int)$id)
        {
            $response[] = array('result'=>'success', 'message'=>'Update Successfully','school_code'=>$school_code->school_code,'schoolID'=>$school_code->schoolID);
        } else {
            $response[] = array('result'=>'failure', 'message' => 'Invalid Details');
        }
        echo json_encode($response);
    }
    public function modules()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $get_modules = $this->webservice_m->get_all_data_where('modules',array('is_active'=>'Y'));
        $modules = array();
        $path = base_url('uploads/modules/');
        foreach($get_modules as $m)
        {
            $modules[] = array(
                'moduleID' => $m->moduleID,
                'module_name' => $m->module_name,
                'module_icon' => $path.$m->module_icon,
                'short_description' => $m->short_description,
                'price' => $m->price
            );
        }
        $response[] = array('result'=>'success', 'modules'=>$modules);
        echo json_encode($response);
    }
    public function school_subscription()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $school_code = $data['school_code'];
        $modules = $data['modules'];
        $amount = $data['amount'];
        $paid_status = $data['paid_status'];
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+1 year'));
        $array = array(
            'schoolID' => $schoolID,
            'school_code' => $school_code,
            'modules' => $modules,
            'amount' => $amount,
            'is_paid' => $paid_status,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'grace_day' => 0,
            'is_active' => 'Y',
            'added_on' => date('Y-m-d H:i:s')
        );

        $subscriptionID = $this->webservice_m->table_insert('school_subscription_history',$array);
        if((int)$subscriptionID)
        {
            $this->webservice_m->table_update('school_registration',array('subscription_status'=>'Y'),array('schoolID'=>$schoolID));
            $response[] = array('result'=>'success', 'subscriptionID'=>$subscriptionID, 'message'=>'Successfully Subscribed');
        } else {
            $response[] = array('result'=>'failure', 'message' => 'Invalid Details');
        }
        echo json_encode($response);
    }
    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $role = strtolower($data['role']);
        $username = $data['username'];
        $password = $data['password'];
        $deviceID = $data['deviceID'];
        $deviceToken = $data['deviceToken'];
        $deviceType = $data['deviceType'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $role1 = $role;
        if ($role == 'school')
        {
            $role = 'school_registration';
        }
        $check = $this->webservice_m->get_single_table($role,array('username'=>$username, 'password'=>$this->hash($password)));
        
        if(count($check)>0)
        {
            //$var = $role.'ID';
            if($role == 'student')
            {
                $id = $check->studentID;
                $status = $check->is_active;
                $config_status = "";
            }

            elseif($role == 'parent')
            {
                $id = $check->parentID;
                $status = $check->is_active;
                $config_status = "";
            }

            if($role == 'teacher')
            {
                $id = $check->teacherID;
                $status = $check->is_active;
                $config_status = "";
            }

            if($role == 'school' || $role == 'school_registration')
            {
                $id = $check->schoolID;
                $status = $check->is_approved;
                $get_school_config = $this->webservice_m->get_single_table('school_config',array('schoolID'=>$id));
                if (count($get_school_config) && $check->is_config_setup == "Y") {
                    $config_status = "Y";
                }else{
                    $config_status = "N";
                }
            }
            $this->update_device($check->schoolID,$id,$role1,$deviceType,$deviceID,$deviceToken,$ip_address);
            $img = (empty($check->image))?'default.png':$check->image;
//            $device_token = $deviceToken;
//            $data1 = '{"notification_type":"text","title":"Login Success","msg":"You have Successfully Logged in as '.$check->name.' as '.$role1.'.","type":"login","role":"'.$role1.'"}';
//            $data1 = array("m" => $data1);
//            $this->fcmNotification($device_token, $data1);
            $response[] = array('result'=>'success','code'=>$check->school_code, 'schoolID'=>$check->schoolID,'id'=>$id, 'name'=>$check->name, 'email'=>$check->email, 'phone' => $check->phone, 'status'=>$status, 'image'=>base_url('uploads/images/').$img,'config_status'=>$config_status );
        } else {
            $response[] = array('result'=>'failure', 'message' => 'Invalid Details');
        }
        echo json_encode($response);
    }
    public function update_device($schoolID,$userID,$role,$device_type,$deviceID,$token,$ip_address)
    {
        $check = $this->db->get_where('user_login',array("deviceID"=>$deviceID))->row();
        if(count($check)>0)
        {
            //update
            $this->db->where(array("deviceID"=>$deviceID));
            $this->db->update("user_login",array("schoolID"=>$schoolID,"userID"=>$userID,"role"=>$role,"device_type"=>$device_type, "device_token"=>$token, "ip_add"=>$ip_address, "login_on"=>date('Y-m-d H:i:s')));
        } else {
            //insert
            $this->db->insert("user_login",array("schoolID"=>$schoolID,"userID"=>$userID,"role"=>$role,"device_type"=>$device_type, "deviceID"=>$deviceID,"device_token"=>$token, "ip_add"=>$ip_address, "login_on"=>date('Y-m-d H:i:s')));
        }
        return TRUE;
    }
    public function student_home()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $studentID = $data['studentID'];
        $path = base_url('uploads/images/');
        $check[] = $this->db->query("SELECT student.schoolID,COALESCE(NULLIF(student_group.latitude, ''),'') as latitude,COALESCE(NULLIF(student_group.longitude, ''),'') as longitude,student.classID,student.sectionID,classes.class as class_name,section.section,student.school_code,student.username,student.is_active,student.`name`,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image,GROUP_CONCAT(modules.module_name ORDER BY modules.module_name SEPARATOR ',') as modules, COALESCE(NULLIF(school_config.default_attendance, ''),'subject') as default_attendance FROM student LEFT JOIN student_group ON student.groupID = student_group.groupID LEFT JOIN classes ON classes.classID = student.classID LEFT JOIN section ON section.sectionID = student.sectionID LEFT JOIN school_config ON student.schoolID = school_config.schoolID LEFT JOIN school_subscription_history ON student.schoolID = school_subscription_history.schoolID AND school_subscription_history.is_active = 'Y' INNER JOIN modules ON FIND_IN_SET (modules.moduleID, school_subscription_history.modules) > 0 WHERE student.studentID = '$studentID'")->row();
        if (count($check)){
            if ($check[0]->schoolID == '21'){
                $modules_array = explode(",",$check[0]->modules);
                $modules_array1 = array_diff($modules_array, array("Attendance"));
                $check[0]->modules = implode(",",$modules_array1);
                //echo $check[0]->modules;
            }
            $response[] = array('result'=>'success','home'=>$check);
        }else{
            $response[] = array('result'=>'failure','message'=>'Not a valid student');
        }
        echo json_encode($response);
    }
    public function teacher_home()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $teacherID = $data['teacherID'];
        $path = base_url('uploads/images/');
        $check[] = $this->db->query("SELECT teacher.teacherID,teacher.school_code,teacher.username,teacher.is_active,teacher.`name`,CONCAT('".$path."',COALESCE(NULLIF(teacher.image, ''),'default.png')) as image,GROUP_CONCAT(modules.module_name ORDER BY modules.module_name SEPARATOR ',') as modules, COALESCE(NULLIF(school_config.default_attendance, ''),'subject') as default_attendance FROM teacher LEFT JOIN school_config ON teacher.schoolID = school_config.schoolID LEFT JOIN school_subscription_history ON teacher.schoolID = school_subscription_history.schoolID AND school_subscription_history.is_active = 'Y' INNER JOIN modules ON FIND_IN_SET (modules.moduleID, school_subscription_history.modules) > 0 WHERE teacher.teacherID = '$teacherID'")->row();
        if (count($check)){
            $response[] = array('result'=>'success','home'=>$check);
        }else{
            $response[] = array('result'=>'failure','message'=>'Not a valid teacher');
        }
        echo json_encode($response);
    }
    public function school_home()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $default_attendance = "subject";
        $get_school = $this->webservice_m->get_single_table('school_registration',array('schoolID'=>$schoolID));
        $get_school_config = $this->webservice_m->get_single_table('school_config',array('schoolID'=>$schoolID));
        $class_check = $this->db->get_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'))->row();
        $section_check = $this->db->get_where('section',array('schoolID'=>$schoolID,'is_active'=>'Y'))->row();
        if (count($class_check) && count($section_check)) {
            $class_configuration = "Y";
        }else{
            $class_configuration = "N";
        }
        if (count($get_school_config)){
            $default_attendance = "$get_school_config->default_attendance";
        }
        $this->db->order_by("historyID", "desc");
        $subscription = $this->db->get_where('school_subscription_history',array('schoolID'=>$schoolID,'is_active'=>'Y'))->row();
        $modules_name = "";
        $subscription_status = 'N';
        if (count($subscription)) {
            if(strtotime($subscription->end_date) > time()){
                $subscription_status = 'Y';
            }
            $module_array = explode(",",$subscription->modules);
            $modules_name_array = array();
            foreach ($module_array as $m) {
                $module_info = $this->db->query("SELECT `module_name` FROM `modules` WHERE `moduleID`= '$m' AND `is_active` = 'Y'")->row();
                if (count($module_info)) {
                    array_push($modules_name_array, $module_info->module_name);
                }                
            }
            $modules_name = implode(",", $modules_name_array);
        }
        if (count($get_school_config) && $get_school->is_config_setup == "Y") {
            $school_configuration = "Y";
        }else{
            $school_configuration = "N";
        }
        $img = (empty($get_school->image) || is_null($get_school->image))?'default.png':$get_school->image;
        $response[] = array('result'=>'success','name'=>$get_school->name, 'email'=>$get_school->email, 'subscription_status'=>$subscription_status, 'is_config_setup'=>$school_configuration, 'default_attendance'=>$default_attendance, 'image'=>base_url('uploads/images/').$img, 'modules'=>$modules_name,'class_config'=>$class_configuration);
        echo json_encode($response);
    }
    public function get_approval_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $this->db->select("teacherID,name,email,phone,address");
        $teachers = $this->db->get_where('teacher',array('schoolID'=>$schoolID, 'is_active'=>'N'))->result();
        $this->db->select("student.studentID,student.name,student.email,student.phone,student.address,student.roll_no,classes.class as class_name,section.section");
        $this->db->join('classes','student.classID = classes.classID','LEFT');
        $this->db->join('section','student.sectionID = section.sectionID','LEFT');
        $students = $this->db->get_where('student',array('student.schoolID'=>$schoolID, 'student.is_active'=>'N'))->result();
        $this->db->select("student.name as student,student.roll_no,classes.class as class_name,section.section,unapproved_parent_list.unapprovedID as parentID,parent.name,parent.address,parent.email,parent.phone");
        $this->db->join('student','unapproved_parent_list.studentID = student.studentID','LEFT');
        $this->db->join('classes','student.classID = classes.classID','LEFT');
        $this->db->join('section','student.sectionID = section.sectionID','LEFT');
        $this->db->join('parent','unapproved_parent_list.parentID = parent.parentID','LEFT');
        $parents = $this->db->get_where('unapproved_parent_list',array('student.schoolID'=>$schoolID, 'student.is_active'=>'Y'))->result();

        $response[] = array('result'=>'success', 'teachers'=>$teachers, 'parents'=> $parents, 'students'=>$students);
        echo json_encode($response);
    }
    public function action_approval()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $ID = $data['ID'];
        $role = strtolower($data['role']);
        $action = $data['action'];
        $column = $role.'ID';
        if ($role == 'parent'){
            if (strtolower($action) == 'Y'){
                $details = $this->db->get_where('unapproved_parent_list',array('unapprovedID'=>$ID))->row();
                if (count($details)){
                    $this->db->where(array('studentID'=>$details->studentID));
                    $this->db->update('student',array('parentID'=>$details->parentID,'is_par_approved'=>'Y'));
                    $this->db->where(array('unapprovedID'=>$ID));
                    $this->db->delete('unapproved_parent_list');
                }
            }else{
                $this->db->where(array('unapprovedID'=>$ID));
                $this->db->delete('unapproved_parent_list');
            }
        }else{
            $t = $this->webservice_m->table_update($role,array('is_active'=>$action),array($column => $ID, 'schoolID'=>$schoolID));
        }
        $response[] = array('result'=>'success','message'=>'Successfully Updated');
        echo json_encode($response);
    }
    public function bulk_approval()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $ID = $data['ID'];
        $ID = explode(",",$ID);
        $role = strtolower($data['role']);
        $action = $data['action'];
        $column = $role.'ID';
        foreach ($ID as $ID){
            if ($role == 'parent'){
                if (strtolower($action) == 'Y'){
                    $details = $this->db->get_where('unapproved_parent_list',array('unapprovedID'=>$ID))->row();
                    if (count($details)){
                        $this->db->where(array('studentID'=>$details->studentID));
                        $this->db->update('student',array('parentID'=>$details->parentID,'is_par_approved'=>'Y'));
                        $this->db->where(array('unapprovedID'=>$ID));
                        $this->db->delete('unapproved_parent_list');
                    }
                }else{
                    $this->db->where(array('unapprovedID'=>$ID));
                    $this->db->delete('unapproved_parent_list');
                }
            }else{
                $t = $this->webservice_m->table_update($role,array('is_active'=>$action),array($column => $ID, 'schoolID'=>$schoolID));
            }
        }
        $response[] = array('result'=>'success','message'=>'Successfully Updated');
        echo json_encode($response);
    }
    public function update_student_on_approval()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $t = $this->webservice_m->table_update('student',array('academicID'=>1,'classID'=>$classID, 'sectionID'=>$sectionID),array('studentID' => $studentID, 'schoolID'=>$schoolID));
        $response[] = array('result'=>'success','message'=>'Successfully Updated');
        echo json_encode($response);
    }
    public function check_student_subscription()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$studentID, 'schoolID'=>$schoolID));
        $att_type = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
        $default_attendance = "daywise";
        if(count($att_type)){
            $default_attendance = $att_type->default_attendance;
        }
        if(count($get_student)>0 && $get_student->subscription_status == 'Y')
        {
            $response[] = array('result'=>'success','default_attendance'=>$default_attendance);
        } else {
            $response[] = array('result'=>'failure');
        }
        echo json_encode($response);
    }
    public function subscription_package()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $get_school = $this->webservice_m->get_single_table('school_config',array('schoolID'=>$schoolID));
        $amount = $get_school->per_student_subscription_amount;
        $service_tax = 18;
        $product_service_charge = 2;
        $response[] = array('result'=>'success', 'subscription_amount' => $amount, 'service_tax'=>$service_tax, 'product_service_charge'=>$product_service_charge);
        echo json_encode($response);
    }
    public function student_subscription()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $amount = $data['amount'];
        $paid_status = $data['paid_status'];
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+1 year'));
        $get_school = $this->webservice_m->get_single_table('school_registration',array('schoolID'=>$schoolID));
        $array = array(
            'schoolID' => $schoolID,
            'school_code' => $get_school->school_code,
            'studentID' => $studentID,
            'amount' => $amount,
            'is_paid' => $paid_status,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'added_on' => date('Y-m-d H:i:s')
        );
        $id = $this->webservice_m->table_insert('student_subscription_history',$array);
        if((int)$id)
        {
            $this->webservice_m->table_update('student',array('subscription_status'=>'Y','start_date'=>$start_date, 'end_date'=>$end_date),array('studentID'=>$studentID));
            $response[] = array('result'=>'success');
        } else {
            $response[] = array('result'=>'failure');
        }
        echo json_encode($response);
    }
    public function child_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $parentID = $data['parentID'];
        $path = base_url('uploads/images/');
        $students = array();
        $this->db->select("studentID,schoolID,school_code,name,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image,email,phone,address,subscription_status,is_par_approved as is_approved");
        $get_student = $this->db->get_where('student',array('parentID'=>$parentID, 'is_active'=>'Y'))->result();
        foreach($get_student as $s){
            array_push($students,$s);
        }
        $get_unapproved = $this->db->query("SELECT unapproved_parent_list.studentID,student.schoolID,student.school_code,student.name,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image,email,phone,address,subscription_status,COALESCE('N') AS is_approved FROM unapproved_parent_list LEFT JOIN student on unapproved_parent_list.studentID = student.studentID WHERE unapproved_parent_list.parentID = '$parentID'")->result();
        foreach ($get_unapproved as $u){
            array_push($students,$u);
        }
        $response[] = array('result'=>'success','students'=>$students);
        echo json_encode($response);
    }
    public function add_child()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $parentID = $data['parentID'];
        $school_code = $data['school_code'];
        $student_code = $data['student_code'];
        $this->db->select("studentID,schoolID,parentID");
        $get_student = $this->db->get_where('student',array('school_code'=>$school_code, 'username'=>$student_code))->row();
        if(count($get_student)>0)
        {
            $schoolID = $get_student->schoolID;
            if ($parentID != $get_student->parentID){
                $check = $this->db->get_where('unapproved_parent_list',array('studentID'=>$get_student->studentID,'parentID'=>$parentID))->row();
                if (count($check) == 0){
                    $this->db->insert('unapproved_parent_list',array('parentID'=>$parentID,'studentID'=>$get_student->studentID,'added_on'=>date("Y-m-d H:i:s")));
                    $device_info = $this->db->get_where('user_login',array('userID'=>$schoolID,'role'=>'school'))->result();
                    foreach ($device_info as $d){
                        $device_token = $d->device_token;
                        $data1 = '{"notification_type":"text","title":"Approval","msg":"A new request for parent has been arrived.","type":"approval","role":"school"}';
                        $data1 = array("m" => $data1);
                        $this->fcmNotification($device_token, $data1);
                    }
                    $data2 = array(
                        "schoolID"=>$schoolID,
                        "userID"=>$schoolID,
                        "role"=>"school",
                        "title" => "Approval",
                        "notification"=>"A new request for parent has been arrived.",
                        "status"=>1,
                        "added_on"=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert('notification',$data2);
                }
            }
            $response[] = array('result'=>'success');
        } else {
            $response[] = array('result'=>'failure');
        }
        echo json_encode($response);
    }
    public function get_classes_admin()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classes = $this->webservice_m->get_all_data_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
        $class = [];
        foreach ($classes as $c)
        {
            $class[]=  array('classID'=>$c->classID,'class_name'=>$c->class);
        }
        $response[] = array('result'=>'success', 'classes' => $class);
        echo json_encode($response);
    }
    public function add_class()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schooID = $data['schoolID'];
        $class = $data['class'];
        $array1 = array(
            'schoolID'=>$schooID,
            'class'=>$class,
            'is_active'=>'Y',
            'added_on'=>date("Y-m-d H:i:s")
        );
        $this->db->insert('classes',$array1);
        $classID = $this->db->insert_id();
        $response[] = array('result'=>'success','classID'=>$classID);
        echo json_encode($response);
    }
    public function edit_class()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schooID = $data['schoolID'];
        $classID = $data['classID'];
        $class = $data['class'];
        $array1 = array(
            'class'=>$class
        );
        $this->db->where(array('classID'=>$classID,'schoolID'=>$schooID));
        $this->db->update('classes',$array1);
        $response[] = array('result'=>'success','classID'=>$classID);
        echo json_encode($response);
    }
    public function get_sectionbyclass()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $this->db->select("sectionID,section");
        $section = $this->db->get_where('section',array('schoolID'=>$schoolID,'classID'=>$classID,'is_active'=>'Y'))->result();
        $response[] = array('result'=>'success','section'=>$section);
        echo json_encode($response);
    }
    public function add_section()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $section = $data['section'];
        $array1 = array(
            'schoolID'=>$schoolID,
            'classID'=>$classID,
            'section'=>$section,
            'class_teacherID'=>0,
            'is_active'=>'Y',
            'added_on'=>date("Y-m-d H:i:s")
        );
        $this->db->insert('section',$array1);
        $sectionID = $this->db->insert_id();
        $response[] = array('result'=>'success','sectionID'=>$sectionID);
        echo json_encode($response);
    }
    public function edit_section()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schooID = $data['schoolID'];
        $sectionID = $data['sectionID'];
        $section = $data['section'];
        $array1 = array(
            'section'=>$section
        );
        $this->db->where(array('sectionID'=>$sectionID,'schoolID'=>$schooID));
        $this->db->update('section',$array1);
        $response[] = array('result'=>'success','sectionID'=>$sectionID);
        echo json_encode($response);
    }
    public function get_classes_teacher()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $teacherID = $data['teacherID'];
        $classes = $this->webservice_m->get_all_data_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
        $class = [];
        foreach ($classes as $c)
        {
            $class[]=  array('classID'=>$c->classID,'class_name'=>$c->class);
        }
        $response[] = array('result'=>'success', 'classes' => $class);
        echo json_encode($response);
    }
    public function get_section()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $classID = $data['classID'];
        $role = $data['role'];
        $sections = $this->webservice_m->get_all_data_where('section',array('schoolID'=>$schoolID,'classID'=>$classID,'is_active'=>'Y'));
        $section = [];
        foreach ($sections as $s)
        {
            $section[]=  array('sectionID'=>$s->sectionID,'section'=>$s->section);
        }
        $response[] = array('result'=>'success', 'section' => $section);
        echo json_encode($response);
    }
    public function get_subject()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $role = $data['role'];
        $subjects = $this->webservice_m->get_all_data_where('subject',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'is_active'=>'Y'));
        $subject = [];
        foreach ($subjects as $s)
        {
            $subject[]=  array('subjectID'=>$s->subjectID,'subject_name'=>$s->subject_name,'subject_code'=>$s->subject_code,'type'=>$s->type);
        }
        $response[] = array('result'=>'success', 'subject' => $subject);
        echo json_encode($response);
    }
    public function get_student_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $subjectID = $data['subjectID'];

        $get_students = $this->webservice_m->get_all_data_where('student',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'is_active'=>'Y'));
        $students = [];
        foreach($get_students as $s)
        {
            $students[] = array(
              'school_code' => $s->school_code,
                'studentID' => $s->studentID,
              'name' => $s->name,
              'email' => $s->email,
                'phone' => $s->phone,
                'subscription_status' => $s->subscription_status
            );
        }
        $response[] = array('result'=>'success', 'students' => $students);
        echo json_encode($response);
    }
    public function add_attendance()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $students = $data['students'];
        //$students ="1:P,2:A,3:P,4:L";
        $tdate = $data['att_date'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $subjectID = $data['subjectID'];
        $userID = $data['userID'];
        $usertype = $data['usertype'];

        $month_year = date('m-Y',strtotime($tdate));
        $d = date('d',strtotime($tdate));
        $a = "a".abs($d);

        $student_array = explode(',', $students);
        foreach($student_array as $st)
        {
            $student = explode(':', $st);
            $studentID = $student[0];
            $status = $student[1];

            $check = $this->webservice_m->check_attendance(array('schoolID'=>$schoolID, 'studentID'=>$studentID, 'classesID'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
            if(count($check)>0)
            {
                //update
                $this->webservice_m->update_attendance(array($a=>$status),array('studentID'=>$studentID, 'classesID'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
            } else {
                //insert
                $this->webservice_m->insert_attendance(array('schoolID'=>$schoolID,'academicID'=>1,'studentID'=>$studentID, 'classesID'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID, 'userID'=>$userID, 'usertype'=>$usertype, 'monthyear'=>$month_year,$a=>$status));
            }
        }
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function view_student_attendance()
    {
        $path = base_url('uploads/');
        $data = json_decode(file_get_contents('php://input'), true);
        $studentID = $data['studentID'];
        $subjectID = $data['subjectID'];
        $tdate = $data['att_date'];
        $month_year = date('m-Y',strtotime($tdate));
        $mday = date('Y-m',strtotime($data['att_date']));
        $first_date = $mday.'-01';
        $last_date = $mday.'-'.date('t',strtotime($data['att_date']));
        $begin = new DateTime($first_date);
        $end   = new DateTime($last_date);
        $alldates = array();
        $check = $this->webservice_m->check_attendance(array('studentID'=>$studentID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
        $this->db->select("schoolID");
        $schoolID = $this->db->get_where('student',array('studentID'=>$studentID))->row();
        $schoolID = $schoolID->schoolID;
        $holiday_array = array();
        $holidays = $this->db->get_where('holiday',array('schoolID'=>$schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
        foreach ($holidays as $h){
            $holiday_array[] = array(
                'wdate' => $h->date,
                'status' => 'H'
            );
        }
        if(count($check)> 0)
        {
            for($i = $begin; $i <= $end; $i->modify('+1 day')){
                $d = $i->format("d");
                $d1 = $i->format("Y-m-d");

                $a = "a".abs($d);               
                $b = $check->$a;
                
                
                if(empty($b) || is_null($b) || !isset($b))
                {
                    $b = '';
                }
                if ($b != 'P'){
                    foreach ($holiday_array as $key => $val) {
                        if ($val['wdate'] == $d1) {
                            $b = 'H';
                            break;
                        }
                    }
                }
                $alldates[] = array(
                    'wdate' => $d1,
                    'status' => $b
                );
            }
        }else{
            $alldates = $holiday_array;
        }


        $response[] = array('result'=>'success','alldates'=>$alldates);
        echo json_encode($response);
    }
    public function view_list_student_attendance()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $subjectID = $data['subjectID'];
        $schoolID = $data['schoolID'];
        $tdate = $data['tdate'];
        $month_year = date('m-Y',strtotime($tdate));
        $d = date('d',strtotime($tdate));
        $a = "a".abs($d);
        $path = base_url('uploads/images/');
        $student = array();
        $get_students_class_wise = $this->webservice_m->get_students_class_wise($classID,$sectionID);
        foreach($get_students_class_wise as $gs)
        {
            $check = $this->webservice_m->get_individual_attendance(array('schoolID'=>$schoolID,"studentID"=>$gs->studentID,"classesID"=>$classID,"sectionID"=>$sectionID, 'subjectID'=>$subjectID, "monthyear"=>$month_year ));
            if(count($check))
            {
                $b = $check->$a;
                if(empty($b) || is_null($b))
                {
                    $b = 'A';
                }
            } else {
               $b = 'A'; 
            }
            
            
            $student[] = array(
                'studentID'=> $gs->studentID,
                'name' => $gs->name,
                'roll_no' => $gs->roll_no,
                'image' => $path.$gs->image1,
                'email' => $gs->email,
                'phone' => $gs->phone,
                'subscription_status' => $gs->subscription_status,
                'status'=>$b
            );
        }
        
        $response[] = array('result'=>'success','students'=>$student);
        echo json_encode($response);
    }
    public function list_teachers()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $get_teachers = $this->webservice_m->get_all_data_where('teacher',array('schoolID'=>$schoolID));
        $teachers = array();
        foreach($get_teachers as $gt)
        {
            $img = (empty($gt->image))?'default.png':$gt->image;
            $teachers[] = array(
                'teacherID' => $gt->teacherID,
                'name' => $gt->name,
                'email' => $gt->email,
                'phone' => $gt->phone,
                'image' => base_url('uploads/images/').$img
            );
        }
        $response[] = array('result'=>'success','teachers'=>$teachers);
        echo json_encode($response);
    }
    public function list_teachers_for_att()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $tdate = $data['tdate'];
        $month_year = date('m-Y',strtotime($tdate));
        $d = date('d',strtotime($tdate));
        $a = "a".abs($d);
        $get_teachers = $this->webservice_m->get_all_data_where('teacher',array('schoolID'=>$schoolID));
        $teachers = array();
        foreach($get_teachers as $gt)
        {
            $check = $this->webservice_m->get_single_table('teacher_attendance',array('schoolID'=>$schoolID,"teacherID"=>$gt->teacherID, "monthyear"=>$month_year ));
            if(count($check))
            {
                $b = $check->$a;
                if(empty($b) || is_null($b))
                {
                    $b = 'A';
                }
            } else {
               $b = 'A'; 
            }
               
            $img = (empty($gt->image))?'default.png':$gt->image;
            $teachers[] = array(
                'teacherID' => $gt->teacherID,
                'name' => $gt->name,
                'email' => $gt->email,
                'phone' => $gt->phone,
                'image' => base_url('uploads/images/').$img,
                'status' => $b
            );
        }
        $response[] = array('result'=>'success','teachers'=>$teachers);
        echo json_encode($response);
    }
    public function add_teacher_attendance()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $teachers = $data['teachers'];
        //$teachers ="1:P,2:A,3:P,4:L";
        $tdate = $data['att_date'];
        $userID = $data['userID'];
        $usertype = $data['usertype'];

        $month_year = date('m-Y',strtotime($tdate));
        $d = date('d',strtotime($tdate));
        $a = "a".abs($d);

        $teacher_array = explode(',', $teachers);
        foreach($teacher_array as $st)
        {
            $teacher = explode(':', $st);
            $teacherID = $teacher[0];
            $status = $teacher[1];

            $check = $this->webservice_m->get_single_table('teacher_attendance',array('schoolID'=>$schoolID, 'teacherID'=>$teacherID, 'monthyear'=>$month_year));
            if(count($check)>0)
            {
                //update
                $this->webservice_m->table_update('teacher_attendance',array($a=>$status),array('schoolID'=>$schoolID, 'teacherID'=>$teacherID, 'monthyear'=>$month_year));
            } else {
                //insert
                $this->webservice_m->table_insert('teacher_attendance',array('schoolID'=>$schoolID,'academicID'=>1,'teacherID'=>$teacherID, 'userID'=>$userID, 'usertype'=>$usertype, 'monthyear'=>$month_year,$a=>$status));
            }
        }
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function view_teacher_attendance()
    {
        $path = base_url('uploads/');
        $data = json_decode(file_get_contents('php://input'), true);
        $teacherID = $data['teacherID'];
        $schoolID = $data['schoolID'];
        $tdate = $data['att_date'];
        $month_year = date('m-Y',strtotime($tdate));
        $mday = date('Y-m',strtotime($data['att_date']));
        $first_date = $mday.'-01';
        $last_date = $mday.'-'.date('t',strtotime($data['att_date']));
        $begin = new DateTime($first_date);
        $end   = new DateTime($last_date);
        $alldates = array();
        $check = $this->webservice_m->get_single_table('teacher_attendance',array('schoolID'=>$schoolID, 'teacherID'=>$teacherID, 'monthyear'=>$month_year));
        $holiday_array = array();
        $holidays = $this->db->get_where('holiday',array('schoolID'=>$schoolID,'date >='=>$first_date,'date <='=>$last_date))->result();
        foreach ($holidays as $h){
            $holiday_array[] = array(
                'wdate' => $h->date,
                'status' => 'H'
            );
        }
        for($i = $begin; $i <= $end; $i->modify('+1 day')){
            $d = $i->format("d");
            $d1 = $i->format("Y-m-d");
            $a = "a".abs($d);
            if (count($check)) {
                $b = $check->$a;
            }else{
                $b = '';
            }

            if (is_null($b)){
                $b = '';
            }
            if ($b != 'P'){
                foreach ($holiday_array as $key => $val) {
                    if ($val['wdate'] == $d1) {
                        $b = 'H';
                        break;
                    }
                }
            }
            $alldates[] = array(
                'wdate' => $d1,
                'status' => $b
            );
        }

        $response[] = array('result'=>'success','alldates'=>$alldates);
        echo json_encode($response);
    }
    public function student_subject_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$studentID));
        $classID = $get_student->classID;
        $sectionID = $get_student->sectionID; 
        $get_subjects = $this->webservice_m->get_all_data_where('subject',array('schoolID'=>$schoolID, 'classID'=>$classID, 'sectionID'=>$sectionID));
        $subjects = array();
        foreach($get_subjects as $gs)
        {
            $subjects[] = array(
                'subjectID' => $gs->subjectID,
                'subject_name' => $gs->subject_name,
                'subject_code' => $gs->subject_code,
                'type' => $gs->type,
                'syllabus' => base_url('uploads/subject/').$gs->syllabus 
            );
        }
        $response[] = array('result'=>'success','subjects'=>$subjects);
        echo json_encode($response);

    }
    public function subject_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $get_subjects = $this->webservice_m->get_all_data_where('subject',array('schoolID'=>$schoolID, 'classID'=>$classID, 'sectionID'=>$sectionID));
        $subjects = array();
        foreach($get_subjects as $gs)
        {
            $subjects[] = array(
                'subjectID' => $gs->subjectID,
                'subject_name' => $gs->subject_name,
                'subject_code' => $gs->subject_code,
                'type' => $gs->type,
                'syllabus' => base_url('uploads/subject/').$gs->syllabus 
            );
        }
        $response[] = array('result'=>'success','subjects'=>$subjects);
        echo json_encode($response);

    }
    public function contact_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $get_contacts = $this->webservice_m->get_all_data_where('school_contacts',array('schoolID'=>$schoolID));
        $response[] = array('result'=>'success','contacts'=>$get_contacts);
        echo json_encode($response);
    }
    public function add_suggestion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $teacherID = $data['teacherID'];
        $title = $data['title'];
        $suggestion = $data['suggestion'];

        $array = array(
            'schoolID' => $schoolID,
            'teacherID' => $teacherID,
            'studentID' => $studentID,
            'title' => $title,
            'suggestion' => $suggestion,
            'status' => 'open',
            'added_on' => date('Y-m-d H:i:s')
        );
        $suggestionID = $this->webservice_m->table_insert('suggestion',$array);

        $new_array = array(
            'suggestionID' => $suggestionID,
            'text' => $suggestion,
            'userID' => $studentID,
            'usertype' => 'student',
            'added_on' => date('Y-m-d H:i:s')
        );
        $this->webservice_m->table_insert('suggestion_message',$new_array);
        $response[] = array('result'=>'success','suggestionID'=>$suggestionID);
        echo json_encode($response);

    }
    public function reply_suggestion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $suggestionID = $data['suggestionID'];
        $userID = $data['userID'];
        $usertype = $data['usertype'];
        $suggestion = $data['reply_text'];

        $new_array = array(
            'suggestionID' => $suggestionID,
            'text' => $suggestion,
            'userID' => $userID,
            'usertype' => $usertype,
            'added_on' => date('Y-m-d H:i:s')
        );
        $this->webservice_m->table_insert('suggestion_message',$new_array);
        $response[] = array('result'=>'success','suggestionID'=>$suggestionID);
        echo json_encode($response);

    }
    public function list_student_suggestion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $studentID = $data['studentID'];
        $get_suggestion = $this->webservice_m->get_all_data_where('suggestion',array('studentID'=>$studentID),'suggestionID','DESC');
        $suggestions = array();
        foreach($get_suggestion as $gs)
        {
            $get_teacher = $this->webservice_m->get_single_table('teacher',array('teacherID'=>$gs->teacherID));
            $suggestions[] = array(
                'suggestionID' => $gs->suggestionID,
                'schoolID' => $gs->schoolID,
                'teacherID' => $gs->teacherID,
                'teacher_name' => ucfirst($get_teacher->name),
                'studentID' => $gs->studentID,
                'title' => $gs->title,
                'suggestion' => $gs->suggestion,
                'status' => $gs->status,
                'added_on' => date('d M Y H:i A', strtotime($gs->added_on))
            );
        }
        $response[] = array('result'=>'success','suggestions'=>$suggestions);
        echo json_encode($response);
    }
    public function list_teacher_suggestion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $teacherID = $data['teacherID'];
        $get_suggestion = $this->webservice_m->get_all_data_where('suggestion',array('teacherID'=>$teacherID),'suggestionID','DESC');
        $suggestions = array();
        foreach($get_suggestion as $gs)
        {
            $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$gs->studentID));
            $suggestions[] = array(
                'suggestionID' => $gs->suggestionID,
                'schoolID' => $gs->schoolID,
                'teacherID' => $gs->teacherID,
                'student_name' => ucfirst($get_student->name),
                'studentID' => $gs->studentID,
                'title' => $gs->title,
                'suggestion' => $gs->suggestion,
                'status' => $gs->status,
                'added_on' => date('d M Y H:i A', strtotime($gs->added_on))
            );
        }
        $response[] = array('result'=>'success','suggestions'=>$suggestions);
        echo json_encode($response);
    }
    public function list_admin_suggestion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $get_suggestion = $this->webservice_m->get_all_data_where('suggestion',array('schoolID'=>$schoolID),'suggestionID','DESC');
        $suggestions = array();
        foreach($get_suggestion as $gs)
        {
            $get_teacher = $this->webservice_m->get_single_table('teacher',array('teacherID'=>$gs->teacherID));
            $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$gs->studentID));
            $suggestions[] = array(
                'suggestionID' => $gs->suggestionID,
                'schoolID' => $gs->schoolID,
                'teacherID' => $gs->teacherID,
                'teacher_name' => ucfirst($get_teacher->name),
                'student_name' => ucfirst($get_student->name),
                'studentID' => $gs->studentID,
                'title' => $gs->title,
                'suggestion' => $gs->suggestion,
                'status' => $gs->status,
                'added_on' => date('d M Y H:i A', strtotime($gs->added_on))
            );
        }
        $response[] = array('result'=>'success','suggestions'=>$suggestions);
        echo json_encode($response);
    }
    public function view_suggestion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $suggestionID = $data['suggestionID'];
        $get_suggestion = $this->webservice_m->get_all_data_where('suggestion_message',array('suggestionID'=>$suggestionID),'added_on','ASC');
        $suggestions = array();
        foreach($get_suggestion as $gs)
        {
            if($gs->usertype == 'school')
            {
                $get_name = $this->webservice_m->get_single_table('school_registration',array('schoolID'=>$gs->userID));
            } elseif($gs->usertype == 'student') {
                $get_name = $this->webservice_m->get_single_table('student',array('studentID'=>$gs->userID));
            } else {
                $get_name = $this->webservice_m->get_single_table('teacher',array('teacherID'=>$gs->userID));
            }

            $suggestions[] = array(
                'messageID' => $gs->messageID,
                'suggestionID' => $gs->suggestionID,
                'text' => $gs->text,
                'userID' => $gs->userID,
                'usertype' => $gs->usertype,
                'name' => ucfirst($get_name->name),
                'added_on' => date('d M Y H:i A', strtotime($gs->added_on))
            );
            
        }
        $response[] = array('result'=>'success','suggestions'=>$suggestions);
        echo json_encode($response);

    }
    public function gallery()
    {
       $path = base_url('uploads/gallery/');
       $data = json_decode(file_get_contents('php://input'), true); 
       $schoolID = $data['schoolID'];
       $usertype = $data['usertype'];
       $gallery = array();
       $images = array();
       $gallery_images = $this->webservice_m->get_the_gallery($schoolID,$usertype);
       foreach($gallery_images as $ga)
       {

         $gallery[] = array(
            'folderID' => $ga->folderID,
            'folder_name' => $ga->folder_name,
            'image' => $path.'foldericon.png',
            'images' => $this->folder_images($ga->folderID, $schoolID, $usertype)
         );
       }    

       $gallery_images1 = $this->webservice_m->gallery_images1($schoolID,$usertype);
       foreach($gallery_images1 as $ga1)
       {

         $gallery[] = array(
            'folderID' => '0',
            'folder_name' => '',
            'image' => $path.$ga1->image,
            'images' => $images
         );
       }  

       $response[] = array('result' => 'success' ,"gallery"=>$gallery );
       echo json_encode($response);  

    }
    public function folder_images($folderID, $schoolID, $usertype)
    {
        $path = base_url('uploads/gallery/');
        $image = array();
        $query = $this->db->query("SELECT `image` FROM `gallery` WHERE `folderID` = '$folderID' AND `student`='Y' AND `schoolID`='$schoolID'");

        foreach($query->result() as $rs)
        {
            $image[] = array(
                'img'=>$path.$rs->image
            );
        }
        return $image;
    }
    public function frame_topics()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        //$path = base_url('uploads/frame_topics/');
        $topics =  array();
        $get_frames = $this->webservice_m->get_frames_topics();
        foreach($get_frames as $gf)
        {
            $topics[] =  array(
                'subID' => $gf->subID,
                'topic' => $gf->frame_topic
            );
        }
        $response[] = array("result"=>"success","topics"=>$topics);
        echo json_encode($response);
    }
    public function school_frames()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $subID = $data['subID'];
        $path = base_url('uploads/frames/');
        $frames =  array();
        $get_frames = $this->webservice_m->get_frames($schoolID,$subID);
        foreach($get_frames as $gf)
        {
            $frames[] =  array(
                'frameID' => $gf->frameID,
                'topic' => $gf->frame_topic,
                'image' => $path.$gf->image
            );
        }
        $response[] = array("result"=>"success","frames"=>$frames, "allowed_frame"=>'25');
        echo json_encode($response);
    }
    public function add_event()
    {
        //$data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $this->input->post('schoolID');
        $userID = $this->input->post('userID');
        $userType = $this->input->post('userType');
        $event = $this->input->post('event');
        $description = $this->input->post('description');
        $bus_required = $this->input->post('bus_required');
        $no_of_bus = $this->input->post('no_of_bus');
        $estimate_expense = $this->input->post('estimate_expense');
        $fee_per_student = $this->input->post('fee_per_student');
        $fee_per_user = $this->input->post('fee_per_user');

        $start_on = date('Y-m-d H:i:s',strtotime($this->input->post('start_on')));
        $end_on = date('Y-m-d H:i:s',strtotime($this->input->post('end_on')));
        $location = $this->input->post('location');     
        $created_on=date('Y-m-d H:i:s');
        $path = base_url('uploads/');
        
        $start = date('Y-m-d',strtotime($start_on));
        $end = date('Y-m-d',strtotime($end_on));
        

        // if(strtotime($start) > strtotime($end))
        // {
        //  $response[] = array("result"=>"error", "message"=>"Event Start time should be less than End time");
        // } 
        //  else {
            $actual_image_name = '';
            $target_path = "uploads/"; // replace this with the path you are going to save the file to
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if(is_array($_FILES)) 
            {
                foreach($_FILES as $fileKey => $fileVal)
                {
                    $imagename = basename($_FILES["image"]["name"]);
                    $extension = substr(strrchr($_FILES['image']['name'], '.'), 1);
                    if(!empty($extension))
                    {
                        $actual_image_name = time().".".$extension;
                    }

                    move_uploaded_file($_FILES["image"]["tmp_name"],$target_path.$actual_image_name);
                                      
                }
            }

            $array = array(
                "schoolID" => $schoolID,
                "userID" =>$userID,
                "userType" => $userType,
                "event" => $event,
                "start_on" => $start_on,
                "end_on" => $end_on,
                "location" => $location,
                "event_image" => $actual_image_name,
                "description" => $description,
                "bus_required" => $bus_required,
                "no_of_bus" => $no_of_bus,
                "estimate_expense" => $estimate_expense,
                "fee_per_student" => $fee_per_student,
                "fee_per_user" => $fee_per_user,
                "status" => "Y",
                "added_on" => $created_on,
            );
            $eventID = $this->webservice_m->event($array);
            if ($eventID){
                $device_info = $this->db->get_where('user_login',array('userID'=>$schoolID,'role'=>'school'))->result();
                foreach ($device_info as $d){
                    $device_token = $d->device_token;
                    $data1 = '{"notification_type":"text","title":"Event Added","msg":"An Event has been created in your school.","type":"event","role":"school"}';
                    $data1 = array("m" => $data1);
                    $this->fcmNotification($device_token, $data1);
                }
                $data2 = array(
                    "schoolID"=>$schoolID,
                    "userID"=>$schoolID,
                    "role"=>"school",
                    "title" => "Event Added",
                    "notification"=>"An Event has been created in your school",
                    "status"=>1,
                    "added_on"=>date("Y-m-d H:i:s")
                );
                $this->db->insert('notification',$data2);
                $this->db->select('studentID');
                $members = $this->db->get_where('student',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                foreach ($members as $m){
                    $this->db->select('device_token');
                    $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                    foreach ($device_info as $d){
                        $device_token = $d->device_token;
                        $data1 = '{"notification_type":"text","title":"Event Added","msg":"An Event has been created in your school.","type":"event","role":"student"}';
                        $data1 = array("m" => $data1);
                        $this->fcmNotification($device_token, $data1);
                    }
                    $data2 = array(
                        "schoolID"=>$schoolID,
                        "userID"=>$m->studentID,
                        "role"=>"student",
                        "title" => "Event Added",
                        "notification"=>"An Event has been created in your school.",
                        "status"=>1,
                        "added_on"=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert('notification',$data2);
                }
                $this->db->select('teacherID');
                $members = $this->db->get_where('teacher',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                foreach ($members as $m){
                    $this->db->select('device_token');
                    $device_info = $this->db->get_where('user_login',array('userID'=>$m->teacherID,'role'=>'teacher'))->result();
                    foreach ($device_info as $d){
                        $device_token = $d->device_token;
                        $data1 = '{"notification_type":"text","title":"Event Added","msg":"An Event has been created in your school.","type":"event","role":"teacher"}';
                        $data1 = array("m" => $data1);
                        $this->fcmNotification($device_token, $data1);
                    }
                    $data2 = array(
                        "schoolID"=>$schoolID,
                        "userID"=>$m->teacherID,
                        "role"=>"teacher",
                        "title" => "Event Added",
                        "notification"=>"An Event has been created in your school.",
                        "status"=>1,
                        "added_on"=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert('notification',$data2);
                }
            }
            $response[] = array("result"=>"success", "message"=>"Event Successfully Added");
            
            // }
        
        echo json_encode($response);
    }
    public function event_list()//error
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $userType = $data['usertype'];
        $schoolID = $data['schoolID'];
        $d = date('Y-m-d');
        $path = base_url('uploads/');
        $event = array();
        $events = $this->webservice_m->event_list($d,$schoolID);
        foreach($events as $e)
        {
            $event[] = array(
                "eventID" => $e->eventID,
                "event" => $e->event,
                "event_image" => $path.$e->event_image,
                "description" => $e->description,
                "start_on" => date('d M Y',strtotime($e->start_on)),
                "et" => date('d M H:i',strtotime($e->start_on)).' - '.date('d M H:i',strtotime($e->end_on)),
                "day" => date('D H:i',strtotime($e->start_on)),
                "end_on" => date('d M Y',strtotime($e->end_on)),
                "location" => $e->location,
                "bus_required" => $e->bus_required,
                "no_of_bus" => $e->no_of_bus,
                "estimate_expense" => $e->estimate_expense,
                "fee_per_student" => $e->fee_per_student,
                "fee_per_user" => $e->fee_per_user,
                "no_of_interested" => $this->no_of_action($e->eventID,"interested"),
                "no_of_going" => $this->no_of_action($e->eventID,"going"),
                "interested_status" => $this->action_status($e->eventID,$userID,$userType,"interested"),
                "going_status" => $this->action_status($e->eventID,$userID,$userType,"going")
            );
        }
        $response[] = array("result"=>"success", "event"=>$event);
            
       
        
        echo json_encode($response);
    }
    public function no_of_action($eventID,$action_type)
    {
        $query = $this->db->get_where('event_activity',array('eventID'=>$eventID, 'action_type'=>$action_type));
        return count($query->result());
    }
    public function action_status($eventID,$userID,$userType,$action_type)
    {
        $query = $this->db->get_where('event_activity',array('eventID'=>$eventID, 'userID'=>$userID, 'userType'=>$userType, 'action_type'=>$action_type));
        if(count($query->row()))
        {
            return 'Y';
        } else {
            return 'N';
        }
    }
    public function event_detail()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $eventID = $data['eventID'];
        $userID = $data['userID'];
        $userType = $data['usertype'];
        $schoolID = $data['schoolID'];
        $path = base_url('uploads/');

        $e = $this->webservice_m->event_detail($eventID);
        $event[] = array(
            "eventID" => $e->eventID,
            "event" => $e->event,
            "event_image" => $path.$e->event_image,
            "description" => $e->description,
            "start_on" => date('d M Y',strtotime($e->start_on)),
            "end_on" => date('d M Y',strtotime($e->end_on)),
            "location" => $e->location,
            "no_of_interested" => $this->no_of_action($e->eventID,"interested"),
            "no_of_going" => $this->no_of_action($e->eventID,"going"),
            "interested_status" => $this->action_status($e->eventID,$userID,$userType,"interested"),
            "going_status" => $this->action_status($e->eventID,$userID,$userType,"going")
        );
        $response[] = array("result"=>"success", "event"=>$event);    
        echo json_encode($response);
    }
    public function event_activity()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $eventID = $data['eventID'];
        $userID = $data['userID'];
        $userType = $data['usertype'];
        $action_type = $data['action_type'];
        $created_on = date("Y-m-d H:i:s");
        $path = base_url('uploads/');

        $check_event = $this->webservice_m->check_event(array("eventID"=>$eventID,"userID"=>$userID,"userType"=>$userType,"action_type"=>$action_type));
        
        if(count($check_event)>0)
        {

            $event = $this->webservice_m->event_deleted($check_event->activityID);
            $message = "Successfully Action Remove";
            
        } else {
            $event = $this->webservice_m->event_activity(array("eventID"=>$eventID,"userID"=>$userID,"userType"=>$userType,"action_type"=>$action_type, "added_on"=>$created_on));
            $message = "Successfully Action Added";
        }
        $no_of_interested = $this->no_of_action($eventID,"interested");
        $no_of_going = $this->no_of_action($eventID,"going");
        $interested_status = $this->action_status($eventID,$userID,$userType,"interested");
        $going_status = $this->action_status($eventID,$userID,$userType,"going");

        $response[] = array("result"=>"success", "message"=>$message , "no_of_interested"=>$no_of_interested, "no_of_going"=>$no_of_going, "interested_status"=>$interested_status, "going_status"=>$going_status);
        echo json_encode($response);
    }
    public function announcement()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $path = base_url('uploads/');
        $schoolID = $data['schoolID'];
        $this->db->select("noticeID,title,notice,CONCAT('".$path."',COALESCE(NULLIF(attachment, ''),'default.png')) as attachment,added_on");
        $query = $this->db->get_where('notice',array('schoolID'=>$schoolID))->result();

        $response[] = array('result'=>'success','announcement'=>$query);
        echo json_encode($response);
    }
    public function add_announcement(){
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $title = $data['title'];
        $notice = $data['notice'];
        $this->db->insert('notice',array('schoolID'=>$schoolID,'title'=>$title,'notice'=>$notice,'added_on'=>date("Y-m-d H:i:s")));
        $noticeID = $this->db->insert_id();
        $response[] = array('result'=>'success','noticeID'=>$noticeID);
        echo json_encode($response);
    }
    public function add_assignment()
    {
        $teacherID =  $this->input->post('teacherID');
        $classID =  $this->input->post('classID');
        $sectionID =  $this->input->post('sectionID');
        $subjectID =  $this->input->post('subjectID');
        $schoolID =  $this->input->post('schoolID');

        $d = date('Y-m-d h:i:s');
      
        
        $target_path = "uploads/assignment/"; // replace this with the path you are going to save the file to
        $target_dir = "uploads/assignment/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if(is_array($_FILES)) 
        {
            foreach($_FILES as $fileKey => $fileVal)
            {
                $imagename = basename($_FILES["image"]["name"]);
                $extension = substr(strrchr($_FILES['image']['name'], '.'), 1);
                $actual_image_name = time().".".$extension;

                move_uploaded_file($_FILES["image"]["tmp_name"],$target_path.$actual_image_name);

                 $array = array(
                    'schoolID' => $schoolID,
                    'teacherID' => $teacherID,
                    'classesId' => $classID,
                    'sectionID' => $sectionID,
                    'subjectID' => $subjectID,
                    'remark' => $this->input->post("title"),
                    'doc_file' => $actual_image_name,
                    'last_date' => date('Y-m-d',strtotime($this->input->post("due_date"))),
                    'added_on' => $d
                );
                $vv = $this->webservice_m->table_insert('assignment',$array);
            }

        }

     

        if($vv)
        {
            $this->db->select('studentID');
            $members = $this->db->get_where('student',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID,'is_active'=>'Y'))->result();
            foreach ($members as $m){
                $this->db->select('device_token');
                $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                foreach ($device_info as $d){
                    $device_token = $d->device_token;
                    $data1 = '{"notification_type":"text","title":"Assignment","msg":"A new assignment has been posted.","type":"assignment","role":"student"}';
                    $data1 = array("m" => $data1);
                    $this->fcmNotification($device_token, $data1);
                }
                $data2 = array(
                    "schoolID"=>$schoolID,
                    "userID"=>$m->studentID,
                    "role"=>"student",
                    "title" => "Assignment",
                    "notification"=>"A new assignment has been posted.",
                    "status"=>1,
                    "added_on"=>date("Y-m-d H:i:s")
                );
                $this->db->insert('notification',$data2);
            }
            $data[] = array(
                "result" => "success",
                "message" => "File Successfully Uploaded",
                "file_name" => $actual_image_name,
                "file_size" => $_FILES['image']['size']
            );
        }
        else
        {
            $data[] = array(
                "result" => "failure",
                "message" => "Error in File Upload"
            );
        }
  
        
        echo json_encode($data);
    }
    public function teacher_assignment()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $path = base_url('uploads/assignment/');
        $schoolID = $data['schoolID'];
        $teacherID = $data['teacherID'];
        if (array_key_exists('submit_date',$data)){
            if ($data['submit_date']==""){
                $date = "";
            }else{
                $date = str_replace("/","-",$data['submit_date']);
                $date = date("Y-m-d",strtotime($date));
            }
        }else{
            $date = "";
        }
        if (array_key_exists('subjectID',$data)){
            $subjectID = $data['subjectID'];
        }else{
            $subjectID = "";
        }
        if ($date != "" && $subjectID != ""){
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('teacherID'=>$teacherID,'schoolID' => $schoolID, 'date(last_date)'=>$date, 'subjectID'=>$subjectID),'assignmentID','DESC');
        }elseif ($date != "" && $subjectID == ""){
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('teacherID'=>$teacherID,'schoolID' => $schoolID, 'date(last_date)'=>$date),'assignmentID','DESC');
        }elseif ($date == "" && $subjectID != ""){
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('teacherID'=>$teacherID,'schoolID' => $schoolID, 'subjectID'=>$subjectID),'assignmentID','DESC');
        }else{
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('teacherID'=>$teacherID,'schoolID' => $schoolID),'assignmentID','DESC');
        }
        $assignments = array();
        foreach($get_assignments as $a)
        {
            $sub = $this->webservice_m->get_single_table('subject',array('subjectID'=>$a->subjectID));
            $assignments[] = array(
                'assignmentID' => $a->assignmentID,
                'schoolID' => $a->schoolID,
                'subjectID' => $a->subjectID,
                'subject' => $sub->subject_name,
                'no_of_submission'=> $this->no_of_submission($a->assignmentID),
                'remark' => $a->remark,
                'doc_file' => $path.$a->doc_file,
                'last_date' => date('d M Y',strtotime($a->last_date))
            );
        }
        $response[] = array('result'=>'success','assignments'=>$assignments);
        echo json_encode($response);

    }
    public function no_of_submission($assignmentID)
    {
        $query = $this->webservice_m->get_single_table_query("SELECT COALESCE(COUNT(`submissionID`),0) as submission_count  FROM `assignment_submit` WHERE `assignmentID`='$assignmentID'");
        return $query->submission_count;
    }
    public function student_assignment()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $path = base_url('uploads/assignment/');
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$studentID));
        $classID = $get_student->classID;
        $sectionID = $get_student->sectionID;
        if (array_key_exists('submit_date',$data)){
            $date = str_replace("/","-",$data['submit_date']);
            $date = date("Y-m-d",strtotime($data['submit_date']));
        }else{
            $date = "";
        }
        if (array_key_exists('subjectID',$data)){
            $subjectID = $data['subjectID'];
        }else{
            $subjectID = "";
        }
        if ($date != "" && $subjectID != ""){
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('classesId'=>$classID, 'sectionID'=>$sectionID, 'date(last_date)'=>$date, 'subjectID'=>$subjectID),'assignmentID','DESC');
        }elseif ($date != "" && $subjectID == ""){
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('classesId'=>$classID, 'sectionID'=>$sectionID, 'date(last_date)'=>$date),'assignmentID','DESC');
        }elseif ($date == "" && $subjectID != ""){
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('classesId'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID),'assignmentID','DESC');
        }else{
            $get_assignments = $this->webservice_m->get_all_data_where('assignment',array('classesId'=>$classID, 'sectionID'=>$sectionID),'assignmentID','DESC');
        }
        $assignments = array();
        foreach($get_assignments as $a)
        {
            $sub = $this->webservice_m->get_single_table('subject',array('subjectID'=>$a->subjectID));
            $teacher = $this->webservice_m->get_single_table('teacher',array('teacherID'=>$a->teacherID));
            $assignments[] = array(
                'assignmentID' => $a->assignmentID,
                'schoolID' => $a->schoolID,
                'subjectID' => $a->subjectID,
                'subject' => $sub->subject_name,
                'teacher_name' => $teacher->name,
                'remark' => $a->remark,
                'doc_file' => $path.$a->doc_file,
                'last_date' => date('d M Y',strtotime($a->last_date)),
                'submit_status' => $this->check_submit_status($a->assignmentID,$studentID)
            );
        }
        $response[] = array('result'=>'success','assignments'=>$assignments);
        echo json_encode($response);
    }
    public function check_submit_status($assignmentID,$studentID)
    {
        $query = $this->webservice_m->get_single_table('assignment_submit',array('assignmentID'=>$assignmentID,'studentID'=>$studentID));
        if(count($query))
        {
            return 'Y';
        } else {
            return 'N';
        }
    }
    public function assignment_upload()
    {
        $assignmentID =  $this->input->post('assignmentID');
        $studentID =  $this->input->post('studentID');
        $d = date('Y-m-d h:i:s');
      
        
        $target_path = "uploads/assignment/"; // replace this with the path you are going to save the file to
        $target_dir = "uploads/assignment/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if(is_array($_FILES)) 
        {
            foreach($_FILES as $fileKey => $fileVal)
            {
                $imagename = basename($_FILES["image"]["name"]);
                $extension = substr(strrchr($_FILES['image']['name'], '.'), 1);
                $actual_image_name = time().".".$extension;

                move_uploaded_file($_FILES["image"]["tmp_name"],$target_path.$actual_image_name);

                 $array = array(
                    'assignmentID' => $assignmentID,
                    'studentID' => $studentID,
                    'doc_file' => $actual_image_name,
                    'added_on' => $d
                );
                $vv = $this->webservice_m->table_insert('assignment',$array);
            }

        }
        if($vv)
        {
            $data[] = array(
                "result" => "success",
                "message" => "File Successfully Uploaded",
                "file_name" => $actual_image_name,
                "file_size" => $_FILES['image']['size']
            );
        }
        else
        {
            $data[] = array(
                "result" => "failure",
                "message" => "Error in File Upload"
            );
        }
  
        
        echo json_encode($data);
    }
    /*Kshitij*/
    public function routine()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $slot_array = array();
        $routine = array();
        if(strtolower($data['role']) == "school")
        {
            $schoolID = $data['schoolID'];
            $classID = $data['classID'];
            $sectionID = $data['sectionID'];
            $day = strtoupper($data['day']);
        }elseif (strtolower($data['role']) == "student" || strtolower($data['role']) == "parent")
        {
             
            $classID = 0;
            $sectionID = 0;
            $schoolID = $data['schoolID'];
            $userID = $data['studentID'];
            $day = strtoupper($data['day']);
            $user_info = $this->db->get_where('student',array('studentID'=>$userID,'schoolID'=>$schoolID,'is_active'=>"Y",'subscription_status'=>"Y"))->row();
            //echo $this->db->last_query();
            if (count($user_info))
            {
                $classID = $user_info->classID;
                $sectionID = $user_info->sectionID;
            }
        }elseif (strtolower($data['role']) == "teacher")
        {
            $schoolID = $data['schoolID'];
            $teacherID = $data['teacherID'];
            $day = strtoupper($data['day']);
        }
        if (strtolower($data['role']) == "school" || strtolower($data['role']) == "student" || strtolower($data['role']) == "parent"){
            $slot = $this->db->get_where('slots',array('classID'=>$classID , 'schoolID'=>$schoolID))->row();
            //echo $this->db->last_query();
                
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
            foreach ($slot_array as $s){
                $lecture = $s['lecture'];
                $this->db->select('teacher.name as teacher_name,subject.subject_name');
                $this->db->join('teacher','routine.teacherID = teacher.teacherID','LEFT');
                $this->db->join('subject','routine.subjectID = subject.subjectID','LEFT');
                $get_routine = $this->db->get_where('routine',array('routine.sectionID'=>$sectionID,'routine.classID'=>$classID ,'routine.schoolID'=>$schoolID,'routine.day'=>$day,'routine.lecture'=>$lecture))->row();
                if (count($get_routine)){
                    if (is_null($get_routine->subject_name)){
                        $subject = "";
                        $teacher = $get_routine->teacher_name;
                    }elseif (is_null($get_routine->teacher_name)){
                        $teacher = "";
                        $subject = $get_routine->subject_name;
                    }else{
                        $subject = $get_routine->subject_name;
                        $teacher = $get_routine->teacher_name;
                    }
                    $routine[] = array('lecture'=>$lecture,'start_time'=>$s['start_time'],'end_time'=>$s['end_time'],'type'=>$s['type'],'subject'=>$subject,'teacher'=>$teacher);

                }else{
                    $routine[] = array('lecture'=>$lecture,'start_time'=>$s['start_time'],'end_time'=>$s['end_time'],'type'=>$s['type'],'subject'=>"",'teacher'=>"");
                }
            }
        }elseif (strtolower($data['role']) == "teacher")
        {
            $this->db->select('routine.classID,routine.lecture,classes.class,section.section,teacher.name as teacher_name,subject.subject_name');
            $this->db->join('classes','routine.classID = classes.classID','LEFT');
            $this->db->join('teacher','routine.teacherID = teacher.teacherID','LEFT');
            $this->db->join('section','routine.sectionID = section.sectionID','LEFT');
            $this->db->join('subject','routine.subjectID = subject.subjectID','LEFT');
            $routine_array = $this->db->get_where('routine',array('routine.schoolID'=>$schoolID,'routine.teacherID'=>$teacherID,'routine.day'=>$day))->result();
            if (count($routine_array)){
                foreach ($routine_array as $r)
                {
                    $slot = $this->db->get_where('slots',array('classID'=>$r->classID , 'schoolID'=>$schoolID))->row();
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
                            $slot_array = array('lecture'=>$count,'start_time'=>$start_time,'end_time'=>$b_start_time,'type'=>'before');
                            if ($slot_array['lecture'] == $r->lecture){
                                $routine[] = array('lecture'=>"",'start_time'=>$slot_array['start_time'],'end_time'=>$slot_array['end_time'],'type'=>$slot_array['type'],'subject'=>$r->subject_name,'teacher'=>$r->teacher_name,'class'=>$r->class,'section'=>$r->section);
                                break;
                            }
                        }
                        $count++;
                        $slot_array = array('lecture'=>$count,'start_time'=>$b_end_time,'end_time'=>$a_start_time,'type'=>'interval');
                        while (strtotime("+$a_slot_duration minutes" , strtotime($a_start_time)) <= strtotime($a_end_time))
                        {
                            $count++;
                            $start_time = $a_start_time;
                            $a_start_time = date("H:i",strtotime("+$a_slot_duration minutes" , strtotime($a_start_time)));
                            $slot_array = array('lecture'=>$count,'start_time'=>$start_time,'end_time'=>$a_start_time,'type'=>'after');
                            if ($slot_array['lecture'] == $r->lecture){
                                $routine[] = array('lecture'=>"",'start_time'=>$slot_array['start_time'],'end_time'=>$slot_array['end_time'],'type'=>$slot_array['type'],'subject'=>$r->subject_name,'teacher'=>$r->teacher_name,'class'=>$r->class,'section'=>$r->section);
                                break;
                            }
                        }
                    }
                }
            }
        }
        function cmp($a, $b)
        {
            return strcmp(strtotime($a["start_time"]), strtotime($b["start_time"]));
        }
        usort($routine, "cmp");
        $response[] = array("result"=>"success","routine"=>$routine);
        echo json_encode($response);
    }
    /*END Kshitij*/
    public function get_exams()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $rs = $this->webservice_m->get_all_table_query("SELECT `examID`, `exam_title`, `exam_note`, `exam_status`, `exam_class`, `exam_section`, `exam_date` FROM `exam` WHERE `schoolID`='$schoolID' AND `exam_class`='$classID' AND FIND_IN_SET('$sectionID',`exam_section`)");
        if(count($rs)>0)
        {
            $response[] = array('result'=>'success','exams'=>$rs);
        
        } else {
            $response[] = array('result'=>'failure');
        }
        echo json_encode($response);
    }
    public function get_exams_for_student()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$studentID));
        if (count($get_student)) {
            $classID = $get_student->classID;
            $sectionID = $get_student->sectionID;
            $rs = $this->webservice_m->get_all_table_query("SELECT `examID`, `exam_title`, `exam_note`, `exam_status`, `exam_class`, `exam_section`, `exam_date` FROM `exam` WHERE `schoolID`='$schoolID' AND `exam_class`='$classID' AND FIND_IN_SET('$sectionID',`exam_section`)");
            if(count($rs)>0)
            {
                $response[] = array('result'=>'success','exams'=>$rs);
            
            } else {
                $response[] = array('result'=>'failure');
            }
        }else{
            $response[] = array('result'=>'failure','response'=>'Please provide a valid data');
        }
        
        echo json_encode($response);
    }
    public function get_result_report()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $examID = $data['examID'];
        $studentID = $data['studentID'];
        $rs = $this->webservice_m->get_all_data_where('exam_result',array('examID'=>$examID, 'studentID'=>$studentID));
        $get_student = $this->webservice_m->get_single_table('student',array('studentID'=>$studentID));
        $response[] = array('result'=>'success','student_name'=>$get_student->name,'result'=>$rs);
        /*if(count($rs)>0)
        {
            $response[] = array('result'=>'success','student_name'=>$get_student->name,'result'=>$rs);
        
        } else {
            $response[] = array('result'=>'failure');
        }*/
        echo json_encode($response);
    }
    public function change_profile()
    {
        $role = $this->input->post('role');
        $userID = $this->input->post('userID');
        $schoolID = $this->input->post('schoolID');
        $target_path = "uploads/images/"; // replace this with the path you are going to save the file to
        $target_dir = "uploads/images/";
        $path = base_url("uploads/images/");
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if(is_array($_FILES)) 
        {
            foreach($_FILES as $fileKey => $fileVal)
            {
                $vv = 0;
                $imagename = basename($_FILES["image"]["name"]);
                $extension = substr(strrchr($_FILES['image']['name'], '.'), 1);
                $actual_image_name = $role.time().".".$extension;

                move_uploaded_file($_FILES["image"]["tmp_name"],$target_path.$actual_image_name);
                if (strtolower($role) == "school") {
                    $array = array(
                        'image' => $actual_image_name,
                        'modified_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->where(array('schoolID' => $userID));
                    $vv = $this->db->update('school_registration',$array);
                }elseif (strtolower($role) == "teacher") {
                    $array = array(
                        'image' => $actual_image_name
                    );
                    $this->db->where(array('teacherID' => $userID));
                    $vv = $this->db->update('teacher',$array);
                }elseif (strtolower($role) == "student") {
                    $array = array(
                        'image' => $actual_image_name
                    );
                    $this->db->where(array('studentID' => $userID));
                    $vv = $this->db->update('student',$array);
                }elseif (strtolower($role) == "parent") {
                    $array = array(
                        'image' => $actual_image_name
                    );
                    $this->db->where(array('parentID' => $userID));
                    $vv = $this->db->update('parent',$array);
                }
                if ($vv) 
                {
                    $response[] = array('result'=>"success",'response'=>"image updated successfully",'image'=>$path.$actual_image_name);
                }else{
                    $response[] = array('result'=>"failure",'response'=>"failure");
                }                      
            }

        }else{
            $response[] = array('result'=>"failure",'response'=>"please choose an image");
        }
        echo json_encode($response);
    }
    public function student_attendance_student()
    {
        $studentID = $this->input->post('userID');
        $schoolID = $this->input->post('schoolID');
        $subjectID = 0;
        $userID = $studentID;
        $usertype = "self";
        $tdate = date("Y-m-d");
        $month_year = date('m-Y',strtotime($tdate));
        $d = date('d',strtotime($tdate));
        $a = "a".abs($d);
        if(is_array($_FILES)) 
        {
            foreach($_FILES as $fileKey => $fileVal)
            {
                $student = $this->db->query("SELECT `image` FROM `student` WHERE `studentID` = '$studentID' AND `schoolID` = '$schoolID'")->row();
                if (count($student)) {
                    if (is_null($student->image) || $student->image == "" || $student->image == "default.png") {
                        $response[] = array('result'=>"failure",'response'=>"please set a profile picture first");
                    }else{
                        $target_url = "https://api-us.faceplusplus.com/facepp/v3/compare";           
                        $tmpfile = $_FILES['image']['tmp_name'];
                        $filename2 = "image2.jpg";
                        $filename1 = "image1.jpg";
                        $filetype1 = "image/jpeg"; 
                        $file1 = '/var/www/html/mentor/customer_portal/uploads/images/'.$student->image;
                        $data = array(
                            'api_key' => 'ExRcCcc584DRN2mnvvC13Ghdv1x2Upai',
                            'api_secret' => '4YHwIRGIg4j5eboPprrOwUdCQ7lrBULQ',
                            'image_file1' => curl_file_create($file1, 'image/jpeg',$filename1),
                            'image_file2' => curl_file_create($tmpfile, $_FILES['image']['type'], $filename2)
                        );
                        $ch = curl_init();  
                        curl_setopt($ch, CURLOPT_URL, $target_url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data'));
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                        /*curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );*/
                        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $result = curl_exec ($ch);
                        if(!curl_exec($ch)){
                            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
                        }
                        if ($result) {
                            $json_decoded = json_decode($result,true);
                            if (array_key_exists("confidence",$json_decoded)) {
                                if ($json_decoded['confidence'] > 85) {
                                    $check_att = $this->webservice_m->check_attendance(array('schoolID'=>$schoolID, 'studentID'=>$studentID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
                                    if(count($check_att)>0)
                                    {
                                        //update
                                        $this->webservice_m->update_attendance(array($a=>'P'),array('studentID'=>$studentID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
                                    } else {
                                        //insert
                                        $this->webservice_m->insert_attendance(array('schoolID'=>$schoolID,'academicID'=>1,'studentID'=>$studentID, 'subjectID'=>$subjectID, 'userID'=>$userID, 'usertype'=>$usertype, 'monthyear'=>$month_year,$a=>'P'));
                                    }
                                    $response[] = array('result'=>"success",'response'=>"attendance submitted successfully");
                                }else{
                                    $response[] = array('result'=>"failure",'response'=>"face doesnot match");
                                }
                            }else{
                                $response[] = array('result'=>"failure",'response'=>"face doesnot match");
                            }
                        } 
                    }
                }else{
                    $response[] = array('result'=>"failure",'response'=>"Student information not found for given studentID and schoolID");
                }                                    
            }

        }else{
            $response[] = array('result'=>"failure",'response'=>"please choose an image");
        }
        echo json_encode($response);
    }
    public function staff_attendance_staff()
    {
        $role = $this->input->post('role');
        $studentID = $this->input->post('userID');
        $schoolID = $this->input->post('schoolID');
        $userID = $studentID;
        $usertype = "self";
        $tdate = date("Y-m-d");
        $month_year = date('m-Y',strtotime($tdate));
        $d = date('d',strtotime($tdate));
        $a = "a".abs($d);
        if ($role == "other_staff"){
            $table = "other_staff";
            $user_column = "staffID";
            $att_table = "staff_attendance";
        }elseif($role == "teacher"){
            $table = $role;
            $user_column = $role."ID";
            $att_table = "teacher_attendance";
        }
        if(is_array($_FILES))
        {
            foreach($_FILES as $fileKey => $fileVal)
            {
                $student = $this->db->query("SELECT `image` FROM $table WHERE $user_column = '$studentID' AND `schoolID` = '$schoolID'")->row();
                if (count($student)) {
                    if (is_null($student->image) || $student->image == "" || $student->image == "default.png") {
                        $response[] = array('result'=>"failure",'response'=>"please set a profile picture first");
                    }else{
                        $target_url = "https://api-us.faceplusplus.com/facepp/v3/compare";
                        $tmpfile = $_FILES['image']['tmp_name'];
                        $filename2 = "image2.jpg";
                        $filename1 = "image1.jpg";
                        $filetype1 = "image/jpeg";
                        $file1 = '/var/www/html/mentor/customer_portal/uploads/images/'.$student->image;
                        $data = array(
                            'api_key' => 'ExRcCcc584DRN2mnvvC13Ghdv1x2Upai',
                            'api_secret' => '4YHwIRGIg4j5eboPprrOwUdCQ7lrBULQ',
                            'image_file1' => curl_file_create($file1, 'image/jpeg',$filename1),
                            'image_file2' => curl_file_create($tmpfile, $_FILES['image']['type'], $filename2)
                        );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $target_url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data'));
                        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                        /*curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );*/
                        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        $result = curl_exec ($ch);
                        if(!curl_exec($ch)){
                            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
                        }
                        if ($result) {
                            $json_decoded = json_decode($result,true);
                            if (array_key_exists("confidence",$json_decoded)) {
                                if ($json_decoded['confidence'] > 85) {
                                    $check_att = $this->db->get_where($att_table, array('schoolID'=>$schoolID, $user_column=>$studentID, 'monthyear'=>$month_year));
                                    if(count($check_att)>0)
                                    {
                                        //update
                                        $this->webservice_m->update_attendance(array($a=>'P'),array('studentID'=>$studentID,'monthyear'=>$month_year));
                                    } else {
                                        //insert
                                        $this->db->insert(array('schoolID'=>$schoolID,'academicID'=>1,$user_column=>$studentID,'userID'=>$userID, 'usertype'=>$usertype, 'monthyear'=>$month_year,$a=>'P'));
                                    }
                                    $response[] = array('result'=>"success",'response'=>"attendance submitted successfully");
                                }else{
                                    $response[] = array('result'=>"failure",'response'=>"face doesnot match");
                                }
                            }else{
                                $response[] = array('result'=>"failure",'response'=>"face doesnot match");
                            }
                        }
                    }
                }else{
                    $response[] = array('result'=>"failure",'response'=>"Staff information not found for given userID and schoolID");
                }
            }

        }else{
            $response[] = array('result'=>"failure",'response'=>"please choose an image");
        }
        echo json_encode($response);
    }
    public function add_circular()
    {
        //$data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $this->input->post('schoolID');
        $userID = $this->input->post('userID');
        $title = $this->input->post('title');
        $circular_no = $this->input->post('circular_no');
        $date = date("Y-m-d",strtotime($this->input->post('date')));
        $description = $this->input->post('description');
        $circular_for = strtolower($this->input->post('circular_for'));
        $added_on=date('Y-m-d H:i:s');
        $path = base_url('uploads/circular/');        
        $actual_image_name = '';
        if ($schoolID == $userID) {
            $target_path = "uploads/circular/"; // replace this with the path you are going to save the file to
            $target_dir = "uploads/circular/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if(is_array($_FILES)) 
            {
                foreach($_FILES as $fileKey => $fileVal)
                {
                    $imagename = basename($_FILES["image"]["name"]);
                    $extension = substr(strrchr($_FILES['image']['name'], '.'), 1);
                    if(!empty($extension))
                    {
                        $actual_image_name = $title.time().".".$extension;
                    }

                    move_uploaded_file($_FILES["image"]["tmp_name"],$target_path.$actual_image_name);
                                          
                }
            }
            $array = array(
                "schoolID" => $schoolID,
                "title" =>$title,
                "description" => $description,
                "for" => $circular_for,
                "image" => $actual_image_name,
                "image_type" => $extension,
                "circular_no" => $circular_no,
                "date" => $date,
                "is_active" => "Y",
                "added_on" => $added_on
            );
            $this->db->insert('circular',$array);
            $circularID = $this->db->insert_id();
            if ($circularID) {
                $response[] = array("result"=>"success", "message"=>"circular Successfully Added with id $circularID");
                $members_array = explode(",",$circular_for);
                if (in_array("student",$members_array)){
                    $this->db->select('studentID');
                    $members = $this->db->get_where('student',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                    foreach ($members as $m){
                        $this->db->select('device_token');
                        $device_info = $this->db->get_where('user_login',array('userID'=>$m->studentID,'role'=>'student'))->result();
                        foreach ($device_info as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"Circular","msg":"There is a new circular.","type":"circular","role":"student"}';
                            $data1 = array("m" => $data1);
                            $this->fcmNotification($device_token, $data1);
                        }
                        $data2 = array(
                            "schoolID"=>$schoolID,
                            "userID"=>$m->studentID,
                            "role"=>"student",
                            "title" => "Circular",
                            "notification"=>"There is a new circular.",
                            "status"=>1,
                            "added_on"=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('notification',$data2);
                    }
                }
                if (in_array("teacher",$members_array)){
                    $this->db->select('teacherID');
                    $members = $this->db->get_where('teacher',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                    foreach ($members as $m){
                        $this->db->select('device_token');
                        $device_info = $this->db->get_where('user_login',array('userID'=>$m->teacherID,'role'=>'teacher'))->result();
                        foreach ($device_info as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"Circular","msg":"There is a new circular.","type":"circular","role":"teacher"}';
                            $data1 = array("m" => $data1);
                            $this->fcmNotification($device_token, $data1);
                        }
                        $data2 = array(
                            "schoolID"=>$schoolID,
                            "userID"=>$m->teacherID,
                            "role"=>"teacher",
                            "title" => "Circular",
                            "notification"=>"There is a new circular.",
                            "status"=>1,
                            "added_on"=>date("Y-m-d H:i:s")
                        );
                        $this->db->insert('notification',$data2);
                    }
                }
            }else{
                $response[] = array("result"=>"failure", "message"=>"some error occured");
            }
            
        }else{
           $response[] = array("result"=>"failure", "message"=>"user is not permitted to upload circular"); 
        }
        echo json_encode($response);
    }
    public function list_circular()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $role = strtolower($data['role']);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $circular = array();
        if (array_key_exists('year',$data)){
            $year  = $data['year'];
        }else{
            $year = "";
        }
        if (array_key_exists('month',$data)){
            $month  = $data['month'];
        }else{
            $year = "";
        }
        $path = base_url('uploads/circular/');
        switch ($role) {
            case 'school':
                $this->db->select("`circularID`,`circular_no`,DATE_FORMAT(date,'%d-%m-%Y') AS date,`title`,`description`,`for` as circular_for,CONCAT('".$path."' , `image`) as image,`image_type` as extension,`is_active`,`added_on`");
                $this->db->order_by("circularID", "desc");
                if ($month != "" && $year != ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'YEAR(date)'=>$year,'MONTH(date)'=>$month,'is_active'=>'Y'))->result();
                }elseif ($month == "" && $year != ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'YEAR(date)'=>$year,'is_active'=>'Y'))->result();
                }elseif ($month != "" && $year == ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'MONTH(date)'=>$month,'is_active'=>'Y'))->result();
                }else{
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                }
                break;

            case 'student':
                $this->db->select("`circularID`,`circular_no`,DATE_FORMAT(date,'%d-%m-%Y') AS date,`title`,`description`,`for` as circular_for,CONCAT('".$path."' , `image`) as image,`image_type` as extension,`is_active`,`added_on`");
                $this->db->where("FIND_IN_SET('student', `for`)");
                $this->db->order_by("circularID", "desc");
                if ($month != "" && $year != ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'YEAR(date)'=>$year,'MONTH(date)'=>$month,'is_active'=>'Y'))->result();
                }elseif ($month == "" && $year != ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'YEAR(date)'=>$year,'is_active'=>'Y'))->result();
                }elseif ($month != "" && $year == ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'MONTH(date)'=>$month,'is_active'=>'Y'))->result();
                }else{
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                }
                break;

            case 'teacher':
                $this->db->select("`circularID`,`circular_no`,DATE_FORMAT(date,'%d-%m-%Y') AS date,`title`,`description`,`for` as circular_for,CONCAT('".$path."' , `image`) as image,`image_type` as extension,`is_active`,`added_on`");
                $this->db->where("FIND_IN_SET('teacher', `for`)");
                $this->db->order_by("circularID", "desc");
                if ($month != "" && $year != ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'YEAR(date)'=>$year,'MONTH(date)'=>$month,'is_active'=>'Y'))->result();
                }elseif ($month == "" && $year != ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'YEAR(date)'=>$year,'is_active'=>'Y'))->result();
                }elseif ($month != "" && $year == ""){
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'MONTH(date)'=>$month,'is_active'=>'Y'))->result();
                }else{
                    $circular = $this->db->get_where('circular',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                }
                break;
            
            default:
                $circular = array();
                break;
        }
        $response[] = array('result'=>'success','circular'=>$circular);
        echo json_encode($response);
        
    }
    public function add_suggestion_new()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $role = strtolower($data['role']);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $title = $data['title'];
        $description = $data['description'];
        if($role == 'teacher' || $role == 'student' || $role == 'parent'){
            $this->db->select("name");
            $user_info = $this->db->get_where($role,array($role.'ID'=>$userID))->row();
            if (count($user_info)){
                $name = $user_info->name;
            }else{
                $name = "";
            }
        }else{
            $name = "";
        }
        $this->db->insert('suggestion_new',array('schoolID'=>$schoolID,'name'=>$name,'title'=>$title,'description'=>$description,'usertype'=>$role,'userID'=>$userID,'added_on'=>date("Y-m-d H:i:s")));
        $suggestionID = $this->db->insert_id();
        if ($suggestionID) {
            $device_info = $this->db->get_where('user_login',array('userID'=>$schoolID,'role'=>'school'))->result();
            foreach ($device_info as $d){
                $device_token = $d->device_token;
                $data1 = '{"notification_type":"text","title":"Suggestion","msg":"A new Suggestion has been posted by '.$role.' ('.$name.').","type":"suggestion","role":"school"}';
                $data1 = array("m" => $data1);
                $this->fcmNotification($device_token, $data1);
            }
            $data2 = array(
                "schoolID"=>$schoolID,
                "userID"=>$schoolID,
                "role"=>"school",
                "title" => "Suggestion",
                "notification"=>"A new Suggestion has been posted by ".$role." (".$name.")",
                "status"=>1,
                "added_on"=>date("Y-m-d H:i:s")
            );
            $this->db->insert('notification',$data2);
            $response[] = array('result'=>'success','message'=>'Your suggestion is successfully sent to admin with id '.$suggestionID);
        }else{
            $response[] = array('result'=>'failure','message'=>'Please Try Again Later');
        }
        echo json_encode($response);
    }
    public function list_suggestion_new()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $role = strtolower($data['role']);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $page = 1;
        $records = 10;
        if (isset($data['page'])) {
            $page = $data['page'];
            if ($page <= 0) {
                $page = 1;
            }
        }
        if (isset($data['records'])) {
            $records = $data['records'];
        }
        $offset = ($page - 1) * $records;
        if($role == 'school' && $userID == $schoolID)
        {
            $this->db->select("COUNT(suggestionID) as total_suggestion");
            $count = $this->db->get_where('suggestion_new',array('schoolID'=>$schoolID))->row();
            $left_rec = $count->total_suggestion - ($page * $records);
            $this->db->select("suggestionID,schoolID,title,description,usertype,COALESCE(NULLIF(name, ''),'') as name,userID,added_on");
            $this->db->order_by("suggestionID", "desc");
            $this->db->limit($records,$offset);
            $suggestion = $this->db->get_where('suggestion_new',array('schoolID'=>$schoolID))->result();
            $response[] = array('result'=>'success','suggestion'=>$suggestion,'total'=>$count->total_suggestion,'left'=>$left_rec);
        }else{
            $response[] = array('result'=>'failure','message'=>'user is not permitted to view suggestion');
        }
        echo json_encode($response);
    }
    public function delete_suggestion(){
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $suggestionID = $data['suggestionID'];
        if ($userID == $schoolID){
            $this->db->where(array('schoolID'=>$schoolID,'suggestionID'=>$suggestionID));
            $this->db->delete('suggestion_new');
            $response[] = array('result'=>'success','message'=>'Successfully Deleted');
        }else{
            $response[] = array('result'=>'failure','message'=>'Unauthorized User');
        }
        echo json_encode($response);
    }
    public function delete_all_suggestion(){
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        if ($userID == $schoolID){
            $this->db->where(array('schoolID'=>$schoolID));
            $this->db->delete('suggestion_new');
            $response[] = array('result'=>'success','message'=>'Successfully Deleted');
        }else{
            $response[] = array('result'=>'failure','message'=>'Unauthorized User');
        }
        echo json_encode($response);
    }
    public function get_state()
    {
        $this->db->select('stateID,name');
        $state = $this->db->get('state')->result();
        $response[] = array('result'=>'success','state'=>$state);
        echo json_encode($response);
    }
    public function get_district()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $stateID = $data['stateID'];
        $this->db->select('districtID,stateID,name');
        $district = $this->db->get_where('district',array('stateID'=>$stateID))->result();
        $response[] = array('result'=>'success','district'=>$district);
        echo json_encode($response);
    }
    public function create_group(){
        $data = json_decode(file_get_contents('php://input'), true);
        $senderID = $data['senderID'];
        $sender_role = $data['sender_role'];
        $receiverID = $data['receiverID'];
        $receiver_role = $data['receiver_role'];
        $check = $this->db->query("SELECT * FROM groups WHERE (senderID = '$senderID' AND sender_role = '$sender_role' AND receiverID = '$receiverID' AND receiver_role = '$receiver_role') OR (senderID = '$receiverID' AND sender_role = '$receiver_role' AND receiverID = '$senderID' AND receiver_role = '$sender_role')")->row();
        if (count($check)){
            $response[] = array('result'=>'success','groupID'=>$check->groupID);
        }else{
            $array1 = array(
                'senderID'=>$senderID,
                'sender_role'=>$sender_role,
                'receiverID'=>$receiverID,
                'receiver_role'=>$receiver_role,
                'last_message'=>'',
                'added_on'=>date("Y-m-d H:i:s"),
                'modified_on'=>date("Y-m-d H:i:s")
            );
            $this->db->insert('groups',$array1);
            $insert_id = $this->db->insert_id();
            $response[] = array('result'=>'success','groupID'=>$insert_id);
        }
        echo json_encode($response);
    }
    public function chat_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $role = strtolower($data['role']);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $path = base_url("uploads/images/");
        $group = $data['group']; // teacher,student,school
        if ($role == "school") {
            if ($group == 'student') {
                $sectionID = $data['sectionID'];
                $members = $this->db->query("SELECT DISTINCT studentID as userID, CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image, student.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM student LEFT JOIN groups ON (groups.senderID = student.studentID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = student.studentID AND groups.receiver_role = '$group') WHERE student.sectionID = '$sectionID' AND student.schoolID = '$schoolID' AND student.is_active = 'Y'")->result();
                $response[] = array('result'=>'success','members'=>$members,'role'=>'student');
            }elseif ($group == 'teacher'){
                $members = $this->db->query("SELECT DISTINCT teacher.teacherID as userID, CONCAT('".$path."',COALESCE(NULLIF(teacher.image, ''),'default.png')) as image, teacher.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM teacher LEFT JOIN groups ON (groups.senderID = teacher.teacherID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = teacher.teacherID AND groups.receiver_role = '$group') WHERE teacher.schoolID = '$schoolID' AND teacher.is_active = 'Y'")->result();
                $response[] = array('result'=>'success','members'=>$members,'role'=>'teacher');
            }else{
                $response[] = array('result'=>'failure','message'=>'Unrecognized Group');
            }

        }elseif ($role == "student"){
            $this->db->select('sectionID');
            $sectionID = $this->db->get_where('student',array('studentID'=>$userID))->row();
            if ($group == 'student') {
                if (count($sectionID)){
                    $members = $this->db->query("SELECT DISTINCT student.studentID as userID, CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image, student.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM student LEFT JOIN groups ON (groups.senderID = student.studentID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = student.studentID AND groups.receiver_role = '$group') WHERE student.studentID != '$userID' AND student.sectionID = '$sectionID->sectionID' AND student.is_active = 'Y'")->result();
                    $response[] = array('result'=>'success','members'=>$members,'role'=>'student');
                }else{
                    $response[] = array('result'=>'failure','message'=>'Unauthorize user');
                }

            }elseif ($group == 'teacher'){
                if (count($sectionID)){
                    $members = $this->db->query("SELECT DISTINCT teacher.teacherID as userID, CONCAT('".$path."',COALESCE(NULLIF(teacher.image, ''),'default.png')) as image, teacher.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM routine LEFT JOIN teacher ON routine.teacherID = teacher.teacherID LEFT JOIN groups ON (groups.senderID = teacher.teacherID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = teacher.teacherID AND groups.receiver_role = '$group') WHERE routine.sectionID = '$sectionID->sectionID' AND teacher.is_active = 'Y'")->result();
                    $response[] = array('result'=>'success','members'=>$members,'role'=>'teacher');
                }else{
                    $response[] = array('result'=>'failure','message'=>'Unauthorize user');
                }
            }elseif ($group == 'school'){
                if (count($sectionID)){
                    $members = $this->db->query("SELECT DISTINCT school_registration.schoolID as userID, CONCAT('".$path."',COALESCE(NULLIF(school_registration.image, ''),'default.png')) as image, school_registration.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM school_registration LEFT JOIN groups ON (groups.senderID = school_registration.schoolID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = school_registration.schoolID AND groups.receiver_role = '$group') WHERE school_registration.schoolID = '$schoolID'")->result();
                    $response[] = array('result'=>'success','members'=>$members,'role'=>'school');
                }
            }else{
                $response[] = array('result'=>'failure','message'=>'Unrecognized Group');
            }
        }elseif ($role == "teacher"){
            if ($group == 'student') {
                $sectionID = $data['sectionID'];
                $members = $this->db->query("SELECT DISTINCT studentID as userID, CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image, student.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM student LEFT JOIN groups ON (groups.senderID = student.studentID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = student.studentID AND groups.receiver_role = '$group') WHERE student.sectionID = '$sectionID' AND student.schoolID = '$schoolID' AND student.is_active = 'Y'")->result();
                $response[] = array('result'=>'success','members'=>$members,'role'=>'student');
            }elseif ($group == 'teacher'){
                $members = $this->db->query("SELECT DISTINCT teacher.teacherID as userID, CONCAT('".$path."',COALESCE(NULLIF(teacher.image, ''),'default.png')) as image, teacher.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM teacher LEFT JOIN groups ON (groups.senderID = teacher.teacherID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = teacher.teacherID AND groups.receiver_role = '$group') WHERE teacher.teacherID != '$userID' AND teacher.schoolID = '$schoolID' AND teacher.is_active = 'Y'")->result();
                $response[] = array('result'=>'success','members'=>$members,'role'=>'teacher');
            }elseif ($group == 'school'){
                $members = $this->db->query("SELECT DISTINCT school_registration.schoolID as userID, CONCAT('".$path."',COALESCE(NULLIF(school_registration.image, ''),'default.png')) as image, school_registration.name, COALESCE(NULLIF(groups.groupID, ''),'') as groupID, COALESCE(NULLIF(groups.last_message, ''),'') as last_message, COALESCE(NULLIF(groups.modified_on, ''),'') as time FROM school_registration LEFT JOIN groups ON (groups.senderID = school_registration.schoolID AND groups.sender_role = '$group' AND groups.receiverID = '$userID' AND groups.receiver_role = '$role') OR (groups.senderID = '$userID' AND groups.sender_role = '$role' AND groups.receiverID = school_registration.schoolID AND groups.receiver_role = '$group') WHERE school_registration.schoolID = '$schoolID'")->result();
                $response[] = array('result'=>'success','members'=>$members,'role'=>'school');
            }else{
                $response[] = array('result'=>'failure','message'=>'Unrecognized Group');
            }
        }else{
            $response[] = array('result'=>'failure','message'=>'Unauthorize user');
        }
        echo json_encode($response);
    }
    public function chat_history()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $groupID = $data['groupID'];
        $message = $data['message'];
        $this->db->where(array('groupID'=>$groupID));
        $this->db->update('groups',array('last_message'=>$message,'modified_on'=>date("Y-m-d H:i:s")));
        if (array_key_exists('senderID',$data)){

            if (array_key_exists('receiverID',$data)){

                if (array_key_exists('sender_role',$data)){

                    if (array_key_exists('receiver_role',$data)){

                        $senderID = $data['senderID'];
                        $sender_role = $data['sender_role'];
                        $receiverID = $data['receiverID'];
                        $receiver_role = $data['receiver_role'];
                        if ($sender_role == 'school'){
                            $table = "school_registration";
                        }else{
                            $table = $sender_role;
                        }
                        $sender = $this->db->get_where($table,array($sender_role.'ID'=>$senderID))->row();
                        $device = $this->db->get_where('user_login',array('userID'=>$receiverID,'role'=>$receiver_role))->result();
                        foreach ($device as $d){
                            $device_token = $d->device_token;
                            $data1 = '{"notification_type":"text","title":"Message Arrived","msg":"'.$sender->name.' has sent you a message","type":"chat","role":"'.$receiver_role.'","userID":"'.$receiverID.'","senderID":"'.$senderID.'","sender_role":"'.$sender_role.'"}';
                            $data1 = array("m" => $data1);
                            $this->fcmNotification($device_token, $data1);
                        }
                    }
                }
            }
        }

        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function teacher_class()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $teacherID = $data['userID'];
        $schoolID = $data['schoolID'];
        $classes = $this->db->query("SELECT DISTINCT classes.classID, classes.class as class_name FROM routine LEFT JOIN classes ON routine.classID = classes.classID WHERE routine.teacherID = '$teacherID' AND routine.schoolID = '$schoolID'")->result();
        $response[] = array('result'=>'success','classes'=>$classes);
        echo json_encode($response);
    }
    public function teacher_section()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $teacherID = $data['userID'];
        $classID = $data['classID'];
        $schoolID = $data['schoolID'];
        $section = $this->db->query("SELECT DISTINCT section.sectionID, section.section as section_name FROM routine LEFT JOIN section ON routine.sectionID = section.sectionID WHERE routine.teacherID = '$teacherID' AND routine.classID = '$classID' AND routine.schoolID = '$schoolID'")->result();
        $response[] = array('result'=>'success','section'=>$section);
        echo json_encode($response);
    }
    public function change_password()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $old_password = $this->hash($data['old_password']);
        $new_password = $this->hash($data['new_password']);
        if (strtolower($role)=='school'){
            $table = 'school_registration';
            $column = 'schoolID';
        }else{
            $table = $role;
            $column = $table.'ID';
        }
        $check = $this->db->get_where($table,array($column=>$userID,'password'=>$old_password))->row();
        if (count($check)){
            $this->db->where(array($column=>$userID));
            $this->db->update($table,array('password'=>$new_password));
            $response[] = array('result'=>'success','message'=>'Password updated successfully');
        }else{
            $response[] = array('result'=>'failure','message'=>'Old password incorrect');
        }
        echo json_encode($response);
    }
    public function user_profile()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $profile = array();
        if (strtolower($role)=='school'){
            $profile[] = $this->db->query("SELECT school_registration.`name`,school_registration.email,school_registration.phone,school_registration.school_code,school_registration.username,school_config.school_name,COALESCE('Starter Plan') as package FROM school_registration JOIN school_config ON school_registration.schoolID = school_config.schoolID WHERE school_registration.schoolID = '$userID'")->row();
        }elseif (strtolower($role)=='teacher'){
            $profile[] = $this->db->query("SELECT teacher.`name`,teacher.username,teacher.email,teacher.phone,teacher.school_code,school_config.school_name from teacher JOIN school_config ON teacher.schoolID = school_config.schoolID WHERE teacher.teacherID = '$userID'")->row();
        }elseif (strtolower($role)=='student'){
            $profile[] = $this->db->query("SELECT student.name, student.username, student.dob, student.email, student.phone, student.school_code, classes.class, section.section, school_config.school_name, COALESCE(NULLIF(parent.`name`,''),'') as parent FROM student LEFT JOIN classes ON student.classID = classes.classID LEFT JOIN section ON student.sectionID = section.sectionID LEFT JOIN school_config ON student.schoolID = school_config.schoolID LEFT JOIN parent ON student.parentID = parent.parentID WHERE student.studentID = '$userID'")->row();
        }
        $response[] = array('result'=>'success','profile'=>$profile);
        echo json_encode($response);
    }
    public function get_notification_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $schoolID = strtolower($data['schoolID']);
        $this->db->order_by("added_on","desc");
        $this->db->select("notificationID,title,notification as description,status, added_on as date");
        $notification = $this->db->get_where('notification',array('userID'=>$userID,'role'=>$role,'schoolID'=>$schoolID))->result();
        $response[] = array('result'=>'success','notification'=>$notification);
        echo json_encode($response);
    }
    public function read_notification()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $notificationID = $data['notificationID'];
        $this->db->where(array('notificationID'=>$notificationID,'userID'=>$userID,'role'=>$role));
        $this->db->update('notification',array('status'=>0));
        $response[] = array('result'=>'success','message'=>'Read');
        echo json_encode($response);
    }
    public function delete_notification()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $notificationID = $data['notificationID'];
        $this->db->where(array('notificationID'=>$notificationID,'userID'=>$userID,'role'=>$role));
        $this->db->delete('notification');
        $response[] = array('result'=>'success','message'=>'Successfully Deleted');
        echo json_encode($response);
    }
    public function delete_all_notification()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $this->db->where(array('userID'=>$userID,'role'=>$role));
        $this->db->delete('notification');
        $response[] = array('result'=>'success','message'=>'Successfully Deleted');
        echo json_encode($response);
    }
    public function total_notification(){
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = $data['role'];
        $schoolID = strtolower($data['schoolID']);
        $this->db->select("count(notificationID) as unread");
        $notification = $this->db->get_where('notification',array('userID'=>$userID,'role'=>$role,'schoolID'=>$schoolID,'status'=>1))->row();
        $response[] = array('result'=>'success','unread'=>$notification->unread);
        echo json_encode($response);
    }
    public function fcmNotification($device_id, $sendData)
    {
        if (!defined('API_ACCESS_KEY')){
            define('API_ACCESS_KEY', 'AAAA2_Z4OH0:APA91bGgWMIQCk-wkpUp_GtJbmDjGrO3_DpL-SiL3fEhv3pH5mXHqf1nEHkLgRXT50IT-iS6PMp372dHF4VK3Jx8KOuAu-dj0m8tihlXEXVXDo6yljYLAS-d3YOXo4HWscaTcOX7MjO6');
        }

        $fields = array
        (
            'to'        => $device_id,
            'data'  => $sendData,
            'notification'  => $sendData
        );


        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch);
        //$data = json_decode($result);
       if($result === false)
           die('Curl failed ' . curl_error($ch));

        curl_close($ch);
        return $result;
    }
    public function student_list_detail()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $path = base_url('uploads/images/');
        $this->db->select("student.studentID,student.classID,student.sectionID,student.username as student_code,student.name,classes.class as class_name,section.section,student.roll_no,COALESCE(NULLIF(parent.name, ''),'') as parent,COALESCE(NULLIF(student_group.latitude, ''),'') as latitude,COALESCE(NULLIF(student_group.longitude, ''),'') as longitude,COALESCE(NULLIF(student.phone, ''),'') as phone,student.school_code,school_config.school_name,student.dob,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image");
        $this->db->join('classes','student.classID = classes.classID','LEFT');
        $this->db->join('school_config','student.schoolID = school_config.schoolID','LEFT');
        $this->db->join('section','student.sectionID = section.sectionID','LEFT');
        $this->db->join('parent','student.parentID = parent.parentID','LEFT');
        $this->db->join('student_group','student.groupID = student_group.groupID','LEFT');
        $student_list = $this->db->get_where('student',array('student.schoolID'=>$schoolID,'student.classID'=>$classID,'student.sectionID'=>$sectionID,'student.is_active'=>'Y'))->result();
        $response[] = array('result'=>'success','list'=>$student_list);
        echo json_encode($response);
    }
    public function search_student_name()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $name = $data['name'];
        $path = base_url('uploads/images/');
        /*$this->db->select("student.studentID,student.classID,student.sectionID,student.username as student_code,student.name,classes.class as class_name,section.section,student.roll_no,COALESCE(NULLIF(parent.name, ''),'') as parent,COALESCE(NULLIF(student.phone, ''),'') as phone,student.school_code,school_config.school_name,student.dob,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image");
        $this->db->join('classes','student.classID = classes.classID','LEFT');
        $this->db->join('school_config','student.schoolID = school_config.schoolID','LEFT');
        $this->db->join('section','student.sectionID = section.sectionID','LEFT');
        $this->db->join('parent','student.parentID = parent.parentID','LEFT');
        $this->db->or_like(array('student.name'=>$name,'student.username'=>$name));
        $this->db->where(array('student.schoolID'=>$schoolID,'student.is_active'=>'Y'));*/
        $student_list = $this->db->query("SELECT student.studentID,student.classID,student.sectionID,student.username as student_code,student.name,classes.class as class_name,section.section,student.roll_no,COALESCE(NULLIF(parent.name, ''),'') as parent,COALESCE(NULLIF(student.phone, ''),'') as phone,student.school_code,school_config.school_name,student.dob,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image from student LEFT JOIN school_config ON student.schoolID = school_config.schoolID LEFT JOIN classes ON student.classID = classes.classID LEFT JOIN section ON student.sectionID = section.sectionID LEFT JOIN parent ON student.parentID = parent.parentID WHERE student.schoolID = '$schoolID' AND student.is_active = 'Y' AND (student.`name` LIKE '%$name%' OR student.`username` LIKE '%$name%')")->result();
        $response[] = array('result'=>'success','list'=>$student_list);
        echo json_encode($response);
    }
    public function school_session_invoice()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $session = $this->db->query("SELECT DISTINCT(session) FROM invoice where schoolID ='$schoolID'")->result();
        $response[] = array('result'=>'success','sessions'=>$session);
        echo json_encode($response);
    }
    public function invoice_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $studentID = $data['studentID'];
        $session = $data['session'];
        $total_fine = 0;
        $total_due = 0;
        $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
        if ($session != "")
        {
            $invoices = $this->db->query("SELECT invoice_fee_head.invoiceID, invoice_fee_head.schoolID, invoice_fee_head.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.`session` = '$session' AND invoice.studentID = '$studentID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID DESC")->result();
        }else{
            $invoices = $this->db->query("SELECT invoice_fee_head.invoiceID, invoice_fee_head.schoolID, invoice_fee_head.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.studentID = '$studentID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID DESC")->result();
        }
        $invoice_array = array();
        foreach ($invoices as $invoice){
            $date1 = date_create($invoice->due_date);
            $date2 = date_create(date("Y-m-d"));
            $diff = date_diff($date1,$date2);
            if ($invoice->due > 0 && $diff->invert == 0 && $diff->days > 0){
                if ($school_info->fees_fine_config == 'day'){
                    $fine = $school_info->fees_fine_amt * $diff->days;
                }elseif ($school_info->fees_fine_config == 'month'){
                    $fine = $school_info->fees_fine_amt + ($school_info->fees_fine_amt * $diff->m) + ($school_info->fees_fine_amt * 12 * $diff->y);
                }else{
                    $fine = 0;
                }
            }else{
                $fine = 0;
            }
            $total_fine += $fine;
            $total_due += $invoice->due;
            $invoice_array[] = array(
                'invoiceID'=>$invoice->invoiceID,
                'schoolID'=>$invoice->schoolID,
                'studentID'=>$invoice->studentID,
                'session'=>$invoice->session,
                'monthyear'=>$invoice->monthyear,
                'due_date'=>$invoice->due_date,
                'total'=>$invoice->total,
                'concession'=>$invoice->concession,
                'payable'=>$invoice->payable,
                'paid'=>$invoice->paid,
                'due'=>$invoice->due,
                'fine'=>$fine
            );
        }
        $total_due = $total_due;
        $total_fine = $total_fine;
        $response[] = array('result'=>'success','total_fine'=>"$total_fine",'total_due'=>"$total_due",'invoices'=>$invoice_array);
        echo json_encode($response);
    }
    public function invoice_details()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $invoiceID = $data['invoiceID'];
        $invoice_info = $this->db->query("SELECT feehead, total_amount AS total, COALESCE(NULLIF(concession_type, ''),'') as concession_type , concession, payable_amount AS payable, paid_amount AS paid, due_amount AS due, due_date FROM invoice_fee_head WHERE invoiceID = '$invoiceID' AND schoolID = '$schoolID'")->result();
        $invoice_details = array();
        foreach ($invoice_info as $fee){
            $invoice_details[] = array(
                'feehead'=>$fee->feehead,
                'total'=>number_format($fee->total,2),
                'concession_type'=>$fee->concession_type,
                'concession'=>number_format($fee->concession,2),
                'payable'=>number_format($fee->payable,2),
                'paid'=>number_format($fee->paid,2),
                'due'=>number_format($fee->due,2),
                'due_date'=>$fee->due_date
            );
        }
        $response[] = array('result'=>'success','invoice_details'=>$invoice_details);
        echo json_encode($response);
    }
    public function group_notification_section_wise()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $classID = $data['classID'];
        $sectionID = $data['sectionID'];
        $title = $data['title'];
        $message = $data['message'];
        $this->db->select('studentID');
        $students = $this->db->get_where('student',array('schoolID'=>$schoolID,'classID'=>$classID,'sectionID'=>$sectionID))->result();
        $student_array = array();
        foreach ($students as $s){
            $data2 = array(
                "schoolID"=>$schoolID,
                "userID"=>$s->studentID,
                "role"=>"student",
                "title" => $title,
                "notification"=>$message,
                "status"=>1,
                "added_on"=>date("Y-m-d H:i:s")
            );
            $this->db->insert('notification',$data2);
            array_push($student_array,$s->studentID);
        }
        $students = implode(",",$student_array);
        $devices = $this->db->query("SELECT `device_type`,`device_token` FROM `user_login` WHERE FIND_IN_SET(userID,'$students') AND `role` = 'student'")->result();
        foreach ($devices as $d){
            if ($d->device_type == "android"){
                $data1 = '{"notification_type":"text","title":"'.$title.'","msg":"'.$message.'","type":"group_notification","role":"student"}';
                $data1 = array("m" => $data1);
                $this->fcmNotification($d->device_token, $data1);
            }
        }
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function group_notification_teacher()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $title = $data['title'];
        $message = $data['message'];
        $this->db->select('teacherID');
        $teachers = $this->db->get_where('teacher',array('schoolID'=>$schoolID))->result();
        $teacher_array = array();
        foreach ($teachers as $t){
            $data2 = array(
                "schoolID"=>$schoolID,
                "userID"=>$t->teacherID,
                "role"=>"teacher",
                "title" => $title,
                "notification"=>$message,
                "status"=>1,
                "added_on"=>date("Y-m-d H:i:s")
            );
            $this->db->insert('notification',$data2);
            array_push($teacher_array,$t->teacherID);
        }
        $teachers = implode(",",$teacher_array);
        $devices = $this->db->query("SELECT `device_type`,`device_token` FROM `user_login` WHERE FIND_IN_SET(userID,'$teachers') AND `role` = 'teacher'")->result();
        foreach ($devices as $d){
            if ($d->device_type == "android"){
                $data1 = '{"notification_type":"text","title":"'.$title.'","msg":"'.$message.'","type":"group_notification","role":"teacher"}';
                $data1 = array("m" => $data1);
                $this->fcmNotification($d->device_token, $data1);
            }
        }
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function transaction()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $role = strtolower($data['role']);
        $type = $data['txn_type']; // invoice or subscription
        $txn_no = $data['txn_no'];
        $amount = $data['amount'];
        $txn_status = $data['txn_status'];
        $secret_key = $data['secret_key']; //md5 of userID
        $package = $data['package1'];
        $get_school = $this->webservice_m->get_single_table('school_registration',array('schoolID'=>$schoolID));
        $school_code = $get_school->school_code;
        if(md5($userID) == $secret_key){
            $array = array(
                'schoolID'=>$schoolID,
                'userID'=>$userID,
                'role'=>$role,
                'type'=>$type,
                'txn_no'=>$txn_no,
                'amount'=>$amount,
                'txn_status'=>$txn_status,
                'added_on'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('transaction',$array);
            $insertID = $this->db->insert_id();
            if ($txn_status == 'success'){
                if ($type == 'subscription'){
                    if ($role == 'student'){
                        $array1 = array(
                            'schoolID'=>$schoolID,
                            'school_code'=>$school_code,
                            'studentID'=>$userID,
                            'amount'=>$amount,
                            'is_paid'=>'Y',
                            'start_date'=>date('Y-m-d'),
                            'end_date'=>date('Y-m-d',strtotime('+1 years')),
                            'added_on'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('student_subscription_history',$array1);
                        $this->db->where(array('studentID'=>$userID));
                        $this->db->update('student',array('subscription_status'=>'Y'));
                    }elseif ($role == 'school'){
                        if (is_null($school_code)){
                            $school_code = "123";
                        }
                        if (is_null($amount)){
                            $amount = 0;
                        }
                        $package_info = $this->db->get_where('package',array('packageID'=>$package))->row();
                        $array1 = array(
                            'schoolID'=>$schoolID,
                            'school_code'=>$school_code,
                            'amount'=>$amount,
                            'modules'=>$package_info->modules,
                            'is_paid'=>'Y',
                            'start_date'=>date('Y-m-d'),
                            'end_date'=>date('Y-m-d',strtotime('+1 years')),
                            'grace_day'=>0,
                            'is_active'=>'Y',
                            'added_on'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('school_subscription_history',$array1);
                        $this->db->where(array('schoolID'=>$userID));
                        $this->db->update('school',array('subscription_status'=>'Y'));
                        $check = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                        if (count($check)){
                            $this->db->where(array('schoolID'=>$schoolID));
                            $this->db->update('school_config',array('per_student_subscription_amount'=>$package_info->price));
                        }else{
                            $this->db->insert('school_config',array('per_student_subscription_amount'=>$package_info->price,'schoolID'=>$schoolID));
                        }
                    }
                }elseif ($type == 'invoice' && $role == 'student'){
                    $remaining_amount = $amount;
                    $schoolID = $schoolID;
                    $studentID = $userID;
                    $total_fine = 0;
                    $total_due = 0;
                    $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                    $invoices = $this->db->query("SELECT invoice_fee_head.invoiceID, invoice_fee_head.schoolID, invoice_fee_head.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.studentID = '$studentID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID ASC")->result();
                    $paid_invoices = array();
                    foreach ($invoices as $invoice){
                        $date1 = date_create($invoice->due_date);
                        $date2 = date_create(date("Y-m-d"));
                        $diff = date_diff($date1,$date2);
                        if ($invoice->due > 0 && $diff->invert == 0 && $diff->days > 0){
                            if ($school_info->fees_fine_config == 'day'){
                                $fine = $school_info->fees_fine_amt * $diff->days;
                            }elseif ($school_info->fees_fine_config == 'month'){
                                $fine = $school_info->fees_fine_amt + ($school_info->fees_fine_amt * $diff->m) + ($school_info->fees_fine_amt * 12 * $diff->y);
                            }else{
                                $fine = 0;
                            }
                        }else{
                            $fine = 0;
                        }
                        $total_fine += $fine;
                        $total_due += $invoice->due;
                        $due_amount = $invoice->due;
                        if ($remaining_amount > 0 && $due_amount > 0){
                            array_push($paid_invoices,$invoice->invoiceID);
                            $invoice_feehead = $this->db->get_where('invoice_fee_head',array('invoiceID'=>$invoice->invoiceID))->result();
                            foreach($invoice_feehead as $fee){
                                if ($remaining_amount >= $fee->due_amount){
                                    $due_amount = $due_amount - $fee->due_amount;
                                    $remaining_amount = $remaining_amount - $fee->due_amount;
                                    $fee_due = 0;
                                    $paid = $fee->payable_amount;
                                    $this->db->where(array('ID'=>$fee->ID));
                                    $this->db->update('invoice_fee_head',array('paid_amount'=>$paid,'due_amount'=>$fee_due));
                                }elseif ($remaining_amount < $fee->due_amount){
                                    $due_amount = $due_amount - $remaining_amount;
                                    $paid = $fee->paid_amount;
                                    $paid += $remaining_amount;
                                    $fee_due = $fee->due_amount - $remaining_amount;
                                    $remaining_amount = 0;
                                    $this->db->where(array('ID'=>$fee->ID));
                                    $this->db->update('invoice_fee_head',array('paid_amount'=>$paid,'due_amount'=>$fee_due));
                                }
                            }
                            if ($due_amount == 0 && $fine > 0){
                                if ($fine <= $remaining_amount){
                                    $paid_fine = $fine;
                                    $due_fine = 0;
                                    $invoiceID = $invoice->invoiceID;
                                    $remaining_amount = $remaining_amount - $fine;
                                    $array2 = array(
                                        'schoolID'=>$schoolID,
                                        'userID'=>$studentID,
                                        'role'=>'student',
                                        'type'=>'invoice',
                                        'invoiceID'=>$invoiceID,
                                        'amount'=>$fine,
                                        'paid'=>$paid_fine,
                                        'due'=>$due_fine,
                                        'added_on'=>date('Y-m-d H:i:s')
                                    );
                                    $this->db->insert('fine',$array2);
                                }else{
                                    $paid_fine = $remaining_amount;
                                    $due_fine = $fine - $paid_fine;
                                    $invoiceID = $invoice->invoiceID;
                                    $remaining_amount = 0;
                                    $array2 = array(
                                        'schoolID'=>$schoolID,
                                        'userID'=>$studentID,
                                        'role'=>'student',
                                        'type'=>'invoice',
                                        'invoiceID'=>$invoiceID,
                                        'amount'=>$fine,
                                        'paid'=>$paid_fine,
                                        'due'=>$due_fine,
                                        'added_on'=>date('Y-m-d H:i:s')
                                    );
                                    $this->db->insert('fine',$array2);
                                }
                            }
                        }
                    }
                    if ($remaining_amount > 0){
                        $check = $this->db->get_where('advance_amount',array('schoolID'=>$schoolID,'studentID'=>$studentID))->row();
                        if (count($check)){
                            $remaining_amount += $check->amount;
                            $this->db->where(array('advanceID'=>$check->advanceID));
                            $this->db->update('advance_amount',array('amount'=>$remaining_amount,'modified_on'=>date("Y-m-d H:i:s")));
                        }else{
                            $this->db->insert('advance_amount',array('schoolID'=>$schoolID,'studentID'=>$studentID,'amount'=>$remaining_amount,'added_on'=>date("Y-m-d H:i:s")));
                        }
                    }
                    $paid_invoices_comma = implode(',',$paid_invoices);
                    $this->db->where(array('txnID'=>$insertID));
                    $this->db->update('transaction',array('invoices'=>$paid_invoices_comma));
                    $total_due = $total_due;
                    $total_fine = $total_fine;
                }
            }
            $response[] = array('result'=>'success');
        }else{
            $response[] = array('result'=>'failure','message'=>'secret key mismatch');
        }
        echo json_encode($response);
    }

    public function packages()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $path = base_url("uploads/package/");
        $this->db->select("packageID,package,CONCAT('".$path."',COALESCE(NULLIF(package.image, ''),'school-min.png')) as image,package.price");
        $packages = $this->db->get('package')->result();
        $response[] = array('result'=>'success','packages'=>$packages);
        echo json_encode($response);
    }
    public function module_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $packageID = $data['packageID'];
        $path = base_url("uploads/modules/");
        $package = $this->db->get_where('package',array('packageID'=>$packageID))->row();
        if (count($package)){
            $modules = $this->db->query("SELECT moduleID,module_name,CONCAT('".$path."',COALESCE(NULLIF(module_icon, ''),'tuition-and-fees.png')) as image FROM modules WHERE FIND_IN_SET(moduleID,'$package->modules') ORDER BY module_name")->result();
            $response[] = array('result'=>'success','modules'=>$modules,'price'=>$package->price);
        }else{
            $response[] = array('result'=>'failure','message'=>'invalid package');
        }
        echo json_encode($response);
    }
     public function all_module_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $path = base_url("uploads/modules/");
        $modules = $this->db->query("SELECT moduleID,module_name,CONCAT('".$path."',COALESCE(NULLIF(module_icon, ''),'tuition-and-fees.png')) as image FROM modules WHERE is_active = 'Y' ORDER BY module_name")->result();
        $response[] = array('result'=>'success','modules'=>$modules);
        
        echo json_encode($response);
    }
    // Transport
    // Driver APP
    public function driver_login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $mobile = $data['mobile'];
        $password = $data['password'];
        $check =
            $this->webservice_m->get_single_table('driver',array('phone'=>$mobile,'password'=>$this->hash($password)));
        if(count($check)>0)
        {
            $response[] =
                array('result'=>'success','driverID'=>$check->driverID,
                    'driver_name'=>$check->driver_name, 'routeID'=>$check->routeID);
        } else {
            $response[] = array('result'=>'failure');
        }
        echo json_encode($response);
    }
    public function driver_home()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $driverID = $data['driverID'];
        $check =$this->db->get_where('driver',array('driverID'=>$driverID))->row();
        if(count($check)>0)
        {
            $route =
                $this->db->query("SELECT routeID,route,description,school_latlong,lastpoint_latlong FROM route WHERE FIND_IN_SET(routeID,'$check->routeID')")->result();
            $response[] =
                array('result'=>'success',
                    'driverID'=>$check->driverID,
                    'name'=>$check->driver_name,
                    'routes'=>$route
                );
        } else {
            $response[] = array('result'=>'failure');
        }
        echo json_encode($response);
    }
    public function start_ride()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $routeID = $data['routeID'];
        $driverID = $data['driverID'];
        $ride_type = $data['ride_type'];
        $date = date('Y-m-d');
        $this->db->select("track_record.recordID,track_record.end_on,route.school_latlong,route.lastpoint_latlong,route.description");
        $this->db->join('route','track_record.routeID = route.routeID','LEFT');
        $check = $this->db->get_where('track_record',array('track_record.routeID'=>$routeID,'track_record.driverID'=>$driverID,'track_record.ride_type'=>$ride_type,'track_record.date'=>$date))->row();
        if(count($check)>0)
        {
            if(is_null($check->end_on))
            {
                $response[] =
                    array('result'=>'success','recordID'=>$check->recordID,'school_latlong'=>$check->school_latlong,'lastpoint_latlong'=>$check->lastpoint_latlong,'description'=>$check->description);
            } else {
                $response[] =
                    array('result'=>'failure','message'=>'Already Completed');
            }
        } else {
            //insert
            $a = array(
                'routeID'=>$routeID,
                'driverID'=>$driverID,
                'ride_type'=>$ride_type,
                'started_on'=>date('Y-m-d H:i:s'),
                'date'=>date('Y-m-d')
            );
            $recordID = $this->webservice_m->table_insert('track_record',$a);
            $this->db->select("track_record.recordID,track_record.end_on,route.school_latlong,route.lastpoint_latlong,route.description");
            $this->db->join('route','track_record.routeID = route.routeID','LEFT');
            $check = $this->db->get_where('track_record',array('track_record.recordID'=>$recordID))->row();
            $response[] =
                array('result'=>'success','recordID'=>$check->recordID,'school_latlong'=>$check->school_latlong,'lastpoint_latlong'=>$check->lastpoint_latlong,'description'=>$check->description);
        }
        echo json_encode($response);
    }
    public function end_ride()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $recordID = $data['recordID'];

        $this->webservice_m->table_update('track_record',array('end_on'=>date('Y-m-d H:i:s')),array('recordID'=>$recordID));
        $response[] = array('result'=>'success','recordID'=>$recordID);
        echo json_encode($response);
    }
    public function add_location()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $recordID = $data['recordID'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $this->db->insert('track_detail',array('recordID'=>$recordID,'latitude'=>$latitude,'longitude'=>$longitude,'added_on'=>date("Y-m-d H:i:s")));
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function routewise_students()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $routeID = $data['routeID'];
        $recordID = $data['recordID'];
        $path = base_url('uploads/images/');
        $this->db->select("student.studentID,student.name,CONCAT('".$path."',COALESCE(NULLIF(student.image, ''),'default.png')) as image,student.roll_no,classes.class as class_name,section.section,COALESCE(parent.name, '') as parent,COALESCE(transport_attendance.attendance, 'A') as attendance");
        $this->db->join('transport_attendance',"student.studentID = transport_attendance.studentID AND transport_attendance.recordID = '$recordID'",'LEFT');
        $this->db->join('parent','student.parentID = parent.parentID','LEFT');
        $this->db->join('section','student.sectionID = section.sectionID','LEFT');
        $this->db->join('classes','student.classID = classes.classID','LEFT');
        $student_list = $this->db->get_where('student',array('student.routeID'=>$routeID))->result();
        $response[] = array('result'=>'success','students'=>$student_list);
        echo json_encode($response);
    }
    public function add_attendance_transport()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $recordID = $data['recordID'];
        $studentID = $data['studentID'];
        $attendance = strtoupper($data['attendance']);
        $check = $this->db->get_where('transport_attendance',array('recordID'=>$recordID,'studentID'=>$studentID))->row();
        if ($check){
            $this->db->where(array('attID'=>$check->attID));
            $this->db->update('transport_attendance',array('attendance'=>$attendance));
        }else{
            $this->db->insert('transport_attendance',array('recordID'=>$recordID,'studentID'=>$studentID,'attendance'=>$attendance,'started_on'=>date("Y-m-d H:i:s")));
        }
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    // End Driver APP
    //student and parent app
    public function student_track_history()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $studentID = $data['studentID'];
        $this->db->select("student.studentID,student.schoolID,COALESCE(student.routeID,'') as routeID,COALESCE(route.route,'') as route,COALESCE(route.school_latlong,'') as school_latlong,COALESCE(route.lastpoint_latlong,'') as lastpoint_latlong");
        $this->db->join('route','student.routeID = route.routeID','LEFT');
        $student_info = $this->db->get_where('student',array('student.schoolID'=>$studentID))->row();
        if (count($student_info)){
            if ($student_info->route != ""){
                $this->db->join('track_record','transport_attendance.recordID = track_record.recordID','LEFT');
                $attendance = $this->db->get_where('transport_attendance',array('transport_attendance.studentID'=>$studentID))->result();
                $att = array();
                $s_att = array();
                foreach ($attendance as $a){
                    array_push($att,array('attID'=>$a->attID,'studentID'=>$a->studentID,'attendance'=>$a->attendance,'time'=>$a->started_on,'ride_type'=>$a->ride_type,'date'=>$a->date,'recordID'=>$a->recordID));
                }
                array_multisort(array_column($att, 'time'), SORT_DESC, $att);
                //unset($att[0]);
                foreach ($att as $a){
                    foreach ($s_att as $key => $val){
                        if ($val['date'] == $a['date']){
                            $i = $key;
                        }
                    }
                    if (!isset($i)){
                        $array = array(
                            'date'=>$a['date'],
                            'pickID'=>"",
                            'pick_time'=>"",
                            'dropID'=>"",
                            'drop_time'=>""
                        );
                        $pushID = array_push($s_att,$array);
                        if (strtolower($a['ride_type']) == 'pick'){
                            $s_att[($pushID -1)]['pickID'] = $a['recordID'];
                            $s_att[($pushID -1)]['pick_time'] = $a['time'];
                        }elseif (strtolower($a['ride_type']) == 'drop'){
                            $s_att[($pushID -1)]['dropID'] = $a['recordID'];
                            $s_att[($pushID -1)]['drop_time'] = $a['time'];
                        }
                    }else{
                        if (strtolower($a['ride_type']) == 'pick'){
                            $s_att[$i]['pickID'] = $a['recordID'];
                            $s_att[$i]['pick_time'] = $a['time'];
                        }elseif (strtolower($a['ride_type']) == 'drop'){
                            $s_att[$i]['dropID'] = $a['recordID'];
                            $s_att[$i]['drop_time'] = $a['time'];
                        }
                    }
                }
                $response[] = array('result'=>'success','studentID'=>$student_info->studentID,'schoolID'=>$student_info->schoolID,'routeID'=>$student_info->routeID,'route'=>$student_info->route,'school_latlong'=>$student_info->school_latlong,'last_latlong'=>$student_info->lastpoint_latlong,'history'=>$s_att);
            }else{
                $response[] = array('result'=>'failure','message'=>'No route allotted');
            }
        }else{
            $response[] = array('result'=>'failure','message'=>'Invalid Student');
        }
        echo json_encode($response);
    }
    public function current_location(){
        $data = json_decode(file_get_contents('php://input'), true);
        $routeID = $data['routeID'];
        $this->db->order_by('track_record.recordID','desc');
        $record = $this->db->get_where('track_record',array('track_record.routeID'=>$routeID,'track_record.date'=>date("Y-m-d")))->row();
        if (count($record)){
            $this->db->select("track_detail.ID as ID,CONCAT(COALESCE(track_detail.latitude,'0.0'),',',COALESCE(track_detail.longitude,'0.0')) as latlong, added_on");
            $this->db->order_by('track_detail.ID','desc');
            $current_point = $this->db->get_where('track_detail',array('recordID'=>$record->recordID))->row();
            if (count($current_point)){
                if (is_null($record->end_on)){
                    $record->end_on = "";
                }
                $response[] = array('result'=>'success','start_time'=>$record->started_on,'end_time'=>$record->end_on,'current_point'=>$current_point->latlong,'time'=>$current_point->added_on);
            }else{
                $response[] = array('result'=>'failure','message'=>'Ride Not Started Yet');
            }
        }else{
            $response[] = array('result'=>'failure','message'=>'Ride Not Started Yet');
        }
        echo json_encode($response);
    }

    //Admin
    public function get_driver_info()
    {
        $data = json_decode(file_get_contents('php://input'),true);
        $schoolID = $data['schoolID'];
        $drivers = array();
        $driver_info = $this->db->get_where('driver',array('schoolID'=>$schoolID))->result();
        foreach ($driver_info as $driver){
            if (is_null($driver->image) || $driver->image == ""){
                $driver->image = 'default.png';
            }
            $routes = $this->db->query("SELECT routeID,route FROM route WHERE Find_in_set(`routeID`,'$driver->routeID')")->result();
            $image_path = base_url('uploads/images/');
            $drivers[] = array(
                'driver_image'=>$image_path.$driver->image,
                'driver_name'=>$driver->driver_name,
                'driver_mobile'=>$driver->phone,
                'routes'=>$routes
            );
        }
        $response[] = array('result'=>'success','driver_info'=>$drivers);
        echo json_encode($response);
    }
//    public function get_transport_att()
//    {
//        $data = json_decode(file_get_contents('php://input'),true);
//        $studentID = $data['studentID'];
//        $month_year = $data['month_year'];
//        $add = "01-";
//        $month_year = str_replace('/','-',$month_year);
//        $month_year = $add.$month_year;
//        if ($month_year == ""){
//            $month = date("m");
//            $year = date("Y");
//        }else{
//            $month = date("m",strtotime($month_year));
//            $year = date("Y",strtotime($month_year));
//        }
//        $this->db->join('track_record','transport_attendance.recordID = track_record.recordID','LEFT');
//        $attendance = $this->db->get_where('transport_attendance',array('transport_attendance.studentID'=>$studentID,'year(transport_attendance.started_on)'=>$year,'month(transport_attendance.started_on)'=>$month))->result();
//        $att = array();
//        $s_att = array();
//        foreach ($attendance as $a){
//            array_push($att,array('attID'=>$a->attID,'studentID'=>$a->studentID,'attendance'=>$a->attendance,'time'=>$a->started_on,'ride_type'=>$a->ride_type,'date'=>$a->date,'recordID'=>$a->recordID));
//        }
//        array_multisort(array_column($att, 'time'), SORT_DESC, $att);
//        //unset($att[0]);
//        foreach ($att as $a){
//            foreach ($s_att as $key => $val){
//                if ($val['date'] == $a['date']){
//                    $i = $key;
//                }
//            }
//            if (!isset($i)){
//                $array = array(
//                    'date'=>$a['date'],
//                    'pickID'=>"",
//                    'pick_time'=>"",
//                    'dropID'=>"",
//                    'drop_time'=>""
//                );
//                $pushID = array_push($s_att,$array);
//                if (strtolower($a['ride_type']) == 'pick'){
//                    $s_att[($pushID -1)]['pickID'] = $a['recordID'];
//                    $s_att[($pushID -1)]['pick_time'] = $a['time'];
//                    $s_att[($pushID -1)]['status'] = $a['attendance'];
//                }elseif (strtolower($a['ride_type']) == 'drop'){
//                    $s_att[($pushID -1)]['dropID'] = $a['recordID'];
//                    $s_att[($pushID -1)]['drop_time'] = $a['time'];
//                    $s_att[($pushID -1)]['status'] = $a['attendance'];
//                }
//            }else{
//                if (strtolower($a['ride_type']) == 'pick'){
//                    $s_att[$i]['pickID'] = $a['recordID'];
//                    $s_att[$i]['pick_time'] = $a['time'];
//                    $s_att[$i]['status'] = $a['attendance'];
//                }elseif (strtolower($a['ride_type']) == 'drop'){
//                    $s_att[$i]['dropID'] = $a['recordID'];
//                    $s_att[$i]['drop_time'] = $a['time'];
//                    $s_att[$i]['status'] = $a['attendance'];
//                }
//            }
//        }
//        $response[] = array('result'=>'success','attendance'=>$s_att);
//        echo json_encode($response);
//    }
//    public function get_school_routes()
//    {
//        $data = json_decode(file_get_contents('php://input'), true);
//        $schoolID = $data['schoolID'];
//        $routes = $this->db->get_where('route',array('schoolID'=>$schoolID))->result();
//        $response[] = array('result'=>'success','routes'=>$routes);
//        echo json_encode($response);
//    }
//    public function get_records_school()
//    {
//        $data = json_decode(file_get_contents('php://input'), true);
//        $date = str_replace('/','-',$data['date']);
//        $routeID = $data['routeID'];
//        $records = $this->db->get_where('track_record',array('routeID'=>$routeID,'date(date)'=>date('Y-m-d',strtotime($date))))->result();
//        $response[] = array('result'=>'success','records'=>$records);
//        echo json_encode($response);
//    }
    //End Admin
    //End Transport
    //for Batch
    
    
    
    //BY SUNIL - get_driver_info1
    public function get_driver_info1()
    {
        $data = json_decode(file_get_contents('php://input'),true);
        $schoolID = $data['schoolID'];
        $drivers = array();
        $driver_info = $this->db->get_where('driver',array('schoolID'=>$schoolID))->result();
        foreach ($driver_info as $driver){
            if (is_null($driver->image) || $driver->image == ""){
                $driver->image = 'default.png';
            }
            
            $image_path = base_url('uploads/images/');
            $routes = $this->get_routes_data($driver->driverID,$driver->routeID);
            $drivers[] = array(
                'driver_image'=>$image_path.$driver->image,
                'driver_name'=>$driver->driver_name,
                'driver_mobile'=>$driver->phone,
                'routes'=>$routes
            );
        }
        $response[] = array('result'=>'success','driver_info'=>$drivers);
        echo json_encode($response);
    }

    public function get_routes_data($driverID,$routeID)
    {
       $routes = $this->db->query("SELECT routeID,route FROM route WHERE Find_in_set(`routeID`,'$routeID')")->result();
       $r = array();
       foreach($routes as $ro)
       {
        $latitude = '';
        $longitude = '';
        //get last record
        $tr = $this->db->query("SELECT `recordID` FROM `track_record` WHERE `routeID`='$ro->routeID' AND `driverID`='$driverID' ORDER BY `recordID` DESC LIMIT 1")->row();
        if(count($tr)){
         $record = $this->db->query("SELECT `latitude`, `longitude` FROM `track_detail` WHERE `recordID`='$tr->recordID' ORDER BY `ID` DESC LIMIT 1")->row();
         if(count($record))
         {
          $latitude = $record->latitude;
          $longitude = $record->longitude;
         }
        }
         $r[] = array(
           "routeID" => $ro->routeID,
           "route" => $ro->route,
           "latitude" =>$latitude,
           "longitude" => $longitude
         );
       }
       return $r;
    }
    
    public function batches()
    {
        $data = json_decode(file_get_contents('php://input'),true);
        $schoolID = $data['schoolID'];
        $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
        $batches = $this->db->get_where('batch',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
        $response[] = array('result'=>'success','batches'=>$batches);
        echo json_encode($response);
    }
    //End Batch
    public function add_attendance_face()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $students = $data['students'];
        //$students ="1@1@1,2@1@1,3@1@1,4@1@1";
        $subjectID = 0;
        $month_year = date('m-Y');
        $d = date('d');
        $a = "a".abs($d);
        $student_array = explode(',', $students);
        foreach($student_array as $st)
        {
            $student = explode('@', $st);
            $studentID = $student[0];
            $status = 'P';
            $classID = $student[1];
            $sectionID = $student[2];
            $check = $this->webservice_m->check_attendance(array('schoolID'=>$schoolID, 'studentID'=>$studentID, 'classesID'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
            if(count($check)>0)
            {
                //update
                $this->webservice_m->update_attendance(array($a=>$status),array('studentID'=>$studentID, 'classesID'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID, 'monthyear'=>$month_year));
            } else {
                //insert
                $this->webservice_m->insert_attendance(array('schoolID'=>$schoolID,'academicID'=>1,'studentID'=>$studentID, 'classesID'=>$classID, 'sectionID'=>$sectionID, 'subjectID'=>$subjectID, 'userID'=>$studentID, 'usertype'=>'student', 'monthyear'=>$month_year,$a=>$status));
            }
        }
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }
    public function logout()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userID = $data['userID'];
        $role = strtolower($data['role']);
        $deviceID = $data['deviceID'];
        $device_type = $data['device_type'];
        $this->db->where(array('userID'=>$userID,'role'=>$role,'device_type'=>$device_type,'deviceID'=>$deviceID));
        $this->db->delete('user_login');
        $response[] = array('result'=>'success');
        echo json_encode($response);
    }

    /*  POLLS */
    public function poll_list()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $schoolID = $data['schoolID'];
        $userID = $data['userID'];
        $role = $data['role'];
        $a = array();
        $this->db->order_by('pollID','desc');
        $poll = $this->db->get_where('poll',array('schoolID'=>$schoolID,'status'=>'Y'))->result();
        foreach($poll as $p)
        {
            $d = date('Y-m-d',strtotime($p->end_date));
            $cur_date = date('Y-m-d');
            $answ = '';
            $scale1 = '';
            $scale2 = '';
            $option1 = '';
            $option2 = '';
            $option3 = '';
            $option4 = '';
            if(strtotime($d) >= strtotime($cur_date))
            {
                $status = 'Y';
            } else {
                $status = 'N';
            }

            if($p->ques_type == 'open')
            {
                $answer = $this->db->get_where('pollanswer',array("pollID"=>$p->pollID,"userID"=>$userID, "usertype"=>$role))->result();
                foreach($answer as $ans)
                {
                    $answ = $ans->answer;
                }
                $a[] = array(
                    "pollID" => $p->pollID,
                    "question" => $p->question,
                    "ques_type" => $p->ques_type,
                    "status" => $status,
                    'answer' => $answ,
                    'option1' =>'',
                    'option2' => '',
                    'option3' => '',
                    'option4' => '',
                    'scale1' => '',
                    'scale2' => ''
                );
            } elseif($p->ques_type == 'scale'){
                $answer = $this->db->get_where('pollanswer',array("pollID"=>$p->pollID,"userID"=>$userID, "usertype"=>$role))->result();
                foreach($answer as $ans)
                {
                    $answ = $ans->answer;
                }

                $options = $this->db->get_where('poll_options',array('pollID'=>$p->pollID))->result();
                foreach($options as $opt)
                {
                    $scale1 = $opt->scale1;
                    $scale2 = $opt->scale2;
                }
                $a[] = array(
                    "pollID" => $p->pollID,
                    "question" => $p->question,
                    "ques_type" => $p->ques_type,
                    "status" => $status,
                    'answer' => $answ,
                    'option1' =>'',
                    'option2' => '',
                    'option3' => '',
                    'option4' => '',
                    'scale1' => $scale1,
                    'scale2' => $scale2
                );
            } elseif($p->ques_type == 'option'){
                $answer = $this->db->get_where('pollanswer',array("pollID"=>$p->pollID,"userID"=>$userID, "usertype"=>$role))->result();
                foreach($answer as $ans)
                {
                    $answ = $ans->answer;
                }
                $options = $this->db->get_where('poll_options',array('pollID'=>$p->pollID))->result();
                foreach($options as $opt)
                {
                    $option1 = $opt->option1;
                    $option2 = $opt->option2;
                    $option3 = $opt->option3;
                    $option4 = $opt->option4;
                }
                $a[] = array(
                    "pollID" => $p->pollID,
                    "question" => $p->question,
                    "ques_type" => $p->ques_type,
                    "status" => $status,
                    'answer' => $answ,
                    'option1' => $option1,
                    'option2' => $option2,
                    'option3' => $option3,
                    'option4' => $option4,
                    'scale1' => '',
                    'scale2' => ''
                );
            } elseif($p->ques_type == 'voting') {
                $answer = $this->db->get_where('pollanswer',array("pollID"=>$p->pollID,"userID"=>$userID, "usertype"=>$role))->result();
                foreach($answer as $ans)
                {
                    $answ = $ans->answer;
                }
                $a[] = array(
                    "pollID" => $p->pollID,
                    "question" => $p->question,
                    "ques_type" => $p->ques_type,
                    "status" => $status,
                    'answer' => $answ,
                    'option1' =>'',
                    'option2' => '',
                    'option3' => '',
                    'option4' => '',
                    'scale1' => '',
                    'scale2' => ''
                );
            }

        }


        $detail[] = array(
            "result" => "success",
            "polls" => $a
        );

        echo json_encode($detail);
    }

    public function poll_answer()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $pollID = $data['pollID'];
        $usertype = $data['role'];
        $username = $data['userID'];
        $answer = $data['answer'];
        $poll = $this->db->get_where('poll',array("pollID"=>$pollID))->row();
        if($poll->ques_type == 'voting')
        {
            $array = array(
                "pollID" => $pollID,
                "usertype" => $usertype,
                "userID" => $username,
                "answer" => $answer,
                "vote" => $answer,
                "added_on" => date('Y-m-d H:i:s')
            );

        } else {
            $array = array(
                "pollID" => $pollID,
                "usertype" => $usertype,
                "userID" => $username,
                "answer" => $answer,
                "added_on" => date('Y-m-d H:i:s')
            );
        }
        $check = $this->db->get_where('pollanswer',array("pollID" => $pollID,"usertype" => $usertype,"userID" => $username))->row();
        if (count($check)){
            $detail[] = array(
                "result" => "success",
                "message" => "Already Answered"
            );
        }else{
            $this->db->insert('pollanswer',$array);
            $detail[] = array(
                "result" => "success",
                "message" => "Answer Submitted Successfully"
            );
        }
        echo json_encode($detail);

    }

    public function get_admin_classes()
    {
        $classes =
            $this->webservice_m->get_all_data_where('admin_class',array('is_active'=>'Y'));
        $response[] = array('result'=>'success','classes'=>$classes);
        echo json_encode($response);
    }

    public function get_admin_subject()
    {
        $subjects =
            $this->webservice_m->get_all_data_where('admin_subject',array('is_active'=>'Y'));
        $response[] = array('result'=>'success','subjects'=>$subjects);
        echo json_encode($response);
    }

    public function get_quiz_topics()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $classID = $data['classID'];
        $subjectID = $data['subjectID'];
        $topics =
            $this->webservice_m->get_all_data_where('quiz_level',array('classid'=>$classID,'subjectid'=>$subjectID));
        if(count($topics)>0)
        {
            $response[] = array('result'=>'success','topics'=>$topics);
        } else {
            $response[] = array('result'=>'failure','message'=>'No topics Available according to your preference.');
        }

        echo json_encode($response);
    }

    public function get_question_topicwise()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $topicID = $data['topicID'];
        //$quiz_bank = $this->webservice_m->get_all_data_where('quiz_bank',array('quiz_level'=>$topicID));
        $this->db->select("Que_ID,Question,quiz_level,OptionA,OptionB,OptionC,OptionD,RightAns,marks,COALESCE(explanation,'') as explanation");
        $quiz_bank = $this->db->get_where('quiz_bank',array('quiz_level'=>$topicID))->result();
        if(count($quiz_bank)>0)
        {
            $response[] =
                array('result'=>'success','quiz_bank'=>$quiz_bank);
        } else {
            $response[] = array('result'=>'failure','message'=>'No Questions Available');
        }

        echo json_encode($response);

    }

    public function quiz_feedback()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $studentID = $data['studentID'];
        $quesID = $data['quesID'];
        $feedback = $data['feedback'];
        if ($studentID == "" || $quesID == "" || $feedback == ""){
            $response[] = array('result'=>'failure','message'=>'Please provide correct details');
        }else{
            $this->db->insert('quiz_feedback',array('studentID'=>$studentID,'quesID'=>$quesID,'feedback'=>$feedback,'added_on'=>date("Y-m-d H:i:s")));
            $feedbackID = $this->db->insert_id();
            $response[] = array('result'=>'success','message'=>'Successfully Submitted','feedbackID'=>$feedbackID);
        }
        echo json_encode($response);
    }

}


