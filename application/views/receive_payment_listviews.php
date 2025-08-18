<style>
.full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }
</style>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Collect cash from Lab
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>receive_payment/receive_payment_list"><i class="fa fa-users"></i>Collect cash from Lab</a></li>

        </ol>
    </section>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<?php $laballsea=$this->input->get('lab'); ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Collect cash from Lab</h3>
                        <a href="<?= base_url()."receive_payment/add"; ?>" class="btn btn-sm btn-primary" style="float:right;">
                        <i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                                 
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						<?php $success=$this->session->flashdata("success"); ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>
							 <div  id="sucessmsg"></div>
							   <?php echo form_open("receive_payment/receive_payment_list", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
						<div class="col-sm-3">
                               <select name="lab[]" class="form-control">
							   <option value="">Select Lab</option>
							  <?php  foreach($lablist as $lab){ ?>
								   <option value="<?= $lab->id; ?>" <?php if($laballsea[0]==$lab->id){ echo "selected"; } ?> ><?= ucwords($lab->name); ?></option>
							  <?php  } ?>
							   </select>
                            </div>
                           <div class="col-sm-3">
                                <input type="text" name="startdate" placeholder="Start date" class="form-control" id="startdate"  value="<?= $this->input->get('startdate'); ?>" />
                            </div>
							<div class="col-sm-3">
                                <input type="text" name="enddate" placeholder="End date" class="form-control" id="enddate"  value="<?= $this->input->get('enddate'); ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close();  $serlab=$laballsea[0];  ?>
						<a href="<?= base_url()."receive_payment/payment_exportcsv?lab[]=$serlab&startdate=$startdate&enddate=$enddate"; ?>" class="btn btn-sm btn-primary"  style="float:right;">
                        <i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
							</div>
                      
                        <div class="tableclass">
                               <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
											<th>Lab Name</th>
											<th>Credit</th>
											<th>Type</th>
											<th>Date</th>
											<th>Note</th>
											<th>Created Date</th>
                                            <th>Added By</th>  
                                           <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = $page;
                                        foreach ($query as $row) {
											$cnt++;
											$month=$row->pay_date; $year=$row->year; 
                                            ?> 

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
												<td><?php echo $row->labname; ?></td>
												<td><?= "Rs." . $row->amount; ?></td>
												<td><?= $row->type; ?></td>
												<td><?php echo date("d-m-Y", strtotime($month)); /* date("F Y", strtotime("01-$month-$year")); */ ?></td>
													<td><?php echo $row->note; ?></td>
													<td><?php echo date("Y-m-d",strtotime($row->created_date)); ?></td>
                                                   
                                                 <td><?php echo ucwords($row->name); ?></td>
                                            
                                                <td>
                                                   <a  href="<?= base_url()."b2b/amount_manage/print_receipt/".$row->id;?>" id="" class="btn btn-sm btn-primary"><i class="fa fa-print"></i><strong> Print Receipt</strong></a>
												    <a  href="javascript:void(0)" id="payrecip_<?= $row->id; ?>" class="btn btn-sm btn-primary sentemail"><i class="fa fa-envelope-o"></i><strong> Send Receipt</strong></a>	
                                                </td>
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
<script>
	    $('#startdate,#enddate').datepicker({format: 'dd-mm-yyyy',
    autoclose: true
}).attr("autocomplete", "off");
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

</script>


