<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input-bootstrap.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>js/inputtokan/token-input-facebook.css" type="text/css" />
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>js/inputtokan/jquery.tokeninput.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Daily Bill Register
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."dailybill_register_report/report"; ?>"><i class="fa fa-users"></i>Daily Bill Register</a></li>

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
						
   <?php  echo form_open("dailybill_register_report/report", array("method" => "GET","id"=>'reportform',"role" => "form","class"=>'form-horizontal' )); ?>
	
	<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Start date<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="start_date" placeholder="Start date" class="form-control" id="startdate"  value="<?= $this->input->get('start_date'); ?>" />
					 <span id="sdateerror" style="color: red;"></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">End date<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="end_date" placeholder="End date" class="form-control" id="enddate"  value="<?= $this->input->get('end_date') ?>" />
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
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Referred Doctor</label>
					  <div class="col-sm-4">
                       <input type="text"  name="doctor" id="assigmemm"  class="form-control"  > 
							<?php /* <input type="text"  name="assignmember" id="assigmemm"  class="form-control"  > */	?>
								</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Admin User/Operator</label>
					  <div class="col-sm-4">
                     <select class="form-control" name="credtejobuser[]">
					 <option value="" >--Select--</option>
                                   
                                    <?php foreach ($admin_list as $branch3) { ?>
									
									
                                        <option value="<?php echo $branch3->id; ?>" <?php if($credtejobuser[0]==$branch3->id){echo "selected"; } ?> ><?php echo ucwords($branch3->name); ?></option>
                                            <?php } ?>
                                </select>
								</div>
                    </div>
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="search" class="btn btn-success" value="Search" />
		<a href="<?= base_url()."dailybill_register_report/report"; ?>" class="btn btn-primary">Reset</a>
      </div>
    </div>
 <?php echo form_close();  ?>
  
                           
                        </div>
			<?php 
			
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
$branchse=$this->input->get('branch');
$doctorse=$this->input->get('doctor');
$credtejobuserse=$this->input->get('credtejobuser');	


		 if($branchse !="" || $start_date != "" || $end_date != "" || $doctorse != "" || $credtejobuserse != ""){ 
			
			?>
					
						<a style="float:right;margin-right:5px;" id="reportcsv" href="javascript:void(0)" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
			<?php }  ?>		
<h3 class="box-title">Date:<?php if($start_date != ""){ echo date("d/m/Y",strtotime($start_date)); }else{ echo "-"; } if($end_date != ""){ echo "- ".date("d/m/Y",strtotime($end_date));  }  ?></h3>						
						<?php /* if($branch != ""){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/report_exportcsv?branch=$branch&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
                        <a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/print_report?branch=$branch&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm" ><strong>Print</strong></a>
						<?php }  */?>
                      
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="border-top: 3px solid black;">
                                            <th>Srl no</th>
											<th>Bill No</th>
                                            <th>Lab RefNo</th>
                                            <th>Patient Name</th>
											<th>Investigation</th>
											<th>Branch name</th>
											<th>Bill Total</th>
											<th>Total Paid</th>
											<th>Due</th>
                                        </tr>
										<tr style="border-top: 3px solid black;"><td colspan="9"></td>
												</tr>
                                    </thead>
                                    <tbody>
									
												
                                       <?php
									   $count=$page;
									   if($query != null){
									   
									   if($perpage < $page+1){
										   
									   $joblastrecords=getjobtotalpri($query[0]->id,$start_date,$end_date,$branch,$doctor,implode(",",$credtejobuser),$branchuser1); 
									  if($joblastrecords != ""){ ?>  
									  
									  <tr>
                                                    <th colspan="6"><b>Total Patient Count for <?php if($start_date != ""){ echo date("d/m/Y",strtotime($start_date)); }else{ echo "-"; } if($end_date != ""){ echo "- ".date("d/m/Y",strtotime($end_date));  } echo ": ".$count  ?></b></th>
                                                    <th><b>Rs.<?= $totalbill=round($joblastrecords->price - $joblastrecords->discount); ?></b></th>
													<th><b>Rs.<?= $ptotalpaid=round($totalbill-round($joblastrecords->dueamount)); ?></b></th>
													<th><b>Rs.<?= $ptdue=round($joblastrecords->dueamount); ?></b></th>
													
                                                </tr>
									  
									  <?php }
									  
									   $billprice=$totalbill;
									   $totalpaid=$ptotalpaid;
									   $totaldue=$ptdue;
									   
									   
									   }else{
										   
										$billprice=0;
									   $totalpaid=0;
									   $totaldue=0; 
									   
									   }
									   }
									   
									   foreach($query as $row){ $count++; 
									    $jobdtest=getjobtest($row->id);
										$job_test_list=$jobdtest['job_test_list'];
										$packagename=$jobdtest['packagename'];
										
									  ?>
									  <tr>
									  <td><?= $count; ?></td>
									  <td><?= $row->id; ?></td>
									  <td><?= $row->branch_fk; ?></td>
									  <td><?= ucwords($row->full_name); ?></td>
									  <td><?php foreach ($job_test_list as $test) {
                    $is_panel = '';
                    if ($test['is_panel'] == 1) {
                        $is_panel = '(Panel)';
                    }
                    echo "<span id=''>" . ucwords($test['test_name']) . " <b>" . $is_panel . "</b></span>" . "<br>";
                    foreach ($test["sub_test"] as $kt_key) {
                        $kt_key["test_name"];
                        if (!in_array($kt_key["sub_test"], $test_list)) {
                            ?>
                            <i style="margin-left:20px">-</i><span id=''><?= $kt_key["test_name"] ?></span><br>
                            <?php
                            $test_list[] = $kt_key["test_fk"];
                        }
                    }
                }$test_list = array();
                foreach ($packagename as $key3) {
					
                    ?>
                    <?php echo ucfirst($key3["name"]); ?><br><?php
                    foreach ($key3["test"] as $kt_key) {
                        $kt_key["test_name"];
                        if (!in_array($kt_key["test_fk"], $test_list)) {
                            ?>
                            <i style="margin-left:20px">-</i><span id=''><?= $kt_key["test_name"] ?></span><br>
                            <?php
                            $test_list[] = $kt_key["test_fk"];
                        }
                    }
                } ?></td>
									  <td><?= $row->branch_name; ?></td>
									  <td><?= $total=round($row->price - $row->discount); ?></td>
									  <td><?= $paid=round($total-$row->payable_amount); ?></td>
									  <td><?= $due=round($row->payable_amount); ?></td>
									  
									  </tr>
									   
									  <?php 
									  $billprice +=$total;
									  $totalpaid +=$paid;
									  $totaldue +=$due;
									  }
                                            
                                        if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="9">No records found</td>
                                            </tr>
