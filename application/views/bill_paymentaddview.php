<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Bill Payment
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>bill_master/bill_list"><i class="fa fa-users"></i>Bill List</a></li>
        <li class="active">Bill Payment</li>
    </ol>
</section>

<section class="content">
    <div class="row">

        <div class="col-md-12">

            <div class="box box-primary">

                <?php if ($this->session->flashdata('duplicate')) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $this->session->flashdata('duplicate'); ?>
                    </div>
                <?php } ?>
                <div class="box-body">

                    <div class="box-header">
                        <h1 class="box-title">Bill Payment Details</h1>
                    </div><br>

                    <div class="col-md-6">



                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Bill Date : </label><?= date("d-m-Y", strtotime($billdetils[0]["expense_date"])); ?>

                        </div>

                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Branch : </label><?php echo $billdetils[0]["branch_name"]; ?>

                        </div>

                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Category : </label><?php echo $billdetils[0]["CategoryName"]; ?>

                        </div>

                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Description : </label><?php echo $billdetils[0]["description"]; ?>

                        </div>

                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Amount : </label><?php echo $billdetils[0]["amount"]; ?>

                        </div>

                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Added By : </label><?php echo $billdetils[0]["name"]; ?>

                        </div>

                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Bill Last Date : </label><?php
                            if (!empty($billdetils[0]["last_date"])) {
                                echo date("d-m-Y", strtotime($billdetils[0]["last_date"]));
                            }
                            ?>

                        </div>
                        <div class="form-group col-sm-12  pdng_0 mrgn_0">
                            <label for="exampleInputFile" class="col-sm-3 pdng_0">Status : </label>
                                <?php if ($billdetils[0]['paystatus'] == 1) { ?><span class="label label-success ">Completed</span><?php } else if ($billdetils[0]['paystatus'] == 0) { ?><span class="label label-danger ">Pending</span><?php } else if ($billdetils[0]['paystatus'] == 2) { echo '<span class="label label-warning ">Booked</span>'; } ?>
                        </div>
                        <?php if (!in_array($billdetils[0]["paystatus"], array("1","2"))) { ?>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0"></label>
                                <a href="<?= base_url() ?>Bill_master/bill_booked/<?= $billid ?>" onclick="return confirm('Are you sure?');" class="btn btn-primary btn-sm">Mark as booked</a>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($billdetils[0]["upload_receipt"] != "") { ?>
                        <div class="col-md-6">
                            <a href="<?= base_url(); ?>upload/expense_master/<?php echo $billdetils[0]["upload_receipt"]; ?>" target="_blank"><img src="<?= base_url(); ?>upload/expense_master/<?php echo $billdetils[0]["upload_receipt"]; ?>" class="col-sm-12" alt=""/></a>
                        </div>
                    <?php } ?>


                </div>	
            </div>
        </div>

        <div class="col-md-12">

            <div class="box box-primary">

                <div class="box-body">
                    <?php if ($billdetils[0]["paystatus"] == 0) { ?>
                        <div class="box-header">
                            <h1 class="box-title">Pay Bill Payment</h1>
                        </div>
                    <?php } else { ?>

                        <div class="box-header">
                            <h1 class="box-title">Pay Bill Payment Details</h1>
                        </div><br>
                    <?php } ?>

                    <div class="col-md-6">
                        <?php if ($billdetils[0]["paystatus"] == 0 || $billdetils[0]["paystatus"] == 2) { ?>
                            <!-- form start -->
                            <form role="form" action="<?php echo base_url(); ?>bill_master/bill_payment/<?= $billid ?>" enctype="multipart/form-data" method="post" >

                                <div class="form-group">
                                    <label for="name">NEFT No</label><span style="color:red">*</span>
                                    <input type="text"  name="neftno" class="form-control" value="<?php echo set_value('neftno'); ?>">
                                    <?php echo form_error('neftno'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="name">Amount</label><span style="color:red">*</span>
                                    <input type="text"  name="amount" class="form-control" onblur="check_bill_amount(this.value);" value="<?php echo set_value('amount'); ?>">
                                    <?php echo form_error('amount'); ?>
                                </div>
                                <script>
                                    function check_bill_amount(val) {
                                        val = parseInt(val);
                                        var bill_amount = "<?= $billdetils[0]["amount"] ?>";
                                        bill_amount = parseInt(bill_amount);
                                        if (val > bill_amount) {
                                            alert("Pay amount is more then bill amount.");
                                        }
                                    }
                                </script>

                                <div class="form-group">
                                    <label for="name">Bill Copy</label>
                                    <input type="file"  name="upload_receipt" >

                                </div>

                                <div class="form-group">
                                    <label for="name">Remark</label>
                                    <textarea name="remark" class="form-control" value=""><?php echo set_value('remark'); ?></textarea>

                                </div>




                                <button class="btn btn-primary" type="submit">Pay</button>

                            </form>
                        <?php } else { ?>

                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Payment Date : </label><?= date("d-m-Y", strtotime($paydetils[0]["creteddate"])); ?>

                            </div>

                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">NEFT No : </label><?php echo $paydetils[0]["neftno"]; ?>

                            </div>

                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Amount : </label><?php echo $paydetils[0]["amount"]; ?>

                            </div>

                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Remark : </label><?php echo $paydetils[0]["remark"]; ?>

                            </div>
                            <div class="form-group col-sm-12  pdng_0 mrgn_0">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Pay by: </label><?php echo ucwords($paydetils[0]["name"]); ?>

                            </div>


                        <?php } ?>
                    </div><!-- /.box -->

                    <?php
                    if ($billdetils[0]["paystatus"] != 0) {
                        if ($paydetils[0]["biil_file"] != "") {
                            ?>
                            <div class="col-md-6">
                                <a href="<?= base_url(); ?>upload/expense_master/<?php echo $paydetils[0]["biil_file"]; ?>" target="_blank"><img src="<?= base_url(); ?>upload/expense_master/<?php echo $paydetils[0]["biil_file"]; ?>" class="col-sm-12" alt=""/></a>
                            </div>
                            <?php
                        }
                    }
                    ?>

                </div>	
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
<script>
    $(document).ready(function () {

        var date_input = $('input[name="expense_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
    });
</script>

