<!-- Page Heading -->
<style>
    #table_body td:nth-child(2n+1) {
        width: 100px;
    }
</style>
<?php //echo "<pre>"; print_r($query); exit;?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Employee<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>hrm/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>hrm/employee"><i class="fa fa-users"></i> Employee List</a></li>
            <li class="active"> Edit Employee</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <section class="content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="personal_form" action="<?php echo base_url(); ?>hrm/employee/personal_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px; color: white">Personal Details
                                        </h3>
                                        <button onclick="personal_data();" id="personal_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="personal_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>Personal Details Successfully updated.</span>
                                            </div>
                                            <div class="alert alert-success" style="display:none;" id="personal_alert1">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>Profile is Deactivated.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Photo </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <?php if ($query->photo != "" && file_exists(base_url() . "upload/employee/" . $query->photo)) { ?>
                                                        <img src="<?php echo base_url(); ?>upload/employee/<?php echo $query->photo; ?>" height="150px" width="150px" id="show_photo">
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>upload/employee/default_avatar.jpg" height="150px" width="150px" id="show_photo">
                                                    <?php } ?>
                                                    <br>

                                                    <?php if ($query->status != '2') { ?>
                                                        <button type="button" onClick="deactivate_profile()" id="deactivate" style="float:right">Deactivate Employee</button>
                                                    <?php } ?>

                                                    <a class="label label-danger" href="javascript:void(0);" id="remove_button" onclick="remove_photo();" style="display:none;">Remove</a><br>
                                                    <input type="file" name="photo" id="photo" class="upload_image">
                                                    <p><span class="label label-danger"> NOTE! </span>
                                                        Image Size must be (872px by 724px) </p>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Name <span style="color:red;">*</span></label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="name" name="name" placeholder="Employee Name" class="form-control" value="<?php echo $query->name; ?>"/>
                                                    <span style="color:red;"><?php echo form_error('name'); ?></span>
                                                    <span id="nameerror" class="errorall" style="color:red;"><?php echo form_error('name'); ?></span>
                                                </div>
                                                <span style="color:red;" id="name_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Father's Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="father" name="father" placeholder="Father Name" value="<?php echo $query->father_name; ?>" class="form-control"/>
                                                </div>
                                                <span style="color:red;" id="father_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Date of Birth </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" id="dob" class="form-control" name="dob" value="<?php echo $query->date_of_birth; ?>"/> 
                                                </div>
                                                <span style="color:red;" id="dob_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Gender </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select name="gender" id="gender" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="male" <?php
                                                        if ($query->gender == "male") {
                                                            echo "selected";
                                                        }
                                                        ?>>Male</option>
                                                        <option value="female" <?php
                                                        if ($query->gender == "female") {
                                                            echo "selected";
                                                        }
                                                        ?>>Female</option>
                                                    </select> 
                                                    <span style="color:red;" id="gender_error"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0"> Phone </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="phone" id="phone" placeholder="Phone" type="text" value="<?php echo $query->phone; ?>">
                                                </div>
                                                <span style="color:red;" id="phone_error"></span>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Local Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="local_address" name="local_address" placeholder="Local Address" class="form-control"><?php echo $query->address; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Permanent Address </label> 
                                                <div class="col-sm-9 pdng_0">
                                                    <textarea id="permanent_address" name="permanent_address" placeholder="Permanent Address" class="form-control"><?php echo $query->permanent_address; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">City <span style="color:red;">*</span></label>

                                                <div class="col-sm-9 pdng_0">
                                                    <select name="city_data" id="city_data" class="form-control">
                                                        <option value="">--Select City--</option>
                                                        <?php foreach ($city_list as $city) { ?>
                                                            <option value="<?= $city->id; ?>" <?php
                                                            if ($query->city == $city->id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?= ucwords($city->name); ?></option>
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
                                                        <?php
                                                        foreach ($branch_list as $b) {
                                                            ?>
                                                            <option value="<?= $b['id']; ?>" <?php
                                                            if ($b['id'] == $query->branch_fk) {
                                                                echo "selected";
                                                            }
                                                            ?>><?= ucwords($b['branch_name']); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <h3>Account Login</h3>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Email <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input class="form-control" name="email" placeholder="Email" id="email" value="<?php echo $query->email; ?>" type="email">
                                                    <span id="emailerror" class="errorall" style="color:red;"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Password <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="password" name="password" id="password" placeholder="Password" class="form-control" value="<?php echo $query->password; ?>">
                                                    <span id="passworderror" class="errorall" style="color:red;"><?php echo form_error('password'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="start_time" class="col-sm-3 pdng_0">In Time</label>
<!--                                                </label><span style="color:red">*</span>-->
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="in_time" class="form-control" id="timepicker1" placeholder="In Time" onblur="OnBlurInput(id)" value="<?php echo $query->in_time; ?>">
                                                </div>
                                                <?php //echo form_error('start_time'); ?>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label for="end_time" class="col-sm-3 pdng_0">Out Time</label>
<!--                                                </label><span style="color:red">*</span>-->
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="out_time" class="form-control" id="timepicker2" placeholder="Out Time" onblur="OnBlurInput(id)" value="<?php echo $query->out_time; ?>">
                                                </div>
                                                <?php //echo form_error('end_time'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <form role="form" id="company_form" action="<?php echo base_url(); ?>hrm/employee/company_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px; color: white">Company Details
                                        </h3>
                                        <button onclick="company_data();" id="company_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="company_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>Company Details Successfully updated</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Employee ID <span style="color:red;">*</span></label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="employee_id" id="employee_id" placeholder="Employee ID" value="<?php echo $query->employee_id; ?>" class="form-control" readonly>
                                                    <span id="empiderror" class="errorall" style="color:red;"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Email</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_email" id="company_email" placeholder="Company Email" value="<?php echo $query->company_email; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Company Mobile</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="company_mobile" id="company_mobile" placeholder="Company Mobile" value="<?php echo $query->company_mobile; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Department </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select class="form-control" name="department" id="department" onchange="designation_list(this.value);">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($department_list as $department) { ?>
                                                            <option value="<?php echo $department->id; ?>" <?php
                                                            if ($query->department == $department->id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo ucwords($department->name); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Designation </label>
                                                <div class="col-sm-9 pdng_0" id="designation_div">
                                                    <select class="form-control" name="designation" id="designation">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($designation_list as $designation) { ?>
                                                            <option value="<?php echo $designation->id; ?>" <?php
                                                            if ($query->designation == $designation->id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo ucwords($designation->name); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Date of Joining </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="date_joining" id="date_joining" value="<?php echo $query->date_of_joining; ?>" class="form-control">
                                                </div>
                                            </div>
											<div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Date of Leaving </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="date_leaving" id="date_leaving" value="<?php echo $query->date_of_leaving; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Joining Salary</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="joinnig_salary" id="joinnig_salary" placeholder="Current Salary" value="<?php echo $query->joining_salary; ?>" class="form-control">
                                                    <span id="join_salary_error" class="errorall" style="color:red;"><?php echo form_error('joinnig_salary'); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-sm-12  pdng_0" id="sal_option" style="display:none">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">PF Option</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="radio" name="sal_option" value="1" <?php
                                                    if ($query->sal_option == "1") {
                                                        echo "checked";
                                                    }
                                                    ?>> With PF &nbsp;
                                                    <input type="radio" name="sal_option" value="0" <?php
                                                    if ($query->sal_option == "0") {
                                                        echo "checked";
                                                    }
                                                    ?>> Without PF<br>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Salary Structure</label>
                                                <div class="col-sm-9 pdng_0">
                                                    <select class="form-control" name="master_salary" id="master_salary">
                                                        <option value="">--Select--</option>
                                                        <?php foreach ($salary_list as $sala) { ?>
                                                            <option value="<?php echo $sala->id; ?>" <?php
                                                            if ($sala->id == $query->salary_structure_id) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo ucwords($sala->salary_strucure_name); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>

                        <?php
                        
                            $count = count($salary_structure_emp1)
                            ?>
                            <div class="col-md-6" id="salary_details" name="salary_details">
                                <div class="panel panel-primary" style="border-color:#bf2d37;">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px; ">
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px; color: white">Salary Structure (Monthly)</h3>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="salary_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>Salary Details Successfully updated</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 salary_data">
                                            <label class = "col-sm-3 pdng_0">
                                            </label>
                                            <!--                                                <div class = "col-sm-9 pdng_0">-->
                                            <?php
//                                                    foreach ($salary_structure_emp1 as $sal) {
//                                                        if ($sal['type'] == 1 && $sal['status'] == 1) {
//                                                            $checked_pf = "checked";
//                                                        }
//                                                        if ($sal['type'] == 2 && $sal['status'] == 1) {
//                                                            $checked_esic = "checked";
//                                                        }
//                                                    }
                                            ?>
                                            <!--                                                    <label class="checkbox-inline">
                                                                                                    <input type="checkbox" name= "pf_check" <?php //echo $checked_pf      ?> id="salerypf" value="1">PF</label>
                                                                                                <label class="checkbox-inline">
                                                                                                    <input id="saleryctc" <?php //echo $checked_esic      ?> type="checkbox" name= "pf_esic" value="1">ESIC</label>
                                                                                            </div>-->
                                            <?php
                                            $cnt = 0;
                                            foreach ($salary_structure_emp1 as $sal) {
                                                //echo "<pre>"; print_r($sal); 
                                                ?>

                                                <div class="form-group col-sm-12  pdng_0 <?php
//                                                    if ($cnt == 1 || $cnt == 2) {
//                                                        echo "pfcontain";
//                                                    } else if ($cnt == 3) {
//                                                        echo "ctccontain";
//                                                    }
                                                ?>" style="<?php
//                                                         if ($sal['status'] == 0) {
//                                                             echo "display:none";
//                                                         }
                                                     ?>">
                                                    <label class="col-sm-3 pdng_0"><?php
//                                                            if ($sal['salary_name'] == "") {
//                                                                echo "Other";
//                                                            } else {
//                                                                echo $sal['salary_name'];
//                                                            }
                                                        echo $sal['salary_name'];
                                                        ?></label>
                                                    <div class="col-sm-9 pdng_0">
                                                        <input type="hidden" name="salary_id_<?php echo $cnt ?>" id="" value="<?php
//                                                            if ($sal['id'] == "") {
//                                                                echo 0;
//                                                            } else {
//                                                                echo $sal['id'];
//                                                            }
                                                        echo $sal['id'];
                                                        ?>">
                                                        <input type="text" name="salary_name_<?php echo $cnt ?>" id="<?php
//                                                            if ($sal['salary_name'] == "") {
//                                                                echo "othersalry";
//                                                            } else {
//                                                                $cutoff = $sal['cutofftype'];
//                                                                $value = $sal['salary_value'];
//                                                                $value2 = $sal['value'];
//                                                                echo "salaryvalue_$cnt" . "_$cutoff" . "_$value" . "_$value2";
//                                                            }
                                                        $cutoff = $sal['cutofftype'];
                                                        $value = $sal['salary_value'];
                                                        $value2 = $sal['value'];
                                                        echo "salaryvalue_$cnt" . "_$cutoff" . "_$value" . "_$value2";
                                                        ?>" placeholder="Enter Monthly Salary Name" value="<?php echo $sal['salary_value'] ?>" class="form-control <?php
                                                               //if ($sal['salary_name'] != "") {
                                                               //echo "calsalury";
                                                               //}
                                                               echo "calsalury";
                                                               ?>">
                                                               <?php $cnt++; ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <input type="hidden" name="count"  value="<?= $count ?>">
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                         </form>
                        <div class="col-md-6">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="bank_account_form" action="<?php echo base_url(); ?>hrm/employee/bank_account_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px; color: white">Bank Account Details
                                        </h3>
                                        <button onclick="bank_account_data();" id="bank_account_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="bank_account_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>Bank Details Successfully updated</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Holder Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="holder_name" id="holder_name" placeholder="Account Holder Name" value="<?php echo $query->bank_holder_name; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Account Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="account_number" id="account_number" placeholder="Account Number" value="<?php echo $query->bank_account_number; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Bank Name </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="bank_name" id="bank_name" placeholder="BANK Name" value="<?php echo $query->bank_name; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">IFSC Code </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" value="<?php echo $query->ifsc_code; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">PAN Number </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="pan_number" id="pan_number" placeholder="PAN Number" value="<?php echo $query->pan_number; ?>" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12  pdng_0">
                                                <label class="col-sm-3 pdng_0" for="exampleInputFile">Branch </label>
                                                <div class="col-sm-9 pdng_0">
                                                    <input type="text" name="branch" id="branch" placeholder="BRANCH" value="<?php echo $query->branch; ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-primary" style="border-color:#bf2d37;">
                                <form role="form" id="document_form" action="<?php echo base_url(); ?>hrm/employee/document_edit/<?php echo $cid; ?>" method="post" enctype="multipart/form-data">
                                    <div class="panel-heading" style="background-color:#bf2d37; width:100%; float:left; padding:0 15px;">
                                        <!-- form start -->
                                        <h3 class="panel-title" style="width:auto; float:left; margin-bottom: 10px; margin-top: 10px; color: white">Documents
                                        </h3>
                                        <button onclick="document_data();" id="document_button" type="button" data-loading-text="Updating..." class="btn btn-default pull-right" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-save"></i> Save </button>
                                    </div>
                                    <div class="panel-body" style="display:inline-block;">
                                        <div class="widget">
                                            <div class="alert alert-success" style="display:none;" id="document_alert">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>Documents Successfully updated</span>
                                            </div>
                                            <div class="alert alert-danger" style="display:none;" id="document_alert1">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10006;</button>
                                                <span>The file type you are attempting to upload is not allowed.</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
                                            <div class="col-md-6" id="document_div">
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->resume != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->resume; ?>" target="_blank">View Resume</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('resume');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->offer_letter != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->offer_letter; ?>" target="_blank">Offer Letter</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('offer');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->joining_letter != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->joining_letter; ?>" target="_blank">Joining Letter</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('joining');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->contract_agreement != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->contract_agreement; ?>" target="_blank">View Contract</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('contract');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group col-sm-12  pdng_0">
                                                    <?php if ($query->id_proof != '') { ?>
                                                        <a class="btn btn-primary" style="background-color:#bf2d37;" href="<?php echo base_url(); ?>upload/employee/<?php echo $query->id_proof; ?>" target="_blank">View ID Proof</a>
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Remove" onclick="document_delete_data('proof');" class="btn btn-primary" style="background-color:#bf2d37;"><i class="fa fa-trash-o"></i></a>
                                                        <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--                                    <div class="panel-footer">
                                                                            <button class="btn btn-primary" type="submit"> UPDATE</button>
                                                                        </div>-->
                            </div>
                        </div>
                    </div>
                </section>
            </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
        <!-- /.row -->
    </section>
</div>
<script>
    function remove_photo() {
        var img = "<?php echo $query->photo; ?>";
        if (img == "") {
            var url = "<?php echo base_url(); ?>upload/employee/default_avatar.jpg";
        } else {
            var url = "<?php echo base_url(); ?>upload/employee/<?php echo $query->photo; ?>";
                    }
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
//                            $("#designation_div").empty();
//                            $("#designation_div").html(data);
                            var $el = $("#designation");
                            $el.empty(); // remove old options
                            $el.append($("<option></option>")
                                    .attr("value", '').text('Select Designation'));

                            $.each(response, function (index, data) {
                                $('#designation').append('<option value="' + data['id'] + '">' + data['name'] + '</option>');
                            });

                        }
                    });
                }
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
                function personal_data() {
                    var error = 1;
                    var name = $("#name").val().trim();
                    var city_data = $("#city_data").val();
                    var email = $("#email").val().trim();
                    var password = $("#password").val().trim();

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
                    if (error == 1) {
                        $("#personal_button").attr("disabled", true);
                        var data = new FormData($('#personal_form')[0]);
                        var path = "<?php echo base_url(); ?>hrm/employee/personal_edit/<?php echo $cid; ?>";
                                    //alert(path);
                                    $.ajax({
                                        type: "POST",
                                        url: path,
                                        data: data,
                                        mimeType: "multipart/form-data",
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function (data)
                                        {
                                            $("#personal_alert").show();
                                            $("#personal_button").attr("disabled", false);
                                        }
                                    });

                                } else {
                                    return false;
                                }
                            }

                            function deactivate_profile() {
                                var path = "<?php echo base_url(); ?>hrm/employee/deactivate/<?php echo $cid; ?>";
                                        $.ajax({
                                            type: "POST",
                                            url: path,
                                            processData: false,
                                            success: function (data)
                                            {
                                                $("#personal_alert1").show();
                                                $("#deactivate").hide();
                                            }
                                        });
                                    }

                                    function company_data() {

                                        var error = 1;
                                        var name = $("#name").val().trim();
                                        var joinnig_salary = $("#joinnig_salary").val().trim();

                                        $(".errorall").html("");
                                        if (name == "") {
                                            error = 0;
                                            $("#nameerror").html("The Name field is required.");
                                        }
                                        if (joinnig_salary != "") {
                                            if (isNaN(joinnig_salary)) {
                                                error = 0;
                                                $("#join_salary_error").html("Enter joining salary in number.");
                                            }
                                        }
                                        if (error == 1) {
                                            $("#company_button").attr("disabled", true);
                                            var data = new FormData($('#company_form')[0]);
                                            var path = "<?php echo base_url(); ?>hrm/employee/company_edit/<?php echo $cid; ?>";
											//alert(path);
											$.ajax({
												type: "POST",
												url: path,
												data: data,
												mimeType: "multipart/form-data",
												contentType: false,
												cache: false,
												processData: false,
												success: function (data)
												{
													$("#company_alert").show();
													$("#company_button").attr("disabled", false);
												}
											});
										} else {
											return false;
										}
                                    }

                                                function bank_account_data() {
                                                    $("#bank_account_button").attr("disabled", true);
                                                    var data = new FormData($('#bank_account_form')[0]);
                                                    var path = "<?php echo base_url(); ?>hrm/employee/bank_account_edit/<?php echo $cid; ?>";
                                                            //alert(path);
                                                            $.ajax({
                                                                type: "POST",
                                                                url: path,
                                                                data: data,
                                                                mimeType: "multipart/form-data",
                                                                contentType: false,
                                                                cache: false,
                                                                processData: false,
                                                                success: function (data)
                                                                {
                                                                    $("#bank_account_alert").show();
                                                                    $("#bank_account_button").attr("disabled", false);
                                                                }
                                                            });

                                                        }


                                                        function salary_edit() {
                                                            $("#salary_button").attr("disabled", true);
                                                            var data = new FormData($('#salary_form')[0]);
                                                            var path = "<?php echo base_url(); ?>hrm/employee/salary_edit/<?php echo $cid; ?>";
                                                                    //alert(path);
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: path,
                                                                        data: data,
                                                                        mimeType: "multipart/form-data",
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        success: function (data)
                                                                        {
                                                                            $("#salary_alert").show();
                                                                            $("#salary_button").attr("disabled", false);
                                                                        }
                                                                    });
                                                                }



                                                                function document_data() {
                                                                    $("#document_button").attr("disabled", true);
                                                                    var data = new FormData($('#document_form')[0]);
                                                                    var path = "<?php echo base_url(); ?>hrm/employee/document_edit/<?php echo $cid; ?>";
                                                                            //alert(path);
                                                                            $.ajax({
                                                                                type: "POST",
                                                                                url: path,
                                                                                data: data,
                                                                                mimeType: "multipart/form-data",
                                                                                contentType: false,
                                                                                cache: false,
                                                                                processData: false,
                                                                                success: function (data)
                                                                                {
                                                                                    if (data != "") {
                                                                                        $("#resume").val("");
                                                                                        $("#offer_letter").val("");
                                                                                        $("#joining_letter").val("");
                                                                                        $("#id_proof").val("");
                                                                                        $("#contract_agreement").val("");
                                                                                        $("#document_button").attr("disabled", false);
                                                                                        $("#document_div").empty();
                                                                                        $("#document_div").html(data);
                                                                                        $("#document_alert").show();
                                                                                    } else {
                                                                                        $("#document_alert1").show();
                                                                                    }
                                                                                }
                                                                            });

                                                                        }
                                                                        function document_delete_data(type) {
                                                                            var test = confirm('Are you sure you want to remove this data?');
                                                                            if (test == true) {
                                                                                var path = "<?php echo base_url(); ?>hrm/employee/document_delete/<?php echo $cid; ?>";
                                                                                            //alert(path);
                                                                                            $.ajax({
                                                                                                type: "POST",
                                                                                                url: path,
                                                                                                data: {types: type},
                                                                                                success: function (data)
                                                                                                {
                                                                                                    $("#document_alert").show();
                                                                                                    $("#document_button").attr("disabled", false);
                                                                                                    $("#document_div").empty();
                                                                                                    $("#document_div").html(data);
                                                                                                }
                                                                                            });
                                                                                        }
                                                                                    }

</script>
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });

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

<?php if ($query->joining_salary >= 21000) { ?>
        $("#sal_option").show();
<?php }?>

    $(document).on('keyup', '#joinnig_salary', function () {
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
        } else {
            $("#sal_option").hide();
        }
        salerycount();
    });

//    $(document).on('keyup', '.calsalury', function () {
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

    $("#master_salary").change(function () {

        var url = "<?php echo base_url(); ?>hrm/employee/get_salaray_strucuture_new";
//        var department = $("#department").val();
//        var designation = $("#designation").val();
        var master_salary = $("#master_salary").val();
        var joinnig_salary = parseFloat($("#joinnig_salary").val().trim());


        if (master_salary != "") {
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {master_salary: master_salary, salary: joinnig_salary},
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

                        html_data = html_data + '<div class="' + divid + '"><div class="form-group col-sm-12  pdng_0"><label class = "col-sm-3 pdng_0"> ' + data[cnt].salary_name + ' </label><div class = "col-sm-9 pdng_0"><input type="text" id="salaryvalue_' + cnt + '_' + data[cnt].cutofftype + '_' + data[cnt].value + '" name="salary_name_' + cnt + '"  placeholder="Enter Monthly Salary value" value="' + svalue + '" class="form-control calsalury"></div></div><input type="hidden" name="salary_id_' + cnt + '" value="' + data[cnt].id + '"></div>';
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

    function salerycount() {
        var url = "<?php echo base_url(); ?>hrm/employee/get_salaray_strucuture_new";
//        var department = $("#department").val();
//        var designation = $("#designation").val();
        var master_salary = $("#master_salary").val();
        var joinnig_salary = parseFloat($("#joinnig_salary").val().trim());


        if (master_salary != "") {
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {master_salary: master_salary, salary: joinnig_salary},
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

                        html_data = html_data + '<div class="' + divid + '"><div class="form-group col-sm-12  pdng_0"><label class = "col-sm-3 pdng_0"> ' + data[cnt].salary_name + ' </label><div class = "col-sm-9 pdng_0"><input type="text" id="salaryvalue_' + cnt + '_' + data[cnt].cutofftype + '_' + data[cnt].value + '" name="salary_name_' + cnt + '"  placeholder="Enter Monthly Salary value" value="' + svalue + '" class="form-control calsalury"></div></div><input type="hidden" name="salary_id_' + cnt + '" value="' + data[cnt].id + '"></div>';
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
        }
    }



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
</script>
