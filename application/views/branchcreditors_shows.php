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
            Creditors list
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Payment Report</li>

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
                        <?php echo form_open("report_master/branchcreditors_all", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
<!--                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="date"  value="<?php //echo $start_date      ?>" />-->
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="start_date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
<!--                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="date1"  value="<?php //echo $end_date      ?>" />-->
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="end_date"  value="<?= $end_date ?>" />
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
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->




                        <div class="tableclass">
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><h4>No.</h4></th>
                                            <th><h4>Creditor Name</h4></th>
                                            <th><h4>Creditor Mobile</h4></th>
                                            <th><h4>Total job</h4></th>
                                            <th><h4>Balance</h4></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($query as $row) {
                                            if ($row["creditors"] != "") {

                                                $view_all_data = getcreorlab($row["creditors"]);
                                                ?>
                                                <tr>
                                                    <td colspan="5"><?= ucwords($row["branch_name"]); ?></td>
                                                </tr>
                                                <?php
                                                $cnt = 1;
                                                $branchid = $row["id"];
                                                foreach ($view_all_data as $am_br) {
                                                    $creditjob = getcreorjobs($row["id"], $am_br["id"]);
                                                    $c_total = 0;
                                                    if ($creditjob[0]["blance"] > 0) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $cnt; ?></td>
                                                            <td><b><a href="<?php echo base_url(); ?>report_master/branchcreditors/<?php echo $row["id"] . "/" . $am_br['id']; ?>"><?php echo ucfirst($am_br['name']); ?></a></b></td>
                                                            <td><?php echo $am_br['mobile']; ?></td>
                                                            <td><?php echo $creditjob[0]["totaljobs"]; ?></td>
                                                            <td><?php echo "Rs. " . round($creditjob[0]["blance"]); ?></td>
                                                        </tr>

                                                        <?php
                                                        $cnt++;
                                                    }
                                                }
                                            }
                                        } if ($cnt == 0) {
                                            ?>
                                            <tr>
                                                <td colspan="5"><center>No records found</center></td>
                                        </tr>
                                    <?php }
                                    ?>
                                    </tbody>
                                </table>
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

    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    }).attr("autocomplete", "off");
//            .on('changeDate', function (selected) {
//        var startDate = new Date(selected.date.valueOf());
//        $('#end_date').datepicker('setStartDate', startDate);
//    }).on('clearDate', function (selected) {
//        $('#end_date').datepicker('setStartDate', null);
//    })
            ;

    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    }).attr("autocomplete", "off");
//            .on('changeDate', function (selected) {
//        var endDate = new Date(selected.date.valueOf());
//        $('#start_date').datepicker('setEndDate', endDate);
//    }).on('clearDate', function (selected) {
//        $('#start_date').datepicker('setEndDate', null);
//    })
            ;
</script>