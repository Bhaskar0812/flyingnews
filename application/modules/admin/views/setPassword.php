<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mabwe | Set Password</title>
  <?php $backend = base_url()."backend_assets/";?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo $backend;?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/AdminLTE.min.css">
  <!-- Material Design -->
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/bootstrap-material-design.min.css">
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/ripples.min.css">
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/MaterialAdminLTE.min.css">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
     <a href="<?php echo base_url();?>admin"><!-- <img src="<?php echo base_url();?>backend_assets/logo/logo_1.png"> -->Mabwe</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <?php $urisegment = $this->uri->segment(3);
    $adminPass = !empty($admin->forgetPass);
    $uriSegment = $this->uri->segment(4);
     if(!empty($urisegment) && !empty($adminPass) && !empty($uriSegment)){ if($admin->forgetPass == $urisegment && md5($admin->id) == $uriSegment){ ?>
    <p class="login-box-msg">Set Password Here...</p>
    <form id="setPass">
      <div class="text-danger error font_12" id="unsuccess" ></div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div class="text-danger error font_12" id="password_error" ></div>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword">
        <input type="hidden" class="form-control" placeholder="Confirm Password" name="id" value="<?php echo $admin->id;?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div class="text-danger error font_12" id="cpassword_error" ></div>
      </div>
      <div class="row">
        <div class="col-xs-5">
          <button type="submit" id="sub_btn" class="btn btn-primary btn-raised btn-block btn-flat">Set</button>
        </div>
       
        <!-- /.col -->
      </div>
    </form><?php } else{ ?><span class="text-warning"></br><?php  echo "Can't Reset Your Password. Please Genrate Another Link.";?><a href="<?php echo base_url();?>admin">Go Back</a>  <?php } 

    } else { ?>
    <span class="text-warning"></br><?php echo "Cannt Reset Your Password. Please Genrate Another Link."; ?><a href="<?php echo base_url();?>admin">Go Back</a> <?php } ?></span>



  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo $backend;?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $backend;?>bootstrap/js/bootstrap.min.js"></script>
<!-- Material Design -->
<script src="<?php echo $backend;?>dist/js/material.min.js"></script>
<script src="<?php echo $backend;?>dist/js/ripples.min.js"></script>
 <script src="<?php echo $backend;?>custom_js/custom_ajax.js"></script>
 <script src="<?php echo $backend; ?>toaster/jquery.toaster.js"></script>
<script>
    $.material.init();
</script>
</body>
</html>
