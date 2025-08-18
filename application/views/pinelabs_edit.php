<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Pine Lab Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Pine_lab_terminal_master/index"><i class="fa fa-users"></i>Pine Lab Master</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>Pine_lab_terminal_master/edit/<?= $row->id ?>" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="col-md-6">
                            <?php //echo validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>

                            <div class="form-group">
                                <label for="name">Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" placeholder="" class="form-control" value="<?php echo $row->name; ?>">
                                <span style="color: red;"><?= form_error('name'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="festival_date">IMEI No</label><span style="color:red">*</span>
                                <input type="text" name="imei_no" id="imei_no" class="form-control" value="<?php echo $row->imei_no; ?>">
                                <span style="color: red;"><?= form_error('imei_no'); ?></span>
                            </div>
							
							 <div class="form-group">
                                <label for="festival_date">MerchantStorePOS code</label><span style="color:red">*</span>
                                <input type="text"  name="postcode" id="festival_date" class="form-control" value="<?php echo $row->postcode; ?>" placeholder = "Enter MerchantStorePOS code">
                                <span style="color: red;"><?= form_error('postcode'); ?></span>
                            </div>

                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>