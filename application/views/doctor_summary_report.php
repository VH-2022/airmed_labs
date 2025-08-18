<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input-bootstrap.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input-facebook.css" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>js/inputtokan/jquery.tokeninput.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Referring Dr.Summary Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."doctor_summary_report/report"; ?>"><i class="fa fa-users"></i>Doctor Summary Report</a></li>

        </ol>
    </section>
<?php
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branch=$this->input->get('branch');
$doctor=$this->input->get('doctor');
$credtejobuser=$this->input->get('credtejobuser');
 ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    
						
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						
   <?php  echo form_open("doctor_summary_report/report", array("method" => "GET","id"=>'reportform', "role" => "form","class"=>'form-horizontal' )); ?>
	
	<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Start date<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker-input" id="startdate"  value="<?= $this->input->get('start_date'); ?>" />
					 <span id="sdateerror" style="color: red;"></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">End date<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="enddate"  value="<?= $this->input->get('end_date') ?>" />
					 <span id="edateerror" style="color: red;"></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Select Branch</label>
					  <div class="col-sm-4">
                     <input type="text"  name="branch"  id="branchid"  class="form-control"  >	
							</div>	
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Referred Doctor<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                      <input type="text"  name="doctor" id="assigmemm"  class="form-control"  > 
							  <span id="brancherror" style="color: red;"></span>
								</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Admin User/Operator</label>
					  <div class="col-sm-4">
                     <select class="form-control"  name="credtejobuser[]">
					 <option value="" >--Select--</option>
                                   
                                    <?php foreach ($admin_list as $branch3) { ?>
                                        <option value="<?php echo $branch3->id; ?>"  <?php if($credtejobuser[0]==$branch3->id){echo "selected"; } ?> ><?php echo ucwords($branch3->name); ?></option>
                                            <?php } ?>
                                </select>
								</div>
                    </div>
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="search" class="btn btn-success" value="Search" />
		<a href="<?= base_url()."doctor_summary_report/report"; ?>" class="btn btn-primary">Reset</a>
      </div>
    </div>
 <?php echo form_close();  ?>
  
                           
                        </div>
<h3 class="box-title">Date:<?php if($start_date != ""){ echo date("d/m/Y",strtotime($start_date)); }else{ echo "-"; } if($end_date != ""){ echo "- ".date("d/m/Y",strtotime($end_date));  }  ?></h3>	
<?php if($doctor != ""){ ?>
						<a style="float:right;margin-right:5px;" id="reportcsv" href="javascript:void(0)" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
                        
						<?php } ?>					
						<?php /* if($branch != ""){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/report_exportcsv?branch=$branch&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
                        <a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/print_report?branch=$branch&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm" ><strong>Print</strong></a>
						<?php }  */?>
                      
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="border-top: 3px solid black;">
                                            <th>Sr no</th>
											<th>Doctor Name</th>
                                            <th>#Patient</th>
                                            <th>Bill Total</th>
											<th>Discount</th>
											<th>Paid Total</th>
											<th>Due</th>
                                        </tr>
										<tr style="border-top: 3px solid black;"><td colspan="7"></td>
												</tr>
                                    </thead>
                                    <tbody>
                                       <?php
									   $totalpatient=0;
									   $totalbill=0;
									   $tdiscount=0;
									   $totalpaid=0;
									   $tdueamount=0;
									   $count=$page;
									   foreach($query as $row){ $count++; 
									   $jobdetils1=getjobdoctor($row->id,$branchuser);
									   $jobdetils=$jobdetils1["totalpaymnts"];
									   /* $paiamount=$jobdetils1["paidtotal"];
									   $mountwallet=$jobdetils1["wallet"];
									    $creditor=$jobdetils1["creditor"]; */
									   
									/* if($paiamount != ""){ $paidamount=round($paiamount->paidamount);  }else{ $paidamount="0";}
									if($mountwallet != ""){ $walletamount=round($mountwallet->debit); }else{$walletamount="0"; }
									if($creditor != ""){ $creamount=round($creditor->debit);  }else{ $creamount="0"; } */
									   
									  
									  ?>
									  <tr>
									  <td><?= $count; ?></td>
									  <td><?= ucwords($row->full_name); ?></td>
									  <td><?php if($jobdetils->tjobs != ""){ echo $patientcount=count(explode(",",$jobdetils->tjobs)); }else{ echo $patientcount="0"; } ?></td>
									  <td><?php echo $totalbillamount=round($jobdetils->bitotal); ?></td>
									  <td><?php if($jobdetils->discount != ""){ echo$disampunt=round($jobdetils->discount); }else{ echo "0"; } ?></td>
									  <td><?php echo $paidtptal=(round($jobdetils->bitotal)-round($jobdetils->discount)-round($jobdetils->dueamount)); ?></td>
									  <td><?= $dueampunt=round($jobdetils->dueamount); ?></td>
									  </tr>
									  <?php 
									  
									  $totalpatient +=$patientcount;
									  $totalbill +=$totalbillamount;
									  $tdiscount +=$disampunt;
									  $totalpaid +=$paidtptal;
									  $tdueamount +=$dueampunt;
									  
									  }
                                            
                                        if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="7">No records found</td>
                                            </tr>
