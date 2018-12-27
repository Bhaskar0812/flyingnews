<?php

class Image_model extends CI_Model
{
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

    function makedirsBk($folder='', $mode=DIR_WRITE_MODE, $defaultFolder='../uploads/'){

        if(!@is_dir(FCPATH . $defaultFolder)) {

            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if(!empty($folder)) {

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }//End Function
    
    function updateMedia($image,$folder,$hieght=600,$width=600,$path=FALSE){
    	//log_event($image);
    	$this->load->helper('string');
       $this->make_dirs($folder);
        
       	$realpath = $path ?'../uploads/':'uploads/';
        $allowed_types = "jpg|png|jpeg"; 	
        $img_name = random_string('alnum', 16);  //generate random string for image name
        
        $img_sizes_arr = $this->image_sizes($folder);  //predefined sizes in model
       
        //We will set min height and width according to thumbnail size
        if($image != 'video_thumb'){
        $min_width = $img_sizes_arr['medium']['width'];
        $min_height = $img_sizes_arr['medium']['height'];
    	}else{
    		$min_width =  '';
    		$min_height = '';
    	}
                
        $config = array(
                'upload_path'       => $realpath.$folder,
                'allowed_types'     => $allowed_types,
                'max_size'          => "10240",   // File size limitation, initially w'll set to 10mb (Can be changed)
                'max_height'        => "4000", // max height in px
                'max_width'         => "4000", // max width in px
                'min_width'         => $min_width, // min width in px
                'min_height'        => $min_height, // min height in px
                'file_name'         => $img_name,
                'overwrite'	    => FALSE,
                'remove_spaces'	    => TRUE,
                'quality'           => '100%',
            );
		
        $this->load->library('upload'); //upload library
        $this->upload->initialize($config);
 
        if(!$this->upload->do_upload($image)){
            $error = array('error' => $this->upload->display_errors());
            return $error; //error in upload

        }
        
        //image uploaded successfully - We will now resize and crop this image
        
        $image_data = $this->upload->data(); //get uploaded image data
        $this->load->library('image_lib'); //Load image manipulation library
        $thumb_img = '';

        foreach($img_sizes_arr as $k=>$v){
            
            // create resize sub-folder
            $sub_folder = $folder.$v['folder'];
            $this->make_dirs($sub_folder);

            $real_path = realpath(FCPATH .$realpath .$folder);

            $resize['image_library']      = 'gd2';
            $resize['source_image']       = $image_data['full_path'];
            $resize['new_image'] 	      = $real_path.$v['folder'].'/'.$image_data['file_name'];
            $resize['maintain_ratio']     = TRUE; //maintain original image ratio
            $resize['width'] 	      	  = $v['width'];
            $resize['height'] 	          = $v['height'];
            $resize['quality'] 	          = '100%';
            // We need to know whether to use width or height edge as the hard-value. 
            // After the original image has been resized, either the original image width’s edge or 
            // the height’s edge will be the same as the container
            $dim = (intval($image_data["image_width"]) / intval($image_data["image_height"])) - ($v['width'] / $v['height']);
            $resize['master_dim'] = ($dim > 0)? "height" : "width";

            $this->image_lib->initialize($resize);
            $is_resize = $this->image_lib->resize();   //create resized copies
            
            //image resizing maintaining it's aspect ratio is done. Now we will start cropping the resized image
            $source_img = $real_path.$v['folder'].'/'.$image_data['file_name'];
            
            if($is_resize && file_exists($source_img)){
                
                $source_image_arr = getimagesize($source_img);
                $source_image_width = $source_image_arr[0];
                $source_image_height = $source_image_arr[1];
                
                $source_ratio = $source_image_width / $source_image_height;
                $new_ratio = $v['width'] / $v['height'];
                
                if($source_ratio != $new_ratio){
                    
                    //image cropping config
                    $crop_config['image_library'] = 'gd2';
                    $crop_config['source_image'] = $source_img;
                    $crop_config['new_image'] = $source_img;
                    $crop_config['quality'] = "100%";
                    $crop_config['maintain_ratio'] = FALSE;
                    $crop_config['width'] = $v['width'];
                    $crop_config['height'] = $v['height'];
                    
                   if($new_ratio > $source_ratio || (($new_ratio == 1) && ($source_ratio < 1))){
					//Source image height is greater than crop image height
					//So we need to move on vertical(Y) axis while keeping horizantal(X) axis static(0)
					$crop_config['y_axis'] = round(($source_image_height - $crop_config['height'])/2);
					$crop_config['x_axis'] = 0;
					}else{
					//Source image width is greater than crop image width
					//So we need to move on horizontal(X) axis while keeping vertical(Y) axis static(0)
					$crop_config['x_axis'] = round(($source_image_width - $crop_config['width'])/2);
					$crop_config['y_axis'] = 0;
					}
                    
                    $this->image_lib->initialize($crop_config); 
                    $this->image_lib->crop();
                    $this->image_lib->clear();
                }

                
            }
        }

        if(empty($thumb_img))
            $thumb_img = $image_data['file_name'];

        return $thumb_img;

	} // End Function


  	function addGroupImage($profileImage,$folder,$hieght=600,$width=600,$path=FALSE){

        if($path){
        	 $this->makedirsBk($folder);
        }else{
        	 $this->makedirs($folder);
        }
       	$realpath = $path ?'../uploads/':'uploads/';

		$allowed_types = "jpg|png|jpeg|JPEG|PNG|JPG"; 
		
		$config = array(
			'upload_path'   	=> $realpath.$folder,
			'allowed_types' 	=> $allowed_types,
			'max_size' 			=> "5048000",// Can be set to particular file size , here it is 2 MB(2048 Kb)

			'min_width'			=> "500",
			'min_heigth'		=> "500",

			//'min_width'			=> "450",
			//'min_height'		=> "450",

			'encrypt_name'		=> TRUE,
			'overwrite'		 	=> false,
			'remove_spaces'		=> TRUE,
			'quality'			=> '100%',
		);
		
		$this->load->library('upload');
		$this->upload->initialize($config);

	  	if(!$this->upload->do_upload($profileImage)){

   			$error = array('error' => $this->upload->display_errors());
			return $error;

		} else {

			$image_data = $this->upload->data(); 
		
			$this->load->library('image_lib');

			
			$folder_thumb = $folder.'/thumb/';//for thumb image
			if($path){
				$this->makedirsBk($folder_thumb);
			}else{
				$this->makedirs($folder_thumb);
			}
			

			$resize['image_library'] 	= 'gd2';
			$resize['source_image'] 	= $image_data['full_path'];
			$resize['new_image'] 		= realpath(FCPATH .$realpath .$folder_thumb);
			$resize['maintain_ratio'] 	= FALSE;
			$resize['width'] 			= 150;
			$resize['height'] 			= 150;
			$resize['quality'] 			= '100%';

			$this->image_lib->initialize($resize);
			$this->image_lib->resize();//thumb image end


			$folder_resize = $folder.'/medium/';//for medium image 
			if($path){
				$this->makedirsBk($folder_resize);
			}else{
				$this->makedirs($folder_resize);
			}
			

			$resize1['source_image'] 	= $image_data['full_path'];
			$resize1['new_image'] 		= realpath(FCPATH .$realpath.$folder_resize);
			$resize1['maintain_ratio'] 	= FALSE;
			$resize1['width'] 			= $width;
			$resize1['height'] 			= $hieght;
			$resize1['quality'] 		= '100%';

			$this->image_lib->initialize($resize1);
			$this->image_lib->resize();//medium image end


			$folder_large = $folder.'/large/';//for large image 
			if($path){
				$this->makedirsBk($folder_large);
			}else{
				$this->makedirs($folder_large);
			}
			

			$resize2['source_image'] 	= $image_data['full_path'];
			$resize2['new_image'] 		= realpath(FCPATH .$realpath.$folder_large);
			$resize2['maintain_ratio'] 	= FALSE;
			$resize2['width'] 			= 1024;
			$resize2['height'] 			= 768;
			$resize2['quality'] 		= '100%';

			$this->image_lib->initialize($resize2);
			$this->image_lib->resize();//large image end

			$this->image_lib->clear();

			return $image_data['file_name'];
		}

	} // End Function

	function updateGallery($fileName,$folder,$hieght=600,$width=600,$path=FALSE)
	{
		  	$this->makedirs($folder);
		 // pr($_FILES[$fileName]);
		  	$realpath = $path ?'../uploads/':'uploads/';
			$storedFile 		= array();
			$allowed_types 		= "gif|jpg|png|jpeg|PNG|JPG|JPEG|GIF"; 
			$files 				= $_FILES[$fileName];
			$number_of_files 	= sizeof($_FILES[$fileName]['tmp_name']);
			// we first load the upload library
			// next we pass the upload path for the images
			$configG['upload_path'] 		= $realpath.$folder;
			$configG['allowed_types'] 		= $allowed_types;
			$configG['max_size']    		= '2048000';
			$configG['min_width']      		= '500';
			$configG['min_heigth']      	= '500';
			$configG['encrypt_name']  		= TRUE;
			$configG['quality'] 			= '100%';
			$this->load->library('upload',$configG);
			// now, taking into account that there can be more than one file, for each file we will have to do the upload
			//pr($configG);
			for ($i = 0; $i < $number_of_files; $i++)
			{
				$_FILES[$fileName]['name'] 		= $files['name'][$i];
				$_FILES[$fileName]['type'] 		= $files['type'][$i];
				$_FILES[$fileName]['tmp_name'] 	= $files['tmp_name'][$i];
				$_FILES[$fileName]['error'] 	= $files['error'][$i];
				$_FILES[$fileName]['size'] 		= $files['size'][$i];

				//pr($this->upload->do_upload($fileName));

				//now we initialize the upload library
				$this->upload->initialize($configG);
		//pr($fileName);
				if ($this->upload->do_upload($fileName))
				{
					$savedFile = $this->upload->data();//upload the image
					
					$folder_thumb = $folder.'/thumb/';
					$this->makedirs($folder_thumb);
					//your desired config for the resize() function
					$config1 = array(
						'image_library' 	=> 'gd2',
						'source_image' 		=> $savedFile['full_path'], //get original image
						'maintain_ratio' 	=> false,
						//'create_thumb' 		=> TRUE,
						'width' 			=> 100,
						'height' 			=> 100,
						'new_image' 		=> realpath(FCPATH .$realpath.$folder_thumb),
						'quality'			=> '100%'
					);	
					$this->load->library('image_lib'); //load image_library
					$this->image_lib->initialize($config1);
					$this->image_lib->resize();
					$folder_resize = $folder.'/resize/';
					$this->makedirs($folder_resize);

					$resize1['source_image'] 	= $savedFile['full_path'];
					$resize1['new_image'] 		= realpath(FCPATH .$realpath.$folder_resize);
					$resize1['maintain_ratio'] 	= FALSE;
					$resize1['width'] 			= $width;
					$resize1['height'] 			= $hieght;
					$resize1['quality'] 		= '100%';

					$this->image_lib->initialize($resize1);
					$this->image_lib->resize();

					$folder_large = $folder.'/large/';//for large image 
			if($path){
				$this->makedirsBk($folder_large);
			}else{
				$this->makedirs($folder_large);
			}
			
			$resize2['source_image'] 	= $savedFile['full_path'];

			$resize2['new_image'] 		= realpath(FCPATH .'uploads/'.$folder_large);

			$resize2['new_image'] 		= realpath(FCPATH .$realpath.$folder_large);

			$resize2['maintain_ratio'] 	= FALSE;
			$resize2['width'] 			= 1024;
			$resize2['height'] 			= 768;
			$resize2['quality'] 		= '100%';

			$this->image_lib->initialize($resize2);
			$this->image_lib->resize();//large image end

			$storedFile[$i]['name'] = $savedFile['file_name'];
						//$storedFile[$i]['type'] = $savedFile['file_type'];
					
			$this->image_lib->clear();


				}
				else
				{
					pr($this->upload->display_errors());
					$storedFile[$i]['error'] = $this->upload->display_errors();
					//$this->upload->display_error;
				}
			} // END OF FOR LOOP
		 
		return $storedFile;
		  
	}//FUnction
	
	function checkImage($fileName){
		 $this->load->library('upload');
          $storedFile     = array();
          $alb_img_key = 'images'; 
          $files = $_FILES;
              $filesCount=0; 
            $filesCount = count($_FILES[$alb_img_key]['name']);   //check image count
            if($filesCount>6){
              $response = array('status' => FAIL, 'message' => 'Maximum 5 images are allowed');
              $this->response($response);
            }
            $allowed_types    = "gif|jpg|png|jpeg|PNG|JPG|JPEG|GIF"; 
            $configG['upload_path']     = 'uploads/postImages';
            $configG['allowed_types']   = $allowed_types;
            $files        = $_FILES['images'];
            for($i = 0; $i < $filesCount; $i++){
              $_FILES['images']['name']    = $files['name'][$i];
              $_FILES['images']['error'] = $files['error'][$i];
              $_FILES['images']['tmp_name']  = $files['tmp_name'][$i];
              $_FILES['images']['size']    = $files['size'][$i];
              $this->upload->initialize($configG);
              if (!$this->upload->do_upload('images'))
              {
                $storedFile[$i]['error'] = $this->upload->display_errors();
              //$resp = $this->user_model->insert($data_val,POSTS_IMAGES);
             }
            }
            return $storedFile;
    }

	function unlinkFile($path,$file){
		$main 	= $path.$file;
		$thumb 	= $path.'thumb/'.$file;
		$medium = $path.'medium/'.$file;
		$large = $path.'large/'.$file;
	
		if(file_exists(FCPATH.$main)):
			unlink( FCPATH.$main);
		endif;
		if(file_exists(FCPATH.$thumb)):
			unlink( FCPATH.$thumb);
		endif;
		if(file_exists(FCPATH.$medium)):
			unlink( FCPATH.$medium);
		endif;
		if(file_exists(FCPATH.$large)):
			unlink( FCPATH.$large);
		endif;
		return TRUE;
	}//End function

	function uploadPdf($image,$folder,$path=FALSE){
		if($path){
		$this->makedirsBk($folder);
		}else{
		$this->make_dirs($folder);
		}
		$realpath = $path ?'../uploads/':'uploads/';

		$allowed_types = "pdf"; 

		// $img_name = random_string('alnum', 16); //generate random string for image name
		$config = array(
		'upload_path' => $realpath.$folder,
		'allowed_types' => $allowed_types,
		'max_size' => "5120", // Can be set to particular file size , for now 
		'overwrite' => false,
		'remove_spaces' => TRUE,
		'quality' => '100%',
		);

		$this->load->library('upload');
		$this->upload->initialize($config);

		if(!$this->upload->do_upload($image)){
		$error = array('error' => $this->upload->display_errors());
		return $error;

		} else { 
		$image_data = $this->upload->data();
		$this->load->library('image_lib');
		$image_data['file_name'];
		if(empty($thumb_img))
		$thumb_img = $image_data['file_name'];

		return array('pdf_name'=>$thumb_img);
		}

		} // End Function


		function image_sizes($folder){
        //add folder name

        $img_sizes = array();
        
        switch($folder){
            case 'profile' :
                $img_sizes['thumbnail'] = array('width'=>300, 'height'=>300, 'folder'=>'/thumb');
                $img_sizes['medium'] = array('width'=>600, 'height'=>600, 'folder'=>'/medium');
                //$img_sizes['large'] = array('width'=>1024,'height'=>768,'folder'=>'/large');
                break;
            case 'postImages' :
                $img_sizes['thumbnail'] = array('width'=>500, 'height'=>333, 'folder'=>'/thumb');
                $img_sizes['medium'] = array('width'=>700, 'height'=>466, 'folder'=>'/medium');
                break;
            case 'video_thumb' :
                $img_sizes['thumbnail'] = array('width'=>640, 'height'=>360, 'folder'=>'/thumb');
                $img_sizes['medium'] = array('width'=>700, 'height'=>466, 'folder'=>'/medium');
                break; 
           case 'categories' :
                $img_sizes['thumbnail'] = array('width'=>640, 'height'=>360, 'folder'=>'/thumb');
                $img_sizes['medium'] = array('width'=>700, 'height'=>466, 'folder'=>'/medium');
                break;

             case 'group' :
                $img_sizes['thumbnail'] = array('width'=>348, 'height'=>232, 'folder'=>'/thumb');
                $img_sizes['medium'] = array('width'=>700, 'height'=>466, 'folder'=>'/medium');
                break;
        }
            
        return $img_sizes;
	}

	function canvasImageUpload($img,$folder,$date){
            if (strpos($img, 'data:image') === 0) {

            $sub_folder = $folder;
            $this->make_dirs($sub_folder);

            $imgNew = explode(',', $img);
            $ini =substr($imgNew[0], 11);
            $type = explode(';', $ini);


            $img = str_replace('data:image/'.$type[0].';base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file_name = 'mindiii-'.$date.'.'.$type[0];

            $source_img = $file = FCPATH.'uploads/'.$folder.'/'.$file_name;

            if (file_put_contents($file, $data)) {

            $image_data = array();
            $source_image_arr_detail = getimagesize($source_img);
            $image_data["image_width"] = $source_image_arr_detail[0];
            $image_data["image_height"] = $source_image_arr_detail[1];
            $image_data['full_path'] = $source_img;
            $image_data['file_name'] = $file_name;

            $img_sizes_arr = $this->image_sizes($folder); 
         
            //pr($img_sizes_arr);
            $this->load->library('image_lib');

            foreach($img_sizes_arr as $k=>$v){

            // create resize sub-folder
            $sub_folder = $folder.$v['folder'];
            $this->make_dirs($sub_folder);

            $real_path = realpath(FCPATH.'uploads/' .$folder);

            $resize['image_library'] = 'gd2';
            $resize['source_image'] = $image_data['full_path'];
            $resize['new_image'] = $real_path.$v['folder'].'/'.$image_data['file_name'];
            $resize['maintain_ratio'] = TRUE; //maintain original image ratio
            $resize['width'] = $v['width'];
            $resize['height'] = $v['height'];
            $resize['quality'] = '100%';
            // We need to know whether to use width or height edge as the hard-value. 
            // After the original image has been resized, either the original image width’s edge or 
            // the height’s edge will be the same as the container
            $dim = (intval($image_data["image_width"]) / intval($image_data["image_height"])) - ($v['width'] / $v['height']);
            $resize['master_dim'] = ($dim > 0)? "height" : "width";

            $this->image_lib->initialize($resize);
            $is_resize = $this->image_lib->resize(); //create resized copies

            //image resizing maintaining it's aspect ratio is done. Now we will start cropping the resized image
            $source_img = $real_path.$v['folder'].'/'.$image_data['file_name'];


            if($is_resize && file_exists($source_img)){

            $source_image_arr = getimagesize($source_img);
            $source_image_width = $source_image_arr[0];
            $source_image_height = $source_image_arr[1];

            $source_ratio = $source_image_width / $source_image_height;
            $new_ratio = $v['width'] / $v['height'];

            if($source_ratio != $new_ratio){

            //image cropping config
            $crop_config['image_library'] = 'gd2';
            $crop_config['source_image'] = $source_img;
            $crop_config['new_image'] = $source_img;
            $crop_config['quality'] = "100%";
            $crop_config['maintain_ratio'] = FALSE;
            $crop_config['width'] = $v['width'];
            $crop_config['height'] = $v['height'];

            if($new_ratio > $source_ratio || (($new_ratio == 1) && ($source_ratio < 1))){
            //Source image height is greater than crop image height
            //So we need to move on vertical(Y) axis while keeping horizantal(X) axis static(0)
            $crop_config['y_axis'] = round(($source_image_height - $crop_config['height'])/2);
            $crop_config['x_axis'] = 0;
            }else{
            //Source image width is greater than crop image width
            //So we need to move on horizontal(X) axis while keeping vertical(Y) axis static(0)
            $crop_config['x_axis'] = round(($source_image_width - $crop_config['width'])/2);
            $crop_config['y_axis'] = 0;
            }

            $this->image_lib->initialize($crop_config); 
            $this->image_lib->crop();
            $this->image_lib->clear();
            }
            }
            }
            $ret_arr['success'] = 1;
            $ret_arr['message'] = 'done';
            $ret_arr['uploadfile'] = $file_name;
            $ret_arr['oldfile'] = $file_name;
           
            } else {
            $ret_arr['success'] = 0;
            $ret_arr['message'] = 'error';
            }
            return $ret_arr;
            }
    }



}// End of class Image_model

?>
