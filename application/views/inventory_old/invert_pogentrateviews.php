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
        Po Generate
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"><i class="fa fa-users"></i>Indent Request </a></li>
        <li class="active">Po Generate</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
			
			 <div class="box-body">

                    <div class="col-md-6">
					
					<?php  echo form_open("inventory/Intent_Request/poigenerate/".$indedrequiest[0]["id"], array("method" => "POST", "role" => "form","id"=>'poform'));  ?>
           
				    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Reagent:</label>
                        <select class="chosen chosen-select new_update" name="category_fk" id="selected_item" onchange="select_item('Reagent',this)">
                            <option value="">--Select--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Lab Consumables:</label>
                        <select class="chosen chosen-select" name="category_fk" id="consumer_id" onchange="select_item('Consumables',this);">
                            <option value="">--Select--</option>
                            <?php
 
                            foreach ($lab_consum as $mkey) {
                                $lab_consum = $lab_consum . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="item_list" value="<?= htmlspecialchars($lab_consum, ENT_QUOTES); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Stationary:</label>
                        <select class="chosen chosen-select" name="category_fk" id="yahoo_id" onchange="select_item('Stationary',this);">
                            <option value="">--Select--</option>
                            <?php
 
                            foreach ($stationary_list as $mkey) {
                                $stationary_list = $stationary_list . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
                            <?php } ?>
                        </select>
						<span id="erroriteam" style="color: red;"></span>
       
                    </div>
					
           
			
			<div id="itemtext" style="display:none">
			<select class="form-control calution1" name="itemtext[]" >
                            <option value="0">--Select Tax--</option>
                            <?php
						foreach ($itemtext as $txt) {
                                ?>
                                <option rel="<?= $txt["tax"]; ?>" value="<?= $txt["id"] ?>"><?= $txt["title"]."(".$txt["tax"]."%)"; ?></option>
                            <?php } ?>
                        </select>
						
					</div>
					<div id="itemvendor" style="display:none">
			<select class="form-control" name="vendorname[]" >
                            <option value="">--Select Vendor--</option>
                            <?php
						foreach ($vendor_list as $ven) {
                                ?>
                                <option  value="<?= $ven["id"] ?>"><?= $ven["vendor_name"]; ?></option>
                            <?php } ?>
                        </select>
						<span id="" class="vendorerror" style="color:red;"></span>
					</div>
					
					  </div><!-- /.box -->
					  
					   <div class="col-md-6">
					  
					   </div>
					   
					   <div class="table-responsive pending_job_list_tbl">
					     
						   <table class="table-striped">
                        <thead>
                            <tr>
								 <th>No</th>
								 <th>Vendor</th> 
								 <th>Category</th> 
                                <th>Item</th>
                                <th>NOS</th>
								<th>Qty.</th>
                                <th>Rate per Test</th>
								 <th>Amount Rs.</th>
								 <th>Discount(%)</th>
								 <th>TAX</th>
                                <th>TOTAL PAYABLE</th>
								<th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="selected_items">
						
						<?php $cnt=0; $totalprice=0;
						foreach($poitenm as $row){
						$cnt++; 
						$itetype=$row["cattype"];
						$whotype="";
						if($itetype=='1'){ $whotype="Stationary"; $cat="Stationary"; }
						if($itetype=='2'){ $whotype="Consumables"; $cat="Lab Consumables"; }
						if($itetype=='3'){ $whotype="Reagent"; $cat="Reagent"; }
						
						
						if($row["box_price"] != ""){ $itemprice=$row["box_price"]; }else{ $itemprice=0; }
						
						$itrmtotal=round($itemprice*$row["qty"]*$row["quantity"]);
						
						$totalprice +=$itrmtotal;
						
						?>
						
						<tr id="tr_<?= $cnt; ?>">
						<td><?= $cnt; ?></td>
						<td><p id="vendorcontainid_<?= $cnt; ?>">
						<select class="form-control" name="vendorname[]" >
                            <option value="">--Select Vendor--</option>
                            <?php
						foreach ($vendor_list as $ven) {
                                ?>
                                <option  value="<?= $ven["id"] ?>"><?= $ven["vendor_name"]; ?></option>
                            <?php } ?>
                        </select>
						<span id="" class="vendorerror" style="color:red;"></span>
						</p>
						</td>
						<td><?= $cat; ?></td>
						<td><?= $row["reagent_name"]; ?><input type="hidden" name="item[]" value="<?= $row["itemid"]; ?>"></td>
						<td><input type="text" name="nos[]" id="nos_<?= $cnt; ?>" value="<?= $row["quantity"]; ?>" class="form-control calution"/></td>
						<td><p id="totalqty_<?= $cnt; ?>"><?= $row["qty"]; ?></p></td>
						<td><input type="text" id="rateqty_<?= $cnt; ?>" value="<?= $itemprice; ?>"  name="rateqty[]" class="form-control calution" /></td>
						<td><p id="testamount_<?= $cnt; ?>"><?= $itrmtotal; ?></p></td>
						<td><input type="text" id="itemdis_<?= $cnt; ?>" value="0" name="itemdis[]" class="form-control calution"/><span id="errorperdis_<?= $cnt; ?>" style="color: red;"></span></td>
						<td><p id="txtid_<?= $cnt; ?>"><select class="form-control calution1" name="itemtext[]" >
                            <option value="0">--Select Tax--</option>
                            <?php
						foreach ($itemtext as $txt) {
                                ?>
                                <option  rel="<?php echo $txt["tax"]; ?>" value="<?= $txt["id"] ?>"><?= $txt["title"]."(".$txt["tax"]."%)"; ?></option>
                            <?php } ?>
                        </select></p></td>
						<td>
						<input type="text" id="totalamount_<?= $cnt; ?>" disabled name="totalamount[]" value="<?= $itrmtotal ?>" class="form-control"/>
						<span id="errortamount_<?= $cnt; ?>" class="" style="color:red;"></span>
						</td>
						<td><a href="javascript:void(0);" onclick="delete_city_price('<?= $cnt; ?>','<?= $row["reagent_name"]; ?>','<?= $row["itemid"]; ?>','<?= $whotype; ?>')"><i class="fa fa-trash"></i></a></td>
						</tr>
						<?php  } ?>
                        </tbody>
						
						<tr>
						<td colspan='9'></td>
						<td>Total Amount Rs.</td>
						<td colspan='2'><input id="maintotalprice" type="text" readonly name="maintotal" value="<?= $totalprice; ?>" /></td>
						</tr>
                    </table>
					
			
					</div> 
					
					
                </div>	
            </div>
			
			 <div class="form-group">
              <button type="submit" class="btn btn-primary">Submit</button>
             
            </div>
					
					 <?php echo form_close(); ?>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                        $(function () {

                            $('.chosen-select').chosen();
							
							

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
 $city_cnt = 0;			
	
	 function select_item(val,id) {
       
        var skillsSelect = id;
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text; 
        var prc = selectedText;
        var pm = skillsSelect.value;
		var explode = pm.split('-');
		
		 $("#loader_div").show();
		 
		   var old_dv_txt = $("#hidden_test").html();
		    var $city_cnt = $('#selected_items').children('tr').length;
        $city_cnt = $city_cnt + 1;
		var catgory="";
        if(val=="Reagent"){
             $("#selected_item option[value='" + skillsSelect.value + "']").remove();
			 var catgory="Reagent";
        }
        if(val == "Consumables"){
         $("#consumer_id option[value='" + skillsSelect.value + "']").remove();
		  var catgory="Lab Consumables";
        }
       
       if(val == "Stationary"){
        $("#yahoo_id option[value='"+ skillsSelect.value +"']").remove();
		var catgory="Stationary";
        }
       $('.chosen').trigger("chosen:updated");
	   
        $.ajax({
            url:"<?php echo base_url();?>inventory/intent_genrate/getitenqty",
            type:"GET",
			dataType: "json",
            data:{itenid:pm},
            success:function(data){
				
				var itemtext = $('#itemtext').html();
				
				var itemvendor=$('#itemvendor').html();
				if(data.price != null && data.price != ""){ var iprice=data.price; }else{ var iprice="0" }
				
			$("#selected_items").append('<tr id="tr_' + $city_cnt + '"><td>'+$city_cnt+'</td><td><p id="vendorcontainid_'+$city_cnt+'">'+itemvendor+'</p></td><td>'+catgory+'</td><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td><input type="text" name="nos[]" id="nos_'+$city_cnt+'" value="1" class="form-control calution"/></td><td><p id="totalqty_' + $city_cnt + '">'+data.qty+'</p></td><td><input type="text" id="rateqty_'+$city_cnt+'" value="'+iprice+'" name="rateqty[]" class="form-control calution"/></td><td><p id="testamount_'+$city_cnt+'">0</p></td><td><input type="text" id="itemdis_'+$city_cnt+'" name="itemdis[]" value="0" class="form-control calution"/><span id="errorperdis_'+$city_cnt+'" style="color: red;"></span></td><td><p id="txtid_'+$city_cnt+'">'+itemtext+'</p></td><td><input type="text" id="totalamount_'+$city_cnt+'" disabled name="totalamount[]" class="form-control"/><span id="errortamount_'+$city_cnt+'" class="" style="color:red;"></span></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\',\'' + val + '\')"><i class="fa fa-trash"></i></a></td></tr>');
			
			tablecal("rateqty_"+$city_cnt);
			
			$("#loader_div").hide();
               
            }
        });
	   
        
       
     
    }
	function delete_city_price(id, name, value,selec) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
			
	if(selec=="Reagent"){  $('#selected_item').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");   }
    if(selec == "Consumables"){ $('#consumer_id').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");  }
    if(selec == "Stationary"){ $('#yahoo_id').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");  }
		$("#tr_" + id).remove();
        
		}
		
		maintotalcchange();
    
	}
	
	$(document).on('keyup', '.calution', function() {
		
		tablecal(this.id);
		
		
});
function tablecal(tid){
	
		var curow=tid;
		var explode = curow.split('_');
		var textid=explode[0];
		var trid=explode[1];
		var nos=parseFloat($("#nos_"+trid).val());
		if(nos > 0){ var nos=nos; }else{ var nos=1; }
		var qty=1;
		var ratepertest=parseFloat($("#rateqty_"+trid).val());
		 var itemdis =parseFloat($("#itemdis_"+trid).val());
		 
		 $("#errorperdis_"+trid).html("");
		if(itemdis >= 0 && itemdis < 99){ var perdiscount=parseFloat(itemdis); }else{ $("#errorperdis_"+trid).html("Please enter valid discount"); var perdiscount=0; }
		
		if(ratepertest != "" && ratepertest > 0){
			var disamount=(qty*ratepertest)*nos;
			
		 var multiqty=(disamount-disamount*perdiscount/100);
		var disamount1=disamount.toFixed(2);
		$("#testamount_"+trid).html(disamount1);
		var itemtxt=parseFloat($("#txtid_"+trid+" select option:selected").attr('rel'));
		if(itemtxt > 0){	
		
		var totalamount=(multiqty+(multiqty*itemtxt/100));
		var totalamount1=totalamount.toFixed(2);
		$("#totalamount_"+trid).val(totalamount1);
		
		}else{ 
		var multiqty1=multiqty.toFixed(2);
		$("#totalamount_"+trid).val(multiqty1); 
		}
		
		}else{ 
		$("#testamount_"+trid).html('0'); 
		$("#totalamount_"+trid).val('0');
		}
		
	maintotalcchange();
	
}
$(document).on('change','.calution1', function() {
		var itemtxt=parseFloat($('option:selected', this).attr('rel'));
		var curow=$(this).parent().attr("id");
		var explode = curow.split('_');
		var textid=explode[0];
		var trid=explode[1];
		var tamount1=parseFloat($("#testamount_"+trid).html());
		var itemdis =parseFloat($("#itemdis_"+trid).val());
		
		if(itemdis >= 0 && itemdis < 99){ var perdiscount=parseFloat(itemdis); }else{ var perdiscount=0; }
		
		var tamount=(tamount1-tamount1*perdiscount/100);
		
		if(tamount > 0 && itemtxt > 0){
			
		var totalamount=(tamount+(tamount*itemtxt/100));
		$("#totalamount_"+trid).val(totalamount.toFixed(2));
		
		}else{
		$("#totalamount_"+trid).val(tamount.toFixed(2));
		}
		
	maintotalcchange();
		
});	
	function maintotalcchange(){
		var dis1=parseFloat($("#pricediscount").val());
		$("#errordisccount").html("");
		if(dis1 > 0 ){
		if(dis1 < 99){ var dis=dis1; }else{ $("#errordisccount").html("Please enter valid discount"); dis=0; }
	}else{ var dis=0; }
	
	var total = 0;
	
	$("input[name='totalamount[]']").each(function (index, element) {
        
		if(parseFloat($(element).val()) > 0 ){ total = total + parseFloat($(element).val()); }
		
    });
	
	
    var discountamount=(total-(total*dis/100));
	
	$("#maintotalprice").val(discountamount);
	
	}
$(document).on('keyup','#pricediscount', function() {
	var dis1=this.value;
	$("#errordisccount").html("");
	if(dis1 > 0 ){
		if(dis1 < 99){ var dis=dis1; }else{ $("#errordisccount").html("Please enter valid discount"); dis=0; }
	}else{ var dis=0; }
		var total = 0;
    $("input[name='totalamount[]']").each(function (index, element) {
       if(parseFloat($(element).val()) > 0 ){  total = total + parseFloat($(element).val()); }
    });
    var discountamount=(total-(total*dis/100));
	
	$("#maintotalprice").val(discountamount);
		
	
	
	
});	

	$("#poform").submit(function(){
		
  var pricetotal=$("#maintotalprice").val();
  var pricediscount=$("#pricediscount").val();
  
  var check=0;
if(pricetotal > 0){ $("#erroriteam").html(""); }else{ $("#erroriteam").html("Please add items"); var check=1;  }
 
 
 if(check == 0){
	 

var nosval= new Array();
$("input[name='nos[]']").each(function(){
    nosval.push($(this).val());
});  

var item= new Array();
$("input[name='item[]']").each(function(){
    item.push($(this).val());
});  

var rateqty= new Array();
$("input[name='rateqty[]']").each(function(){
    rateqty.push($(this).val());
});


var itemdis= new Array();
$("input[name='itemdis[]']").each(function(){
    itemdis.push($(this).val());
});

var itemtext= new Array();
var it=0;
$("select[name='itemtext[]']").each(function(){
	if(it != 0){
    itemtext.push($(this).val()); }
	it++;
});

var itemvendor= new Array();
var itemerrorvendor= new Array();
var it1=0;
$("select[name='vendorname[]']").each(function(){
	
	if(it1 != 0){
		
		var vendorid=$(this).val();
		var curow1=$(this).parent().attr("id");
		var explode1 = curow1.split('_');
		var errid=explode1[1];
		
	 if(vendorid == ""){ itemerrorvendor.push(1); $("#vendorcontainid_"+errid+" .vendorerror").html("Vendor field is required"); }else{ $("#vendorcontainid_"+errid+" .vendorerror").html("");  } 
    itemvendor.push($(this).val()); }
	it1++;
});

     $("input[name='totalamount[]']").each(function (index, element) {
		var totalid=this.id;
		var explode1 = totalid.split('_');
		var err1id=explode1[1];
		
       if(parseFloat($(element).val()) > 0 ){ $("#errortamount_"+err1id).html("");  }else{ itemerrorvendor.push(1); $("#errortamount_"+err1id).html("Invalid price in total amount"); }
	  
    });

if(itemerrorvendor == null || itemerrorvendor == ''){

	   $("#loader_div").show();
		 
	   $.ajax({
            url:"<?php echo base_url();?>inventory/intent_Request/poigenerateadd/<?= $indedrequiest[0]["id"]; ?>",
            type:"POST",
            data:{itemvendor:itemvendor,maintotal:pricetotal,pricediscount:pricediscount,item:item,itemtext:itemtext,nosval:nosval,rateqty:rateqty,itemdis:itemdis},
            success:function(data){
				
			$("#loader_div").hide();
			if(data==1){ window.location.href="<?= base_url()."inventory/intent_genrate/index" ?>"; }
            }
        });
		
}	
		
		 return false; 
		 
 }else{
	
	 return false; 
 }
  
 });
 
  function getReagent(branchid){
							 
        var id =branchid;
        $.ajax({
            url:"<?php echo base_url();?>inventory/Intent_Request/getsub",
            type:"POST",
            data:{branch_fk:id},
            success:function(data){
				$('.new_update').html(data);
                $('.chosen').trigger("chosen:updated");

            }
        });
    }
	setTimeout(function(){
	getReagent('<?= $indedrequiest[0]["branch_fk"]; ?>');
	},
	 2000);
 
</script> 