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

class Configuration_m extends MY_Model
{
    function __construct() {
        parent::__construct();
    }

    function hash($string) {
        return parent::hash($string);
    }

    function get_single_row($table,$array = NULL,$select ='*')
    {
        $this->db->select($select);
        return $this->db->get_where($table,$array)->row();
    }

    function get_multiple_row($table,$array = NULL,$select ='*')
    {
        $this->db->select($select);
        return $this->db->get_where($table,$array)->result();
    }

    function get_section($array1)
    {
        $this->db->select('section.*,classes.class,teacher.name');
        $this->db->join('classes','section.classID = classes.classID','LEFT');
        $this->db->join('teacher','section.class_teacherID = teacher.teacherID','LEFT');
        return $this->db->get_where('section',$array1)->result();
    }

    function get_slots($array1)
    {
        $this->db->select('slots.*,classes.class');
        $this->db->join('classes','slots.classID = classes.classID','LEFT');
        return $this->db->get_where('slots',$array1)->result();
    }

    function get_students_atten($table,$array)
    {
        $this->db->select('student.studentID,student.roll_no,student.name,student.username,student.image,student.phone,student.email,parent.name as parent');
        $this->db->join('parent','student.parentID = parent.parentID','LEFT');
        $query = $this->db->get_where($table,$array)->result();
        return $query;
    }
}