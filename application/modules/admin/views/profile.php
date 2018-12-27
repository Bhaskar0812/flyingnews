
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Admin Profile
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin"><i class="fa fa-dashboard"></i>Dashboard > </a>  Admin Profile</li>
      </ol>
    </section>

    <!-- Main content -->
     <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <div class="container">
                <?php if(empty($admin->profileImage)){?>
                <img id="blah" class="profile-user-img img-responsive img-circle image" src="<?php echo base_url().DEFAULT_IMAGE;?>" alt="User profile picture" width="128px" height="128px">
                <?php } else{ 
                  if (file_exists(ADMIN_PROFILE.'/'.$admin->profileImage)) {
                    $imgPath = base_url().ADMIN_PROFILE."/$admin->profileImage";
                    } else {
                     $imgPath = base_url().DEFAULT_IMAGE; 
                    }  
                  ?>                
                <img id="blah" class="profile-user-img img-responsive img-circle image" src="<?php echo $imgPath;?>" alt="User profile picture" width="128px" height="128px">
                <input type="hidden" value="<?php echo $admin->profileImage;?>" id="image">
                <?php } ?>
                <div class="middle">
                  <div class="text img-circle"><a onclick="deleteImg(<?php echo $admin->id;?>)" style="cursor: pointer;"><i class="fa fa-times manual"></i></a></div>
                </div>
              </div>
              <h3 class="profile-username text-center"><?php echo $admin->fullName;?></h3>
              <p class="text-muted text-center"><?php echo $admin->email;?></p>
            </div>
            <!-- /.box-body -->
          </div>

         
          <!-- /.box -->
          <!-- About Me Box -->
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#settings" data-toggle="tab">Edit Profile</a></li>
               <li><a href="#changePassword" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="settings">
                <form id="editProfile">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control col-md-7 col-xs-12" id="inputName" placeholder="Name" value="<?php echo $admin->fullName;?>" name="fullName">
                       <div class="text-danger error font_12" id="fullName_error" ></div>
                    </div>
                  </div>
                  <div class="form-group">
                  <label for="inputImage" class="col-sm-2 control-label">Image</label>
                    <div class="col-sm-10 ">
                      <div class="form-control">
                    <a id="inputImage"><label  for="upload-photo" id="inputImage" style="cursor: pointer;">Browse</label></a>
                  </div>
                    <input type="file" name="photo" id="upload-photo" style="opacity: 0;position: absolute;z-index: -1;" /></br>
                  </div>
                  </div>
                      <button type="submit" class="btn btn-danger">Submit</button>
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="changePassword">
                 <form id="changePass">
                <div class="text-danger error font_12" id="message"></div>
                  <div class="text-danger error font_12" id="unsuccess_error"></div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Old Password</label>
                    <div class="col-sm-10">
                      <input  type="password" name="password" class="form-control col-md-7 col-xs-12" placeholder="Old Password" value="">
                       <div class="text-danger error font_12" id="password_error"></div>
                    </div>
                  </div>
                
              </br>
              <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">New Password</label>
                    <div class="col-sm-10">
                     <input  type="password" name="npassword" class="form-control col-md-7 col-xs-12" placeholder="New Password" value="">
                     <div class="text-danger error font_12" id="npassword_error"></div></br>
                    </div>
                  </div>
              
              <div class="form-group">
                  <label for="inputEmail" class="col-sm-2 control-label">Confirm Password</label>
                  <div class="col-sm-10">
                 <input  type="password" name="rnpassword" class="form-control col-md-7 col-xs-12" placeholder="Confirm Password" value="">
                 <div class="text-danger error font_12" id="rnpassword_error"></div>
               </div>
               </div></br>
                 
                </br><input class="btn btn-success" type="submit" value="UPDATE">
                </form>
              </div>
            </div>
           
           
          
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
