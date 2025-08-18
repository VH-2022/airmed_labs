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
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: justify; vertical-align: middle; border: 1px solid #b1b1b1;}
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
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Date";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Amount";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Branch";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Category";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Description";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Last Date";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Added By";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Action";}


    }
    /* End pending_job_detail responsive table */
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Bill List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>bill_master/bill_list/"><i class="fa fa-users"></i>Bill</a></li>

        </ol>
    </section>
    <?php 
			$category = $_GET['expense_category_fk']; 
			$paystatus = $_GET['paystatus'];
	?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Bill List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>bill_master/bill_add' class="btn btn-primary btn-sm"><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;" href='<?php echo base_url(); ?>bill_master/bill_export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&branch_fk=<?php echo $branch_fk; ?>&expense_category_fk=<?php echo $expense_category_fk; ?>&paystatus=<?php echo $paystatus; ?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export To Data</strong></a>

                    </div>
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>bill_master/bill_list" method="get" >
                                <div class="col-md-3" style="width:150px;">
                                    <input type="text" placeholder="Bill Date" class="form-control datepicker-input" name="start_date" value="<?php echo $start_date; ?>"/>

                                </div>
                                <div class="col-md-3" style="width:150px;">
                                    <input type="text" placeholder="Bill Date" class="form-control datepicker-input" name="end_date" value="<?php echo $end_date; ?>"/>

                                </div>

                                <div class="col-md-3" style="width:250px;">
                                    <select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="branch_fk">

                                        <option value="">All</option>
                                        <?php foreach ($branch as $branches) { ?>
                                            <option value="<?php echo $branches['branch_fk']; ?>" <?php
                                            if ($branch_fk == $branches['branch_fk']) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo ucwords(strtolower($branches['branch_name'])); ?></option>
                                                <?php } ?>
                                    </select>                                
                                </div>

                                <div class="col-md-3" style="width:250px;">
                                    <select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="expense_category_fk">
                                        <option value="">Select Category</option>
                                        <?php foreach ($expensecate as $cate) { ?>
                                            <option value="<?php echo $cate['id']; ?>" <?php
                                            if ($category == $cate['id']) {
                                                echo "selected";
                                            }
                                            ?> ><?php echo ucwords(strtolower($cate['name'])); ?></option>
                                                <?php } ?>
                                    </select>                                
                                </div>
								
								<div class="col-md-3" style="width:250px;">
                                    <select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Status" name="paystatus">
                                        <option value="">Select Status</option>
                                        <option value="0" <?php
										if ($paystatus == '0') {
											echo "selected='selected'";
										}
										?>>Pending</option>
										<option value="1" <?php
										if ($paystatus == '1') {
											echo "selected='selected'";
										}
										?>>Completed</option>
										<option value="2" <?php
										if ($paystatus == '2') {
											echo "selected='selected'";
										}
										?>>Booked</option>
										<option value="3" <?php
										if ($paystatus == '3') {
											echo "selected='selected'";
										}
										?>>Rejected</option>
                                    </select>                                
                                </div>


                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                <a class="btn btn-primary" href="<?= base_url() . "bill_master/bill_list"; ?>">Reset</a><br><br>

                                <div id="prnt_rpt">
                                    <div class="table-responsive pending_job_list_tbl">
                                        <table id="example3" class="table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Invoice No</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Branch</th>
                                                    <th>Category</th>
                                                    <th>Description</th>
                                                    <th>Last Date</th>
                                                    <th>Status</th>
                                                    <th>Added By</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                <?php
                                                $cnt = 1;
                                                foreach ($query as $row) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $row['invoice_no']; ?></td>
                                                        <td ><?php
                                                            $old_date = $row['expense_date'];
                                                            $new_date = date('d-m-Y', strtotime($old_date));
                                                            echo $new_date;
                                                            ?></td>
                                                        <td ><?php echo $row['amount']; ?></td>
                                                        <td ><?php echo $row['branch_name']; ?></td>
                                                        <td><?php echo ucwords($row['CategoryName']); ?></td>

                                                        <td><?php echo ucwords($row['description']); ?></td>
                                                        <td><?php
                                                            if (!empty($row['last_date'])) {
                                                                echo date('d-m-Y', strtotime($row['last_date']));
                                                            }
                                                            ?></td>
                                                        <td><?php if ($row['paystatus'] == 1) { ?>
                                                                <span class="label label-success ">Completed</span>
                                                            <?php } else if ($row['paystatus'] == 0) { ?>
                                                                <span class="label label-danger ">Pending</span><?php
                                                            } else if ($row['paystatus'] == 2) {
                                                                echo '<span class="label label-info ">Booked</span>';
                                                            } else if ($row['paystatus'] == 3) {
                                                                echo '<span class="label label-warning ">Rejected</span>';
                                                            }
                                                            ?></td>
                                                        <td><?php echo ucwords($row['AdminName']); ?></td>
                                                        <td>
                                                            <?php if (($admintype == 1 || $admintype == 2) && $row['paystatus'] != 1) { ?>
                                                                <a href='<?php echo base_url(); ?>bill_master/bill_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> &nbsp
                                                            <?php } ?>
                                                            <?php if (($admintype == 1 || $admintype == 2) && $row['paystatus'] != 1 && $login_data["id"] == '10') { ?>
                                                                <a href='<?php echo base_url(); ?>bill_master/bill_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>&nbsp
                                                            <?php } ?>
                                                            <?php
                                                            if ($admintype == 1 || $admintype == 2) {
                                                                ?><a href='<?php echo base_url(); ?>bill_master/bill_payment/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Payment Details"><i class="fa fa-credit-card"></i></a>&nbsp  <?php
                                                                }
                                                                ?>
                                                                <?php if (!empty($row['upload_receipt'])) { ?>
                                                                <a href='<?php echo base_url(); ?>upload/expense_master/<?php echo $row['upload_receipt']; ?>' target="_blank" data-toggle="tooltip" data-original-title="View Bill"><i class="fa fa-image"></i></a>
                                                            <?php } ?>
                                                            <?php
                                                            if ($admintype == 1 || $admintype == 2) {
                                                                if ($row['paystatus'] == 0 || $row['paystatus'] == 2) {
                                                                    ?>
                                                                    <a href='javascript:void(0);' onclick="openreject_popup('<?= $row['id'] ?>')" data-toggle="tooltip" data-original-title="Reject Bill"><i class="fa fa-thumbs-down"></i></a>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    
                                                    <?php
                                                    $cnt++;
                                                }if (empty($query)) {
                                                    ?>
                                                    <tr>
                                                        <td colspan ="9">No records found</td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div class="modal fade" id="reject_modal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reject Bill</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="<?php echo base_url(); ?>/bill_master/reject" method="post" id="reject_form" enctype="multipart/form-data">
                    <div id="all_mobile_no1">
                        <div class="form-group">
                            <textarea name="reject_reason" id="reject_reason" placeholder="Enter reason"class="form-control"><?php echo set_value('reject_reason'); ?></textarea>
                            <span id="reasonerror" class="errorall" style="color:red;"><?php echo form_error('reject_reason'); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-primary" value="Reject">
                    </div>
                    <input type="hidden" name ="bill_id" id="bill_id" value=""/>
            </div>

            <div class="modal-footer">
<!--                                        <a href="javascript:void(0);" target="_blank" id="b_print" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>-->
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?= form_close(); ?>
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {

        var date_input = $('input[name="expense_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd-mm-yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true
        });
    });</script>
<script type="text/javascript">
    function printData()
    {
        var divToPrint = document.getElementById("prnt_rpt");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>
<script>
    function openreject_popup(id) {
        $('#reject_modal').modal('show');
        $("#bill_id").val(id);
    }

    $("#reject_form").submit(function (event) {

        var reason = $("#reject_reason").val();
        
        var error = 1;
        $(".errorall").html('');
        if (reason == "") {
            error = 0;
            $("#reasonerror").html('Please enter reason');
        }

        if (error == 1) {
            return true;
        } else {
            return false;
        }
    });
</script>