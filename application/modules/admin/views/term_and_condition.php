<!-- Content Wrapper. Contains page content  -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Term & Condition</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Term & Condition</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="row clearfix pd-15" id="termAling">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="card" style="padding-left: 10px;">
                    <div class="header">
                    <h3>
                        Upload PDF File
                   </h3>
                </div>
                <div class="body">
                    <label>Current File</label> <br>
                    <?php if (!empty($content->option_value)) { ?>
                    <a href="<?php echo (base_url().TERM_CONDITION. $content->option_value);?>" target="_blank">
                        <i class="material-icons" style="color:#605ca8;">insert_drive_file</i>
                        <span><?php echo $content->option_value;?> </span>
                    </a>
                    <?php } ?>
                    <br><br>
                    <form class="form-horizontal" role="form" id="editFormAjax" method="post" action="<?php echo base_url('admin/updateTermAndCondition') ?>" enctype="multipart/form-data">
                        <div class="form-group">
                  <label for="inputImage" class="control-label"></label>
                    <div class="col-sm-10 ">
                      <div class="form-control">
                    <a id="inputImage"><label  for="upload-photo" id="inputImage" style="cursor: pointer;">Browse</label></a>
                  </div>
                    <input type="file" name="pdf" accept="application/pdf" id="upload-photo" style="opacity: 0;position: absolute;z-index: -1;" /></br>
                  </div>
                  </div>
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

