<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sample
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list"><i class="fa fa-users"></i>Sample List</a></li>
        </ol>
    </section>
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
        .chosen-container {
            display: inline-block;
            font-size: 14px;
            position: relative;
            vertical-align: middle;
            width: 100%;
        }
        .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
        .full_bg .loader img{width:70px; height:70px;}
    </style>
    <div class="full_bg" style="display:none;" id="loader_div">
        <div class="loader">
            <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
        </div>
    </div>
    <!-- Main content -->
<?php 
$this->load->library("util");
    $util = new Util;
?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Sample List</h3>
                        <?php if ($login_data["type"] != 4) { ?>
                            <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>b2b/Logistic/sample_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <?php } ?>
                        <!--
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_master/test_csv?city=<?= $city ?>' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php echo form_open("b2b/Logistic/sample_list/", array("method" => "GET")); ?>
                        <div class="tableclass  table-responsive">
                            <div class="widget">
                                <?php echo form_open("b2b/Logistic/sample_list/", array("method" => "GET")); ?>
								<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                        <input type="text" class="form-control" readonly="" name="date" placeholder="from Scan Date" value="<?= $date ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                        <input type="text" class="form-control" readonly="" id="todatese" name="todate" placeholder="To Scan Date" value="<?= $todate ?>"/>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                        <input type="text" class="form-control" name="barcode" placeholder="Barcode" value="<?= $barcode ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;">
                                        <input type="text" class="form-control" name="patientsname" placeholder="Patients name" value="<?= $patientsname ?>"/>
                                    </div>
                                </div>

                                

                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                       <select class="form-control" name="name">
                                                    <option value="">Select Logistic</option>
                                                    <?php foreach ($phlebo_list as $phlebo) { ?>
                                                        <option value="<?php echo $phlebo['id']; ?>" <?php if($name==$phlebo['id']){ echo "selected"; } ?> ><?php echo ucwords($phlebo['name']); ?></option>
                                                    <?php } ?>
                                                </select>
                                    </div>
                                </div>

                                <?php if ($login_data["type"] != 4) { ?>

                                    <div class="form-group">
                                        <div class="col-sm-3" style="margin-bottom:10px;" >

                                            <select name="salesperson" class="form-control"  >
                                                <option value="" >Sales person</option>
                                                <?php foreach ($salesall as $saleds) { ?>
                                                    <option value="<?= $saleds['id']; ?>" <?php
                                                    if ($salesperson == $saleds['id']) {
                                                        echo "selected";
                                                    }
                                                    ?>  ><?= $saleds['first_name'] . " " . $saleds['last_name']; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-3" style="margin-bottom:10px;" >
                                            <select name="from"  class="form-control"  >
                                                <option value="" >Collect From</option>
                                                <?php foreach ($laball as $key) { ?>
                                                    <option value="<?= $key['id']; ?>" <?php
                                                    if ($from == $key['id']) {
                                                        echo "selected";
                                                    }
                                                    ?>  ><?= $key['name']; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-3" style="margin-bottom:10px;" >
                                            <select name="sendto" class="form-control"  >
                                                <option value="" >Send To</option>
                                                <?php foreach ($desti_lab as $labdesc) { ?>
                                                    <option value="<?= $labdesc['id']; ?>" <?php
                                                    if ($sendto == $labdesc['id']) {
                                                        echo "selected";
                                                    }
                                                    ?>  ><?= $labdesc['name']; ?></option>
                                                        <?php } ?>
                                            </select>

                                        </div>
                                    </div>

                                <?php } ?>





                                <div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >

                                        <select name="city" class="form-control"  >
                                            <option value="" >City</option>
                                            <?php foreach ($citys as $cityl) { ?>
                                                <option value="<?= $cityl['id']; ?>" <?php
                                                if ($city == $cityl['id']) {
                                                    echo "selected";
                                                }
                                                ?>  ><?= ucwords($cityl['name']); ?></option>
                                                    <?php } ?>
                                        </select>

                                    </div>
                                </div>
								
								<div class="form-group">
                                        <div class="col-md-3" style="margin-bottom:10px;">
                                            <label>Payment</label>
                                            <label class="radio-inline"><input value="due" <?php if($payment=="due"){ echo "checked"; } ?> name="payment" type="radio">Due</label>
                                            <label class="radio-inline"><input value="paid" <?php if($payment=="paid"){ echo "checked"; } ?> name="payment" type="radio">Paid</label>
                                            <label class="radio-inline"><input value="all" <?php if($payment=="all"){ echo "checked"; } ?> name="payment" type="radio">All</label> 
                                        </div>
                                        
                                    </div>

                                <?php /* <div class="form-group">
                                  <div class="col-sm-3" style="margin-bottom:10px;" >
                                  <select  name="status" class="form-control"  >
                                  <option value="" >test</option>
                                  </select>
                                  </div>
                                  </div> */ ?>

                                <div class="form-group pull-right">
                                    <div class="col-sm-12" style="margin-bottom:10px;">

                                        <button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                                        <a type="button" href="<?= base_url() . "b2b/Logistic/sample_list" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>

                                        <?php if ($login_data["type"] != 4) { ?>
                                            <a style="float:right;margin-left:3px" href="<?= base_url() . "b2b/Logistic/sample_export?name=$name&barcode=$barcode&date=$date&from=$from&patientsname=$patientsname&salesperson=$salesperson&sendto=$sendto&todate=$todate&city=$city&status=$status&payment=$payment"; ?>" id=""  class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
                                            <?php
                                        } else {
                                            if ($login_data["type"] == 4) {
                                                ?>
                                                <a style="float:right;margin-left:3px" href="<?= base_url() . "b2b/Logistic/sampledescti_export?name=$name&barcode=$barcode&date=$date&from=$from&patientsname=$patientsname&salesperson=$salesperson&sendto=$sendto&todate=$todate&city=$city&status=$status&payment=$payment"; ?>" id=""  class="btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                </form>
                            </div>
							<div class="tableclass">
							<div class="table-responsive pending_job_list_tbl">
                            <table id="example4" class="table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>Order id</th>
                                        <th>Barcode</th>
										<?php if ($login_data["type"] != 4) { ?><th>Patient Name</th><?php } ?>
                                        <th>Logistic Name</th>
                                        <th>Scan Date</th>
                                        <th>Test/Package Name</th>
                                        <?php if ($login_data["type"] != 4) { ?>
                                            <th>Collect From</th>
                                            <th>Send To</th>
                                            <th>City</th>
                                            <th>Due Amount/Total Amount</th>
                                        <?php } ?>

                                        <th>Status</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $cnt = $counts;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        $gettest = getjobtestpack($row['jobid']);
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
										<td><a href='<?php echo base_url(); ?>b2b/Logistic/details/<?php echo $row['id']; ?>' target="_blank" data-toggle="tooltip" data-original-title="details"><?= $row['order_id']; ?></a></td>
                                            <td>
                                                <a href="javascript:void(0);" id="sshowtrack_<?= $row['id']; ?>" class="sshowtrack" data-toggle="collapse" data-target="#demo_<?= $row['id']; ?>"><?php echo ucwords($row['barcode']); ?></a>

                                                <div id="demo_<?= $row['id']; ?>" class="collapse">
                                                    <?php
                                                    /* $cnt1 = 0;
                                                      foreach ($row["sample_track"] as $key) {
                                                      ?>
                                                      <?php
                                                      if ($cnt1 != 0) {
                                                      echo "<br><i class='fa fa-arrow-down'></i><br>";
                                                      }
                                                      ?>
                                                      <?= "<b>" . ucfirst($key["name"]) . "</b>" ?><?php echo " (<small>" . date("d-m-Y g:i A", strtotime($key["scan_date"])) . "</small>)"; ?>
                                                      <?php
                                                      $cnt1++;
                                                      } */
                                                    ?>
                                                </div>
                                                <?php
                                                /* if ($login_data["type"] != 4) {
                                                    echo "<br><a><b>" . ucwords($row['customer_name']) . "</b></a>";
                                                } */
                                                ?>
                                            </td>
											<?php if ($login_data["type"] != 4) { ?><td><?php 
											if($row['customer_name'] != ""){ 
											$age = $util->get_age($row['customer_dob']);
											echo "<span style='color:#d73925;'>". ucwords($row['customer_name'])."</span>(".$age[0]."/".$row['customer_gender'][0].")&nbsp;".$row['customer_mobile'].""."<br>"; 
											}
											echo "<small><b>Added By- </b>".ucwords($row['creteddby'])." </small>"; ?></td>
										<?php } ?>
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo ucwords($row['scan_date']); ?></td>
                                            <td><?php
                                                foreach ($gettest as $val) {
                                                    $client_name = ucfirst($val);
                                                    echo $client_name . "<br>";
                                                }
                                                ?></td>
                                            <?php if ($login_data["type"] != 4) { ?>
                                                <td><?php echo ucwords($row['c_name']); ?></td>
                                                <td><?php echo ucwords($row["desti_lab1"]); ?></td>
                                                <td><?php echo ucwords($row["tetst_city_name"]); ?></td>
                                                <td><?php
                                                    $payable_amount = 0;
                                                    /* Nishit code start */
                                                    $color_code = '#00A65A';
                                                    if ($row['payable_amount'] > 0) {
                                                        $color_code = '#D33724';
                                                    }
                                                    /* END */
                                                    if ($row['payable_amount'] == "") {
                                                        echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. 0";
                                                    } else {
                                                        $payable_amount = $row['payable_amount'];
                                                        echo "<spam style='color:white;background:" . $color_code . ";padding:2px'>Rs. " . number_format((float) $row['payable_amount'], 2, '.', '');
                                                    }
                                                    ?> /<?= " Rs." . number_format((float) $row["price"], 2, '.', '') . "</spam>"; ?></td>
                                            <?php } ?>
                                            <td><?php
                                                if ($login_data["type"] != 4) {
                                                    if ($row['jobsstatus'] == 0) {
                                                        echo '&nbsp;&nbsp;<span class="label label-warning">Enroute</span>';
                                                    } else if ($row['jobsstatus'] == 2) {

                                                        echo '&nbsp;&nbsp;<span class="label label-info">Processing</span>';
                                                    } if ($row['jobsstatus'] == 1) {
                                                        echo '&nbsp;&nbsp;<span class="label label-success">Completed</span>';
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['jobsstatus'] != 1) {
                                                        if (!empty($row['job_details'])) {
                                                            echo '&nbsp;&nbsp;<span class="label label-success">Test suggested</span>';
                                                        } else {
                                                            echo '&nbsp;&nbsp;<span class="label label-warning">Test not suggested</span>';
                                                        }

                                                        if (!empty($row['job_details'])) {

                                                            if ($login_data["type"] != 4) {

                                                                if ($row['collect_from'] == '12') {
                                                                    if ($row['thyrocare_report'] != "") {


                                                                        echo '&nbsp;&nbsp;<span style="cursor:pointer;" class="label label-warning" onclick="alert(\'Your Booking Id - ' . $row['thyrocare_job_id'] . '\')">Already Assign to Thyrocare</span>';
                                                                    }
                                                                }
                                                                if ($row['thyrocare_report'] != "") {
                                                                    if ($row['collect_from'] == '12') {
                                                                        ?>
                                                                        <a target="_blank"  href='<?php echo base_url(); ?>b2b/Logistic/fetchresult/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-eye"></i> Fetch Result</a>
                                                                        <a target="_blank"  href='<?php echo base_url(); ?>b2b/Logistic/fetchresult/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="details"><i class="fa fa-arrow-down"></i> Download Report</a>
                                                                        <?php
                                                                    }
                                                                }
                                                            } else {
                                                                /* if ($row['job_details'][0]["original"]) {
                                                                  echo '&nbsp;&nbsp;<span class="label label-success">Report uploaded</span>';
                                                                  } else {
                                                                  echo '&nbsp;&nbsp;<span class="label label-warning">Report pending</span>';
                                                                  } */
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if (!empty($row['treport'])) {
                                                        echo '&nbsp;&nbsp;<span class="label label-success">Completed</span>';
                                                    } else {
                                                        echo '&nbsp;&nbsp;<span class="label label-info">Processing</span>';
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                if ($row['jobsstatus'] != 1 && $row["desti_lab"] == "") {
                                                    if ($login_data["type"] != 4) { 
                                                        if($row["price"]>0){
                                                        ?>
                                                        <a  href='javascript:void(0);' onclick="$('#myModal').modal('show');$('#job_fk_id').val('<?php echo $row['id']; ?>')">Forward</a>    
                                                        <?php
                                                        }else{
                                                            ?><a  href='javascript:void(0);' onclick="alert('Please assign test first.');">Forward</a>    
                                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                                            <a  href='<?php echo base_url(); ?>b2b/Logistic/details/<?php echo $row['id']; ?>' target="_blank" data-toggle="tooltip" data-original-title="details"><i class="fa fa-eye"></i></a>
                                                <?php if ($login_data["type"] != 4) { ?>
                                                    <a  href='<?php echo base_url(); ?>b2b/Logistic/sample_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                                <?php } ?>
                                                <?php
                                                if ($login_data["type"] != 4) {
                                                    if ($row['collect_from'] == '12') {
                                                        if ($row['thyrocare_report'] == "") {
                                                            ?>
                                                            <a onclick="assigntotyrocare('<?php echo $row['id']; ?>')" href="javascript:void(0);" data-toggle="tooltip" data-original-title="Assign to thyrocare"><i class="fa fa-eyedropper"></i></a>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                                    <?php if ($row['treport']) { ?> <a  href='javascript:void(0)' class="showreport" id="pdfreport_<?= $row['id']; ?>_<?= $row["collect_from"] ?>"  data-toggle="tooltip" data-original-title="Download Report"><i class="fa fa-arrow-down "></i> Download Report</a><?php } ?>
                                                <a href="<?= base_url() . "b2b/logistic/pdf_invoice/" . $row['id']; ?>" target="_blank" data-toggle="tooltip" data-original-title="Download Invoice"><span class=""><i class="fa fa-download fa-6"></i></span></a>
                                            </td>

                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="13">No records found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                           </div>
						   </div>
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
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div id="myModalreport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Uploaded Reports</h4>
            </div>

            <div id="reportcontain" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(".sshowtrack").click(function () {

        var id = this.id;
        var splitid = id.split("_")
        var jid = splitid[1];
        $('#demo_' + jid).html("Please wait...");

        $.ajax({url: "<?php echo base_url() . "b2b/logistic/getjobs_track"; ?>",
            type: "POST",
            data: {pid: jid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                $('#demo_' + jid).html(data);
            }
        });

    });

    $(document).on("click", ".showreport", function () {

        var id = this.id;
        var splitid = id.split("_")
        var jid = splitid[1];
        var cid = splitid[2];
        $('#reportcontain').html('<div style="height:50px"><span id="searching_spinner_center" style="position: absolute;left: 50%;"><img src="<?= base_url() . "img/ajax-loader.gif" ?>" /></span></div>');
        $('#myModalreport').modal('show');


//$("#"+id).prop('disabled', true);
        $.ajax({url: "<?php echo base_url() . "b2b/logistic/getreportpdf"; ?>",
            type: "POST",
            data: {pid: jid, cid: cid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                $('#reportcontain').html(data);
            }
        });

    });
</script>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign Destination Lab</h4>
            </div>
            <?php echo form_open("b2b/logistic/desti_assign", array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
                <div>
                    <label>Select Lab</label>
                    <select class="form-control" name="desti_lab" required="">
                        <option value="">--Select--</option>
                        <?php foreach ($desti_lab as $k_lab) { ?>
                            <option value="<?= $k_lab["id"] ?>"><?= $k_lab["name"] ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="job_fk" id="job_fk_id" value=""/>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Forward">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(function () {
        var date_input = $('#todatese'); //our date input has the name "date"
        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        });

        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
    function assigntotyrocare(id) {

        $.ajax({
            url: '<?php echo base_url(); ?>b2b/Logistic/sendToThyrocare/' + id,
            type: 'get',
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            success: function (data) {

                var result = JSON.parse(data);
                if (result.status == "SUCCESS") {
                    alert("Sample forwared to Thyrocare. Job Id is " + result.barcode_patient_id);
                    window.location.reload();
                } else {
                    alert("Error on sample forwared to Thyrocare. Mesage  " + result.message);
                }
            },
            error: function (jqXhr) {
                alert("error");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
                //$("#send_opt_1").removeAttr("disabled");
                // alert("complete");

            },
        });
    }
</script>
