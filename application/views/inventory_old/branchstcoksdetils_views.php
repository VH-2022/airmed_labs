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
.full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>

<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<div class="content-wrapper">

<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Branch Stock details
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= base_url()."inventory/stock_itemmaster" ?>"><i class="fa fa-shopping-bag"></i>Branch Stock Master</a></li>
            <li class="active">Branch Stock Details</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <div class="widget">

        </div>
		<?php

$branch =$this->input->get_post("branch");
$itemget=$this->input->get_post("item");	 
		 ?>
        <div class="row">
		
		<?php if (! empty($query)) { ?>
<a  href="<?= base_url()."inventory/stock_itemmaster/stockdetilsexport?branch=$branch&item=$itemget"; ?>" id="" class="pull-right btn btn-sm btn-primary"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>
<?php } ?>
		
        </div>

        <div class="row">
		
		
		<div class="widget">
                              
                            </div><br>
		
							
            <div class="col-md-12">
<div class="table-responsive pending_job_list_tbl">
					     
						   <table class="table-striped">
                    <thead>
                    <th>No</th>
                    <th>Branch Name</th>
					<th>Item</th>
                    <th>Batch number</th>
					<th>Expiry date</th>
					 <th>Quantity</th>
					 <th>Used</th>
					 <th>Available</th>
                   
                    </thead>
                    <tbody>
                        <?php
                        $i = $counts;
						$totaavlible=0;
						$totalused=0;
						$totalquity=0;
                        foreach ($query as $row) {
                            $i++;
							
                            ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= ucwords($row["branch_name"]); ?></td>
                                <td><?= $row["reagent_name"]; ?></td>
								<td><?= $row["batch_no"]; ?></td>
								<td><?= date("d-m-Y",strtotime($row["expire_date"])); ?></td>
								<td><?= $row["quantity"]; ?></td>
								<td><?= $used=$row["stcok"]; ?></td>
								<td><?= $avlible=($row["quantity"]-$row["stcok"]) ?></td>
								
                               
                            </tr>
                        <?php $totaavlible +=$avlible;
							$totalused +=$used;	
							$totalquity +=$row["quantity"];
						
						}if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="4">No records found</td>
                                        </tr>
                                    <?php }else{ ?>
										 <tr>
                                    <td colspan="5"></td>
									<td><?= $totalquity; ?></td>
                                    <td><?= $totalused ?></td>
                                    <td><?= $totaavlible ?></td>
                                </tr>
									<?php }
                        ?>

                    </tbody>
                    
                </table>
				</div>
            </div>
            <div style="text-align:right;" class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <?php  echo $links;  ?>
                </ul>
            </div>
        </div>

    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->

<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>
$(function () {	

 $('.chosen-select').chosen();
 
$("#startdate1").datepicker({
        todayBtn:  1,
        autoclose: true,format: 'dd-mm-yyyy',endDate: '+0d'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate1').datepicker('setStartDate', minDate);
    });
    
    $("#enddate1").datepicker({format: 'dd-mm-yyyy',autoclose: true,endDate: '+0d'})
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate1').datepicker('setEndDate', minDate);
        });	
	
	$("#branch_id").change(function(){
		
    var id = $('#branch_id').val();
	var machinid="<?=$machine ?>";
	
    $("#loader_div").show();
		
        $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/getbranchmachin",
            type:"POST",
            data:{branch_fk:id,machinid:machinid},
            success:function(data){
               
                $('#machine').html(data);
                $('#machine').trigger("chosen:updated");
				
				$('#reagent').html("<option value=''>Select Reagent</option>");
                $('#reagent').trigger("chosen:updated");
				$('#batchno').html("<option value=''>Select Batch no</option>");
                $('#batchno').trigger("chosen:updated");
				$("#getstocks").html("");
				$("#totalstock").val("0");
				if(id != ""){ $("#errorbranch").html(""); }
				
				$("#loader_div").hide();
				
				$("#machine").change();
            }
        });
});

$("#branch_id").change();
$(document).on('change','#machine', function(){
	 $("#loader_div").show();
	var machinid=this.value;
	var reagent="<?= $reagent ?>";
	
	 $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/getmachinagent",
            type:"POST",
            data:{machin:machinid,reagent:reagent},
            success:function(data){
                $('#reagent').html(data);
                $('#reagent').trigger("chosen:updated");
				
				$('#batchno').html("<option value=''>Select Batch no</option>");
                $('#batchno').trigger("chosen:updated");
				$("#getstocks").html("");
				$("#totalstock").val("0");
				if(this.value != ""){ $("#errormachine").html(""); }
				
				$("#loader_div").hide();
				$("#reagent").change();

            }
        });
		
});

$(document).on('change','#reagent', function(){
	
	 $("#loader_div").show();
	var machinid=this.value;
	var batchno="<?= $batchno; ?>";
	 $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/getreagentbanch",
            type:"POST",
            data:{reagent:machinid,batchno:batchno},
            success:function(data){
                $('#batchno').html(data);
                $('#batchno').trigger("chosen:updated");
				$("#getstocks").html("");
				$("#totalstock").val("0");
				if(this.value != ""){ $("#errorreagent").html(""); }
				
				$("#loader_div").hide();

            }
        });
		 });
                           
 });
</script>