<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Vendor add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Vendor_master/index"><i class="fa fa-users"></i>Vendor list</a></li>
        <li class="active">Add Vendor</li>
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
                        <form role="form" action="<?php echo base_url(); ?>inventory/Vendor_master/add" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Vendor Name</label><span style="color:red">*</span>
                                <input type="text"  name="vendor_name" class="form-control"  placeholder="Enter Vendor Name">
                                <span style="color: red;"><?php echo form_error('vendor_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">City Name</label><span style="color:red">*</span>
                                <select name="city_fk" class="form-control">
                                    <option value="">Select City</option>
                                    <?php foreach ($city_list as $key => $value) { ?>
                                        <option value="<?php echo $value['city_fk']; ?>"><?php echo $value['name']; ?></option>
                                    <?php } ?>
                                </select>OR
                                <input type="text" name="city_name" class="form-control" placeholder="Enter City Name" value="<?php echo $query[0]['city_name'];?>">
                                <span style="color: red"> <?php echo form_error('city_name'); ?></span>
                               
                            </div>
                            <div class="form-group">
                                <label for="name">Address</label>
                                <textarea name="address" class="form-control" placeholder="Enter Address"></textarea>

                            </div>
                           <div class="form-group">
                                <label for="name">Contact No 1</label>
                                <input type="text"  name="mobile" class="form-control"  placeholder="Enter Contact No 1">
                                <span style="color: red;"><?php echo form_error('mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Contact No 2</label>
                                <input type="text"  name="contact_no_2" class="form-control"  placeholder="Enter Contact No 2" >
                                <span style="color: red;"><?php echo form_error('contact_no_2'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Contact No 3</label>
                                <input type="text"  name="contact_no_3" class="form-control"  placeholder="Enter Contact No 3">
                                <span style="color: red;"><?php echo form_error('contact_no_3'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Email Id</label>
                                <input type="text"  name="email_id" class="form-control"  placeholder="Enter Email id">
                                <span style="color: red;"> <?php echo form_error('email_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person Name</label>
                                <input type="text"  name="cp_name" class="form-control"  placeholder="Enter Contact Person Name">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person No.</label>
                                <div class="input-group"> 
                                    <span class="input-group-addon" style="">+91</span>
                                    <input type="text" name="cp_mobile" class="form-control"  placeholder="Contact Person No.">
                                </div>

                                <span style="color: red;"> <?php echo form_error('cp_mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person Email Id</label>
                                <input type="text"  name="cp_email_id" class="form-control"  placeholder="Enter Contact Person Email Id">
                                <span style="color: red;"> <?php echo form_error('cp_email_id'); ?></span>
                            </div>


                            <button class="btn btn-primary" type="submit">Add</button>

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