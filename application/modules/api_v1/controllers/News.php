<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends Api_v1 {

	function __construct(){
		parent::__construct();
		$this->load->model('common_model');
    $this->load->model('image_model');
    $this->load->model('news_model');
    $this->load->model('get_news_model');
    $this->load->library('news_api');
    
	}

  function getNews_post(){//get news by this function..
    $this->check_service_auth();
      $getSources = $this->common_model->getSingle(USER_PREFRENCES,array('user_id'=>$this->authData->userId));
      if(empty($getSources) AND empty($this->post('filter'))){
        $this->form_validation->set_rules('source','We do not found sources and news type both, Please provide atlease 1','required');
        if($this->form_validation->run() == FALSE){
          $response = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
          $this->response($response);
        }
      }else{
      $data['type'] = $this->post('type');
      $country = $this->post('country');
      $data['from'] = $this->post('from');
      $data['to'] = $this->post('to');
      $data['pageSize'] = $this->post('pageSize');
      $filter = $this->post('filter');
      $filterData = explode(' ',$filter);
      $data['q'] = implode('%20OR%20',$filterData);
      $data['page'] = $this->post('page');
      $data['language'] = !empty($getSources)?$getSources->usersLanguage:'';;
      $data['source'] = !empty($getSources)?$getSources->userSources:'';
      $data['category'] = empty( $data['source'])?$this->post('category'):'';//only one thing is allowed sources or category.
      $response = $this->news_api->get_news($data);//calll library of news API from here..
      //pr($response);
      if(!empty($response)){
        $jsonDecoded = json_decode($response);
          if(isset($jsonDecoded->status) AND  $jsonDecoded->status == 'ok'){
            echo $response; exit;//if status is ok then return this function else return something went wrong
          }
        $res = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        $this->response($res);
      }
      $res = array('status'=>FAIL,'message'=>'Passing worng parameters.');
        $this->response($res);
    }
  }//end of get news api

  function updateUserLanguage_post(){
    $this->check_service_auth();
    //pr($_POST);
    $this->form_validation->set_rules('languages','language','required');
	 if($this->form_validation->run() == FALSE){
	    $response = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
	    $this->response($response);
	 }
  	$language = $this->post('languages');
  	$languageCode = $this->post('languageCode');
  	$data['user_id'] = $this->authData->userId;
	$data['upd'] = datetime();
    $res = $this->common_model->updateFields(USERS,array('userId'=>$this->authData->userId),array('language'=>$language,'languageCode'=>$languageCode));//update user language.
    if($res){
      $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(171));
    }else{
      $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(172));
    }
     $this->response($response);
  }//end of function

  function getCategory_get(){//get categories 
    $this->check_service_auth();
    $result = $this->common_model->select_result(array(),CATEGORIES);//select all categories by using this
    if(!empty($result)){
      $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(166),'data'=>$result);
    }else{
       $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
    }
    $this->response($response);

  }//end of get categories

  function insertNewsSources_get(){//just call this function to insert news sources in databse.
    $response = $this->news_api->get_news_sources();
    $json_decode = json_decode($response);
    foreach($json_decode->sources as $value){
      $dataInsert['newsSourceId'] = $value->id;
      $dataInsert['name']         = $value->name;
      $dataInsert['description']  = $value->description;
      $dataInsert['url']          = $value->url;
      $dataInsert['category']     = $value->category;
      $dataInsert['language']     = $value->language;
      $dataInsert['country']      = $value->country;
      //pr($dataInsert);
      $this->common_model->insertData($dataInsert,NEWS_SOURCES);
    }

  }//end of function 

  function get_news_sources_get(){//get news sources
    $this->check_service_auth();
    $response = $this->get_news_model->select_news_sources(NEWS_SOURCES);
    if($response){
      $res = array('status'=>SUCCESS,'data'=>$response);
    }else{
      $res = array('status'=>FAIL,'data'=>array());
    }
    $this->response($res);
  }//end of 


  function addUserSources_post(){//add and update sources from this api
    $this->check_service_auth();
    $this->form_validation->set_rules('sources','Sources','required');
      if($this->form_validation->run() == FALSE){//cheack sources should not be empty
        $response = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $this->response($response);
      }
    $sources = $this->post('sources');
    $explodedData = explode(',',$sources);
    if(count($explodedData) <= 20){
      $data['user_id'] = $this->authData->userId;
      $data['userSources'] = $sources;
      $data['crd'] = datetime();
      $getUserSources = $this->common_model->is_id_exist(USER_PREFRENCES,'user_id',$this->authData->userId);//select sources if exist
      if(empty($getUserSources)){
        $res = $this->common_model->insertData($data,USER_PREFRENCES);
        if($res){
          $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(167));
        }else{
          $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
      }else{
        $res = $this->common_model->updateFields(USER_PREFRENCES,array('user_id'=>$this->authData->userId),array('userSources'=>$data['userSources'],'upd'=>datetime()));
        if($res){
          $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(168));
        }else{
          $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
      }
    }else{
      $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(169));
    }
    $this->response($response);  
  }

} //end of class
