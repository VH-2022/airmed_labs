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
        .pending_job_list_tbl td:nth-of-type(2):before {content: "PO Number";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Branch Name";}
        .pending_job_list_tbl td:nth-of-type(4):before {content: "Vendor Details";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Indent Code";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: " Price";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: " Added by";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: " Created Date";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: " Status Action";}
        .pending_job_list_tbl td:nth-of-type(10):before {content: " Action";}
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
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<?php /* <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> */ ?>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Inventory Po List
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Inventory Po List</li>
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

            <?php if (isset($error) != NULL) {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $error['0']; ?>
                </div>
            <?php } ?>

        </div>
        <div class="row">

            <?php
            $branch = $this->input->get_post("branch");
            $vender = $this->input->get_post("vendor");
            $indent_code = $this->input->get_post("indent_code");
            $start_date = $this->input->get_post("start_date");
            $end_date = $this->input->get_post("end_date");
            $status = $this->input->get_post("status");
            $ponumber = $this->input->get_post("ponumber");
            ?>

            <?php /* if (!empty($query)) { ?>
              <a style="float:right;margin-left:3px" href="<?= base_url() . "inventory/Intent_genrate/poexport?start_date=$start_date&end_date=$end_date&ponumber=$ponumber&indent_code=$indent_code&branch=$branch&vender=$vender&status=$status"; ?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
              <?php } */ ?>
        </div>

        <div class="row">
            <div class="widget">
                <?php echo form_open("vendor/Intent_genrate", array("method" => "GET")); ?>
                <div class="form-group">
                    <div class="col-sm-2" style="margin-bottom:10px;" >
                        <input type="text" id="startdate1" name="start_date" class="form-control datepicker" placeholder="Start Date" value="<?= $start_date ?>"> 
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2" style="margin-bottom:10px;" >
                        <input type="text" id="enddate1" name="end_date" class="form-control datepicker" placeholder="End Date" value="<?= $end_date ?>"> 
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-2" style="margin-bottom:10px;">
                        <input type="text" class="form-control" name="ponumber" placeholder="Enter PO Number" value="<?= $ponumber ?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <button type="submit"  class="btn btn-sm btn-primary" >Search</button>
                        <a type="button" href="<?= base_url() . "vendor/Intent_genrate" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>
                    </div>
                </div>	

                </form>
            </div><br>

            <div class="col-md-12">

                <div class="table-responsive pending_job_list_tbl">
                    <table id="example4" class="table-striped sub_record">
                        <thead>
                        <th>No</th>
                        <th>PO Number</th>
                        <th>Branch Name</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Added by</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($query as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php
                                        echo $i;
//                                        if ($login_data["type"] == 1 || $login_data["type"] == 2) {
//
//                                            if ($row["status"] == 3) {
//                                                if ($row['a_notity'] == 1) {
//                                                    echo '<span class="round round-sm blue"></span>';
//                                                }
//                                            }
//                                        } else if ($login_data["type"] == 8) {
//                                            if ($row['i_notiy'] == 1) {
//                                                echo '<span class="round round-sm blue"></span>';
//                                            }
//                                        } else {
//                                            if ($row['b_notity'] == 1) {
//                                                echo '<span class="round round-sm blue"></span>';
//                                            }
//                                        }
                                        ?></td>
                                    <td><?= $row["ponumber"]; ?></td>
                                    <td><?= ucwords($row["branch_name"]); ?></td>
                                    <td> <?php
                                        $old_cat = '';
                                        foreach ($row["po_item_list"] as $pikey):
                                            if ($old_cat != $pikey["category_name"]) {
                                                echo "<b>" . $pikey["category_name"] . "</b><br>";
                                            }
                                            echo "<small>-" . $pikey["reagent_name"] . " (" . $pikey["itemnos"] . " " . $pikey["unit"] . ")</small><br>";
                                            $old_cat = $pikey["category_name"];
                                        endforeach;
                                        ?> </td>
                                    <td>Rs.<?= $row["poprice"]; ?></td>
                                    <td><?= $row["cretdby"]; ?></td>
                                    <td><?= date("d-m-Y", strtotime($row["creteddate"])); ?></td>
                                    <td>
                                        <?php
//                                        if ($row["status"] == 1 && count($row["inward"]) == 0 && count($row["pobilldetails"]) == 0) {
//                                            echo '<span class="label label-info">Approved</span>';
//                                        }
                                        //echo "<pre>"; print_r($row["stock_master"]);
//                                        if (count($row["inward"]) > 0 && count($row["pobilldetails"]) == 0 && count($row["stock_master"]) > 0) {
                                        if (count($row["inward"]) > 0 && count($row["pobilldetails"]) == 0) {
                                            echo '<span class="label label-warning">In-warded<span>';
                                        } else if ($row["status"] == 2) {
                                            echo '<span class="label label-warning ">Draft<span>';
                                        } else if ($row["status"] == 3) {
                                            echo '<span class="label label-danger">Waiting for Approval<span>';
                                        } elseif ($row["status"] == 4) {
                                            echo '<span class="label label-danger">Canceled<span>';
                                        }
                                        
//                                        else if (count($row["inward"]) > 0 && count($row["pobilldetails"]) == 0) {
//                                            echo '<span class="label label-warning">In-warded<span>';
//                                        }
//                                        else if ($row["status"] == 1 && count($row["pobilldetails"]) == 0 || count($row["inward"]) > 0 && count($row["stock_master"]) > 0) {
                                        if ($row["status"] == 1 && count($row["inward"]) == 0 && count($row["pobilldetails"]) == 0) {
                                            echo '<span class="label label-info">Approved</span>';
                                        } else if (count($row["pobilldetails"]) > 0) {
                                            echo '<span class="label label-success">Bill Paid<span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href='<?php echo base_url() . "vendor/intent_genrate/poigeneratedetils/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>
                                        <?php
                                        //echo "<pre>"; print_r($row["vendor_bill_data"]);
                                        if ($row["status"] == 1 && count($row["inward"]) == 0 && count($row["vendor_bill_data"]) == 0) {
                                            ?>

                                            <a href='javascript:void(0)' onclick="$('#po_id').val('<?= $row["ponumber"] ?>');
                                                            $('#myModalReceive').modal('show');
                                                            DelayedCallMe(200, $('#po_id').val());" data-toggle="tooltip" class="btn btn-sm btn-primary" data-original-title="Upload Bill"><i class="fa fa-upload"></i>&nbsp;Upload Bill</a>
                                           <?php } ?>



                                        <?php if (count($row["vendor_bill_data"]) > 0) { ?>
                                            <a href="<?php echo base_url(); ?>vendor/Intent_genrate/vendor_bill_detail/<?php echo $row["id"]; ?>" target="_blank">View Vendor Bill</a>
                                        <?php } ?>


                                    </td>

                                </tr>
                                <?php
                            }
                            if ($i == 0) {
                                ?>
                                <tr>
                                    <td colspan="9">No records found</td>
                                </tr>
                                <?php
                            }
                            ?>

                        </tbody>
                        </form>
                    </table>
                </div>
                <div style="text-align:right;" class="box-tools">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php echo $links; ?>
                    </ul>
                </div>
            </div>

            <div id="myModalReceive" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Receive Items</h4>
                        </div>
                        <?php echo form_open("vendor/Intent_Inward/invert_add", array("method" => "POST", "role" => "form", "id" => 'submit_id', 'enctype' => "multipart/form-data")); ?>
                        <div class="modal-body">
                            <input type="hidden" name="intent_id" value="<?php echo $generate[0]['Intent']; ?>">
                            <div class="form-group">
                                <label for="message-text" class="form-control-label">PO Number:</label>
                                <input type="text" name="po_number" id="po_id" class="form-control" value="" onkeyup="DelayedCallMe(200, this.value);" readonly=""/>
                            </div>
                            <script>
                                var _timer = 0;
                                function DelayedCallMe(num, val) {
                                    if (_timer)
                                        window.clearTimeout(_timer);
                                    _timer = window.setTimeout(function () {
                                        get_indent_request1(val);
                                    }, 500);
                                }

                            </script>
                            <div id="items_list" style="display:none;">
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Add Branch:</label>
                                    <select class="chosen chosen-select branch_id" name="branch_fk" onchange="getMachine();">
                                        <option value="">--Select--</option>
                                        <?php
                                        $item_list_array = array();
                                        foreach ($branch_list as $val) {

                                            $item_list_array = $item_list_array . '<option value="' . $val["BranchId"] . '">' . $val["BranchName"] . '</option>';
                                            ?>

                                            <option value="<?= $val["BranchId"] ?>"><?= $val["BranchName"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                    <span id="branch_error_id" style="color:red;"></span>
                                </div>
                                <?php /*
                                  <div class="form-group">
                                  <label for="message-text" class="form-control-label">Add Machine:</label>
                                  <select class="chosen chosen-select machine_sub" name="machine_fk"  onchange="subChange();">
                                  <option value="">--Select--</option>
                                  </select>
                                  </div>
                                  <div class="form-group">
                                  <label for="message-text" class="form-control-label">Add Reagent:</label>
                                  <select class="chosen chosen-select" name="category_fk" id="selected_item" onchange="select_item('Reagent', this);">
                                  <option value="">--Select--</option>

                                  </select>
                                  <input type="hidden" id="item_list" value="<?= htmlspecialchars($item_list_array, ENT_QUOTES); ?>"/>
                                  </div>
                                  <div class="form-group">
                                  <label for="message-text" class="form-control-label">Add Consumables:</label>
                                  <select class="chosen chosen-select" name="category_fk" id="reagent_sub_id" onchange="select_item('Consumables', this);">
                                  <option value="">--Select--</option>
                                  <?php
                                  foreach ($lab_consum as $mkey) {
                                  $lab_consum = $lab_consum . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                  ?>
                                  <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                                  <?php } ?>
                                  </select>
                                  <input type="hidden" id="item_list" value="<?= htmlspecialchars($lab_consum, ENT_QUOTES); ?>"/>
                                  </div>
                                  <div class="form-group">
                                  <label for="message-text" class="form-control-label">Add Stationary:</label>
                                  <select class="chosen chosen-select" name="category_fk" id="yahoo_id" onchange="select_item('Stationary', this);">
                                  <option value="">--Select--</option>
                                  <?php
                                  foreach ($stationary_list as $mkey) {
                                  $stationary_list = $stationary_list . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                  ?>
                                  <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                                  <?php } ?>
                                  </select>
                                  <input type="hidden" id="item_list" value="<?= htmlspecialchars($stationary_list, ENT_QUOTES); ?>"/>
                                  </div>
                                 */ ?>                 

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                            <th>Batch No</th>
                                            <th>Expiry Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr><th>Intent Code</th><td><input type="text" name="intent_code"></td></tr>
                                    </tfoot>
                                    <tbody id="selected_items">
                                    <span id="error_id" style="color:red;"></span>

                                    </tbody>
                                </table>
                            </div>
                            <!-- vishal COde Start-->
                            <div id="upload_id" style="display:none;">
                                <h2>Upload Bill Details</h2>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Invoice Number:</label>
                                    <input type="text" name="invoice_number" class="form-control" id="invoice_id">
                                    <span id="invoice_error_id" style="color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Invoice Date:</label>
                                    <input type="text" name="invoice_date" class="form-control indent_datepicker" id="invoice_date_id" placeholder="DD/MM/YYYY" maxlength="10" tabindex="3">

                                    <span id="invoice_date_error" style="color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Bill Amount:</label>
                                    <input type="text" name="bill_amount" class="form-control" id="bill_amount_id">
                                    <span id="invoice_bill_error" style="color:red"></span>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Bill Copy:</label>
                                    <input type="file" name="test_images[]" multiple id="image_id">
                                    <span id="bill_file_error" style="color:red"></span>
                                </div>
                            </div>
                        </div>
                        <script  type="text/javascript">
                            var j = jQuery.noConflict();
                            var end_date = new Date();
                            j('.indent_datepicker').datepicker({
                                dateFormat: 'dd-mm-yyyy'
                                        /*endDate: end_date*/
                            });
                        </script>
                        <div class="modal-footer" id="sub_id" style="display:none;">
                            <button type="submit" class="btn btn-primary" id="uploadform">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>



    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->

<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

<script>
                            $(function () {
                                $("#startdate1").datepicker({
                                    todayBtn: 1,
                                    autoclose: true, format: 'dd/mm/yyyy', endDate: '+0d'
                                }).on('changeDate', function (selected) {
                                    var minDate = new Date(selected.date.valueOf());
                                    $('#enddate1').datepicker('setStartDate', minDate);
                                });

                                $("#enddate1").datepicker({format: 'dd/mm/yyyy', autoclose: true, endDate: '+0d'})
                                        .on('changeDate', function (selected) {
                                            var minDate = new Date(selected.date.valueOf());
                                            $('#startdate1').datepicker('setEndDate', minDate);
                                        });
                                $('.datepicker').datepicker({
                                    format: 'dd/mm/yyyy'
                                });

                            });
                            function get_indent_request1(poid) {
                                if (poid.trim() == '') {
                                    $("#items_list").attr("style", "display:none;");
                                    return false;
                                }
                                $.ajax({
                                    url: '<?php echo base_url(); ?>vendor/Intent_genrate/get_po_details/?id=' + poid,
                                    type: 'GET',
                                    success: function (data) {
                                        if (data == 0) {
                                            $("#items_list").html("<span style='color:red;'>PO already inwarded.</span>");
                                            $("#items_list").attr("style", "display:block;");
                                            $('#upload_id').attr("style", "display:none;");
                                            $('#sub_id').attr("style", "display:none;");
                                            return false;
                                        }
                                        $("#items_list").attr("style", "display:block;");
                                        $("#items_list").html(data);


                                        $("#items_list").attr("style", "display:none;");

                                        $('.chosen').trigger("chosen:updated");
                                        $('#sub_id').attr("style", "display:block;");
                                        $('#upload_id').attr("style", "display:block");
                                    },
                                    complete: function () {
                                        $("#machine_id_edit").trigger("chosen:updated");
                                    },
                                });
                                $('.chosen').trigger("chosen:updated");
                                //$("#myModalEdit").modal("show");
                                //subBranch_sub(tid, cid);
                            }

                            $city_cnt = 0;
                            function machine_select() {
                                var selected_ids = $("#machine_id").val();
                                if (selected_ids != '') {
                                    $("#items_list").attr("style", "");
                                } else {
                                    $("#items_list").attr("style", "display:none;");
                                    //$(".chosen").val('').trigger("liszt:updated");
                                }
                                $("#selected_items").html("");
                                $("#selected_item").html("");
                                var option_data = $("#item_list").val();
                                $("#selected_item").html(option_data);
                                $nc('.chosen').trigger("chosen:updated");

                            }
                            function select_item(val, id) {
                                var input_id = $('#input_id').html();
                                console.log(input_id)
                                var skillsSelect = id;
                                var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
                                var prc = selectedText;
                                var pm = skillsSelect.value;
                                var explode = pm.split('-');
                                $("#selected_items").append('<tr id="tr_' + $city_cnt + '" class="validation"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td>' + val + '</td><td><input type="text" name="quantity[]" required="" class="form-control"/></td><td><input type="text" name="batch_no[]" required="" class="form-control"/></td><td>' + input_id + '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\',\'' + val + '\')"><i class="fa fa-trash"></i></a></td></tr>');
                                //$("#test option[value='1']").remove();
                                var old_dv_txt = $("#hidden_test").html();
                                $city_cnt = $city_cnt + 1;
                                // $("#selected_item option[value='" + skillsSelect.value + "']").remove();
                                //  $("#consumer_id option[value='" + skillsSelect.value + "']").remove();

                                // $("#statinary_id option[value='" + skillsSelect.value + "']").remove();

                                if (val == "Reagent") {
                                    $("#selected_item option[value='" + skillsSelect.value + "']").remove();
                                }
                                if (val == "Consumables") {
                                    $("#reagent_sub_id option[value='" + skillsSelect.value + "']").remove();
                                }

                                if (val == "Stationary") {
                                    $("#yahoo_id option[value='" + skillsSelect.value + "']").remove();
                                }
                                $nc('.chosen').trigger("chosen:updated");
                                //$nc('.chosen').trigger("chosen:updated");
                            }

                            // function delete_city_price(id, name, value) {
                            //     var tst = confirm('Are you sure?');
                            //     if (tst == true) {
                            //         /*Total price calculate start*/
                            //         $('#selected_item').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
                            //         $("#tr_" + id).remove();
                            //     }
                            // }

</script>
<script>//Vishal COde Start
    $(document).ready(function () {

        $("#submit_id").on('submit', (function (e) {
            
            //08 June 2018 bhavik
            //e.preventDefault();
            //08 June 2018 bhavik

            var id = $('.branch_id').val();
            //$('#branch_error_id').html("");
            var frm_data = $('#submit_id');
            var path = frm_data.attr("action");
            var count = $('#selected_items tr').length;
            $('#error_id').html(" ");
            var invoice = $('#invoice_id').val();
            var invoice_date_id = $('#invoice_date_id').val();

            var bill_amount_id = $('#bill_amount_id').val();
            var image_id = $("#image_id").val();

            var cnt = 0;
//        if (id == '') {
//            $('#branch_error_id').html("Please Select Branch");
//            cnt = cnt + 1;
//        }
            if (count == 0) {
                $('#error_id').html("Please choose Item");
                cnt = cnt + 1;
            }

            if (invoice == '') {
                $('#invoice_error_id').html("Please Enter Invoice Number");
                cnt = cnt + 1;
            }
            if (invoice_date_id == '') {
                $('#invoice_date_error').html("Please Enter Invoice Date");
                cnt = cnt + 1;
            }
            if (bill_amount_id == '') {
                $('#invoice_bill_error').html("Please Enter Bill Amount");
                cnt = cnt + 1;
            }

            //08 June 2018 bhavik
            if (image_id == '') {
                $('#bill_file_error').html("Please upload bill");
                cnt = cnt + 1;
            }
            if (cnt > 0) {
                return false;
            } else {
                return true;
            }
//            $.ajax({
//                url: path,
//                type: "POST",
//                data: new FormData(this),
//                contentType: false,
//                cache: false,
//                processData: false,
//                success: function (data) {
//                    location.reload();
//                }, error: function () {}
//            });
            //08 June 2018 bhavik

        }));
    });

    //Vishal COde End
    //$("#uploadForm").on('submit',(function(e){ e.preventDefault(); $.ajax({ url: "upload.php", type: "POST", data: new FormData(this), contentType: false, cache: false, processData:false, success: function(data){ $("#targetLayer").html(data); }, error: function(){} }); })); });
</script>