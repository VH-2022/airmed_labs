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
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Hard Copy Delivery Status Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Hard Copy Delivery Status Report</li>

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
                        <?php echo form_open("Hardcopydelivery_report/index", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2" style="width:13%">
                                <select class="form-control chosen" data-placeholder="Select Type" tabindex="-1" name="type" id="type">
                                    <option value="1" <?php
                                    if ($type == "1") {
                                        echo "selected";
                                    }
                                    ?>>Doctor</option>
                                    <option value="2" <?php
                                    if ($type == "2") {
                                        echo "selected";
                                    }
                                    ?>>Patient</option>
                                    <option value="3" <?php
                                    if ($type == "3") {
                                        echo "selected";
                                    }
                                    ?>>Both</option>
                                </select>
                            </div>
                            <div class="col-sm-2" style="width:13%">
<!--                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="date"  value="<?php //echo $start_date                                     ?>" />-->
                                <input  type="text" name="handover_start_date" placeholder="Handover Start date" class="form-control datepicker" id="handover_start_date"  value="<?= $handover_start_date ?>" />
                            </div>

                            <div class="col-sm-2" style="width:13%">
<!--                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="date1"  value="<?php //echo $end_date                                     ?>" />-->
                                <input type="text" name="handover_end_date" placeholder="Handover End date" class="form-control datepicker" id="handover_end_date"  value="<?= $handover_end_date ?>" />
                            </div>

                            <div class="col-sm-2" style="width:13%">
                                <input type="text" name="delivery_start_date" placeholder="Delivery Start date" class="form-control datepicker" id="delivery_start_date"  value="<?= $delivery_start_date ?>" />
                            </div>

                            <div class="col-sm-2" style="width:13%">
                                <input type="text" name="delivery_end_date" placeholder="Delivery End date" class="form-control datepicker" id="delivery_end_date"  value="<?= $delivery_end_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <select class="form-control chosen" data-placeholder="Select Phlebo" tabindex="-1" name="phlebo" id="phlebo">
                                    <option value="">Select Phlebo</option>
                                    <?php
                                    foreach ($phlebo_list as $key) {
                                        ?>
                                        <option value = "<?php echo $key['id']; ?>" <?php
                                        if ($key['id'] == $phlebo) {
                                            echo "selected";
                                        }
                                        ?>> <?php echo $key['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                <?php if ($_GET) { ?>
                                    <a style="float:right;margin-right:5px;" href="<?php echo base_url() ?>Hardcopydelivery_report/export_csv?type=<?= $type ?>&handover_start_date=<?= $handover_start_date; ?>&handover_end_date=<?= $handover_end_date; ?>&delivery_start_date=<?= $delivery_start_date; ?>&delivery_end_date=<?= $delivery_end_date; ?>&phlebo=<?= $phlebo; ?>" class="btn btn-primary btn-sm">Export</a>
                                    <a style="float:right;margin-right:5px;" href="<?php echo base_url() ?>Hardcopydelivery_report/index" class="btn btn-primary btn-sm">Reset</a>
                                <?php } ?>

                            </div>
                        </div>
                        <br>
                        <?php //echo form_close(); ?>
                        <!--Search end-->

                        <div class="tableclass">
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><h4>Sr. No.</h4></th>
                                    <th><h4>Job ID</h4></th>
                                    <th><h4>Customer Name</h4>
                                    <th><h4>Phlebo/Runner</h4></th>
                                    <th><h4>Handover DateTime</h4></th>
                                    <th><h4>Delivery DateTime</h4>
                                    <th><h4>Status</h4>
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            if ($type == "3") {
                                                // Doctor && Patient Both
                                                ?>
                                                <tr><td colspan="7"><center>Doctor</center></td></tr>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?= ucwords($row["full_name"]); ?></td>
                                                <td><?php echo $row['doctorPhlebo'] ? ucwords($row['doctorPhlebo']) : '-'; ?></td>
                                                <td><?php echo $row['doctor_date'] ? $row['doctor_date'] . ' ' . $row['doctor_time'] : '-'; ?></td>
                                                <td><?php echo $row['doc_delivery_timestamp'] ? $row['doc_delivery_timestamp'] : '-' ?></td>
                                                <td><?php if ($row['doctor_status'] == "2") { ?>
                                                        <span class="label label-success">Delivered</span>
                                                    <?php } else if ($row['doctor_status'] == "1") { ?>
                                                        <span class="label label-warning">Handover</span>
                                                    <?php } else { ?>
                                                        <span class="label label-danger">Pending</span>
                                                    <?php } ?></td>
                                            </tr>

                                            <tr><td colspan="7"><center>Patient</center></td></tr>
                                            <tr>
                                                <td></td>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?= ucwords($row["full_name"]); ?></td>
                                                <td><?php echo $row['patientPhlebo'] ? ucwords($row['patientPhlebo']) : '-'; ?></td>
                                                <td><?php echo $row['patient_date'] ? $row['patient_date'] . ' ' . $row['patient_time'] : '-'; ?></td>
                                                <td><?php echo $row['patient_delivery_timestamp'] ? $row['patient_delivery_timestamp'] : '-' ?></td>
                                                <td><?php if ($row['patient_status'] == "2") { ?>
                                                        <span class="label label-success">Delivered</span>
                                                    <?php } else if ($row['patient_status'] == "1") { ?>
                                                        <span class="label label-warning">Handover</span>
                                                    <?php } else { ?>
                                                        <span class="label label-danger">Pending</span>
                                                    <?php } ?></td>
                                            </tr>
                                            <tr><td colspan="7"></td></tr>
                                            <?php
                                        } else if ($type == "1") {
                                            // Doctor
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?= ucwords($row["full_name"]); ?></td>
                                                <td><?php echo $row['doctorPhlebo'] ? ucwords($row['doctorPhlebo']) : '-'; ?></td>
                                                <td><?php echo $row['doctor_date'] ? $row['doctor_date'] . ' ' . $row['doctor_time'] : '-'; ?></td>
                                                <td><?php echo $row['doc_delivery_timestamp'] ? $row['doc_delivery_timestamp'] : '-' ?></td>
                                                <td><?php if ($row['doctor_status'] == "2") { ?>
                                                        <span class="label label-success">Delivered</span>
                                                    <?php } else if ($row['doctor_status'] == "1") { ?>
                                                        <span class="label label-warning">Handover</span>
                                                    <?php } else { ?>
                                                        <span class="label label-danger">Pending</span>
                                                    <?php } ?></td>

                                            </tr>
                                            <?php
                                        } else {

                                            // patient
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?= ucwords($row["full_name"]); ?></td>
                                                <td><?php echo $row['patientPhlebo'] ? ucwords($row['patientPhlebo']) : '-'; ?></td>
                                                <td><?php echo $row['patient_date'] ? $row['patient_date'] . ' ' . $row['patient_time'] : '-'; ?></td>
                                                <td><?php echo $row['patient_delivery_timestamp'] ? $row['patient_delivery_timestamp'] : '-' ?></td>
                                                <td><?php if ($row['patient_status'] == "2") { ?>
                                                        <span class="label label-success">Delivered</span>
                                                    <?php } else if ($row['patient_status'] == "1") { ?>
                                                        <span class="label label-warning">Handover</span>
                                                    <?php } else { ?>
                                                        <span class="label label-danger">Pending</span>
                                                    <?php } ?></td>
                                            </tr>


                                        <?php }
                                        ?>


                                        <?php
                                        $cnt++;
                                    } if ($cnt == 1) {
                                        ?>
                                        <tr>
                                            <td colspan="7"><center>No records found</center></td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>
                            <?php echo form_close(); ?>
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
    // $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
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

    $("#handover_start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    })
//            .on('changeDate', function (selected) {
//        var startDate = new Date(selected.date.valueOf());
//        $('#end_date').datepicker('setStartDate', startDate);
//    }).on('clearDate', function (selected) {
//        $('#end_date').datepicker('setStartDate', null);
//    })
            ;

    $("#handover_end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    })
//            .on('changeDate', function (selected) {
//        var endDate = new Date(selected.date.valueOf());
//        $('#start_date').datepicker('setEndDate', endDate);
//    }).on('clearDate', function (selected) {
//        $('#start_date').datepicker('setEndDate', null);
//    })
            ;

    $("#delivery_start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });
    $("#delivery_end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });

//    function get_branch() {
//        var url = "<?php //echo base_url();                                                 ?>Report_master/get_branch";
//        var value = $("#city").val();
//        $.ajax({
//            type: "POST",
//            url: url,
//            dataType: 'json',
//            data: {"id": value},
//            success: function (response)
//            {
//                var $el = $("#branch");
//                $el.empty();
//                $el.append($("<option></option>")
//                        .attr("value", '').text('Select Test'));
//
//                $.each(response, function (index, data) {
//                    $('#branch').append('<option value="' + data['id'] + '">' + data['branch_name'] + '</option>');
//                });
//
//                $('#branch').trigger("chosen:updated");
//                $('#branch').trigger("listz:updated");
//            }
//        });
//    }
</script>