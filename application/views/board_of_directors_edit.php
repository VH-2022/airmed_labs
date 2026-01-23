<section class="content-header">
    <h1>Edit Director</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/board_of_directors_list">Board List</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">

<form action="<?php echo base_url(); ?>Investor_master/board_of_directors_edit/<?php echo $director[0]['id']; ?>"
      method="post" enctype="multipart/form-data">

<div class="box-body">

    <div class="row">

        <!-- LEFT COLUMN -->
        <div class="col-md-6">

            <div class="form-group">
                <label>Name <span style="color:red">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="<?php echo $director[0]['name']; ?>">
                <span style="color:red;"><?= form_error('name'); ?></span>
            </div>

            <div class="form-group">
                <label>Position <span style="color:red">*</span></label>
                <input type="text" name="position" class="form-control"
                       value="<?php echo $director[0]['position']; ?>">
            </div>

            <div class="form-group">
                <label>Current Image</label><br>
                <img src="<?php echo base_url(); ?>upload/board/<?php echo $director[0]['image']; ?>"
                     width="80" style="border-radius:50%;">
            </div>

            <div class="form-group">
                <label>Change Image</label>
                <input type="file" name="image" class="form-control">
            </div>

        </div>


        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <div class="form-group">
                <label>Description <span style="color:red">*</span></label>
                <textarea id="editor1" name="description" class="form-control" rows="4"><?php
                    echo $director[0]['description']; ?></textarea>
                <span style="color:red;"><?= form_error('description'); ?></span>
            </div>

            <div class="form-group">
                <label>Display Order <span style="color:red">*</span></label>
                <input type="number" name="display_order" class="form-control"
                       value="<?php echo $director[0]['display_order']; ?>">
                <span style="color:red;"><?= form_error('display_order'); ?></span>
            </div>

        </div>

    </div>

</div>

<div class="box-footer">
    <button class="btn btn-primary" style="float:right;" type="submit">Update</button>
    <a href="<?php echo base_url(); ?>Investor_master/board_of_directors_list" class="btn btn-default"  style="float:right; margin-right:10px;" >
        Cancel
    </a>
</div>

</form>

</div>
</div>
</div>
</section>
