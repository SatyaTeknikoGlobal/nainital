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

class Accounts extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("webservice_m");
        $this->load->model("accounts_m");
        $this->load->library('SSP');
        $this->load->model("fcm_m");
        $this->load->library('email');
    }
    public function send_email_attachment($subject, $message, $to, $attachment)
    {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
            <title>' . html_escape($subject) . '</title>
            <style type="text/css">
                body {
                    font-family: Arial, Verdana, Helvetica, sans-serif;
                    font-size: 16px;
                }
            </style>
        </head>
        <body>
        ' . $message . '
        </body>
        </html>';
        $result = $this->email
            ->from('webmaster@mentorerp.com')
            ->reply_to('webmaster@mentorerp.com')    // Optional, an account where a human being reads.
            ->to($to)
            ->subject($subject)
            ->message($body)
            ->attach('/home/mentorerp/public_html/customer_portal/invoices/'.$attachment)
            ->send();

        return '/home/mentorerp/public_html/customer_portal/invoices/'.$attachment.$result;
    }
    public function index()
    {
        if($_SESSION['role'] == "school")
        {
            redirect(base_url("accounts/fee_head"));
        }
    }
    public function fee_head($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($param1 == "edit" && $param2 != "")
            {
                if (isset($_POST['is_onetime'])){
                    $is_onetime = "Y";
                }else{
                    $is_onetime = "N";
                }
                if (isset($_POST['is_transport'])){
                    $is_transport = "Y";
                }else{
                    $is_transport = "N";
                }
                $array = array(
                    'feehead'=>$this->input->post('feehead'),
                    'is_onetime'=>$is_onetime,
                    'is_transport'=>$is_transport,
                    'is_active'=>$this->input->post('is_active'),
                    'modified_on'=>date("Y-m-d H:i:s")
                );
                $this->db->where(array('schoolID'=>$schoolID,'feeheadID'=>$param2));
                $this->db->update('feehead',$array);
                redirect(base_url("accounts/fee_head"));
            }else{
                if($_POST)
                {
                    if (isset($_POST['is_onetime'])){
                        $is_onetime = "Y";
                    }else{
                        $is_onetime = "N";
                    }
                    if (isset($_POST['is_transport'])){
                        $is_transport = "Y";
                    }else{
                        $is_transport = "N";
                    }
                    $array = array(
                        'schoolID'=>$schoolID,
                        'feehead'=>$this->input->post('feehead'),
                        'is_onetime'=>$is_onetime,
                        'is_transport'=>$is_transport,
                        'is_active'=>$this->input->post('is_active'),
                        'added_on'=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert('feehead',$array);
                    redirect(base_url("accounts/fee_head"));
                }else{
                    $array = array('schoolID'=>$schoolID);
                    $this->data['feehead'] = $this->accounts_m->get_multiple_row('feehead',$array);
                    $this->data['title'] = "Fee Heads";
                    $this->data['subview'] = "accounts/fee_head";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function fee_type($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($param1 == "edit" && $param2 != "")
            {
                $feeheads = implode(",",$this->input->post('feehead'));
                $array = array('feetype'=>$this->input->post('feetype'),'feehead'=>$feeheads,'is_active'=>$this->input->post('is_active'),'modified_on'=>date("Y-m-d H:i:s"));
                $this->db->where(array('schoolID'=>$schoolID,'feetypeID'=>$param2));
                $this->db->update('feetype',$array);
                redirect(base_url("accounts/fee_type"));
            }else{
                if($_POST)
                {
                    $feeheads = implode(",",$this->input->post('feehead1'));
                    $array = array('schoolID'=>$schoolID,'feetype'=>$this->input->post('feetype'),'feehead'=>$feeheads,'is_active'=>$this->input->post('is_active'),'added_on'=>date("Y-m-d H:i:s"));
                    $this->db->insert('feetype',$array);
                    redirect(base_url("accounts/fee_type"));
                }else{
                    $array = array('schoolID'=>$schoolID,'is_active'=>"Y");
                    $this->data['feehead'] = $this->accounts_m->get_multiple_row('feehead',$array);
                    $this->data['feetype'] = $this->accounts_m->get_multiple_row('feetype',array('schoolID'=>$schoolID));
                    $this->data['title'] = "Fee Types";
                    $this->data['subview'] = "accounts/fee_type";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function fee_structure($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($param1 == "edit" && $param2 != "")
            {
                $array = array('feeheadID'=>$this->input->post('feeheadID'),'classID'=>$this->input->post('classID'),'amount'=>$this->input->post('amount'),'modified_on'=>date("Y-m-d H:i:s"));
                $this->db->where(array('schoolID'=>$schoolID,'feestructureID'=>$param2));
                $this->db->update('feestructure',$array);
                redirect(base_url("accounts/fee_structure"));
            }else{
                if($_POST)
                {
                    $classes = $this->input->post('classID');
                    foreach($classes as $c){
                        $check = $this->db->get_where('feestructure',array('schoolID'=>$schoolID,'feeheadID'=>$this->input->post('feeheadID'),'classID'=>$c))->row();
                        if (count($check)){
                            $array = array('amount'=>$this->input->post('amount'),'modified_on'=>date("Y-m-d H:i:s"));
                            $this->db->where(array('schoolID'=>$schoolID,'feeheadID'=>$this->input->post('feeheadID'),'classID'=>$c));
                            $this->db->update('feestructure',$array);
                        }else{
                            $array = array('schoolID'=>$schoolID,'feeheadID'=>$this->input->post('feeheadID'),'classID'=>$c,'amount'=>$this->input->post('amount'),'added_on'=>date("Y-m-d H:i:s"));
                            $this->db->insert('feestructure',$array);
                        }
                    }

                    redirect(base_url("accounts/fee_structure"));
                }else{
                    $array = array('schoolID'=>$schoolID,'is_active'=>"Y",'is_transport'=>'N');
                    $this->data['feehead'] = $this->accounts_m->get_multiple_row('feehead',$array);
                    $this->data['classes'] = $this->accounts_m->get_multiple_row('classes',array('schoolID'=>$_SESSION['loginUserID'],'is_active'=>"Y"),'classID,class');
                    $select = "classes.class,feehead.feehead,feestructure.*";
                    $this->data['feestructure'] = $this->accounts_m->get_feestructure('feestructure',array('feestructure.schoolID'=>$schoolID),$select);
                    $this->data['title'] = "Fee Structure";
                    $this->data['subview'] = "accounts/fee_structure";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function concession($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if ($param1 == "edit" && $param2 != "")
            {
                if ($_POST){
                    $feeheads = implode(",",$this->input->post('feehead'));
                    $array = array('concession'=>$this->input->post('concession'),'feehead'=>$feeheads,'percentage'=>$this->input->post('percentage'),'is_active'=>$this->input->post('is_active'),'modified_on'=>date("Y-m-d H:i:s"));
                    $this->db->where(array('schoolID'=>$schoolID,'concessionID'=>$param2));
                    $this->db->update('concession',$array);
                }
                redirect(base_url("accounts/concession"));

            }else{
                if ($_POST){
                    $feeheads = implode(",",$this->input->post('feehead'));
                    $array = array('schoolID'=>$schoolID,'concession'=>$this->input->post('concession'),'feehead'=>$feeheads,'percentage'=>$this->input->post('percentage'),'is_active'=>$this->input->post('is_active'),'added_on'=>date("Y-m-d H:i:s"));
                    $this->db->insert('concession',$array);
                    redirect(base_url("accounts/concession"));
                }else{
                    $array = array('schoolID'=>$schoolID,'is_active'=>"Y");
                    $this->data['feehead'] = $this->accounts_m->get_multiple_row('feehead',$array);
                    $this->data['concession'] = $this->accounts_m->get_multiple_row('concession',array('schoolID'=>$schoolID));
                    $this->data['title'] = "Concession / Scholarship";
                    $this->data['subview'] = "accounts/concession";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function allocate_concession()
    {
        if ($_SESSION['role']=="school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST)
            {
                $this->db->where(array('studentID'=>$this->input->post('studentID'),'schoolID'=>$schoolID));
                $query = $this->db->update('student',array('concessionID'=>$this->input->post('concessionID')));
                if($query){
                    echo "success";
                }else{
                    echo "failed";
                }
            }else{
                $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
                $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
                $this->data['classes'] = $this->accounts_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                $this->data['title'] = "Allocate Scholarship / Concession";
                $this->data['subview'] = "accounts/allocate_concession";
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
            $select = "concessionID,concession";
            $concession = $this->accounts_m->get_multiple_row('concession',$array = array('schoolID'=>$schoolID,'is_active'=>"Y"),$select);
            $option = "<option value='0'>none</option>";
            foreach ($concession as $con) {
                $option = $option."<option value='".$con->concessionID."'>".$con->concession."</option>";
            }
            //$select = "groupID,group_name";
            //$groups = $this->accounts_m->get_multiple_row('student_group',array('schoolID'=>$schoolID),$select);
            if ($param1 == "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 == "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
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
                array( 'db' => 'concession',
                    'dt' => 8,
                    'formatter' => function( $d, $row) use ($concession){
                        $concession_array = explode(",", $d);
                        //$d = $concession_array[1];
                        $select1 = "<select class='form-control allocate_concession' onchange='alert_selected(".$concession_array[0].")'  id = ".$concession_array[0].">";
                        $select2 = "</select>";
                        $option = "<option value='0'>none</option>";
                        foreach ($concession as $con) {
                            if ($con->concessionID == $concession_array[1]) {
                                $option = $option."<option selected = 'selected' value='".$con->concessionID."'>".$con->concession."</option>";
                            }else{
                                $option = $option."<option value='".$con->concessionID."'>".$con->concession."</option>";
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
    public function generate_invoice()
    {
        if ($_SESSION['role']=="school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($_POST)
            {
                $school_info = $this->accounts_m->get_single_row('school_config',array('schoolID'=>$schoolID));
                $feetypeID = $this->input->post('feetypeID');
                $monthyear = date("Y-m",strtotime($this->input->post('from_month-year'))).'-01';
                $to_monthyear = date("Y-m",strtotime($this->input->post('to_month-year'))).'-01';
                $classes = $this->input->post('classID');
                $last_date = date('Y-m-d',strtotime($this->input->post('last_date')));
                $fee_heads = $this->accounts_m->get_single_row('feetype',array('feetypeID'=>$feetypeID,'schoolID'=>$schoolID,'is_active'=>'Y'));
                if (strtotime($monthyear) <= strtotime($to_monthyear)){
                    $feehead_array = array();
                    if (count($fee_heads)) {
                        $feehead_array = explode(",", $fee_heads->feehead);
                        foreach ($classes as $c) {
                            $select = "schoolID,classID,sectionID,studentID,concessionID,routeID,coalesce(price,0) as t_price";
                            $students_array = $this->accounts_m->get_multiple_row('student',array('is_active'=>'Y','schoolID'=>$schoolID,'classID'=>$c),$select);
                            if (count($students_array)) {
                                foreach ($students_array as $student) {
                                    $start    = (new DateTime($monthyear))->modify('first day of this month');
                                    $end      = (new DateTime($to_monthyear))->modify('first day of next month');
                                    $interval = DateInterval::createFromDateString('1 month');
                                    $period   = new DatePeriod($start, $interval, $end);
                                    foreach ($period as $dt) {
                                        $date = $dt->format("Y-m-d");
                                        $invoice_check = $this->accounts_m->get_single_row('invoice',array('schoolID'=>$schoolID,'studentID'=>$student->studentID,'date(monthyear) <='=>$date,'date(to_monthyear) >='=>$date,'status'=>'Y'));
                                        if (count($invoice_check)){
                                            break;
                                        }
                                    }
                                    if (count($invoice_check) <= 0) {
                                        $this->db->insert('invoice',array('schoolID'=>$schoolID,'session'=>$school_info->default_academic_year,'classID'=>$student->classID,'sectionID'=>$student->sectionID,'studentID'=>$student->studentID,'monthyear'=>$monthyear,'to_monthyear'=>$to_monthyear,'status'=>'Y','due_date'=>$last_date,'added_on'=>date("Y-m-d H:i:s")));
                                        $invoiceID = $this->db->insert_id();
                                        foreach ($feehead_array as $fa) {
                                            $feehead_dec = $this->accounts_m->get_single_row('feehead',array('feeheadID'=>$fa,'is_active'=>'Y','schoolID'=>$schoolID));
                                            if (count($feehead_dec)) {
                                                if ($feehead_dec->is_onetime == "Y"){
                                                    $one_time_check = $this->db->get_where('invoice_fee_head',array('feeheadID'=>$feehead_dec->feeheadID,'session'=>$school_info->default_academic_year,'studentID'=>$student->studentID))->row();
                                                    if (count($one_time_check) <= 0){
                                                        $already = 0;
                                                    }else{
                                                        $already = 1;
                                                    }
                                                }else{
                                                    $already = 0;
                                                }
                                                if ($already == 0){
                                                    $concession_type = " ";
                                                    $concession_amount = 0;
                                                    $amount = 0;
                                                    if ($feehead_dec->is_transport == "Y"){
                                                        $paid_amount = 0;
                                                        $amount = $student->t_price;
                                                        if (!empty($student->concessionID) || $student->concessionID > 0) {
                                                            $concession = $this->accounts_m->get_single_row('concession',array('schoolID'=>$schoolID,'concessionID'=>$student->concessionID,'is_active'=>'Y'));
                                                            if (count($concession)) {
                                                                $con_feehead = explode(",", $concession->feehead);
                                                                if (in_array($fa, $con_feehead)) {
                                                                    $concession_type = $concession->concession;
                                                                    $concession_amount = ($concession->percentage * $amount)/100;
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        $feestructure = $this->accounts_m->get_single_row('feestructure',array('schoolID'=>$schoolID,'classID'=>$c,'feeheadID'=>$fa));
                                                        $paid_amount = 0;
                                                        if (count($feestructure)) {
                                                            $amount = $feestructure->amount;
                                                            if (!empty($student->concessionID) || $student->concessionID > 0) {
                                                                $concession = $this->accounts_m->get_single_row('concession',array('schoolID'=>$schoolID,'concessionID'=>$student->concessionID,'is_active'=>'Y'));
                                                                if (count($concession)) {
                                                                    $con_feehead = explode(",", $concession->feehead);
                                                                    if (in_array($fa, $con_feehead)) {
                                                                        $concession_type = $concession->concession;
                                                                        $concession_amount = ($concession->percentage * $amount)/100;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $payable_amount = $amount - $concession_amount;
                                                    $due_amount = $payable_amount;
                                                    $advance_array = $this->accounts_m->get_single_row('advance_amount',array('schoolID'=>$schoolID,'studentID'=>$student->studentID));
                                                    if (count($advance_array)) {
                                                        $advance = $advance_array->amount;
                                                        if ($advance >= $due_amount) {
                                                            $advance = $advance - $due_amount;
                                                            $paid_amount = $due_amount;
                                                            $due_amount = 0;
                                                        }elseif ($advance < $due_amount) {
                                                            $due_amount = $due_amount - $advance;
                                                            $paid_amount = $advance;
                                                            $advance = 0;
                                                        }
                                                        $this->db->where(array('schoolID'=>$schoolID,'studentID'=>$student->studentID));
                                                        $this->db->update('advance_amount',array('amount'=>$advance,'modified_on'=>date("Y-m-d H:i:s")));
                                                    }
                                                    $this->db->insert('invoice_fee_head',array('schoolID'=>$schoolID,'session'=>$school_info->default_academic_year,'studentID'=>$student->studentID,'invoiceID'=>$invoiceID,'feehead'=>$feehead_dec->feehead,'feeheadID'=>$feehead_dec->feeheadID,'total_amount'=>$amount,'concession_type'=>$concession_type,'concession'=>$concession_amount,'payable_amount'=>$payable_amount,'paid_amount'=>$paid_amount,'due_amount'=>$due_amount,'status'=>'Y','due_date'=>$last_date,'added_on'=>date("Y-m-d H:i:s")));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                redirect(base_url("accounts/invoice"));
            }else{
                $this->data['classes'] = $this->accounts_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                $this->data['fee_type'] = $this->accounts_m->get_multiple_row('feetype',array('schoolID'=>$schoolID,'is_active'=>'Y'));
                $this->data['title'] = "Generate Invoices";
                $this->data['subview'] = "accounts/generate_invoices";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function invoice()
    {
        if ($_SESSION['role']=="school")
        {
            $schoolID = $_SESSION['schoolID'];
            $this->db->distinct('session');
            $this->db->select('session');
            $this->db->order_by('invoiceID','DESC');
            $this->data['session'] = $this->db->get_where('invoice',array('schoolID'=>$schoolID))->result();
            $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
            $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
            $this->data['classes'] = $this->accounts_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
            $this->data['fee_type'] = $this->accounts_m->get_multiple_row('feetype',array('schoolID'=>$schoolID,'is_active'=>'Y'));
            $this->data['title'] = "Invoices";
            $this->data['subview'] = "accounts/invoices";
            $this->load->view("layout",$this->data);
        }
    }
    public function get_section()
    {
        if ($_SESSION['role']=="school" && $_POST)
        {
            $schoolID = $_SESSION['schoolID'];
            $session = $this->input->post('session');
            $classID = $this->input->post('classID');
            $section = $this->db->get_where('section',array('classID'=>$classID , 'schoolID'=>$schoolID))->result();
            $this->db->distinct('monthyear');
            $this->db->select("monthyear,to_monthyear");
            $this->db->order_by('invoiceID','DESC');
            $period = $this->db->get_where('invoice',array('schoolID'=>$schoolID,'classID'=>$classID,'session'=>$session))->result();
            $res = array();
            foreach($period as $p){
                $res[] = array(
                    'date'=>$p->monthyear.'@'.$p->to_monthyear,
                    'value'=>date("M",strtotime($p->monthyear)).'-'.date("M",strtotime($p->to_monthyear))
                );
            }
            $response = array('result'=>'success','section'=>$section,'period'=>$res);
            echo json_encode($response);
        }
    }
    public function get_invoices()
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            $classID = $this->input->post('classID');
            $sectionID = $this->input->post('sectionID');
            $batchID = $this->input->post('batchID');
            $monthyear = explode('@',$this->input->post('monthyear'));
            $start = $monthyear['0'];
            $end = $monthyear['1'];
            if ($batchID != "null"){
                $invoices = $this->db->query("SELECT classes.class,section.section,invoice.invoiceID,invoice.session,student.name,student.roll_no,invoice.due_date,SUM(invoice_fee_head.total_amount) AS invoice_total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable_amount, SUM(invoice_fee_head.paid_amount) AS paid_amount, SUM(invoice_fee_head.due_amount) AS due_amount FROM `invoice` LEFT JOIN invoice_fee_head ON invoice.invoiceID = invoice_fee_head.invoiceID LEFT JOIN student ON invoice.studentID = student.studentID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID WHERE invoice.schoolID = '$schoolID' AND invoice.monthyear = '$start' AND invoice.to_monthyear = '$end' AND invoice.classID = '$classID' AND invoice.sectionID = '$sectionID' AND student.batchID = '$batchID' GROUP BY invoiceID ORDER BY student.roll_no")->result();
            }else{
                $invoices = $this->db->query("SELECT classes.class,section.section,invoice.invoiceID,invoice.session,student.name,student.roll_no,invoice.due_date,SUM(invoice_fee_head.total_amount) AS invoice_total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable_amount, SUM(invoice_fee_head.paid_amount) AS paid_amount, SUM(invoice_fee_head.due_amount) AS due_amount FROM `invoice` LEFT JOIN invoice_fee_head ON invoice.invoiceID = invoice_fee_head.invoiceID LEFT JOIN student ON invoice.studentID = student.studentID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID WHERE invoice.schoolID = '$schoolID' AND invoice.monthyear = '$start' AND invoice.to_monthyear = '$end' AND invoice.classID = '$classID' AND invoice.sectionID = '$sectionID' GROUP BY invoiceID ORDER BY student.roll_no")->result();
            }
            foreach($invoices as $i){
                $i->secret = md5($i->invoiceID);
            }
            echo json_encode($invoices);
        }
    }
    public function view_slip($param = "",$param2 = "")
    {
        if ($_SESSION['role'] == "school" && $param != "" && $param2 == md5($param)){
            $schoolID = $_SESSION['loginUserID'];
            $invoiceID = $param;
            $check = $this->db->get_where('invoice',array('invoiceID'=>$invoiceID,'schoolID'=>$schoolID))->row();
            if (count($check)){
                $this->data['invoice'] = $this->db->get_where('invoice',array('invoiceID'=>$invoiceID,'schoolID'=>$schoolID))->row();
                $this->data['logo'] = base_url("uploads/logo/").$_SESSION['logo'];
                $this->db->select('school_config.*,school_registration.address');
                $this->db->join('school_registration','school_config.schoolID = school_registration.schoolID','LEFT');
                $this->data['school_info'] = $this->db->get_where('school_config',array('school_config.schoolID'=>$schoolID))->row();
                $this->db->select("student.studentID,student.name,student.phone,student.roll_no,student.email,student.address,parent.name as parent,classes.class,section.section");
                $this->db->join('parent','student.parentID = parent.parentID','LEFT');
                $this->db->join('section','student.sectionID = section.sectionID','LEFT');
                $this->db->join('classes','student.classID = classes.classID','LEFT');
                $this->data['student_info'] = $this->db->get_where('student',array('student.studentID'=>$check->studentID))->row();
                $this->data['fee_heads'] = $this->db->get_where('invoice_fee_head',array('invoiceID'=>$invoiceID,'schoolID'=>$schoolID))->result();
                $this->data['title'] = "Invoice Slip";
                $this->data['subview'] = "accounts/invoice_slip";
                $this->load->view("layout",$this->data);
            }else{
                echo "Permission Denied";
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function payment()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            $this->db->select("batchID,CONCAT(start_year,'-',end_year) as batch");
            $this->data['batch'] = $this->db->get_where('batch',array('schoolID'=>$schoolID))->result();
            $this->data['classes'] = $this->accounts_m->get_multiple_row('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'));
            $this->data['title'] = "Payment";
            $this->data['subview'] = "accounts/payment_list_students";
            $this->load->view("layout",$this->data);
        }else{
            echo "Permission Denied";
        }
    }
    public function payment_list_students($param1="",$param2="",$param3="")
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
            //$select = "groupID,group_name";
            //$groups = $this->accounts_m->get_multiple_row('student_group',array('schoolID'=>$schoolID),$select);
            if ($param1 == "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 == "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID and student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 == "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 == "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
            }elseif ($param1 != "" && $param2 != "" && $param3 != "") {
                $table = "(select student.studentID, CONCAT(COALESCE(student.studentID,'0'),',',COALESCE(student.concessionID,'0'))as concession, student.name, COALESCE(student.batchID,'') AS batchID, CONCAT(COALESCE(batch.start_year,''),'-',COALESCE(batch.end_year,'')) AS batch, student.username, student.roll_no, student.phone, student.image, parent.name as parent, classes.class, section.section FROM student LEFT JOIN parent on student.parentID = parent.parentID LEFT JOIN batch on student.batchID = batch.batchID LEFT JOIN classes on student.classID = classes.classID LEFT JOIN section on student.sectionID = section.sectionID where student.schoolID = $schoolID AND student.batchID = $param3 AND student.classID = $param1 and student.sectionID = $param2 AND (student.is_active = 'Y' OR student.is_active = 'D'))as table1";
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
                array( 'db' => 'studentID',
                    'dt' => 8,
                    'formatter' => function( $d, $row){
                        $a = md5($d);
                        return "<a target='_blank' class='btn btn-success text-white' href='".base_url("accounts/list_invoices/$d/$a")."'>Invoices</a>";
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
    public function list_invoices_1($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == 'school'){
            if ($param1 != "" && $param2 != "" && md5($param1) == $param2){
                $studentID = $param1;
                $schoolID = $_SESSION['loginUserID'];
                if ($_POST){
                    $fine_concession = $this->input->post('fine_concession');
                    $userID = $studentID;
                    $role = "student";
                    $type = "invoice"; // invoice or subscription
                    $txn_no = "cash";
                    $amount = $this->input->post("cash");
                    $txn_status = "success";
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
                    $remaining_amount = $amount;
                    $total_fine = 0;
                    $total_due = 0;
                    $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                    $invoices = $this->db->query("SELECT invoice.invoiceID, invoice.schoolID, invoice.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.studentID = '$studentID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID ASC")->result();
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
                        if ($fine_concession > 0){
                            if($fine_concession >= $fine){
                                $fine_concession = $fine_concession - $fine;
                                $fine = 0;
                            }else{
                                $fine = $fine - $fine_concession;
                                $fine_concession = 0;
                            }
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
                                    echo "true<br>";
                                    var_dump($array2);
                                    //$this->db->insert('fine',$array2);
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
                    redirect(base_url("accounts/list_invoices/$param1/$param2"));
                }else{
                    $total_fine = 0;
                    $total_due = 0;
                    $this->db->select("student.studentID,student.name,classes.class,section.section,student.roll_no,parent.name as parent");
                    $this->db->join('section','student.sectionID = section.sectionID','LEFT');
                    $this->db->join('classes','student.classID = classes.classID','LEFT');
                    $this->db->join('parent','student.parentID = parent.parentID','LEFT');
                    $student_info = $this->db->get_where('student',array('student.studentID'=>$studentID,'student.schoolID'=>$schoolID))->row();
                    $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                    $invoices = $this->db->query("SELECT invoice_fee_head.invoiceID, invoice_fee_head.schoolID, invoice_fee_head.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.studentID = '$studentID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID DESC")->result();
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
                    $this->data['student'] = $student_info;
                    $this->data['total_fine'] = $total_fine;
                    $this->data['total_due'] = $total_due;
                    $this->data['invoices'] = $invoice_array;
                    $this->data['title'] = ucwords($student_info->name)." Invoices";
                    $this->data['subview'] = "accounts/student_wise_invoices";
                    $this->load->view("layout",$this->data);
                }
            }else{
                echo "Permission Denied";
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function list_invoices($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == 'school'){
            if ($param1 != "" && $param2 != "" && md5($param1) == $param2){
                $studentID = $param1;
                $schoolID = $_SESSION['loginUserID'];
                $total_fine = 0;
                $total_due = 0;
                $this->db->select("student.studentID,student.name,classes.class,section.section,student.roll_no,parent.name as parent");
                $this->db->join('section','student.sectionID = section.sectionID','LEFT');
                $this->db->join('classes','student.classID = classes.classID','LEFT');
                $this->db->join('parent','student.parentID = parent.parentID','LEFT');
                $student_info = $this->db->get_where('student',array('student.studentID'=>$studentID,'student.schoolID'=>$schoolID))->row();
                $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                $invoices = $this->db->query("SELECT invoice_fee_head.invoiceID, invoice_fee_head.schoolID, invoice_fee_head.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.studentID = '$studentID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID DESC")->result();
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
                $this->data['student'] = $student_info;
                $this->data['total_fine'] = $total_fine;
                $this->data['total_due'] = $total_due;
                $this->data['invoices'] = $invoice_array;
                $this->data['title'] = ucwords($student_info->name)." Invoices";
                $this->data['subview'] = "accounts/student_wise_invoices";
                $this->load->view("layout",$this->data);
            }else{
                echo "Permission Denied";
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function pay($param1 = "",$param2 = "")
    {
        if ($_SESSION['role'] == 'school'){
            if ($param1 != "" && $param2 == md5($param1)){
                $invoiceID = $param1;
                $invoice_info = $this->db->get_where('invoice',array('invoiceID'=>$invoiceID))->row();
                $studentID = $invoice_info->studentID;
                $schoolID = $_SESSION['schoolID'];
                if ($_POST){
                    $fine_concession = $this->input->post('fine_concession');
                    $userID = $studentID;
                    $role = "student";
                    $type = "invoice"; // invoice or subscription
                    $txn_no = "cash";
                    $amount = $this->input->post("cash");
                    $actual = $amount;
                    $advance = 0;
                    $payment_array = array();
                    $txn_status = "success";
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
                    $remaining_amount = $amount;
                    $total_fine = 0;
                    $total_due = 0;
                    $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                    $invoices = $this->db->query("SELECT invoice.invoiceID, invoice.schoolID, invoice.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.invoiceID = '$invoiceID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID ASC")->result();
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
                        if ($fine_concession > 0){
                            if($fine_concession >= $fine){
                                $fine_concession = $fine_concession - $fine;
                                $fine = 0;
                            }else{
                                $fine = $fine - $fine_concession;
                                $fine_concession = 0;
                            }
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
                                    $payment_array[] = array(
                                        'feeheadID'=>$fee->feeheadID,
                                        'feehead'=>$fee->feehead,
                                        'total_amount'=>$fee->total_amount,
                                        'concession'=>$fee->concession,
                                        'payable_amount'=>$fee->payable_amount,
                                        'paid_amount'=>$fee->paid_amount,
                                        'receive'=>$fee->due_amount
                                    );
                                }elseif ($remaining_amount < $fee->due_amount){
                                    $due_amount = $due_amount - $remaining_amount;
                                    $paid = $fee->paid_amount;
                                    $paid += $remaining_amount;
                                    $fee_due = $fee->due_amount - $remaining_amount;
                                    $payment = $remaining_amount;
                                    $remaining_amount = 0;
                                    $this->db->where(array('ID'=>$fee->ID));
                                    $this->db->update('invoice_fee_head',array('paid_amount'=>$paid,'due_amount'=>$fee_due));
                                    $payment_array[] = array(
                                        'feeheadID'=>$fee->feeheadID,
                                        'feehead'=>$fee->feehead,
                                        'total_amount'=>$fee->total_amount,
                                        'concession'=>$fee->concession,
                                        'payable_amount'=>$fee->payable_amount,
                                        'paid_amount'=>$fee->paid_amount,
                                        'receive'=>$payment
                                    );
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
                            $advance = $remaining_amount;
                            $this->db->insert('advance_amount',array('schoolID'=>$schoolID,'studentID'=>$studentID,'amount'=>$remaining_amount,'added_on'=>date("Y-m-d H:i:s")));
                        }
                    }
                    $paid_invoices_comma = implode(',',$paid_invoices);
                    $this->db->where(array('txnID'=>$insertID));
                    $this->db->update('transaction',array('invoices'=>$paid_invoices_comma));
                    $total_due = $total_due;
                    $total_fine = $total_fine;
                    $receipt = $this->webservice_m->pdf_receipt($insertID,$studentID,$schoolID,$actual,$advance,$payment_array);
                    $attachment = $receipt;
                    $student_info = $this->db->get_where('student',array('student.studentID'=>$studentID,'student.schoolID'=>$schoolID))->row();
                    /*if (!is_null($student_info->email) && $student_info->email != ""){
                        $this->send_email_attachment('Payment Receipt','Please find your Fee Receipt #'.$insertID, $student_info->email, $attachment);
                    }*/
                    $result = $this->send_email_attachment('Payment Receipt','Please find your Fee Receipt #'.$insertID, 'kshitij.singh@teknikoglobal.com', $attachment);
                    var_dump($result);
                    //redirect(base_url("invoices/$attachment"));
                    //redirect(base_url("accounts/pay/$param1/$param2"));
                }else{
                    $total_fine = 0;
                    $total_due = 0;
                    $this->db->select("student.studentID,student.name,classes.class,section.section,student.roll_no,parent.name as parent");
                    $this->db->join('section','student.sectionID = section.sectionID','LEFT');
                    $this->db->join('classes','student.classID = classes.classID','LEFT');
                    $this->db->join('parent','student.parentID = parent.parentID','LEFT');
                    $student_info = $this->db->get_where('student',array('student.studentID'=>$studentID,'student.schoolID'=>$schoolID))->row();
                    $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
                    $invoices = $this->db->query("SELECT invoice_fee_head.invoiceID, invoice_fee_head.schoolID, invoice_fee_head.studentID, invoice.`session`, invoice.monthyear, invoice.due_date, SUM(invoice_fee_head.total_amount) AS total, SUM(invoice_fee_head.concession) AS concession, SUM(invoice_fee_head.payable_amount) AS payable, SUM(invoice_fee_head.paid_amount) AS paid, SUM(invoice_fee_head.due_amount) AS due FROM invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID WHERE invoice.invoiceID = '$invoiceID' AND invoice.schoolID = '$schoolID' GROUP BY invoice_fee_head.invoiceID ORDER BY invoice.invoiceID DESC")->result();
                    $invoice_array = array();
                    $fee_head = $this->db->get_where('invoice_fee_head',array('invoiceID'=>$invoiceID))->result();
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
                    $this->data['student'] = $student_info;
                    $this->data['total_fine'] = $total_fine;
                    $this->data['total_due'] = $total_due;
                    $this->data['fee_head'] = $fee_head;
                    $this->data['invoices'] = $invoice_array;
                    $this->data['title'] = "Invoice    #".$invoiceID;
                    $this->data['subview'] = "accounts/id_wise_invoices";
                    $this->load->view("layout",$this->data);
                }
            }else{
                echo "Permission Denied";
            }
        }else{
            echo "Permission Denied";
        }
    }
    public function reports()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                $report_type = $this->input->post('report');
                $session = $this->input->post('session');
                redirect(base_url("accounts/$report_type/$session"));
            }else{
                $this->data['session'] = $this->db->query("SELECT DISTINCT(session) FROM invoice where schoolID ='$schoolID'")->result();
                $this->data['title'] = "Generate Reports";
                $this->data['subview'] = "accounts/report";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function class_wise_report($param1="")
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($param1 != ""){
                $this->data['report'] = $this->db->query("SELECT classes.class, section.section, SUM(invoice_fee_head.total_amount) as total_amount, SUM(invoice_fee_head.concession) as concession, SUM(invoice_fee_head.payable_amount) as payable_amount, SUM(invoice_fee_head.paid_amount) as paid_amount, SUM(invoice_fee_head.due_amount) as due_amount from invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID WHERE invoice_fee_head.schoolID = '$schoolID' AND invoice.session = '$param1' GROUP BY invoice.sectionID")->result();
            }else{
                $this->data['report'] = $this->db->query("SELECT classes.class, section.section, SUM(invoice_fee_head.total_amount) as total_amount, SUM(invoice_fee_head.concession) as concession, SUM(invoice_fee_head.payable_amount) as payable_amount, SUM(invoice_fee_head.paid_amount) as paid_amount, SUM(invoice_fee_head.due_amount) as due_amount from invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID WHERE invoice_fee_head.schoolID = '$schoolID' GROUP BY invoice.sectionID")->result();
            }
            $this->data['title'] = "Class Wise Report";
            $this->data['subview'] = "accounts/class_wise_report";
            $this->load->view("layout",$this->data);
        }
    }
    public function single_class($param1="")
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                $classID = $this->input->post('classID');
                $sectionID = $this->input->post('sectionID');
                if ($param1 != ""){
                    $this->data['report'] = $this->db->query("SELECT student.studentID, student.name, student.roll_no, classes.class, section.section, SUM(invoice_fee_head.total_amount) as total_amount, SUM(invoice_fee_head.concession) as concession, SUM(invoice_fee_head.payable_amount) as payable_amount, SUM(invoice_fee_head.paid_amount) as paid_amount, SUM(invoice_fee_head.due_amount) as due_amount from invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID LEFT JOIN student ON invoice_fee_head.studentID = student.studentID WHERE invoice_fee_head.schoolID = '$schoolID' AND invoice.session = '$param1' AND invoice.classID = '$classID' AND invoice.sectionID = '$sectionID' GROUP BY invoice_fee_head.studentID")->result();
                }else{
                    $this->data['report'] = $this->db->query("SELECT student.studentID, student.name, student.roll_no, classes.class, section.section, SUM(invoice_fee_head.total_amount) as total_amount, SUM(invoice_fee_head.concession) as concession, SUM(invoice_fee_head.payable_amount) as payable_amount, SUM(invoice_fee_head.paid_amount) as paid_amount, SUM(invoice_fee_head.due_amount) as due_amount from invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID LEFT JOIN student ON invoice_fee_head.studentID = student.studentID WHERE invoice_fee_head.schoolID = '$schoolID' AND invoice.classID = '$classID' AND invoice.sectionID = '$sectionID' GROUP BY invoice_fee_head.studentID")->result();
                }
                $this->data['title'] = "Single Class Wise Report";
                $this->data['subview'] = "accounts/single_class_report";
                $this->load->view("layout",$this->data);
            }else{
                $this->data['classes'] = $this->db->get_where('classes',array('schoolID'=>$schoolID))->result();
                $this->data['title'] = "Single Class Wise Report";
                $this->data['subview'] = "accounts/single_class";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function balance_report($param1="")
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                $classID = $this->input->post('classID');
                $sectionID = $this->input->post('sectionID');
                if ($param1 != ""){
                    $this->data['report'] = $this->db->query("SELECT student.studentID, student.name, student.roll_no, classes.class, section.section, SUM(invoice_fee_head.total_amount) as total_amount, SUM(invoice_fee_head.concession) as concession, SUM(invoice_fee_head.payable_amount) as payable_amount, SUM(invoice_fee_head.paid_amount) as paid_amount, SUM(invoice_fee_head.due_amount) as due_amount from invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID LEFT JOIN student ON invoice_fee_head.studentID = student.studentID WHERE invoice_fee_head.schoolID = '$schoolID' AND invoice.session = '$param1' AND invoice.classID = '$classID' AND invoice.sectionID = '$sectionID' AND invoice_fee_head.due_amount > 0 GROUP BY invoice_fee_head.studentID")->result();
                }else{
                    $this->data['report'] = $this->db->query("SELECT student.studentID, student.name, student.roll_no, classes.class, section.section, SUM(invoice_fee_head.total_amount) as total_amount, SUM(invoice_fee_head.concession) as concession, SUM(invoice_fee_head.payable_amount) as payable_amount, SUM(invoice_fee_head.paid_amount) as paid_amount, SUM(invoice_fee_head.due_amount) as due_amount from invoice_fee_head LEFT JOIN invoice ON invoice_fee_head.invoiceID = invoice.invoiceID LEFT JOIN classes ON invoice.classID = classes.classID LEFT JOIN section ON invoice.sectionID = section.sectionID LEFT JOIN student ON invoice_fee_head.studentID = student.studentID WHERE invoice_fee_head.schoolID = '$schoolID' AND invoice.classID = '$classID' AND invoice.sectionID = '$sectionID' AND invoice_fee_head.due_amount > 0 GROUP BY invoice_fee_head.studentID")->result();
                }
                $this->data['title'] = "Fee Balance Report";
                $this->data['subview'] = "accounts/balance_report";
                $this->load->view("layout",$this->data);
            }else{
                $this->data['classes'] = $this->db->get_where('classes',array('schoolID'=>$schoolID))->result();
                $this->data['title'] = "Fee Balance Report";
                $this->data['subview'] = "accounts/single_class";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function fee_head_wise()
    {
        if ($_SESSION['role'] == "school"){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                $feeheadID = $this->input->post('feeheadID');
                $fee_head = implode(",",$feeheadID);
                $to_date = date("Y-m-d",strtotime($this->input->post('date_to')));
                $from_date = date("Y-m-d",strtotime($this->input->post('date_from')));
                $this->data['fee_head'] = $this->db->query("SELECT * FROM `feehead` WHERE `feeheadID` IN ($fee_head)")->result();
                $this->data['report'] = $this->db->query("SELECT feehead.feehead AS feehead, SUM(`invoice_fee_head`.`total_amount`) AS total, SUM(`invoice_fee_head`.`concession`) AS concession, SUM(`invoice_fee_head`.`payable_amount`) AS payable, SUM(`invoice_fee_head`.`paid_amount`) AS paid, SUM(`invoice_fee_head`.`due_amount`) AS due FROM `invoice_fee_head` JOIN `feehead` ON invoice_fee_head.feeheadID = feehead.feeheadID  WHERE `invoice_fee_head`.`feeheadID` IN ($fee_head) AND `invoice_fee_head`.`status`= 'Y' AND `invoice_fee_head`.`schoolID`= '$schoolID' AND date(`invoice_fee_head`.added_on) BETWEEN '$from_date' AND '$to_date' GROUP BY `invoice_fee_head`.`feeheadID`")->result();
                $this->data['title'] = "Fee Head Wise Report  FROM ".date("d M Y",strtotime($from_date))." to ".date("d M Y",strtotime($to_date));
                $this->data['subview'] = "accounts/fee_head_wise_report";
                $this->load->view("layout",$this->data);
            }else{
                $this->data['select_fee_head'] = $this->db->get_where('feehead',array('schoolID'=>$schoolID))->result();
                $this->data['title'] = "Select Fee Heads And Date";
                $this->data['subview'] = "accounts/fee_head_wise";
                $this->load->view("layout",$this->data);
            }
        }
    }

    /* Expense */

    public function expense($param1 = "",$param2 = "")
    {
        if($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['schoolID'];
            if ($param1 == 'edit' && $param2 != ""){
                if ($_POST){
                    $array1 = array(
                        'schoolID'=>$schoolID,
                        'create_date'=>date("Y-m-d H:i:s"),
                        'date'=>date("Y-m-d",strtotime($this->input->post('date'))),
                        'expense'=>$this->input->post('expense'),
                        'amount'=>$this->input->post('amount'),
                        'userID'=>$_SESSION['loginUserID'],
                        'uname'=>$_SESSION['username'],
                        'usertype'=>$_SESSION['role'],
                        'expenseyear'=>date("Y",strtotime($this->input->post('date'))),
                        'note'=>$this->input->post('note')
                    );
                    $this->db->where(array('expenseID'=>$param2));
                    $this->db->update('expense',$array1);
                    redirect(base_url("accounts/expense"));
                }
            }elseif ($param1 == 'delete' && $param2 != ""){
                $this->db->where(array('expenseID'=>$param2));
                $this->db->delete('expense');
                redirect(base_url("accounts/expense"));
            }else{
                if ($_POST){
                    $array1 = array(
                        'schoolID'=>$schoolID,
                        'create_date'=>date("Y-m-d H:i:s"),
                        'date'=>date("Y-m-d",strtotime($this->input->post('date'))),
                        'expense'=>$this->input->post('expense'),
                        'amount'=>$this->input->post('amount'),
                        'userID'=>$_SESSION['loginUserID'],
                        'uname'=>$_SESSION['username'],
                        'usertype'=>$_SESSION['role'],
                        'expenseyear'=>date("Y",strtotime($this->input->post('date'))),
                        'note'=>$this->input->post('note')
                    );
                    $this->db->insert('expense',$array1);
                    redirect(base_url("accounts/expense"));
                }else{
                    $this->data['expense'] = $this->db->get_where('expense',array('schoolID'=>$schoolID))->result();
                    $this->data['title'] = "Expenses";
                    $this->data['subview'] = "accounts/expense";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
}
