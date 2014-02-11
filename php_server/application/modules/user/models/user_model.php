<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {

    function validate() {
        // Get the username and password from post
        $this->db->where('email', $this->input->post('email'));
        $this->db->where('password', md5($this->input->post('password')));

        // Try get a record using the credentials
        $q = $this->db->get('users');

        // If I get anything then I'm good
        if ($q->num_rows == 1) {
            return true;
        }
    }

    function validate_password($pass) {
        // Get the username and password from post
        $this->db->where('email', $this->session->userdata('email'));
        $this->db->where('password', $pass);

        // Try get a record using the credentials
        $q = $this->db->get('users');

        // If I get anything then I'm good
        if ($q->num_rows == 1) {
            return true;
        }
    }

    function valid_id($id) {
        // Get the username and password from post
        $this->db->where('id', $id);

        // Try get a record using the credentials
        $q = $this->db->get('users');

        // If I get anything then I'm good
        if ($q->num_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    // ============================================

    function create_member($level = null) {
        // Grab all the validated post info
        $new_member_insert_data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
            'created_at' => date('Y-m-d H:i:s'),
        );

        //var_dump($new_member_insert_data);

        if ($level != null) {
            $new_member_insert_data['level'] = $level;
        }

        // Insert into the database
        $insert = $this->db->insert('users', $new_member_insert_data);

        //var_dump($insert);
        // Check to see if everything went OK
        return $insert;
    }

    // ============================================

    function is_logged_in() {
        // Get the session logged in status
        $is_logged_in = $this->session->userdata('is_logged_in');

        // Check and see if logged in. SEND THEN TO THE HOME SCREEN OTHERWISE
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('user/login');
            die();
        }
    }

    function login_check() {
        // Get the session logged in status
        $is_logged_in = $this->session->userdata('is_logged_in');

        // Check and see if logged in or not. return tru/false
        if (!isset($is_logged_in) || $is_logged_in != true) {
            return false;
        } else {
            return true;
        }
    }

    // ============================================

    function user_data() {
        // Check if logged in
        $this->login_check();

        // Query database and get only relvant data pertaining to user that we could use
        $email = $this->session->userdata('email');

        $this->db->select('id, name, level, email', 'password');
        $this->db->where('email', $email);
        $q = $this->db->get('users');

        // Store data within the session
        if ($q->num_rows() == 1) {
            $d = $q->result();
            $data = array(
                'id' => $d[0]->id,
                'name' => $d[0]->name,
                'level' => $d[0]->level,
                'p' => $d[0]->password,
            );

            $this->session->set_userdata($data);

            // echo "<br><br>session email-> ".$this->session->userdata('email'); die();

            return true;
        } else {
            return false;
        }
        // return true of false on the status
    }

    // ============================================
    function site_admin_check() {
        // Get security level from session
        $level = $this->session->userdata('level');

        // Check if admin and return true of false
        if (!isset($level) || $level != 2) {
            return false;
        } else {
            return true;
        }
    }

    // ===========================================

    function is_site_admin() {
        // Get the session logged in status
        $level = $this->session->userdata('level');

        // Check and see if logged in. SEND THEN TO THE HOME SCREEN OTHERWISE
        if ($level != 2) {
            redirect($this->index);
            die();
        }
    }

    // ============================================

    function email_exists($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Query database to see if email exists
            $q = $this->db->where('email', $email)->count_all_results('users');
            
            // return true of false
            if ($q > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // ============================================

    function update_password() {
        //Pull up user_id from session data
        $id = $this->session->userdata('id');

        // Get information from post
        $pass = md5($this->input->post('new_pass'));

        // DB update
        return $this->db->where('id', $id)->set('password', $pass)->update('users');
    }

    function update_email() {
        //Pull up user_id from session data
        $id = $this->session->userdata('id');

        // Get information from post
        $pass = $this->input->post('email');

        // DB update
        if ($this->db->where('id', $id)->set('email', $pass)->update('users')) {
            $data['email'] = $this->input->post('email');
            $this->session->set_userdata($data);
        }
    }

    // ============================================

    function id_from_email($email) {
        $q = $this->db->select('id')
                ->where('email', $email)
                ->get('users')
                ->row();

        if (count($q))
            return $q->id;
    }

    function name_from_id($id) {
        $q = $this->db->select('name')
                ->where('id', $id)
                ->get('users')
                ->row();

        if (count($q))
            return $q[0]->name;
    }

    function email_from_id($id) {
        $q = $this->db->select('email')
                ->where('id', $id)
                ->get('users')
                ->result();

        if (count($q))
            return $q[0]->email;
    }

}
