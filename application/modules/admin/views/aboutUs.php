
<!-- Content Wrapper. Contains page content  -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>About Us</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">About Us</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
   
                <div class="body col-md-12">
            
                    <form class="form-horizontal" role="form" id="editAboutUs" method="post" action="<?php echo base_url('admin/aboutUsSub') ?>" enctype="multipart/form-data">
                    <textarea id="editor1" name="content" rows="10" cols="80">
                    <?php if(!empty($content)){ echo $content->option_value; } ?>
                    </textarea>
                    <div style="padding-right: 10px;"> <button type="submit" class="btn btn-primary btn-raised btn-flat pull-right">ADD</button></div>
              </form>
                </div>
     
    </section>
    <!-- /.content -->
</div>


<div id="form-modal-box"></div>
