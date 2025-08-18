<!-- Page Heading -->
<?php /* <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> */ ?>
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
<section class="content-header">
    <h1>
         Receive payment add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <?php  /* <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list"><i class="fa fa-users"></i>Branch List</a></li> */ ?>
        <li class="active">Receive payment add</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">

                <div class="box-body">
				
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success[0]; ?>
                                </div>
                            <?php } ?>

                        </div>
						<h3 id="dueamountid" class="pull-right" style="display:none"><b style="color:red;">Due:</b> Rs.<span id="pricedue">0</span>	</h3>
                    <div class="col-md-6">
					 
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url()."Receive_payment/add"; ?>" method="post" >

                            <div class="form-group">
                                <label for="type">Lab</label><span style="color:red">*</span>

                                <select class="form-control" id="labidname"  name="labname">
                                    <option value=""> Select Lab</option>
									<?php foreach($lablist as $bran){ ?>
									<option value="<?= $bran->id; ?>" <?php echo set_select('labname',$bran->id);  ?> ><?= ucwords($bran->name); ?></option>
									<?php } ?>
                                    
                                </select>
                                <?php echo form_error('labname'); ?>
                            </div>
							
							 <div class="form-group">
                                <label for="name">Date</label><span style="color:red">*</span>
                                <input type="text"  name="paydate" class="form-control" id="paydate" placeholder="Date" value="<?php echo date("d-m-Y"); ?>" >

                                <?php echo form_error('paydate'); ?>
                            </div>
							
							<div class="form-group">
                    <label for="recipient-name" class="control-label">Amount <span style="color:red">*</span></label>
                    <input type="text" name="amount" value="<?php echo set_value('amount'); ?>"  class="form-control number"/>
					<?php echo form_error('amount'); ?>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Payment Mode:</label>
                    <select class="form-control" name="type">
                        <option value="cash">Cash</option>
                        <option value="bank-deposit">Bank Deposit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Note:</label>
                    <textarea class="form-control" name="note"></textarea>
                </div>

                    </div>
                </div>

                <div class="box-footer">
                    <div class="col-md-6">
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </div>
                </form>
            </div><!-- /.box -->

        </div>
    </div>
</section>
  <script>
  $( function() {
    $( "#paydate" ).datepicker({ format: 'dd-mm-yyyy',endDate: '+0d'});
	
	$('#labidname').on('change', function() {
		if(this.value != ""){
			$("#loader_div").show();
			$("#dueamountid").show();
 var paydate=$("#paydate").val();
  $.ajax({
        type:"POST",
        url:"<?php echo base_url()."receive_payment/lab_dueamount";?>",
        data:{paydate:paydate,lab_id:this.value},
         success:function(data){ 
		     if(data !=''){  $("#pricedue").html(data); $("#loader_div").hide(); }
			 
            }
       });
		}else{ $("#dueamountid").hide(); }
});
  } );
  
  </script>