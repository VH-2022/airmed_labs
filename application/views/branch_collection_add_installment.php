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
<section class="content-header">
    <h1>
        Branch Collection Add Installment
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_collections/index"><i class="fa fa-users"></i>Branch Collection List</a></li>
        <li class="active">Add Branch Collection Installment</li>
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
                        <form role="form" action="<?php echo base_url(); ?>Branch_collections/add_installment/<?php echo $cid; ?>" method="post" enctype="multipart/form-data" id="branch_form">
                            <input type="hidden"  name="collection_id" id="collection_id" value="<?php echo $cid; ?>">
                            <?php /* <div class="form-group">
                              <label for="type">Branch</label><span style="color:red">*</span>
                              <select class="form-control"  name="branch" id="branch" disabled="">
                              <option value="">Select Branch</option>
                              <?php
                              foreach ($branch_list as $row) {
                              ?>
                              <option value="<?php echo $row['id']; ?>" <?php
                              if ($branch[0]->id == $row['id']) {
                              echo "selected";
                              }
                              ?>><?php echo ucwords($row['branch_name']); ?></option>
                              <?php } ?>
                              </select>
                              <span class="errorvalidation" id="error_branch" style="color:red"></span>
                              <?php echo form_error('branch'); ?>
                              </div>
                             */ ?>

                            <div class="form-group">
                                <label for="name">Date Of Receipt</label><span style="color:red">*</span>
                                <input type="text"  name="receipt_date" class="form-control datepicker-input" id="receipt_date" placeholder="Receipt Date" value="<?php echo set_value('receipt_date'); ?>">
                                <span class="errorvalidation" id="error_receipt_date" style="color:red"></span>
                                <?php echo form_error('receipt_date'); ?>
                            </div>

                            <div class="form-group">
                                <label for="name">Amount</label><span style="color:red">*</span>
                                <input type="text"  name="installment" class="form-control" id="installment" placeholder="Installment" value="<?php echo set_value('installment'); ?>">
                                <span class="errorvalidation" id="error_installment" style="color:red"></span>
                                <?php echo form_error('installment'); ?>
                            </div>

                            <div class="form-group">
                                <label for="type">Mode Of Payment</label><span style="color:red">*</span>
                                <select class="form-control"  name="payment_mode" id="payment_mode">
                                    <option value="">Select Payment Mode</option>
                                    <option value="Payumoney">Payumoney</option>
                                    <option value="NEFT/RTGS">NEFT/RTGS</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Cash">Cash</option>
                                </select>
                                <span class="errorvalidation" id="error_payment_mode" style="color:red"></span>
                                <?php echo form_error('payment_mode'); ?>
                            </div>

                            <div class="form-group">
                                <label for="name">Payment Details</label><span style="color:red">*</span>
                                <textarea type="text"  name="remark" class="form-control" id="remark" placeholder="Payment Details" ></textarea>
                                <span class="errorvalidation" id="error_remark" style="color:red"></span>
                                <?php echo form_error('remark'); ?>
                            </div>


                    </div>
                </div>

                <div class="box-footer">
                    <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </div>
                </form>
            </div><!-- /.box -->
            <script type="text/javascript">
                $("#branch_form").submit(function (event) {
                    //var branch = $('#branch').val();
                    var installment = $('#installment').val();
                    var payment_mode = $('#payment_mode').val();
                    var receipt_date = $('#receipt_date').val();
                    var remark = $('#remark').val();


                    var error = 0;
                    $('.errorvalidation').html("");

                    if (payment_mode == '') {
                        $('#error_payment_mode').html("Please Select Payment Mode");
                        error = 1;
                    }

                    if (remark == '') {
                        $('#error_remark').html("Please Select Payment Details");
                        error = 1;
                    }

                    if (receipt_date == '') {
                        $('#error_receipt_date').html("Please Select Date Of Receipt");
                        error = 1;
                    }


                    if (installment != '') {
                        if (isNaN(installment)) {
                            $('#error_installment').html("Please Enter Valid Installment");
                            error = 1;
                        }

                    } else {
                        $('#error_installment').html("Please Enter Installment");
                        error = 1;
                    }
                    if (error == 1) {
                        return false;
                    } else {
                        return true;
                    }

                });
                $(document).ready(function () {
                    $("#installment").keydown(function (e) {
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
            </script>
        </div>
    </div>
</section>
