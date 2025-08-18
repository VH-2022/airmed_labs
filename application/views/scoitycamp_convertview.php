<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<?php /* <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> */ ?>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
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

<div class="content-wrapper">

<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          Society Camp Convert Report
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()."dashboard"; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Society camp convert report.</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
		<?php
		
		$did= $this->input->get('did');
		$dockcamp= $this->input->get('dockcamp');
		$start_date = $this->input->get_post("start_date");
        $end_date = $this->input->get_post("end_date");
		
		
		?>	
				 <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Society Camp Convert</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						<?php 
						if($query != null){ ?>
						
						<a style="float:right;margin-right:5px;" href="<?= base_url()."camping/campconvert_export?did=$did&dockcamp=$dockcamp&start_date=$start_date&end_date=$end_date"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
							
						<?php }
						?>
                           
                        </div>
						
						<div class="widget">
						 <?php 
						 $cmapurl=base_url()."camping/society_campconvert";
						 echo form_open($cmapurl, array("id"=>'covertform',"method" => 'GET')); ?>
						 
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
                                    <div class="col-sm-2" id="getdocto" style="margin-bottom:10px;" >
									
                                         <select name="did" id="doctorid"  class="form-control chosen-select">
										  <option value="">Select Doctor</option>
										  <?php foreach($doctor_list as $doc){ ?>
											  <option value="<?= $doc["id"] ?>" <?php if($doc["id"]==$did){ echo "selected"; } ?> ><?= ucwords($doc["full_name"]); ?></option>
											  
										<?php  } ?>
                                       
                                    </select>
									<span id="erorrdoctot" style="color:red;"></span>
									
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <div class="col-sm-2" id="getcamp" style="margin-bottom:10px;" >
									
                                         <select name="dockcamp" class="form-control chosen-select">
										  <option value="">Select Camp</option>
										  
                                       
                                    </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <div class="col-sm-2" style="margin-bottom:10px;" >
									<button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                                        <a type="button" href="<?= base_url()."camping/society_campconvert" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>
										
										
									</div>
								</div>	
								
								
								 <?php echo form_close(); ?>
						</div>
					<br>
			            <div class="tableclass">
                           
							<div class="table-responsive pending_job_list_tbl">
                            <table id="example3" class="table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>Camp name</th>
                                        <th>Patient name</th>
										 <th>Mobile</th>
										  <th>Age</th>
										  <th>Gender</th>
										  <th>Test</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    <?php
                                    $cnt = 0;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr>
										
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo ucfirst($row['campname']); ?></td>
											<td><?php echo ucfirst($row['name']); ?></td>
											<td><?php echo $row['mobile']; ?></td>
											<td><?php echo $row['age']; ?></td>
											<td><?php 
											if($row["gender"] == "2"){ echo $gen="Female"; }else{ echo $gen="Male"; } ?></td>
											<td><?php 
											$gettest=$row['job_test_list'];
											foreach ($gettest as $val) {
                                                  echo ucfirst($val["test_name"])."<br>";
                                               }
												
											?></td>
											
                                        </tr>
                                        <?php
                                    }
                                    if ($cnt == '0') {
                                        ?>
                                        <tr>
                                            <td colspan="6"><center>No records found</center></td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
							</div>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
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
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>
 $(function () {

                        $('.chosen-select').chosen();
						
						 $("#startdate1").datepicker({
                                                todayBtn: 1,
                                                autoclose: true, format: 'dd-mm-yyyy', endDate: '+0d'
                                            }).on('changeDate', function (selected) {
                                                var minDate = new Date(selected.date.valueOf());
                                                $('#enddate1').datepicker('setStartDate', minDate);
                                            }).attr("autocomplete", "off");;

                                            $("#enddate1").datepicker({format: 'dd-mm-yyyy', autoclose: true, endDate: '+0d'})
                                                    .on('changeDate', function (selected) {
                                                        var minDate = new Date(selected.date.valueOf());
                                                        $('#startdate1').datepicker('setEndDate', minDate);
                                                    }).attr("autocomplete", "off");;
													$('#doctorid').on('change', function() {
														
							$("#loader_div").show();
							var didval="<?= $dockcamp; ?>";
  $.ajax({
                url: '<?php echo base_url(); ?>camping/getdoctorcamp',
                type: 'get',
                data: {did:this.value,didval:didval},
                success: function (data) {

                    $("#getcamp").html(data);
					$('.chosen-select').chosen();
					 $("#loader_div").hide();
                }
            });
});

$('#covertform').on('submit', function() {
	
	var doctorid=$("#doctorid").val();
	$("#erorrdoctot").html("");
	if(doctorid == ""){ $("#erorrdoctot").html("The Doctor Field Is Required"); return false;  }else{ return true;  }
	
	
});
<?php if($did != ""){ ?>
$('#doctorid').change();
<?php } ?>
                  
						
                    });
					
					
				
</script>