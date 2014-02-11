<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('members/members_m');
        $this->load->model('members/poll_m');
    }

    function login($email, $password) {
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));

        // Try get a record using the credentials
        return $this->db->get('users')->row();
    }

    function create_member($name, $email, $password, $level = null) {
        // Grab all the validated post info
        /*$new_member_insert_data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        );*/
        
        $new_member_insert_data = array(
            'name' => $name,
            'email' => $email,
            'password' => md5($password),
            'created_at' => date('Y-m-d H:i:s'),
        );

        if ($level != null) {
            $new_member_insert_data['level'] = $level;
        }

        // Insert into the database
        $insert = $this->db->insert('users', $new_member_insert_data);

        // Check to see if everything went OK
        if ($insert) {
            $uid = $this->db->insert_id(); // last inserted id
                // var_dump($uid);
            
            $result = mysql_query("SELECT * FROM users WHERE id = $uid");
                // var_dump($result); die();
            
            // return user details
            return mysql_fetch_array($result);
        }
    }
    
    //===========================
    // grab all polls for that group
    function load_all_polls($group_id) {
        $q1 = $this->db->where('group_id', $group_id)->get('poll_ques')->result();
        return $q1;
    }
    
    function open_groups($user_id){
        $my_groups = $this->members_m->load_groups($user_id);
        $groups = $this->members_m->load_groups();
        
        foreach ($groups as $key=>$g){
            foreach($my_groups as $m){
                if($g->id == $m->id){
                    unset($groups[$key]);
                    break;
                }
            }
        }
        
        return $groups;
    }

}