<?php }else{ ?>
	<tr style="border-top: 3px solid black;">
                                                    <td colspan="6"><b>Total Patient Count for <?php if($start_date != ""){ echo date("d/m/Y",strtotime($start_date)); }else{ echo "-"; } if($end_date != ""){ echo "- ".date("d/m/Y",strtotime($end_date));  } echo ": ".$count  ?></b></td>
                                                    <td><b>Rs.<?= round($billprice); ?></b></td>
													<td><b>Rs.<?= round($totalpaid); ?></b></td>
													<td><b>Rs.<?= round($totaldue); ?></b></td>
													
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
		e.preventDefault(); 
		
		}
        
    });
/* var date = new Date();
date.setDate(date.getDate()-1);													
 $('#startdate,#enddate').datepicker({format: 'dd-mm-yyyy',startDate: date,
    autoclose: true
});  */
 $("#startdate").datepicker({
        todayBtn:  1,
        autoclose: true,format: 'dd-mm-yyyy',endDate: '+0d'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
    }).attr("autocomplete", "off");
    
    $("#enddate").datepicker({format: 'dd-mm-yyyy',autoclose: true,endDate: '+0d'})
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
        }).attr("autocomplete", "off");
														 $("#reportcsv").click(function(){
															 location.href = '<?= base_url()."dailybill_register_report/report_exportcsv?" ?>'+$("#reportform").serialize();
															 
														 });
                                                    });
													var jq = jQuery.noConflict();
													var baseurl="<?= base_url(); ?>";
													jq("#assigmemm").tokenInput(function(){ return baseurl+'dailybill_register_report/getdoctore';},{theme: "facebook",noResultsText: "Nothing found.",hintText: "Type doctor name",preventDuplicates: true,prePopulate: [<?php foreach($getdoctor as $key){ ?>
{ id:<?= $key['id']; ?>, name: "<?= ucwords($key['full_name']); ?>" },
<?php }  ?>]});
jq("#branchid").tokenInput(function(){ return baseurl+'dailybill_register_report/getbranch';},{theme: "facebook",noResultsText: "Nothing found.",hintText: "Type branch name",preventDuplicates: true,prePopulate: [<?php foreach($getbranch as $key){ ?>
{ id:<?= $key['id']; ?>, name: "<?= ucwords($key['branch_name']); ?>" },
<?php }  ?>]});
													</script>	