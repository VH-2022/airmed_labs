
<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        PRO Update
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>pro_master/pro_list"><i class="fa fa-users"></i>PRO List</a></li>
        <li class="active">Edit PRO</li>
    </ol>
</section>
					
<section class="content">
    <div class="row">
        <div class="col-md-12">
<?php 
					
					foreach($query as $data)
						$id = $data['id'];
					{
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
                <form role="form" action="<?php echo base_url(); ?>Pro_master/pro_edit/<?= $id ?>" method="post" enctype="multipart/form-data">
					
		
					<div class="form-group">
					
						<label for="name">PRO Name</label><span style="color:red">*</span>
						<input type="text"  name="name" class="form-control" value="<?php echo $data['pro_name']; ?>" >
						<?php echo form_error('name');?>
						
					</div>

                    <div class="form-group">
					
						<label for="designation">PRO Designation</label><span style="color:red">*</span>
						<input type="text"  name="designation" class="form-control" value="<?php echo $data['pro_designation']; ?>" >
						<?php echo form_error('designation');?>
						
					</div>
					<div class="form-group">
					
						<label for="name">PRO Mobile</label><span style="color:red">*</span>
						<input type="text"  name="mobile" class="form-control" value="<?php echo $data['pro_mobile']; ?>" >
						<?php echo form_error('mobile');?>
						
					</div>

                    <div class="form-group">
					
						<label for="name">PRO Email</label><span style="color:red">*</span>
						<input type="text"  name="email" class="form-control" value="<?php echo $data['pro_email']; ?>" >
						<?php echo form_error('email');?>
						
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