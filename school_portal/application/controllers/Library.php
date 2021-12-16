<?php
/**
 * Created by PhpStorm.
 * User: kshit
 * Date: 2019-02-05
 * Time: 18:00:03
 */

class Library extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('SSP');
        $this->load->model("fcm_m");
    }
    public function index($param1 = "", $param2 = "")
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($param1 == "edit" && $param2 != "")
            {
                $array = array('category'=>$this->input->post('category'),'is_active'=>$this->input->post('is_active'));
                $this->db->where(array('schoolID'=>$schoolID,'bookcatID'=>$param2));
                $this->db->update('book_category',$array);
                redirect(base_url("library/index"));
            }else{
                if($_POST)
                {
                    $array = array('schoolID'=>$schoolID,'category'=>$this->input->post('category'),'is_active'=>$this->input->post('is_active'),'added_on'=>date("Y-m-d H:i:s"));
                    $this->db->insert('book_category',$array);
                    redirect(base_url("library/index"));
                }else{
                    $array = array('schoolID'=>$schoolID);
                    $this->data['category'] = $this->db->get_where('book_category',$array)->result();
                    $this->data['title'] = "Books Category";
                    $this->data['subview'] = "library/category";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function books($param1 = "", $param2 = "", $param3 = "")
    {
        if ($_SESSION['role'] == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($param1 == "edit" && $param2 != "" && $param3 != "")
            {
                if ($param3 == md5($param2)){
                    if ($_POST){
                        $array = array('category'=>$this->input->post('category'),'is_active'=>$this->input->post('is_active'));
                        $this->db->where(array('schoolID'=>$schoolID,'bookcatID'=>$param2));
                        $this->db->update('book_category',$array);
                        redirect(base_url("library/books"));
                    }else{

                    }
                }else{
                    redirect(base_url("library/books"));
                }
            }else{
                if($_POST)
                {
                    $array = array('schoolID'=>$schoolID,'category'=>$this->input->post('category'),'is_active'=>$this->input->post('is_active'),'added_on'=>date("Y-m-d H:i:s"));
                    $this->db->insert('book_category',$array);
                    redirect(base_url("library/index"));
                }else{
                    $array = array('schoolID'=>$schoolID);
                    $this->data['category'] = $this->db->get_where('book_category',$array)->result();
                    $this->data['classes'] = $this->db->get_where('classes',array('schoolID'=>$schoolID))->result();
                    $this->data['title'] = "Books";
                    $this->data['subview'] = "library/book_list";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function get_subject()
    {
        if ($_POST){
            $classID = $this->input->post('classID');
            $this->db->select("subjectID,subject_name,subject_code");
            $subject = $this->db->get_where('subject',array('classID'=>$classID))->result();
            echo json_encode($subject);
        }
    }
    public function book_list($param1="",$param2="",$param3="")
    {
        if (strtolower($_SESSION['role']) == 'school'){
            $schoolID = $_SESSION['schoolID'];
            if ($param1 == "" && $param2 == "" && $param3 == ""){
                $table = "(select books.*,CONCAT(subject.subject_name,' ( ',subject.subject_code,' ) ') AS subject,book_category.category  FROM books LEFT JOIN book_category ON books.bookcatID = book_category.bookcatID LEFT JOIN subject ON books.subjectID = subject.subjectID where books.schoolID = $schoolID)as table1";
                //echo "$table";
            }elseif($param1 != "0" && $param2 == "0" && $param3 == "0"){
                $classID = $param1;
                $this->db->select("subjectID");
                $sub = $this->db->get_where('subject',array('classID'=>$classID))->result();
                $subject = array();
                foreach ($sub as $s){
                    array_push($subject,$s->subjectID);
                }
                $subjects = implode(',',$subject);
                if ($subjects == ""){
                    $subjects = "0";
                }
                $table = "(select books.*,CONCAT(subject.subject_name,' ( ',subject.subject_code,' ) ') AS subject,book_category.category  FROM books LEFT JOIN book_category ON books.bookcatID = book_category.bookcatID LEFT JOIN subject ON books.subjectID = subject.subjectID where books.schoolID = $schoolID AND books.subjectID IN ($subjects))as table1";
            }elseif ($param2 != "0" && $param3 == "0"){
                $subjects = "$param2";
                $table = "(select books.*,CONCAT(subject.subject_name,' ( ',subject.subject_code,' ) ') AS subject,book_category.category  FROM books LEFT JOIN book_category ON books.bookcatID = book_category.bookcatID LEFT JOIN subject ON books.subjectID = subject.subjectID where books.schoolID = $schoolID AND books.subjectID IN ($subjects))as table1";
            }elseif ($param1 != "0" && $param2 == "0" && $param3 != "0"){
                $categoryID = $param3;
                $classID = $param1;
                $this->db->select("subjectID");
                $sub = $this->db->get_where('subject',array('classID'=>$classID))->result();
                $subject = array();
                foreach ($sub as $s){
                    array_push($subject,$s->subjectID);
                }
                $subjects = implode(',',$subject);
                if ($subjects == ""){
                    $subjects = "0";
                }
                $table = "(select books.*,CONCAT(subject.subject_name,' ( ',subject.subject_code,' ) ') AS subject,book_category.category  FROM books LEFT JOIN book_category ON books.bookcatID = book_category.bookcatID LEFT JOIN subject ON books.subjectID = subject.subjectID where books.schoolID = $schoolID AND books.bookcatID = $categoryID AND books.subjectID IN ($subjects))as table1";
            }elseif ($param2 != "0" && $param3 != "0"){
                $categoryID = $param3;
                $subjects = "$param2";
                $table = "(select books.*,CONCAT(subject.subject_name,' ( ',subject.subject_code,' ) ') AS subject,book_category.category  FROM books LEFT JOIN book_category ON books.bookcatID = book_category.bookcatID LEFT JOIN subject ON books.subjectID = subject.subjectID where books.schoolID = $schoolID AND books.bookcatID = $categoryID AND books.subjectID IN ($subjects))as table1";
            }elseif ($param1 == "0" && $param2 == "0" && $param3 != "0"){
                $categoryID = $param3;
                $table = "(select books.*,CONCAT(subject.subject_name,' ( ',subject.subject_code,' ) ') AS subject,book_category.category  FROM books LEFT JOIN book_category ON books.bookcatID = book_category.bookcatID LEFT JOIN subject ON books.subjectID = subject.subjectID where books.schoolID = $schoolID AND books.bookcatID = $categoryID)as table1";
            }
            $primaryKey = 'bookID';
            $columns = array(
                array( 'db' => 'book','dt' => 0),
                array( 'db' => 'subject','dt' => 1 ),
                array( 'db' => 'category','dt' => 2 ),
                array( 'db' => 'isbn_number','dt' => 3 ),
                array( 'db' => 'author', 'dt' => 4 ),
                array( 'db' => 'price', 'dt' => 5 ),
                array( 'db' => 'quantity', 'dt' => 6 ),
                array( 'db' => 'due_quantity', 'dt' => 7 ),
                array( 'db' => 'rack', 'dt' => 8 ),
                array( 'db' => 'bookID', 'dt' => 9,
                    'formatter'=> function( $d, $row ) {
                        return "<a href=".base_url('library/books/edit').'/'.$d.'/'.md5($d)." class = 'btn btn-success' target='_blank'>Edit</a>";
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

}