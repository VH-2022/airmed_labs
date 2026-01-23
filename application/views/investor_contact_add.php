<section class="content-header">
    <h1>Investor Contact</h1>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">

<form method="post" action="<?php echo base_url(); ?>Investor_master/investor_contact_add">

<div class="box-body">
<div class="row">

<div class="col-md-6">
    <div class="form-group">
        <label>Title <span style="color:red">*</span></label>
        <input type="text" name="title" class="form-control" value="<?= set_value('title'); ?>">
        <span style="color:red;"><?= form_error('title'); ?></span>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Name <span style="color:red">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= set_value('name'); ?>">
        <span style="color:red;"><?= form_error('name'); ?></span>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Designation <span style="color:red">*</span></label>
        <input type="text" name="designation" class="form-control" value="<?= set_value('designation'); ?>">
        <span style="color:red;"><?= form_error('designation'); ?></span>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Telephone <span style="color:red">*</span></label>
        <input type="text" name="telephone" class="form-control" value="<?= set_value('telephone'); ?>">
        <span style="color:red;"><?= form_error('telephone'); ?></span>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Email <span style="color:red">*</span></label>
        <input type="email" name="email" class="form-control" value="<?= set_value('email'); ?>">
        <span style="color:red;"><?= form_error('email'); ?></span>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Fax <span style="color:red">*</span></label>
        <input type="text" name="fax" class="form-control" value="<?= set_value('fax'); ?>">
        <span style="color:red;"><?= form_error('fax'); ?></span>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label>Address <span style="color:red">*</span></label>
        <textarea name="address" class="form-control"><?= set_value('address'); ?></textarea>
        <span style="color:red;"><?= form_error('address'); ?></span>
    </div>
</div>

</div>
</div>

<div class="box-footer">
    <button class="btn btn-primary" style="float:right;">Save</button>
    <a href="<?php echo base_url(); ?>Investor_master/investor_contact_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>

</div>

</form>

</div>
</div>
</div>
</section>
