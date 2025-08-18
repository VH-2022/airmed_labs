<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input-bootstrap.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input-facebook.css" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>js/inputtokan/jquery.tokeninput.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Discount Register
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."discount_registerreport/report"; ?>"><i class="fa fa-users"></i>Discount Register</a></li>

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
						
   <?php  echo form_open("discount_registerreport/report", array("method" => "GET", "role" => "form","class"=>'form-horizontal',"id"=>'reportform' )); ?>
	
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
                     <?php /* <select class="form-control" multiple  name="branch[]">
					   <option value="" >--Select--</option>
                                   
                                    <?php foreach ($branch_list as $branch1) { ?>
                                        <option value="<?php echo $branch1->id; ?>" <?php if (in_array($branch1->id, $branch))
										{ echo "selected";  } ?> ><?php echo ucwords($branch1->branch_name); ?></option>
                                            <?php } ?>
											
                                </select> */ ?>
							<input type="text"  name="branch" id="branchid"  class="form-control"  >	
								
							</div>	
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Referred Doctor</label>
					  <div class="col-sm-4">
                     <?php /* <select class="form-control" name="doctor[]">
					   <option value="" >--Select--</option>
                                	<?php foreach ($doctor_list as $branch2){ ?>
                                        <option value="<?php echo $branch2->id; ?>" <?php if (in_array($branch2->id, $doctor))
										{ echo "selected";  } ?> ><?php echo ucwords($branch2->full_name); ?></option>
                                            <?php } ?>
                                </select> */ ?>
							 <input type="text"  name="doctor" id="assigmemm"  class="form-control"  > 
								</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Admin User/Operator</label>
					  <div class="col-sm-4">
                     <select class="form-control" name="credtejobuser[]">
					 <option value="" >--Select--</option>
                                   
                                    <?php foreach ($admin_list as $branch3) { ?>
									
									
                                        <option value="<?php echo $branch3->id; ?>" <?php if($credtejobuser[0]==$branch3->id){ echo "selected"; } ?> ><?php echo ucwords($branch3->name); ?></option>
                                            <?php } ?>
                                </select>
								</div>
                    </div>
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="search" class="btn btn-success" value="Search" />
		 <a href="<?= base_url()."discount_registerreport/report"; ?>" class="btn btn-primary">Reset</a>
      </div>
	  
    </div>
 <?php echo form_close();  ?>
  
                           
                        </div>
			<?php 
			
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branchse=$this->input->get('branch')[0];
$doctorse=$this->input->get('doctor')[0];
$credtejobuserse=$this->input->get('credtejobuser')[0];	


			/* if($branchse != ""){ if($branchse !="" || $start_date != "" || $end_date != "" || $doctorse != "" || $credtejobuserse != ""){ 
			
			?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."accountdailybillregister_report/report_exportcsv?branch[]=$branchse&start_date=$start_date&end_date=$end_date&doctor[]=$doctorse&credtejobuser[]=$credtejobuserse"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
			<?php } } */ ?>		
<h3 class="box-title">Date:<?php if($start_date != ""){ echo date("d/m/Y",strtotime($start_date)); }else{ echo "-"; } if($end_date != ""){ echo "- ".date("d/m/Y",strtotime($end_date));  }  ?></h3>						
						<?php if($query != null){ ?>
						<a style="float:right;margin-right:5px;" id="reportcsv" href="javascript:void(0)" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
                        
						<?php } ?>
                      
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="border-top: 3px solid black;">
                                            <th style="width:6%;">Srl no</th>
											<th>Date</th>
											<th>Bill No</th>
                                            <th>Lab RefNo</th>
											<th>Patient Name</th>
                                            <th>Total</th>
											<th>Disc</th>
											<th>Disc%</th>
											<th>DiscBy</th>
											<th>Operator Name</th>
											<th>Disc.Note</th>
                                        </tr>
										<tr style="border-top: 3px solid black;"><td colspan="11"></td>
												</tr>
                                    </thead>
                                    <tbody>
									   <?php
									   
									   $count=0;
									   $totalprice =0;
									  $totaldisc =0;
									  $totaldiscpar =0;
									   
									   foreach($query as $row){ $count++; 
									  $discby=getjobdiscby($row->id);
									     ?>
									  <tr>
									  
									  <td ><?= $count; ?></td>
									  <td><?= date("d-m-Y",strtotime($row->date)); ?></td>
									  <td><?= $row->id; ?></td>
									  <td><?= $row->branch_fk; ?></td>
									  <td><?= ucwords($row->full_name); ?></td>
									  <td><?= $total=round($row->price); ?></td>
									  <td><?= $totaldiprice=round($row->discountprice); ?></td>
									  <td><?= $discount=round($row->discount); ?></td>
									  <td><?= ucwords($discby["disby"]); ?></td>
									  <td><?= ucwords($row->operater); ?></td>
									  <td style="width:6%;"><?= ucwords($row->discount_note); ?></td>
									  
									  </tr>
									   
									  <?php 
									  $totalprice +=$total;
									  $totaldisc +=$totaldiprice;
									  $totaldiscpar +=$discount; 
									  }
                                            
                                        if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="11">No records found</td>
                                            </tr>
<?php }else{ ?>
	
										<tr style="border-top: 3px solid black;">
                                                    <td colspan="4"></td>
													<td><b>Total:</b></td>
                                                    <td><b><?= round($totalprice); ?></b></td>
													<td><b><?= round($totaldisc); ?></b></td>
													<td colspan="4"></td>
													
                                                </tr>
												<tr>
                                                    <td colspan="4"></td>
													<td><b>Average:</b></td>
                                                    <td><b><?= round(round($totalprice)/$count); ?></b></td>
													<td><b><?= round(round($totaldisc)/$count); ?></b></td>
													<td><?= round(round($totaldiscpar)/$count) ?></td>
													<td colspan="3"></td>

													
                                                </tr>
												
												<tr style="border-top: 3px solid black;"><td colspan="11"></td>
												</tr>		
	
<?php } ?>
                                    
                                    </tbody>
                                </table>
                                 </div>
								 <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
<?php /* echo $links;  */?>
                            </ul>
                        </div>
                            
                        </div>
                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->

<?php /* <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script> */ ?>

<script  type="text/javascript">

                                                    $(function () {
														$('#reportform').on('submit', function(e){
        
        var lstartdate = $('#startdate').val();
		var lenddate = $('#enddate').val();
		$("#sdateerror").html("");
		$("#edateerror").html("");
		
		if(lstartdate == "" || lenddate == ""){ 
		if(lstartdate == ""){ $("#sdateerror").html("The Start date field is required."); }
		if(lenddate == ""){ $("#edateerror").html("The End date field is required."); }
		e.preventDefault(); }
        
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
														 $("#reportcsv").click(function(){
															 location.href = '<?= base_url()."discount_registerreport/report_exportcsv?" ?>'+$("#reportform").serialize();
															 
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