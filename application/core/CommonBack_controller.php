<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . "third_party/MX/Controller.php";

class CommonBack_controller extends MX_Controller {

    public $filedata = "";

  public function __construct(){
        parent::__construct();
        $this->admin_user_session_key = ADMIN_USER_SESS_KEY; //user session key
        $this->tbl_users = ADMIN; //admin table
    }
    
    /**
     * Admin session authentication for pages
     * Added in ver 2.0
     */
    public function check_admin_user_session(){

        $page_slug = $this->router->fetch_method();
        $allowed_pages = array('index','login','forgetPassword','forgetPassView','setPassword','setPassReset'); //these pages/methods do not require user authentication
        $allowed_control = 'admin'; //methods of this controller does not require authentication
        $current_control = $this->router->fetch_class(); // get current controller, class = controller
       
        if(!is_admin_logged_in() && (in_array($page_slug,$allowed_pages)) && $current_control == $allowed_control){
            return TRUE; //session is empty and pages is not restricted
        }else{
            //either page is resticted or session exist
            
            if(!is_admin_logged_in()){
                redirect('admin'); //redirect to home/login if session not exit
            }
            
            //user session exists
            $user_sess_data = $_SESSION[ $this->admin_user_session_key ]; //user session array
            $session_u_id = $user_sess_data['id']; //user ID
            $where = array('id'=>$session_u_id,'status'=>1); //status:0 means active 
            $check = $this->common_model->is_data_exists($this->tbl_users,$where);

            if($check === FALSE){
               //user is either deleted or is inactivated
               $this->logout(); //force logout
            }
            
            if(empty($page_slug)){
               return TRUE; //if slug is empty and session is set
            }
            
             $after_auth = array('index','login','forgetPassword','forgetPassView','setPassword','setPassReset');  //restrict access to these pages if session is set
            if(in_array($page_slug,$after_auth) && $current_control == $allowed_control){
                redirect('admin/dashboard');
            }else{
                return TRUE; 
            }
            
        } 
    }
    
    /**
     * Admin user logout
     * Added in ver 2.0
     */
    function admin_logout($is_redirect=TRUE){
        
        // instead of destroying whole session data, we will just unset biz user session data
        unset($_SESSION[$this->admin_user_session_key]); 
        if($is_redirect)
            redirect('admin');  //redirect only when $is_redirect is set to TRUE
    }
    
    /**
     * Admin authentication for ajax
     * Added in ver 2.0
     */
    public function check_admin_ajax_auth(){
       
        $failed_res = json_encode(array('status'=> -1,'msg'=>'Your session expired. Please login again.','url'=>base_url('admin')));
        if(!is_admin_logged_in()){
            echo $failed_res; exit;
        }

        //user session exists
        $user_data = get_admin_session_data();
        $where = array('id'=>$user_data['id'],'status'=>0); //status:0 means active 
        $check = $this->common_model->is_data_exists($this->tbl_users,$where);

        if($check===FALSE){
           //user is either deleted or is inactivated
           $this->logout(FALSE); //force logout- $is_redirect is FALSE here because we will redirect user from JS
           echo $failed_res; exit;
        }

        return TRUE; //all good
    }

