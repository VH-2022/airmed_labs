
<section class="content-header">
    <h1>
         <?= $pagename ?> Name Edit
        <small></small>
    </h1>
	 <?php $cmapurl="doctor/reception";  ?>
    <ol class="breadcrumb">
       <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Home</a></li>
        <li><a href="<?php echo $cmapurl ?>"><i class="fa fa-users"></i>  <?= $pagename ?> List</a></li>

        <li class="active">Edit <?= $pagename ?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-6">
                            <?php
                           
						   echo form_open('doctor/reception/edit/'.$query->id. '',['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target']);
                            ?>
                            <div class="form-group">
                                <label for="name">Name<span style="color:red;">*</span></label>
                                <?php
                                echo form_input(['name' => 'name', 'class' => 'form-control', 'placeholder' => 'Camping Name',
                                    'value' => $query->name]);
                                ?>
                                <?php echo form_error('name'); ?>
                            </div>
							
							<div class="form-group">
                                <label for="name">Mobile<span style="color:red;">*</span></label>
                               <input type="text" name="mobile" placeholder="Mobile" class="form-control" value="<?= $query->mobile; ?>" />
					  <span style="color: red;"><?=form_error('mobile');?></span>
                            </div>
							
                        </div>
                    </div>
                    <div class="box-footer">
                        <button  class="btn btn-primary" name="button" type="submit">Update</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
                
            
        </div>
    </div>
</section>
