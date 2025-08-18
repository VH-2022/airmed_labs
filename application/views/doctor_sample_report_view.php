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
            Doctor Sample Collection Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Doctor Sample Collection Report</li>

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


                        <?php echo form_open("Doctor_sample_report/index", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="start_date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="end_date"  value="<?= $end_date ?>"/>
                            </div>

                            <div class="col-sm-2">
                                <select class="form-control" name="city_id" id="city_id" tabindex="-1">
                                    <?php foreach ($city as $key2) {
                                        ?>
                                        <option value="<?php echo $key2->id ?>" <?php if ($key2->id == $city_id) { ?>selected<?php } ?>><?php echo $key2->name ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <select class="multiselect-ui form-control" multiple="multiple"  name="doc_id[]" id="doc_id" tabindex="-1">
                                    <?php foreach ($doctor as $key) {
                                        ?>
                                        <option value="<?php echo $key->id ?>" <?php if (in_array($key->id, $doc)) { ?>selected<?php } ?>><?php echo $key->full_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-sm-3" style="margin-top:10px;float:right;">
                                <input type="submit" name="search" class="btn btn-success btn-sm" value="Search" />
                                <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>Doctor_sample_report/index">Reset</a>
                                <a href='<?php echo base_url(); ?>Doctor_sample_report/export_csv/?start_date=<?= $start_date; ?>&end_date=<?= $end_date; ?>&city_id=<?= $city_id; ?>&doc_id=<?= urlencode(implode(",", $doc)); ?>&search=Search' class="btn btn-primary btn-sm"><i class="fa fa-download"></i>Export To CSV</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <div class="tableclass">
                            <div class="table-responsive pending_job_list_tbl" id="prnt_rpt">
                                <table id="example4" class="table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:12%"><h4>Date\Doctors</h4></th>
                                    <?php
                                    $haystack = [];
                                    foreach ($doc_list as $row) {
                                        if (!in_array($row->id, $haystack)) {
                                            $haystack[] = $row->id;
                                            ?>
                                            <th><h4><?php echo $row->full_name; ?></h4></th>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <th><h4>Total</h4></th>
                                    </tr>




                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 0;
                                        foreach ($final_array as $row1) {
                                            ?>
                                            <tr>
                                                <td><?php echo date('Y-m-d', strtotime($row1->job_date)) ?></td>
                                                <?php
                                                $total_vertical = 0;
                                                foreach ($haystack as $h) {
                                                    $count = 0;
                                                    foreach ($row1->doc_info as $r) {
                                                        if ($h == $r->id) {
                                                            $count = $r->doctor_wise_total;
                                                            $total_vertical += $count;
                                                        }
                                                    }

                                                    if ($count == 0) {
                                                        $style = "background:yellow";
                                                    } else {
                                                        $style = "";
                                                    }
                                                    ?>
                                                    <td style="<?php echo $style; ?>"><?= $count; ?></td>
                                                    <?php
                                                }
                                                ?>
                                                <td><B><?php echo $total_vertical; ?></B></td>
                                            </tr>

                                            <?php
                                            $cnt++;
                                        }
                                        ?>



                                        <?php if (empty($final_array)) {
                                            ?>
                                            <tr>
                                                <td colspan="2">Data Not Available</td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td><B>Total</B></td>
                                                <?php
                                                $grand_total = 0;
                                                foreach ($doc_wise_total as $key1) {
                                                    $grand_total += $key1->total;
                                                    ?>
                                                    <td><B><?php echo $key1->total; ?></B></td>
                                                <?php } ?>
                                                <td><B><?php echo $grand_total ?></B></td>
                                            </tr>                                          <?php }
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script>
    $('#city_id').on('change', function () {
        var cityID = $(this).val();
        if (cityID) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>Doctor_sample_report/fetch_doc/' + cityID,
                //dataType: 'json',
                data: {"cityID": cityID}, // serializes the form's elements.
                success: function (response)
                {
                   //console.log(response); 
                    var $el = $("#doc_id");
                    $el.empty(); // remove old options
//                    $el.append($("<option></option>")
//                            .attr("value", '').text('Select Doctor'));
//                    
//                    $.each(response, function (index, data) {
//                        $('#doc_id').append('<option value="' + data['id'] + '">' + data['full_name'] + '</option>');
//                    });
                    $('#doc_id').html(response);
                    $('#doc_id').multiselect('rebuild');
                }
            });
        }
    });

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
            $("#start_date").datepicker('setEndDate', endDate);
        }).attr("autocomplete", "off");
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<Script>
    $(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            //nonSelectedText: 'Select Branch',
            enableFiltering: true
        });
    });
</script>