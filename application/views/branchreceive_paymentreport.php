<style>
.chosen-container {width: 100% !important;}
</style>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Daily cash register
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-users"></i>Daily case register</a></li>

        </ol>
    </section>
<?php
$start_date=$this->input->get('start_date');
$end_date=$this->input->get('end_date');
 ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Daily cash register</h3>
						
						<a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/receive_payment_add"; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i><strong> Receive Payment</strong></a>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                           <?php echo form_open("branch_receive_payment/report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                           <div class="col-sm-3">
                                <input type="text" required name="start_date" placeholder="Start date" class="form-control datepicker-input" id="startdate"  value="<?= $this->input->get('start_date'); ?>" />
                            </div>

                            <div class="col-sm-3">
                                <input type="text" required name="end_date" placeholder="End date" class="form-control datepicker-input" id="enddate"  value="<?= $this->input->get('end_date') ?>" />
                            </div> 
							<div class="col-sm-4"> 
                                <select class="form-control chosen" required data-placeholder="Select Branch" tabindex="-1" name="branch">
                                    <option value="" >Branch</option>
                                    <?php foreach ($branch_list as $branch1) { ?>
                                        <option value="<?php echo $branch1->id; ?>" <?php
                                            if ($branch == $branch1->id){ echo "selected";   }
                                        ?>><?php echo $branch1->branch_name; ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                            
                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>

                        </div>
						<?php if($branch != ""){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/report_exportcsv?branch=$branch&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
                        <a style="float:right;margin-right:5px;" href="<?= base_url()."branch_receive_payment/print_report?branch=$branch&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm" ><strong>Print</strong></a>
						<?php } ?>
                      
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr no</th>
											<th>Date</th>
                                            <th>Cash Collection Same Day</th>
                                            <th>Prev. Day collection</th>
											<th>Deposit in bank</th>
											<th>Same Day Difference</th>
											<th>Cumulative Difference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
									   /*CashCreditorDataFromStartDay
									   CashFromStartDay */
									  
									   $tptal=0;
									   $count=0;
									 
									   $lastBankEntry=$CashCreditorDataPrevDay+$CashPrevDay;
									   $backCumulativeDiff=0;
									   $backCumulativeDiff=$CashCreditorDataFromStartDay[0]->SameDay+$CashCreditorDataFromStartDay[0]->BackDay+$CashFromStartDay[0]->SameDay+$CashFromStartDay[0]->BackDay-$lastBankEntry;
									   $CumulativeDiff=$backCumulativeDiff-$BankDepositFromStartDay;
										
										/* print_r( $backCumulativeDiff);
										print_r( $CumulativeDiff);
										print_r( $BankDepositFromStartDay); */
										$prevDate="";
									   foreach($query as $row){ $count++; 
									  $data=getjobspayamount($branch,"1",date("Y-m-d",strtotime($row["date"]))); ?>
									   <tr>
									   <td><?= $count; ?></td>
									   <td><?= date("d-m-Y",strtotime($row["date"])); ?></td>
									   <td><?= round($row["SameDay"])+round($row["creditor_sameday"]); ?></td>
									   <td><?= round($row["BackDay"])+round($row["creditor_backday"]); ?></td>
									   <td><?= round($data['banckamount']); ?></td>
									   
									   <td><?php $tptal=$lastBankEntry-round($data['banckamount']);
									   echo $tptal;
									   $CumulativeDiff+=$tptal;

									   ?></td><td><?= $CumulativeDiff ?></td>
									   </tr>
									   
									  <?php $total=round($row["SameDay"])+round($row["creditor_sameday"])+round($row["BackDay"])+round($row["creditor_backday"]);
									   $lastBankEntry=$total;
									  
									  }
                                            
                                        if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="7">No records found</td>
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



<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

                                                   
                                                    $(function () {
                                                        $('.chosen').chosen();
														 $( "#startdate" ).datepicker({ format: 'dd-mm-yyyy' });
														 $( "#enddate" ).datepicker({ format: 'dd-mm-yyyy' });
                                                    });
													</script>