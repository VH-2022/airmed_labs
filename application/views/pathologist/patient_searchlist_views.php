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
  <?php } ?> </ul> <?php } ?>   