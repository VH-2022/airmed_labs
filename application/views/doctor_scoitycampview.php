<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<?php /* <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> */ ?>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
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
           <?= $pagename ?> List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()."dashboard"; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $pagename ?> List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
		<?php $cfname = $this->input->get('cfname');
		$did= $this->input->get('did');
		$city= $this->input->get('city');

		?>	
				 <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?= $pagename ?> List</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                           
                        </div>
						
						<div class="widget">
						 <?php 
						 $cmapurl=base_url()."camping/societyadmin";
						 echo form_open($cmapurl, array("method" => 'GET')); ?>
						 
						 <div class="form-group">
                                    <div class="col-sm-2" style="margin-bottom:10px;" >
									
                                         <?php echo form_input(['name' => 'cfname', 'class' => 'form-control', 'placeholder' => 'Name', 'value' => $cfname]); ?>
										 
                                    </div>
                                </div>
								
									<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
                                         <select name="city" id="city" class="form-control chosen-select">
                                        <option value="">Select City</option>
                                        <?php foreach ($city_list as $val) { ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if($city == $val['id']){ echo "selected"; } ?>  ><?php echo ucwords($val['name']); ?></option>
<?php } ?>
                                    </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <div class="col-sm-3" id="getdocto" style="margin-bottom:10px;" >
									
                                         <select name="did"  class="form-control chosen-select">
										  <option value="">Select Doctor</option>
                                       
                                    </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
									<button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                                        <a type="button" href="<?= base_url()."camping/societyadmin" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>
										
										
									</div>
								</div>	
								
								
								 <?php echo form_close(); ?>
						</div>
					<br>
			            <div class="tableclass">
                           
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
										 <th>Remark</th>
										 <th>Doctor Name</th>
										 <th>Added by</th>
										<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                    <?php
                                    $cnt = $pages;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo ucfirst($row['name']); ?></td>
											<td><?php echo ucfirst($row['remark']); ?></td>
											<td><?php echo ucfirst($row['full_name']); ?></td>
											<td><?php echo $row['addedby']; ?></td>
											<td>  
                                               
												<a target="_blank" href='<?php echo base_url()."camping/register/".$row['id']; ?>' data-original-title="Registration" data-toggle="tooltip" /><?= $row['totalragister']; ?> </a>
												
												
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if ($cnt == '0') {
                                        ?>
                                        <tr>
                                            <td colspan="4"><center>No records found</center></td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>
                           
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
				
				
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script>
 $(function () {

                        $('.chosen-select').chosen();
						 
						$('#city').on('change', function() {
							$("#loader_div").show();
							var didval="<?= $did ?>";
  $.ajax({
                url: '<?php echo base_url(); ?>camping/getcitydoctor',
                type: 'get',
                data: {did:this.value,didval:didval},
                success: function (data) {

                    $("#getdocto").html(data);
					$('.chosen-select').chosen();
					 $("#loader_div").hide();
                }
            });
})
<?php if($city != ""){ ?>
$('#city').change();
<?php } ?>
                    });
</script>