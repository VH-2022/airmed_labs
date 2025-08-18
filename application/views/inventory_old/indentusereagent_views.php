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
            Use Reagent List
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User Reagent List</li>
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
        $start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
        $batchno = $this->input->get_post("batchno");
        $machine = $this->input->get_post("machine");
        $reagent = $this->input->get_post("reagent");
        ?>
        <div class="row">

            <span style="float: right;"><a href="<?= base_url() . "inventory/indent_usereagent/addusestock"; ?>" class="btn btn-primary btn-sm">Add</a></span>



        </div>





        <div class="row">


            <div class="widget">
                <?php echo form_open("inventory/indent_usereagent", array("method" => "GET")); ?>

                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >

                        <input type="text" id="startdate1" name="start_date" class="form-control datepicker" placeholder="Start Date" value="<?= $start_date ?>"> 

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >

                        <input type="text" id="enddate1" name="end_date" class="form-control datepicker" placeholder="End Date" value="<?= $end_date ?>"> 

                    </div>
                </div>



                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select name="branch" id="branch_id" class="chosen chosen-select form-control">
                            <option value="">Select Branch</option>
                            <?php foreach ($branch_list as $val) { ?>
                                <option value="<?php echo $val['BranchId']; ?>" <?php
                                if ($branch == $val['BranchId']) {
                                    echo "selected";
                                }
                                ?> ><?php echo $val['BranchName']; ?></option>
                                    <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="display:none;">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select class="chosen chosen-select" id="machine" name="machine"  >
                            <option value="">--Select Machine ---</option>

                        </select>
                    </div>

                </div>

                <div class="form-group" style="display:none;">

                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select class="chosen chosen-select" id ="reagent" name="reagent"  >
                            <option value="">--Select Reagent ---</option>

                        </select>
                    </div>

                </div>

                <div class="form-group" style="display:none;">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select class="chosen chosen-select" id ="batchno" name="batchno"  >
                            <option getstock="" value="">--Select Batch no ---</option>

                        </select>
                    </div>

                </div>


                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                        <a type="button" href="<?= base_url() . "inventory/indent_usereagent" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>

                        <?php if (!empty($query)) { ?>
                            <a  href="<?= base_url() . "inventory/indent_usereagent/poexportcsv?start_date$start_date=&end_date=$end_date&branch=$branch&batchno=$batchno&machine=$machine&reagent=$reagent"; ?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
                        <?php } ?>


                    </div>
                </div>	




                </form>
            </div><br>


            <div class="col-md-12">

                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                    <th>No</th>
                    <th>Branch Name</th>
                    <th>Machine Name</th>
                    <th>Reagent Name</th>
                    <th>Batch no</th>
                    <th>Quantity</th>
                    <?php /* <th>Stock</th> */ ?>
                    <th>Added by</th>
                    <th>Created Date</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $i = $counts;
                        foreach ($query as $row) {
                            $i++;
                            ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= ucwords($row["branch_name"]); ?></td>
                                <td><?= $row["machinname"]; ?></td>
                                <td><?= $row["reagent_name"]; ?></td>
                                <td><?= $row["batch_no"]; ?></td>
                                <td><?= $row["quantity"]; ?></td>
                                <?php /* <td><?= $row["used"]; ?></td> */ ?>
                                <td><?= $row["adminname"]; ?></td>
                                <td><?= date("d-m-Y", strtotime($row["creteddate"])); ?></td>
                                <td>
                                    <a  href='<?php echo base_url() . "inventory/indent_usereagent/indedstocksremove/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove  this data?');"><i class="fa fa-trash-o"></i></a> 
                                </td>


                            </tr>
                        <?php }if (empty($query)) {
                            ?>
                            <tr>
                                <td colspan="9">No records found</td>
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

                                            $("#branch_id").change(function () {

                                                var id = $('#branch_id').val();
                                                var machinid = "<?= $machine ?>";

                                                $("#loader_div").show();

                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>inventory/indent_usereagent/getbranchmachin",
                                                    type: "POST",
                                                    data: {branch_fk: id, machinid: machinid},
                                                    success: function (data) {

                                                        $('#machine').html(data);
                                                        $('#machine').trigger("chosen:updated");

                                                        $('#reagent').html("<option value=''>Select Reagent</option>");
                                                        $('#reagent').trigger("chosen:updated");
                                                        $('#batchno').html("<option value=''>Select Batch no</option>");
                                                        $('#batchno').trigger("chosen:updated");
                                                        $("#getstocks").html("");
                                                        $("#totalstock").val("0");
                                                        if (id != "") {
                                                            $("#errorbranch").html("");
                                                        }

                                                        $("#loader_div").hide();

                                                        $("#machine").change();
                                                    }
                                                });
                                            });

                                            $("#branch_id").change();
                                            $(document).on('change', '#machine', function () {
                                                $("#loader_div").show();
                                                var machinid = this.value;
                                                var reagent = "<?= $reagent ?>";

                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>inventory/indent_usereagent/getmachinagent",
                                                    type: "POST",
                                                    data: {machin: machinid, reagent: reagent},
                                                    success: function (data) {
                                                        $('#reagent').html(data);
                                                        $('#reagent').trigger("chosen:updated");

                                                        $('#batchno').html("<option value=''>Select Batch no</option>");
                                                        $('#batchno').trigger("chosen:updated");
                                                        $("#getstocks").html("");
                                                        $("#totalstock").val("0");
                                                        if (this.value != "") {
                                                            $("#errormachine").html("");
                                                        }

                                                        $("#loader_div").hide();
                                                        $("#reagent").change();

                                                    }
                                                });

                                            });

                                            $(document).on('change', '#reagent', function () {

                                                $("#loader_div").show();
                                                var machinid = this.value;
                                                var batchno = "<?= $batchno; ?>";
                                                $.ajax({
                                                    url: "<?php echo base_url(); ?>inventory/indent_usereagent/getreagentbanch",
                                                    type: "POST",
                                                    data: {reagent: machinid, batchno: batchno},
                                                    success: function (data) {
                                                        $('#batchno').html(data);
                                                        $('#batchno').trigger("chosen:updated");
                                                        $("#getstocks").html("");
                                                        $("#totalstock").val("0");
                                                        if (this.value != "") {
                                                            $("#errorreagent").html("");
                                                        }

                                                        $("#loader_div").hide();

                                                    }
                                                });
                                            });

                                        });
</script>