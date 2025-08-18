<?php

$startdate = $this->input->get("startdate");
$enddate = $this->input->get("enddate");
$type = $this->input->get("type");

 ?>
<div data-role="header" data-position="fixed" >
		<h1>Test List </h1>
		<?php /* <a id="backurl" data-rel="back" data-icon="carat-l" data-ajax="false" data-iconpos="notext"></a> */ ?>
		<a href="<?= base_url()."pathologist/dashboard/patientlist"; ?>" data-icon="carat-l" data-ajax="false" data-iconpos="notext"></a>
		 <a href="#panel-reveal" data-icon="bars" class="ui-btn-right" data-iconpos="notext">Menu</a>

	</div><!-- /header -->

<div class="ui-content" role="main">
<div id="alertstatus" data-role="popup" data-overlay-theme="b" class="ui-content">

</div>
<ul data-role="listview" data-inset="true" class="margin-1" data-theme="c">
<?php
$aptest=explode(",",$apotest[0]["aptest"]);
$procestest=explode(",",$procestest[0]["processtest"]);

foreach($jobtestlist as $row){
	$applink="<a class='checkjobstatus' id='0' href='javascript:void(0)'>";

	if(in_array($row["id"],$aptest)){
$applink="<a class='checkjobstatus' id='1' href='javascript:void(0)'>";
	$msg="success";
	}else{
	$msg="warning";
	$status=$jobdetils[0]["status"];
if($status==8){
	if(in_array($row["id"],$procestest)) {
		$link = base_url() . "pathologist/dashboard/all_test_approve_details_mail?jid=" . $jobdetils[0]["id"] . "&tid=" . $row["id"] . "&startdate=$startdate&enddate=$enddate&type=$type";
		$applink = "<a href='$link'>";

	}else{

		$applink="<a class='checkjobstatus' id='0' href='javascript:void(0)'>";
	}
}
	}

	?>
    <li><?= $applink; ?><p class="text-<?= $msg ?>"><b><?= ucwords($row["test_name"]); ?></b></p></a></li>
<?php }
/* foreach($jobpacklist as $row){
	if($row["testid"] != ""){ $getrsulturl=base_url()."pathologist/dashboard/all_test_approve_details_mail?jid=".$jobdetils[0]["id"]."&tid=".$row["testid"]; }else{ $getrsulturl="javascript:void(0)"; }
 ?>
 <li><a href="<?= $getrsulturl; ?>"><?= ucwords($row["test_name"]); ?></a></li>
<?php } */ ?>

</ul>

</div><!-- /content -->
   
</div>
<script>

</script>
</body>
</html>