<?php 
class Service_model extends CI_Model {
	//Generate token for user
	function _generate_token(){
		$this->load->helper('security');
		$salt = do_hash(time().mt_rand());
		$new_key = substr($salt, 0, 20);
		return $new_key;
	}
	
	//Function for check provided token is valid or not
	function isValidToken($authToken){
		$this->db->select('*');
		$this->db->where('authToken',$authToken);
		if($query = $this->db->get(USERS)){
			if($query->num_rows() > 0){
				return $query->row();
			}
		}		
		return FALSE;
	}

	function random_password( $length = 8 ) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,?";
	    $password = substr( str_shuffle( $chars ), 0, $length );
	    return $password;
	}

	
	function forgetPassword($table,$data){ 
    //function for forget password 
        $get = array();
        $this->load->library('Smtp_email');
        $email = $data['email'];
        $query = $this->db->select('*')->where('email',$email)->get($table);
        if($query->num_rows()==1){
            $getData 		= 	$query->row();
            $get['userId'] 		= 	$getData->userId;
            $temp_pass 		= 	md5(uniqid());
            $userUpdate		= 	$this->db->set(array('forgetPass'=>$temp_pass))->where(array('userId'=>$get['userId']))->update($table);
            if($userUpdate){
                $query = $this->db->select('*')->where('userId',$get['userId'])->get($table);
                if($query->num_rows()==1){
	                $dataUser = $query->row();
	                $dataSend['name']       = $dataUser->fullName;
	                $dataSend['message']    = 'Reset Link for You Email:'.''.$data['email'].' '.'is'.'';
	                $dataSend['email']      = $data['email'];
	                $dataSend['link']       = base_url()."user_authentication/setPasswordUser/?token=".$dataUser->forgetPass."&userid=".encoding($get['userId']);//used encoding for encrypt user id becase it will give always same encode for same digit.
	                 $dataSend['browser']   = $_SERVER['HTTP_USER_AGENT'];
	                 // this will give browser name and os detail.
	                $message = $this->load->view('email/reset_password',$dataSend,TRUE);
	                //email template load from this.
	                $subject = "Reset password";
	                $isSend = $this->smtp_email->send_mail($data['email'],$subject,$message);
	                //email sent to user email id..
	                if($isSend){
	                	return array('type'=>'ES','userDetail'=>$dataUser);//Email Send.
	            	}else{
	            		return FALSE;
	            	}
                } else{
                    return FALSE; //Not Sent
                }
            } 
        }//Email Found on database
        else{
            return array('type'=>'IE','userDetail'=>array()); //Invalid Email
        }
    }//END OF FUNCTION..

    

  	function customGet($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        if($query->num_rows()==1){
        $user = $query->row();
        return $user;
        }
    }//END OF FUNCTION..

	function generateAuthToken(){
    	$authToken 	= $this->_generate_token();
    	$existToken = $this->common_model->get_records_by_id(ADMIN,true,array('authToken'=>$authToken),"*","","");
    	if(!empty($existToken)){
    	 	$this->_generate_token(); 	
    	} else{
    	 	$authToken = $authToken;
    	}
    	return $authToken;
    }

    function userInfo($id){

		$res = $this->db->select('*')->where($id)->get(USERS);

		if($res->num_rows()){
			$result = $res->row();
			
			if (!empty($result->profileImage) && filter_var($result->profileImage, FILTER_VALIDATE_URL) === false) {
				//$result->profileImage = base_url().UPLOAD_FOLDER.'/profile/thumb/'.$result->profileImage;
				$result->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$result->profileImage;
			}
					
			return $result;
		} else {
			return false;
		}
	}
	//user registration
	function userRegistration($data) {

		$res = $this->db->select('userId')->where(array('email'=>$data['email']))->get(USERS);
		//check data exist or not
		if($res->num_rows() == 0) {
			if(!empty($data['socialId']) && !empty($data['socialType'])) {

				$check = $this->db->select('userId')->where(array('socialId'=>$data['socialId'],'socialType'=>$data['socialType']))->get(USERS);
				//check data exist using social id
				if($check->num_rows() == 1) {
					$id=$check->row();

					// $this->db->update(USERS,array('deviceToken' => ''),array('deviceToken'=>$data['deviceToken']));

					//update divice token and type
					$this->db->where(array('userId'=>$id->userId));
					$this->db->update(USERS,array('authToken'=>$data['authToken'],'deviceToken'=>$data['deviceToken'],'deviceType'=>$data['deviceType']));
					$userDetail['data'] = $this->userInfo(array('userId'=>$id->userId));					
					$userDetail['regType'] = 'SL';
					return $userDetail;
				} else{
					
					//insert data into user table in social registration
					$this->db->insert(USERS,$data);
					$userId = $this->db->insert_id();
					//get user detail
					$userDetail['data'] = $this->userInfo(array('userId'=>$userId));
					$userDetail['regType'] = 'SR';
					return $userDetail;			
				}
			}else{
				//get address using lat long
				$this->db->insert(USERS,$data);
				$userId = $this->db->insert_id();
				//check data inserted yes or not
				if(empty($userId)){
					return "SGW";
				}
				//get user detail from table
				$userDetail['data'] = $this->userInfo(array('userId'=>$userId));            
	            //send mai							
				$userDetail['regType'] = 'NR';
				return $userDetail;
			}
		}else {
			//check social id or type
			if(!empty($data['socialId']) && !empty($data['socialType'])) {
				//get user info using socialid
				$check = $this->db->select('userId')->where(array('socialId'=>$data['socialId'],'socialType'=>$data['socialType']))->get(USERS);
				if($check->num_rows() == 1){
					//check data is exist 
					$id=$check->row();
					// $this->db->update(USERS,array('deviceToken' => ''),array('deviceToken'=>$data['deviceToken']));
					$this->db->where(array('userId'=>$id->userId));
					$this->db->update(USERS,array('authToken'=>$data['authToken'],'deviceToken'=>$data['deviceToken'],'deviceType'=>$data['deviceType']));
					$userDetail['data'] = $this->userInfo(array('userId'=>$id->userId));
					$userDetail['regType'] = 'SL';
					return $userDetail;
				} else{
					return 'AE';
				}
			} else{
				return 'AE';
			}
		}
		return false;
	}

	function isLogin($data,$authToken,$where,$table){// function for login
	  $res = $this->db->select('*')->where($where)->get($table);
	  if($res->num_rows() > 0){
	  	$result = $res->row();
	  	$password = $data['password'];	
	  	if(password_verify($password,$result->password)){
	  		if($result->status == 1){//if user is active
	  				$update_data = array();
	  				$update_data['deviceToken'] = $data['deviceToken'];
	  				$update_data['deviceType'] = $data['deviceType'];
	          $update_data['authToken'] = $authToken;
	  				$update_data['forgetPass'] = '';
	  			if(!empty($update_data['deviceToken'])){
	  				$this->db->update($table,array('deviceToken' => '','forgetPass'   =>''),array('deviceToken'=>$update_data['deviceToken']));
	  				$this->db->update($table,$update_data,array('userId'=>$result->userId));
	  				$userDetail = $this->userInfo(array('userId'=>$result->userId));
	  				return array('type'=>'LS','userDetail'=>$userDetail); //login successfull
	  			} else{
	  				$this->db->update($table,array('authToken'=>$data['authToken'],'forgetPass'=>''),array('userId'=>$result->userId));
	  				$userDetail = $this->userInfo(array('userId'=>$result->userId));
	  				return array('type'=>'LS','userDetail'=>$userDetail); //login successfull
	  			}
	  		} else {
	  			return array('type'=>'NA','userDetail'=>array()); // not active
	  		}
	  	} else {
	  		return array('type'=>'WP','userDetail'=>array()); //wrong password
	  	}
	  } 
	  return FALSE;
  }//end of function.

  function updateProfile($table,$where,$updateData){//function for update profile.
      if(isset($where)){  
          $query = $this->db->update($table, $updateData, array('userId'=>$where));
          if($query){
            $userDetail   =   $this->userInfo(array('userId'=>$where),$table);//fetch user data from userInfo function.
        return array('type'=>'US','userDetail'=>$userDetail);//update successfully.
          } else{
            return array('type'=>'NU','userDetail'=>array()); //not update.
          }
        } else{
          return FALSE; //we cannt process your request.
        }
    }//End Of FUNCTION
	
}//END OF CLASS.

