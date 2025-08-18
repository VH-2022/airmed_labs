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
        Branch Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list"><i class="fa fa-users"></i>Branch List</a></li>
        <li class="active">Add Branch</li>
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
                        <form role="form" action="<?php echo base_url(); ?>Branch_Master/Branch_add" method="post" enctype="multipart/form-data" id="branch_master_id" onsubmit="return jvalidation();">

                             <div class="form-group">
                                <label for="type">Branch Type</label><span style="color:red">*</span>

                                <select class="form-control"  name="branch_type_fk" id="branch_type" onchange="getBranch();">
                                    <option value=""> Select Branch Type</option>
                                    <?php
                                    foreach ($branch_type as $row) {
                                        if ($row > 0) {
                                            ?>
                                            <option value="<?php echo $row['id']; ?>-<?php echo $row['type'];?>"><?php echo ucwords($row['name']); ?></option>
                                            
                                            <?php
                                        } else {
                                            echo '<option value="">Branch is Not Avaliable</option>';
                                        }
                                    }
                                    ?> 

                                </select>
                                <span class="errmsg"></span>
                                <?php echo form_error('branch_type_fk'); ?>
                            </div>

                            <div class="form-group" id="branch_type_id">
                                <label for="type">Select PLM</label><span style="color:red">*</span>

                                <select class="form-control"  name="parent_fk" id="parent_id">
                                   <option value="">Select PLM</option>
                                   <?php
                                    foreach ($branch_list as $row) {
                                        if ($row > 0) {
                                            ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo ucwords($row['branch_name']); ?></option>
                                            
                                            <?php
                                        } else {
                                            echo '<option value="">Branch is Not Avaliable</option>';
                                        }
                                    }
                                    ?> 
                                </select>
                               <span class="errmsg1"></span>
                                <?php echo form_error('parent_fk'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Branch Code</label><span style="color:red">*</span>
                                <input type="text"  name="branch_code" class="form-control" id="branch_code" placeholder="Branch Code" onblur="OnBlurInput(id)" value="<?php echo set_value('branch_code'); ?>" >
<span class="errmsg2"></span>
                                <?php echo form_error('branch_code'); ?>
                            </div>


                            <div class="form-group">
                                <label for="name">Branch Name</label><span style="color:red">*</span>
                                <input type="text"  name="branch_name" class="form-control" id="branch_name" placeholder="Branch Name" onblur="OnBlurInput(id)" value="<?php echo set_value('branch_name'); ?>" >
<span class="errmsg3"></span>
                                <?php echo form_error('branch_name'); ?>
                            </div>




                            <div class="form-group">
                                <label for="type">City</label><span style="color:red">*</span>

                                <select class="form-control" id="city" onchange="test(this.value)" name="city">
                                    <option value=""> Select City</option>
                                    <?php
                                    foreach ($city as $row) {
                                        if ($row > 0) {
                                            ?>
                                            <option value="<?php echo $row['cid']; ?>"><?php echo ucwords($row['city_name']); ?></option>
                                            <?php
                                        } else {
                                            echo '<option value="">city is Not Avaliable</option>';
                                        }
                                    }
                                    ?> 

                                </select>
                                <span class="errmsg4"></span>
                                <?php echo form_error('city'); ?>
                            </div>
                          <div class="form-group">
                                <label for="type">Address</label>
                                <?php echo form_textarea(['class' => 'form-control', 'rows' => '3', 'cols' => '4', 'name' => 'address']); ?>

                                <?php echo form_error('address'); ?>
                            
                            </div>
                            <div class="form-group">
                                <label for="name">Bank Name</label>

                               <input type="text"  name="bank_name" class="form-control" id="branch_name" placeholder="Bank Name"  value="<?php echo set_value('bank_name'); ?>" >
                            <?php echo form_error('bank_name'); ?>
                         

                            </div>
                            
                            <div class="form-group">
                                <label for="name">Bank Account No</label>

                               <input type="text"  name="bank_acc_no" class="form-control" id="branch_name" placeholder="Bank Account No"  value="<?php echo set_value('bank_acc_no'); ?>" >
                            <?php echo form_error('bank_acc_no'); ?>

                            </div>
                          

    <div class="form-group">
                                <label for="name">Bank  details</label>

                               <?php echo form_textarea(['class' => 'form-control', 'rows' => '3', 'cols' => '4', 'name' => 'bankdetils']); ?>

                            </div>

                             <div class="form-group" id="auto_completed_div"> 
                                <label for="name">Auto Complete Job</label> &nbsp;&nbsp;
                                <input type="radio" name="auto_completejob" value="1"> Yes &nbsp;&nbsp;
                                <input type="radio" name="auto_completejob" value="0" checked> No
                            </div>
                            
                            <div class="form-group" id="sms_email">
                                <input type="checkbox" name="sms_alert" id="sms_alert" value="1"> SMS Alert &nbsp;&nbsp;
                                <input type="checkbox" name="email_alert" id="email_alert" value="1"> Email Alert
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="ipd" id="ipd" value="1"> IPD &nbsp;&nbsp;
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




            <script>
                $city_cnt = 0;
                function get_city_price() {
                    var city_val = $("#city").val();
                    $("#city_error").html("");
                    $("#price_error").html("");
                    var cnt = 0;
                    if (city_val.trim() == '') {
                        $("#city_error").html("City is required.");
                        cnt = cnt + 1;
                    }
                    var price_val = $("#price").val();
                    if (!CheckNumber(price_val)) {
                        $("#price_error").html("Invalid price.");
                        cnt = cnt + 1;
                    }
                    if (cnt > 0) {
                        return false;
                    }
                    var skillsSelect = document.getElementById("city");
                    var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                    $("#city_wiae_price").append('<tr id="tr_' + $city_cnt + '"><td>' + selectedText + '<input type="hidden" name="city[]" value="' + skillsSelect.value + '"/></td><td>' + price_val + '<input type="hidden" name="price[]" value="' + price_val + '"/></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\')">Delete</a></td></tr>');
                    $city_cnt = $city_cnt + 1;
                    $("#price").val("");
                    $('#exampleModal').modal('hide');

                }

                function CheckNumber(nmbr) {
                    var filter = /^[0-9-+]+$/;
                    if (filter.test(nmbr)) {
                        return true;
                    } else {
                        return false;
                    }
                }


                function OnFocusInput(input) {
                    input.style.color = "red";
                }

                function OnBlurInput(input) {
                    this.value == "";
                }

            </script>
            <script  type="text/javascript">
                $(document).ready(function () {
                    $("#showHide").click(function () {
                        if ($("#password").attr("type") == "password") {
                            $("#password").attr("type", "text");
                        } else {
                            $("#password").attr("type", "password");
                        }

                    });
                });
            </script>

        <!-- Vishal COde Start -->
        <script type="text/javascript">
            function getBranch(){
                var branch_id = $('#branch_type').val();
              
            var arr = branch_id.split('-');
            if(arr[1] == 1){
              $('#branch_type_id').show();

            }else{
                $('#branch_type_id').hide();
                }
            }
            getBranch();

            function jvalidation(){
                var branch_id = $('#branch_type').val();
                var id =branch_id.split('-');
                var parent = $('#parent_id').val();
                var branch_code = $('#branch_code').val();
                var branch_name = $('#branch_name').val();
                var city = $('#city').val();
                
                var error_cnt=0;
                if(branch_id ==''){
                    $('.errmsg').html("Branch Type Required");
                    error_cnt=1; 
                }else{
                    $('.errmsg').html("");
                }
                if(id[1] =="1"){
                    if(parent == ''){
                        $('.errmsg1').html("PLM Required");

                        error_cnt=1;     
                    }else{
                     $('.errmsg1').html("");
                }
                }
                if(branch_code ==''){
                        $('.errmsg2').html("Branch Code Required");
                        error_cnt=1;
                       
                }else{
                    $('.errmsg2').html("");
                }
                if(branch_name == ''){
                      $('.errmsg3').html("Branch Name Required");
                      error_cnt=1;
                      
                }else{
                    $('.errmsg3').html("");
                }

                var city = $('#city').val();
                if(city ==''){
                  $('.errmsg4').html("City Required");
                        error_cnt=1; 
                }else{
                    $('.errmsg4').html("");
                }
                if(error_cnt==1){
                    return false;
                }
            }
        </script>
        <!-- Vishal COde End -->
        </div>
    </div>
</section>
