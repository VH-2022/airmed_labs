<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Tech Revenue Collection List<small></small>
        </h1>
        <ol class="breadcrumb">
           <li><a href="<?php echo base_url(); ?>Dashboard1"><i class="fa fa-dashboard"></i>Dashboard</a></li>
 
            <li><i class="fa fa-users"></i>Tech Revenue Collection List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Tech Revenue Collection List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <!--<a style="float:right;" href='<?php echo base_url(); ?>test_master/test_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
							<?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success')[0]; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('Techrevenuecollection_master/techrevenuecollection_list', $attributes); ?>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" value="<?=$search;?>" placeholder="search"/>							
                        </div>
						<div class="col-md-3">
							<select class="form-control chosen-select" name="collectiontype">
								<option value="">--Select--</option>
								<option value="1" <?php if($collectiontype==1){?>selected<?php } ?>>Fixed</option>
								<option value="2" <?php if($collectiontype==2){?>selected<?php } ?>>Percentage</option>								
							</select>
						</div>
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
						<a href="<?= base_url()."Techrevenuecollection_master/techrevenuecollection_list"; ?>" class="btn btn-primary btn-md">Reset</a>
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
										<th>Airmed Tech Branch</th>
										<th>Collection Type</th>
										<th>Collection Value</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									$cnt = 1;
									foreach ($query as $row) {
										
										?>
                                        <tr> <td><?php echo $cnt; ?></td>													
											<td><?php echo ucwords($row['branch_name']); ?></td>
											<td><?php echo ucwords(($row['collection_type']==1)?"Fixed":"Percentage"); ?></td>
											<td><?php echo ucwords($row['collection_value']); ?></td>											
                                            <td>
												<a href='<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_viewlogs/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View_Logs"><i class="fa fa-file-archive-o"></i></a> 
												<a href='<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>												
                                            </td>
                                        </tr>
										  <?php $cnt++; ?>
										<?php }if (empty($query)) {
										?>
                                        <tr>
                                            <td colspan="5">No Records Found.</td>
                                        </tr>
										<?php } ?>
                                </tbody>
                            </table>
							<?php ?>
                        </div>
						
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
								<?php echo $links; ?>
                            </ul>
                        </div>
						
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
	window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>