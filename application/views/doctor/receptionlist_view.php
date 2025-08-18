<div class="content-wrapper" id="extension-settings">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
    Reception list
        </h1>
        <ol class="breadcrumb">
           <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li>
            <li>Reception list</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
			
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ADD Reception</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php $success = $this->session->flashdata('success'); ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-autocloseable-success">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->userdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>
						
						 <?php  $cmapurl="doctor/reception"; 						 
						 
						 echo form_open($cmapurl,array("method" => "POST", "role" => "form","class"=>'form-horizontal',"id"=>'campingform' )); ?>
	
	<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Reception Name<span style="color:red">*</span></label>
					  <div id="cnameerror"  class="col-sm-4">
                     <?php echo form_input(['name' => 'name','id'=>'campname', 'class' => 'form-control', 'placeholder' => 'Reception Name', 'value' => set_value('name')]); ?>
                           <span style="color: red;"><?=form_error('name');?></span>
					 </div>
                    </div>
					
				<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Reception Mobile <span style="color:red">*</span></label>
					  <div class="col-sm-4">

					 <input type="text" name="mobile" placeholder="Mobile" class="form-control" value="<?= set_value('mobile'); ?>" />
					  <span style="color: red;"><?=form_error('mobile');?></span>
					 </div>
                    </div>	
	
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name="submit" class="btn btn-success" value="Submit" />
		
      </div>
	  
    </div>
 <?php echo form_close();  ?>
					
			          
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
				
				
				 <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?= $pagename ?> List</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
						
						  <?php echo form_open("doctor/reception/index", array("method" => "GET")); ?>
								
								<div class="form-group">
                                    <div class="col-sm-2" style="margin-bottom:10px;" >
									
                                         <input type="text" id="" name="search" class="form-control" placeholder="Search" value="<?= $this->input->get('search'); ?>"> 
										 
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <div class="col-sm-3" style="margin-bottom:10px;" >
									<button type="submit"  class="btn btn-sm btn-primary" >Search</button>

                                        <a type="button" href="<?= base_url()."doctor/reception" ?>" class="btn btn-sm btn-primary" ><i class="fa fa-refresh"></i> Reset</a>
										
										
									</div>
								</div>	


                               

                                </form>
                           
                        </div>
					
			            <div class="tableclass">
                            <?php echo form_open($cmapurl, array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
										 <th>Mobile</th>
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
											<td><?php echo $row['mobile']; ?></td>
											
											<td>  
                                                <a  href='<?php echo base_url()."doctor/reception/edit/".$row['id']; ?>' data-original-title="edit" data-toggle="tooltip" <i style="margin-left:3px; "class="fa fa-edit"> </i> </a>  
                                                
												
												<a  href='<?php echo base_url(); ?>doctor/reception/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a>  
												
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if ($cnt == '0') {
                                        ?>
                                        <tr>
                                            <td colspan="3"><center>No records found</center></td>
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
                            <?php echo form_close(); ?>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
				
				
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script type="text/javascript">
/* $(document).ready(function(){
    $('#campingform').on('submit', function(e){
		
       
        var len = $('#campname').val();
		 $("#cnameerror").removeClass("has-error");
		var len1=len.trim();
        if (len != ""){
			 this.submit();
        }else{
			$("#cnameerror").addClass("has-error");
			 e.preventDefault();
		}
    });
}); */
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);</script>