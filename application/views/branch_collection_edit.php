
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
<section class="content-header">
    <h1>
        Branch Collection Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list"><i class="fa fa-users"></i>Branch List</a></li>
        <li class="active">Edit Branch Collection</li>
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
                        <form role="form" action="<?php echo base_url(); ?>Branch_collections/edit/<?php echo $query[0]->id ?>" method="post" enctype="multipart/form-data" id="branch_form">

                            <div class="form-group">
                                <label for="name">Date Of Inception</label><span style="color:red">*</span>
                                <input type="text"  name="inceptiondate" class="form-control datepicker-input" id="inceptiondate" placeholder="Date Of Inception" value="<?php echo date('d/m/Y',  strtotime($query[0]->inceptiondate)) ?>">
                                <span class="errorvalidation" id="error_inceptiondate" style="color:red"></span>
                                <?php echo form_error('inceptiondate'); ?>
                            </div>

                            <div class="form-group">
                                <label for="type">City</label><span style="color:red">*</span>
                                <select class="form-control"  name="city" id="city" disabled>
                                    <option value="">Select City</option>
                                    <?php
                                    foreach ($test_city as $row) {
                                        ?>
                                        <option value="<?php echo $row->id; ?>" <?php
                                        if ($query[0]->city_fk == $row->id) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($row->name); ?></option>
                                            <?php } ?>
                                </select>
                                <span class="errorvalidation" id="error_city" style="color:red"></span>
                                <?php echo form_error('city'); ?>
                            </div>

                            <div class="form-group">
                                <label for="type">Branch</label><span style="color:red">*</span>
                                <select class="form-control"  name="branch" id="branch" disabled>
                                    <option value="">Select Branch</option>
                                    <?php
                                    foreach ($branch_list as $row) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>" <?php
                                        if ($query[0]->branch_fk == $row['id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($row['branch_name']); ?></option>
                                            <?php } ?>
                                </select>
                                <span class="errorvalidation" id="error_branch" style="color:red"></span>
                                <?php echo form_error('branch'); ?>
                            </div>

                            <div class="form-group">
                                <label for="name">Address</label><span style="color:red">*</span>
                                <textarea type="text"  name="address" class="form-control" id="address" placeholder="Address" ><?php echo $query[0]->address ?></textarea>
                                <span class="errorvalidation" id="error_address" style="color:red"></span>
                                <?php echo form_error('address'); ?>
                            </div>

                            <div class="form-group">
                                <label for="name">Remark</label>
                                <textarea type="text"  name="remark" class="form-control" id="remark" placeholder="Remark" ><?php echo $query[0]->remark ?></textarea>
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Integration Fees (Rs.)</label><span style="color:red">*</span>
                            <input type="text" name="total" class="form-control" id="total" placeholder="Total" value="<?php echo $query[0]->total; ?>">
                            <span class="errorvalidation" id="error_total" style="color:red"></span>
                            <?php echo form_error('total'); ?>
                        </div>

                        <div class="form-group">
                            <label for="name">Discount</label>
                            <input type="text"  name="discount" class="form-control" id="discount" placeholder="Discount" value="<?php echo $query[0]->discount; ?>"> 
                            <input type="radio" name="dis_type" value="percentage" <?php echo ($query[0]->discount_type == 1) ? "checked" : "" ?>>% 
                            <input type="radio" name="dis_type" value="flat" <?php echo ($query[0]->discount_type == 2) ? "checked" : "" ?>> Flat 
                            <span class="errorvalidation" id="error_discount" style="color:red"></span>
                        </div>

                        <div class="form-group">
                            <?php
                            if ($query[0]->discount_type == '1') {
                                $net_pay = $query[0]->total - (($query[0]->total * $query[0]->discount) / 100);
                            } else if ($query[0]->discount_type == '2') {
                                $net_pay = $query[0]->total - $query[0]->discount;
                            }
                            ?>

                            <label for="name">Net Pay</label><span style="color:red">*</span>
                            <input type="text"  name="net_pay" class="form-control" id="net_pay" placeholder="Net Pay" value="<?php echo $net_pay; ?>">
                            <span class="errorvalidation" id="error_net_pay" style="color:red"></span>
                            <?php echo form_error('net_pay'); ?>
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
                $("#branch_form").submit(function (event) {
                    var branch = $('#branch').val();
                    var total = parseFloat($('#total').val());
                    var city = $('#city').val();
                    var inceptiondate = $('#inceptiondate').val();
                    var address = $('#address').val();
                    var discount = parseFloat($('#discount').val());
                    var discount_type = $("input[name='dis_type']:checked").val();
                    var net_pay = $('#net_pay').val();

                    var error = 0;
                    $('.errorvalidation').html("");
                    if (city == '') {
                        $('#error_city').html("Please Select City");
                        error = 1;
                    }

                    if (branch == '') {
                        $('#error_branch').html("Please Select Branch");
                        error = 1;
                    }

                    if (inceptiondate == '') {
                        $('#error_inceptiondate').html("Please Enter Date Of Inception");
                        error = 1;
                    }

                    if (address == '') {
                        $('#error_address').html("Please Enter Address");
                        error = 1;
                    }

                    if (discount) {
                        if (discount_type == "percentage") {
                            if (isNaN(discount) || discount <= 0 || discount >= 100) {
                                $('#error_discount').html("Please Enter Valid Discount");
                                error = 1;
                            }
                        } else if (discount_type == "flat") {
                            if (isNaN(discount) || discount >= total) {
                                $('#error_discount').html("Please Enter Valid Discount");
                                error = 1;
                            }
                        }

                    }

                    if (total != '') {
                        if (isNaN(total)) {
                            $('#error_total').html("Please Enter Valid Integratioin Fees");
                            error = 1;
                        }

                    } else {
                        $('#error_total').html("Please Enter Integratioin Fees");
                        error = 1;
                    }

                    if (net_pay != '') {
                        if (isNaN(net_pay)) {
                            $('#error_net_pay').html("Please Enter Valid Net Pay");
                            error = 1;
                        }

                    } else {
                        $('#error_net_pay').html("Please Enter Net Pay");
                        error = 1;
                    }

                    if (error == 1) {
                        return false;
                    } else {
                        return true;
                    }

                });


                $(document).ready(function () {
                    $("#total,#discount").keydown(function (e) {
                        // Allow: backspace, delete, tab, escape, enter and .
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

                        $("#discount,#total").keyup(function () {
                            var total = $("#total").val();
                            var discount_type = $("input[name='dis_type']:checked").val();
                            var discount = $("#discount").val();
                            if (discount == "") {
                                discount = 0;
                            }

                            if (total != "") {
                                if (discount_type == "percentage") {
                                    var dis_amount = (total * discount) / 100;
                                    var net_pay = total - dis_amount;
                                    $("#net_pay").val(net_pay);
                                } else if (discount_type == "flat") {
                                    var net_pay = total - discount;
                                    $("#net_pay").val(net_pay);
                                }

                            }
                        });



                        $('input[type=radio][name=dis_type]').change(function () {
                            var total = $("#total").val();
                            var discount_type = $("input[name='dis_type']:checked").val();
                            var discount = $("#discount").val();
                            if (discount == "") {
                                discount = 0;
                            }

                            if (total != "") {
                                if (discount_type == "percentage") {
                                    var dis_amount = (total * discount) / 100;
                                    var net_pay = total - dis_amount;
                                    $("#net_pay").val(net_pay);
                                } else if (discount_type == "flat") {
                                    var net_pay = total - discount;
                                    $("#net_pay").val(net_pay);
                                }

                            }
                        });


            </script>
        </div>
    </div>
</section>
