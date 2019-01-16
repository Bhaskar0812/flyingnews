<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'vendor/autoload.php';
use phpFastCache\CacheManager;
use phpFastCache\Helper\Psr16Adapter;
//use phpfastcache\Config\ConfigurationOption;

class News extends Common_api_v1 {

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
      //pr( $data['q']);
      $data['page'] = $this->post('page');
      $data['language'] = !empty($this->authData->languageCode)?$this->authData->languageCode:'';
      $data['source'] = !empty($getSources)?$getSources->userSources:'';
      $data['category'] = empty( $data['source'])?$this->post('category'):'';//only one thing is allowed sources or category.
      /* CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => APPPATH.'cache/news_api/news_cache.php', // or in windows "C:/tmp/"
        ]));*/
        $url = 'https://newsapi.org/v2/'.$data['type'].'?apikey='.NEWS_KEY.'&sortBy=popularity&&pageSize='.$data['pageSize'].'&page='.$data['page'].'&q='.$data['q'].'&sources='.$data['source'].'&language='.$data['language'].'&category='.$data['category'].'';

        $config = array('path'=>APPPATH.'cache/news_api/');
        $InstanceCache = CacheManager::getInstance('files', $config);
		// An alternative exists:
		CacheManager::Files($config);
		$key = "news_page";
		$url_key = "news_page_url";
		//$key = $url;
		///echo $url;
		$CachedString = $InstanceCache->getItem($key);
		$CachedString_url = $InstanceCache->getItem($url_key);
		//print_r($CachedString_url->get().' <br>'.$url);
       	if ($CachedString_url->get() != $url) {
       		//if()
      		$response = $this->news_api->get_news($url);//calll library of news API from here..
      		$CachedString->set($response)->expiresAfter(15);//in seconds, also accepts Datetime
      //pr($response);   
      		$CachedString_url->set($url)->expiresAfter(15);//in seconds, also accepts Datetime
      //pr($response);
      		$InstanceCache->save($CachedString_url);       
            $InstanceCache->save($CachedString); // Save the cache item just like you do with doctrine and entities
            echo $response;

        } else {
            echo $CachedString->get();// Will print 'First product'
        }
 
    
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

  function addFavouriteNews_post(){
  	$this->check_service_auth();
  	$this->form_validation->set_rules('news','News','required');
      if($this->form_validation->run() == FALSE){//cheack sources should not be empty
        $response = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
        $this->response($response);
      }
    $data['favouriteNews'] = $this->post('news'); 
    $data['crd'] = datetime(); 
    $data['user_id'] = $this->authData->userId;
    $res = $this->common_model->insert_data(USER_FAVOURITE_NEWS,$data); 
    if($res){
    	$response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(174));
	}else{
		$response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(175));
	}
	$this->response($response);
  }

} //end of class
