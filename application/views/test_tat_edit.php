<!-- Page Heading -->
<style type="text/css">
    .errmsg{
        color:red;
    }
    .errmsg1{
        color:red;
    }
    .errmsg2{
        color:red;
    }
    .errmsg3{
        color:red;
    }
    .errmsg4{
        color:red;
    }

</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<section class="content-header">
    <h1>
        Test TAT Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Test_tat_master/index"><i class="fa fa-users"></i>Test TAT</a></li>
        <li class="active">Edit Test TAT</li>
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
                <div class="box-body">
                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url(); ?>Test_tat_master/edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data" id="branch_form">

                            <div class="form-group">
                                <label for="type">Test</label><span style="color:red">*</span>
                                <select class="form-control chosen-select"  name="test" id="test">
                                    <option value="">Select Test</option>
                                    <?php
                                    foreach ($test_list as $row) {
                                        ?>
                                        <option type="1" value="<?php echo $row->id; ?>" <?php
                                        if ($query[0]->test_fk == $row->id && $query[0]->type == "1") {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($row->test_name); ?></option>
                                            <?php } ?>

                                    <?php
                                    foreach ($package_list as $row) {
                                        ?>
                                        <option type="2" value="<?php echo $row->id; ?>" <?php
                                        if ($query[0]->test_fk == $row->id && $query[0]->type == "2") {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($row->title); ?></option>
                                            <?php } ?>


                                </select>
                                <span class="errorvalidation" id="error_test" style="color:red"></span>
                                <?php echo form_error('test'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Type</label><span style="color:red">*</span>

                                <input type="hidden" id="type1" name="type1" value="<?php echo $query[0]->type; ?>">
                                <select class="form-control"  name="type" id="type" disabled="">
                                    <option value="">Type</option>
                                    <option value="1" <?php
                                    if ($query[0]->type == 1) {
                                        echo "selected";
                                    }
                                    ?>>Test</option>
                                    <option value="2" <?php
                                    if ($query[0]->type == 2) {
                                        echo "selected";
                                    }
                                    ?>>Package</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">TAT (In Hour)</label><span style="color:red">*</span>
                                <input type="text"  name="tat" class="form-control" id="tat" placeholder="TAT" value="<?php echo $query[0]->tat; ?>">
                                <span class="errorvalidation" id="error_tat" style="color:red"></span>
                                <?php echo form_error('tat'); ?>
                            </div>
                    </div>

                </div>

                <div class="box-footer">
                    <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </div>
                </form>
            </div><!-- /.box -->
            <script type="text/javascript">
                $('#test').on('change', function () {
                    var element = $("option:selected", this);
                    var myTag = element.attr("type");
                    $('#type,#type1').val(myTag);
                });

                $("#branch_form").submit(function (event) {
                    var test = $('#test').val();
                    var tat = parseFloat($('#tat').val());

                    var error = 0;
                    $('.errorvalidation').html("");
                    if (test == '') {
                        $('#error_test').html("Please Select Test");
                        error = 1;
                    }


                    if (tat) {
                        if (isNaN(tat)) {
                            $('#error_tat').html("Please Enter Valid Discount");
                            error = 1;
                        }
                    } else {
                        $('#error_tat').html("Please Enter TAT");
                        error = 1;
                    }

                    if (error == 1) {
                        return false;
                    } else {
                        return true;
                    }

                });

                $(document).ready(function () {
                    $("#tat").keydown(function (e) {
                        // Allow: backspace, delete, tab, escape, enter and.
                        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                                // Allow: Ctrl/cmd+A
                                        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                        // Allow: Ctrl/cmd+C
                                                (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                // Allow: Ctrl/cmd+X
                                                        (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                        // Allow: home, end, left, right
                                                                (e.keyCode >= 35 && e.keyCode <= 39)) {
                                                    // let it happen, don't do anything
                                                    return;
                                                }
                                                // Ensure that it is a number and stop the keypress
                                                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                    e.preventDefault();
                                                }
                                            });
                                });
            </script>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                        jQuery(".chosen-select").chosen({
                            search_contains: true
                        });
</script>