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
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all"/>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            TAT Status Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>TAT Report</li>

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
                        <?php echo form_open("tat_report_master/TAT_report", array("method" => "GET", "role" => "form", "id" => "tat_form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control" id="start_date"  value="<?php echo $start_date; ?>" />
                                <span style="color: red;" id="startdate_error"></span>
                            </div>

                            <div class="col-sm-3">
                                <input type="text" name="end_date" placeholder="End date" class="form-control" id="end_date"  value="<?php echo $end_date; ?>" />
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control chosen" data-placeholder="Select City" tabindex="-1" name="city" id="city" onchange="get_branch()">
                                    <option value="" >Select City</option>
                                    <?php
                                    foreach ($city_list as $branch1) {
                                        ?>
                                        <option value = "<?php echo $branch1['id']; ?>" <?php
                                        if ($branch1['id'] == $city) {
                                            echo "selected";
                                        }
                                        ?>> <?php echo $branch1['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="branch" id="branch">
                                    <option value="" >Select Branch</option>
                                    <?php
                                    if (!empty($branch_list)) {
                                        foreach ($branch_list as $branch1) {
                                            ?>
                                            <option value = "<?php echo $branch1['id']; ?>" <?php
                                            if ($branch1['id'] == $branch) {
                                                echo "selected";
                                            }
                                            ?>> <?php echo $branch1['branch_name'] ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                <a style="float:right;" href='<?php echo base_url(); ?>tat_report_master/export_csv_tat/?branch=<?= $_GET['branch'] ?>&start_date=<?= $_GET['start_date'] ?>&end_date=<?= $_GET['end_date'] ?>&city=<?= $_GET['city'] ?>&branch=<?= $_GET['branch'] ?>&search=Search' class="btn btn-primary btn-sm">Export</a>
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
                                            <th><h4>Sr.No</h4></th>
                                    <th><h4>Booking ID</h4></th>
                                    <th><h4>Test Name</h4></th>
                                    <th><h4>TAT</h4></th>
                                    <th><h4>Perform Time</h4></th>
                                    <th><h4>In Range</h4></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            //echo "<pre>"; print_r($row); 
                                            $temp = "";
                                            $in_range = "";
//                                            if ($row['updated_date'] != "") {
//                                                $start_date = new DateTime($row['updated_date']);
//                                            }else{
//                                                $start_date = new DateTime($row['date_time']);
//                                            }
                                            $start_date = new DateTime($row['date_time']);
                                            
                                            $since_start = $start_date->diff(new DateTime($row['created_date']));
                                            $totaltesthorse = $since_start->h;
                                            $total_minute = $since_start->i;



                                            $reporting = $row["reporting"];
                                            if ($totaltesthorse > $reporting) {
                                                $temp = "background-color: #FFF1BC;background-color: #FFBDBC;";
                                                $in_range = "No";
                                            } else if ($totaltesthorse == $reporting) {
                                                if ($since_start->i >= 1) {
                                                    $temp = "background-color: #FFF1BC;background-color: #FFBDBC;";
                                                    $in_range = "No";
                                                } else {
                                                    $in_range = "Yes";
                                                    $temp = "";
                                                }
                                            } else if ($totaltesthorse < $reporting) {
                                                $temp = "";
                                                $in_range = "Yes";
                                            }


                                            //echo "<pre>"; print_r($row); exit;
                                            ?>
                                            <tr style="<?= $temp ?>">
                                                <td><?php echo $cnt; ?></td>
                                                <td><b><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $row["id"]; ?>"><?php echo $row['order_id']; ?></a></b></td>
                                                <td><?php echo $row["test_name"]; ?></td>
                                                <td><?php echo $row["reporting"] . " HRS"; ?></td>
                                                <td><?php
                                                    $start_date = "";
//                                                    if ($row['updated_date'] != "") {
//                                                        $start_date = new DateTime($row['updated_date']);
//                                                    } else {
//                                                        $start_date = new DateTime($row['date_time']);
//                                                    }
                                                    $start_date = new DateTime($row['date_time']);
                                                    $since_start = $start_date->diff(new DateTime($row['created_date']));
                                                    $totaltesthorse = $since_start->h;
                                                    echo $totaltesthorse . " HRS " . $since_start->i . " MINUTES";
                                                    ?></td>
                                                <td><?php echo $in_range; ?></td>
                                            </tr>

                                            <?php
                                            $cnt++;
                                        } if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="6"><center>No records found</center></td>
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
    function get_branch() {
        var did = $("#city").val();
        $.ajax({
            url: '<?php echo base_url(); ?>Tat_report_master/getbranch',
            type: 'post',
            dataType: 'json',
            data: {city: did},
            success: function (response) {
                var $el = $("#branch");
                $el.empty();
                $el.append($("<option></option>")
                        .attr("value", '').text('Select Branch'));

                $.each(response, function (index, data) {
                    $('#branch').append('<option value="' + data['id'] + '">' + data['branch_name'] + '</option>');
                });
                $('#branch').trigger("chosen:updated");
                $('#branch').trigger("listz:updated");
            }
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<!--<script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>-->

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
    })
//            .on('changeDate', function (selected) {
//                var startDate = new Date(selected.date.valueOf());
//                $('#end_date').datepicker('setStartDate', startDate);
//            }).on('clearDate', function (selected) {
//        $('#end_date').datepicker('setStartDate', null);
//    })
            ;


    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    })
//            .on('changeDate', function (selected) {
//                var endDate = new Date(selected.date.valueOf());
//                $('#start_date').datepicker('setEndDate', endDate);
//            }).on('clearDate', function (selected) {
//        $('#start_date').datepicker('setEndDate', null);
//    })
            ;


    // New added
//    $("#start_date").datepicker({
//        todayBtn: 1,
//        autoclose: true, format: 'dd-mm-yyyy', endDate: '+0d'
//    }).on('changeDate', function (selected) {
//        var minDate = new Date(selected.date.valueOf());
//        $('#end_date').datepicker('setStartDate', minDate);
//    });
//
//    $("#end_date").datepicker({format: 'dd-mm-yyyy', autoclose: true, endDate: '+0d'})
//            .on('changeDate', function (selected) {
//                var minDate = new Date(selected.date.valueOf());
//                $('#start_date').datepicker('setEndDate', minDate);
//            });




    $("#tat_form").submit(function (event) {
        $("#startdate_error").html("");
        if ($("#start_date").val() > $("#end_date").val()) {
            $("#startdate_error").html("Start date is greater than end date");
            event.preventDefault();
        }
    });
</script>