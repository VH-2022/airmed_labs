<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
    .chosen-container {width: 100% !important;}

    span.multiselect-native-select {
        position: relative
    }
    span.multiselect-native-select select {
        border: 0!important;
        clip: rect(0 0 0 0)!important;
        height: 1px!important;
        margin: -1px -1px -1px -3px!important;
        overflow: hidden!important;
        padding: 0!important;
        position: absolute!important;
        width: 1px!important;
        left: 50%;
        top: 30px
    }
    .multiselect-container {
        position: absolute;
        list-style-type: none;
        margin: 0;
        padding: 0
    }
    .multiselect-container .input-group {
        margin: 5px
    }
    .multiselect-container>li {
        padding: 0
    }
    .multiselect-container>li>a.multiselect-all label {
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group label {
        margin: 0;
        padding: 3px 20px 3px 20px;
        height: 100%;
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group-clickable label {
        cursor: pointer
    }
    .multiselect-container>li>a {
        padding: 0
    }
    .multiselect-container>li>a>label {
        margin: 0;
        height: 100%;
        cursor: pointer;
        font-weight: 400;
        padding: 3px 0 3px 30px
    }
    .multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
        margin: 0
    }
    .multiselect-container>li>a>label>input[type=checkbox] {
        margin-bottom: 5px
    }
    .btn-group>.btn-group:nth-child(2)>.multiselect.btn {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px
    }
    .form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
        padding: 3px 20px 3px 40px
    }
    .form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
        margin-left: -20px;
        margin-right: 0
    }
    /* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: center; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    @media (max-width: 980px) {
        .pending_job_list_tbl table, .pending_job_list_tbl thead, .pending_job_list_tbl tbody, .pending_job_list_tbl th, .pending_job_list_tbl td, .pending_job_list_tbl tr {display: block;}
        .pending_job_list_tbl thead tr {position: absolute; top: -9999px; left: -9999px;}
        .pending_job_list_tbl tr {border: 1px solid #ccc !important;}
        .pending_job_list_tbl td {border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 60%; text-align: left;}
        .pending_job_list_tbl td:before {position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}
        .pending_job_list_tbl tr{margin-bottom:15px;}
        .table-responsive.pending_job_list_tbl{border:none !important;}

        .pending_job_list_tbl td:nth-of-type(1):before {content: "No";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Vendor Name";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Address";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Mobile";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Contact No";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Email Id";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Contact Person Name";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Contact Person Email Id";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Action";}
        

    }
    /* End pending_job_detail responsive table */
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Vendor List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Vendor List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Vendor List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>inventory/Vendor_master/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Vendor Add</strong></a>
                        <!--<a style="float:right;" href='<?php echo base_url(); ?>test_master/test_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success')[0]; ?>
                                </div>
                            <?php } ?>
                        </div>

                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('inventory/Vendor_master/index', $attributes); ?>
                        <div class="col-md-2" style="padding-left:0px;">
                            <input type="text" class="form-control" name="search" placeholder="Enter Vendor Name" value="<?php echo $name; ?>"/>
                        </div>
                        <div class="col-md-1" style="padding-left:0px;">
                            <select name="city" class="form-control">
                                <option value="">Select City</option>
                                <?php foreach($city as $val){ ?>
                                <option value="<?php echo $val['city_fk'];?>" <?php if($city_one == $val['city_fk']){ echo "selected='selected'";}?>><?php echo $val['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-1" style="padding-left:0px;">
                            <input type="text" class="form-control" name="mobile" placeholder="Enter Mobile No" value="<?php echo $mobile; ?>"/>
                        </div>
                        
                        <div class="col-md-2" style="padding-left:0px;">
                            <input type="text" class="form-control" name="email" placeholder="Enter Email Id" value="<?php echo $email; ?>"/>
                        </div>
                         <div class="col-md-2" style="padding-left:0px;">
                            <input type="text" class="form-control" name="cp_name" placeholder="Enter Contact Person Name" value="<?php echo $cp_name; ?>"/>
                        </div>
                         <div class="col-md-2" style="padding-left:0px;">
                            <input type="text" class="form-control" name="bank_name" placeholder="Enter bank_name" value="<?php echo $bank_name; ?>"/>
                        </div>
                        <div class="col-md-2" style="padding-left:0px;">
                            <input type="text" class="form-control" name="branch_name" placeholder="Enter Branch Name" value="<?php echo $branch_name; ?>"/>
                        </div>
                        <div class="col-md-2" style="padding-left:0px;margin-top:10px;">
                            <input type="text" class="form-control" name="account_no" placeholder="Enter Account" value="<?php echo $account_no; ?>"/>
                        </div>
                                               
                       
                        <input type="submit"  value="search" class="btn btn-primary btn-md" style="margin-top:10px;">
                        <a style="margin-top:10px;" href='<?php echo base_url(); ?>inventory/vendor_master/export_csv?search=<?php echo $name;?>&city=<?php echo $city_one;?>&mobile=<?php echo $mobile;?>&phone_no=<?php echo $phone_no;?>&email=<?php echo $email;?>&cp_name=<?php echo $cp_name;?>&bank_name=<?php echo $bank_name;?>&branch_name=<?php echo $branch_name;?>&account_no=<?php echo $account_no;?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export Csv</strong></a>
                        </form>
                        <br> 
                        <div class="tableclass">
                            <div class="table-responsive pending_job_list_tbl">
                            <table id="example4" class="table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Vendor Name</th>    
                                        <th>City</th>
                                        <th>Mobile</th>
                                        <th>Email Id</th>
                                        <th>Contact Person Name</th>
                                        <th>Bank Name</th>
                                        <th>Branch Name</th>
                                         <th>Account Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) { 
                                        ?>
                                        <tr> <td><?php echo $count+ $cnt; ?></td>
                                            <td><?php echo ucwords($row['vendor_name']); ?></td>

                                            <td><?php if($row['city_fk'] !=''){
                                                $city= ucwords($row['CityName']);
                                            }else{
                                                $city = ucfirst($row['city_name']);
                                            }
                                            echo $city;
                                             ?></td>
                                           
                                            <td><?php echo ucwords($row['mobile']); ?></td>
                                           
                                             <td><?php echo $row['email_id']; ?></td>
                                              <td><?php echo ucwords($row['cp_name']); ?></td>
                                               <td><?php echo ucwords($row['bank_name']); ?></td>
                                                <td><?php echo ucwords($row['branch_name']); ?></td>
                                                 <td><?php echo ucwords($row['account_no']); ?></td>
                                              
                                            <td>
                                                
                                                                                                    <?php
                                                    if ($login_data['type'] == 1 || $login_data['type'] == 2 || $login_data['type'] == 8) {
                                                        if ($row['login_email'] == "" && $row['password'] == "") {
                                                            ?>
                                                            <a href='javascript:void(0)' onclick="ven_log('<?php echo $row['id'] ?>')">Create Login</a>    
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                
                                                
                                                <a href='<?php echo base_url(); ?>inventory/Vendor_master/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/Vendor_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                        <?php $cnt++; ?>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="8">No Records Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul> 
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<div class="modal fade" id="vendor_login_details" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Vendor Login Details</h4>
            </div>

            <?php echo form_open("inventory/Vendor_master/add_login", array("method" => "POST", "role" => "form", "id" => "vendor_login_form")); ?>
            <div class="modal-body">
                <input type="hidden" id="vendorid" name="vendorid" />
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Login Email:</label>
                    <input type="text" id="email" name="email" class="form-control vemailpass" placeholder="Email" value=""/>
                    <span id="emailerror" class="vlogin_error" style="color:red"></span>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control vemailpass" placeholder="Password"/>
                    <span id="passerror" class="vlogin_error" style="color:red"></span>
                </div>
            </div>



            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
    
    
    
        $(document).ready(function () {

        $("#email").blur(function () {
            var email = $("#email").val();
            if (email == "") {
                $("#emailerror").html("Email Id is required.");
            } else {
                var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                if (filter.test(email)) {
                    $("#emailerror").html('');
                } else {
                    $("#emailerror").html('Email Id is not valid.');
                }
            }
        }
        );
    });

    function ven_log(id) {
        $('.vemailpass').val('');
        $('.vlogin_error').html('');
        $('#vendorid').val('');

        $('#vendorid').val(id);
        $('#vendor_login_details').modal('show');
    }

    $("#vendor_login_form").submit(function (event) {
        $('.vlogin_error').html('');
        var error = 0;
        var email = $('#email').val();
        var password = $('#password').val();

        if (email == "") {
            error = 1;
            $('#emailerror').html('Please enter email');
        }

        if (email != "") {
            var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (filter.test(email)) {
                $("#emailerror").html('');
            } else {
                error = 1;
                $("#emailerror").html('Email is not valid.');
            }

        }

        if (password == "") {
            error = 1;
            $('#passerror').html('Please enter password');
        }

        if (error == 1) {
            return false;
        } else {
            return true;
        }

    });
    
    
    
</script>