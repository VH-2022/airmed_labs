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
</style>
<style>
   
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
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Order ID";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Booking Date";}
		.pending_job_list_tbl td:nth-of-type(4):before {content: "Patient Name";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Test Name";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: "Airmed Source Lab Name";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: "Test MRP at Airmed";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: "Outsource Lab";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: "Outsource Price";}
        .pending_job_list_tbl td:nth-of-type(10):before {content: "Show Price of other Destination";}
        /*.pending_job_list_tbl td:nth-of-type(10):before {content: "Action";}*/
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
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Outsource list
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Outsource Report</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        </div>


                        <!-- Search start-->
                        <?php echo form_open("report_master/outsource_report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
<!--                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="date"  value="<?php //echo $start_date                             ?>" />-->
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="start_date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
<!--                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="date1"  value="<?php //echo $end_date                             ?>" />-->
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="end_date"  value="<?= $end_date ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch">
                                    <option value="" >Select Branch</option>
                                    <?php
                                    foreach ($branch_list as $branch1) {
                                        ?>
                                        <option value = "<?php echo $branch1['id']; ?>" <?php
                                        if ($branch1['id'] == $branch_id) {
                                            echo "selected";
                                        }
                                        ?>> <?php echo $branch1['branch_name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </div>
							<div class="col-sm-3">
                                <select class="form-control chosen" data-placeholder="Select Outsource Lab" tabindex="-1" name="outsourcelab">
                                    <option value="" >Select Outsource Lab</option>
                                    <?php
                                    foreach ($outsourcelab_list as $outsourcelab) {
                                        ?>
                                        <option value = "<?php echo $outsourcelab['id']; ?>" <?php
                                        if ($outsourcelab['id'] == $outsourcelab_id) {
                                            echo "selected";
                                        }
                                        ?>> <?php echo $outsourcelab['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </div>
                            <div class="col-sm-3" style="margin-top:10px;float:right;">								
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
								<a class="btn btn-primary" href="<?php echo base_url(); ?>report_master/outsource_report">Reset</a>								                                
								<a href='<?php echo base_url(); ?>report_master/export_csv_outsource/?start_date=<?= $_GET['start_date']; ?>&end_date=<?= $_GET['end_date']; ?>&branch=<?= $_GET['branch']; ?>&outsourcelab=<?=$_GET['outsourcelab'];?>&search=Search' class="btn btn-primary btn-sm"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
                            </div>																						
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->

                        <div class="tableclass">
                            <div class="table-responsive pending_job_list_tbl" id="prnt_rpt">
                                <table id="example4" class="table-striped">
                                    <thead>
                                        <tr>
                                   <th><h4>No.</h4></th>
                                    <th><h4>Order ID</h4></th>
                                     <th><h4>Booking Date</h4></th>
									 <th><h4>Patient Name</h4></th>
                                    <th><h4>Test Name</h4></th>
                                    <th><h4>Airmed Source Lab Name</h4></th>
                                    <th><h4>Test MRP At Airmed</h4></th>
                                    <th><h4>Outsource Lab</h4></th>
                                     <th><h4>Outsource Price</h4></th>
                                    <th><h4>Price Of Other Destination Lab</h4></th>

                                    </tr>
                                    </thead>
                                     <tbody>
                                        <?php
                                        $cnt = 1;
                                        foreach ($final_array as $row) { //echo "<prE>"; print_r($final_array);
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $counts+$cnt; ?></td>
                                                <td><b><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $row["job_id"]; ?>"><?php echo $row['order_id']; ?></a></b><br>
                                                   <small>Added By: <?php echo ucwords($row["admin_name"]); ?></small>
                                                </td>
                                                <td><?php echo $row["created_date"]; ?></td>
												<td><?php echo $row["patient_name"]; ?></td>
                                                <td><?php echo $row["test_name"]; ?></td>
                                                <td><?php echo $row['branch_name']; ?></td>
                                                <td><?php echo $row['price'];?></td>
                                                <td><?php echo ucwords($row["lab_name"]); ?></td>
                                               
                                                <td>
                                               <?php foreach($row['new_price'] as $val){ if($val['OutID'] == $row['OutSource_id']){ ?>
                                                    <?php echo "Rs.".$val['OutPrice'];?>
                                               <?php } } ?>
                                                    </td>
                                                <td>
                                                     <?php foreach($row['new_price'] as $val){ if($val['OutID'] != $row['OutSource_id']){ ?>
                                                    <span><?php echo ucwords($val['name']);?></span> <?php echo '(Rs.'.$val['OutPrice'].')<br>';?>
                                               <?php }  } ?>
                                                
                                                    </td>
                                            </tr>

                                            <?php
                                            $cnt++;
                                        } if (empty($final_array)) {
                                            ?>
                                            <tr>
                                                <td colspan="10">No records found</td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                        </div>
 
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    $(function () {
        $('.chosen').chosen();
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');
    function printData()
    {
        var divToPrint = document.getElementById("prnt_rpt");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script> 
<script type="text/javascript">
    $(function () {

        $('#example3').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 100
        });
    });
    var cities = $("#city_id").val();
    $.ajax({
        url: '<?php echo base_url(); ?>Admin/get_refered_by',
        type: 'post',
        data: {val: cities, selected: '<?= $doctor ?>'},
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
    function change_citys(value) {
        $.ajax({
            url: '<?php echo base_url(); ?>Admin/get_refered_by',
            type: 'post',
            data: {val: value},
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
    }
    function cut_doctor_save(did) {
        var cut_doc = $("#doctor_cut_" + did).val();
        $.ajax({
            url: '<?php echo base_url(); ?>doctor_report/doctor_cut',
            type: 'post',
            data: {did: did, cut_val: cut_doc},
            success: function (data) {
                location.reload();
            }, error: function (jqXhr) {
                alert("Please Try Again!");
            },
        });
    }
    function doctors_handover(did, amount) {
        if (amount == '0') {
            alert("Amount is 0.");
        } else {
            var confo = confirm("Are you sure ?");
            if (confo == true) {
                $("#remark_model").modal('show');
                $("#doctor_id").val(did);
                $("#doctor_amount").val(amount);
            }
        }
    }
    function pay_doctors() {
        var did = $("#doctor_id").val();
        var amount = $("#doctor_amount").val();
        var remark = $("#remark").val();
        $.ajax({
            url: '<?php echo base_url(); ?>doctor_report/doctor_pay',
            type: 'post',
            data: {did: did, amount: amount, remarks: remark},
            success: function (data) {
                location.reload();
            },
            error: function (jqXhr) {
                alert("Please Try Again!");
            },
        });
    }
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<!--<script>
//    var start = new Date();
//
//    start.setFullYear(start.getFullYear() - 100);
//    var end = new Date();
//
//    end.setFullYear(end.getFullYear() - 0);
//    $('.datepicker').datepicker({
//        changeMonth: true,
//        changeYear: true,
//        minDate: start,
//        maxDate: end,
//        yearRange: start.getFullYear() + ':' + end.getFullYear(),
//        format: 'yyyy-mm-dd'
//    });   

    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    })
            .on('changeDate', function (selected) {
        var startDate = new Date(selected.date.valueOf());
        alert(startDate);
        $('#end_date').datepicker('setStartDate', startDate);
   }).on('clearDate', function (selected) {
        $('#end_date').datepicker('setStartDate', null);
   });

    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    })
            .on('changeDate', function (selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', endDate);
    }).on('clearDate', function (selected) {
        $('#start_date').datepicker('setEndDate', null);
    });
</script>-->
<script>
$(function () {
    $("#start_date").datepicker({
         format: 'yyyy-mm-dd',
        numberOfMonths: 1,
       
    }).on('changeDate', function (selected) {
            var dt = new Date(selected.date.valueOf());
            
            dt.setDate(dt.getDate());
            $("#end_date").datepicker('setStartDate', dt);
        }).on('clearDate', function (selected) {
        $('#end_date').datepicker('setStartDate', null);
   }).attr("autocomplete", "off");
    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        numberOfMonths: 1,
       
    }).on('changeDate', function (selected) { 
             var endDate = new Date(selected.date.valueOf());
            
            endDate.setDate(endDate.getDate());
            $("#start_date").datepicker('setEndDate', endDate).attr("autocomplete", "off");
        }).attr("autocomplete", "off");
});      
</script>