 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         Daily Lab Collection Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."daily_lab_report/report"; ?>"><i class="fa fa-users"></i>Daily Lab Report</a></li>

        </ol>
    </section>
<?php
$start_date=$this->input->get('startdate');
$end_date=$this->input->get('enddate');
$branch=$this->input->get('branch');

 ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Daily Lab Collection Report</h3>
						
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                           <?php echo form_open("daily_lab_report/report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                           <div class="col-sm-3">
                                <input type="text" name="startdate" required placeholder="Start date" class="form-control datepicker-input" id="startdate"  value="<?= $this->input->get('startdate'); ?>" />
                            </div>
							<div class="col-sm-3">
                                <input type="text" name="enddate" required placeholder="End date" class="form-control datepicker-input" id="enddate"  value="<?= $this->input->get('enddate'); ?>" />
                            </div>
							<div class="col-sm-3">
                               <select name="branch" class="form-control">
							   <option value="">Select Branch</option>
							   <?php foreach($branchall as $bra){ ?> <option value="<?= $bra->id; ?>" <?php if($branch==$bra->id){ echo "selected"; } ?> ><?= ucwords($bra->branch_name); ?></option>  <?php } ?>
							   </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
								<a class="btn btn-primary" href="<?= base_url()."daily_lab_report/report"; ?>">Reset</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close();  ?>

                        </div>
						<?php if($start_date != "" && $end_date != ""){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."daily_lab_report/report_exportcsv?startdate=".$start_date."&enddate=".$end_date."&branch=".$branch; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
						<?php } ?>
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr no</th>
											<th>Lab Name</th>
                                            <th>Total Sample</th>
                                            <th>Total Amount</th>
											<th>Amount collection</th>
										</tr>
                                    </thead>
                                    <tbody>
                                       <?php
									   
									   $gtotralsample=0;
									$gtotalsamount=0;
									$gtotalsamountcol=0;
									  
									  foreach($query as $row){ 
									  $startdate1=$start_date;
									  $enddate=$end_date;
										   ?>
										 
										   <?php 
									  while (strtotime($startdate1) <= strtotime($enddate)) {


									  ?>
									  <tr><td colspan="5"><?= "<b>".$startdate1."</b>"; ?></td></tr>   
										  <tr><td colspan="5"><?= "<b>".ucwords($row->branch_name)."</b>"; ?></td></tr>
									   <?php
									   
									   $branchlab=getbranchlab($row->labid);
									   if($branchlab != ""){
										   $count=0;
										   $totralsample=0;
										   $totalsamount=0;
										   $totalsamountcol=0;
									  foreach($branchlab as $row1){
										   $count++; 
									  $data=getlabpayamount($row1->id,$startdate1);
									  $summary=getsummary($row1->id,$startdate1);
									   
									  ?>
									  
									  <tr>
									  <td><?= $count; ?></td>
									  <td><?= ucwords($row1->name); ?></td>
									  <td><?= round($summary[0]->samplecount);  ?></td>
									  <td><?= round($summary[0]->sampleamount); ?></td>
									  <td><?= round($data); ?></td>
									  </tr>
										<?php 
										
										$totralsample +=round($summary[0]->samplecount);
										$totalsamount += round($summary[0]->sampleamount);
										$totalsamountcol +=round($data);
										
									   
									   } ?> 
									   
									   <tr style="border-top: 3px solid black;">
                                                    <td align="right" colspan="2">Total for</td>
													
                                                    <td><b><?= round($totralsample); ?></b></td>
													 <td><b><?= round($totalsamount); ?></b></td>
													<td><b><?= round($totalsamountcol); ?></b></td>
													
                                                </tr>
												
												<tr style="border-top: 3px solid black;"><td colspan="5"></td>
												</tr>
									   
									   <?php   $gtotralsample +=round($totralsample);
												$gtotalsamount +=round($totalsamount);
												$gtotalsamountcol +=round($totalsamountcol);  
												} 
									    $startdate1=date ("d-m-Y", strtotime("+1 day", strtotime($startdate1)));
									  }
									   
									  /* 
									  $data=getlabpayamount($row->id,$startdate);
									  $summary=getsummary($row->id,$startdate);
									   
									  ?>
									  <tr>
									  <td><?= $count; ?></td>
									  <td><?= ucwords($row->name); ?></td>
									  <td><?= round($summary[0]->samplecount);  ?></td>
									  <td><?= round($summary[0]->sampleamount); ?></td>
									  <td><?= round($data); ?></td>
									  </tr>
									   
									  <?php  */
									  
									  } if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
                                            </tr>
<?php }else{ ?>
	 <tr>
                                                    <td align="right" colspan="2">Grand Total</td>
													
                                                    <td><b><?= round($gtotralsample); ?></b></td>
													 <td><b><?= round($gtotalsamount); ?></b></td>
													<td><b><?= round($gtotalsamountcol); ?></b></td>
													
                                                </tr>
												
												<tr style="border-top: 3px solid black;"><td colspan="5"></td>
												</tr>
	
<?php } ?>
                                    
                                    </tbody>
                                </table>
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
														 
                                                    });
													</script>