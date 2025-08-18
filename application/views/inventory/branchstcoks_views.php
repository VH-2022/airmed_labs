<style>
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
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>

<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<div class="content-wrapper">

    <div class="full_bg" style="display:none;" id="loader_div">
        <div class="loader">
            <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
        </div>
    </div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Branch Stock Master
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Branch Stock Master</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <div class="widget">
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>

        </div>

        <?php
        $branch = $this->input->get_post("branch");
        $itemget = $this->input->get_post("item");
        ?>
        <div class="row">

        </div>





        <div class="row">


            <div class="widget">
                <?php echo form_open("inventory/stock_itemmaster", array("id" => "poform", "method" => "GET")); ?>

                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select  name="branch" id="branch_id" class="chosen chosen-select form-control">
                            <option value="">Select Branch</option>
                            <?php foreach ($branch_list as $val) { ?>
                                <option value="<?php echo $val['BranchId']; ?>" <?php
                                if ($branch == $val['BranchId']) {
                                    echo "selected";
                                }
                                ?> ><?php echo $val['BranchName']; ?></option>
                                    <?php } ?>
                        </select>
                        <span id="errorbranch" style="color: red;"></span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select class="chosen chosen-select" id="Item" name="item"  >
                            <option value="">--Select Item ---</option>
                            <?php foreach ($itemlist as $item) { ?>
                                <option value="<?= $item["id"]; ?>" <?php
                                if ($itemget == $item["id"]) {
                                    echo "selected";
                                }
                                ?> ><?= ucwords($item["reagent_name"]); ?></option>
                                    <?php } ?>

                        </select>

                    </div>

                </div>





                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                        <a type="button" href="<?= base_url() . "inventory/stock_itemmaster" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>

                        <?php if (!empty($query)) { ?>
                            <a  href="<?= base_url() . "inventory/stock_itemmaster/exportcsv?branch=$branch&item=$itemget"; ?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
                        <?php } ?>


                    </div>
                </div>	


                </form>
            </div><br>


            <div class="col-md-12">
                <div class="table-responsive pending_job_list_tbl">

                    <table class="table-striped">
                        <thead>
                        <th>No</th>
                        <th>Branch Name</th>
                        <th>Category</th>
                        <th>Item</th>
                        <th>Available Stock</th>

                        </thead>
                        <tbody>
                            <?php
                            $i = $counts;
                            foreach ($query as $row) {
                                $i++;

                                $itetype = $row["category_fk"];
                                $whotype = "";
                                if ($itetype == '1') {
                                    $whotype = "Stationary";
                                    $cat = "Stationary";
                                }
                                if ($itetype == '2') {
                                    $whotype = "Consumables";
                                    $cat = "Lab Consumables";
                                }
                                if ($itetype == '3') {
                                    $whotype = "Reagent";
                                    $cat = "Reagent";
                                }
                                ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= ucwords($row["branch_name"]); ?></td>
                                    <td><?= $whotype ?></td>
                                    <td><?= $row["reagent_name"]; ?></td>
                                    <td><a href="<?= base_url() . "inventory/stock_itemmaster/stockdetils?branch=$branch&item=" . $row["item_fk"] . "" ?>" target="_blank"><?= $row["totalqty"] - $row["stcok"]; ?><a/></td>


                                </tr>
                            <?php }if (empty($query)) {
                                ?>
                                <tr>
                                    <td colspan="5">No records found</td>
                                </tr>
                            <?php }
                            ?>

                        </tbody>

                    </table>
                </div>
            </div>
            <div style="text-align:right;" class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <?php echo $links; ?>
                </ul>
            </div>
        </div>

    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->

<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>
    $(function () {

        $('.chosen-select').chosen();

        $("#startdate1").datepicker({
            todayBtn: 1,
            autoclose: true, format: 'dd-mm-yyyy', endDate: '+0d'
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#enddate1').datepicker('setStartDate', minDate);
        });

        $("#enddate1").datepicker({format: 'dd-mm-yyyy', autoclose: true, endDate: '+0d'})
                .on('changeDate', function (selected) {
                    var minDate = new Date(selected.date.valueOf());
                    $('#startdate1').datepicker('setEndDate', minDate);
                });
        $("#poform").submit(function () {

            var baranch = $("#branch_id").val();
            if (baranch == "") {
                $("#errorbranch").html("Branch field is required");
                return false
            } else {
                $("#errorbranch").html("");
            }

        });
    });
</script>