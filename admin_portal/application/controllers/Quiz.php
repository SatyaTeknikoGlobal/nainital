<?php
/**
 * Created by PhpStorm.
 * User: kshit
 * Date: 2019-02-19
 * Time: 11:35:08
 */

class Quiz extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->library('csvimport');
    }

    public function index($param1 = '',$param2 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            if ($param1 == 'edit' && $param2 != '')
            {
                $classID = $param2;
                if ($_POST)
                {
                    $array2 = array('class'=>$this->input->post('class'),
                        'is_active'=>$this->input->post('is_active')
                    );
                    $this->db->where(array('classid'=>$classID));
                    $this->db->update('admin_class',$array2);
                    redirect(base_url('quiz/index'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array('class'=>$this->input->post('class'),
                        'is_active'=>$this->input->post('is_active'),
                        'added_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('admin_class',$array2);
                    redirect(base_url('quiz/index'));
                }else{
                    $this->data['classes'] = $this->db->get('admin_class')->result();
                    $this->data['title'] = "Quiz Classes";
                    $this->data['subview'] = "quiz/classes";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            echo "Permission Denied";
        }
    }

    public function subject($param1 = '',$param2 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            if ($param1 == 'edit' && $param2 != '')
            {
                $classID = $param2;
                if ($_POST)
                {
                    $array2 = array('subject'=>$this->input->post('subject'),
                        'is_active'=>$this->input->post('is_active')
                    );
                    $this->db->where(array('subid'=>$classID));
                    $this->db->update('admin_subject',$array2);
                    redirect(base_url('quiz/subject'));
                }
            }else{
                if ($_POST)
                {
                    $array2 = array('subject'=>$this->input->post('subject'),
                        'is_active'=>$this->input->post('is_active'),
                        'added_on' => date("Y-m-d H:i:s")
                    );
                    $this->db->insert('admin_subject',$array2);
                    redirect(base_url('quiz/subject'));
                }else{
                    $this->data['subject'] = $this->db->get('admin_subject')->result();
                    $this->data['title'] = "Quiz Subject";
                    $this->data['subview'] = "quiz/subject";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            echo "Permission Denied";
        }
    }

    public function topics($param1 = '',$param2 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            if ($param1 == 'edit' && $param2 != '')
            {
                $classID = $param2;
                if ($_POST)
                {
                    $array2 = array('subjectid'=>$this->input->post('subjectid'),
                        'classid'=>$this->input->post('classid'),
                        'Level_name'=>$this->input->post('Level_name')
                    );
                    $this->db->where(array('Level_id'=>$classID));
                    $this->db->update('quiz_level',$array2);
                    redirect(base_url('quiz/topics'));
                }
            }elseif($param1 == 'delete' && $param2 != ''){
                $this->db->where(array('Level_id'=>$param2));
                $this->db->delete('quiz_level');
                redirect(base_url('quiz/topics'));
            }else{
                if ($_POST)
                {
                    $array2 = array('subjectid'=>$this->input->post('subjectid'),
                        'classid'=>$this->input->post('classid'),
                        'Level_name'=>$this->input->post('Level_name')
                    );
                    $this->db->insert('quiz_level',$array2);
                    redirect(base_url('quiz/topics'));
                }else{
                    $this->data['classes'] = $this->db->get('admin_class')->result();
                    $this->data['subject'] = $this->db->get('admin_subject')->result();
                    $this->db->select('quiz_level.Level_id,quiz_level.Level_name,quiz_level.classid,quiz_level.subjectid,admin_class.class,admin_subject.subject');
                    $this->db->join('admin_class','quiz_level.classid = admin_class.classid','LEFT');
                    $this->db->join('admin_subject','quiz_level.subjectid = admin_subject.subID','LEFT');
                    $this->db->order_by('quiz_level.Level_id','DESC');
                    $this->data['topics'] = $this->db->get('quiz_level')->result();
                    $this->data['title'] = "Quiz Topics";
                    $this->data['subview'] = "quiz/topics";
                    $this->load->view("layout",$this->data);
                }
            }
        }else{
            echo "Permission Denied";
        }
    }

    public function quiz_list($param1 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            if ($param1 != ''){
                $this->data['quiz_level'] = $param1;
                $this->data['quiz'] = $this->db->get_where('quiz_bank',array('quiz_level'=>$param1))->result();
                $this->data['title'] = "Quiz List";
                $this->data['subview'] = "quiz/list";
                $this->load->view("layout",$this->data);
            }
        }
    }

    public function edit_question($param1 = '',$param2 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            if ($param1 != '' && $param2 != ''){
                $array2 = array(
                    'Question'=>$this->input->post('Question'),
                    'OptionA'=>$this->input->post('OptionA'),
                    'OptionB'=>$this->input->post('OptionB'),
                    'OptionC'=>$this->input->post('OptionC'),
                    'OptionD'=>$this->input->post('OptionD'),
                    'RightAns'=>$this->input->post('RightAns'),
                    'marks'=>$this->input->post('marks'),
                    'explanation'=>$this->input->post('explanation')
                );
                $this->db->where(array('Que_ID'=>$param1));
                $this->db->update('quiz_bank',$array2);
                redirect(base_url("quiz/quiz_list/$param2"));
            }
        }
    }

    public function add_question($param1 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            $array2 = array(
                'quiz_level'=>$param1,
                'Question'=>$this->input->post('Question'),
                'OptionA'=>$this->input->post('OptionA'),
                'OptionB'=>$this->input->post('OptionB'),
                'OptionC'=>$this->input->post('OptionC'),
                'OptionD'=>$this->input->post('OptionD'),
                'RightAns'=>$this->input->post('RightAns'),
                'marks'=>$this->input->post('marks'),
                'explanation'=>$this->input->post('explanation')
            );
            $this->db->insert('quiz_bank',$array2);
            redirect(base_url("quiz/quiz_list/$param1"));
        }
    }

    public function bulk_add_question($param1 = '')
    {
        if (strtolower($_SESSION['role']) == "admin" && $param1 != "") {
            if ($_FILES){
                $target_path = "uploads/"; // replace this with the path you are going to save the file to
                $target_dir = "uploads/";
                if(is_array($_FILES))
                {
                    foreach($_FILES as $fileKey => $fileVal)
                    {
                        $imagename = basename($_FILES["imported_file"]["name"]);
                        $extension = substr(strrchr($_FILES['imported_file']['name'], '.'), 1);
                        if (strtolower($extension == "csv")){
                            $actual_image_name = "quiz".time().".".$extension;
                            move_uploaded_file($_FILES["imported_file"]["tmp_name"],$target_path.$actual_image_name);
                            $file_path =  $target_path.$actual_image_name;
                            if ($this->csvimport->get_array($file_path)) {
                                $csv_array = $this->csvimport->get_array($file_path);
                                $unupload[]=array('Question','OptionA','OptionB','OptionC','OptionD','RightAns','marks','explanation','remark');
                                foreach ($csv_array as $row) {
                                    $check_duplicate = $this->db->get_where('quiz_bank',array('Question'=>$row["Question"]))->row();
                                    if (count($check_duplicate))
                                    {
                                        $unupload[] = array(
                                            "Question" => $row["Question"],
                                            "OptionA" => $row["OptionA"],
                                            "OptionB" => $row["OptionB"],
                                            "OptionC" => $row["OptionC"],
                                            "OptionD" => $row["OptionD"],
                                            "RightAns" => $row["RightAns"],
                                            "marks" => $row["marks"],
                                            "explanation" => $row["explanation"],
                                            "remark" => "Already Exist"
                                        );
                                    }else
                                    {
                                        $array = array(
                                            "Question" => $row["Question"],
                                            "quiz_level"=> $param1,
                                            "OptionA" => $row["OptionA"],
                                            "OptionB" => $row["OptionB"],
                                            "OptionC" => $row["OptionC"],
                                            "OptionD" => $row["OptionD"],
                                            "RightAns" => $row["RightAns"],
                                            "marks" => $row["marks"],
                                            "explanation" => $row["explanation"]
                                        );
                                        $this->db->insert('quiz_bank',$array);
                                    }


                                }
                                $filename = "Unuploaded Questions";
                                //$csv = $this->export_csv($unuploaded_report,$filename);
                                header("Content-type: application/csv");
                                header("Content-Disposition: attachment; filename=\"$filename".".csv\"");
                                header("Pragma: no-cache");
                                header("Expires: 0");

                                $handle = fopen('php://output', 'w');

                                foreach ($unupload as $un) {
                                    fputcsv($handle, $un);
                                }

                                fclose($handle);
                                /*$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                                redirect(base_url("user/customer"));*/

                            } else{
                                echo "Something went wrong. Please try again later.";
                            }
                        }else{
                            echo "Please Upload a valid file";
                        }

                    }
                }
            }else{
                echo "Please Upload a file";
            }
        }
    }

    public function delete_question($param1 = '',$param2 = '')
    {
        if (strtolower($_SESSION['role']) == "admin") {
            $this->db->where(array('Que_ID'=>$param1));
            $this->db->update('quiz_bank');
            redirect(base_url("quiz/quiz_list/$param2"));
        }
    }

}