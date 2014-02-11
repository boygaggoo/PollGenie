<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * This Model handles most of the data transfer from the PHP application through
 * to the GCM API
 */
class Gcm_m extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     * Send message to all phones for the given registatoin_ids array
     */
    function send_notification($registatoin_ids, $message) {

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . $this->config->item('google_api'),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        echo $result;
    }
    
    /*
     * Send message to a single phone
     */
    function send_message($regId, $type, $msg){
        $registatoin_ids = array($regId);
        $message = array($type => $msg);
        
        $result = $this->send_notification($registatoin_ids, $message);
        echo $result;
    }

}
