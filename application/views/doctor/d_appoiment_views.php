<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<style>
.full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        Appointment
            <small></small>
        </h1>
		<?php 
		if(isset($pagetype)){
			$pageurl="reception";
		}else{
			$pageurl="doctor";
		}
		?>
        <ol class="breadcrumb">
          <?php if(! isset($pagetype)){ ?>  <li><a href="<?php echo base_url()."$pageurl/dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li> <?php } ?>
            <li><a href="<?php echo base_url()."$pageurl/appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y"); ?>"><i class="fa fa-users"></i>Appointment</a></li>

        </ol>
    </section>
<?php

$start_date=$this->input->get('startdate');
$end_date=$this->input->get('end_date');
$apptype=$this->input->get('apptype');
$appcheckin=$this->input->get('checkin');

if($start_date != "" && $end_date != ""){
			
			$start_date=$start_date;
			$end_date=$end_date;
		}else{ 
		
		$start_date=date("d-m-Y");
		$end_date=date("d-m-Y");

		}


 ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
			
			<div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ADD Appointment</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php $success = $this->session->flashdata('success'); ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-autocloseable-success">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->userdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
						
						 <?php  $cmapurl="$pageurl/appointment?startdate=$start_date&end_date=$end_date"; 						 
						 
						 echo form_open($cmapurl,array("method" => "POST", "role" => "form","class"=>'form-horizontal',"id"=>'campingform' )); ?>
	
	<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Patient name<span style="color:red">*</span></label>
					  <div id="cnameerror"  class="col-sm-4">
                     <?php echo form_input(['name' => 'name','id'=>'', 'class' => 'form-control', 'placeholder' => 'Patient Name', 'value' => set_value('name')]); ?>
                           <span style="color: red;"><?=form_error('name');?></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Patient Mobile <span style="color:red">*</span></label>
					  <div class="col-sm-4">

					 <input type="text" name="mobile" placeholder="Mobile" class="form-control" value="<?= set_value('mobile'); ?>" />
					  <span style="color: red;"><?=form_error('mobile');?></span>
					 </div>
                    </div>
					
				<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Patient Age <span style="color:red">*</span></label>
					  <div class="col-sm-4">

					 <input type="text" name="paage" placeholder="Patient Age" class="form-control" value="<?= set_value('paage'); ?>" />
					  <span style="color: red;"><?=form_error('paage');?></span>
					 </div>
                    </div>	
					
						
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Patient Gender <span style="color:red">*</span></label>
					  <div class="col-sm-4">
					  <select class="form-control" name="pagender">
					  <option value="male" <?php if(set_value('pagender')=="male"){ echo "selected"; } ?>>Male</option>
					  <option value="female" <?php if(set_value('pagender')=="female"){ echo "selected"; } ?> >Female</option>
					  </select>

					
					  <span style="color: red;"><?=form_error('pagender');?></span>
					 </div>
                    </div>	
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Appointment Date<span style="color:red">*</span></label>
					  <div class="col-sm-4">

					 <input type="text" name="apdate" readonly="" id="apdate" placeholder="Appointment Date" class="form-control" value="<?= set_value('apdate') == false ? date("d-m-Y") : set_value('apdate'); ?>" />
					  <span style="color: red;"><?=form_error('apdate');?></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Appointment Time<span style="color:red">*</span></label>
					  <div class="col-sm-4">

					<select class="form-control" id="aptime" name="aptime">
					  <option value="">--Select--</option>
					 
					  </select>
					  <span style="color: red;"><?=form_error('aptime');?></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1"></label>
					  <div class="col-sm-4">
					  
