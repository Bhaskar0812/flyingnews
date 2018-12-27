 
 <!DOCTYPE html>
<html lan="En">
<head>
 <!-- <link rel="shortcut icon" href="<?php echo base_url();?>backend_assets/logo/20.png"> -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title style="color:black;">
    Flying News | <?php  echo !empty($title)?$title:''; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <!-- ================================================================================ -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
 <!-- ================================================================================ -->
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- ================================================================================ -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>dist/css/AdminLTE.min.css">
  <!-- ================================================================================ -->
  <!-- Material Design -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>dist/css/bootstrap-material-design.min.css">
  <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>dist/css/ripples.min.css">
   <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>dist/css/MaterialAdminLTE.min.css">
   <!-- ================================================================================ -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>dist/css/skins/all-md-skins.min.css">
   <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>dist/css/custom.css">
   <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>plugins/datatables/dataTables.bootstrap.css">
   <!-- ================================================================================ -->
  <link rel="stylesheet" href="<?php echo base_url().FN_ADMIN;?>js/toastr/toastr.min.css">
   <!-- ================================================================================ -->
</head>
<body class="light-red-fixed sidebar-mini skin-green-light" id="tl_admin_main_body" data-base-url="<?php echo base_url(); ?>">
  
<!-- Site wrapper -->
 <div id="tl_admin_loader" class="tlr_loader" >
   <img src="<?php echo base_url().FN_ADMIN_IMAGES.'loader.apng';?>">

 </div> 
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url();?>admin" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" title="Mabwe"><img src="<?php echo base_url().FN_ADMIN_IMAGES.'logo4.png'?>" width="42px"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" title="Mabwe"><img src="<?php echo base_url().FN_ADMIN_IMAGES.'logo3.png';?>" style="width: 55px;height: 55px;margin-top: 16px;"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             
              <img src=" <?php if(!empty($admin->profileImage) AND file_exists(ADMIN_PROFILE_THUMB.$admin->profileImage)){echo base_url().ADMIN_IMAGE_PATH.$admin->profileImage; }else{echo base_url().PLACEHOLDER;}?>" alt="User profile picture" class="user-image" alt="User Image" onerror="this.src='<?php echo base_url().DEFAULT_IMAGE;?>'"> 
              
              <span title="<?php echo $admin->fullName;?>" class="hidden-xs"><?php echo $admin->fullName;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
              
                <img src="<?php if(!empty($admin->profileImage) AND file_exists(ADMIN_PROFILE_THUMB.$admin->profileImage)){echo base_url().ADMIN_IMAGE_PATH.$admin->profileImage; }else{echo base_url().PLACEHOLDER;}?>" class="img-circle" > 
             
                <p>
                  <?php echo $admin->fullName;?> <br> <small><?php echo $this->session->userdata('emailId');?></small>
                  <?php echo $admin->email;?>
          
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-12 text-center text-success" >
                    <!-- <b><a style="cursor: pointer;" onclick="model()" >Change Password</a></b> -->
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url();?>admin/profileView" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('admin/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
         <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <!-- <li class="dropdown tasks-menu">
            <a title="Signout" onclick="logout();" class="dropdown-toggle" data-toggle="dropdown">
             <i class="fa fa-sign-out"></i>
            </a>
          </li> -->
        
             
        </ul>
      </div>
       
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->

    
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="treeview <?php if($title == "Dashboard"){echo "active";}?>">
          <a href="<?php echo base_url();?>admin/dashboard" title="Dashboard">
            <i class="fa fa-dashboard" title="Dashboard"></i> <span title="Dashboard">Dashboard</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>