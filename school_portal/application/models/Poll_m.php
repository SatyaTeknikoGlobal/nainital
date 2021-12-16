<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poll_m extends MY_Model {

	protected $_table_name = 'poll';
	protected $_primary_key = 'pollID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "pollID asc";

	function __construct() {
		parent::__construct();
	}

	function get_poll($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_poll($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_poll($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function get_single_order_by_poll($array=NULL) {
		$this->db->select('*');
		$this->db->from('poll');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->row();
	}

	function insert_poll_options($array)
	{
		$query = $this->db->insert('poll_options',$array);
		return TRUE;
	}

	function get_options_result($array)
	{
		$query = $this->db->get_where('poll_options', $array);
		return $query->row_array();
	}

	function update_poll_options($array,$where)
	{
		$this->db->where($where);
		$this->db->update('poll_options', $array);
		return TRUE;
	}

	function get_que_ans($username,$usertype,$id)
	{
		$this->db->select('*');
		$this->db->from('pollanswer');
		$this->db->where('pollID',$id);
		$this->db->where('usertype',$usertype);
		$this->db->where('username',$username);
		$query = $this->db->get();
		return $query->result();
	}

	function get_options($id)
	{
		$this->db->select('*');
		$this->db->from('poll_options');
		$this->db->where('pollID',$id);
		
		$query = $this->db->get();
		return $query->result();

	}

	function view_opinion($id,$username,$usertype)
	{
		$this->db->select('*');
		$this->db->from('pollanswer');
		//$this->db->where('pollID',$id);
		$this->db->where(array("pollID"=>$id,"username"=>$username, "usertype"=>$usertype));
		
		$query = $this->db->get();
		return $query->result();

	}
    function view_opinion_id($id)
    {
        $this->db->select('*');
        $this->db->from('pollanswer');
        //$this->db->where('pollID',$id);
        $this->db->where(array("pollID"=>$id));

        $query = $this->db->get();
        return $query->result();

    }
    
     function get_options_graph($qid)
	 {
	 	$this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('answer','1');
        $query = $this->db->get();
        $results['option1'] = $query->result();
	 	$this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('answer','2');
        $query = $this->db->get();
        $results['option2'] = $query->result();
        $this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('answer','3');
        $query = $this->db->get();
        $results['option3'] = $query->result();
        $this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('answer','4');
        $query = $this->db->get();
        $results['option4'] = $query->result();
        
        return $results;
     }


      function get_voting_graph($qid)
	 {
	 	$this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('vote','Y');
        $query = $this->db->get();
        $results['y'] = $query->result();
	 	$this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('vote','N');
        $query = $this->db->get();
        $results['n'] = $query->result();
      
        
        return $results;
     }

     function get_scale_graph($qid)
     {
     	$this->db->select('poll_options.scale1,poll_options.scale2');
        $this->db->from('poll');
        $this->db->join('poll_options','poll.pollID=poll_options.pollID');
        $this->db->where('poll.pollID',$qid);
        $query = $this->db->get();
        return $query->result();

     }

     public function get_ans_vote($qid,$scale)
     {
     	$this->db->select('*');
        $this->db->from('pollanswer');
        $this->db->where('pollID',$qid);
        $this->db->where('answer',$scale);
        $query = $this->db->get();
        return $query->result();
     }


     public function get_poll_answer($array)
     {
     	$query = $this->db->insert('pollanswer',$array);
     	return TRUE;
     }

}