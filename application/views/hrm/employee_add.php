<!-- Page Heading -->
<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>/plugins/TimePicki-master/css/timepicki.css" rel="stylesheet">

<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Employee<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>hrm/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/employee"><i class="fa fa-users"></i> Employee List</a></li>
            <li class="active"> Add Employee</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <form role="form" action="<?php echo base_url(); ?>hrm/employee/add" method="post" id="department_form" enctype="multipart/form-data">
                    <section class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <!-- form start -->
                                        <h3 class="panel-title">Personal Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="widget">
                                            <?php if ($error) { ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                    <?php echo $error; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Photo </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <img src="<?php echo base_url(); ?>upload/employee/default_avatar.jpg" height="150px" width="150px" id="show_photo">
                                                    <br>
                                                    <a class="label label-danger" href="javascript:void(0);" id="remove_button" onclick="remove_photo();" style="display:none;">Remove</a><br>
                                                    <input type="file" name="photo" id="photo" class="upload_image">
                                                    <p><span class="label label-danger"> NOTE! </span>
                                                        Image Size must be (872px by 724px) </p>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Name <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="name" name="name" placeholder="Employee Name" class="form-control" value="<?php echo set_value('name'); ?>"/>
                                                    <span id="nameerror" class="errorall" style="color:red;"><?php echo form_error('name'); ?></span>
                                                </div>
                                                <span style="color:red;" id="name_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Father's Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="father" name="father" placeholder="Father Name" value="<?php echo set_value('father'); ?>" class="form-control"/>
                                                </div>
                                                <span style="color:red;" id="father_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date of Birth </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="dob" class="form-control datepicker-input" name="dob" value="<?php echo set_value('dob'); ?>"/> 
                                                </div>
                                                <span style="color:red;" id="dob_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select> 
                                                    <span style="color:red;" id="gender_error"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0"> Phone </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="phone" id="phone" placeholder="Enter Phone" type="text" value="<?php echo set_value('phone'); ?>">
                                                </div>
                                                <span style="color:red;" id="phone_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Local Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="local_address" name="local_address" placeholder="Local Address" class="form-control"><?php echo set_value('local_address'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Permanent Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="permanent_address" name="permanent_address" placeholder="Permanent Address" class="form-control"><?php echo set_value('permanent_address'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">City <span style="color:red;">*</span></label>

                                                <div class="col-sm-9 pdng_0">
                                                    <select name="city_data" id="city_data" class="form-control">
                                                        <option value="">--Select City--</option>
                                                        <?php foreach ($city_list as $city) { ?>
                                                            <option value="<?= $city->id; ?>"><?= ucwords($city->name); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="cityerror" class="errorall" style="color:red;"><?php echo form_error('city_data'); ?></span>
                                                    <?php echo form_error('city'); ?>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Branch </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="branch_data" id="branch_data" class="form-control">
                                                        <option value="">--Select Branch--</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <h3>Account Login</h3>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Email <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="email" placeholder="Email" id="email" value="<?php echo set_value('email'); ?>" type="email">
                                                    <span id="emailerror" class="errorall" style="color:red;"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Password <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="password" name="password" placeholder="Password" id="password" class="form-control">
                                                    <span id="passworderror" class="errorall" style="color:red;"><?php echo form_error('password'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="start_time" class="col-sm-3 pdng_0">In Time</label>
<!--                                                </label><span style="color:red">*</span>-->
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="in_time" class="form-control" id="timepicker1" placeholder="In Time" onblur="OnBlurInput(id)">
                                                </div>
                                                <?php //echo form_error('start_time'); ?>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="end_time" class="col-sm-3 pdng_0">Out Time</label>
<!--                                                </label><span style="color:red">*</span>-->
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="out_time" class="form-control" id="timepicker2" placeholder="Out Time" onblur="OnBlurInput(id)">
                                                </div>
                                                <?php //echo form_error('end_time'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Company Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Employee ID <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="employee_id" id="employee_id" placeholder="Employee ID" readonly="" value="<?php echo $new_employee_id[0]["new_id"]; ?>" class="form-control">
                                                    <span id="empiderror" class="errorall" style="color:red;"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Email</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_email" id="company_email" placeholder="Company Email" value="<?php echo set_value('company_email'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Mobile</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_mobile" id="company_mobile" placeholder="Company Mobile" value="<?php echo set_value('company_mobile'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Department </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select class="form-control" name="department" id="department" onchange="designation_list(this.value);">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($department_list as $department) { ?>
                                                            <option value="<?php echo $department->id; ?>"><?php echo ucwords($department->name); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Designation </label>
                                                <div class="col-sm-9 pdng_0" id="designation_div">
                                                    <select class="form-control" name="designation" id="designation">
                                                        <option value="">--Select--</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Date of Joining </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="date_joining" id="date_joining" value="<?php echo set_value('date_joining'); ?>" class="form-control datepicker-input">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Joining Salary</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="joinnig_salary" id="joinnig_salary" placeholder="Current Salary" value="<?php echo set_value('joinnig_salary'); ?>" class="form-control">
                                                    <span id="join_salary_error" class="errorall" style="color:red;"><?php echo form_error('joinnig_salary'); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-sm-12  pdng_0" id="sal_option" style="display:none">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">PF Option</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="radio" name="sal_option" value="1"> With PF &nbsp;
                                                    <input type="radio" name="sal_option" value="0" checked> Without PF<br>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Salary Structure</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select class="form-control" name="master_salary" id="master_salary">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($salary_list as $sala) { ?>
                                                            <option value="<?php echo $sala->id; ?>"><?php echo ucwords($sala->salary_strucure_name); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="col-md-6" id="salary_details" name="salary_details" style="display:none">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Salary Structure (Monthly)</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12 salary_data">

                                        </div>
                                        <input type="hidden" id="emppf" name="emppf" value="0">
                                        <input type="hidden" id="empesic" name="empesic" value="0">
                                    </div>
                                </div>
                            </div>




                            <div class="col-md-6">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Bank Account Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Holder Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="holder_name" id="holder_name" placeholder="Account Holder Name" value="<?php echo set_value('holder_name'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="account_number" id="account_number" placeholder="Account Number" value="<?php echo set_value('account_number'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Bank Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="bank_name" id="bank_name" placeholder="BANK Name" value="<?php echo set_value('bank_name'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">IFSC Code </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" value="<?php echo set_value('ifsc_code'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">PAN Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="pan_number" id="pan_number" placeholder="PAN Number" value="<?php echo set_value('pan_number'); ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Branch </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="branch" id="branch" placeholder="BRANCH" value="<?php echo set_value('branch'); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37;">
                                        <h3 class="panel-title">Documents</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="widget">
                                            <?php if ($error_doc) { ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                    <?php echo $error_doc; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Resume </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="resume" id="resume">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Offer Letter </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="offer_letter" id="offer_letter">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Joining Letter </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="joining_letter" id="joining_letter">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Contract and Agreement </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="contract_agreement" id="contract_agreement">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">ID Proof </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="file" name="id_proof" id="id_proof">
                                                </div>
                                            </div>
                                            <p><span class="label label-danger"> NOTE! </span>
                                                &nbsp; Allowed types are (PDF, DOC) </p>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> ADD</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
        <!-- /.row -->
    </section>
</div>
<script>
    function remove_photo() {
        var url = "<?php echo base_url(); ?>upload/employee/default_avatar.jpg";
        $('#show_photo').attr('src', url);
        $("#remove_button").hide();
        $(".upload_image").val('');
    }
    function designation_list(value) {
        var url = "<?php echo base_url(); ?>hrm/employee/get_designation";
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"did": value}, // serializes the form's elements.
            success: function (response)
            {

                var $el = $("#designation");
                $el.empty(); // remove old options
                $el.append($("<option></option>").attr("value", '').text('Select Designation'));

                $.each(response, function (index, data) {
                    $('#designation').append('<option value="' + data['id'] + '">' + data['name'] + '</option>');
                });
            }
        });
    }

    //Get Branch
    $('#city_data').change(function () {
        var url = "<?php echo base_url(); ?>hrm/employee/get_branch";
        var value = $("#city_data").val();
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {"id": value},
            success: function (response)
            {
                var $el = $("#branch_data");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Branch'));

                $.each(response, function (index, data) {
                    $('#branch_data').append('<option value="' + data['id'] + '">' + data['branch_name'] + '</option>');
                });
            }
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {

                $('#show_photo').attr('src', e.target.result);
                $("#remove_button").show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".upload_image").change(function () {
        readURL(this);
    });

//    $(document).on('change', '#salerypf', function () {
//        var ischecked = $(this).is(':checked');
//        if (ischecked) {
//            $(".pfcontain").show();
//        } else {
//            $(".pfcontain").hide();
//        }
//        salerycount();
//    });
//
//    $(document).on('change', '#saleryctc', function () {
//        var ischecked = $(this).is(':checked');
//        if (ischecked) {
//            $(".ctccontain").show();
//        } else {
//            $(".ctccontain").hide();
//        }
//        salerycount();
//    });

    $(document).on('keyup', '#joinnig_salary', function (e) {
        var joinnig_salary1 = parseFloat($("#joinnig_salary").val().trim());
//        if (joinnig_salary1 >= 21000) {
//            if ($("#salerypf").prop("checked") == true) {
//                $('#salerypf').trigger('click');
//            }
//            if ($("#saleryctc").prop("checked") == true) {
//                $('#saleryctc').trigger('click');
//            }
//            $('#salerypf,#saleryctc').attr('disabled', true);
//        } else {
//            $('#salerypf,#saleryctc').attr('disabled', false);
//
//            if ($("#salerypf").prop("checked") == false) {
//                $('#salerypf').trigger('click');
//            }
//            if ($("#saleryctc").prop("checked") == false) {
//                $('#saleryctc').trigger('click');
//            }
//        }

        if (joinnig_salary1 >= 21000) {
            $("#sal_option").show();
        }else{
            $("#sal_option").hide();
        }
        salerycount();
    });

//    $(document).on('keyup', '.calsalury', function () {
//        // salerycount();
//        var joinnig_salary = parseFloat($("#joinnig_salary").val().trim());
//        var other = 0;
//        var saleryvalue = 0;
//        $(".calsalury").each(function (index, element) {
//            if ($(this).is(':visible')) {
//
//                saleryvalue = $(this).val();
//                other = other + parseFloat(saleryvalue);
//            }
//        });
//        $("#othersalry").val((joinnig_salary - other).toFixed(2));
//    });

    function salerycount() {
        var joinnig_salary = parseFloat($("#joinnig_salary").val().trim());
        //var other = 0;
        $(".calsalury").each(function (index, element) {
            if ($(this).is(':visible')) {

                var id = this.id;
                var idsplit = id.split("_");
                var salstrid = idsplit[1];
                var salerytytype = idsplit[2];
                var saleryvalue = idsplit[3];

//                if ($(this).val() != "") {
//                    saleryvalue = $(this).val();
//                }

                if (joinnig_salary != "" && parseFloat(joinnig_salary) != null) {
                    if (salerytytype == 2) {
                        if (salstrid == 1 || salstrid == 2 || salstrid == 3) {
                            // if pf or esic then calculate from basic                            
                            var basic = $("input[name=salary_value_0]").val();
                            var svalue = (parseFloat(saleryvalue) * basic / 100).toFixed(2);
                        } else {
                            // else calculate from main salary
                            var svalue = (parseFloat(saleryvalue) * joinnig_salary) / 100;
                        }
                    } else {
                        var svalue = parseFloat(saleryvalue);
                    }
                } else {
                    var svalue = "";
                }

                if (!isNaN(svalue)) {
                    var svalue = svalue;
                } else {
                    var svalue = "";
                }

                //other = other + parseFloat(svalue);
                $('input[id="' + id + '"]').val(parseFloat(svalue));
                //                $("#" + id).val(parseFloat(svalue));
            }
        });

        //$("#othersalry").val((joinnig_salary - other).toFixed(2));
    }



//    $("#department,#designation").change(function () {
//
//        var url = "<?php //echo base_url();       ?>hrm/employee/get_salaray_strucuture";
//        var department = $("#department").val();
//        var designation = $("#designation").val();
//        var joinnig_salary = parseFloat($("#joinnig_salary").val().trim());
//
//        if (department != "" && designation != "") {
//            $.ajax({
//                type: "POST",
//                url: url,
//                dataType: 'json',
//                data: {"department": department, "designation": designation},
//                success: function (data)
//                {
//                    var cnt = 0, html_data = '<label class = "col-sm-3 pdng_0"></label><div class = "col-sm-9 pdng_0"><label class="checkbox-inline"><input type="checkbox" name= "pf_check" checked id="salerypf" value="1">PF</label><label class="checkbox-inline"><input id="saleryctc" checked type="checkbox" name= "pf_esic" value="1">ESIC</label></div>';
//                    var other = 0;
//                    var salpf = 0;
//                    var esic = 0;
//                    for (var i = 0; i < data.length; i++) {
//                        if (!isNaN(parseFloat(joinnig_salary)) && !isNaN(parseFloat(data[cnt].value))) {
//                            if (data[cnt].cutofftype == 2) {
//                                var svalue = (parseFloat(data[cnt].value) * joinnig_salary / 100).toFixed(2);
//                            } else {
//                                var svalue = parseFloat(data[cnt].value).toFixed(2);
//                            }
//                        } else {
//                            var svalue = "";
//                        }
//
//                        if (!isNaN(svalue)) {
//                            var svalue = svalue;
//                        } else {
//                            var svalue = "";
//                        }
//
//                        if (i == 1 || i == 2) {
//                            var divid = "pfcontain";
//                            salpf = salpf + svalue;
//                        } else if (i == 3) {
//                            var divid = "ctccontain";
//                            esic = esic + svalue;
//                        } else {
//                            var divid = "";
//                        }
//
//                        html_data = html_data + '<div class="' + divid + '"><div class="form-group col-sm-12  pdng_0"><label class = "col-sm-3 pdng_0"> ' + data[cnt].salary_name + ' </label><div class = "col-sm-9 pdng_0"><input type="text" id="salaryvalue_' + cnt + '_' + data[cnt].cutofftype + '_' + data[cnt].value + '" name="salary_value_' + cnt + '"  placeholder="Enter Monthly Salary value" value="' + svalue + '" class="form-control calsalury"></div></div><input type="hidden" name="salary_id_' + cnt + '" value="' + data[cnt].id + '"></div>';
//                        cnt++;
//                        other = other + parseFloat(svalue);
//                    }
//
//                    $("#emppf").val(salpf);
//                    $("#empesic").val(esic);
//
//                    if (!isNaN(parseFloat(joinnig_salary))) {
//                        var otherval = joinnig_salary - other;
//                    } else {
//                        var otherval = "";
//                    }
//
//                    html_data = html_data + '<div class="form-group col-sm-12  pdng_0"><label class = "col-sm-3 pdng_0">other</label><div class = "col-sm-9 pdng_0"><input type="text" id="othersalry"  name="salary_other" value="' + otherval + '" class="form-control"></div></div>';
//
//                    html_data = html_data + '<input type="hidden" name="count" value="' + cnt + '">';
//
//                    $('.salary_data').html(html_data);
//                    $("#salary_details").show();
//                }
//            });
//        } else {
//            $("#salary_details").hide();
//        }
//
//    });


    $("#master_salary").change(function () {

        var url = "<?php echo base_url(); ?>hrm/employee/get_salaray_strucuture_new";
//        var department = $("#department").val();
//        var designation = $("#designation").val();
        var master_salary = $("#master_salary").val();
        var joinnig_salary = parseFloat($("#joinnig_salary").val().trim());
        var sal_option = $("input[name='sal_option']:checked").val();

        if (master_salary != "") {
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {master_salary: master_salary,salary:joinnig_salary,sal_option:sal_option},
                success: function (data)
                {
                    var cnt = 0;
                    //var html_data = '<label class = "col-sm-3 pdng_0"></label><div class = "col-sm-9 pdng_0"><label class="checkbox-inline"><input type="checkbox" name= "pf_check" checked id="salerypf" value="1">PF</label><label class="checkbox-inline"><input id="saleryctc" checked type="checkbox" name= "pf_esic" value="1">ESIC</label></div>';
                    var html_data = "";
                    //var other = 0;
                    //var salpf = 0;
                    //var esic = 0;
                    for (var i = 0; i < data.length; i++) {
                       
                            var svalue = data[cnt].new_value;
                        
                        

                       
                        var divid = "";

                        html_data = html_data + '<div class="' + divid + '"><div class="form-group col-sm-12  pdng_0"><label class = "col-sm-3 pdng_0"> ' + data[cnt].salary_name + ' </label><div class = "col-sm-9 pdng_0"><input type="text" id="salaryvalue_' + cnt + '_' + data[cnt].cutofftype + '_' + data[cnt].new_value + '" name="salary_value_' + cnt + '"  placeholder="Enter Monthly Salary value" value="' + svalue + '" class="form-control calsalury"></div></div><input type="hidden" name="salary_id_' + cnt + '" value="' + data[cnt].id + '"></div>';
                        cnt++;
                        //other = other + parseFloat(svalue);
                    }

//                    $("#emppf").val(salpf);
//                    $("#empesic").val(esic);

//                    if (!isNaN(parseFloat(joinnig_salary))) {
//                        var otherval = joinnig_salary - other;
//                    } else {
//                        var otherval = "";
//                    }

//                    html_data = html_data + '<div class="form-group col-sm-12  pdng_0"><label class = "col-sm-3 pdng_0">other</label><div class = "col-sm-9 pdng_0"><input type="text" id="othersalry"  name="salary_other" value="' + otherval + '" class="form-control"></div></div>';

                    html_data = html_data + '<input type="hidden" name="count" value="' + cnt + '">';

                    $('.salary_data').html(html_data);
                    $("#salary_details").show();
                }
            });
        } else {
            $("#salary_details").hide();
        }

    });

</script>
<script>
    $("#department_form").submit(function (e) {
        var error = 1;
        var name = $("#name").val().trim();
        var city_data = $("#city_data").val()

        var email = $("#email").val().trim();
        var password = $("#password").val().trim();
        var employee_id = $("#employee_id").val().trim();
        var joinnig_salary = $("#joinnig_salary").val().trim();
        $(".errorall").html("");

        if (name == "") {
            error = 0;
            $("#nameerror").html("The Name field is required.");
        }

        if (city_data == "") {
            error = 0;
            $("#cityerror").html("The City field is required.");
        }
        if (email == "") {
            error = 0;
            $("#emailerror").html("The Email field is required.");
        }
        if (password == "") {
            error = 0;
            $("#passworderror").html("The Password field is required.");
        }
        
        if (joinnig_salary != "") {
            if (isNaN(joinnig_salary)) {
                error = 0;
                $("#join_salary_error").html("Enter joining salary in number.");
            }
        }
        if (error == 1) {
            return true;
        } else {
            return false;
        }
    });
</script>
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
</script>
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
<script>
    $('#timepicker1').timepicki({
        show_meridian: false,
        min_hour_value: 0,
        max_hour_value: 23,
        overflow_minutes: true,
        increase_direction: 'up',
        disable_keyboard_mobile: true
    });
</script>

<script>
    $('#timepicker2').timepicki({
        show_meridian: false,
        min_hour_value: 0,
        max_hour_value: 23,
        overflow_minutes: true,
        increase_direction: 'up',
        disable_keyboard_mobile: true});
</script>