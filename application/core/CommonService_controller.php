<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//load rest library
require APPPATH . '/libraries/REST_Controller.php';
class CommonService_controller extends REST_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('service_model');
        $this->load->helper('responseMessages');
        $this->load->model('Image_model'); //load image model
        $this->load->model('notification_model'); //load push notification model
    }

    //check auth token of request
    public function check_service_auth(){
        /*Authtoken*/
        $this->authData = '';
        $array = $this->input->request_headers();
        $header = array_change_key_case($array, CASE_LOWER);

        //check if key exist as different server may have different types of key (case sensitive) 
        if(array_key_exists ('authtoken', $header )){
        
            $key = 'authtoken';
        }
        else{

            $res = $this->response($this->token_error_msg(), SERVER_ERROR); //authetication failed 
            //pr($res);
        }
       
        $authToken = isset($header[$key]) ? $header[$key] : '';
        $userAuthData =  !empty($authToken) ? $this->service_model->isValidToken($authToken) : '';
       // pr($userAuthData);

        if(empty($userAuthData)){ 
            $this->response($this->token_error_msg(2), SERVER_ERROR); //authetication failed 
        }

        if($userAuthData->status != 1){
            $this->response($this->token_error_msg(1), SERVER_ERROR); //authetication failed, user is inactive 
        } 

        $this->authData = $userAuthData; 
        return TRUE;
     }

    //show auth token error message
    public function token_error_msg($inactive_status=1){

        $ar = array('message'=>ResponseMessages::getStatusCodeMessage(101),'authToken'=>'','responseCode'=>300, 'isActive'=>1);

        if($inactive_status==1){
            $ar['isActive'] = 0;//user inactive
        }

        $this->response($ar, SERVER_ERROR); //authetication failed, user is inactive 

    }

   //send push notifications
    public function send_push_notification($userName,$token_arr, $title, $body, $reference_id, $type,$userType,$profileImage){
        if(empty($token_arr)){
            return false;
        }
        //prepare notification message array
        $notif_msg = array('title'=>$title, 'body'=> $body, 'reference_id'=>$reference_id,'profile_image'=>$profileImage ,'type'=> $type, 'click_action'=>$userType, 'sound'=>'default','username'=>$userName);
        $this->notification_model->send_notification($token_arr, $notif_msg);  //send andriod and ios push notification
        return $notif_msg;  //return message array
    }


}//End Class 