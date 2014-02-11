<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('api_m');
        $this->load->model('gcm_m');
        $this->load->model('user_m');
    }
    
    function index(){
        $data['users'] = $this->user_m->getU();
        
        $data['main_content'] = 'panel';
	$this->load->view('includes/template', $data);
    }

    function register() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('regId', 'Registration ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $name = $this->input->post("name");
            $email = $this->input->post("email");
            $regId = $this->input->post("regId");
            
            echo $this->user_m->storeUser($name, $email, $regId);
        }
    }
    
    function send_message(){
        $phone = $this->input->post('phones');
        
        var_dump($this->input->post()); die();
        
        $p = explode('_', $phone);
        echo end($p);
        die();
        
        $this->gcm_m->send_message($regId, $type, $msg);
    }

}
