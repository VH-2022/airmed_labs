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
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">Ã—</button>
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

            <?php if (!empty($query)) { ?>
                <a style="float:right;margin-left:3px" href="<?= base_url() . "inventory/Intent_genrate/poexport?start_date=$start_date&end_date=$end_date&ponumber=$ponumber&indent_code=$indent_code&branch=$branch&vender=$vender&status=$status"; ?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
            <?php } ?>

        </div>




        <div class="row">

            <div class="widget">
                <?php echo form_open("inventory/Intent_genrate", array("method" => "GET")); ?>

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
                    <div class="col-sm-2" style="margin-bottom:10px;" >

                        <input type="text" class="form-control" name="ponumber" placeholder="Enter PO Number" value="<?= $ponumber ?>"/>

                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-2" style="margin-bottom:10px;" >

                        <input type="text" class="form-control" name="indent_code" placeholder="Enter Indent Code" value="<?= $indent_code ?>"/>

                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select name="branch" class="form-control">
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

                <?php /* <div class="form-group">
                  <div class="col-sm-2" style="margin-bottom:10px;" >

                  <select name="vender" class="form-control">
                  <option value="">Select Vendor</option>
                  <?php foreach ($vendor_list as $vendor) { ?>
                  <option value="<?php echo $vendor['id']; ?>" <?php if($vender==$vendor['id']){ echo "selected"; } ?> ><?php echo $vendor['vendor_name']; ?></option>
                  <?php } ?>
                  </select>

                  </div>
                  </div> */ ?>





                <div class="form-group">
                    <div class="col-sm-2" style="margin-bottom:10px;" >

                        <select name="status" class="form-control"  >
                            <option value="">--Select Status--</option>
                            <option value="1" <?php
                            if ($status == '1') {
                                echo "selected='selected'";
                            }
                            ?>>Approved</option>
                            <option value="2" <?php
                            if ($status == '2') {
                                echo "selected='selected'";
                            }
                            ?>>Draft</option>
                            <option value="3" <?php
                            if ($status == '3') {
                                echo "selected='selected'";
                            }
                            ?>>Waiting for Approval</option>
                            <option value="4" <?php
                            if ($status == '4') {
                                echo "selected='selected'";
                            }
                            ?>>Canceled</option>

                            <option value="5" <?php
                            if ($status == '5') {
                                echo "selected='selected'";
                            }
                            ?>>In-warded</option>

                            <option value="6" <?php
                            if ($status == '6') {
                                echo "selected='selected'";
                            }
                            ?>>Bill Paid</option>


                        </select>


                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <select name="vendor" class="form-control">
                            <option value="">Select Vendor</option>
                            <?php foreach ($vendor_list as $val) { ?>
                                <option value="<?php echo $val['id']; ?>" <?php
                                if ($vendor == $val['id']) {
                                    echo "selected";
                                }
                                ?> ><?php echo $val['vendor_name']; ?></option>
                                    <?php } ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-3" style="margin-bottom:10px;" >
                        <button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                        <a type="button" href="<?= base_url() . "inventory/Intent_genrate" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>


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
                        <th>Vendor Details</th>
                        <th>Indent Code</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Added by</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $i = $counts;
                            foreach ($query as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php
                                        echo $i;
                                        if ($login_data["type"] == 1 || $login_data["type"] == 2) {

                                            if ($row["status"] == 3) {
                                                if ($row['a_notity'] == 1) {
                                                    echo '<span class="round round-sm blue"></span>';
                                                }
                                            }
                                        } else if ($login_data["type"] == 8) {
                                            if ($row['i_notiy'] == 1) {
                                                echo '<span class="round round-sm blue"></span>';
                                            }
                                        } else {
                                            if ($row['b_notity'] == 1) {
                                                echo '<span class="round round-sm blue"></span>';
                                            }
                                        }
                                        ?></td>
                                    <td><a href='<?php echo base_url() . "inventory/intent_genrate/poigeneratedetils/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="View"><?= $row["ponumber"]; ?></a></td> 
                                    <td><?= ucwords($row["branch_name"]); ?></td>
                                    <td><b>Name:</b>&nbsp;&nbsp;<?= $row["vendor_name"]; ?><br>
                                        <b>Email:</b>&nbsp;&nbsp;<?= $row["vemail"]; ?><br>
                                        <b>Mobile:</b>&nbsp;&nbsp;<?= $row["vmobile"]; ?>
                                    </td>
                                    <td><?= $row["indentcode"]; ?></td>
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
                                    <td><?php
                                        if ($row["status"] == 1 && count($row["inward"]) == 0 && count($row["pobilldetails"]) == 0) {
                                            echo '<span class="label label-info">Approved</span>';
                                        } else if ($row["status"] == 2) {
                                            echo '<span class="label label-warning ">Draft<span>';
                                        } else if ($row["status"] == 3) {
                                            echo '<span class="label label-danger">Waiting for Approval<span>';
                                        } elseif ($row["status"] == 4) {
                                            echo '<span class="label label-danger">Canceled<span>';
                                        } else if (count($row["inward"]) > 0 && count($row["pobilldetails"]) == 0) {
                                            echo '<span class="label label-warning">In-warded<span>';
                                        } else if (count($row["pobilldetails"]) > 0) {
                                            echo '<span class="label label-success">Bill Paid<span>';
                                        }
                                        ?></td>
                                    <td>
                                        <?php if (in_array($row["status"], array("1", "2", "3")) && in_array($login_data["type"], array("1", "2", "8")) && count($row["inward"]) == 0 && count($row["pobilldetails"]) == 0) { ?>
                                            <a href='<?php echo base_url() . "inventory/intent_genrate/poigenerateedit/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                        <?php } if (in_array($row["status"], array("1"))) { ?>
                                            <a href='<?php echo base_url() . "inventory/intent_genrate/invoice_pdf/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="Download PO"><span class=""><i class="fa fa-download"></i></span></a>
                                        <?php } ?>
                                        <a href='<?php echo base_url() . "inventory/intent_genrate/poigeneratedetils/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>
                                        <?php if ($row["status"] == 1 && count($row["inward"]) == 0 && count($row["pobilldetails"]) == 0) { ?>

                                            <a href='javascript:void(0)' onclick="$('#po_id').val('<?= $row["ponumber"] ?>');
                                                    $('#myModalReceive').modal('show');
                                                    DelayedCallMe(200, $('#po_id').val());" data-toggle="tooltip" class="btn btn-sm btn-primary" data-original-title="Receive Item"><i class="fa fa-truck"></i>&nbsp;Receive Item</a>

                                            <?php if ($login_data["type"] == "1" || $login_data["type"] == "2") { ?>
                                                <a  href='<?php echo base_url() . "inventory/intent_genrate/cancelpo_approve/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="Cancel" onclick="return confirm('Are you sure you want to Cancel this data?');"><i class="fa fa-trash-o"></i></a>
                                            <?php } ?>

                                        <?php } if ($row["status"] == 3 && in_array($login_data["type"], array(1, 2))) { ?>

                                            <a  href='<?php echo base_url() . "inventory/intent_genrate/cancelpo/" . $row['id']; ?>' data-toggle="tooltip" data-original-title="Cancel" onclick="return confirm('Are you sure you want to Cancel this data?');"><i class="fa fa-trash-o"></i></a> 

                                        <?php } if ($row["status"] == 2 && in_array($login_data["type"], array(8))) { ?>

                                            <a  onclick=" return confirm('Are you sure ?');" href="<?= base_url() . "inventory/intent_genrate/posaved/" . $row['id']; ?>" type="submit" class="btn btn-primary btn-sm">Generate PO and Submit for approval</a>


                                            <?php if (count($row["qoutaion"]) == 0) { ?>
                                                <a  onclick="send_quot('<?php echo $row['id'] ?>', '<?php echo $row['id'] ?>')" href="javascript:void(0)" type="submit" class="btn btn-primary btn-sm" style="margin-top:2px">Send Quotation</a>
                                                <?php
                                            }
                                            if ($row["qoutaion"][0]['vendor_approve'] == '1') {
                                                ?>
                                                <a href="<?php echo base_url() . 'inventory/intent_genrate/qoutaion_detilsviews?id=' . $row["id"] ?>" class="btn btn-primary btn-sm" style="margin-top:2px">View Vendor Quotation</a>
                                            <?php }
                                            ?>



                                        <?php } ?>

                                        <?php if (count($row["vendor_bill_data"]) > 0) { ?>

                                            <a href="<?php echo base_url(); ?>inventory/Intent_genrate/vendor_bill_detail/<?php echo $row["id"]; ?>" target="_blank">View Vendor Bill</a>

                                        <?php } ?>
                                        <?php if (count($row["inward"]) > 0 && count($row["pobilldetails"]) == 0) {
                                            ?>
                                            <a href="javascript:void(0)" onclick="uploadmore_bill('<?php echo $row['id'] ?>')">Upload Bill</a>
                                        <?php }
                                        ?>

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
                        <?php echo form_open("inventory/Intent_Inward/invert_add", array("method" => "POST", "role" => "form", "id" => 'submit_id', 'enctype' => "multipart/form-data")); ?>
                        <div class="modal-body">
                            <input type="hidden" name="intent_id" value="<?php echo $generate[0]['Intent']; ?>">
                            <div class="form-group">
                                <label for="message-text" class="form-control-label">PO Number:</label>
                                <input type="text" name="po_number" id="po_id" class="form-control" value="" onkeyup="DelayedCallMe(200, this.value);"/>
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
                                    <label for="message-text" class="form-control-label">Received Time Temperature:</label>
                                    <input type="text" name="temperature" class="form-control" id="temperature">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Opened/Packed:</label>
                                    <input type="radio" name="pack_ornot" value="1" checked> Packed <input type="radio" name="pack_ornot" value="0"> Opened 
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Supplier name:</label>
                                    <input type="text" name="supplier" id="supplier" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Receiver name:</label>
                                    <input type="text" name="receiver" id="receiver" class="form-control">
                                </div>



                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Bill Copy:</label>
                                    <input type="file" name="test_images[]" multiple id="image_id">
                                    <span id="bill_file_error" style="color:red"></span>
                                </div>
                            </div>
                            <!-- vishal COde End-->
                        </div>
                        <script  type="text/javascript">
                            var j = jQuery.noConflict();
                            var end_date = new Date();
                            j('.indent_datepicker').datepicker({
                                dateFormat: 'dd-mm-yyyy'
                                        /*endDate: end_date*/
                            }).attr("autocomplete", "off");
                        </script>
                        <!-- vishal COde Start-->
                        <div class="modal-footer" id="sub_id" style="display:none;">
                            <button type="submit" class="btn btn-primary" id="uploadform">Add</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <!-- vishal COde End-->
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>




            <div id="send_quotation" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Send Quotation </h4>
                        </div>
                        <?php echo form_open("inventory/Intent_genrate/add_quote", array("method" => "POST", "role" => "form", "id" => 'submit_id1', 'enctype' => "multipart/form-data")); ?>
                        <input type="hidden" name="purch_or_id1" id="purch_or_id1">
                        <input type="hidden" name="vendor_id" id="vendor_id">
                        <div class="modal-body" id="items_list11">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="uploadform1" onclick="fun()">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
            <script>
                $(document).on('keydown', '.calution', function (e) {
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                            (e.keyCode == 65 && e.ctrlKey === true) ||
                            // Allow: Ctrl+C
                                    (e.keyCode == 67 && e.ctrlKey === true) ||
                                    // Allow: Ctrl+X
                                            (e.keyCode == 88 && e.ctrlKey === true) ||
                                            // Allow: home, end, left, right
                                                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                                        // let it happen, don't do anything
                                        return;
                                    }
                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                        e.preventDefault();
                                    }
                                });
                        function send_quot(id) {
                            $('#purch_or_id1').val(id);
                            $.ajax({
                                url: '<?php echo base_url(); ?>inventory/Intent_genrate/send_qoutation_list/?id=' + id,
                                type: 'GET',
                                success: function (data) {
                                    $("#items_list11").html(data);
                                }
                            });
                            $('#send_quotation').modal();
                        }

                        $(document).on('keyup', '.calution', function () {

                            tablecal(this.id);


                        });
                        function tablecal(tid) {

                            var curow = tid;
                            var explode = curow.split('_');
                            var textid = explode[0];
                            var trid = explode[1];

                            //var qty = 1;
                            var qty = parseFloat($("#qty_" + trid).val());
                            var nos = parseFloat($("#itemnos_" + trid).val());
                            var ratepertest = parseFloat($("#price_" + trid).val());
                            var itemdis = parseFloat($("#discount_" + trid).val());
                            var tax = parseFloat($("#tax_" + trid).val());


                            $("#discounterror_" + trid).html("");
                            if (itemdis >= 0 && itemdis < 99) {
                                var perdiscount = parseFloat(itemdis);
                            } else {
                                $("#discounterror_" + trid).html("Please enter valid discount");
                                var perdiscount = 0;
                            }

                            if (ratepertest != "" && ratepertest > 0) {

                                if (!tax) {
                                    tax = 0;
                                }
                                if (!itemdis) {
                                    itemdis = 0;
                                }
//                                var temp = ratepertest * nos;
//                                var temp1 = (temp - ((temp * itemdis) / 100));
//                                var temp2 = (temp1 + ((temp1 * tax) / 100));
//
//                                $("#total_price_" + trid).val(temp2.toFixed(2));

                                var temp = ratepertest * nos;
                                var disc = 0;
                                var tax1 = 0;
                                if (itemdis > 0) {
                                    disc = ((temp * itemdis) / 100);
                                }
                                if (tax > 0) {
                                    tax1 = ((temp * tax) / 100);
                                }
                                temp = temp + tax1 - disc;

                                $("#total_price_" + trid).val(temp.toFixed(2));

                            } else {
                                $("#testamount_" + trid).html('0');
                                $("#totalamount_" + trid).val('0');
                            }

                            //maintotalcchange();

                        }

                        function fun() {
                            var error = 0;
                            $(".errorall").html("");
                            var pricecnt = 0;
                            $("input[name='price[]']").each(function (index, element) {
                                pricecnt = pricecnt + 1;
                                if ($(element).val() != "") {
                                    if (isNaN($(element).val())) {
                                        $("#priceerror_" + pricecnt).html("Please enter valid price");
                                        error = 1;
                                    }
                                } else {
                                    $("#priceerror_" + pricecnt).html("Please enter price");
                                    error = 1;
                                }
                            });


                            var discountcnt = 0;
                            $("input[name='discount[]']").each(function (index, element) {
                                discountcnt = discountcnt + 1;
                                if ($(element).val() != "") {
                                    if (isNaN($(element).val())) {
                                        $("#discounterror_" + discountcnt).html("Please enter valid discount");
                                        error = 1;
                                    }
                                } else {
                                    $("#discounterror_" + discountcnt).html("Please enter discount");
                                    error = 1;
                                }
                            });


                            var taxcnt = 0;
                            $("input[name='quotation_tax[]']").each(function (index, element) {
                                taxcnt = taxcnt + 1;
                                if ($(element).val() != "") {
                                    if (isNaN($(element).val())) {
                                        $("#taxerror_" + taxcnt).html("Please enter valid tax");
                                        error = 1;
                                    }
                                } else {
                                    $("#taxerror_" + taxcnt).html("Please enter tax");
                                    error = 1;
                                }
                            });





                            if (error == 0) {
                                $("#submit_id1").submit();
                            }
                        }
            </script>




            <div id="uploadextra_bill" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Upload Bill</h4>
                        </div>
                        <?php echo form_open("inventory/Intent_Inward/uploadmore_bill", array("method" => "POST", "role" => "form", "id" => 'submit_id', 'enctype' => "multipart/form-data")); ?>
                        <input type="hidden" name="purch_or_id" id="purch_or_id">
                        <div class="modal-body">
                            <div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label">Bill Copy:</label>
                                    <input type="file" name="test_images1[]" multiple id="image_id1">

                                    <span id="bill_file_error1" style="color:red"></span>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="uploadform">Upload</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
            <script>
                        function uploadmore_bill(id) {
                            $('#uploadextra_bill').modal();
                            $('#purch_or_id').val(id);
                        }
            </script>




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
                            }).attr("autocomplete", "off");

                            $("#enddate1").datepicker({format: 'dd/mm/yyyy', autoclose: true, endDate: '+0d'})
                                    .on('changeDate', function (selected) {
                                        var minDate = new Date(selected.date.valueOf());
                                        $('#startdate1').datepicker('setEndDate', minDate);
                                    });
                            $('.datepicker').datepicker({
                                format: 'dd/mm/yyyy'
                            }).attr("autocomplete", "off");

                        });
                        function get_indent_request1(poid) {
                            /*AJAX start*/
                            if (poid.trim() == '') {
                                $("#items_list").attr("style", "display:none;");
                                return false;
                            }
                            $.ajax({
                                url: '<?php echo base_url(); ?>inventory/Intent_Inward/get_po_details/?id=' + poid,
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
                                    $("#items_list").attr("style", "");
                                    $('.chosen').trigger("chosen:updated");
                                    //Vishal COde Start
                                    $('#sub_id').attr("style", "display:block;");
                                    $('#upload_id').attr("style", "display:block");
//Vishal COde End
                                },
                                complete: function () {
                                    $("#machine_id_edit").trigger("chosen:updated");
                                },
                            });
                            /*AJAX end*/
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
                    //e.preventDefault();
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
                }));
            });

            //Vishal COde End
            //$("#uploadForm").on('submit',(function(e){ e.preventDefault(); $.ajax({ url: "upload.php", type: "POST", data: new FormData(this), contentType: false, cache: false, processData:false, success: function(data){ $("#targetLayer").html(data); }, error: function(){} }); })); });
</script>