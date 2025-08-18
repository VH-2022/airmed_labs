<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         Reports
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."doctor/report?from=".date("d-m-Y")."&to=".date("d-m-Y"); ?>"><i class="fa fa-users"></i>Reports</a></li>

        </ol>
    </section>
<?php
 $from = $this->input->get('from');
 $to = $this->input->get('to');

 ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Reports</h3>
						
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                           <?php echo form_open("doctor/report?from=$from&to=$to",array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                           <div class="col-sm-3">
                                <input type="text" name="from" required placeholder="Start date" class="form-control datepicker-input"   id="startdate"  value="<?=  $from ?>" />
                            </div>
							<div class="col-sm-3">
                                 <input type="text" name="to" required placeholder="Start date" class="form-control datepicker-input" id="enddate"  value="<?=  $to ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
								<a class="btn btn-primary" href="<?php echo base_url()."doctor/report?from=".date("d-m-Y")."&to=".date("d-m-Y"); ?>">Reset</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close();  ?>

                        </div>
						 <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
											<th>Reg. No</th>
                                            <th>Order Id</th>
                                            <th>Customer Name</th>
											<th>Test/Package Name</th>
											<th>Date</th>
											<th>Price</th>
											<th>Job Status</th>
											<th>Action</th>
										</tr>
                                    </thead>
                                    <tbody>
                                       <?php
									  $i=0;
									  foreach($querydata as $row){ $i++; ?>
									  <tr>
									  <td><?= $i; ?></td>
									  <td><?= $row["id"]; ?></td>
									  <td><?= $row["order_id"]; ?></td>
									  <td><?= $row["full_name"]; ?></td>
									  <td><?= $row["test"]; ?></td>
									  <td><?= $row["DATE"]; ?></td>
									  <td><?= $row["price"]; ?></td>
									  <td><?php
                                                    if ($row['status'] == 1) {
                                                        echo "<span class='label label-danger '>Waiting For Approval</span>";
                                                    }
                                                    if ($row['status'] == 6) {
                                                        echo "<span class='label label-warning '>Approved</span>";
                                                    }
                                                    if ($row['status'] == 7) {
                                                        echo "<span class='label label-warning '>Sample Collected</span>";
                                                    }
                                                    if ($row['status'] == 8) {
                                                        echo "<span class='label label-warning '>Processing</span>";
                                                    }
                                                    if ($row['status'] == 2) {
                                                        echo "<span class='label label-success '>Completed</span>";
                                                    }
                                                    if ($row['status'] == 0) {
                                                        echo "<span class='label label-danger '>User Deleted</span>";
                                                    }
													
                                                    ?></td>
									  <td><a href='javascript:void(0);' id="job_<?= $row["id"]; ?>" class="getjobsdetils"  ><span class=""><i class="fa fa-eye"></i></span></a></td>
									  
									  </tr>
										
									  
									  <?php } if (empty($querydata)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
                                            </tr>
<?php } ?>
                                    
                                    </tbody>
                                </table>
                                 </div>
								 
                            
                        </div>
						
						<div class="modal fade" id="print_model1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Report Details</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="printerror" ></div>
                                        <div id="printcontain" class="modal-body"></div>

                                        </div>
                                    
                                </div>

                            </div>
                        </div>
                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->
<script src="<?php echo base_url(); ?>user_assets/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<script  type="text/javascript">

                                                   
                                                    $(function () {
														$(".getjobsdetils").click(function(){
															var thiid=this.id;
															var splitid=thiid.split("_");
															var jobid=splitid[1];
															if(jobid != ""){
																
																 $('#print_model1').modal('show');
        $('#printcontain').html('<div style="height:50px"><span id="searching_spinner_center" style="position: absolute;left: 50%;"><img src="<?= base_url() . "img/ajax-loader.gif" ?>" /></span></div>');
		$.ajax({url: "<?php echo base_url()."doctor/report/getjobdetils"; ?>",
            type: "GET",
            data: {job_id: jobid},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
				$('#printcontain').html(data);
            }
        });
																
																
																
															}
															
														});
                                                       
	$("#startdate").datepicker({
        todayBtn:  1,
        autoclose: true,endDate: '+0d',format: 'dd-mm-yyyy'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
    });
	
	/* $('#startdate').datepicker('update');
	 */
	 
    $("#enddate").datepicker({format: 'dd-mm-yyyy',autoclose: true,endDate: '+0d'})
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
        });	
		/* var javascript_date = new Date("<?php echo date('Y-m-d'); ?>"); */
														 
                                                    });
													</script>