<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         Request Discount
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."doctor/request_discount"; ?>"><i class="fa fa-users"></i>Request Discount</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Request Discount</h3>
						
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                          <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
						
			<form role="form" action="<?php echo base_url()."doctor/request_discount"; ?>" method="post" >
                  <div class="box-body">
                    <div class="col-md-6">
						
                    <div class="form-group">
                      <label for="exampleInputFile">Patient Name</label><span style="color:red">*</span>
                      <input type="text"  name="username" class="form-control" value="<?= set_value("username"); ?>">
                       <span style="color: red;"><?= form_error('username'); ?></span>
                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">Mobile Number</label><span style="color:red">*</span>
                      <input type="text"  name="mnumber" class="form-control" value="<?= set_value("mnumber"); ?>">
                       <span style="color: red;"><?= form_error('mnumber'); ?></span>
                    </div>
					
					<div class="form-group">
                      <label for="exampleInputFile">Description</label><span style="color:red">*</span>
                     <textarea name="desc" class="form-control"><?= set_value("desc"); ?></textarea>
                       <span style="color: red;"><?= form_error('desc'); ?></span>
                    </div>
                    
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                </form>
                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->