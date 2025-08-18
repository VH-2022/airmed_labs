<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Tech Revenue Collection Update
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_list"><i class="fa fa-users"></i>Tech Revenue Collection List</a></li>
        <li class="active">Edit Tech Revenue Collection</li>
    </ol>
</section>
					
<section class="content">
    <div class="row">
        <div class="col-md-12">
<?php 
					foreach($query as $data){
						$id = $data['id'];
						$branchid = $data['branch_fk'];
						$collectiontype = $data['collection_type'];
						$collectionvalue = $data['collection_value'];
					
				?>
            <div class="box box-primary">
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
					
					
                    ?></p>
		<div class="box-body">
				<div class="col-md-6">
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_edit/<?= $id ?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="techbranch">Airmed Tech Branch</label><span style="color:red">*</span>
						<select class="form-control chosen-select" name="techbranch">
                            <option value="">--Select--</option>
							<?php foreach ($techbranch as $val) { ?>
								<option value="<?php echo $val['id'];?>" <?php if($branchid==$val['id']){?>selected<?php } ?>><?php echo $val['branch_name'];?></option>
							<?php } ?>					                                                      
						</select>
						<span style="color: red;"><?= form_error('techbranch'); ?></span>
					</div>					
					<div class="form-group">
						<label for="collectiontype">Collection Type</label><span style="color:red">*</span>
						<select class="form-control chosen-select" name="collectiontype">
                            <option value="">--Select--</option>
							<option value="1" <?php if($collectiontype==1){?>selected<?php } ?>>Fixed</option>
							<option value="2" <?php if($collectiontype==2){?>selected<?php } ?>>Percentage</option>							
						</select>
						<span style="color: red;"><?= form_error('collectiontype'); ?></span>
					</div>
					<div class="form-group">
						<label for="collectionvalue">Collection Value</label><span style="color:red">*</span>
						<input type="text"  name="collectionvalue" class="form-control" value="<?=$collectionvalue;?>">
						<?php echo form_error('collectionvalue');?>
					</div>
                    <button class="btn btn-primary" type="submit">Update</button>
					<input type="hidden"  name="id" class="form-control" value="<?php echo $id; ?>" >
				</div>
				
                </form>
			<?php 
				}
			?> 
            </div><!-- /.box -->
	
        </div>
    </div>
</section>
<script type="text/javascript">
	window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>