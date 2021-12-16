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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Webservice_m extends CI_Model {

	function __construct() {
		parent::__construct();
	}


	public function table_insert($table,$array)
	{
		$query = $this->db->insert($table,$array);
		return $this->db->insert_id();
	}

	public function get_single_table($table,$array)
	{
		$query = $this->db->get_where($table,$array);
		return $query->row();
	}

	public function table_update($table,$array,$where)
	{
		$query = $this->db->where($where);
		$this->db->update($table,$array);
		return 1;
	}

	public function get_all_data($table,$order_by = NULL,$order="ASC")
	{
		if($order_by != NULL)
		{
			$this->db->order_by($order_by, $order);
		}
		
		$query = $this->db->get($table);
		return $query->result();
	}

	public function get_all_data_where($table,$where,$order_by = NULL,$order="ASC")
	{
		if($order_by != NULL)
		{
			$this->db->order_by($order_by, $order);
		}
		$query = $this->db->get_where($table,$where);
		return $query->result();
	}

	public function get_single_table_query($query)
	{
		$query = $this->db->query($query);
		return $query->row();
	}

	public function get_all_table_query($query)
	{
		$query = $this->db->query($query);
		return $query->result();
	}


	public function get_the_gallery($a,$b)
	{

		if($b == 'student' || $b == 'other' || $b == 'parent')
		{
			return $this->db->query("SELECT DISTINCT(`gallery`.`folderID`), `gallery_folder`.`folder_name` FROM `gallery` JOIN `gallery_folder` ON `gallery_folder`.`folderID`=`gallery`.`folderID` WHERE `gallery`.`folderID`!='0' AND `gallery`.`student`='Y' AND `gallery_folder`.`schoolID`='$a'")->result();
		}
		elseif($b == 'teacher')
		{
			return $this->db->query("SELECT DISTINCT(`gallery`.`folderID`), `gallery_folder`.`folder_name` FROM `gallery` JOIN `gallery_folder` ON `gallery_folder`.`folderID`=`gallery`.`folderID` WHERE `gallery`.`folderID`!='0' AND `gallery`.`teacher`='Y' AND `gallery_folder`.`schoolID`='$a'")->result();
		}elseif ($b == 'school')
        {
            return $this->db->query("SELECT DISTINCT(`gallery`.`folderID`), `gallery_folder`.`folder_name` FROM `gallery` JOIN `gallery_folder` ON `gallery_folder`.`folderID`=`gallery`.`folderID` WHERE `gallery`.`folderID`!='0' AND `gallery_folder`.`schoolID`='$a'")->result();
        }
		
	}

	public function gallery_images1($a,$b)
	{

		if($b == 'student' || $b == 'other' || $b == 'parent')
		{
			return $this->db->query("SELECT * FROM `gallery` WHERE `folderID` = '0' AND `student`='Y' AND `schoolID`='$a'")->result();
		}
		elseif($b == 'teacher')
		{
			return $this->db->query("SELECT * FROM `gallery` WHERE `folderID` = '0' AND `teacher`='Y' AND `schoolID`='$a'")->result();
		}
        elseif($b == 'school')
        {
            return $this->db->query("SELECT * FROM `gallery` WHERE `folderID` = '0' AND `schoolID`='$a'")->result();
        }
		
	}

	public function get_frames_topics()
	{
		$query = $this->db->get_where('frame_subjects',array('is_active'=>'Y'));
		return $query->result();
	}

	public function get_frames($schoolID,$subID)
	{
	    $query = $this->db->query("SELECT * FROM `frame_allowed` JOIN `frame` ON `frame`.`frameID`= `frame_allowed`.`frameID` JOIN `frame_subjects` ON `frame_subjects`.`subID` = `frame`.`subID` WHERE `frame_allowed`.`schoolID`='$schoolID' AND `frame`.`subID`='$subID'");
	    return $query->result();
	}

	public function event($array)
	{
		$query = $this->db->insert('event',$array);
		$id = $this->db->insert_id();
		return $id;
	}

	public function event_list($d,$schoolID)
	{
	    $query = $this->db->query("SELECT * FROM `event` WHERE `schoolID`='$schoolID' ORDER BY `start_on` desc");
	   return $query->result();
	    //return $this->db->last_query();
	}

	public function event_detail($eventID)
	{
		$query = $this->db->query("SELECT * FROM `event` WHERE `eventID` = '$eventID' ");
		return $query->row();
	}

	public function check_event($array)
	{
		$query = $this->db->get_where('event_activity',$array);
		return $query->row();
	}

	public function event_activity($array)
	{
	    $query = $this->db->insert('event_activity',$array);
	    $id = $this->db->insert_id();
	    return $id;
	}

	public function event_deleted($activityID)
	{
		$this->db-> where('activityID', $activityID);
	  	$this->db-> delete('event_activity');
	  	return TRUE;  
	}

	public function check_attendance($array)
	{
		$query = $this->db->get_where('student_attendance',$array);
		return $query->row();
	}

	public function update_attendance($array,$array1)
	{
		$this->db->where($array1);
		$this->db->update('student_attendance',$array);
		return TRUE;
	}

	public function insert_attendance($array)
	{
		$this->db->insert('student_attendance',$array);
	}

	public function get_students_class_wise($classID,$sectionID)
	{
		$query = $this->db->query("SELECT *,COALESCE(NULLIF(student.image, ''),'default.png') as image1 FROM `student` WHERE `classID`='$classID' AND `sectionID`='$sectionID'");
		return $query->result();
	}

	public function get_individual_attendance($array)
	{
		$query = $this->db->get_where('student_attendance',$array);
		//return $this->db->last_query();

		return $query->row(); 
	}

	public function pdf_receipt($txnID,$studentID,$schoolID,$payment,$advance,$payment_array)
    {
        require("assets/fpdf/kpdf.php");
        $this->db->select("student.name,student.roll_no,classes.class,section.section,parent.name as parent");
        $this->db->join('parent','student.parentID = parent.parentID','LEFT');
        $this->db->join('section','student.sectionID = section.sectionID','LEFT');
        $this->db->join('classes','student.classID = classes.classID','LEFT');
        $student_info = $this->db->get_where('student',array('student.studentID'=>$studentID))->row();
        $admin_info = $this->db->get_where('school_registration',array('schoolID'=>$schoolID))->row();
        $school_info = $this->db->get_where('school_config',array('schoolID'=>$schoolID))->row();
        $txn_info = $this->db->get_where('transaction',array('txnID'=>$txnID))->row();
        $invoices = explode(',',$txn_info->invoices);
        $inst = array();
        foreach ($invoices as $i){
            $info = $this->db->get_where('invoice',array('invoiceID'=>$i))->row();
            $inst[] = date("M",strtotime($info->monthyear)).'-'.date("M",strtotime($info->to_monthyear));
        }
        $installment = implode(',',$inst);
        //$installment = $txn_info->invoices;
        $pdf=new KPDF('P','mm','A4',$school_info,base_url('uploads/logo'),$admin_info);
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->Ln('10');
        $pdf->SetFont('Arial','B',14);
        // Title
        //$pdf->Ln(6);
        $pdf->Cell('65');
        $pdf->Cell('35','6','FEE RECEIPT','B','','C');
        $pdf->Cell('75','6','','','1');
        $pdf->Ln('4');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','RECEIPT No. : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('50','6','#'.$txnID,'0','','L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Payment Date : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('45','6',date("d-M-Y",strtotime($txn_info->added_on)),'0','1','L');
        $pdf->Ln('2');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Roll No. : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('50','6',$student_info->roll_no,'0','','L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Class-Section : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('45','6',$student_info->class." - ".$student_info->section,'0','1','L');
        $pdf->Ln('2');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Name : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('50','6',strtoupper($student_info->name),'0','','L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Installment : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('45','6',$installment,'0','1','L');
        $pdf->Ln('2');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Parent Name : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('50','6',strtoupper($student_info->parent),'0','','L');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell('35','6','Fee Type : ','0','','L');
        $pdf->SetFont('Arial','',12);
        $pdf->Cell('45','6','INVOICE','0','1','L');
        $pdf->Ln('8');
        $pdf->SetFont('Arial','B',10);
        $pdf->SetWidths(array(15,30,30,30,30,30));
        $pdf->Row(array('S.No.','Fee Head','Actual Amount','Concession','Last Paid','Paid Amount'));
        $pdf->SetFont('Arial','',10);
        $sn = 1;
        $total = 0;
        $concession = 0;
        $paid = 0;
        $receive = 0;
        foreach($payment_array as $p){
            $pdf->Row(array(
                $sn,
                $p['feehead'],
                number_format($p['total_amount'],2),
                number_format($p['concession'],2),
                number_format($p['paid_amount'],2),
                number_format($p['receive'],2)
            ));
            $sn += 1;
            $total += $p['total_amount'];
            $concession += $p['concession'];
            $paid += $p['paid_amount'];
            $receive += $p['receive'];
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->Row(array(
            '',
            'Total',
            number_format($total,2),
            number_format($concession,2),
            number_format($paid,2),
            number_format($receive,2)
        ));
        $pdf->Ln('2');
        $pdf->SetWidths(array(50,135));
        $pdf->Row2(array('Advance Amount : ',number_format($advance,2)),5);
        $pdf->Row2(array('Paid Amount : ',number_format($payment,2)),5);
        $pdf->Row2(array('Paid Amount(in words) : ',strtoupper($this->numberTowords($payment).' only')),5);
        $pdf->Output('F',"./invoices/".time().'.pdf');
        return time().'.pdf';
    }
    function numberTowords($num)
    {
        $ones = array(
            1 => "one",
            2 => "two",
            3 => "three",
            4 => "four",
            5 => "five",
            6 => "six",
            7 => "seven",
            8 => "eight",
            9 => "nine",
            10 => "ten",
            11 => "eleven",
            12 => "twelve",
            13 => "thirteen",
            14 => "fourteen",
            15 => "fifteen",
            16 => "sixteen",
            17 => "seventeen",
            18 => "eighteen",
            19 => "nineteen"
        );
        $tens = array(
            1 => "ten",
            2 => "twenty",
            3 => "thirty",
            4 => "forty",
            5 => "fifty",
            6 => "sixty",
            7 => "seventy",
            8 => "eighty",
            9 => "ninety"
        );
        $hundreds = array(
            "hundred",
            "thousand",
            "million",
            "billion",
            "trillion",
            "quadrillion"
        ); //limit t quadrillion
        $num = number_format($num,2,".",",");
        $num_arr = explode(".",$num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",",$wholenum));
        krsort($whole_arr);
        $rettxt = "";
        foreach($whole_arr as $key => $i){
            if($i < 20){
                $rettxt .= $ones[$i];
            }elseif($i < 100){
                $rettxt .= $tens[substr($i,0,1)];
                $rettxt .= " ".$ones[substr($i,1,1)];
            }else{
                $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                $rettxt .= " ".$tens[substr($i,1,1)];
                $rettxt .= " ".$ones[substr($i,2,1)];
            }
            if($key > 0){
                $rettxt .= " ".$hundreds[$key]." ";
            }
        }
        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $ones[$decnum];
            }elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
        }
        return $rettxt;
    }

}