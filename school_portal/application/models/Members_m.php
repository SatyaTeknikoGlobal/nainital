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

class Members_m extends MY_Model
{
    function __construct() {
        parent::__construct();
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
}