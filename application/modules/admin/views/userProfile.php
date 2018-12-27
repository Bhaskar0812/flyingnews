
<input type="hidden" name="" value="<?php echo $user_id; ?>" id="users_id">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Profile
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url();?>admin/users/allUsers">Users List</a></li>
        <li>User Detail</li>
  
      </ol>
    </section>

    <!-- Main content -->
     <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <div class="zoom container">
              <?php if(empty($users->profileImage)){ ?>
                <img id="blah" class="profile-user-img img-responsive img-circle" src="<?php echo base_url().DEFAULT_IMAGE;?>" alt="User profile picture">
              <? } else{  
                    if (file_exists(ADMIN_PROFILE_THUMB.$users->profileImage)) {
                    $imgPath = base_url().ADMIN_PROFILE_THUMB.$users->profileImage;
                    } else {
                     $imgPath = base_url().DEFAULT_IMAGE; 
                    }  
                ?>
                <img id="blah" class="profile-user-img img-responsive img-circle" src="<?php echo $imgPath; ?>" alt="User profile picture" width="128px" height="128px">
                
                <input type="hidden" value="<?php echo $users->profileImage;?>" id="image">
                 <?php } ?>
                 <strong><p class="profile-username text-center"><?php echo display_placeholder_text(ucwords($users->fullname));?></p></strong>
              </div>

             
              
                <!-- <center><strong><i class="fa fa-envelope margin-r-5"></i></strong>
                <p class="text-muted">
                
              </center> -->
            </div>
            <!-- /.box-body -->
          </div>

          
          <!-- /.box -->
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <center><h3 class="box-title">About</center></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-envelope margin-r-5"></i></strong><br>
              <?php echo $users->email;?>
              <hr>
              
              <strong><i class="fa fa-user margin-r-5"></i>Status</strong>
              <p class="text-muted">
               <?php 
                 $req = status_color($users->status); 
                 $status = $users->status ? 'Active' : 'Inactive'; ?>
                 <span style="color:<?php echo $req; ?>"><?php echo $status; ?></span>
              </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" >
              <li class="active"><a href="#settings" data-toggle="tab">My Posts</a></li>
              <!-- <li ><a href="#inventories" data-toggle="tab">Inventories</a></li>
              <li ><a href="#business" data-toggle="tab">Business</a></li>
              <li ><a href="#expense" data-toggle="tab">Expenses</a></li>
              <li ><a href="#payment" data-toggle="tab">Payment Method</a></li>
              <li ><a href="#taxes" data-toggle="tab">Taxes</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="settings">
                 <div class="box-body">
              <table id="myPost" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Image</th>
                  <th>Category</th>
                  <th>Title</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                </tfoot>
              </table>
            </div>
                  <div class="form-group">
                    <label for="inputUser" class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                      <span class="col-md-7 col-xs-12" id="inputUser" ></span>
                    </div>
                  </div>
                  <div style="height:50px;"></div>
                   </div>
                      <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </section>
              </div>
              <!-- /.tab-pane -->
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

  
