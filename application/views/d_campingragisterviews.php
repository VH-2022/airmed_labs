<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
</style>	
<div class="content-wrapper" id="extension-settings">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          <?= $campdatils->name." ".$pagename ?>
        </h1>
		 <?php 
		 $doctid=$dockdetils->id;
		 if($camptype==1){ $cmapurl="camping/index/$doctid"; }else{ $cmapurl="camping/society/$doctid"; } ?>
        <ol class="breadcrumb">
           <li><a href="<?php echo base_url()."dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li>
		   <li><a href="<?php echo base_url().$cmapurl; ?>"><i class="fa fa-dashboard"></i>Camp</a></li>
            <li>Camp List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
			
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?= $campdatils->name." ".$pagename ?> Registration</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php $success = $this->session->flashdata('success'); ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-autocloseable-success">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success ?>
                                </div>
                            <?php } ?>
                        </div>
					
			         <?php  echo form_open("camping/register/".$campdatils->id,array("method" => "POST", "role" => "form","class"=>'form-horizontal',"id"=>'reportform' )); ?>
	
	<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Patient name<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="pname" value="<?= set_value("pname"); ?>" placeholder="Patient name" class="form-control" id="pname"   />
					  <span style="color: red;"><?= form_error('pname'); ?></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Mobile no<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="mobile" placeholder="Mobile no" value="<?= set_value("mobile"); ?>" class="form-control" id="mobile"   />
					   <span style="color: red;"><?= form_error('mobile'); ?></span>
					 </div>
                    </div>
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Patient Age<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <input type="text" name="paintage" placeholder="Patient Age" value="<?= set_value("paintage"); ?>" class="form-control" id="paintage"   />
					   <span style="color: red;"><?= form_error('paintage'); ?></span>
					 </div>
                    </div>
					 <?php if($camptype==2){ 
					 
					 $testselectall=$this->input->post("testall");
					if($testselectall != ""){ $testselect=$testselectall; }else{ $testselect=array(); } 
					
					
					 ?>
					 
					 <div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Test/Package<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                     <select class="chosen-select" multiple  name="testall[]" data-live-search="true" id="test" data-placeholder="Select Test" >
    <?php
    foreach ($test as $ts){
    ?>
        <option value="t-<?php echo $ts['id']; ?>" <?php if(in_array("t-".$ts['id'],$testselect)){ echo "selected"; } ?>> <?php echo ucfirst($ts['test_name']); ?></option>
<?php } foreach ($packges as $ts) {
        ?>
        <option value="p-<?php echo $ts['id']; ?>" <?php if(in_array("p-"	.$ts['id'],$testselect)){ echo "selected"; } ?>> <?php echo ucfirst($ts['test_name']); ?></option>

<?php } ?>
</select>

					   <span style="color: red;"><?= form_error('testall[]'); ?></span>
					 </div>
                    </div>
					 
					 <?php } ?>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Gender<span style="color:red">*</span></label>
					  <div class="col-sm-4">
                    <div class="checkbox">
                      <label>
                        <input checked name="pgender" value="1" type="radio"> Male
                      </label>
					  <label>
                        <input name="pgender" value="2" type="radio"> Female
                      </label>
                    </div>
					   <span style="color: red;"><?= form_error('pgender'); ?></span>
					 </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-sm-2" for="exampleInputEmail1">Remark</label>
					  <div class="col-sm-4">
                     <textarea name="remark" placeholder="Remark" class="form-control"><?= set_value("remark"); ?></textarea>
					  <span style="color: red;"><?= form_error('remark'); ?></span>
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
                        <h3 class="box-title"> <?= $campdatils->name." ".$pagename ?> Registration List</h3>
                        
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            
                        </div>
				<?php  if($query != null){ ?>
						<a style="float:right;margin-right:5px;" href="<?= base_url()."camping/campragister_csv?cid=".$campdatils->id; ?>" class="btn btn-primary btn-sm"><strong> Export CSV</strong></a>
						<?php }  ?>
                        <div class="tableclass">
                           
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
										 <th>Mobile</th>
										  <th>Age</th>
										  <th>Gender</th>
										<?php if($camptype==2){ ?>  <th>Test/Package </th> <?php } ?>
										<th>Remark</th>
										<th>Added by</th>
										<th>Date</th>
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
                                            <td><?php echo $row['name']; ?></td>
											<td><?= $row['mobile']; ?></td>
											<td><?= $row['age']; ?></td>
											<td><?php if( $row['gender']==1){ echo "Male"; }else{ echo "Female"; }  ?></td>
										<?php if($camptype==2){ ?>	<td><?= $row['test']; ?></td> <?php } ?>
											<td><?= $row['remark']; ?></td>
											<td><?= $row['addedby']; ?></td>
											<td><?= date("d-m-Y",strtotime($row['createddate'])) ?></td>
                                            <td>  
                                               <a  href='<?php echo base_url(); ?>camping/campregisterdelete/<?php echo $row['camp_fk']."/".$row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a>  
												
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
                           
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
				
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script type="text/javascript">
  jQuery(".chosen-select").chosen({
    });
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);</script>