<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('api_m');
        $this->load->model('members/members_m');
        $this->load->model('members/poll_m');
    }

    // login
    function login() {
        //if (isset($_POST['tag']) && $_POST['tag'] != '') {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // check for user
        $user = $this->api_m->login($email, $password);
        //var_dump($user);
        if ($user != false) {
            // user found
            // echo json with success = 1
            $response["success"] = 1;
            $response["uid"] = $user->id;
            $response["user"]["name"] = $user->name;
            $response["user"]["email"] = $user->email;
            $response["user"]["created_at"] = $user->created_at;
            $response["user"]["updated_at"] = $user->updated_at;
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }
        //} else {
        //    echo "Access Denied";
        //}
    }

    // register
    function register() {
        //if (isset($_POST['tag']) && $_POST['tag'] != '') {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');

        // $name = "t"; $email="t.m@test.com"; $password="pass";
        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["success"] = 0;
            $response["error"] = 1;
            $response["error_msg"] = "Please provide Name, Email and Password";
            echo json_encode($response);
            die();
        } else {
            if ($this->user_model->email_exists($email)) {
                $response["success"] = 0;
                $response["error"] = 2;
                $response["error_msg"] = "Email not valid";
                echo json_encode($response);
                die();
            } else {
                $user = $this->api_m->create_member($name, $email, $password, 0);
                if ($user) {
                    $response["success"] = 1;
                    $response["uid"] = $user["id"];
                    $response["user"]["name"] = $user["name"];
                    $response["user"]["email"] = $user["email"];
                    $response["user"]["created_at"] = $user["created_at"];
                    $response["user"]["updated_at"] = $user["updated_at"];
                    echo json_encode($response);
                    die();
                } else {
                    $response["success"] = 0;
                    $response["error"] = 1;
                    $response["error_msg"] = "Error occured in Registartion";
                    echo json_encode($response);
                    die();
                }
            }
        }
    }

    // ====================

    function all_groups() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'Name', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID";
            echo json_encode($response);
            die();
        } else {
            // check user id
            $id = $this->input->post("user_id");

            if (!$this->user_model->valid_id($id)) {
                $response["error"] = 2;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            // get groups n send
            $g = $this->members_m->load_groups($id);
            $response["success"] = 1;
            $response["groups"] = & $g;
            echo json_encode($response);
        }
    }

    // Send back all polls for group
    function group() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'Name', 'trim|required');
        $this->form_validation->set_rules('group_id', 'Name', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID and Group ID";
            echo json_encode($response);
            die();
        } else {
            // check user id
            $id = $this->input->post("user_id");
            $group_id = $this->input->post("group_id");

            if (!$this->user_model->valid_id($id)) {
                $response["error"] = 2;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            //grab data and send
            $p = $this->api_m->load_all_polls($group_id);

            $response["success"] = 1;
            $response["group_id"] = $group_id;
            $response["user_id"] = $id;
            $response["polls"] = $p;
            echo json_encode($response);
            die();
        }
    }

    // create group
    function c_group() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_name', 'Group name', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Name', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID and Group Name";
            echo json_encode($response);
            die();
        } else {
            // grab the group name and try create
            $group_name = $this->input->post('group_name');
            $user_id = $this->input->post('user_id');

            // validate user
            if (!$this->user_model->valid_id($id)) {
                $response["error"] = 2;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            if ($this->members_m->check_group($group_name)) {
                // Add group
                $group_id = $this->members_m->create_group($g_name, $user_id);

                // add user to group
                if ($this->members_m->add_to_group($user_id, $group_id)) {

                    // echo success
                    $response["success"] = 1;
                    $response["group_id"] = $group_id;
                    $response["user_id"] = $id;
                    echo json_encode($response);
                    die();
                } else {
                    $response["error"] = 2;
                    $response["error_msg"] = "Sorry... Group created but did not add you to it. Try join manually!";
                    echo json_encode($response);
                }
            } else {
                // failed -> echo failed respose
                $response["error"] = 3;
                $response["error_msg"] = "Sorry... Group name already taken!";
                echo json_encode($response);
                die();
            }
        }
    }
    
    // Available groups to join
    function open_groups($user_id=null){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide a User ID";
        } else {
            $user_id = $this->input->post('user_id');
            
            // validate user
            if (!$this->user_model->valid_id($user_id)) {
                $response["error"] = 1;
                $response["error_msg"] = "Invalid User ID in Open_Groups method";
                echo json_encode($response);
                die();
            }
            
            $response['success'] = 1;
            $response["groups"] = array_values($this->api_m->open_groups($user_id));
            echo json_encode($response);
            die();
        }
    }

    // Join group
    function j_group() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID and Group ID";
        } else {
            // grab the group name and try create
            $group_id = $this->input->post('group_id');
            $user_id = $this->input->post('user_id');

            // validate user
            if (!$this->user_model->valid_id($user_id)) {
                $response["error"] = 1;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            // add
            if ($this->members_m->add_to_group($user_id, $group_id)) {
                // echo success
                $response["success"] = 1;
                $response["group_id"] = $group_id;
                $response["user_id"] = $id;
            } else {
                // failed -> echo failed respose
                $response["error"] = 2;
                $response["error_msg"] = "Sorry... Was not added to the group!";
            }
        }
    }
    
    // Join group
    function r_group() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID and Group ID";
        } else {
            // grab the group name and try create
            $group_id = $this->input->post('group_id');
            $user_id = $this->input->post('user_id');

            // validate user
            if (!$this->user_model->valid_id($user_id)) {
                $response["error"] = 1;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            // remove
            if ( $this->members_m->remove_from_group($user_id, $group_id) ) {
                // echo success
                $response["success"] = 1;
            } else {
                // failed -> echo failed respose
                $response["error"] = 2;
                $response["error_msg"] = "Sorry... Was not removed to the group!";
            }
        }
    }

    // ==================
    // create poll
    function c_poll($group_id) {
        echo "in c poll"; // die();

        $data['group_id'] = $group_id;
        $this->poll_m->create_poll($group_id);
    }

    // submit poll data
    function s_poll() {
        //$this->poll_m->submit_poll($user_id, $poll_id, $ans)
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
        $this->form_validation->set_rules('poll_id', 'Poll ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID and Group ID";
        } else {
            // grab the group name and try create
            $poll_id = $this->input->post('poll_id');
            $user_id = $this->input->post('user_id');
            $group_id = $this->input->post('group_id');
            $ans = $this->input->post('ans');

            // test data
                // $poll_id=4; $user_id=1; $group_id=2;
            
            // validate user
            if (!$this->user_model->valid_id($user_id)) {
                $response["error"] = 1;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            if($this->poll_m->submit_poll($user_id, $poll_id, $ans)){
                $response["success"] = 1;
                echo json_encode($response);
                die();
            } else {
                $response["error"] = 1;
                $response["error_msg"] = "Sorry... Could not submit poll";
                echo json_encode($response);
                die();
            }
        }
    }

    // Pull up poll options
    function poll() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('group_id', 'Group ID', 'trim|required');
        $this->form_validation->set_rules('poll_id', 'Poll ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // echo error message
            $response["error"] = 1;
            $response["error_msg"] = "Please provide User ID and Group ID";
        } else {
            // grab the group name and try create
            $poll_id = $this->input->post('poll_id');
            $user_id = $this->input->post('user_id');
            $group_id = $this->input->post('group_id');

            // test data
                // $poll_id=4; $user_id=1; $group_id=2;
            
            // validate user
            if (!$this->user_model->valid_id($user_id)) {
                $response["error"] = 2;
                $response["error_msg"] = "Invalid User ID";
                echo json_encode($response);
                die();
            }

            $poll = $this->poll_m->load_poll($poll_id);
            $response["success"] = 1;
            $response["group_id"] = $group_id;
            $response["poll_id"] = $poll_id;
            $response["poll"] = $poll;
            echo json_encode($response);
            die();
        }
    }
    
    function p_result($poll_id) {
        $this->load->library('gcharts');
        
        $data['result'] = $this->poll_m->poll_result($poll_id);
        $data['group_id'] = $data['result']->group_id;

        $ans = $data['result']->answers;
        // var_dump($data); die();

        // =========================================
        $this->gcharts->load('ColumnChart');

        $this->gcharts->DataTable('Poll')->addColumn('string', 'Poll question', 'poll');

        $vals = array();

        // Add the question
        array_push($vals, $data['result']->ques);

        foreach($data['result']->answers as $a){
            $this->gcharts->DataTable('Poll')->addColumn('number', $a->ans, $a->ans);
            array_push($vals, $a->num);
        }

        // Add the data to the graph
        $this->gcharts->DataTable('Poll')->addRow($vals);
        $config = array( 'title' => 'Poll '.$data['result']->id);

        $this->gcharts->ColumnChart('Poll')->setConfig($config);

        $data['main_content'] = 'poll_result';
        $this->load->view('includes/template', $data);

    }
    
}
