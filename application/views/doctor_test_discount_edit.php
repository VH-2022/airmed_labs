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
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Reg No";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Customer Name";}
        .pending_job_list_tbl td:nth-of-type(4):before {content: "Doctor";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Test/Package Name";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: "Date";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: "Payable Amount / Price";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: "Job Status";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: "Action";}
    }
    /* End pending_job_detail responsive table */
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
    .text_highlight:hover{
        text-decoration: underline;
        font-weight: bold;
    }
</style>
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<!--Nishit code start-->
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq('.fancybox').fancybox();
    });
</script>
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<section class="content-header">
    <h1>
        Doctor Test Discount Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>doctor_master/doctor_list"><i class="fa fa-users"></i>Doctor List</a></li>
        <li class="active">Edit Doctor Test Discount</li>
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
                <form role="form" action="<?php echo base_url(); ?>doctor_test_discount/doctor_test_discount_edit/<?= $cid ?>" method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="col-md-6">
                            <!--<?= validation_errors('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>', '</div>'); ?>-->

          
                    <div class="form-group">
                      <label for="exampleInputFile">Lab</label><span style="color:red">*</span>
                      <select class="form-control chosen-select" name="lab_fk" onchange="state_list(this.value)">
                          <option value="">--Select--</option>
                          <?php foreach ($branch_list as $branch) { ?>
                          <option value="<?php echo $branch['id']; ?>" <?php if($branch['id'] == $query[0]['lab_fk']) { echo 'selected'; } ?>><?php echo $branch['branch_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('lab_fk');?></span>
                    </div>
                            
                <div class="form-group">
                      <label for="exampleInputFile">Doctor</label><span style="color:red">*</span>
                      <select class="form-control" name="doc_fk" id="city_list">
                          <option value="">--Select--</option>
                          <?php foreach ($doctor_list as $doc) { ?>
                          <option value="<?php echo $doc['id']; ?>" <?php if($doc['id'] == $query[0]['doc_fk']) { echo 'selected'; } ?>><?php echo $doc['full_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('doc_fk');?></span>
                    </div>

                 
                      <div class="form-group">
                      <label for="exampleInputFile">Test</label><span style="color:red">*</span>
                      <select class="form-control chosen-select" name="test_fk" ">
                          <option value="">--Select--</option>
                          <?php foreach ($test_list as $test) { ?>
                          <option value="<?php echo $test['id']; ?>" <?php if($test['id'] == $query[0]['test_fk']) { echo 'selected'; } ?>><?php echo $test['test_name'] ?></option>
                          <?php } ?>
                      </select>
                      <span style="color: red;"><?=form_error('test_fk');?></span>
                    </div>

                      <div class="form-group">
                                <label for="exampleInputFile">Price</label><span style="color:red">*</span>
                                <input type="text"  name="price" class="form-control" min="1" max="100000"  value="<?php echo $query[0]["price"]; ?>" >
                                <span style="color: red;"><?= form_error('price'); ?></span>
                            </div>

                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>

                </form>
            </div><!-- /.box -->
            <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
                        <Script>
                                                $(function () {
                                                    $('.multiselect-ui').multiselect({
                                                        includeSelectAllOption: true,
                                                        nonSelectedText: 'Select Branch'
                                                    });
                                                });
                                                $(function () {
                                                    $('.chosen').chosen();
                                                });
                                                jQuery(".chosen-select").chosen({
                                                    search_contains: true
                                                });
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
                function state_list(cid) {
     $.ajax({
            url: '<?php echo base_url(); ?>doctor_master/city_state_list',
            type: 'post',
            data: {cid: cid},
            success: function (data) {
                $("#city_list").html(data);
            }
    });
 }
            </script>
        </div>
    </div>
</section>
