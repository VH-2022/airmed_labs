<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
       Bank Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Bank_master/bank_list"><i class="fa fa-users"></i>Bank List</a></li>
        <li class="active">Add Bank</li>
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
                <?php if ($this->session->flashdata('duplicate')) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $this->session->flashdata('duplicate'); ?>
                    </div>
                <?php } ?>
                <div class="box-body">

                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url(); ?>inventory/Bank_master/edit/<?php echo $eid;?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $eid;?>">

                            <div class="form-group">
                                <label for="name">Bank Name</label><span style="color:red">*</span>
                                <input type="text"  name="bank_name" class="form-control" value="<?php echo $query[0]['bank_name'];?>">
                                <?php echo form_error('bank_name'); ?>
                            </div>
                              <div class="form-group">
                                <label for="name">Branch Name</label><span style="color:red">*</span>
                                <input type="text"  name="branch_name" class="form-control" value="<?php echo $query[0]['branch_name'];?>">
                                <?php echo form_error('branch_name'); ?>
                            </div>

                                <div class="form-group">
                                <label for="name">Account No</label><span style="color:red">*</span>
                                <input type="text"  name="account_no" class="form-control" value="<?php echo $query[0]['account_no'];?>">
                                <?php echo form_error('account_no'); ?>
                            </div>

                                <div class="form-group">
                                <label for="name">City</label><span style="color:red">*</span>
                                <input type="text"  name="city" class="form-control" value="<?php echo $query[0]['city'];?>">
                                <?php echo form_error('city'); ?>
                            </div>
                               <div class="form-group">
                                <label for="name">Remark</label>
                                <textarea name="remark" class="form-control"><?php echo $query[0]['remark'];?></textarea>
                                
                            </div>
                          
                            <button class="btn btn-primary" type="submit">Update</button>

                        </form>
                    </div><!-- /.box -->
                </div>	
            </div>
        </div>
    </div>
</section>
</div>
</div>
<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>