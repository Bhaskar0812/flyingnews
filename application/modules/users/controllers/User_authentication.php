<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_authentication extends CommonBack_controller {

	public function setPasswordUser() { //SET PASSWORD VIEW FUNCTION.
        $table= USERS;
        $token = $_GET['token'];
        $userId = decoding($_GET['userid']);
        $where = array('forgetPass'=> $token,'userId'=>$userId);
        $dataUser['title']='Forget Password';
        $dataUser['admin'] = $this->common_model->select_row($where,$table); 
        if(!empty($dataUser['admin'])){
         $this->load->front_render('forgetPassword',$dataUser);
        }else{
           
            $this->session->set_flashdata('error',"Link has been expired.");
            redirect("admin");
        }
            
    }//END OF FUNCTION 

    public function passwordSet($data="") { //SET PASSWORD VIEW FUNCTION.
            $table= USERS;
            $where = array('forgetPass'=> $data);
            $dataUser['admin'] = $this->common_model->select_row($where,$table);
             $this->load->view('passwordSuccess',$dataUser);
           

    }//END OF FUNCTION 

    public function setPassReset() {
        $res = array();
        //pr($_POST);
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE){
            $requireds = strip_tags($this->form_validation->error_string()) ? strip_tags($this->form_validation->error_string()) : ''; //validation 
            $response = array('status' => FAIL, 'messages' => $requireds ,'csrf'=>get_csrf_token()['hash']); 
        } //END OF FORM VALIDATION IF
        else{
            
            $dataSet['password'] = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
            $dataSet['forgetPass'] = '';
            $dataSet['upd'] = date("Y-m-d H:m:s");
            //$dataSet['emailLink'] = '';
            $table = USERS;
            $where = array('userId' => $this->input->post('id'));
            $response = $this->common_model->update($where,$dataSet,$table);
          
            if($response == TRUE){
                $response = array('status'=>SUCCESS,'messages'=>ResponseMessages::getStatusCodeMessage(145),'csrf'=>get_csrf_token()['hash']);
            }else{
                $response = array('status'=>FAIL,'messages'=>ResponseMessages::getStatusCodeMessage(146),'csrf'=>get_csrf_token()['hash']);
              
            }
        }
        echo json_encode($response);     
    }//END OF FUNCTION
	
}
