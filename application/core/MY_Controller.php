<?php if (! defined('BASEPATH')) exit('No direct script access');

class MY_Controller extends CI_Controller {

function __construct() {
    parent::__construct();
    $this->_check_auth();
}

private function _check_auth(){
    if(!$this->session->userdata('name')){
        $this->session->sess_destroy();
        redirect(base_url());
    }
}

}