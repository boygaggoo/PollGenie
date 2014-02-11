<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Members_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    //=============================
    // load all the users groups
    function load_groups($user_id = null) {
        if ($user_id != null) {
            return $this->db->select('g.id, g.name, g.owner')
                            ->from('group_reg AS r, groups AS g')
                            ->where('r.group_id = g.id')
                            ->where('r.user_id', $user_id)
                            ->get()
                            ->result();
        } else {
            return $this->db->get('groups')
                            ->result();
        }
    }

    // ad the user id to the group
    function add_to_group($user_id, $group_id) {
        $data['user_id'] = $user_id;
        $data['group_id'] = $group_id;

        $count = $this->db->where($data)->count_all_results('group_reg');
        if ($count > 0) {
            return false;
        } else {
            return $this->db->insert('group_reg', $data);
        }
    }
    
    // remove the user id to the group
    function remove_from_group($user_id, $group_id) {
        $data['user_id'] = $user_id;
        $data['group_id'] = $group_id;

        $count = $this->db->where($data)->count_all_results('group_reg');
        if ($count != 1) {
            return false;
        } else {
            return $this->db->where($data)->delete('group_reg');
        }
    }

    // ==========================
    // create a group
    function create_group($name, $owner) {
        $data['name'] = $name;
        $data['owner'] = $owner;
        $this->db->insert('groups', $data);
        return $this->db->insert_id();
    }

    // remove the group and all registered people
    function delete_group($group_id) {
        // remove registered members
        $this->db->where('group_id', $group_id)
                ->delete('group_reg');

        // remove group
        $this->db->where('id', $group_id)
                ->delete('groups');
    }

    // Check to see if group name exists
    function check_group($name) {
        $c = $this->db->where('name', $name)->count_all_results('groups');
        if ($c == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // ============================
    
}
