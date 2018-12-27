<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('post_model');
        $this->load->model('my_posts');
         $this->check_admin_user_session();
    }

    public function postDetail(){
        
        $data['parent'] = "User";
        $data['title'] = "User";
        $offset = 0;
        $limit = 5;
        $table = ADMIN;
        $userTable = POSTS;
        $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
        $data['post_id'] = $post_id = decoding($this->uri->segment(4));
        $whereUser = array('postId'=> $post_id);
        $data['admin'] = $this->common_model->select_row($where,$table);
        $data['likes'] = $this->post_model->getPostLikesCount($post_id);
       // print_r($data['post_id']);die;
        $data['posts'] = $this->common_model->select_post_detail($post_id, $limit, $offset);
       // pr($data['posts']);
        $data['pos'] = $this->common_model->getComments($post_id, $limit, $offset);
        $this->load->admin_render('postDetail',$data,'');
    }

    public function get_comment_on_post(){//CODE FOR LOAD MORE ON POST DETAIL
        //echo "string";die;
        $where = array();
        $table = COMMENTS;
        $post_id = $this->uri->segment(4);
        $offset = 0;
        extract($_POST);
        $limit = 5;
        $total_comment = $this->common_model->select_comment_on_post($post_id);
        //$data['posts'] = $this->common_model->select_post_detail($post_id, $limit, $offset);
        $is_next = 0;
        $new_offset = $offset + $limit;
        if($new_offset<count($total_comment)){
        $is_next = 1;
        }
        $data['is_next'] = $is_next;
        $data['new_offset'] = $new_offset;
        $data['comment_list'] = $this->post_model->getCommentsLists($post_id, $limit, $offset);
        //pr($data['comment_list']);
        $list_html = $this->load->view('postDetailList',$data, true); //load event list view

        $res= array('status'=>1,'html'=>$list_html, 'is_next'=>$is_next, 'new_offset'=>$new_offset,'total'=>$total_comment);
        echo json_encode($res); exit; 
    }


    public function get_post_likes(){//CODE FOR LOAD MORE ON GROUP DETAIL
        $where = array();
        $table = GROUP_COMMENTS;
        $post_id = $this->uri->segment(4);
        $offset = 0;
        extract($_POST);
        $limit = 5;
        $total_likes = $this->post_model->getPostLikesCount($post_id);
        $is_next = 0;
        $new_offset = $offset + $limit;
        if($new_offset<count($total_likes)){
        $is_next = 1;
        }
        $data['is_next'] = $is_next;
        $data['new_offset'] = $new_offset;
        $data['postLikesMember'] = $this->post_model->getPostLikes($post_id, $limit, $offset);
        $list_html = $this->load->view('postLikes',$data, true); //load event list view
        $res= array('status'=>1,'html'=>$list_html, 'is_next'=>$is_next, 'new_offset'=>$new_offset,'total'=>$total_likes);
        echo json_encode($res); exit; 
    }


    
    public function userDetail($id){
            $data['parent'] = "User";
            $data['title'] = "User";
            $table = ADMIN;
            $userTable = USERS;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['user_id'] = $userId = decoding($this->uri->segment(4));
            $whereUser = array('userId'=> decoding($id));
            $data['admin'] = $this->common_model->select_row($where,$table);
            
            $data['users'] = $this->common_model->select_row($whereUser,$userTable);
            //print_r($data['users']);die;
            $this->load->admin_render('userProfile',$data,'');
    }

    public function myPosts() { //get Post list
        $userId = $this->uri->segment(4);
        //echo $userId;die;
        $list = $this->my_posts->get_list($userId); 
        //pr($list);
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            if(empty($get->postImage)){
                $imgPath = base_url().DEFAULT_IMAGE;
            }elseif(!empty($get->postImage)){
                $imgPath = $get->postImage;
            }else {
                $imgPat = base_url().$get->postImage;    
                if (file_exists($get->postImage)) {
                $imgPath = base_url().$get->postImage;
                } else {
                 $imgPath = base_url().DEFAULT_IMAGE; 
                }   
            }

            $row[] = $no;
            $row[] = '<a><img src="'.$imgPath.'" class="ListImage"></a>';
          
            $row[] = display_placeholder_text($get->categoryName); 
            $row[] = display_placeholder_text($get->title);
            $encoded = encoding($get->postId);
            //echo decoding($encoded);die;
            if($get->status){
                 
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".POSTS."','postId','".$encoded."','$get->status','post','Post')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".POSTS."','postId','".$encoded."','$get->status','post','Post')" ;
                 $class = 'fa fa-check';
            }
           $viewUrl = base_url().'admin/users/postDetail/'.encoding($get->postId);
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
           $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
          

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->my_posts->count_all(),
                "recordsFiltered" => $this->my_posts->count_filtered($userId),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function


    public function allUsers(){
            $data['parent'] = "Users";
            $data['title'] = "Users";
            $table = ADMIN;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['counts'] = $this->post_model->counts_all('users');
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('usersList', $data, '');
    }

    public function postList_ajax(){
        
            $data['parent'] = "Posts";
            $data['title'] = "Posts";
            $table = ADMIN;
            $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
            $data['count'] = $this->post_model->counts_all('posts');
            //pr($data['count']);
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('postList', $data, '');
    }

    

    //For user listing via ajax
    public function getUsersList() { //get user list

        $this->load->model('users_model');
        $list = $this->users_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            if(empty($get->profileImage)){
                $imgPath = base_url().DEFAULT_IMAGE;
            }elseif(!empty($get->profileImage) && $get->socialType != ''){
                $imgPath = $get->profileImage;
            }else {
                $imgPat = base_url().ADMIN_PROFILE."/".$get->profileImage;    
                if (file_exists(ADMIN_PROFILE."/".$get->profileImage)) {
                $imgPath = base_url().ADMIN_PROFILE."/".$get->profileImage;
                } else {
                 $imgPath = base_url().DEFAULT_IMAGE; 
                }   
            }
            $row[] = $no;
            $row[] = '<a><img src="'.$imgPath.'" class="ListImage"></a>';
          
            $row[] = display_placeholder_text($get->fullName); 
            $row[] = display_placeholder_text($get->email);
            $encoded = encoding($get->userId);
            if($get->status){
                 
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','user','User')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','user','User')" ;
                 $class = 'fa fa-check';
            }
           $viewUrl = base_url().'admin/users/userDetail/'.encoding($get->userId);
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
           $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
          

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->users_model->count_all(),
                "recordsFiltered" => $this->users_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

  public function postList() { //get Post list

        $this->load->model('post_model');
        $list = $this->post_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            if(empty($get->post_image)){
                $imgPath = base_url().DEFAULT_IMAGE;
            }elseif(!empty($get->post_image)){
                $imgPath = $get->post_image;
            }else {
                $imgPat = base_url().$get->post_image;    
                if (file_exists($get->post_image)) {
                $imgPath = base_url().$get->post_image;
                } else {
                 $imgPath = base_url().DEFAULT_IMAGE; 
                }   
            }

            $row[] = $no;
            $row[] = '<a><img src="'.$imgPath.'" class="ListImage"></a>';
          
            $row[] = display_placeholder_text($get->categoryName); 
            $row[] = display_placeholder_text($get->title);
            //$row[] = display_placeholder_text($get->description);
            $row[] = $get->like_count;
            $row[] = $get->comment_count;
            $encoded = encoding($get->userId);
            $row[] = '<a href="'.base_url().'admin/users/userDetail/'.$encoded.'">'.display_placeholder_text($get->fullname).'</a>';
            $encoded = encoding($get->postId);
            if($get->status){
                 
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".POSTS."','postId','".$encoded."','$get->status','post','Post')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".POSTS."','postId','".$encoded."','$get->status','post','Post')" ;
                 $class = 'fa fa-check';
            }
           $viewUrl = base_url().'admin/users/postDetail/'.encoding($get->postId);
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
           $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';
          

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->post_model->count_all(),
                "recordsFiltered" => $this->post_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

    function _alpha_spaces_check($string){

        if(alpha_spaces($string)){
            return true;
        }
        else{
            $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
            return FALSE;
        }
    }//end of function

}//end of class