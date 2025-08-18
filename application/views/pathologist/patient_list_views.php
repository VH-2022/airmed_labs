<link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/xpull.css">
<script src="<?php echo base_url(); ?>user_assets/jquery.mobile/xpull.js"></script>
<div data-role="header" data-position="fixed" >
		<h1>Patient List </h1>
		<?php /* <a id="backurl" data-rel="back" data-icon="carat-l" data-ajax="false" data-iconpos="notext"></a> */ ?>
		 <a href="#panel-reveal" data-icon="bars" class="ui-btn-right" data-iconpos="notext">Menu</a>
		
	</div>

<div class="ui-content" role="main">

<form method="get" id="filterpatient" action="<?= base_url()."pathologist/Dashboard/patientlist" ?>">
	 <?php /* <label for="date">Start Date:</label> */ ?>
<input name="startdate" readonly id="startdate" value="<?= date("d-m-Y",strtotime($to)); ?>" type="text">
<?php /* <label for="date">End Date:</label> */ ?>
<input name="enddate" readonly id="enddate" value="<?= date("d-m-Y",strtotime($from)); ?>" type="text">
<input type="hidden" name="type" value='<?= $type ?>' />
<select  name="branch" data-theme="a" data-mini="true" id="branch" >
<option value="">--SELECT--</option>
<?php foreach($branch_list as $branchd){ ?>
<option value="<?= $branchd["id"]; ?>" <?php if($branch==$branchd["id"]){ echo "selected"; } ?> ><?= ucwords($branchd["branch_name"]); ?></option>
<?php } ?>

</select>
<label for=""></label>
                <input  data-mini="true"  type="submit" value="Search" />
				</form>
<div id="demo">


		
<?php if(empty($new_array)) { ?>
<div class="ui-bar ui-bar-e">No data available</div>
<?php }else{ ?>

<ul data-role="listview"  data-inset="true">
<?php foreach($new_array as $row){
	$status=$row["status"];
	switch ($status){ case 1: $jobstatus="<span class='label label-default pull-right'>Waiting For Confirmation</span>";break; case 2: $jobstatus="<span class='label label-success pull-right'>Completed<span>";break;case 6:$jobstatus="<span class='label label-success pull-right'>Confirmed Booking</span>";break;case 7:$jobstatus="<span class='label label-info pull-right'>Sample Collected</span>";break;case 8:$jobstatus="<span class='label label-warning pull-right'>Processing</span>";break; }
	?>
<a  href="<?= base_url()."pathologist/dashboard/patient_job_test?job_fk=".$row["id"]."&startdate=$to&enddate=$from&type=$type"; ?>">

<div class="panel panel-primary">
      <div class="panel-heading"><?= $row["DATE"]; ?><span class="badge pull-right"><?= $row["approvetest"]."/".$row["totaltest"]; ?></span></div>
      <div class="panel-body"><h5 class="text-primary"><?= ucwords($row["full_name"])." ".$row["order_id"]." (".$row["age"]."/".ucwords(substr($row["gender"],0,1)).")";  ?></h5>
	 
	  <p class="text-info"><strong><?= ucfirst($row["branch_name"]); ?></strong></p>
	  <p class="text-info">
	  <?php $aptest=explode(",",$row["apotest"][0]["aptest"]);
	  
	  $jobtestlist=$row["jobtestlist"];
	  
	  foreach($jobtestlist as $trow){
		  
		  if(in_array($trow["id"],$aptest)){ $msg="success"; }else{ $msg="warning"; }
		  ?>
		  <p class="text-<?= $msg ?>"><?= ucwords($trow["test_name"]); ?></p>
		  <?php 
	  }

	  ?>
	  </p>
	  <?= $jobstatus; ?>
	  </div>
    </div>
	</a>
  <?php } } ?>   
    
    
</ul>
</div>
</div><!-- /content -->
   
</div>
<script>
 $(function () {

                    // Init xpull plugin for demo
                    $('#demo').xpull({
                        'callback': function () {
                            //console.log('Released...');
                            window.location.reload();
                        }
                    });
					
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
				

$('#filterpatient').submit(function() {
	
    
	$.ajax({url: '<?= base_url()."pathologist/Dashboard/patientlistsearch"; ?>',
                        data: $('#filterpatient').serialize(),
                        type: 'GET',                   
                        async: 'true',
                        beforeSend: function() {
                            $.mobile.loading('show', {theme:"a", text:"Please wait...", textonly:true, textVisible: true});
                            /* $.mobile.showPageLoadingMsg(true); */ // This will show ajax spinner
                        },
                        complete: function() {
                           $.mobile.loading('hide');
                             /* $.mobile.hidePageLoadingMsg(); */  // This will hide ajax spinner
                        },
                        success: function (result) {
                               $('#demo').html(result).enhanceWithin();
                        },
                        error: function (request,error) {
                                    
                            alert('Network error has occurred please try again!');
                        }
                    }); 
    return false; // disable default submit handling
});
		
</script>

</body>
</html>