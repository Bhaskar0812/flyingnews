
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
    <div class="row clearfix pd-15" id="termAling">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="card" style="padding-left: 10px;">
                    <div class="header">
                    <h3>
                        Update About Us
                   </h3>
                </div>
                <div class="body col-md-12">
            
                    <form class="form-horizontal" role="form" id="editAboutUsWeb" method="post" action="<?php echo base_url('admin/aboutUsSubWeb') ?>" enctype="multipart/form-data">
                    <textarea id="editor1" name="contentWeb" rows="10" cols="80">
                    <?php if(!empty($content)){ echo $content->option_value; } ?>
                    </textarea>
                    <div style="padding-right: 10px;"> <button type="submit" class="btn btn-primary btn-raised btn-flat pull-right">ADD</button></div>
              </form>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- /.content -->
</div>


<div id="form-modal-box"></div>
