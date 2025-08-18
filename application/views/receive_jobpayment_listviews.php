<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
    .chosen-container {width: 100% !important;}

    span.multiselect-native-select {
        position: relative
    }
    span.multiselect-native-select select {
        border: 0!important;
        clip: rect(0 0 0 0)!important;
        height: 1px!important;
        margin: -1px -1px -1px -3px!important;
        overflow: hidden!important;
        padding: 0!important;
        position: absolute!important;
        width: 1px!important;
        left: 50%;
        top: 30px
    }
    .multiselect-container {
        position: absolute;
        list-style-type: none;
        margin: 0;
        padding: 0
    }
    .multiselect-container .input-group {
        margin: 5px
    }
    .multiselect-container>li {
        padding: 0
    }
    .multiselect-container>li>a.multiselect-all label {
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group label {
        margin: 0;
        padding: 3px 20px 3px 20px;
        height: 100%;
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group-clickable label {
        cursor: pointer
    }
    .multiselect-container>li>a {
        padding: 0
    }
    .multiselect-container>li>a>label {
        margin: 0;
        height: 100%;
        cursor: pointer;
        font-weight: 400;
        padding: 3px 0 3px 30px
    }
    .multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
        margin: 0
    }
    .multiselect-container>li>a>label>input[type=checkbox] {
        margin-bottom: 5px
    }
    .btn-group>.btn-group:nth-child(2)>.multiselect.btn {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px
    }
    .form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
        padding: 3px 20px 3px 40px
    }
    .form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
        margin-left: -20px;
        margin-right: 0
    }
    
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
    .btn-group, .btn-group-vertical {
        display: inline-block;
        position: relative;
        vertical-align: left;
        width: 100%;
    }
    .multiselect{width:100%;text-align: left;}
    .wordwrap { 
        white-space: pre-wrap;      /* CSS3 */   
        white-space: -moz-pre-wrap; /* Firefox */    
        white-space: -pre-wrap;     /* Opera <7 */   
        white-space: -o-pre-wrap;   /* Opera 7 */    
        word-wrap: break-word;      /* IE */
    }
    .multiselect-container.dropdown-menu {
        max-height: 400px;
        min-height: 100px;
        overflow-wrap: break-word;
        overflow-x: hidden;
        overflow-y: scroll;
        width: 100%;
    }
    a .checkbox {
        white-space: pre-line;
    }
    ul .active-result {
        white-space: pre-line;
        word-wrap: break-word; 
        width:100% !important;
    }
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }
    span.multiselect-selected-text {
        white-space: nowrap;
        overflow: hidden;
        width: 100%;
        float: left;
        white-space:pre-line;
    }   
