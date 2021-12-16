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

class Accounts_m extends MY_Model
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

    function get_feestructure($table,$array = NULL,$select ='*')
    {
        $this->db->select($select);
        $this->db->join('classes','feestructure.classID = classes.classID','LEFT');
        $this->db->join('feehead','feestructure.feeheadID = feehead.feeheadID','LEFT');
        return $this->db->get_where($table,$array)->result();
    }
}