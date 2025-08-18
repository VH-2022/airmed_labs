<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<section class="content-header">
    <h1>
        Test Method
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_sample_type/index"><i class="fa fa-users"></i>Branch Sample Type List</a></li>
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
                <form role="form" action="<?php echo base_url(); ?>Branch_sample_type/edit/<?php echo $cid ?>" method="post" enctype="multipart/form-data" id="test_submit">
                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->
                            <div class="form-group">
                                <label for="exampleInputFile">Branch Name</label><span style="color:red">*</span>
                                <select class="form-control chosen chosen-select" name="branch_name" id="branch_name">
                                    <option value="">Select Branch</option>
                                    <?php foreach ($branch as $key) { ?>
                                        <option value="<?php echo $key['id'] ?>" <?php
                                        if ($query[0]['branch_fk'] == $key['id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $key['branch_name'] ?></option>
                                            <?php } ?>
                                </select>
                                <span id="branch_nameerror" class="errorall" style="color:red;"><?php echo form_error('branch_name'); ?></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="exampleInputFile">Test Name</label><span style="color:red">*</span>
                                <select class="form-control" name="test_name" id="test_name">
                                    <option value="">Select Test</option>
                                    <?php foreach ($test_list as $key) { ?>
                                        <option value="<?php echo $key['id'] ?>" <?php
                                        if ($query[0]['test_fk'] == $key['id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $key['test_name'] ?></option>
                                            <?php } ?>
                                </select>
                                <span id="test_nameerror" class="errorall" style="color:red;"><?php echo form_error('test_name'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Sample Type</label><span style="color:red">*</span>
                                <input type="text" name="sample_type" class="form-control"  value="<?php echo $query[0]['sample_type']; ?>" id="sample_type">
                                <span id="sample_typeerror" class="errorall" style="color:red;"><?= form_error('sample_type'); ?></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
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
<script>
    $('#branch_name').change(function () {
        var url = "<?php echo base_url(); ?>Test_method/get_test";
        var value = $("#branch_name").val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"id": value},
            success: function (response)
            {
                var $el = $("#test_name");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Test'));

                $.each(response, function (index, data) {
                    $('#test_name').append('<option value="' + data['id'] + '">' + data['test_name'] + '</option>');
                });
                                $('#test_name').trigger("chosen:updated");
                $('#test_name').trigger("listz:updated");
            }
        });
    });

    $("#test_submit").submit(function (e) {
        var error = 1;
        var branch_name = $("#branch_name").val().trim();
        var test_name = $("#test_name").val().trim();
        var sample_type = $("#sample_type").val().trim();

        $(".errorall").html("");

        if (branch_name == "") {
            error = 0;
            $("#branch_nameerror").html("The Branch Name field is required.");
        }
        if (test_name == "") {
            error = 0;
            $("#test_nameerror").html("The Test Name field is required.");
        }
        if (sample_type == "") {
            error = 0;
            $("#sample_typeerror").html("The Sample Type field is required.");
        }

        if (error == 1) {
            return true;
        } else {
            return false;
        }
    });
</script>
<!--<script  type="text/javascript">
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script>-->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    $nc = $.noConflict();
    $nc(function () {
        $nc('.chosen-select').chosen();
    });

</script>
<script>
    jQuery(document).ready(function () {
        jQuery("#test_name").chosen();
    });
</script>
