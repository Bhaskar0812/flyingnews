<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

//Handles notifications for ios and andriod devices
class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();  
        $this->notify_log = FCPATH.'notification_log.txt';    //notifcation file path
    }
    /*Firebase notification for Andriod and ios*/
    function send_notification($registrationIds, $notificationMsg){     
        $msg = $notificationMsg;
        $fields = array(
            'registration_ids'  => $registrationIds,  //firebase token array
            'data'      => $msg ,  //msg for andriod
            'notification'      => $msg   //msg for ios
        );

        $headers = array(
            'Authorization: key=' . NOTIFICATION_KEY, //firebase API key
            'Content-Type: application/json'
        );
        
        //curl request
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );  //firebase end url
        //curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        log_event($result, $this->notify_log);  //create log of notifcation
        return $result;
    }
    
    //store notifcation in DB
    function save_notification($table, $data){
        
        return $this->insertData($table, $data);
        //}
    }

     function insertData($table,$data){
     $this->db->insert($table,$data);
     return $this->db->insert_id();
     }//E
}