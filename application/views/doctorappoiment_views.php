<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
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
        Doctor Appointment
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."doctor_appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y"); ?>"><i class="fa fa-users"></i>Doctor Appointment</a></li>

        </ol>
    </section>
<?php
$start_date=$this->input->get('startdate');
$end_date=$this->input->get('end_date');
$city=$this->input->get('city');
$doctor=$this->input->get('doctor');
 ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Doctor Appointment</h3>
						
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						 <div  id="sucessmsg"></div>
                           <?php echo form_open("doctor_appointment", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                           <div class="col-sm-2">
                                <input type="text" readonly="" name="startdate" required placeholder="Start date" class="form-control datepicker-input" id="startdate"  value="<?= $this->input->get('startdate'); ?>" />
                            </div>
							<div class="col-sm-2">
                              <input type="text" readonly="" name="end_date" placeholder="End date" class="form-control datepicker-input" id="enddate"  value="<?= $this->input->get('end_date') ?>" />
                            </div>
							<div class="col-sm-3">
                               <select name="city" id="cityid" class="form-control chosen-select" data-placeholder="Select City" tabindex="-1" >
							   <option value="">Select City</option>
							   <?php foreach($cityall as $cit){ ?>
								   <option value="<?= $cit->id; ?>" <?php if($city==$cit->id){ echo "selected"; } ?> ><?= ucwords($cit->name); ?></option>
							<?php   } ?>
							   </select>
                            </div>
							
							<div class="col-sm-3">
                               <select name="doctor" id="doctor" class="form-control chosen-select" data-placeholder="Select Doctor" tabindex="-1" >
							   <option value="">Select Doctor</option>
							   </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
								<a class="btn btn-primary" href="<?= base_url()."doctor_appointment?startdate=".date("d-m-Y")."&end_date=".date("d-m-Y"); ?>">Reset</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close();  ?>

                        </div>
						<?php  if($query != null){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."doctor_appointment/exportcsv?startdate=$start_date&end_date=$end_date&city=$city&doctor=$doctor"; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
						<?php }  ?>
                        <div class="tableclass">
						 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
										
                                            <th>Sr no</th>
											<th>Doctor Name</th>
											<th>Patient Name</th>
											<th>Patient mobile no</th>
											<th>Appointment Date</th>
                                            <th>Booking Time</th>
											<?php /* <th>Action</th> */ ?>
                                            
										</tr>
                                    </thead>
                                    <tbody>
                                       <?php
									  $i=$counts;
									  foreach($query as $row){ $i++; ?>
									  <tr><td><?= $i; ?></td>
									  <td><?= ucwords($row->full_name); ?></td>
									  <td><?= ucwords($row->p_name); ?></td>
									   <td><?= ucwords($row->p_mobile); ?></td>
									   <td><?= date("d-m-Y",strtotime($row->starttime)); ?></td>
									  <td><?= date("h:i a",strtotime($row->starttime))."-".date("h:i a",strtotime($row->endtime)); ?></td>
									 <?php /* <td><a href="javascript:void(0);" id="sendmsg_<?= $row->id; ?>" class="sendsms" data-toggle="tooltip" data-original-title="Send Report Via Sms"><span class=""><i class="fa fa-envelope-o" style=""></i></span></a></td> */ ?>
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

<script  type="text/javascript">
       
                                                   $(function () {
													   
													    /* jQuery(".chosen-select").chosen({
        search_contains: true
    }); */
          $('#cityid').on('change', function() {
  var cityval=this.value;
  
   getdoctor(cityval);
			
});
function getdoctor(cityval,selectdoct=null){
	
	var startdate =$("#startdate").val();
	var enddate= $("#enddate").val();
	
	$.ajax({
                url: '<?php echo base_url(); ?>Doctor_appointment/getcitydoctor',
                type: 'get',
                data: {cityval: cityval,startdate:startdate,end_date:enddate,selectdoct:selectdoct},
                success: function (data) {
                    if(data != ""){
						$("#doctor").html(data);
					}
                }
            });
} 
<?php if($city != ""){  ?>                                     
getdoctor("<?= $city ?>","<?= $doctor ?>"); 
<?php } ?>
	$("#startdate").datepicker({
        todayBtn:  1,
        autoclose: true,format: 'dd-mm-yyyy'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#enddate').datepicker('setStartDate', minDate);
		var cityval =$("#cityid").val();
		var doctor =$("#doctor").val();
		if(cityval != ""){ getdoctor(cityval,doctor); }
		
    });
    
    $("#enddate").datepicker({format: 'dd-mm-yyyy',autoclose: true})
        .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#startdate').datepicker('setEndDate', minDate);
			var cityval =$("#cityid").val();
			var doctor =$("#doctor").val();
		
			
		if(cityval != ""){ getdoctor(cityval,doctor); }
        });
														 
                                                    });
													$(".sendsms").click(function(){
														var getid=this.id;
														var spliid=getid.split("_");
														var id=spliid[1];
														$("#loader_div").show();
														$.ajax({
                url: '<?php echo base_url(); ?>doctor_appointment/send_book_sms',
                type: 'get',
                data: {bookid:id},
                success: function (data) {
                    if(data='1'){ $("#sucessmsg").html('<div class="alert alert-success alert-dismissable" id="sucessmsg"  ><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>SMS successfully send</div>'); $("#loader_div").hide(); }
                }
            });
														
													});
													</script>