     public function is_auth_admin() {
        if (!$this->ion_auth->logged_in()) {
             $this->session->set_flashdata('message', 'Your session has been expired');
             redirect('admin/login', 'refresh');
        } else {
            if (!$this->ion_auth->is_admin()) {
                $this->session->set_flashdata('message', 'You are not authorised to access administration');
                redirect('admin/login', 'refresh');
            }
        }
    }
    public function uploadImage($data = '', $folder = '') {

        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "5048",
            'max_height' => "2048",
            'max_width' => "2048",
            'file_name' => time() . "_" . $_FILES['file_name']['name']
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file_name')) {
            $this->filedata = array('upload_data' => $this->upload->data());
            $this->filedata['status'] = 1;
            return $this->filedata;
        } else {
            $this->filedata = array('error' => $this->upload->display_errors());
            $this->filedata['status'] = 0;
            return $this->filedata;
        }
    }
    public function uploadImageThumb($folder = '', $height = '', $width = '', $i) {
        $_FILES['assetimage[]']['name'] = $_FILES['assetimage']['name'][$i];
        $_FILES['assetimage[]']['tmp_name'] = $_FILES['assetimage']['tmp_name'][$i];
        $_FILES['assetimage[]']['type'] = $_FILES['assetimage']['type'][$i];
        $_FILES['assetimage[]']['size'] = $_FILES['assetimage']['size'][$i];
        $_FILES['assetimage[]']['error'] = $_FILES['assetimage']['error'][$i];
        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "5048",
            'max_height' => "2048",
            'max_width' => "2048",
            'min_height' => $height,
            'min_width' => $width,
            'file_name' => time() . "_" . $_FILES['assetimage[]']['name']
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('assetimage[]')) {
            $this->filedata = array('upload_data' => $this->upload->data());
            $this->filedata['status'] = 1;
            $this->resizeImageBig($this->upload->data());
            $this->resizeImageSmall($this->upload->data());
            return $this->filedata;
        } else {
            $this->filedata = array('error' => $this->upload->display_errors());
            $this->filedata['status'] = 0;
            return $this->filedata;
        }
    }
    public function commonUploadImage($data = '', $folder = '', $file_name = '') {

        $this->load->library('upload');
        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "10000000",
            'max_height' => "4048",
            'max_width' => "4048",
            'file_name' => time() . "_" . $_FILES[$file_name]['name']
        );
        $this->upload->initialize($config);
        if ($this->upload->do_upload($file_name)) {
            $this_filedata = array('upload_data' => $this->upload->data());
            $this_filedata['status'] = 1;
            return $this_filedata;
        } else {
            $this_filedata = array('error' => $this->upload->display_errors());
            $this_filedata['status'] = 0;
            return $this_filedata;
        }
    }
    public function resizeImageBig($image_data = '') {

        $this->load->library('image_lib');

        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['new_image'] = './uploads/assets/500/' . $image_data['file_name'];
        $config['width'] = 500;
        $config['height'] = 350;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        return true;
    }
    public function resizeImageSmall($image_data = '') {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['new_image'] = './uploads/assets/150/' . $image_data['file_name'];
        $config['width'] = 150;
        $config['height'] = 150;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        return true;
    }
    
    public function image_unlink($filename, $filepath) {

        $file_path_name = $filepath . $filename;
        // print_r($file_path_name);die;
        if (file_exists($file_path_name)) {

            if (unlink($file_path_name)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function send_email($to, $from, $subject, $template, $title) {
        $this->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        $this->email->from($from, $title);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($template);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
    function send_email_smtp($to, $from, $subject, $template, $title) {

        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = 'pawan.mobiwebtech@gmail.com';
        $config['smtp_pass'] = '********';
        $config['charset'] = 'iso-8859-1';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
        $config['validation'] = TRUE;
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($from, $title);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($template);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
    public function delete() {
        $response = "";
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        if (!empty($table) && !empty($id) && !empty($id_name)) { 
            $where = array($id_name => $id);
            $delete = $this->common_model->deleteData($table, $where);
            if ($delete) {
                $response = 200;
            }else
                $response = 400;
            }else {
                $response = 400;
            }
        echo $response;
    }

    public function delete_role() {
        $response = "";
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        if (!empty($table) && !empty($id) && !empty($id_name)) {
         $option = array(
                'table' => USER_GROUPS,
                'select'=>'group_id',
                'where' => array('group_id' => $id)
            );
         $role_assign = $this->common_model->customGet($option);
        if(empty($role_assign)){
            $option = array(
                'table' => $table,
                'where' => array($id_name => $id)
            );
            $delete = $this->common_model->customDelete($option);
            if ($delete) {
                $response = 200;
            } 
         }else
                $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
    }

    public function status() {
        $response = "";
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        $status = $this->input->post('status');
        if (!empty($table) && !empty($id) && !empty($id_name)) {
           
            $data = array('status' => ($status == 1) ? 0 : 1);
            $where = array($id_name => $id);
            
            $update = $this->common_model->updateFields($table,$data,$where);
            if ($update) {
                $response = 200;
            } else
                $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
    }

    public static function seo_url($string) {
        $str = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $string); // Removes special chars.
        $dbl = str_replace('  ', '-', strtolower($str));
        return str_replace(' ', '-', strtolower($dbl));
    }

    public function adminIsAuth() {
        if (!$this->ion_auth->is_admin()) {
            redirect('admin/login', 'refresh');
        } else {
            return true;
        }
    }

    public function get_config($key) {
        $option = array('table' => SETTING,
            'where' => array('option_name' => $key, 'status' => 1),
            'single' => true,
        );
        $is_result = $this->common_model->customGet($option);
        if (!empty($is_result)) {
            return $is_result->option_value;
        } else {
            return false;
        }
    }

    function clear_strip($var) {
        $s = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $var);
        return strip_tags(trim($s));
    }

    function clear_url_rewrite($var) {
        $s = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $var);
        $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', strip_tags(trim($s)));
        return preg_replace('/\s+/', ' ', $string);
    }
   
}
