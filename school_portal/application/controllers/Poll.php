<?php
/**
 * Created by PhpStorm.
 * User: kshit
 * Date: 2019-02-05
 * Time: 18:00:03
 */
class Poll extends MY_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model("poll_m");
        $this->load->model("fcm_m");
    }

    function index()
    {
        $usertype = $_SESSION['role'];
        if($usertype == 'school')
        {
            $schoolID = $_SESSION['loginUserID'];
            $this->db->order_by('pollID','desc');
            $this->data['polls']=$this->db->get_where('poll',array('schoolID'=>$schoolID))->result();
            $this->data['title'] = "POLLS";
            $this->data["subview"] = "poll/index";
            $this->load->view('layout', $this->data);
        }
    }
    function statistics($ques_type,$qid)
    {
        $usertype = $_SESSION['role'];
        if ($usertype == "school") {
            if($ques_type == 'option')
            {
                $results                = $this->poll_m->get_options_graph($qid);
                $this->data['option1']    = count($results['option1']);
                $this->data['option2']      = count($results['option2']);
                $this->data['option3']  = count($results['option3']);
                $this->data['option4'] = count($results['option4']);



                $this->data['year'] = date('Y');


                $this->data['title'] = "POLLS";
                $this->data["subview"] = "poll/option_statistics";

                $this->load->view('layout', $this->data);

            }

            if($ques_type == 'voting')
            {
                $results                = $this->poll_m->get_voting_graph($qid);
                $this->data['y']    = count($results['y']);
                $this->data['n']      = count($results['n']);
                $this->data['year'] = date('Y');
                $this->data['title'] = "POLLS";
                $this->data["subview"] = "poll/voting_statistics";
                $this->load->view('layout', $this->data);
            }

            if($ques_type == 'scale')
            {
                $this->data["scale"]= $this->poll_m->get_scale_graph($qid);
                $this->data["qid"] = $qid;
                $this->data['title'] = "POLLS";
                $this->data["subview"] = "poll/scale_statistics";
                $this->load->view('layout', $this->data);
        	}

        }

    }
    function delete($id)
    {
        $usertype = $_SESSION['role'];
        if ($usertype == 'school')
        {
            $schoolID = $_SESSION['loginUserID'];
            if((int)$id)
            {
                $this->db->where(array('pollID'=>$id,'schoolID'=>$schoolID));
                $this->db->delete('poll');
            }
            redirect(base_url('poll/index'));
        }
    }
    function view_opinion($id)
    {
        $usertype = $_SESSION['role'];
        if($usertype == 'school')
        {
            if((int)$id)
            {
                $this->data['title'] = "POLLS";
                $this->data['poll_id'] = $id;
                $this->data['poll'] = $this->poll_m->get_single_order_by_poll(array('pollID'=>$id));
                $this->data["opinion"] = $this->poll_m->view_opinion_id($id);
                $this->data["subview"] = "poll/view_opinion";
                $this->load->view('layout', $this->data);
            }
            else
            {
                redirect(base_url("poll/index"));
            }

        }
    }
    function add_option($qt,$id)
    {
        $check = $this->poll_m->get_options_result(array('pollID'=>$id));

        if($qt == 'option')
        {
            if(count($check)>0)
            {
                $this->data['options'] = $check;
                if($_POST)
                {
                    $array = array(
                        "pollID" =>$id,
                        "option1" => $this->input->post("option1"),
                        "option2" => $this->input->post("option2"),
                        "option3" => $this->input->post("option3"),
                        "option4" => $this->input->post("option4"),
                        "added_on" => date('Y-m-d h:i:s')
                    );

                    $this->poll_m->update_poll_options($array,array('pollID'=>$id));
                    redirect(base_url("poll/index"));
                }
                else
                {
                    $this->data['title'] = "OPTIONS";
                    $this->data["subview"] = "poll/edit_options";
                    $this->load->view('layout', $this->data);
                }
            }
            else
            {
                if($_POST)
                {
                    $array = array(
                        "pollID" =>$id,
                        "option1" => $this->input->post("option1"),
                        "option2" => $this->input->post("option2"),
                        "option3" => $this->input->post("option3"),
                        "option4" => $this->input->post("option4"),
                        "added_on" => date('Y-m-d h:i:s')
                    );

                    $this->poll_m->insert_poll_options($array);
                    redirect(base_url("poll/index"));
                }
                else
                {
                    $this->data['title'] = "OPTIONS";
                    $this->data["subview"] = "poll/options";
                    $this->load->view('layout', $this->data);
                }
            }

        }
        elseif($qt == 'scale')
        {
            if(count($check)>0)
            {
                $this->data['scale'] = $check;
                if($_POST)
                {
                    $array = array(
                        "pollID" =>$id,
                        "scale1" => $this->input->post("scale1"),
                        "scale2" => $this->input->post("scale2"),
                        "added_on" => date('Y-m-d h:i:s')
                    );
                    $this->poll_m->update_poll_options($array,array('pollID'=>$id));
                    redirect(base_url("poll/index"));
                }
                else
                {
                    $this->data['title'] = "Scale";
                    $this->data["subview"] = "poll/edit_scale";
                    $this->load->view('layout', $this->data);
                }
            }
            else
            {
                if($_POST)
                {
                    $array = array(
                        "pollID" =>$id,
                        "scale1" => $this->input->post("scale1"),
                        "scale2" => $this->input->post("scale2"),
                        "added_on" => date('Y-m-d h:i:s')
                    );

                    $this->poll_m->insert_poll_options($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("poll/index"));
                }
                else
                {
                    $this->data['title'] = "Scale";
                    $this->data["subview"] = "poll/scale";
                    $this->load->view('layout', $this->data);
                }
            }
        }
    }
    function add()
    {
        $usertype = $_SESSION['role'];
        if($usertype == 'school')
        {
            $schoolID = $_SESSION['loginUserID'];
            $this->data['classes']=$this->db->get_where('classes',array('schoolID'=>$schoolID,'is_active'=>'Y'))->result();
            if($_POST)
            {
                $array = array(
                    "schoolID"=>$schoolID,
                    "question" =>$this->input->post('question'),
                    "ques_type" => $this->input->post("ques_type"),
                    "start_date" => date("Y-m-d", strtotime($this->input->post("start_date"))),
                    "end_date" => date("Y-m-d", strtotime($this->input->post("end_date"))),
                    "teacher_allow" => $this->input->post("allteacher"),
                    "student_allow" => $this->input->post("allstudent"),
                    "class_allow" => implode(',', $this->input->post("classesID")),
                    "status" => $this->input->post("status"),
                    "added_on" => date('Y-m-d h:i:s')
                );

                $this->poll_m->insert_poll($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("poll/index"));
            }
            else
            {
                $this->data['title'] = "POLLS";
                $this->data["subview"] = "poll/add";
                $this->load->view('layout', $this->data);
            }

        }
        else
        {
            $this->data["subview"] = "error";
            $this->load->view('layout', $this->data);
        }
    }

    protected function rules1() {
        $rules = array(
            array(
                'field' => 'answer',
                'label' => $this->lang->line("answer"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            )
        );
        return $rules;
    }

    function answer()
    {
        $usertype = $this->session->userdata('usertype');
        if($usertype == "Student" || $usertype == "Teacher" || $usertype == "Parent")
        {
            $id = htmlentities(mysql_real_escape_string($this->uri->segment(3)));
            $this->data['poll_id'] = $id;
            $this->data['poll'] = $this->poll_m->get_single_order_by_poll(array('pollID'=>$id));
            $username = $this->session->userdata('username');
            $this->data['check_answer'] = $this->poll_m->get_que_ans($username,$usertype,$id);
            $ans_type = $this->input->post('qtype');
            if((int)$id)
            {
                if($_POST)
                {
                    if($ans_type == 'open')
                    {
                        $rules = $this->rules1();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data["subview"] = "poll/answer";
                            $this->load->view('layout', $this->data);
                        } else {

                            $array = array(
                                "pollID" => $id,
                                "usertype" => $usertype,
                                "username" => $username,
                                "answer" => $this->input->post('answer'),
                                "added_on" => date('Y-m-d H:i:s')
                            );

                            $this->db->insert('pollanswer',$array);
                            $this->session->set_flashdata('success','Successfully!!!!');
                            redirect(base_url('poll/answer/'.$id));

                        }

                    }
                    else if($ans_type == "voting")
                    {
                        $array = array(
                            "pollID" => $id,
                            "usertype" => $usertype,
                            "username" => $username,
                            "vote" => $this->input->post('answer'),
                            "added_on" => date('Y-m-d H:i:s')
                        );

                        $this->db->insert('pollanswer',$array);
                        $this->session->set_flashdata('success','Successfully!!!!');
                        redirect(base_url('poll/answer/'.$id));

                    }
                    else if($ans_type == 'option')
                    {
                        $rules = $this->rules1();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data["subview"] = "poll/answer";
                            $this->load->view('layout', $this->data);
                        } else {

                            $array = array(
                                "pollID" => $id,
                                "usertype" => $usertype,
                                "username" => $username,
                                "answer" => $this->input->post('answer'),
                                "added_on" => date('Y-m-d H:i:s')
                            );

                            $this->db->insert('pollanswer',$array);
                            $this->session->set_flashdata('success','Successfully!!!!');
                            redirect(base_url('poll/answer/'.$id));

                        }

                    }
                    else if($ans_type == "scale")
                    {
                        $array = array(
                            "pollID" => $id,
                            "usertype" => $usertype,
                            "username" => $username,
                            "answer" => $this->input->post('answer'),
                            "added_on" => date('Y-m-d H:i:s')
                        );


                        $this->db->insert('pollanswer',$array);
                        $this->session->set_flashdata('success','Successfully!!!!');
                        redirect(base_url('poll/answer/'.$id));

                    }

                }
                else
                {
                    $this->data["subview"] = "poll/answer";
                    $this->load->view('layout', $this->data);
                }
            }
            else
            {
                $this->data["subview"] = "error";
                $this->load->view('layout', $this->data);
            }


        }
    }

    function date_valid($date) {
        if(strlen($date) <10) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return FALSE;
        } else {
            $arr = explode("-", $date);
            $dd = $arr[0];
            $mm = $arr[1];
            $yyyy = $arr[2];
            if(checkdate($mm, $dd, $yyyy)) {
                return TRUE;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return FALSE;
            }
        }
    }

    function check()
    {
        if($this->input->post('options') == '' && ($this->input->post('option') || $this->input->post('scale')))
        {
            $this->form_validation->set_message("check", "%s field is required");
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    function edit()
    {
        $id = htmlentities(mysql_real_escape_string($this->uri->segment(3)));
        $this->data['poll'] = $this->poll_m->get_single_order_by_poll(array('pollID'=>$id));
        if((int)$id)
        {
            if($_POST)
            {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "poll/edit";
                    $this->load->view('layout', $this->data);
                } else {

                    $array = array(
                        "question" =>$this->input->post('question'),
                        "ques_type" => $this->input->post("ques_type"),
                        "start_date" => date("Y-m-d", strtotime($this->input->post("start_date"))),
                        "end_date" => date("Y-m-d", strtotime($this->input->post("end_date"))),
                        "status" => $this->input->post("status"),
                        "added_on" => date('Y-m-d h:i:s')
                    );

                    $this->poll_m->insert_poll($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("poll/index"));

                }
            }
            else
            {
                $this->data["subview"] = "poll/edit";
                $this->load->view('layout', $this->data);
            }

        }
        else
        {
            $this->data["subview"] = "error";
            $this->load->view('layout', $this->data);
        }
    }
}