<?php }else{ ?>
	
	<tr style="border-top: 3px solid black;"><td colspan="1"></td>
                                                    <td><b>Total Rs.: </b></td>
                                                    <td><b><?= round($totalpatient); ?></b></td>
													<td><b><?= round($totalbill); ?></b></td>
													<td><b><?= round($tdiscount); ?></b></td>
													<td><b><?= round($totalpaid); ?></b></td>
													<td><b><?= round($tdueamount); ?></b></td>
													
                                                </tr>
												
												<tr style="border-top: 3px solid black;"><td colspan="9"></td>
												</tr>
	
<?php } ?>
                                    
                                    </tbody>
                                </table>
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
        </div><!-- /.row -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->
<script  type="text/javascript">

                                                    $(function () {
														/* $('#reportform').on('submit', function(e){
        
        var len = $('#assigmemm').val();
		if(len == ""){ $("#brancherror").html("The Referring Doctor field is required."); e.preventDefault(); }
        
    }); */
	
	$('#reportform').on('submit', function(e){
        var len = $('#assigmemm').val();
		var lstartdate = $('#startdate').val();
		var lenddate = $('#enddate').val();
		$("#brancherror").html("");
		$("#sdateerror").html("");
		$("#edateerror").html("");
		
		if(len == "" || lstartdate == "" || lenddate == ""){
			
		if(lstartdate == ""){ $("#sdateerror").html("The Start date field is required."); }
		if(lenddate == ""){ $("#edateerror").html("The End date field is required."); }
		if(len == ""){ $("#brancherror").html("The Referring Doctor field is required."); } 
		e.preventDefault(); 
		}
        
    });
                                                       
														 /* $('.multiselect-ui').multiselect({
                                                        includeSelectAllOption: true,
                                                        nonSelectedText: 'Select'
                                                    }); */
													
												 $("#startdate").datepicker({
        todayBtn:  1,
        autoclose: true,format: 'dd-mm-yyyy',endDate: '+0d'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
    });
    
    $("#enddate").datepicker({format: 'dd-mm-yyyy',autoclose: true,endDate: '+0d'})
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
        });	
														/*  $( "#startdate" ).datepicker({ format: 'dd-mm-yyyy' });
														 $( "#enddate" ).datepicker({ format: 'dd-mm-yyyy' }); */
														 $("#reportcsv").click(function(){
															 location.href = '<?= base_url()."doctor_summary_report/report_exportcsv?" ?>'+$("#reportform").serialize();
															 
														 });
                                                    });
													var jq = jQuery.noConflict();
													var baseurl="<?= base_url(); ?>";
													jq("#assigmemm").tokenInput(function(){ return baseurl+'dailybill_register_report/getdoctore';},{theme: "facebook",noResultsText: "Nothing found.",preventDuplicates: true,prePopulate: [<?php foreach($getdoctor as $key){ ?>
{ id:<?= $key['id']; ?>, name: "<?= ucwords($key['full_name']); ?>" },
<?php }  ?>]});
jq("#branchid").tokenInput(function(){ return baseurl+'dailybill_register_report/getbranch';},{theme: "facebook",noResultsText: "Nothing found.",preventDuplicates: true,prePopulate: [<?php foreach($getbranch as $key){ ?>
{ id:<?= $key['id']; ?>, name: "<?= ucwords($key['branch_name']); ?>" },
<?php }  ?>]});
													</script>