<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
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
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content-header">
    <h1>
        Use Reagent
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/indent_usereagent/"><i class="fa fa-users"></i>List</a></li>
        <li class="active">Use Reagent</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
			
			 <div class="box-body">

                    <div class="col-md-6">
					
					 <?php  echo form_open("inventory/indent_usereagent/addusestock", array("method" => "POST", "role" => "form","id"=>'poform'));  ?>
           
                <div class="form-group">
				 <label for="message-text" class="form-control-label">Branch: <span style="color:red">*</span></label>
                    <select class="chosen chosen-select" id ="branch_id" name="branch_fk" onchange="getReagent();">
                            <option value="">--Select Branch---</option>
                        <?php
                           $item_list_array = array();
                            foreach ($branch as $val) {
                                   
                                    $item_list_array = $item_list_array . '<option value="' . $val["BranchId"] . '">' . $val["BranchName"] . '</option>';
                                    
                                    ?>

                                    <option value="<?= $val["BranchId"] ?>"><?= $val["BranchName"] ?></option>
                            <?php }
                            ?>
                        </select>
						 <span id="errorbranch" style="color: red;"></span>
                </div>
				
				 <div class="form-group">
				 <label for="message-text" class="form-control-label">Machine : <span style="color:red">*</span></label>
                    <select class="chosen chosen-select" id="machine" name="machine "  >
                            <option value="">--Select Machine ---</option>
                       
                        </select>
						<span id="errormachine" style="color: red;"></span>
                </div>
				
				<div class="form-group">
				 <label for="message-text" class="form-control-label">Reagent : <span style="color:red">*</span></label>
                    <select class="chosen chosen-select" id ="reagent" name="reagent "  >
                            <option value="">--Select Reagent ---</option>
                       
                        </select>
						<span id="errorreagent" style="color: red;"></span>
                </div>
				
				<div class="form-group">
				 <label for="message-text" class="form-control-label">Batch no : <span style="color:red">*</span></label>
                    <select class="chosen chosen-select" id ="batchno" name="batchno "  >
                            <option getstock="" value="">--Select Batch no ---</option>
                       
                        </select>
						<span id="errorbatch" style="color: red;"></span>
                </div>
				 <div class="form-group">
				 
                        <label for="message-text" class="form-control-label">Quantity:</label><br>
						<span id="getstocks"></span>
                      
					   <input type="text" class="form-control calution" name="quantity" id="quantity" />
					   <span id="errorqunty" style="color: red;"></span>
 
                    </div>
				
                 <div class="form-group">
              <button type="submit" class="btn btn-primary">Submit</button>
             
            </div>
          
            
					 <?php echo form_close(); ?>
           		<input type="hidden" name="stock" value="0" id="totalstock" />
					  </div><!-- /.box -->
					  
					   <div class="col-md-6">
					  
					   </div>
					   
					
					
                </div>	
            </div>
			
			 
					
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                        $(function () {
                            $('.chosen-select').chosen();
	
	
$("#branch_id").change(function(){
   getReagent();
});

$(document).on('keydown', '.calution', function(e) {		
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });	
	
function getReagent(){
		
		$("#loader_div").show();
		
        var id = $('#branch_id').val();
		
        $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/getbranchmachin",
            type:"POST",
            data:{branch_fk:id},
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
            }
        });
    }

$(document).on('change','#machine', function(){
	var machinid=this.value;
	$("#loader_div").show();
	 $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/getmachinagent",
            type:"POST",
            data:{machin:machinid},
            success:function(data){
                $('#reagent').html(data);
                $('#reagent').trigger("chosen:updated");
				
				$('#batchno').html("<option value=''>Select Batch no</option>");
                $('#batchno').trigger("chosen:updated");
				$("#getstocks").html("");
				$("#totalstock").val("0");
				if(this.value != ""){ $("#errormachine").html(""); }
				 $("#loader_div").hide();

            }
        });
		
});

$(document).on('change','#reagent', function(){
	var machinid=this.value;
	$("#loader_div").show();
	 $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/getreagentbanch",
            type:"POST",
            data:{reagent:machinid},
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
$(document).on('change','#batchno', function(){
	
var getstcoks=$('option:selected', this).attr('getstock');
$("#getstocks").html("Available stock("+getstcoks+")");
$("#totalstock").val(getstcoks);
if(this.value != ""){ $("#errorbatch").html(""); }

});

$("#poform").submit(function(){
	
	
		
var baranch= $("#branch_id").val();
var machine= $("#machine").val();
var reagent= $("#reagent").val();
var batchno= $("#batchno").val();
var quantity= $("#quantity").val();
var totalstock= parseInt($("#totalstock").val());


var check=0;
 if(baranch == ""){ $("#errorbranch").html("Branch field is required"); var check=1; }else{ $("#errorbranch").html(""); }
 if(machine == ""){ $("#errormachine").html("Machine field is required"); var check=1;  }else{ $("#errormachine").html(""); }
  if(reagent == ""){ $("#errorreagent").html("Reagent field is required"); var check=1; }else{ $("#errorreagent").html(""); }
 if(batchno == ""){ $("#errorbatch").html("Batch no field is required"); var check=1;  }else{ $("#errorbatch").html(""); }
  if(quantity == ""){ $("#errorqunty").html("Quantity field is required"); var check=1; }else{ 
  
   if(parseInt(quantity) <= 0 || totalstock < parseInt(quantity) ){
  
  $("#errorqunty").html("Invalid quantity"); var check=1;
  }else{ $("#errorqunty").html("");  }
  
  }
 if(check == 0){

 $("#loader_div").show();
 
 $.ajax({
            url:"<?php echo base_url();?>inventory/indent_usereagent/addusestock",
            type:"POST",
            data:{branch_fk:baranch,machine:machine,reagent:reagent,batchno:batchno,quantity:quantity,},
            success:function(data){
				
			 $("#loader_div").hide();
			
			if(data==1){ window.location.href="<?= base_url()."inventory/indent_usereagent" ?>"; }
               
            }
        });
 
 }
 return false; 

})

      });                 
			
</script> 