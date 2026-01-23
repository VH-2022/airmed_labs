<section class="content-header">
    <h1>Edit Financials</h1>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">

<form action="<?php echo base_url(); ?>Investor_master/financial_edit/<?php echo $director[0]['id']; ?>"
      method="post" enctype="multipart/form-data">

<div class="box-body">
<div class="row">

<div class="col-md-6">

    <div class="form-group">
        <label>Financial Category <span style="color:red">*</span></label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            <?php foreach($category as $c){ ?>
                <option value="<?= $c['id'] ?>"
                    <?= ($director[0]['category_id']==$c['id'])?'selected':'' ?>>
                    <?= $c['name'] ?>
                </option>
            <?php } ?>
        </select>
        <span style="color:red;"><?= form_error('category_id'); ?></span>
    </div>

    <div class="form-group">
        <label>Financial Year <span style="color:red">*</span></label>
        <select name="report_year" class="form-control">
            <?php
            $currentYear = date("Y");
            for ($i = 0; $i < 10; $i++) {
                $start = $currentYear - $i;
                $end = $start + 1;
                $fy = $start . "-" . substr($end, 2);
                $sel = ($director[0]['report_year'] == $fy) ? 'selected' : '';
                echo "<option value='$fy' $sel>$fy</option>";
            }
            ?>
        </select>
        <span style="color:red;"><?= form_error('report_year'); ?></span>
    </div>

</div>

<div class="col-md-6">

    <div class="form-group">
        <label>File Title <span style="color:red">*</span></label>
        <input type="text" name="file_title" class="form-control"
               value="<?= $director[0]['file_title']; ?>">
        <span style="color:red;"><?= form_error('file_title'); ?></span>
    </div>

    <div class="form-group">
        <label>PDF File </label>
        <input type="file" name="pdf" class="form-control">
        <input type="hidden" name="old_file" value="<?= $director[0]['file_path']; ?>">

        <?php if($director[0]['file_path']){ ?>
            <a target="_blank"
               href="<?= base_url('upload/financial/'.$director[0]['file_path']); ?>">
               View Current PDF
            </a>
        <?php } ?>
    </div>

</div>

</div>
</div>

<div class="box-footer">
    <button class="btn btn-primary" style="float:right;">Update</button>
    <a href="<?php echo base_url(); ?>Investor_master/financial_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
</div>

</form>

</div>
</div>
</div>
</section>