</style>
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .nav-justified>li{width:auto !important;}
    .nav-justified>li.active{background:#eee; border-top:3px solid #3c8dbc;}
</style>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
 <link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           B2b collection report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>receive_payment/b2bcollection_report"><i class="fa fa-users"></i>B2b collection report</a></li>

        </ol>
    </section>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<?php $laballsea=$this->input->get('lab');
$tcity=$this->input->get('city');
$plm=$this->input->get('plm');
$branch=$this->input->get('branch'); 

?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Collect cash from Lab</h3>
                        
                                 
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						
							 <div  id="sucessmsg"></div>
							   <?php echo form_open("receive_payment/b2bcollection_report", array("method" => "GET", "id"=>"payform","role" => "form")); ?>
                        
						<?php /* <div class="col-sm-3">
                               <select name="lab" class="form-control chosen" data-placeholder="Select Lab" tabindex="-1" >
							   <option value="">Select Lab</option>
							  <?php  foreach($lablist as $lab){ ?>
								   <option value="<?= $lab->id; ?>" <?php if($laballsea==$lab->id){ echo "selected"; } ?> ><?= ucwords($lab->name); ?></option>
							  <?php  } ?>
							   </select>
                            </div> */ ?>
							
							<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                         <input type="text" name="startdate" placeholder="Start date" readonly="" class="form-control" id="startdate"  value="<?= $startdate ?>" />
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
									 <input type="text" name="enddate" placeholder="End date" readonly="" class="form-control" id="enddate"  value="<?= $enddate ?>" />
									 </div>
                                </div>
                          
							<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
									 <select class="form-control chosen" onchange="get_plm(this.value);" data-placeholder="Select Test city" tabindex="-1" name="city" id="citiess">
                                            <option value="">--All city--</option>
                                            <?php
                                            $test_cnt = "";
                                            foreach ($test_cities as $bkey) {
                                                      ?>
                                            <option value="<?= $bkey->id ?>" <?php if ($tcity == $bkey->id) { $test_cnt=$bkey->name;  echo "selected"; } ?>><?= $bkey->name; ?></option> <?php  } ?>
                                        </select>
                                </div>
							
							
                               
                            </div>
							
							<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                               <select class="form-control chosen" onchange="get_branch(this.value)" data-placeholder="Select PLM" tabindex="-1" name="plm" id="plm_id">
                                            <option value="">--All PLM--</option>
                                        </select>
										<span id="errorbranch" style="color: red;"></span>
                            </div>
							</div>
							<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
							
                               <select class="multiselect-ui form-control" id="branch" title="Select Branch" multiple="multiple" name="branch[]">
                                            
                                        </select>
                            </div>
							</div>
							<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >

                           
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
								<button type="button" class="btn btn-sm btn-primary" onclick="window.location = '<?= base_url(); ?>receive_payment/b2bcollection_report'" value="Reset"/><i class="fa fa-refresh"></i>Reset</button>
								<?php if($query != null){ ?>
								<a  href='javascript:void(0);' id="job_search_btn" onclick="get_csv_link();" class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
								<?php } ?>
                            </div>
							</div>
                        
                        <br>
                        <?php echo form_close();  $serlab=$laballsea;  ?>
						
							</div>
                      
                        <div class="tableclass">
                               <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
											<th>Lab name</th>
											<th>Plm name</th>
											<th>City name</th>
											<th>Order id</th>
											<th>Branch name</th>
											
											<th>Collected amount</th>
											<th>Type</th>
											<th>Collected date</th>
                                            <th>Collected by</th>  
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = $page;
                                        foreach ($query as $row) {
											$cnt++;
											
                                            ?> 

                                            <tr>
											
                                                <td><?php echo $cnt; ?></td>
												<td><?= ucwords($row->name); ?></td>
												<td><?php if($row->parentbranch != ""){ echo ucwords($row->parentbranch); }else{ echo ucwords($row->branch_name); } ?></td>
												<td><?= $row->cityname ?></td>
												<td><a target="_blank" href="<?= base_url()."b2b/Logistic/details/".$row->jobid ?>"><?= $row->order_id ?></a></td>
												<td><?= ucwords($row->branch_name); ?></td>
												<td><?= "Rs.".$row->amount ?></td>
												<td><?= $row->type ?></td>
												<td><?= date("d-m-Y",strtotime($row->createddate)); ?></td>
												<td><?= $row->addedby ?></td>
												
                                            </tr>
                                            
                                            <?php
                                            
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="12">No records found</td>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<script>
$(function () {
        $('.chosen').chosen();
		$('.multiselect-ui').multiselect({includeSelectAllOption: true,                                                     nonSelectedText: 'Select Branch'});
			   
$("#startdate").datepicker({
                                                todayBtn: 1,
                                                autoclose: true, format: 'dd-mm-yyyy', endDate: '+0d'
                                            }).on('changeDate', function (selected) {
                                                var minDate = new Date(selected.date.valueOf());
                                                $('#enddate').datepicker('setStartDate', minDate);
                                            });

                                            $("#enddate").datepicker({format: 'dd-mm-yyyy', autoclose: true, endDate: '+0d'})
                                                    .on('changeDate', function (selected) {
                                                        var minDate = new Date(selected.date.valueOf());
                                                        $('#startdate').datepicker('setEndDate', minDate);
                                                    });
													
   });

$(".sentemail").click(function(){
	var selectId  = this.id;
	var splitid=selectId.split('_');
	var sentid=splitid[1];
	$("#loader_div").show();
	$("#sucessmsg").html('');
	$.get("<?= base_url()."b2b/amount_manage/receipt_sendmail/"; ?>"+sentid, function(data, status){
		if(data='1'){ $("#sucessmsg").html('<div class="alert alert-success alert-dismissable" id="sucessmsg"  ><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Payment Receipt successfully send </div>'); $("#loader_div").hide(); }
    });
});
function get_plm(val) {
        var setplm="<?= $plm ?>";
        $.ajax({
            url: '<?php echo base_url(); ?>receive_payment/get_plm',
            type: 'post',
            data: {city: val,setplm:setplm},
            success: function (data) {
                $("#plm_id").html(data);
                jQuery("#plm_id").trigger("chosen:updated");
                get_branch("");
            },
            error: function (jqXhr) {
                $("#plm_id").html("");
            },
            complete: function () {
            },
        });
    }
<?php 
if($tcity != ""){ ?>
	get_plm('<?= $tcity ?>')
<?php }

?> 	
function get_branch(val) {
        
        var plm = $("#plm_id").val();
		var branchname=$("#plm_id option:selected").text();
		var setbbrach="<?= implode(",",$branch); ?>";
        $.ajax({
            url: '<?php echo base_url(); ?>receive_payment/get_branch',
            type: 'post',
            data: {plm: plm,setbbrach:setbbrach,branchname:branchname},
            success: function (data) {
                $("#branch").html(data);
                $('.multiselect-ui').multiselect("rebuild");
                if (data.trim() == '') {
                    setTimeout(function () {
                        $("ul.multiselect-container.dropdown-menu").html("Data not available.");
                    }, 1000);
                }
            },
            error: function (jqXhr) {
                $("#branch").html("");
            },
            complete: function () {
            },
        });
    }
/*  $("#payform").submit(function () {

            var baranch = $("#plm_id").val();
            if (baranch == "") {
                $("#errorbranch").html("PLM field is required");
                return false
            } else {
                $("#errorbranch").html("");
            }

        }); */	
		function get_csv_link() {
                                                    var form = $("#payform");
                                                    var recursiveDecoded = decodeURIComponent(form.serialize());
                                                    window.location.href = "<?= base_url(); ?>/receive_payment/collectionreport_csv?" + recursiveDecoded;
                                                }
</script>