<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
  <h1>Edit Team</h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><a href="<?php echo base_url(); ?>TeamMaster/index">Team List</a></li>
    <li class="active">Edit Team</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">

      <div class="box box-primary">

        <?php if (isset($error['error'])) { ?>
          <p class="help-block text-danger"><?php echo $error['error']; ?></p>
        <?php } ?>

        <form role="form" method="post" enctype="multipart/form-data">

          <div class="box-body">

            <?php $row = $query[0]; ?>

            <div class="row">

              <!-- LEFT COLUMN -->
              <div class="col-md-6">

                <div class="form-group">
                <label>Image</label>

                <div style="display:flex; align-items:center; gap:25px;">

                  <!-- Clickable Image -->
                  <a href="<?php echo base_url('upload/team/'.$row['image']); ?>" target="_blank">
                    <img src="<?php echo base_url('upload/team/'.$row['image']); ?>"
                        style="width:50px; height:50px; border:1px solid #ccc; padding:3px; cursor:pointer;">
                  </a>

                  <!-- File Input -->
                  <input type="file" name="sliderfile" class="form-control"  style="width:600px;max-width:600px;">

                </div>
              </div>

                <div class="form-group">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control"
                         value="<?php echo set_value('title', $row['title']); ?>">
                  <span class="text-danger"><?php echo form_error('title'); ?></span>
                </div>

              </div>

              <!-- RIGHT COLUMN -->
              <div class="col-md-6">

                <div class="form-group">
                  <label>Designation <span class="text-danger">*</span></label>
                  <input type="text" name="designation" class="form-control"
                         value="<?php echo set_value('designation', $row['designation']); ?>">
                  <span class="text-danger"><?php echo form_error('designation'); ?></span>
                </div>

                <div class="form-group">
                  <label>Description <span class="text-danger">*</span></label>
                  <textarea id="editor1" name="desc" class="form-control" rows="5"><?php
                    echo set_value('desc', $row['desc']); ?></textarea>
                  <span class="text-danger"><?php echo form_error('desc'); ?></span>
                </div>

              </div>

            </div>

          </div>

          <div class="box-footer text-right">
            <button class="btn btn-primary" type="submit">UPDATE</button>
            <a href="<?php echo base_url(); ?>TeamMaster/index"
               class="btn btn-default">BACK</a>
          </div>

        </form>

      </div>

    </div>
  </div>
</section>
