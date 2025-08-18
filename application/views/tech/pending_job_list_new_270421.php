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
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Order Id";}
        .pending_job_list_tbl td:nth-of-type(4):before {content: "Customer Name";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Doctor";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: "Test/Package Name";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: "Date";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: "Payable Amount / Price";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: "Job Status";}
        .pending_job_list_tbl td:nth-of-type(10):before {content: "Action";}
    }
    /* End pending_job_detail responsive table */
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
    .btn-group, .btn-group-vertical {
        display: inline-block;
        position: relative;
        vertical-align: left;
        width: 100%;
    }
    .multiselect{width:100%;text-align: left;}
    .wordwrap { 
        white-space: pre-wrap;      /* CSS3 */   
        white-space: -moz-pre-wrap; /* Firefox */    
        white-space: -pre-wrap;     /* Opera <7 */   
        white-space: -o-pre-wrap;   /* Opera 7 */    
        word-wrap: break-word;      /* IE */
    }
    .multiselect-container.dropdown-menu {
        max-height: 400px;
        min-height: 100px;
        overflow-wrap: break-word;
        overflow-x: hidden;
        overflow-y: scroll;
        width: 100%;
    }
    a .checkbox {
        white-space: pre-line;
    }
    ul .active-result {
        white-space: pre-line;
        word-wrap: break-word; 
        width:100% !important;
    }
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }
    span.multiselect-selected-text {
        white-space: nowrap;
        overflow: hidden;
        width: 100%;
        float: left;
        white-space:pre-line;
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
    .nav-justified>li{width:auto !important;}
    .nav-justified>li.active{background:#eee; border-top:3px solid #3c8dbc;}
</style>
<?php $sub = 6;
foreach ($login_data['branch_fk'] as $val) {
    $btype[] = $val['branch_type_fk'];
}
?>
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

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            All Bookings
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>All Bookings</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary" style="min-height: 500px;">
                    
                    <!------ <div class="box-header">
                         <div class="col-xs-12">
                             <div class="col-xs-2 pull-right">
                                 <div class="form-group">
                    <?php if ($login_data['type'] != 5) { ?>
                                                                                                                                                                                                            
                                                                                                                                                                                                                                                                                                                                                                                     <a style="float:right; display:none;" href='<?php echo base_url(); ?>job_master/export_csv/1' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                    <?php } ?>
                                 </div>
                             </div>
                         </div>
                     </div>------><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">?</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="box-body">
                            <div class="widget">
                                <?php echo form_open("tech/job_master/pending_list_search", array("method" => "GET", "role" => "form", "id" => "job_search")); ?>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" placeholder="Ref No." class="form-control" name="p_ref" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" placeholder="Order Id" class="form-control" name="p_oid" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="mobile" class="form-control" style="" placeholder="Mobile No." id="date"  value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">

                                        <input type="text" name="user" placeholder="Customer Name." class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="date" placeholder="Start date" readonly="" class="form-control datepicker-input" id="date"  value="<?php
                                        if (isset($date2)) {
                                            echo $date2;
                                        } else {
                                            echo date("d/m/Y");
                                        }
                                        ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" name="end_date" readonly="" placeholder="End date" class="form-control datepicker-input" id="date"  value="<?php
                                        if (isset($end_date)) {
                                            echo $end_date;
                                        } else {
                                            echo date("d/m/Y");
                                        }
                                        ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select class="form-control chosen-select" data-placeholder="Select Sample From" tabindex="-1" name="sample_from">
                                            <option value="">--Sample From-- </option>
                                            <?php foreach ($sample_from as $cf_key) { ?>
                                                <option value="<?= $cf_key["id"] ?>"><?= ucfirst($cf_key["name"]) ?></option>
                                            <?php } ?>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" placeholder="Test/Package" class="form-control" name="test_package" value=""/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="status">
                                            <option value="">All Status </option>
                                            <option value="1"> Waiting For Approval </option>
                                            <option value="6"> Approved </option>
                                            <option value="7"> Sample Collected </option>
                                            <option value="8"> Processing </option>
                                            <option value="2"> Completed </option>
                                            <option value="0"> User Deleted </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">

                                        <select class="form-control chosen-select" onchange="get_plm(this.value);" data-placeholder="Select Test city" tabindex="-1" name="city" id="citiess">
                                            <option value="">--All city--</option>
                                            <?php
                                            $test_cnt = 0;
                                            foreach ($test_cities as $bkey) {
                                                if (!empty($search_c)) {
                                                    if (in_array($bkey['id'], $search_c)) {
                                                        ?>
                                                        <option value="<?= $bkey["id"] ?>" <?php
                                                        if ($tcity == $bkey["id"]) {
                                                            echo "selected";
                                                        } if (!empty($search_c)) {
                                                            if ($search_c[0] == $bkey['id'] || $test_cnt == 0) {
                                                                echo "selected";
                                                            }
                                                        }
                                                        ?>><?= $bkey["name"] ?></option>
                                                                <?php
                                                                $test_cnt++;
                                                            }
                                                        } else {
                                                            ?>
                                                    <option value="<?= $bkey["id"] ?>" <?php
                                                    if ($tcity == $bkey["id"] || $test_cnt == 0) {
                                                        echo "selected";
                                                    }
                                                    ?>><?= $bkey["name"] ?></option>
                                                            <?php
                                                            $test_cnt++;
                                                        }
                                                    }
                                                    ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select class="form-control" onchange="get_branch(this.value)" data-placeholder="Select PLM" tabindex="-1" name="plm" id="plm_id">
                                            <option value="">--All PLM--</option>
                                        </select>
                                    </div>
                                </div>
                                <script>
                                    jQuery(document).ready(function () {
                                        jQuery("#plm_id").chosen();
                                        /*jQuery('#plm_id').live('change', function () {
                                         alert(jQuery(this).val());
                                         });*/
                                    });
                                </script>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select class="multiselect-ui form-control" id="branch" title="Select Branch" multiple="multiple" name="branch[]">
                                            <?php
                                            foreach ($branchlist as $bkey) {
                                                if (isset($tcity) && $tcity == $bkey["city"]) {
                                                    ?>
                                                    <option value="<?= $bkey["id"] ?>"><?= $bkey["branch_name"] ?></option>
                                                <?php } else {
                                                    ?><option value="<?= $bkey["id"] ?>"><?= $bkey["branch_name"] ?></option><?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <div class="col-sm-3" style="margin-bottom:10px;">
                                            <select class="form-control chosen-select" data-placeholder="Select Status" tabindex="-1" name="outsource">
                                                <option value="">--Select Outsource-- </option>
                                                <option value="1"> All </option>
                                                <option value="2"> Pending </option>
                                                <option value="3"> Completed </option>
                                            </select>
                                        </div>
                                    </div>
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <select name="referral_by" id="referral_by" class="chosen">
                                            <option value="">--Select Referral By--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="widget">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-5 pdng_0" style="margin-bottom:10px;">
                                            <label>Payment</label>
                                            <label class="radio-inline"><input type="radio" value="due" name="payment">Due</label>
                                            <label class="radio-inline"><input type="radio" value="paid" name="payment">Paid</label>
                                            <label class="radio-inline"><input type="radio" value="all" name="payment" checked="checked">All</label> 
                                        </div>
                                        <div class="col-md-3 col-sm-5 pdng_0" style="margin-bottom:10px;">
                                            <label> Search with  sample collection date</label>
                                            <input type="checkbox" value="sample" name="withsample">  </label>

                                        </div>
                                    </div>

                                    <div class="form-group pull-right">
                                        <div class="col-sm-12" style="margin-bottom:10px;">
                                            <button type="button" onclick="sub_form();" name="search" class="btn btn-sm btn-success" value="Search" /><i class="fa fa-search"></i>Search</button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="window.location = '<?= base_url(); ?>tech/job_master/pending_list'" value="Reset"/><i class="fa fa-refresh"></i>Reset</button>
                                            <a style="float:right;margin-left:3px;" href='javascript:void(0);' id="job_search_btn" onclick="get_csv_link();" class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
                        <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
                        <Script>
                                                function get_csv_link() {
                                                    var form = $("#job_search");
                                                    var recursiveDecoded = decodeURIComponent(form.serialize());
                                                    window.location.href = "<?= base_url(); ?>tech/job_master/export_csv?" + recursiveDecoded;
                                                }
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
                        <div class="tableclass">

                            <div class="table-responsive pending_job_list_tbl">
                                <table id="example4" class="table-striped">
                                </table>
                            </div>
                        </div>
                        <?php /* Nishit doctor model start */ ?>
                        <div class="modal fade" id="print_model" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Print Report</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Do you want to print with letterhead?</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-primary" href="http://websitedemo.co.in/phpdemoz/patholab/add_result/pdf_report/3" target="_blank" onclick="$('#print_model').modal('hide');" id="print_simple">Yes</a>
                                        <a class="btn btn-primary" href="http://websitedemo.co.in/phpdemoz/patholab/add_result/pdf_report/3?type=wlp" target="_blank" onclick="$('#print_model').modal('hide');" id="print_wlpd">No</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal fade" id="sms_modal1" role="dialog">
                            <div class="modal-dialog modal-lg">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <?php echo form_open("tech/job_master/send_sms_due_payment", array("method" => "post", "role" => "form", "target" => "_blank")); ?>
                                    <input type="hidden" id="job_fk1" name="job_fk" value=""/>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Send Payment Link Via SMS</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="all_mobile_no2">
                                            <div class="form-group">
                                                <input type="text" placeholder="Mobile No.1" id="mobile_no10" name="mobile_no[]" class="form-control"/>
                                            </div>


                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num2();"><i class="fa fa-plus"></i> Add</a>
                                        </div>

                                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?= form_close(); ?>
                                </div>

                            </div>
                        </div>

                        <?php /* Nishit doctor model end */ ?>
                        <div class="modal fade" id="sms_modal" role="dialog">
                            <div class="modal-dialog modal-lg">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <?php echo form_open("tech/job_master/send_report_sms", array("method" => "post", "role" => "form")); ?>
                                    <input type="hidden" id="job_fk" name="job_fk" value=""/>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Send Report Via SMS</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="all_mobile_no">
                                            <div class="form-group">
                                                <input type="text" placeholder="Mobile No.1" id="mobile_no0" name="mobile_no[]" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num();"><i class="fa fa-plus"></i> Add</a>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" id="sms_report" rows="20" placeholder="SMS..." name="sms" required=""></textarea>
                                        </div>
                                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>
                                        <h3>Send SMS History</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Mobile No</th>
                                                    <th style="width:70%">SMS</th>
                                                    <th>Send By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="result_sms_history">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?= form_close(); ?>
                                </div>

                            </div>
                        </div>
                        <div class="modal fade" id="mail_modal" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <?php echo form_open("tech/job_master/send_report_email", array("method" => "post", "role" => "form")); ?>
                                    <input type="hidden" id="job_fk1" name="job_fk" value=""/>
                                    <input type="hidden" id="cust_fk1" name="cust_fk" value=""/>
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Send Report Via Mail</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="all_mobile_no1">
                                            <div class="form-group">
                                                <input type="email" placeholder="Email-1" id="email_no0" name="email[]" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="append_num1();"><i class="fa fa-plus"></i> Add</a>
                                        </div>
                                        <div class="pull-right"><button type="submit" class="btn btn-primary" id="amount_submit_btn">Send</button></div><br>
                                        <h3>Send Email History</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Send By</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="result_email_history">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?= form_close(); ?>
                                </div>

                            </div>
                        </div>
                        <div class="modal fade" id="barcode_modal" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Barcode</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="all_mobile_no1">
                                            <div class="form-group">
                                                <input type="text" placeholder="Barcode" id="b_barcode" name="barcode" class="form-control"/>
                                                <span style="color:red;" id="b_error"></span>
                                                <span style="color:green" id="b_success"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="b_update();">Update</a>
                                        </div>
                                        <input type="hidden" id="b_job_id" value=""/>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="javascript:void(0);" target="_blank" id="b_print" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?= form_close(); ?>
                                </div>

                            </div>
                        </div>
                        <div class="modal fade" id="print_model1" role="dialog">
                            <div class="modal-dialog">
                                <input type="hidden" id="getjobsid" >
                                <?php echo form_open("tech/add_result/reporttest_preview/", array("method" => "POST", "id" => 'printmyForm', "role" => "form")); ?>
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Print Report</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="printerror" ></div>
                                        <div id="printcontain" class="modal-body"></div>

                                        <label class="radio-inline">
                                            <input type="radio" checked value='1' name="latterhead">without letterhead
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" value='2' name="latterhead">with letterhead
                                        </label> </div>
                                    <div class="modal-footer">
                                        <input type="submit"  class="btn btn-primary" id="genprintbutton" value="Print Report">
                                        <?php echo form_close(); ?>

                                    </div>
                                </div>

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
<script  type="text/javascript">
    function open_barcode_popup(val) {
        //alert(val);
        $("#b_error").html("");
        $("#b_success").html("");
        $("#b_job_id").val(val);
        if (val.trim() != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>tech/job_master/get_barcode?jid=" + val,
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                },
                success: function (response) {
                    var barcode = JSON.parse(response);
                    $("#b_barcode").val(barcode[0].barcode);
                    $("#b_print").attr("href", "<?= base_url(); ?>index.php/utility/getJobBarcode?id=" + val)

                }, complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                }
            });
            $('#barcode_modal').modal('show');
        } else {
            $("#b_error").html("Sample Id is null Try again.");
        }
    }
    function b_update() {
        $("#b_error").html("");
        $("#b_success").html("");
        var jid = $("#b_job_id").val();
        var b_barcode = $("#b_barcode").val();

        if (jid.trim() != '' && b_barcode.trim() != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>tech/job_master/update_barcode?jid=" + jid + "&b_barcode=" + b_barcode,
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                },
                success: function (response) {
                    if (response.trim() == '1') {
                        $("#b_success").html("Barcode successfully updated.");
                    } else {
                        $("#b_error").html("Oops somthing wrong, Try again.");
                    }
                }, complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                }
            });
            //$('#barcode_modal').modal('show');
        } else {
            $("#b_error").html("Barcode is required.");
        }
    }
    function printreport(jobsid) {

        $("#getjobsid").val(jobsid);
        $('#print_model1').modal('show');
        $('#printcontain').html('<div style="height:50px"><span id="searching_spinner_center" style="position: absolute;left: 50%;"><img src="<?= base_url() . "img/ajax-loader.gif" ?>" /></span></div>');

        $.ajax({url: "<?php echo base_url() . "tech/job_master/getjobs_bookedprint"; ?>",
            type: "GET",
            data: {jobs: jobsid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                if (data != "0") {
                    $('#printcontain').html(data);
                    $(".up,.down").click(function () {
                        var row = $(this).parents("tr:first");
                        if ($(this).is(".up")) {
                            row.insertBefore(row.prev());
                        } else {
                            row.insertAfter(row.next());
                        }
                    });

                    $(".printnewadd").click(function () {
                        var checkid = this.id;
                        var splitchack = checkid.split("_")
                        var chtestid = splitchack[1];
                        if ($('#' + checkid).is(':checked')) {
                            $("#printvalue_" + chtestid).val("1");
                        } else {
                            $("#printvalue_" + chtestid).val("");
                        }
                    });
                }
            }
        });
        return false;
    }
    $('#printmyForm').on('submit', function (e) {
        e.preventDefault();

        var favorite = [];

        $.each($("input[name='testall[]']:checked"), function () {

            favorite.push($(this).val());

        });
        if (favorite != "") {

            $('#print_model1').modal('hide');

            $("#loader_div").show();
            var jobsid = $("#getjobsid").val();

            $.ajax({
                url: '<?= base_url() . "tech/add_result/reporttest_preview/"; ?>' + jobsid,
                type: 'post',
                data: $('form#printmyForm').serialize(),
                success: function (data) {
					$('#printicon').css({'color': 'green'});
                    window.location.href = '<?= base_url() . "tech/job_master/testprint_downloade/" ?>' + data;
                    $("#loader_div").hide();
                    $("#printmyForm")[0].reset();
                }
            });
        } else {
            $("#printerror").html('<div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>Please select booked test.</div>');
        }

    });
    function sub_form() {
        var form = $('#job_search');
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (response) {
                var recursiveDecoded = decodeURIComponent(form.serialize());
                $("#example4").html("");
                $("#example4").html(response);
                // $("#job_search_btn").attr("href", "<?= base_url(); ?>/job_master/export_csv?" + recursiveDecoded);
                get_test_rslt();
            }, complete: function () {
                $("#loader_div").attr("style", "display:none;");
            }
        });
    }
    $sms_cnt = 1;
    $num_cnt = 2;
    function append_num() {
        $("#all_mobile_no").append('<div class="form-group" id="add_num_' + $sms_cnt + '" style="width:93%;height:40px;"><input type="text" placeholder="Mobile No.' + $num_cnt + '" id="mobile_no' + $sms_cnt + '" name="mobile_no[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num_' + $sms_cnt + '.remove();$sms_cnt--;$num_cnt--;"><i class="fa fa-trash"></i></a></div>');
        $sms_cnt++;
        $num_cnt++;
    }
    $sms_cnt1 = 1;
    $num_cnt1 = 2;
    function append_num1() {
        $("#all_mobile_no1").append('<div class="form-group" id="add_num1_' + $sms_cnt1 + '" style="width:93%;height:40px;"><input type="email" placeholder="Email-' + $num_cnt1 + '" id="mobile_no1' + $sms_cnt1 + '" name="email[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num1_' + $sms_cnt1 + '.remove();$sms_cnt1--;$num_cnt1--;"><i class="fa fa-trash"></i></a></div>');
        $sms_cnt1++;
        $num_cnt1++;
    }
    $sms_cnt = 1;
    $num_cnt = 2;
    function append_num2() {
        $("#all_mobile_no2").append('<div class="form-group" id="add_num_' + $sms_cnt + '" style="width:93%;height:40px;"><input type="text" placeholder="Mobile No.' + $num_cnt + '" id="mobile_no1' + $sms_cnt + '" name="mobile_no[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num_' + $sms_cnt + '.remove();$sms_cnt--;$num_cnt--;"><i class="fa fa-trash"></i></a></div>');
        $sms_cnt++;
        $num_cnt++;
    }
    var citiess = $("#citiess").val();
    if (citiess == "1") {
        testcity = 333;
    }
    if (citiess == "5") {
        ;
        testcity = 1510;
    }

    if (citiess == "6") {
        testcity = 345;
    }
    if (citiess == "8") {
        testcity = 476;
    }
    $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_refered_by',
        type: 'post',
        data: {val: testcity, selected: '<?= $referral_by ?>'},
        success: function (data) {
            var json_data = JSON.parse(data);
            $("#referral_by").html(json_data.refer);
            jQuery('.chosen').trigger("chosen:updated");
        },
        error: function (jqXhr) {
            $("#referral_by").html("");
        },
        complete: function () {
        },
    });

    /*    $.ajax({
     url: '<?php echo base_url(); ?>job_master/get_customer',
     type: 'post',
     data: {val: 1, selected: '<?= $user2 ?>'},
     success: function (data) {
     var json_data = JSON.parse(data);
     $("#customer_list").html(json_data.customer);
     jQuery('.chosen').trigger("chosen:updated");
     },
     error: function (jqXhr) {
     $("#customer_list").html("");
     },
     complete: function () {
     },
     }); */
    function sms_popup(jid) {
        $("#result_sms_history").empty();
        $.ajax({
            url: '<?php echo base_url(); ?>add_result/send_result/' + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {

                var json_data = JSON.parse(data);

                if (json_data.status == 1) {
                    $("#sms_report").val(json_data.sms);
                    for (var i = 0; json_data.mobile.length > i; i++) {
                        if (i == 0) {
                            $("#mobile_no" + i).val(json_data.mobile[i]);
                        }
                        if (i > 0) {
                            if ($num_cnt1 == 2)
                            {
                                append_num();
                                $("#mobile_no" + i).val(json_data.mobile[i]);
                            }
                        }

                    }
                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].mobile;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }
                        $("#result_sms_history").append('<tr> <td>' + txt + '</td> <td><span class="more">' + json_data.history[j].sms + '</span></td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                    }
                    if (json_data.history.length == 0) {
                        $("#result_sms_history").append('<tr> <td colspan="4"><center>Data not available.</center></td></tr>');
                    }
                    more_less();
                    $("#job_fk").val(jid);
                    $("#sms_modal").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }
    function sms_popup1(jid) {

        $.ajax({
            url: '<?php echo base_url(); ?>tech/add_result/send_result/' + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);

                if (json_data.status == 1) {
                    $("#sms_report1").val(json_data.sms);
                    for (var i = 0; json_data.mobile.length > i; i++) {
                        if (i == 0) {
                            $("#mobile_no1" + i).val(json_data.mobile[i]);
                        }
                        if (i > 0) {

                            append_num2();
                            $("#mobile_no1" + i).val(json_data.mobile[i]);
                        }



                    }
                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].mobile;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }

                    }
                    if (json_data.history.length == 0) {
                        $("#result_sms_history").append('<tr> <td colspan="4"><center>Data not available.</center></td></tr>');
                    }
                    more_less();
                    $("#job_fk1").val(jid);
                    $("#sms_modal1").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }

    function sms_popup_old(jid) {
        $("#result_sms_history").empty();
        $.ajax({
            url: '<?php echo base_url(); ?>tech/add_result/send_result/' + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $("#sms_report").val(json_data.sms);
                    for (var i = 0; json_data.mobile.length > i; i++) {
                        $("#mobile_no" + i).val(json_data.mobile[i]);
                    }
                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].mobile;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }
                        $("#result_sms_history").append('<tr> <td>' + txt + '</td> <td><span class="more">' + json_data.history[j].sms + '</span></td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                    }
                    if (json_data.history.length == 0) {
                        $("#result_sms_history").append('<tr> <td colspan="4"><center>Data not available.</center></td></tr>');
                    }
                    more_less();
                    $("#job_fk").val(jid);
                    $("#sms_modal").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }
    function mail_popup(jid, cid) {
        $("#result_email_history").empty();
        $.ajax({
            url: '<?php echo base_url(); ?>tech/job_master/get_user_info/' + cid + "/" + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $("#email_no0").val(json_data.data[0]);
                    if (json_data.data.length > 1) {
                        if ($num_cnt1 == 2)
                        {
                            $("#all_mobile_no1").append('<div class="form-group" id="add_num1_' + $sms_cnt1 + '" style="width:93%;height:40px;"><input type="email" value="' + json_data.data[1] + '" placeholder="Email-' + $num_cnt1 + '" id="mobile_no1' + $sms_cnt1 + '" name="email[]" class="form-control" style="width:94%;float:left;margin-right:16px"/><a href="javascript:void(0);" onclick="add_num1_' + $sms_cnt1 + '.remove();$sms_cnt1--;$num_cnt1--;"><i class="fa fa-trash"></i></a></div>');
                            $sms_cnt1++;
                            $num_cnt1++;

                        }
                    }


                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].email;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }
                        $("#result_email_history").append('<tr> <td>' + txt + '</td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                    }
                    if (json_data.history.length == 0) {
                        $("#result_email_history").append('<tr> <td colspan="3"><center>Data not available.</center></td></tr>');
                    }
                    $("#job_fk1").val(jid);
                    $("#cust_fk1").val(cid);
                    $("#mail_modal").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk1").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }

    function mail_popup_old(jid, cid) {
        $("#result_email_history").empty();
        $.ajax({
            url: '<?php echo base_url(); ?>tech/job_master/get_user_info/' + cid + "/" + jid,
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.status == 1) {
                    $("#email_no0").val(json_data.data[0].email);
                    for (var j = 0; json_data.history.length > j; j++) {
                        var numbr = json_data.history[j].email;
                        var new_no = numbr.split(",");
                        var txt = '';
                        for (var nbr = 0; new_no.length > nbr; nbr++) {
                            txt += new_no[nbr] + '<br/>';
                        }
                        $("#result_email_history").append('<tr> <td>' + txt + '</td> <td>' + json_data.history[j].name + '</td> <td>' + json_data.history[j].created_date + '</td></tr>');
                    }
                    if (json_data.history.length == 0) {
                        $("#result_email_history").append('<tr> <td colspan="3"><center>Data not available.</center></td></tr>');
                    }
                    $("#job_fk1").val(jid);
                    $("#cust_fk1").val(cid);
                    $("#mail_modal").modal("show");
                } else {
                    alert("Data not available.");
                    $("#job_fk1").val("");
                }
            },
            error: function (jqXhr) {
                alert("Try again.");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }
    function get_branch(val) {
        var test_city = $("#citiess").val();
        var plm = $("#plm_id").val();
        $.ajax({
            url: '<?php echo base_url(); ?>tech/job_master/get_branch',
            type: 'post',
            data: {city: test_city, plm: plm},
            success: function (data) {
                $("#branch").html(data);
                $('.multiselect-ui').multiselect("rebuild");
                if (data.trim() == '') {
                    setTimeout(function () {
                        $("ul.multiselect-container.dropdown-menu").html("Data not available.");
                    }, 1000);
                }
            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });
    }
    function get_plm(val) {
        $.ajax({
            url: '<?php echo base_url(); ?>tech/Admin/get_refered_by',
            type: 'post',
            data: {tid: val, selected: '<?= $referral_by ?>'},
            success: function (data) {
                var json_data = JSON.parse(data);
                $("#referral_by").html(json_data.refer);
                jQuery('.chosen').trigger("chosen:updated");

            },
            error: function (jqXhr) {
                $("#referral_by").html("");
            },
            complete: function () {
            },
        });

        $.ajax({
            url: '<?php echo base_url(); ?>tech/job_master/get_plm',
            type: 'post',
            data: {city: val},
            success: function (data) {
                $("#plm_id").html(data);
                //$('.multiselect-ui').multiselect("rebuild");
                jQuery("#plm_id").trigger("chosen:updated");
                get_branch("1");
            },
            error: function (jqXhr) {
                $("#plm_id").html("");
            },
            complete: function () {
            },
        });
    }
    function clear_branch() {
        $("#branch").html("");
        $('.multiselect-ui').multiselect("rebuild");
    }
</script> 
<script type="text/javascript">
    $(function () {

        $('#example3').dataTable({
            //"bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
<?php if (!isset($_GET["city"]) || $_GET["city"] == '') { ?>
        setTimeout(function () {
            // $("ul.multiselect-container.dropdown-menu").html("Select City");
        }, 3000);
<?php } ?>
    $(document).ready(function () {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
    function more_less() {
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    }
    /*Check test result start*/
    $(document).ready(function () {
        $.ajax({
            url: '<?php echo base_url(); ?>tech/job_master/check_test_parameter_val',
            type: 'post',
            data: {job_id: '<?= implode(",", $test_ids); ?>'},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.data) {
                    var job_data = json_data.data;
                    $.each(job_data, function (index, value) {
                        var job_fk = value.job_test;
                        if (value.details) {
                            $.each(value.details, function (index1, value1) {
                                var tst_fk = value1.test_fk;
                                var tst_parameter = value1.parameter;
                                var tst_result = value1.result;
                                if (tst_result.length > 0 && tst_parameter.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_graph = value1.graph;
                                if (tst_graph.length > 0) {
                                    if ($("#test_" + job_fk + "_" + tst_fk)) {
                                        $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                    }
                                }
                                var tst_approve_result = value1.approve;
                                if (tst_approve_result.length > 0) {
                                    $.each(tst_approve_result, function (index2, value2) {
                                        if ($("#test_" + value2.job_fk + "_" + value2.test_fk)) {
                                            $("#test_" + value2.job_fk + "_" + value2.test_fk).attr("style", "background:green;color:white;");
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });

    });
    /*End*/
function get_test_rslt() {
        var ids = $("#search_test_ids").html();
        if ($job_fk != '') {
            var ids = $job_fk;
        }
        $approved_test_cnt = 0;
        $.ajax({
            url: '<?php echo base_url(); ?>job_master/check_test_parameter_val',
            type: 'post',
            data: {job_id: ids},
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.data) {
                    var job_data = json_data.data;

                    var add_result_array = [];
                    $.each(job_data, function (index, value) {
                        var job_fk = value.jid;
                        $(".test_" + job_fk).each(function () {
                            var vl = this.id;
                            var v2 = vl.split("_");
                            /*var vl = this.title;*/
                            $(this).attr("onclick", "approveTest1('" + job_fk + "','" + v2[2] + "')");
                            $(this).attr("style", "cursor:pointer;");
                            $(this).attr("class", "text_highlight");
                        });
                        var job_status = value.status;
                        $.each(value.details, function (index1, value1) {
                            var tst_fk = value1.test_fk;
                            var tst_status = value1.test_status;
                            tst_status = parseInt(tst_status);
                            var tst_parameter = value1.parameter;
                            var tst_result = value1.result;
                            var tst_outsource_result = value1.outsource;
                            var check_test = 0;

                            if (tst_status == 0) {
                                if ($("#test_" + job_fk + "_" + tst_fk)) {
                                    $("#test_" + job_fk + "_" + tst_fk).attr("style", "cursor:pointer;");
                                    $("#test_" + job_fk + "_" + tst_fk).attr("onclick", "approveTest1('" + job_fk + "','" + tst_fk + "')");
                                    $("#test_" + job_fk + "_" + tst_fk).attr("class", "text_highlight");
                                    check_test = 1;
                                }
                            }

                            if (tst_status == 3) {
                                /*Remianing Nishit*/
                                if ($("#test_" + job_fk + "_" + tst_fk)) {
                                    $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:blue;color:white;");
                                    check_test = 1;
                                    if (job_status == '8') {
<?php if ($login_data['type'] == 1 || $login_data['type'] == 2 || $login_data['type'] == 5 || $login_data['type'] == 6) { ?>
                                            $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:blue;color:white;cursor:pointer;");
                                            $("#test_" + job_fk + "_" + tst_fk).attr("onclick", "approveTest1('" + job_fk + "','" + tst_fk + "')");
                                            $("#test_" + job_fk + "_" + tst_fk).attr("class", "text_highlight");
<?php } ?>
                                    }
                                }
                            }


                            if (tst_status == 1) {
                                if ($("#test_" + job_fk + "_" + tst_fk)) {
                                    $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:#f7f574;cursor:pointer;");
<?php if ($login_data['type'] == 1 || $login_data['type'] == 2 || $login_data['type'] == 5) { ?>
                                        $("#test_" + job_fk + "_" + tst_fk).attr("onclick", "approveTest1('" + job_fk + "','" + tst_fk + "')");
                                        $("#test_" + job_fk + "_" + tst_fk).attr("class", "text_highlight");
                                        $("#btn_" + job_fk + "_" + tst_fk).attr("style", "");
                                        add_result_array.push(tst_fk);
<?php } else if ($login_data['type'] == 6) { ?>
                                        $("#test_" + job_fk + "_" + tst_fk).attr("onclick", "approveTest1('" + job_fk + "','" + tst_fk + "')");
                                        $("#test_" + job_fk + "_" + tst_fk).attr("class", "text_highlight");
<?php } ?>
                                    check_test = 1;
                                }
                            }

                            var tst_approve_result = value1.approve;
                            if (tst_status == 2) {
                                if ($("#test_" + job_fk + "_" + tst_fk)) {
                                    $("#test_" + job_fk + "_" + tst_fk).attr("style", "background:green;color:white;cursor:pointer;");
                                    $("#test_" + job_fk + "_" + tst_fk).attr("onclick", "approveTest1('" + job_fk + "','" + tst_fk + "')");
<?php if ($login_data['type'] == 1 || $login_data['type'] == 2 || $login_data['type'] == 5 || $login_data['type'] == 6) { ?>
                                        $("#dbtn_" + job_fk + "_" + tst_fk).attr("style", "");
<?php } ?>
                                    $("#btn_" + job_fk + "_" + tst_fk).attr("style", "display:none;");
                                    check_test = 1;
                                    removeA(add_result_array, tst_fk);
                                    $approved_test_cnt++;
                                }
                            }
                        });
                    });
                    var selected_ids = JSON.parse(JSON.stringify(add_result_array));
                    $("#result_added_test").val(selected_ids);
                    if (selected_ids != '') {
                        $("#approved_all_btn").removeAttr("style");
                    } else {
                        $("#approved_all_btn").attr("style", "display:none;");
                    }
                }
            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });
    }
    sub_form();
    function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
    what = a[--L];
    while ((ax = arr.indexOf(what)) !== - 1) {
    arr.splice(ax, 1);
    }
    }
    return arr;
    }
    //pinkesh code start
    function dispatched_job_test(jid, status) {
        var test = confirm('Are you sure?');
        if (test == true) {
            $.ajax({
                url: '<?php echo base_url(); ?>tech/job_master/job_dispatch',
                type: 'post',
                data: {job_id: jid, status: status},
                success: function (data) {
                    if (status == '0') {
                        $("#dispatch_1_" + jid).hide();
                        $("#dispatch_0_" + jid).show();
                    } else if (status == '1') {
                        $("#dispatch_1_" + jid).show();
                        $("#dispatch_0_" + jid).hide();
                    }
                },
                complete: function () {
                    if (status == '0') {
                        alert("Dispatch Remove successfully");
                    } else if (status == '1') {
                        alert("Dispatched Successfully");
                    }
                },
            });
        }
    }
    //pinkesh code end
    $job_fk = '';
    function approveTest(jid, tid) {
        jq.fancybox.open({
            href: '<?= base_url(); ?>tech/add_result/test_details/' + jid + '/' + tid,
            type: 'iframe',
            padding: 5,
            width: '100%',
        });
        $job_fk = jid;
    }
    function approveTest1(jid, tid) {
        jq.fancybox.open({
            href: '<?= base_url(); ?>tech/add_result/test_details/' + jid + '/' + tid,
            type: 'iframe',
            padding: 5,
            width: '100%',
        });
        $job_fk = jid;
    }
    function close_popup1() {
        jq.fancybox.close();
        get_test_rslt();
    }
    var test_city = $("#citiess").val();
    get_plm(test_city);
    get_branch("1");
    
    //setInterval(function(){ sub_form(); },300000);
</script>