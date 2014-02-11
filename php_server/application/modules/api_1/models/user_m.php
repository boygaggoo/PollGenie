<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * This Model handles Base user functionality 
 */

class User_m extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /************************************
     * Add or remove users 
     ************************************/
    function storeUser($name, $email, $gcm_regid) {
        $data = array(
            'name' => $name,
            'email' => $email,
            'gcm_regid' => $gcm_regid
        );

        $count = $this->db->where('gcm_regid', $gcm_regid)
                ->count_all_results('gcm_users');

        if ($count == 0) {
            // insert in the users table
            $this->db->insert('gcm_users', $data);

            // Get the ID
            $id = $this->db->insert_id();

            // return the data back as an array
            return $this->db->where('id', $id)
                            ->get('gcm_users')
                            ->row();
        }
    }
    function removeUser($id){
        
    }
    
    /************************************
     * Get a user of get everone here
     ************************************/
    function getU($id = null) {
        if ($id != null) {
            $this->db->where('id', $id);
            return $this->db->get('gcm_users')
                            ->row();
        } else {
            return $this->db->get('gcm_users')
                            ->result();
        }
    }

    /************************************
     * Group functions
     ************************************/
    // Get groups based on user ID
    function get_groups_id($id) {
        
    }

    // get groups based on device ID
    function get_groups_regId($regId) {
        
    }
    
    // return all groups
    function groups(){
    
    }
    
    // add someone to a group
    function group_add($groupId, $id){
    }

}
