<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CommonBack_controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
         $this->check_admin_user_session();
    }

    public function index(){//INDEX FUNCTION.
           //$this->check_user_session();
           $this->load->view('login');   
    }//END OF FUNCTION

   /* public function registerView(){//Registration Form view FUNCTION.
           $this->load->view('register');   
    }//END OF FUNCTION */

    function aboutUs(){
        $session = $this->check_admin_user_session();
        $data['title'] = "About Us";
        $where = array('id'=>$session);
        $table = 'admin';
        $data['admin'] = $this->common_model->select_row($where,$table);
        //get polocy content and set to key
        $data['content'] = $this->common_model->getsingle(OPTIONS,array('option_name'=>'about_us'),'option_value');
        //print_r($data['content']);die; 
        $this->load->admin_render('aboutUs', $data, '');
    }

    function terms_web(){
        $session = $this->check_admin_user_session();
        $data['title'] = "Terms&Condition";
        $where = array('id'=>$session);
        $table = 'admin';
        $data['admin'] = $this->common_model->select_row($where,$table);
        //get polocy content and set to key
        $data['content'] = $this->common_model->getsingle(OPTIONS,array('option_name'=>'terms_web'),'option_value');
        //pr($data['content']);die; 
        $this->load->admin_render('termsWeb', $data, '');
    }
    //end of function 

    //update About Us
    function aboutUsSub(){
        //set form validation
        $this->form_validation->set_rules('content','About Us','trim|required');
        //set msg if validation fail
        if($this->form_validation->run() == FALSE){
            $response = array('status' => 0, 'message' => strip_tags(validation_errors()));
            echo json_encode($response);die;
        }else{
            //set data for insert or update
            $data['option_name']  = 'about_us';
            $data['option_value'] =  $this->input->post('content');
            //check if data alredy exist or not
            $exist = $this->common_model->is_data_exists(OPTIONS, array('option_name'=>$data['option_name']));
           if(!$exist){
                //if not exist insert data
                $result = $this->common_model->insertData(OPTIONS,$data);
                if(!$result){
                    //check if data not save set msg 
                    $response = array('status' => 0, 'message' => 'Something went wrong. Please try again','url' => base_url('admin/aboutUs')); 
                    echo json_encode($response);die;
                }
                //set msg for success
                $response = array('status' => 1, 'message' => 'About Us Content save successfully','url' => base_url('admin/aboutUs')); 
                echo json_encode($response);die;
            }else{
                //if data exist in table update field
                $result = $this->common_model->updateFields(OPTIONS,array('option_name'=>'about_us'),$data); 
                //print_r($result);die; 
                //set msg for data updated successfully
                $response = array('status' => 1, 'message' => 'About Us Content updated successfully','url' => base_url('admin/aboutUs')); 
                echo json_encode($response);die;
            } 
        }
    }


    function abouTCweb(){
        //set form validation
        $this->form_validation->set_rules('content','Terms & Condition','trim|required');
        //set msg if validation fail
        if($this->form_validation->run() == FALSE){
            $response = array('status' => 0, 'message' => strip_tags(validation_errors()));
            echo json_encode($response);die;
        }else{
            //set data for insert or update
            $data['option_name']  = 'terms_web';
            $data['option_value'] =  $this->input->post('content');
            //check if data alredy exist or not
            $exist = $this->common_model->is_data_exists(OPTIONS, array('option_name'=>$data['option_name']));
           if(!$exist){
                //if not exist insert data
                $result = $this->common_model->insertData(OPTIONS,$data);
                if(!$result){
                    //check if data not save set msg 
                    $response = array('status' => 0, 'message' => 'Something went wrong. Please try again','url' => base_url('admin/terms_web')); 
                    echo json_encode($response);die;
                }
                //set msg for success
                $response = array('status' => 1, 'message' => 'Terms & Condition Content save successfully','url' => base_url('admin/terms_web')); 
                echo json_encode($response);die;
            }else{
                //if data exist in table update field
                $result = $this->common_model->updateFields(OPTIONS,array('option_name'=>'terms_web'),$data); 
                //print_r($result);die; 
                //set msg for data updated successfully
                $response = array('status' => 1, 'message' => 'About Us Content updated successfully','url' => base_url('admin/terms_web')); 
                echo json_encode($response);die;
            } 
        }
    }
    //front end banner data save/update

    //policy
    function policy(){
        $session = $this->check_admin_user_session();
        $data['title'] = "Policy";
        $data['parent'] = "Policy";
        $where = array('id'=>$session);
        $table = 'admin';
        $data['admin'] = $this->common_model->select_row($where,$table);
       //set title
        $where = array("option_name"=>"policy");
        $data['content'] =$this->common_model->getsingle(OPTIONS, $where,'option_value');
        //print_r($data['content']);die;
        $this->load->admin_render('policy', $data, '');
    }
    //end of function 

    //update policy
    function policySub(){
       //set form validation
        if (empty($_FILES['pdf']['name'])) {

            $this->form_validation->set_rules('pdf', 'File', 'required');
            if ($this->form_validation->run() == FALSE){ 
                $messages = (validation_errors()) ? validation_errors() : '';
                $response = array('status' => 0, 'message' => $messages); 
                echo json_encode($response); die; 
            }
        }else{

            $data = array();//upload pdf
            if (!empty($_FILES['pdf']['name'])) {
                $this->load->model('Image_model');
                $folder     = 'content/term_condition';
                $image = $this->Image_model->uploadPdf('pdf',$folder,FALSE); //upload pdf 
            }
            //check for error in uploads
            if(array_key_exists("error",$image) && !empty($image['error'])){
                $response = array('status' => 0, 'message' =>$image['error']); 
                echo json_encode($response); die;   
            }   
            //set pdf name in data
           if(array_key_exists("pdf_name",$image)){

                $data['option_value'] = $image['pdf_name'];
                $data['option_name']  = "policy";   
            }
            //set where 
            $where = array("option_name"=>"policy");
            //check for data exist
            $exits = $this->common_model->getsingle(OPTIONS, $where,'option_value');
            if($exits){
                //update if data exist
                $update = $this->common_model->updateFields(OPTIONS, $where, $data);
                //print_r($update);
                //$this->Image_model->unlinkFile(TERM,$exits->content);
                $response = array('status' => 1, 'message' => 'Successfully Updated', 'url' => base_url('admin/policy'));
            }else{
                //insert if data not exist
                 $this->common_model->insertData(OPTIONS, $data);
                $response = array('status' => 1, 'message' => 'Successfully Added', 'url' => base_url('admin/policy'));
            }
        }
        echo json_encode($response); die;
    }//END OF FUNCTION

     //termand condition
    function termAndCondition(){
        $session = $this->check_admin_user_session();
        $data['title'] = "Term & Condition";
        $where = array('id'=>$session);
        $table = 'admin';
        $data['admin'] = $this->common_model->select_row($where,$table);
       //set title
        $where = array("option_name"=>"term_and_condition");
        $data['content'] =$this->common_model->getsingle(OPTIONS, $where,'option_value');
        $this->load->admin_render('term_and_condition', $data, '');
    }
    //end of function 

    //update term & condition
    function updateTermAndCondition(){
       //set form validation
        $this->load->model('Image_model');
        if (empty($_FILES['pdf']['name'])) {

            $this->form_validation->set_rules('pdf', 'File', 'required');
            if ($this->form_validation->run() == FALSE){ 
                $messages = (validation_errors()) ? validation_errors() : '';
                $response = array('status' => 0, 'message' => $messages); 
                echo json_encode($response); die; 
            }
        }else{

            $data = array();//upload pdf
            if (!empty($_FILES['pdf']['name'])) {
                $this->load->model('Image_model');
                $folder     = 'content/term_condition';
                $image = $this->Image_model->uploadPdf('pdf',$folder,FALSE); //upload pdf 
            }
            //check for error in uploads
            if(array_key_exists("error",$image) && !empty($image['error'])){
                $response = array('status' => 0, 'message' =>$image['error']); 
                echo json_encode($response); die;   
            }   
            //set pdf name in data
           if(array_key_exists("pdf_name",$image)){

                $data['option_value'] = $image['pdf_name'];
                $data['option_name']  = "term_and_condition";   
            }
            //set where 
            $where = array("option_name"=>"term_and_condition");
            //check for data exist
            $exits = $this->common_model->getsingle(OPTIONS, $where,'option_value');
            if($exits){
                //update if data exist
                $update = $this->common_model->updateFields(OPTIONS, $where, $data);
                //print_r($update);
                //$this->Image_model->unlinkFile(TERM,$exits->content);
                $response = array('status' => 1, 'message' => 'Successfully Updated', 'url' => base_url('admin/termAndCondition'));
            }else{
                //insert if data not exist
                 $this->common_model->insertData(OPTIONS, $data);
                $response = array('status' => 1, 'message' => 'Successfully Added', 'url' => base_url('admin/termAndCondition'));
            }
        }
        echo json_encode($response); die;
    }//END OF FUNCTION


    public function login(){  //LOGIN FUNCION..

        $res =array();
        $this->form_validation->set_rules('userName', 'Username/Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){
                $res['messages'][$key] = form_error($key);
            }//foreach end..
        }
        else{ 
            $password        =        $this->input->post('password');
            $userName        =        $this->input->post('userName');
            $email = $userName;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $where = array('userName'=>$email);
            }else{
                 $where = array('email'=>$email);
            }
            $isLogin = $this->common_model->isLogin($where,$password, ADMIN);
           
            if($isLogin == TRUE){

               $res['messages']['success']  =  ResponseMessages::getStatusCodeMessage(106);
            }else{
                $res['messages']['unsuccess']  = ResponseMessages::getStatusCodeMessage(105);
            }
    }
    echo !empty($res) ?json_encode($res): redirect('admin'); //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX.
    }//END OF FUNCTION

    public function dashboard(){//Dashboard View Function FUNCTION.
            $data['parent'] = "Dashboard";
            $data['title'] = "Dashboard";
            $table = ADMIN;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $tableData = USERS;
            $data['admin'] = $this->common_model->select_row($where,$table);
           $this->load->admin_render('dashboard',$data,'');   
    }//END OF FUNCTION

    function logout($is_redirect=TRUE){
        //$this->session->unset_userdata($this->user_session_key); // instead of destroying whole session data, we will just unset biz user session data
        $is_redirect =  $this->admin_logout();
        if($is_redirect)
            redirect('admin/login');  //redirect only when $is_redirect is set to TRUE
    }

    
    public function profileView() {//profile view function.
            $data['parent'] = "Profile";
            $data['title'] = "Profile";
            $table = ADMIN;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('profile',$data,'');
           
    }//END OF FUNCTION 

    public function editSubmit() { //EDIT PROFILE SUBMIT UPDATE
        $res = array();
        $this->form_validation->CI =& $this;
        $whereAdmin =  array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
        $selectEmail = $this->common_model->select_row($whereAdmin,ADMIN);
        $emails = strtolower($this->input->post('email'));
        /*if(strtolower($selectEmail->email) != $emails){
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');
        }*/
        $this->form_validation->set_rules('fullName', 'Full Name', 'trim|required|callback__alpha_spaces_check');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){
                $res['messages'][$key] = form_error($key);
            }//foreach end..
        }else{
        $response = array();
        $photo = '';
        $pic = $_FILES['photo']['name'];
        if(!empty($pic)){ 
            $this->load->model('image_model');   
            $upload                      =   $_FILES['photo']['name'];
            $imageName                   =   'photo';
            $folder                      =   "profile";
            $response                    =   $this->image_model->updateMedia($imageName,$folder);
            //IMAGE UPLOAD.
        }
        if(!empty($response) && is_array($response)){
                    $image = 0;
                    $res['messages']['imageerror'] = $response['error'];
        }else{
            $dataUpdate = array();
            $image                      =   isset($response) ? ($response):"";
            $table                      =   ADMIN;
            $where                      =   array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $dataUpdate['fullName']     =   $this->input->post('fullName');
            $dataUpdate['upd']          =   date("Y-m-d H:m:s");
            if(!empty($image)){
                $dataUpdate['profileImage'] =   $image;
            }

            $update                     =   $this->common_model->updateAdmin($where,$dataUpdate,$table); //UPDATE DATABASE.
            if($update == TRUE){
                $res['messages']['success'] = ResponseMessages::getStatusCodeMessage(108);
            }else{  
                $res['messages']['unsuccess'] = ResponseMessages::getStatusCodeMessage(144);
            } 
        }
        }
        echo json_encode($res);
    }//END OF FUNCTION

    function changePassword(){ //Chnage Password SUBMIT FUNCTION.. 
        $res = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]',array('required'=>'Please enter current password','min_length'=>'Password Should be atleast 6 Character Long'));
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[rnpassword]|min_length[6]',array('required'=>'Please enter new password','min_length'=>'Password Should be atleast 6 Character Long','matches'=>'New Password does not match with retype password'));
        $this->form_validation->set_rules('rnpassword', 'retype new password ', 'trim|required|min_length[6]',array('required'=>'Please retype new password','min_length'=>'Password Should be atleast 6 Character Long'));
        $this->form_validation->set_error_delimiters('<div class="err_msg">', '</div>');
        if ($this->form_validation->run() == FALSE)
        { 
            foreach($_POST as $key =>$value){
            $res['messages'][$key] = form_error($key);
            }
        }else 
        {
            $password =$this->input->post('password');
            $npassword =$this->input->post('npassword');
            $table  = ADMIN;
            $select = "password";
            $where =  array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $admin = $this->common_model->customGet($select,$where,$table); // password with select from here to check old passwod
            $passwordc = $admin->password;
            $passwordVerfied = password_verify($password, $passwordc); //verified password here. 
            if($passwordVerfied){
                $newPassword = password_hash($this->input->post('npassword') , PASSWORD_DEFAULT);//password hash encrypt.
                $data =array('password'=> $newPassword); 
                $update = $this->common_model->updateAdmin($where, $data, $table);
                if($update){
                    $res = array();
                    if($update){
                        $res['messages']['success']= ResponseMessages::getStatusCodeMessage(140); //UPDATE SUCCESSFULLY
                    }
                    else{
                    $res['messages']['failed']='Failed! Please try again'; //ERROR NOT UPDATE
                    }
                } 
            }else{
                $res['messages']['oldPaas']= ResponseMessages::getStatusCodeMessage(141); //ERROR FOR OLD PASSWORD WRONG
            }
        }
    echo json_encode($res); //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX. 
    }//END OF FUNCTION

    public function forgetPassword() { //Forget Password Function.. Send Link by using this function.
            $res = array();
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            if ($this->form_validation->run() == FALSE){
                $res['messages']['email'] = form_error('email');
            } //END OF FORM VALIDATION IF
        else{
            $dataSet['email'] = $this->input->post('email');
            $table = ADMIN;
            $response = $this->common_model->forgetPassword($table,$dataSet);
            if($response == TRUE){
                $res['messages']['success']   =   ResponseMessages::getStatusCodeMessage(120);
           }else{
                $res['messages']['unsuccess'] = ResponseMessages::getStatusCodeMessage(143);
           }
        }
    echo json_encode($res);   
    }//END OF FUNCTION 

    public function forgetPassView() {//profile view function.
            $data['parent'] = "Forget";
            $data['title'] = "Forget";
            $this->load->view('forgetPass',$data,'');
           
    }//END OF FUNCTION 
    
    public function setPassword($data="") { //SET PASSWORD VIEW FUNCTION
            $table= ADMIN;
            $where = array('forgetPass'=> $data);
            $dataUser['admin'] = $this->common_model->select_row($where,$table);
            $this->load->view('setPassword',$dataUser);
           
    }//END OF FUNCTION 

    public function deleteImg() { //DELETE IMAGE FUNCTION
        $this->load->model('image_model');
            $image = $this->input->post('image');
            $id = $this->input->post('id');
            $path = "uploads/profile/";
            $response = $this->image_model->unlinkFile($path,$image); 
        if($response == TRUE){
            $where =  array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data = array('profileImage'=>'');
            $table = ADMIN;
            $this->common_model->update($where,$data,$table);
            $error['success']="Profile Image Deleted";
        }
        echo json_encode($error);   //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX.
    }//END OF FUNCTION

    public function setPassReset() {
        $res = array();
        $session = $this->check_admin_user_session(); 
         if(!empty($session))
            redirect(site_url().'admin/dashboard');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){
                $res['messages'][$key] = form_error($key);
            }//foreach end..
        } //END OF FORM VALIDATION IF
        else{
            $dataSet['password'] = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
            $dataSet['forgetPass'] = '';
            $dataSet['upd'] = date("Y-m-d H:m:s");
            //$dataSet['emailLink'] = '';
            $table = ADMIN;
            $where =  array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $response = $this->common_model->updateAdmin($where,$dataSet,$table);
            if($response == TRUE){
                $res['messages']['success'] = ResponseMessages::getStatusCodeMessage(145);
            }else{
                $res['messages']['unsuccess'] = ResponseMessages::getStatusCodeMessage(146);
            }
        }
        echo json_encode($res);     
    }//END OF FUNCTION

    function _alpha_spaces_check($string){
    if(alpha_spaces($string)){
      return true;
    }
    else{
      $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
      return FALSE;
    }
    }

    function activeInactive(){
        $data['id'] =  decoding($this->input->post('id'));

        $table = $this->input->post('table');  
        $column = $this->input->post('id_name');  

        $status = $this->Users_model->activeInactive($table,$data,$column);

        if($status == TRUE){
            $data['messages']['activated'] = 'activated successfully';
        }else{
            $data['messages']['inactivated'] ='inactivated successfully';
        }
        echo json_encode($data);
    }

    function deleteData(){
        $data['id'] =  decoding($this->input->post('id'));
        $table = $this->input->post('table');  
        $column = $this->input->post('id_name');  

        $status = $this->Users_model->deleteData($table,$data,$column);

        if($status == TRUE){
            $data['messages']['delete'] = 'Delete Successfully';
        }else{
            $data['messages']['notDelete'] ='Not Deleted';
        }
        echo json_encode($data);
    }
   
}//END OF CLASS
