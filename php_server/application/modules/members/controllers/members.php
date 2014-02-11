<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Members extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('members_m');
        $this->load->model('poll_m');
        $this->user_model->is_logged_in();

        // gCharts for graphs
        $this->load->library('gcharts');
    }

    function index() {
        // var_dump($this->session->all_userdata() ); die();

        $data['groups'] = $this->members_m->load_groups($this->session->userdata('id'));
        // var_dump($data); die();
        
        $data['main_content'] = 'panel';
        $this->load->view('includes/template', $data);
    }

    // ====================

    function create_group($error = null) {
        if ($error != null) {
            $data['error'] = $error;
        }

        $data['main_content'] = 'group_create';
        $this->load->view('includes/template', $data);
    }

    function c_group() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('g_name', 'Group name', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'group_create';
            $this->load->view('includes/template', $data);
        } else {
            // grab the group name and try create
            $g_name = $this->input->post('g_name');
            $owner = $this->session->userdata('id');
            if ($this->members_m->check_group($g_name)) {
                // Group name i free so create one
                $group_id = $this->members_m->create_group($g_name, $owner);

                // At least add me to the group
                $user_id = $this->session->userdata('id');

                $this->members_m->add_to_group($user_id, $group_id);

                // return to index once done
                $this->index();
            } else {
                $this->create_group("Sorry, name already exists! Please try another one.");
            }
        }
    }

    // ====================

    function join_group($error = null) {
        if ($error != null) {
            $data['error'] = $error;
        }

        $data['groups'] = $this->members_m->load_groups();
        $data['my_groups'] = $this->members_m->load_groups($this->session->userdata('id'));
        $data['main_content'] = 'group_join';
        $this->load->view('includes/template', $data);
    }

    function j_group($group_id) {
        $user_id = $this->session->userdata('id');
        $this->members_m->add_to_group($user_id, $group_id);
        $this->index();
    }

    // ===================

    function r_group($group_id) {
        $user_id = $this->session->userdata('id');
        $this->members_m->remove_from_group($user_id, $group_id);
        $this->index();
    }

    // ===================

    function group($group_id) {
        $api_call = false;
        $data['polls'] = $this->poll_m->load_all_polls($group_id);

        if ($api_call) {
            echo json_encode($data['polls']);
            die();
        }

        $data['group_id'] = $group_id;
        $data['main_content'] = 'group_view';
        $this->load->view('includes/template', $data);
    }

    // ==================

    function create_poll($group_id) {
        $data['group_id'] = $group_id;
        $data['main_content'] = 'poll_create';
        $this->load->view('includes/template', $data);
    }

    function c_poll($group_id) {
        $data['group_id'] = $group_id;
        $owner = $this->session->userdata('id');
        $this->poll_m->create_poll($group_id, $owner);
        $this->group($group_id);
    }

    // =================
    function results($group_id) {
        echo "Should show summary of results as Pie/Bar charts";
    }

    // =================

    function poll_ajax($poll_id, $ans_id, $user_id = null) {
        if ($user_id == null) {
            $user_id = $this->session->userdata('id');
        }
        $r = $this->poll_m->submit_poll($user_id, $poll_id, $ans_id);
        $response = array();
        if ($r) {
            $response["success"] = 1;
            echo "Successfully updated poll!";
            die();
        } else {
            $response["error"] = 1;
            $response["error_msg"] = "Answer not saved!";
            echo "Failed to update poll!";
            die();
        }
        echo json_encode($response);
    }

    // ================

    function poll_result($poll_id) {
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
