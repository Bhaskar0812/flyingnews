<?php

/**
* Handles video upload and resizing
* version: 2.0 (14-08-2018)
*/

class Upload_media_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('string');
    }

    /**
     * Upload video and create resized copies
     * Modified in ver 2.0
     */
    function upload_video($video, $folder, $path=FALSE ){
       // print_r($video);die;
        $this->make_dirs($folder);
        
       	$realpath = $path ?'../uploads/':'uploads/';
        $allowed_types = 'mp4|3gp';
        $video_name = random_string('alnum', 16);  //generate random string for image name
        
        $config = array(
                'upload_path'       => $realpath.$folder,
                'allowed_types'     => $allowed_types,
                'max_size'          => "1000480",   // File size 204.8 limitation, initially w'll set to 10mb (Can be changed)
                'file_name'         => $video_name,
                'overwrite'	    => FALSE,
                'remove_spaces'	    => TRUE,
                'quality'           => '100%',
            );
    	//print_r($config);die;
		
        $this->load->library('upload'); //upload library
        $this->upload->initialize($config);
 
        if(!$this->upload->do_upload($video)){
            $error = array('error' => $this->upload->display_errors());
            return $error; //error in upload

        }
        
        //video uploaded successfully - proceed to create resized copies
        $video_data = $this->upload->data(); //get uploaded data
        $this->load->library('image_lib'); //image library

        
        $real_path = realpath(FCPATH .$realpath .$folder);
        $resize['source_image'] = $video_data['full_path'];
        $resize['maintain_ratio'] = FALSE;
        $resize['quality'] = '100%';

        $this->image_lib->initialize($resize);
        $this->image_lib->resize();
        $this->image_lib->clear(); //clear memory

        $thumb_img = $video_data['file_name'];
        return $thumb_img;
        // return array('video_name'=>$thumb_img);
        
    } 


    /**
     * Make upload directory
     * Modified in ver 2.0
     */
    function make_dirs($folder='', $mode=DIR_WRITE_MODE, $defaultFolder='uploads/'){

        if(!@is_dir(FCPATH . $defaultFolder)){
            mkdir(FCPATH . $defaultFolder, $mode);
        }
        
        if(!empty($folder)){

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }
    
    //not used in new version -- kept for backward compatibility
    function makedirsBk($folder='', $mode=DIR_WRITE_MODE, $defaultFolder='../uploads/'){

        if(!@is_dir(FCPATH . $defaultFolder)){

            mkdir(FCPATH . $defaultFolder, $mode);
        }
        
        if(!empty($folder)){

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }

    //delete(unlink) video from folder/server
    function delete_media($path,$file){
    
        $main = $path.$file;
        
        if(file_exists(FCPATH.$main)):
            unlink( FCPATH.$main);
        endif;
        return TRUE;
    }


}    