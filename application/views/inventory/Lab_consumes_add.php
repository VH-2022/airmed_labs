<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Lab Consumables Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Consumes_master/consumes_list"><i class="fa fa-users"></i>Lab Consumables List</a></li>
        <li class="active">Add Consumables</li>
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
                        <form role="form" action="<?php echo base_url(); ?>inventory/Consumes_master/add" method="post" enctype="multipart/form-data">


                            <div class="form-group">
                                <label for="name">Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control" value="<?php echo set_value('name'); ?>">
                                <?php echo form_error('name'); ?>
                            </div>

                            <div class="form-group">
                                <label for="name">Type</label>
                                <input type="radio" name="type" value="1" checked> Pathology
                                <input type="radio" name="type" value="2"> Radiology
                            </div>

                            <div class="form-group">
                                <label for="name">City</label><span style="color:red">*</span>
                                <select name="city_fk" class="form-control" onchange="getCity();" id="city_id">
                                    <option value="">Select City</option>
                                    <?php foreach ($city_list as $val) { ?>
                                        <option value="<?php echo $val['id']; ?>" ><?php echo $val['name']; ?></option>

                                    <?php } ?>
                                </select>
                                <span style="color:red;"><?php echo form_error('city_fk'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Branch</label><span style="color:red">*</span>
                                <select name="branch_fk" class="form-control"  id="branch_id">
                                    <option value="">Select Branch</option>

                                </select>
                                <span style="color:red;"><?php echo form_error('branch_fk'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Quantity</label>
                                <input type="text"  name="quantity" class="form-control" value="<?php echo set_value('quantity'); ?>">
                                <?php echo form_error('quantity'); ?>
                            </div>
                            <div class="form-group">
                                <label>Unit</label>
                                <select name="unit_fk" class="form-control">
                                    <option value="">Select Unit</option>
                                    <?php foreach ($unit_list as $key => $val) { ?>
                                        <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>

                                    <?php } ?>
                                </select>
                                <?php echo form_error('unit_fk'); ?>
                            </div>
                            <div class="form-group">
                                <label>Brand</label>
                                <select name="brand_fk" class="form-control">
                                    <option value="">Select Brand</option>
                                    <?php foreach ($brand_list as $key => $val) { ?>
                                        <option value="<?php echo $val['id']; ?>"><?php echo $val['brand_name']; ?></option>

                                    <?php } ?>
                                </select>
                                <?php echo form_error('brand_fk'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Remark</label>
                                <textarea name="remark" class="form-control" value="<?php echo set_value('remark'); ?>"></textarea>


                            </div>
                            <div class="form-group">
                                <label for="name">Box Price</label>
                                <input type="text"  name="box_price" class="form-control" placeholder="Enter Box Price" value="<?php echo set_value('box_price'); ?>">
                                <?php echo form_error('box_price'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">HSN Code</label>
                                <input type="text"  name="hsn_code" class="form-control" placeholder="Enter HSN Code" value="<?php echo set_value('hsn_code'); ?>">
                                <?php echo form_error('hsn_code'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Location</label>
                                <input type="text"  name="location" class="form-control" placeholder="Enter Location" value="<?php echo set_value('location'); ?>">
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
<script>
    function getCity() {
        var id = $('#city_id').val();
        $.ajax({
            url: "<?php echo base_url(); ?>inventory/consumes_master/getBranch",
            type: "POST",
            data: {'city_fk': id},
            success: function (data) {

                if (data != '') {
                    $('#branch_id').html("<option value=''>Select Branch</option>" + data);
                } else {
                    $('#branch_id').html('<option value="">No Record Found</option>');
                }
            }
        });
    }
</script>