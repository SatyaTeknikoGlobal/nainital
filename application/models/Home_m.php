<?php
class Home_m extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function hash($string) {
        return parent::hash($string);
    }

    function get_all_row_where($table,$array,$select='*')
    {
        $this->db->select($select);
        return $this->db->get_where($table,$array)->result();
    }

    function get_single_row_where($table,$array,$select='*')
    {
        $this->db->select($select);
        return $this->db->get_where($table,$array)->row();
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

    function get_single_row($table,$select='*')
    {
        $this->db->select($select);
        return $this->db->get($table)->row();
    }

    function get_all_row_where_join ($table,$array,$join,$select='*')
    {
        $this->db->select($select);
        foreach($join as $j){
            $this->db->join($j['table'],$j['parameter'],$j['position']);
        }
        return $this->db->get_where($table,$array)->result();
    }
    function get_single_row_where_join ($table,$array,$join,$select='*')
    {
        $this->db->select($select);
        foreach($join as $j){
            $this->db->join($j['table'],$j['parameter'],$j['position']);
        }
        return $this->db->get_where($table,$array)->row();
    }
    function insert_data($table,$array)
    {
        $this->db->insert($table,$array);
        return $this->db->insert_id();
    }
    function update_data($table,$where,$values)
    {
        $this->db->where($where);
        $this->db->update($table,$values);
        return true;
    }
    function delete_data($table,$where)
    {
        $this->db->where($where);
        $this->db->delete($table);
        return true;
    }
    public function get_single_table($query)
    {
        $query = $this->db->query($query);
        return $query->row();
    }
}