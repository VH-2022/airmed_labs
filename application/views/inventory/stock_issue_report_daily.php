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
    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Stock Issue Report Daily
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Stock Issue Report Daily</li>

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
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">Ã—</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                        </div>


                        <!-- Search start-->
                        <?php echo form_open("inventory/Stock_issue_report_daily/index", array("method" => "GET", "role" => "form"));
                        $location_name="";
                        ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Start date" autocomplete="off" class="form-control datepicker" id="start_date"  value="<?= $start_date ?>" />
                            </div>

<!--                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="end_date"  value="<?= $end_date ?>" />
                            </div>-->
                            <div class="col-sm-2">
                                <select class="form-control chosen chosen-select" data-placeholder="Select Branch" name="branch" id="branch" >
                                    <option value="" >Select Branch</option>
                                    <?php
                                    foreach ($branch_list as $branch1) {
                                        ?>
                                        <option value = "<?php echo $branch1['id']; ?>" <?php
                                        if ($branch1['id'] == $branch) {
                                            echo "selected";
                                            $location_name=$branch1['branch_name'];
                                        }
                                        ?>> <?php echo $branch1['branch_name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </div>
                            
                          

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
<a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>inventory/Stock_issue_report_daily/get_pdf?start_date=<?php echo $start_date ?>&branch=<?php echo $branch ?>' class="btn btn-primary btn-sm" >Generate PDF</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->

                        <div class="tableclass">
                            <div class="table-responsive" id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <?php /* <tr>
                                          <td colspan="2" style="text-align: left;"><h4>Item Name: </h4></td>
                                          <td colspan="5" style="text-align: left;"><h4><?php
                                          if ($query[0]['reagent_name'] != "") {
                                          echo $query[0]['reagent_name'];
                                          } else {
                                          "-";
                                          }
                                          ?> </h4></td>
                                          <td colspan="1" style="text-align: left;"><h4>Page No:</h4></td>
                                          <td colspan="1" style="text-align: left;"><h4></h4></td>
                                          </tr>

                                          <tr>
                                          <td colspan="2" style="text-align: left;"><h4>Location: </h4></td>
                                          <td colspan="5" style="text-align: left;"><h4>Refrigerator 1/3 </h4></td>
                                          <td colspan="1" style="text-align: left;"><h4>Cup Board No :</h4></td>
                                          <td colspan="1" style="text-align: left;"><h4></h4></td>
                                          </tr>


                                          <tr>
                                          <th><h4>No.</h4></th>
                                          <th><h4>Date</h4></th>
                                          <th><h4>Company</h4></th>
                                          <th><h4>Pack Size</h4></th>
                                          <th><h4>Lot No.</h4></th>
                                          <th><h4>Expiry</h4></th>
                                          <th><table style="width:100%;">
                                          <tr>
                                          <th colspan="4"> <center><h4>Stock</h4></center></th>
                                          </tr>
                                          <tr>
                                          <th><h4>Opening</h4></th>
                                          <th><h4>Received</h4></th>
                                          <th><h4>Issue</h4></th>
                                          <th><h4>Closing</h4></th>
                                          </tr>
                                          </table></th>
                                          <th><h4>Name</h4></th>
                                          <th><h4>Sign</h4></th>
                                          </tr> */ ?>
                                        <tr>
                                            <th colspan="2">Date :</th>
                                            <th colspan="9"><?php
                                                echo date('d/m/Y',strtotime($start_date));
                                                ?></th>
                                            <th colspan="2">Page No.</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Location Name:</th>
                                            <th colspan="9"><?php
                                                echo $location_name;
                                                ?></th>
                                    <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">Sr. No.</td>
                                        <td rowspan="2">Date</td>
                                        <td rowspan="2">Item</td>
                                        <td rowspan="2">Company</td>
                                        <td rowspan="2">Pack Size</td>
                                        <td rowspan="2">Lot No.</td>
                                        <td rowspan="2">Expiry</td>
                                        <td colspan="4"><center>Stock</center></td>
                                    <td rowspan="2">Name</td>
                                    <td rowspan="2">Sign</td>
                                    </tr>
                                    <tr>
                                        <td>Opening</td>
                                        <td>Received</td>
                                        <td>Issue</td>
                                        <td>Closing</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
//                                        $temp = explode('-', $start_date);
//                                        $s_date = $temp[2];
//                                        $month = $temp[1];
//                                        $year = $temp[0];
//                                        $temp2 = explode('-', $end_date);
//                                        $e_date = $temp2[2];

                                        if ($start_date != "" && $end_date != "" && $branch != "") {
                                            $cnt = 0;
                                            foreach ($new_array as $row) {
                                                $cnt++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($row['date'])) ?></td>
                                                       <td><?php echo $row['item'] ?></td>
                                                    <td><?php echo $row['compony'] ?></td>
                                                    <td><?php echo $row['pack_size'] ?></td>
                                                    <td><?php echo $row['lot'] ?></td>
                                                    <td><?php echo $row['expire_date'] ?></td>
                                                    <td><?php echo $row['opening'] ?></td>
                                                    <td><?php echo $row['received'] ?></td>
                                                    <td><a href="javascript:void(0)" title='<?php echo $row['issue']."/".$row['wastage']."(Westage)" ?>'><?php echo $row['issue']+$row['new_bottol_test'] ?></a></td>
                                                    <td><?php echo $row['closing'] ?></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>                                                
                                                <?php
                                            }
                                        }
                                        ?>
                                        <?php if ($cnt == 0) {
                                            ?>
                                            <tr>
                                                <td colspan="12"><center>No records found</center></td>
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
        maxDate: new Date()
    });
    //            .on('changeDate', function (selected) {
    //        var startDate = new Date(selected.date.valueOf());
    //        $('#end_date').datepicker('setStartDate', startDate);
    //    }).on('clearDate', function (selected) {
    //        $('#end_date').datepicker('setStartDate', null);
    //    });

    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });
    //            .on('changeDate', function (selected) {
    //        var endDate = new Date(selected.date.valueOf());
    //        $('#start_date').datepicker('setEndDate', endDate);
    //    }).on('clearDate', function (selected) {
    //        $('#start_date').datepicker('setEndDate', null);
    //    });

</script>
<script>
//    $("#branch").change(function () {
//        var url = "<?php echo base_url(); ?>inventory/stock_master/getreagent";
//        var value = $("#branch").val();
//        $.ajax({
//            type: "POST",
//            url: url,
//            data: {id: value},
//            success: function (response)
//            {
//                $('#item').html("");
//                $('#item').html(response);
//
//                $('#item').trigger("chosen:updated");
//            }
//        });
//    });
    
    
    
        function reagent() {
        var id = $('#branch').val();
        $.ajax({
            url: "<?php echo base_url(); ?>inventory/Stock_issue_report_daily/get_item",
            type: "POST",
            data: {'id': id},
            success: function (data) {
                $('#item').html(data);
                $nc('.chosen').trigger("chosen:updated");
                $('#item').trigger("chosen:updated");
            }
        });
    }
    
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    $nc = $.noConflict();
    $nc(function () {
        $nc('.chosen-select').chosen();
    });
</script>
<script>
    jQuery(document).ready(function () {
        jQuery("#item").chosen();
    });
</script>