<label class="radio-inline">
      <input type="radio" name="type" <?php echo set_radio('type', '1', TRUE); ?> value="1" name="optradio">Walk In Appointment 
    </label>
    <label class="radio-inline">
      <input type="radio" name="type" <?php echo set_radio('type', '2'); ?> value="2" name="optradio">Schedule Appointment
    </label>
	
					  <span style="color: red;"><?=form_error('type');?></span>
					 </div>
                    </div>
	
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="submit" class="btn btn-success" value="Submit" />
		
      </div>
	  
    </div>
 <?php echo form_close();  ?>
					  
                    </div><!-- /.box-body -->
                </div>
				
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Appointment List</h3>
						
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						 <div  id="sucessmsg"></div>
                           <?php echo form_open("$pageurl/appointment", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                           <div class="col-sm-3">
                                <input type="text" readonly="" name="startdate" required placeholder="Start date" class="form-control datepicker-input" id="startdate"  value="<?= $start_date ?>" />
                            </div>
							<div class="col-sm-3">
                              <input type="text" readonly="" name="end_date" placeholder="End date" class="form-control datepicker-input" id="enddate"  value="<?=$end_date ?>" />
                            </div>
							
							
							<div class="col-sm-2">
                               <select class="form-control" name="apptype">
							   <option value="">--Select Appointment--</option>
							   <option value="1" <?php if($apptype==1){ echo "selected"; } ?> >Walk In</option>
							   <option value="2" <?php if($apptype==2){ echo "selected"; } ?> >Schedule</option>
		
					  </select>
					  
                            </div>
							
							<div class="col-sm-2">
                               <select class="form-control" name="checkin">
							   <option value="">--select--</option>
							   <option value="1" <?php if($appcheckin==1){ echo "selected"; } ?> >Check In</option>
							   <option value="2" <?php if($appcheckin==2){ echo "selected"; } ?> >Not Check in</option>
		
					  </select>
					  
                            </div>
							 

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
								<a class="btn btn-primary" href="<?= base_url()."$pageurl/appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y"); ?>">Reset</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close();  ?>

                        </div>
						<?php  if($query != null){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."$pageurl/appointment/exportcsv?startdate=$start_date&end_date=$end_date&apptype=$apptype&checkin=$appcheckin"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
						<?php }  ?>
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
										
                                            <th>Sr no</th>
											<th>Patient Name</th>
											<th>Patient mobile no</th>
											<th>Appointment Date</th>
                                            <th>Booking Time</th>
											<th>Appointment</th>
											 <th>Action</th>
                                            
										</tr>
                                    </thead>
                                    <tbody>
                                       <?php
									  $i=$counts;
									  foreach($query as $row){ $i++; ?>
									  <tr><td><?= $i; ?></td>
									  <td><?= ucwords($row->p_name); ?></td>
									   <td><?= ucwords($row->p_mobile); ?></td>
									   <td><?= $appdate=date("d-m-Y",strtotime($row->starttime)); ?></td>
									  <td><?= date("h:i a",strtotime($row->starttime))."-".date("h:i a",strtotime($row->endtime)); ?></td>
									  <td>
									  <?php if($row->type=='2'){ if($row->checkin=='1'){ echo "Schedule(<b>Check In</b>)"; }else{ echo "Schedule(<b>Not Check In</b>)"; }  }else{ echo "Walk in"; } ?>
									  </td>
									  <td>
									  
									  <?php if($row->checkin == '2' && $row->status=='1' && $appdate==date("d-m-Y")){ ?>
									  <a class="btn btn-primary btn-xs" href='<?= base_url()."$pageurl/appointment/check_in/".$row->id;  ?>' data-toggle="tooltip" data-original-title="Check in" onclick="return confirm('Are you sure you want to appointment check in?');"><i class="fa fa-check-circle"></i> Check in</a>
									  <?php } ?>
									  
									  <?php 
									  
									  if($row->type=='2' && $row->status=='1' && $row->checkin == '2' && strtotime(date("Y-m-d H:i:s")) < strtotime($row->starttime)){ ?>
									  
									  <a class="btn btn-danger btn-xs" href='<?= base_url()."$pageurl/appointment/app_delete/".$row->id;  ?>' data-toggle="tooltip" data-original-title="Cancel" onclick="return confirm('Are you sure you want to cancel this data?');"><i class="fa fa-trash-o"></i> Cancel</a>
									  
									  <?php } if($row->status=='2'){ echo "<a class='btn btn-danger btn-xs' href='javascript:void(0)'>Canceled</a>"; }
									  if($row->checkin == '1' && $row->status=='1' ){
										  echo "<a class='btn btn-primary btn-xs' href='javascript:void(0)'>Checked In</a>";
									  }


									  ?>
									  
									  
									  
									  
									  </td>
									
									  </tr>
										 
									  <?php } if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

<script>

$(document).ready(function() {
	
	var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    $('input[type=radio][name=type]').change(function() {
        if(this.value == 2){
			
			
			$("#apdate").datepicker({
        todayBtn:  1,
		startDate: today,
        autoclose: true,format: 'dd-mm-yyyy'
    }).on('changeDate', function (selected) {
        
		getslot();
	
    });
	
			
		}else{
			
			$("#apdate").val("<?= date("d-m-Y"); ?>");
			$('#apdate').datepicker('remove');
			
			
		}
       
});

 $('#apdate > .form-control').prop('disabled', true);
});


 
  $("#startdate").datepicker({
        todayBtn:  1,
        autoclose: true,format: 'dd-mm-yyyy'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
    });
    
    $("#enddate").datepicker({format: 'dd-mm-yyyy',autoclose: true})
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
        });	
		
		

function getslot(){
	var date=$("#apdate").val();
	
	$("#loader_div").show();
	
	$.ajax({
    url:'<?php echo base_url().$pageurl; ?>/appointment/getdoctorslot',
            type:"get",
            data:{adate:date},
			dataType:"json",
            success:function(data1){ if(data1.status==1){ 
			$('#aptime').html('');
			
 $.each(data1.data, function(i, item) { 
if(item.status==0){ var dis="disabled"; }else{ var dis=""; } 
 $("#aptime").append($("<option "+dis+" ></option>").attr("value",item.id).text(item.slot)); 
 });
 
 
	}
	$("#loader_div").hide();
}

    });
}
getslot();	
		
</script>