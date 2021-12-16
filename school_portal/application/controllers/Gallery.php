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

class Gallery extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("signin_m");
        $this->load->model("configuration_m");
        $this->load->library('SSP');
        $this->load->model("fcm_m");
    }
    public function index($param1 = "",$param2 = "")
    {
        if (strtolower($_SESSION['role']) == "school")
        {
            $schoolID = $_SESSION['loginUserID'];
            if($param1 == "edit" && $param2 != "")
            {
                $array = array('folder_name'=>$this->input->post('folder_name'),'is_active'=>$this->input->post('is_active'));
                $this->db->where(array('schoolID'=>$schoolID,'folderID'=>$param2));
                $this->db->update('gallery_folder',$array);
                redirect(base_url("gallery/index"));
            }else{
                if($_POST)
                {
                    $array = array('schoolID'=>$schoolID,'folder_name'=>$this->input->post('folder_name'),'is_active'=>$this->input->post('is_active'));
                    $this->db->insert('gallery_folder',$array);
                    redirect(base_url("gallery/index"));
                }else{
                    $array = array('schoolID'=>$schoolID);
                    $this->data['gallery_folder'] = $this->configuration_m->get_multiple_row('gallery_folder',$array);
                    $this->data['title'] = "Gallery";
                    $this->data['subview'] = "gallery/folders";
                    $this->load->view("layout",$this->data);
                }
            }
        }
    }
    public function images($param = "")
    {
        if (strtolower($_SESSION['role']) == "school" && $param != ""){
            $path = base_url('uploads/gallery/');
            $schoolID = $_SESSION['loginUserID'];
            $folder = $this->db->get_where('gallery_folder',array('schoolID'=>$schoolID,'folderID'=>$param))->row();
            $this->db->select("galleryID,folderID,student,teacher,CONCAT('".$path."',COALESCE(NULLIF(image, ''),'default.png')) as image");
            $this->data['images'] = $this->db->get_where('gallery',array('schoolID'=>$schoolID,'folderID'=>$param))->result();
            $this->data['folderID']=$param;
            $this->data['title'] = $folder->folder_name;
            $this->data['title1'] = "<a class='btn btn-primary' href='".base_url('gallery/add_images/').$param."'><i class='fa fa-plus'></i>&nbsp;ADD Images</a>";
            $this->data['subview'] = "gallery/images";
            $this->load->view("layout",$this->data);
        }
    }
    public function add_images($param = "")
    {
        if (strtolower($_SESSION['role']) == "school" && $param != ""){
            $schoolID = $_SESSION['loginUserID'];
            if ($_POST){
                if (array_key_exists('student',$_POST)){
                    $student = "Y";
                }else{
                    $student = "N";
                }
                if (array_key_exists('teacher',$_POST)){
                    $teacher = "Y";
                }else{
                    $teacher = "N";
                }
                $target_path = "uploads/gallery/"; // replace this with the path you are going to save the file to
                $target_dir = "uploads/gallery/";
                foreach($_FILES["images"]["name"] as $k=>$v)
                {
                    $imagename = basename($_FILES["images"]["name"][$k]);
                    $extension = substr(strrchr($_FILES['images']['name'][$k], '.'), 1);
                    if ($extension == "jpg" || $extension == "jpeg" || $extension == "png"){
                        $actual_image_name = "gallery".time().$k.".".$extension;
                        move_uploaded_file($_FILES["images"]["tmp_name"][$k],$target_path.$actual_image_name);
                        $this->db->insert('gallery',array('schoolID'=>$schoolID,'folderID'=>$param,'student'=>$student,'teacher'=>$teacher,'image'=>$actual_image_name,'added_on'=>date("Y-m-d H:i:s")));
                    }

                }
                redirect(base_url("gallery/images/$param"));
            }else{
                $folder = $this->db->get_where('gallery_folder',array('schoolID'=>$schoolID,'folderID'=>$param))->row();
                $this->data['folderID'] = $param;
                $this->data['title'] = "Add image to ".$folder->folder_name;
                $this->data['subview'] = "gallery/add_image";
                $this->load->view("layout",$this->data);
            }
        }
    }
    public function delete_image($param="",$param1="")
    {
        if (strtolower($_SESSION['role']) == "school" && $param != ""){
            $gallery_info = $this->db->get_where('gallery',array('galleryID'=>$param))->row();
            if (count($gallery_info)){
                $this->db->where(array('galleryID'=>$param));
                $this->db->delete('gallery');
                unlink("uploads/gallery/$gallery_info->image");
                redirect(base_url("gallery/images/$param1"));
            }
        }
    }
}