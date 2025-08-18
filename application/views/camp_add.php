<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/2.0.1/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<section class="content-header">
    <h1>
        Camp SMS Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list"><i class="fa fa-users"></i>Camp SMS  List</a></li>
        <li class="active">Add Camp SMS</li>
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
                        <form role="form" action="<?php echo base_url(); ?>Camp_sms/add" method="post" enctype="multipart/form-data" id="branch_master_id">

                             <div class="form-group">
                                <label for="type">SMS</label><span style="color:red">*</span>

                                <textarea name="sms_name" class="form-control"></textarea>
                                <span class="errmsg"></span>
                                <span style="color:red"> <?php echo form_error('sms_name'); ?></span>
                            </div>

                            <div class="form-group" id="branch_type_id">
                                <label for="type">Schedule Date & Time</label><span style="color:red">*</span>

                             <input type="text" name="schedule_date" class="form-control form_datetime" id="datetime">
                             <span style="color:red"> <?php echo form_error('schedule_date'); ?></span>
                            </div>
                             <div class="form-group">
                      <label for="exampleInputFile">Import Doctor List</label><span style="color:red">*</span>
                       <input type="file" name="doctor_list">
                       <p style="color:red;">Allow File type .csv</p>
                       <p style="color:red;">Upload CSV format:-no,mobile</p>
                       <span style="color:red;"><?php echo form_error('doctor_list'); ?></span>
                    
                    </div>


                            <div class="form-group">
                                <label for="name">Sender Name</label><span style="color:red">*</span>
                                  <select name="sender" class="form-control">
                                    <option value="">Select Sender</option>
                                    <option value="1">Mobi blogdns</option>
<!--                                    <option value="2">Leeway softech</option>-->
                                  </select>
                                 <span style="color:red"><?php echo form_error('sender'); ?></span>
                            </div>
               <div class="box-footer">
                    <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </div>
                </div>

               
                </form>
            </div><!-- /.box -->

          </div>
    </div>
</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>                       
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript">
$("#datetime").datetimepicker({
    format: 'DD-MM-YYYY HH:mm:ss'
});
</script>