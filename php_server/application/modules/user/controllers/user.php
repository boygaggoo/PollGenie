<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    // ==========================================
    // Login & Logout 
    // ==========================================

    function index($error = null) {
        if ($this->user_model->login_check())
            redirect('site/members');

        if (isset($error)) {
            $data['error'] = $error;
        }
        $data['main_content'] = 'login_form';
        $this->load->view('includes/template', $data);
    }

    function login($error = null) {
        if ($this->user_model->login_check())
            redirect('site/members');

        if (isset($error)) {
            $data['error'] = $error;
        }
        $data['main_content'] = 'login_form';
        $this->load->view('includes/template', $data);
    }

    function logout() {
        $this->session->sess_destroy();
        redirect('site');
    }

    // ==========================================
    // Signup accounts
    // ==========================================

    function signup() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $data['main_content'] = 'signup_form';
        $this->load->view('includes/template', $data);
    }

    // ==========================================
    // Validation checkers
    // ==========================================

    function validate_credentials() {
        $query = $this->user_model->validate();

        // If users credentials validated
        if ($query) {
            $data = array(
                'email' => $this->input->post('email'),
                'is_logged_in' => true
            );
            $this->session->set_userdata($data);
            $this->user_model->user_data();
            redirect('site/members');
        } else {
            $error = "Sorry the username/password combination is wrong, please try again.";
            $this->index($error);
        }
    }

    function check_password() {
        $response = array();
        $pass = md5($this->input->post('password'));

        echo "<script>alert($pass)</script>";
        if ($this->user_model->validate_password($pass) != true) {
            echo '<span class="badge badge-warning">Password does not match.</span>';
        } else {
            echo '<span class="badge badge-success">Password ok.</span>';
        }

        return $response;
    }

    // ==========================================
    // Creation
    // ==========================================

    function create_user() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $this->load->library('form_validation');

        // fieldname, error msg, rules

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'signup_form';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->user_model->create_member(0)) {
                $data['main_content'] = 'signup_successful';
                $this->load->view('includes/template', $data);
            } else {
                $this->signup();
            }
        }
    }


    function create_sa() {
        if ($this->user_model->login_check())
            redirect('site/members');
        $this->load->library('form_validation');

        // fieldname, error msg, rules

        $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'users/signup_form';
            $this->load->view('includes/template', $data);
        } else {
            if ($this->user_model->create_member(9)) {
                $data['main_content'] = 'users/signup_successful';
                $this->load->view('includes/template', $data);
            } else {
                $this->signup();
            }
        }
    }

    // ==========================================
    // Settings
    // ==========================================

    function settings_update() {
        if (!$this->user_model->login_check())
            redirect('site');

        $p = $this->input->get_post('submit'); // die();

        if (strpos($p, 'password')) {
            $this->update_password();
        } else if (strpos($p, 'email')) {
            $this->update_email();
        }
    }

    function update_password() {
        $this->form_validation->set_rules('old_pass', 'Old password', 'trim|required');
        $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('new_pass1', 'Repeated password', 'trim|required|matches[new_pass]');

        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'users/settings';
            $this->load->view('includes/template', $data);
        } else {
            $this->user_model->update_password();
            $data['success'] = "Password successfully updated";
            $data['main_content'] = 'users/settings';
            $this->load->view('includes/template', $data);
        }
    }

    function update_email() {
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'users/settings';
            $this->load->view('includes/template', $data);
        } else {
            $this->user_model->update_email();
            $data['success'] = "Email successfully updated";
            $data['main_content'] = 'users/settings';
            $this->load->view('includes/template', $data);
        }
    }

}
