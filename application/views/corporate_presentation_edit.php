<section class="content-header">
    <h1>Edit Corporate Presentation</h1>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">

<form action="<?php echo base_url(); ?>Investor_master/corporate_presentation_edit/<?php echo $director[0]['id']; ?>"
      method="post" enctype="multipart/form-data">

<div class="box-body">
<div class="row">

<div class="col-md-6">

    <div class="form-group">
        <label>PDF Title <span style="color:red">*</span></label>
        <input type="text" name="pdf_title" class="form-control"
               value="<?= $director[0]['pdf_title']; ?>">
        <span style="color:red;"><?= form_error('pdf_title'); ?></span>
    </div>

</div>

<div class="col-md-6">
    <div class="form-group">
        <label>PDF File </label>
        <input type="file" name="pdf" class="form-control">
        <input type="hidden" name="old_file" value="<?= $director[0]['pdf_path']; ?>">

        <?php if($director[0]['pdf_path']){ ?>
            <a target="_blank"
               href="<?= base_url('upload/corporate_presentation/'.$director[0]['pdf_path']); ?>">
               View PDF
            </a>
        <?php } ?>
    </div>

</div>

</div>
</div>

<div class="box-footer">
    <button class="btn btn-primary" style="float:right;">Update</button>
    <a href="<?php echo base_url(); ?>Investor_master/corporate_presentation_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
</div>

</form>

</div>
</div>
</div>
</section>
