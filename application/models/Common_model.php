<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Common Model
 * Consist common DB methods which will be commonly used throughout the project
 */
class Common_model extends CI_Model {

    function isLogin($data,$password,$table){  
    //login function common 
        $this->db->select("*");
        $this->db->where($data);
        $query = $this->db->get($table);
        if($query->num_rows()>0){
            $user = $query->row();
            //pr($user);
            //verify password- It is good to use php's password hashing functions so we are using password_verify fn here
            $id = $user->id;
            $this->updateFields($table,array('id'=>$id),array('forgetPass'=>''));
            if(password_verify($password, $user->password)){
               $session_data['id']          =       $user->id ;
                $session_data['emailId']    =       $user->email;
                $session_data['name']       =       $user->fullName;
                $session_data['isLogin']    =       TRUE;

                $_SESSION[ADMIN_USER_SESS_KEY] = $session_data;
            
                //$this->session->set_userdata($session_data);
                return TRUE;
            }
            else{
                return FALSE; 
            }
        }
       return FALSE;
    }//END OF FUNCTION..

    public function updateFields($table,$whereCondition,$updateData){
    
      $this->db->update($table, $updateData, $whereCondition);
      $row = $this->db->affected_rows() ;
      return $row;
    }  

    function deleteData($table,$where){
        $this->db->where($where);
        $this->db->delete($table); 
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }   
    }

    function forgetPassword($table,$data){ 
    //function for forget password 
        $get = array();
        $this->load->library('Smtp_email');
        //smtp email send library..
        $email = $data['email'];
        $query = $this->db->select('*')->where('email',$email)->get($table);
        if($query->num_rows()==1){
            $getData = $query->row();
            $get['id'] = $getData->id;
            $temp_pass = md5(uniqid());
            $userUpdate= $this->db->set(array('forgetPass'=>$temp_pass))->where(array('id'=>$get['id']))->update($table);
            if($userUpdate){
                $query = $this->db->select('*')->where('id',$get['id'])->get($table);
                if($query->num_rows()==1){
                $dataUser = $query->row();
                $dataSend['name']       = $dataUser->fullName;
                $dataSend['message']    = 'Reset Link for You Email:'.''.$data['email'].' '.'is'.'';
                $dataSend['email']      = $data['email'];
                $dataSend['link']       = base_url()."admin/setPassword/".$dataUser->forgetPass."/".md5($get['id']);//used md5 for encrypt user id becase it will give always same encode for same digit.
                 $dataSend['browser']   = $_SERVER['HTTP_USER_AGENT'];
                 // this will give browser name and os detail.
                $message = $this->load->view('email/reset_password',$dataSend,TRUE);
                //email template load from this.
                $subject = "Reset password";
                $this->smtp_email->send_mail($data['email'],$subject,$message);
                //email sent to user email id..
                return TRUE;
                }else{
                    return FALSE;
                }
            } 
        }//Email Found on database
        else{
            return FALSE;
        }
    }//END OF FUNCTION..

     public function is_id_exist($table,$key,$value){
        $this->db->select("*");
        $this->db->from($table);
        $this->db->where($key,$value);
        $ret = $this->db->get()->row();
        if(!empty($ret)){
            return $ret;
        }
        return FALSE;
    } 

    public function getCount($table){
       $ret = $this->db->select("*")->get($table);
        if($ret->num_rows()){
            return $ret->num_rows();
        }
        return 0;
    }

    public function getCountByData($table,$where){
       $ret = $this->db->select("*")->where($where)->get($table);
        if($ret->num_rows()){
            return $ret->num_rows();
        }
        return 0;
    }

    function customGet($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($query->num_rows() == 1){
        $user = $query->row();
        return $user;
        }return FALSE;
    }//END OF FUNCTION..

    function userLogin($data,$or_where,$password,$table){  //login function common
        $this->db->select("*");
        $this->db->where($data);
        $this->db->or_where($or_where);
        //$this->db->or_where($or_data);
        $query = $this->db->get($table);
        if($query->num_rows()==1){
            $user = $query->row();
            //verify password- It is good to use php's password hashing functions so we are using password_verify fn here
            if(password_verify($password, $user->password)){
               $session_data['userid']          =       $user->id ;
                $session_data['emailId']   =       $user->email;
                $session_data['isLogin']    =       TRUE ;
                $this->session->set_userdata($session_data);
                return TRUE;
            }
            else{
               return FALSE; 
            }
        }
       return FALSE;
    }//END OF FUNCTION..

    function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = ''){

        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);
        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        $num = $q->num_rows();
        if ($num > 0) {
            return $q->row();
        }
    }
     function insertData($table,$data){
     $this->db->insert($table,$data);
     return $this->db->insert_id();
     }//E

    function common_registration($data,$table){
        //insert Single data by checking uniq  data...
        $email = $data['email'];
        $username = $data['userName'];
        $this->db->select("*");
        $this->db->where('email',$email);
        $this->db->or_where('userName',$username);
        $query = $this->db->get($table);
        if($query->num_rows()>0){
            return FALSE;
        }
        $this->db->insert($table,$data);
        $query = $this->db->insert_id();
        return TRUE;

    }//END OF FUNCTION..

    function registrationData($data,$table){
        //insert Single data by checking uniq  data and send email to verify email id...
        $this->load->library('Smtp_email');
        $email = $data['email'];
        $username = $data['userName'];
        $this->db->select("*");
        $this->db->where('email',$email);
        //$this->db->or_where('userName',$username);
        $query = $this->db->get($table);
        if($query->num_rows()>0){
            return FALSE;
        }
        $this->db->insert($table,$data);
        $query = $this->db->insert_id();
        if($query){
             $select = $this->db->select('*')->where('id',$query)->get($table);
        if($select->num_rows()==1){
            $dataUser = $select->row();
            //if we would want to send email to user for verify email, we can use it.
            $id = md5($dataUser->id);
            $dataSend['name'] = 'Welcome'.' '.$dataUser->fullName;
            $dataSend['message'] = 'Thankyou For Your Registration Please Verify your Email id On click Link Ypur Email Id is'.' '.$data['email'].' '.'is And verification link is:';
            $dataSend['email'] = $data['email'];
           
            $dataSend['link']    = base_url()."admin/genratePassword/".$dataUser->emailLink."/".$id;
            $message = $this->load->view('template/mail',$dataSend,TRUE);
            $subject = "Reset password";
            $this->smtp_email->send_mail($data['email'],$subject,$message);//email sent to user email id..
            return TRUE;
        }else{
            return FALSE;
        }
        }else{
            return FALSE;
        }
    }//END OF FUNCTION..

    //check if given data exists in table
    function is_data_exists($table, $where){
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if($rowcount==0){
            return false;
        }
        else {
            return true;
        }
    }

    function select_row($where,$table){  
    //function for select row from database..
        $this->db->select("*");
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $str = $this->db->last_query(); die;
        if($query->num_rows()==1){
        $user = $query->row();
        return $user;
        }
    }//END OF FUNCTION..

    function select_data($where,$table){  
    //function for select row from database..
        $this->db->select(INVOICE.".*");
        $this->db->select(USERS.".fullName,businessName,uId");
        $this->db->select(LOCATIONS.".locationName");
        $this->db->select(SALESMAN.".name");
        $this->db->join(USERS,"invoices.user_id = users.uId");
        $this->db->join(SALESMAN,"invoices.salesman = salesmans.salesmanId");
        $this->db->join(LOCATIONS,"invoices.location_id = locations.locationId");
        $this->db->where($where);
        $query = $this->db->get($table);
         //echo $str = $this->db->last_query(); die;
        if($query->num_rows()==1){
        $user = $query->row();
        return $user;
        }
    }//END OF FUNCTION..

    function select_result($where,$table){  
    //function for select row from database..
        $this->db->select("*");
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $str = $this->db->last_query(); die;
        if($query->num_rows()>0){
        $user = $query->result();
        return $user;
    }
        return FALSE;
    }//END OF FUNCTION..

    function select_count($table){
        //function for select ALL data from database..
        $this->db->select("*");
        $query = $this->db->get($table);
        $count = $query->num_rows();
        return $count;
        
     }//END OF FUNCTION..

    function insert_data_uniq($data,$table){
        //insert Single data by checking uniq  data...
        $this->db->select("*");
        $this->db->where($data);
        $query = $this->db->get($table);
        if($query->num_rows()>0){
        return FALSE;
        }
        $this->db->insert($table,$data);
        $query = $this->db->insert_id();

        return TRUE;
    }//END OF FUNCTION..

    function insert_data($table,$data){
        //print_r($data);die;
        //insert data without check uniq...
        $this->db->insert($table,$data);
        $query = $this->db->insert_id();
        if($query){
        return TRUE;
        }
        return FALSE;
    }
    function insert_batch($table,$data){
    //insert batch function for insert multiple row data into database.
        $query =$this->db->insert_batch($table,$data);
        return $query;
    }//END OF FUNCTION..

    function update($where,$data,$table){
        $this->db->set($data);
        $this->db->where($where);
        $query = $this->db->update($table);
        //echo $this->db->last_query(); die;
        if(isset($where['userId'])){
        if($query){
            return TRUE;
        }
    }
    return FALSE;  
    }//END OF FUNCTION..



    function updateAdmin($where,$data,$table){
        $this->db->set($data);
        $this->db->where($where);
        $query = $this->db->update($table);
        //echo $this->db->last_query(); die;
        if(isset($where['id'])){
        if($query){
            return TRUE;
        }
    }
    return FALSE;  
    }//END OF FUNCTION.. 
     
    function update_unique($where,$data,$table){
        //update unique data into database..
        $this->db->select("*");
        $this->db->where($data);
        $query = $this->db->get($table);
        if($query->num_rows()>0){
        return FALSE;
        }
        $this->db->set($data);
        $this->db->where($where);
        $query = $this->db->update($table);
        //echo $this->db->last_query();die;
        if($query){
            return TRUE;
        } 
    }//END OF FUNCTION..

    public function delete_data($tblname,$where){
        //delete data..from database.
        $id = $where['id'];
        $this->db->where($where);
        $query = $this->db->delete($tblname); //echo $str = $this->db->last_query(); die;
        if($tblname == subject){
         $this->db->where(array('subject_id'=> $id));
        $delete = $this->db->delete(QUESTIONS);
        if($delete){
        return TRUE;
         }
        }
        if($query){
            return TRUE;
        }
        return FALSE;
    }//END OF FUNCTION..

    function select_post_detail($where, $limit, $offset){
       // pr($where);
        //select jobs data..with multiple images..
       $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_USER;
        $this->db->select('post.postId,post.title,post.description,post.latitude,post.longitude,post.country,post.state,post.address,DATE_FORMAT(`post`.crd, "%d/%m/%Y %H:%m:%s") as crd,comment.commentId,comment.comment,COUNT(DISTINCT `comment`.`commentId`) as comment_count,comment.user_id,category.categoryName,user.fullname,user.userId as user_id,(case 
            when( user.profileImage = "" OR user.profileImage IS NULL) 
            THEN "'.$default.'"
            ELSE
            concat("'.$existThumb.'",user.profileImage) 
            END ) as profile_image,usr.fullname as user_name,like.likeId,COUNT(DISTINCT `like`.`likeId`) as like_count, COALESCE((SELECT likes.status FROM `likes` WHERE `post_id` = `post`.`postId` AND `user_id` = '.$where.'
        ),0) as user_like_status,pm.authorisedToWork,pm.willingToRelocate,pm.whilingToship,pm.email,pm.contact
       ');
        //$this->db->select('CONCAT("'.$existThumb.'",postImage) as post_image');
        $this->db->join('`categories` `category`','category.categoryId = post.category_id','left');
        $this->db->join('`post_permissions` `pm`','pm.post_id = post.postId','left');
        $this->db->join('`users` `user`','user.userId = post.user_id','left');
        
        //$this->db->join('`comments` `commnt`','`commnt`.`user_id`= `user`.`userId`','left');
        $this->db->join('`comments` `comment`','`comment`.`post_id`= `post`.`postId`','left');
        $this->db->join('`users` `usr`','usr.userId  =  comment.user_id' ,'left');
        $this->db->join('`likes` `like`','`post`.`postId` = `like`.`post_id` and like.status = 1','left');
        //$this->db->join('`posts`.`postId` = `comm`.`post_id`','left');
        $this->db->where('post.postId',$where);
        $this->db->group_by('post.postId');
        $this->db->limit($limit, $offset);
        //$this->db->group_by('`comments`.`user_id`');
        $query = $this->db->get('`posts` `post`');//lq();
        //echo $this->db->last_query();die;
        if($query->num_rows()){
          $query->row()->postimage = $this->getImages($where);
            $dat= $this->getTags($where);
            if(empty($dat)){
               $query->row()->tags = array();
            }else{
              $query->row()->tags = $dat;
            }

            $comm= $this->getComments($where, $limit, $offset);
            if(empty($comm)){
               $query->row()->comments = array();
            }else{
              $query->row()->comments = $comm;
            }
          //$query->row()->tags =  $this->getTags($where);
        $user = $query->result();
        if (!empty($user->profileImage) && filter_var($user->profileImage, FILTER_VALIDATE_URL) === false) {
        $user->profileImage = base_url().UPLOAD_FOLDER.'/profile/'.$user->profileImage;
         }
        //pr($user);
         return $user;
        }return FALSE;
    }//END OF FUNCTION..

    function getImages($where){//select multiple images..
      $existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $query = $this->db->select('CONCAT("'.$existThumb.'",attachmentName) as post_image')->where('post_id',$where)->get(POST_ATTACHMENTS);
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..


    function getTags($where){//select multiple images..
      //$existThumb= base_url().UPLOAD_FOLDER.'/postImages/';
      $this->db->select(TAGS_MAPPING.'.tag_id');
      $this->db->select(TAGS.'.tagName');
      $this->db->join(TAGS,'tags_mapping.tag_id = tags.tagId');
       $this->db->where('post_id',$where);
      $query =  $this->db->get(TAGS_MAPPING);
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function getComments($where){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_USER;
      $query = $this->db->select('comment.comment,comment.user_id,user.fullname,concat("'.$existThumb.'",user.profileImage) as profile_image')
      ->join('`users` `user`','user.userId = comment.user_id','left')
      ->
      where('post_id',$where)
      ->get('`comments` `comment`', 5);
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

    function select_comment_on_post($where){//select multiple images..
        $existThumb= base_url().UPLOAD_FOLDER.'/profile/';
        $default   = base_url().DEFAULT_USER;
      $query = $this->db->select('comment.comment,comment.user_id,user.fullname,concat("'.$existThumb.'",user.profileImage) as profile_image')
      ->join('`users` `user`','user.userId = comment.user_id','left')
      ->
      where('post_id',$where)
      ->get('`comments` `comment`');
      if($query->num_rows()){
        $result = $query->result();
        return $result;
      }else{
        return FALSE;
      }
    }//end of function..

     function getAll($table, $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='') {
        $data = array();
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }
        $this->db->from($table);

        $clone_db = clone $this->db;
        $total_count = (int) $clone_db->get()->num_rows();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count,'result' => $data);
    }


}//END OF CLASS..
