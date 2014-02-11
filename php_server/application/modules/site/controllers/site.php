<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Site extends CI_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->model('site_m');
    }

    function index() {
        $data['main_content'] = 'home';
        $this->load->view('includes/template', $data);
    }

    function members() {
        redirect('members');
    }
    
    function features() {
        $data['main_content'] = 'features';
        $this->load->view('includes/template', $data);
    }

    function contact() {
        $data['main_content'] = 'contact';
        $this->load->view('includes/template', $data);
    }

    function settings() {
        $data['main_content'] = 'user/settings';
        $this->load->view('includes/template', $data);
    }

    function register() {
        $data['main_content'] = 'user/signup_form';
        $this->load->view('includes/template', $data);
    }

}
