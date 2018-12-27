<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <link rel="shortcut icon" href="<?php echo base_url();?>backend_assets/logo/20.png">
  <title>Mabwe | Log in</title>
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
  <link rel="stylesheet" href="<?php echo $backend;?>dist/css/custom.css">

</head>
<body class="hold-transition login-page" >
<div class="login-box">
  
  <div class="login-logo">
     <a href="<?php echo base_url();?>admin"><img src="<?php echo base_url();?>backend_assets/logo/logo_200.png"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form id="login">
      <div class="text-danger error font_12" id="unsuccess" ></div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Email/Username" name="userName">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <div class="text-danger error font_12" id="userName_error" ></div>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div class="text-danger error font_12" id="password_error" ></div>
      </div>
      <div class="row">
        <div class="col-xs-7">
           <div class="checkbox">
            <!-- <label>
              <input type="checkbox" name="check'"> Remember Me
            </label> -->
          </div>
          
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
          <button type="submit" class="btn btn-primary btn-raised btn-block btn-flat">Sign In</button>
        </div>
        
        <!-- /.col -->
      </div>
    </form>


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
 <script src="<?php echo $backend; ?>toaster/jquery.toaster.js"></script>
<script>
    $.material.init();
    $("#login").submit(function(e){
  $('#gif').show(); 
  e.preventDefault();
  $(".error").html(''); 
  $.ajax({
    type:"POST",
    url:"admin/login",
    data:$(this).serialize(),
    datatype:"JSON",
    success:function(res){
      var obj = JSON.parse(res);
            if(obj){
            var err = obj.messages;
            var er = '';
            $.each(err, function(k, v) { 
            er = '  ' + v; 
           $("#"+k+"_error").html(er);
           });
          }
          if(obj.messages.success){
            var surl = 'admin/dashboard'; 
            window.setTimeout(function() { window.location = surl; }, 500);
          } 
          if(obj.messages.unsuccess){

          // $('#unsuccess').html(obj.messages.unsuccess);
           $('#unsuccess').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Alert!</h4>'+obj.messages.unsuccess+'</div>');
            window.setTimeout(function() { $('#unsuccess').html("")  }, 3000);
            
          }
    }
  });
});


</script>
</body>
</html>
