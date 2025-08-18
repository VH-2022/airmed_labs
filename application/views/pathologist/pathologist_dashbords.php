<link rel="stylesheet" href="<?php echo base_url(); ?>user_assets/jquery.mobile/xpull.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>user_assets/jquery.mobile/xpull.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<style type="text/css">
    body {
        padding-top: 20px;
    }

    #demo {
        width: 100%;
        height: 400px;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
        border: 1px solid #ccc;
    }

    /* Main marketing message */
    .jumbotron {
        text-align: center;
    }
</style>
<div data-role="header" data-position="fixed">
    <h1>Dashboard</h1>
	
	<a id="backurl" data-rel="back" data-icon="carat-l" data-ajax="false" data-iconpos="notext"></a>
   <?php  /* <a href="<?= base_url() . "pathologist/dashboard"; ?>" data-ajax="false" class="ui-btn ui-shadow ui-corner-all ui-icon-refresh ui-btn-icon-notext">refresh</a> */ ?>
     <a href="#panel-reveal" data-icon="bars" class="ui-btn-right" data-iconpos="notext">Menu</a>

</div>

<div class="ui-content"  role="main">
<form method="get" action="<?= base_url()."pathologist/Dashboard" ?>" data-ajax="false">
	 <?php /* <label for="date">Start Date:</label> */ ?>
<input name="startdate" readonly id="startdate" value="<?= $startdate ?>" type="text">
<?php /* <label for="date">End Date:</label> */ ?>
<input name="enddate" readonly id="enddate" value="<?= $enddate ?>" type="text">
<label for=""></label>
                <input  data-mini="true"  type="submit" value="Search" />
				</form>
    <div id="demo">
        <div class="xpull">
            <div class="xpull__start-msg">
                <div class="xpull__start-msg-text">Pull Down &amp; Release to Refresh</div>
                <div class="xpull__arrow"></div>
            </div>
            <div class="xpull__spinner">
                <div class="xpull__spinner-circle"></div>
            </div>
        </div>
        <ul data-role="listview" data-theme="c" class="margin-1"  data-count-theme="a">
            <li><a class="chnagepage" jobtype="1" hrefurl="<?php
                if ($final_data[0]["waiing_for_approve"] > 0) {
                    echo base_url() . "pathologist/dashboard/patientlistsearch?type=1&startdate=".$startdate."&enddate=".$enddate;
                } else {
                    echo "";
                }
                ?>" >Waiting For Confirmation<span class="ui-li-count"><?= $final_data[0]["waiing_for_approve"]; ?></span></a></li>
            <li><a class="chnagepage" jobtype="6" hrefurl="<?php
                if ($final_data[0]["approve"] > 0) {
                    echo base_url() . "pathologist/dashboard/patientlistsearch?type=6&startdate=".$startdate."&enddate=".$enddate;
                } else {
                    echo "";
                }
                ?>" >Confirmed Booking<span class="ui-li-count"><?= $final_data[0]["approve"]; ?></span></a></li>
            <li><a class="chnagepage" jobtype="7" hrefurl="<?php
                if ($final_data[0]["sample_collected"] > 0) {
                    echo base_url() . "pathologist/dashboard/patientlistsearch?type=7&startdate=".$startdate."&enddate=".$enddate;
                } else {
                    echo "";
                }
                ?>" >Sample Collected<span class="ui-li-count"><?= $final_data[0]["sample_collected"]; ?></span></a></li>
            <li><a class="chnagepage" jobtype="8" hrefurl="<?php
                if ($final_data[0]["processing"] > 0) {
                    echo base_url() . "pathologist/dashboard/patientlistsearch?type=8&startdate=".$startdate."&enddate=".$enddate;
                } else {
                    echo "";
                }
                ?>">Processing<span class="ui-li-count"><?= $final_data[0]["processing"]; ?></span></a></li>
            <li><a class="chnagepage" jobtype="2" hrefurl="<?php
                if ($final_data[0]["completed"] > 0) {
                    echo base_url() . "pathologist/dashboard/patientlistsearch?type=2&startdate=".$startdate."&enddate=".$enddate;
                } else {
                    echo "";
                }
                ?>" >Completed<span class="ui-li-count"><?= $final_data[0]["completed"]; ?></span></a></li>
            <li><a class="chnagepage" jobtype="12" hrefurl="<?php
                if ($final_data[0]["total_jobs"] > 0) {
                    echo base_url() . "pathologist/dashboard/patientlistsearch?type=12&startdate=".$startdate."&enddate=".$enddate;
                } else {
                    echo "";
                }
                ?>" >Total Jobs<span class="ui-li-count"><?= $final_data[0]["total_jobs"]; ?></span></a></li>
        </ul>

    </div><!-- /content -->



</div>

</div><!-- /page -->

<div data-role="page" class="ui-page-theme-b" id="second">
        <div data-role="panel" data-position="right" id="panel-reveal">
                <ul data-role="listview">
                    <li><a href="<?= base_url() . "pathologist/Dashboard/patientlist"; ?>" data-ajax="false" data-role="button">Home</a></li>
					 <li><a href="<?= base_url() . "pathologist/Dashboard"; ?>" data-ajax="false" data-role="button">All Bookings</a></li>
                    <li><a href="javascript:void(0)" id="pagelogout" data-role="button">Log out</a></li>
                </ul>

            </div>
			
			<div data-role="header" data-position="fixed" >
		<h1>Patient List </h1>
		 <a id="backurl" data-rel="back" data-icon="carat-l" data-ajax="false" data-iconpos="notext"></a>
		 <a href="#panel-reveal" data-icon="bars" class="ui-btn-right" data-iconpos="notext">Menu</a>
		
	</div>
	
	<div class="ui-content" role="main">

<form method="get"  id="filterpatient" action="<?= base_url()."pathologist/Dashboard/patientlist" ?>">
	 <?php /* <label for="date">Start Date:</label> */ ?>
<input name="startdate" readonly id="startdate1" value="<?= $startdate ?>" type="text">
<?php /* <label for="date">End Date:</label> */ ?>
<input name="enddate" readonly id="enddate1" value="<?= $enddate ?>" type="text">
<input type="hidden" id="jobtype" name="type" value='' />
<select  name="branch" data-theme="a" data-mini="true" id="branch" >
<option value="">--All Branch--</option>
<?php foreach($branch_list as $branchd){ ?>
<option value="<?= $branchd["id"]; ?>" <?php if($branch==$branchd["id"]){ echo "selected"; } ?> ><?= ucwords($branchd["branch_name"]); ?></option>
<?php } ?>

</select>
<label for=""></label>
                <input  data-mini="true"  type="submit" value="Search" />
				</form>
<div id="demo123">
</div>
</div>
			
    </div>
<script>

/* $.extend(  $.mobile , {	ajaxEnabled		 : false,	hashListeningEnabled: false}); */
 $(function () {

                    // Init xpull plugin for demo
                    $('#demo123').xpull({
                        'callback': function () {
                            //console.log('Released...');
                            window.location.reload();
                        }
                    });

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
$(".chnagepage").click(function(){
	var urlval=$(this).attr("hrefurl");
	
	
	if(urlval != ""){
		var jobtype=$(this).attr("jobtype");
		
		$("#jobtype").val(jobtype)
		
	$.ajax({url: urlval,
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
                               $('#demo123').html(result).enhanceWithin();
							   $.mobile.changePage("#second");
                        },
                        error: function (request,error) {
                                    
                            alert('Network error has occurred please try again!');
                        }
                    }); 
	}
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
                               $('#demo123').html(result).enhanceWithin();
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