<style>
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
            Slot
            <small></small>
        </h1>
        <ol class="breadcrumb">
           <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li>
            <li><a href="<?php echo base_url()."doctor/timslot"; ?>">slot</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Slot</h3>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
							
							<div  id="sucessmsg"></div>
							</div>
						
						 
                       
                        <div class="tableclass">
                            
                                 <table class="table table-striped" id="city_wiae_price">
                                                    <thead>
													<tr>
                                                       <?php 
													   $daysall = array (0 => 'Sun',1 => 'Mon',2 => 'Tue',3 => 'Wed',4 => 'Thu',5 => 'Fri',6 => 'Sat');
													   foreach($daysall as $key => $value){ ?>
													   <td><?= $value ?></td>

													   <?php } ?>
													   </tr>
                                                    </thead>
                                                    <tbody>
													<?php foreach($slotlist as $slot){ if($slot->weekend=='0'){ echo "<tr>"; } ?>
													<td><div class="checkbox"> <label><input type="checkbox" <?php if($slot->deltid ==0){ echo "checked"; } ?>  value="<?= $slot->id ?>" name="cal[]" class="checkbox" ><?= date("g:i a",strtotime($slot->start_time))."-".date("g:i a",strtotime($slot->end_time)); ?></label></div></td>
													<?php /* foreach($daysall as $key => $value){
														$getdayslot=getdoctorslot($key);
														foreach($getdayslot as $slot){ ?>
														<tr>
													   <td><?=  $slot->start_time ?></td>
													   </tr>
													<?php } } */ 
													if($slot->weekend=='6'){ echo "</tr>"; }
													
													} ?>
													   
        
                                                    </tbody>
                                                </table>
											
												<div class="checkbox">
                                                   
													 <label><input type="checkbox"  id="select_all" class="checkbox"/> Check all</label>
													 
													<span style="color: red;"><p id="sloterror"></p></span> 
													
                                                </div>
												
					
					  <div class="col-sm-6">
					   <label class="control-label " for="exampleInputEmail1">Slot Total Book<span style="color:red">*</span></label>
                     <input type="text" name="stotalbook" placeholder="Slot Total Book" class="form-control" id="stotalbook"  value="<?= $doctotinfo->slotbook; ?>" />
					 <span style="color: red;"><p id="slottbook"></p></span>
					 
					  <label class="control-label " for="exampleInputEmail1"></label>
					 <a href="javascript:void(0)" class="btn btn-primary form-control" id="updatedoctorid" >Update</a>
					
					 </div>
					 <div class="col-sm-6">
					   
					   
					 </div>
                   
                        </div>
						
                       
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
    $("#select_all").change(function () {  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

    $('.checkbox').change(function () {
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });
	$("#updatedoctorid").click(function(){
		var unchecked = [];
		var checked = [];
		var stotalbook=$("#stotalbook").val();
		$("#loader_div").show();
$("input[name='cal[]']:not(:checked)").each(function ()
{
    unchecked.push(parseInt($(this).val()));
});
$("input[name='cal[]']:checked").each(function ()
{
    checked.push(parseInt($(this).val()));
});
$("#sloterror").html("");
$("#slottbook").html("");

if(checked == ""){ $("#sloterror").html("Please select slots."); }

if(stotalbook <= 0){ $("#slottbook").html("Please valid slot total book"); }
if(checked != "" && stotalbook > 0){
	
$.ajax({  url: '<?php echo base_url(); ?>doctor/timslot/slotupdate',
                type: 'POST',
                data: {stotalbook:stotalbook,dslot:unchecked},
                success: function (data) {
					 if(data=='1'){ $("#sucessmsg").html('<div class="alert alert-success alert-dismissable" id=""  ><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Successfully Updated Slot</div>');$("html, body").animate({
            scrollTop: 0
        }, 600);
        }
		if(data=='2'){  window.location.reload(); }
$("#loader_div").hide();					 
                }
            });
	
}else{

$("#loader_div").hide();
	
}

 
	});
</script>