<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imp_email{

	protected $CI;
	public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
    }

    public function send_mail($to,$subject,$message)
    {
		$config = Array(
		    'protocol'  => 'http',
		    'smtp_host' => 'gmail.com',
		    'smtp_port' => 25,
		    'smtp_user' => 'bhaskarsharmamanish@gmail.com',
		    'mailtype'  => 'html',
		    'charset'   => 'iso-8859-1',
		    'wordwrap'  => TRUE,
		    'smtp_timeout'  => 600
		);
			        
		//$message = $this->load->view('email/reset_password', $data, true);
		$this->CI->load->library('email', $config);
		$this->CI->email->set_newline("\r\n");
		$this->CI->email->from('bhaskarsharmamanish@gmail.com'); // change it to yours
		$this->CI->email->to($to);// change it to yours
		$this->CI->email->subject($subject);
	 	$this->CI->email->message($message);
		if($this->CI->email->send()){
			return TRUE;
		}
		else{
			
			return FALSE;
		}
    }
    
}