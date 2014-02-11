<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Poll_m extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    //===========================
    // grab all polls for that group
    function load_all_polls($group_id) {
        $q1 = $this->db->select('id')->where('group_id', $group_id)->get('poll_ques')->result();

        // var_dump($q1); die();

        $data = array();
        foreach ($q1 as $i) {
            array_push($data, $this->load_poll($i->id));
        }

        return $data;
    }

    // return an object with the poll
    function load_poll($poll_id) {
        if ($this->valid_poll($poll_id)) {
            $q1 = $this->db->where('id', $poll_id)->get('poll_ques')->row();
            $q2 = $this->db->where('poll_id', $poll_id)->get('poll_choices')->result();

            $data['id'] = $q1->id;
            $data['ques'] = $q1->ques;
            $data['group_id'] = $q1->group_id;
            $data['owner'] = $q1->owner;

            $d = array();
            foreach ($q2 as $key => $i) {
                // $data['a_'.$key] = $i->ans;
                $temp['ans'] = $i->ans;
                $temp['id'] = $i->id;

                // push the ans onto d
                array_push($d, $temp);
            }

            // add ans array to package
            $data['answers'] = $d;
            return $data;
        } else {
            return array();
        }
    }

    function valid_poll($poll_id) {
        if ($this->db->where('id', $poll_id)->count_all_results('poll_ques') > 0) {
            return true;
        } else {
            return false;
        }
    }

    // ==========================

    function delete_poll($poll_id) {
        // delete choices
        // delete responses
        // delete question
    }

    // ========================

    function create_poll($group_id, $owner) {
        $ques = $this->input->post('ques');
        $o = array(); // answer object
        $a = array(); // correct object

        $x = $this->input->post();

        // extract options and answer indicators into 2 objects 
        foreach ($x as $key => $item) {
            $c = explode('_', $key);
            if ($c[0] == 'option') {
                $o[$c[1]] = $item;
            }

            if ($c[0] == 'correct') {
                $a[$c[1]] = $item;
            }
        }

        // merge these objects into 1 object
        $opt = array();

        foreach ($o as $key => $val) {
            $c = new stdClass();
            $c->ans = $val;
            foreach ($a as $key1 => $val1) {
                if ($key1 == $key) {
                    $c->correct = $val1;
                    break;
                }
            }
            $opt[] = $c;
        }

        // var_dump($opt); 
        // Insert question 
        $ques_id = $this->insert_ques($group_id, $ques, $owner);

        // Insert Options and ansers
        return $this->insert_options($group_id, $ques_id, $opt);
    }

    function insert_ques($group_id, $ques, $owner) {
        $table = 'poll_ques';
        $data = array(
            'group_id' => $group_id,
            'ques' => $ques,
        );

        $c = $this->db->where($data)->count_all_results($table);
        if ($c == 0) {
            $data['owner'] = $owner;
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        } else {
            $q = $this->db->select('id')->where($data)->limit(1)->get($table)->row();
            return $q->id;
        }
    }

    function insert_options($group_id, $ques_id, $opt) {
        $table = 'poll_choices';

        foreach ($opt as $item) {
            // check if already there
            $count = $this->db->where('poll_id', $ques_id)->where('ans', $item->ans)->count_all_results($table);
            if ($count == 0) {
                $data['poll_id'] = $ques_id;
                $data['ans'] = $item->ans;
                $this->db->set($data)->insert($table);
            }// no duplicates
        }
        return;
    }

    // =====================

    function submit_poll($user_id, $poll_id, $ans) {
        $table = 'poll_responses';
        if ($this->valid_poll_ans($poll_id, $ans)) {
            $check = array(
                'user_id' => $user_id,
                'poll_id' => $poll_id,
            );

            $data = array(
                'user_id' => $user_id,
                'poll_id' => $poll_id,
                'ans_id' => $ans
            );

            $c = $this->db->where($check)->count_all_results($table);

            if ($c == 0) {
                return $this->db->insert($table, $data);
            } else {
                return $this->db->where($check)->set($data)->update($table);
            }
        } else {
            return false;
        }
    }

    // Check whether the poll given was even valid
    function valid_poll_ans($poll_id, $ans_id) {
        $a = $this->db->select('id')->where('poll_id', $poll_id)->get('poll_choices')->result();
        foreach ($a as $opt) {
            if ($opt->id == $ans_id) {
                return true;
            }
        }
        return false;
    }

    // ========================

    function poll_result($poll_id) {
        $total = 0;

        // get question
        $d = $this->db->where('id', $poll_id)->get('poll_ques')->row();

        // get choices and count values
        $c = $this->db->where('poll_id', $poll_id)->get('poll_choices')->result();

        if (sizeof($c) > 0) {
            foreach ($c as $item) {
                $count = $this->db->where('ans_id', $item->id)->count_all_results('poll_responses');
                $item->num = $count;
                $total += $count;
            }
        }
        $d->total = $total;
        $d->answers = $c;

        return $d;
    }

}
