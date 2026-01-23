<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
  <h1>Add Team</h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><a href="<?php echo base_url(); ?>TeamMaster/index">Team List</a></li>
    <li class="active">Add Team</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Add Team Member</h3>
        </div>

        <form role="form" action="<?php echo base_url(); ?>TeamMaster/add"
              method="post" enctype="multipart/form-data">

          <div class="box-body">

            <?php if (isset($unsuccess) != NULL) { ?>
              <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo $unsuccess['0']; ?>
              </div>
            <?php } ?>

            <div class="row">

              <!-- LEFT COLUMN -->
              <div class="col-md-6">

                <div class="form-group">
                  <label>Image</label>
                  <span style="color:red"><b>Note:</b> Please upload an image with a size of 650 × 863 pixels only.
</span>
                  <input id="imageUpload" type="file" name="sliderfile" class="form-control">
                  <span class="text-danger" id="imgError"><?php echo form_error('sliderfile'); ?></span>
                </div>

                <div class="form-group">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control"
                         value="<?php echo set_value('title'); ?>">
                  <span class="text-danger"><?php echo form_error('title'); ?></span>
                </div>

              </div>

              <!-- RIGHT COLUMN -->
              <div class="col-md-6">

                <div class="form-group">
                  <label>Designation <span class="text-danger">*</span></label>
                  <input type="text" name="designation" class="form-control"
                         value="<?php echo set_value('designation'); ?>">
                  <span class="text-danger"><?php echo form_error('designation'); ?></span>
                </div>

                <div class="form-group">
                  <label>Description <span class="text-danger">*</span></label>
                  <textarea id="editor1" name="desc" class="form-control" rows="5"></textarea>
                  <span class="text-danger"><?php echo form_error('desc'); ?></span>
                </div>

              </div>

            </div>

          </div>

          <div class="box-footer text-right">
            <button class="btn btn-primary" type="submit">ADD</button>
            <a href="<?php echo base_url(); ?>TeamMaster/index"
               class="btn btn-default">BACK</a>
          </div>

        </form>

      </div>

    </div>
  </div>
</section>
<script>
$('#imageUpload').on('change', function (e) {
    var file = e.target.files[0];
    if (!file) return;

    var img = new Image();
    var objectUrl = URL.createObjectURL(file);

    img.onload = function () {
        var width = this.naturalWidth;
        var height = this.naturalHeight;
        if (width === 650 && height === 863) {
            $('#imgError').text('');   // valid image
        } else {
            $('#imgError').text(
                'Invalid image size. Please upload an image of 650 × 863 pixels only.'
            ).show();

            $('#imageUpload').val(''); // clear file
        }

        URL.revokeObjectURL(objectUrl);
    };

    img.src = objectUrl;
});
</script>
