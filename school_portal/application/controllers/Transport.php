<?php
/**
 * Created by PhpStorm.
 * User: kshit
 * Date: 2019-02-05
 * Time: 09:53:54
 */

class Transport extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->library('SSP');
        $this->load->model("fcm_m");
    }
    public function index($param1 = '',$param2 = ''){
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $routeID = $param2;
                if ($_POST)
                {
                    $array2 = array(
                        'route'=>$this->input->post('route'),
                        'price'=>$this->input->post('price'),
                        'school_latlong'=>$this->input->post('s_latitude').','.$this->input->post('s_longitude'),
                        'lastpoint_latlong'=>$this->input->post('l_latitude').','.$this->input->post('l_longitude'),
                        'description'=>$this->input->post('description')
                    );
                    $this->db->where(array('routeID'=>$routeID));
                    $this->db->update('route',$array2);
                    redirect(base_url('transport/index'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array(
                        'schoolID'=>$_SESSION['loginUserID'],
                        'route'=>$this->input->post('route'),
                        'price'=>$this->input->post('price'),
                        'school_latlong'=>$this->input->post('s_latitude').','.$this->input->post('s_longitude'),
                        'lastpoint_latlong'=>$this->input->post('l_latitude').','.$this->input->post('l_longitude'),
                        'description'=>$this->input->post('description')
                    );
                    $this->db->insert('route',$array2);
                    redirect(base_url('transport/index'));
                }else{
                    $this->data['route'] = $this->db->get_where('route',$array1)->result();
                    $this->data['title'] = "Routes";
                    $this->data['subview'] = "transport/route";
                    $this->load->view("layout",$this->data);
                }
            }


        }

    }
    public function drivers($param1 = '',$param2 = '')
    {
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 == 'edit' && $param2 != '')
            {
                $routeID = $param2;
                if ($_POST)
                {
                    $routes_array = implode(',',$this->input->post('routes'));
                    $array2 = array(
                        'schoolID'=>$_SESSION['loginUserID'],
                        'driver_name'=>$this->input->post('name'),
                        'email'=>$this->input->post('email'),
                        'image'=>'default.png',
                        'license_no'=>$this->input->post('license_no'),
                        'routeID'=>$routes_array,
                        'status'=>$this->input->post('status'),
                        'added_on'=>date("Y-m-d H:i:s"),
                        'password'=>$this->signin_m->hash($this->input->post('password'))
                    );
                    $this->db->where(array('driverID'=>$routeID));
                    $this->db->update('driver',$array2);
                    redirect(base_url('transport/drivers'));
                }
            }else{
                if ($_POST)
                {
                    $routes_array = implode(',',$this->input->post('routes'));
                    $array2 = array(
                        'schoolID'=>$_SESSION['loginUserID'],
                        'driver_name'=>$this->input->post('name'),
                        'phone'=>$this->input->post('mobile'),
                        'email'=>$this->input->post('email'),
                        'image'=>'default.png',
                        'license_no'=>$this->input->post('license_no'),
                        'routeID'=>$routes_array,
                        'status'=>$this->input->post('status'),
                        'added_on'=>date("Y-m-d H:i:s"),
                        'password'=>$this->signin_m->hash($this->input->post('password'))
                    );
                    $check = $this->db->get_where('driver',array('phone'=>$this->input->post('mobile')))->row();
                    if (count($check)){
                        $error = "This mobile number is already registered with us. Please try again later.";
                        $_SESSION['error'] = 'error_msg';
                        $_SESSION['error_msg'] = $error;
                        redirect(base_url("transport/drivers"));
                    }else{
                        $this->db->insert('driver',$array2);
                        redirect(base_url('transport/drivers'));
                    }
                }else{
                    $drivers = array();
                    $driver_info = $this->db->get_where('driver',$array1)->result();
                    foreach ($driver_info as $driver){
                        if (is_null($driver->image) || $driver->image == ""){
                            $driver->image = 'default.png';
                        }
                        $routes = $this->db->query("SELECT routeID,route FROM route WHERE Find_in_set(`routeID`,'$driver->routeID')")->result();
                        $image_path = base_url('uploads/images/');
                        $drivers[] = array(
                            'driverID'=>$driver->driverID,
                            'driver_image'=>$image_path.$driver->image,
                            'driver_name'=>$driver->driver_name,
                            'driver_mobile'=>$driver->phone,
                            'email'=>$driver->email,
                            'license_no'=>$driver->license_no,
                            'status'=>$driver->status,
                            'routes'=>$routes
                        );
                    }
                    $this->data['school_routes'] = $this->db->get_where('route',$array1)->result();
                    $this->data['drivers'] = $drivers;
                    $this->data['title'] = "Drivers";
                    $this->data['subview'] = "transport/drivers";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function change_username($param1 = '')
    {
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 != '' && $_POST){
                $driverID = $param1;
                $mobile = $this->input->post('mobile');
                $check = $this->db->get_where('driver',array('phone'=>$mobile))->row();
                if (count($check)){
                    $error = "This mobile number is already registered with us. Please try again later.";
                    $_SESSION['error'] = 'error_msg1';
                    $_SESSION['error_msg'] = $error;
                    redirect(base_url("transport/drivers"));
                }else{
                    $this->db->where(array('driverID'=>$driverID));
                    $this->db->update('driver',array('phone'=>$this->input->post('mobile')));
                    redirect(base_url('transport/drivers'));
                }

            }
        }
    }
    public function change_password($param1 = '')
    {
        if ($_SESSION['role'] == "school"){
            $array1 = array('schoolID'=>$_SESSION['loginUserID']);
            if ($param1 != '' && $_POST){
                $driverID = $param1;
                $password = $this->input->post('password');
                $c_password = $this->input->post('c_password');
                if ($password == $c_password){
                    $this->db->where(array('driverID'=>$driverID));
                    $this->db->update('driver',array('password'=>$this->signin_m->hash($password)));
                    redirect(base_url('transport/drivers'));
                }else{
                    $error = "Password and Confirm Password not matches. Please Try Again Later.";
                    $_SESSION['error'] = 'error_msg1';
                    $_SESSION['error_msg'] = $error;
                    redirect(base_url("transport/drivers"));
                }
            }
        }
    }
    public function transport_allocation()
    {
        if ($_SESSION['role']=="school")
        {
            $schoolID = $_SESSION['schoolID'];
            if ($_POST)
            {
                $this->db->where(array('studentID'=>$this->input->post('studentID'),'schoolID'=>$schoolID));
                $query = $this->db->update('student',array('routeID'=>$this->input->post('routeID'),'price'=>$this->input->post('price')));
                if($query){
                    echo "success";
                }else{
                    echo "failed";
                }
            }else{
                $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
                $this->data['classes'] = $this->db->get_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
                $this->data['title'] = "Allocate Transport to Students";
                $this->data['subview'] = "transport/allocate_transport";
                $this->load->view("layout",$this->data);
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function get_route_info()
    {
        if ($_SESSION['role']=="school")
        {
            $schoolID = $_SESSION['schoolID'];
            if ($_POST)
            {
                $routeID = $this->input->post('routeID');
                $route_info = $this->db->get_where('route',array('routeID'=>$routeID))->row();
                echo json_encode($route_info);
            }
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
            $select = "routeID,route";
            $routes = $this->db->get_where('route',$array = array('schoolID'=>$schoolID),$select)->result();
            $option = "<option value='0'>none</option>";
            foreach ($routes as $con) {
                $option = $option."<option value='".$con->routeID."'>".$con->route."</option>";
            }
            //$select = "groupID,group_name";
            //$groups = $this->accounts_m->get_multiple_row('student_group',array('schoolID'=>$schoolID),$select);
            if ($param1 == "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, COALESCE(student.price,'0') AS r_price, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.routeID,'0'))as route, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, COALESCE(student.price,'0') AS r_price, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.routeID,'0'))as route, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 == "") {
                $table = "(select student.studentID, COALESCE(student.price,'0') AS r_price, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.routeID,'0'))as route, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 == "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, COALESCE(student.price,'0') AS r_price, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.routeID,'0'))as route, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, COALESCE(student.price,'0') AS r_price, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.routeID,'0'))as route, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 != "") {
                $table = "(select student.studentID, COALESCE(student.price,'0') AS r_price, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.routeID,'0'))as route, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }
            //$table = "(select CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession,student.studentID,student.name,student.roll_no,student.phone,student.image,parent.name as parent,student.concessionID   FROM student LEFT JOIN parent on student.parentID = parent.parentID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2)as table1";
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
                array( 'db' => 'r_price', 'dt' => 8 ),
                array( 'db' => 'route',
                    'dt' => 9,
                    'formatter' => function( $d, $row) use ($routes){
                        $route_array = explode(",", $d);
                        //$d = $concession_array[1];
                        $select1 = "<select class='form-control allocate_concession' onchange='alert_selected(".$route_array[0].")'  id = ".$route_array[0].">";
                        $select2 = "</select>";
                        $option = "<option value='0'>none</option>";
                        foreach ($routes as $con) {
                            if ($con->routeID == $route_array[1]) {
                                $option = $option."<option selected = 'selected' value='".$con->routeID."'>".$con->route."</option>";
                            }else{
                                $option = $option."<option value='".$con->routeID."'>".$con->route."</option>";
                            }

                        }
                        $input = "<input class='form-control' placeholder='amount' type='number' step='any' id='price".$route_array[0]."'>";
                        $input1 = "<input type='button' class='btn btn-sm btn-success' onclick='update_route(".$route_array[0].")' value='update'>";
                        return $select1.$option.$select2.$input.$input1;
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
    public function attendance()
    {
        if ($_SESSION['role'] == "school") {
            $schoolID = $_SESSION['loginUserID'];
            $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
            $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
            $this->data['classes'] = $this->db->get_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
            $this->data['title'] = "Transport Attendence";
            $this->data['subview'] = "transport/student";
            $this->load->view("layout",$this->data);
        }

    }
    public function list_students_att($param1="",$param2="",$param3="")
    {
        if ($_SESSION['role']=="school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if ($param1 == 'null'){
                $param1 = "";
            }
            if ($param2 == 'null'){
                $param2 = "";
            }
            if ($param3 == 'null'){
                $param3 = "";
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
                        $student_edit_button = '<a class = "btn btn-success" target="_blank" href = "'.base_url("transport/view_attendance/$d").'">View</a>';
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
    public function view_attendance($param1="")
    {
        if ($_SESSION['role'] == "school") {
            if ($param1 != "")
            {
                $studentID = $param1;
                $schoolID = $_SESSION['loginUserID'];
                $select = "classID,sectionID,name";
                $student = $this->db->get_where('student',array('studentID'=>$studentID,'schoolID'=>$schoolID),$select)->row();
                if (count($student)){
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
                    $this->data['attendance'] = $s_att;
                    $this->data['title'] = $student->name." Attendence";
                    $this->data['subview'] = "transport/show_attendance";
                    $this->load->view("layout",$this->data);
                }else{
                    redirect(base_url("transport/attendance"));
                }
            }else{
                redirect(base_url("transport/attendance"));
            }

        }else{
            echo "Permission Denied";
        }
    }
}