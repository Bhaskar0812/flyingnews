<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

//Handles notifications for ios and andriod devices
class News_model extends CI_Model {

    public function __construct() {
        parent::__construct();  
        $this->notify_log = FCPATH.'newslog_log.txt';    //notifcation file path
    }
    /*Firebase notification for Andriod and ios*/
    function get_news($data){
        $type = $data['type'];
        $from = $data['from'];
        $q = $data['q'];
        $to = $data['to'];
        $pageSize = $data['pageSize'];
        $page = $data['page'];
        $source = $data['source'];
         $fields = array(
            
        );
         $date = '2019-01-11';
         $dateTo = '2019-01-12';
            $headers = array(
           
        );

        $url = 'https://newsapi.org/v2/'.$type.'?apikey=2846ee24d67b48e6bd8248dddb2331f7&from='.$from.'&sortBy=popularity&q='.$q.'&to='.$to.'&pageSize='.$pageSize.'&page='.$page.'&sources='.$source.'';
        //pr($url);
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );   
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
       

        log_event($result, $this->notify_log);  //create log of news Api
        return $result;
    }


    function get_news_sources(){
        $fields = array();
        $headers = array();
        $url = 'https://newsapi.org/v2/sources?apiKey=2846ee24d67b48e6bd8248dddb2331f7';
            /*$options = array(

                CURLOPT_URL             => $url,
                CURLOPT_HEADER          => FALSE,
                CURLOPT_POST            => 1,
                CURLOPT_HTTPHEADER      => $headers,
                CURLOPT_POSTFIELDS      => $fields,
                CURLOPT_RETURNTRANSFER  => TRUE

            ); // cURL options*/

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );   
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch );
        curl_close( $ch );
       

        log_event($result, $this->notify_log);  //create log of news Api
        return $result;
    }
    
  
}