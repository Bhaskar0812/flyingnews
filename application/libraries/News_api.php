<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class News_api{

    public function get_news($data){
        $notify_log = FCPATH.'newslog_log.txt';
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
        $url = 'https://newsapi.org/v2/'.$type.'?apikey='.NEWS_KEY.'&from='.$from.'&sortBy=popularity&q='.$q.'&to='.$to.'&pageSize='.$pageSize.'&page='.$page.'&sources='.$source.'';
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
        log_event($result, $notify_log);  //create log of news Api
        return $result;
    }

   public function get_news_sources(){
        $fields = array();
        $headers = array();
        $url = 'https://newsapi.org/v2/sources?apiKey='.NEWS_KEY.'';
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