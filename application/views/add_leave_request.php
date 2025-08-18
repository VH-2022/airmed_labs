<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!--<link href="http://airmedpathlabs.info/sales/assets/datetime/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">-->
<link href="http://airmedpathlabs.info/sales/assets/datetime/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>-->


<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">-->
<!--<link href="http://airmedpathlabs.info/sales/assets/css/style.css" rel="stylesheet" type="text/css" />-->


<section class="content-header">
    <h1>
        Add Leave Request
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Leave_applications/index"> Leave Request List</a></li>
        <li class="active">Add Leave Request</li>
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
                <form role="form" action="<?php echo base_url(); ?>Leave_applications/add" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">

                            <!--                            <div class="form-group">
                                                            <label for="exampleInputFile">Start Date</label><span style="color:red">*</span>
                                                            <input type="text"  name="start_date" class="form-control datepicker-input" placeholder="Start Date" value="<?php echo set_value('start_date') ?>">
                                                            <span style="color: red;"><?php //echo form_error('start_date'); ?></span>
                                                        </div>-->

                            <div class="form-group">
                                <label for="input-text" class="control-label">Start Date <span style="color:red;">*</span></label>
                                <div class="">
                                    <input type="text" id="start_date"  name="start_date" class="form-control datepicker" placeholder=" Start Date" value="<?php echo set_value('start_date') ?>">
                                    <span style="color: red;"><?= form_error('start_date'); ?></span>
                                </div>
                            </div>

                            <!--                            <div class="form-group">
                                                            <label for="exampleInputFile">End Date</label><span style="color:red">*</span>
                                                            <input type="text"  name="end_date" class="form-control datepicker-input" placeholder="End Date" value="<?php echo set_value('end_date') ?>">
                                                            <span style="color: red;"><?php //echo form_error('end_date'); ?></span>
                                                        </div>-->


                            <div class="form-group">
                                <label for="input-text" class="control-label">End Date <span style="color:red;">*</span></label>
                                <div class="">
                                    <input type="text" id="end_date"  name="end_date" class="form-control datepicker" placeholder=" End Date" value="<?php echo set_value('end_date') ?>">
                                    <span style="color: red;"><?= form_error('end_date'); ?></span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputFile">Remark</label><span style="color:red">*</span>
                                <textarea rows="3" cols="50" name="remark" class="form-control"></textarea>
                                <span style="color: red;"><?= form_error('remark'); ?></span>
                            </div>


                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
            </div>
            </form>
        </div><!-- /.box -->
    </div>
</div>
</div>
</section>
<script type="text/javascript" src="http://airmedpathlabs.info/sales/assets/datetime/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
//    $(function () {
//        $('#datetimepicker1').datetimepicker();
//    });

    $('#start_date').datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
    });
    $('#end_date').datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
    });


</script>