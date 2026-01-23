<section class="content-header">
    <h1>Edit Financials Category</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/financial_category_list">Financials Category List</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-primary">

<form action="<?php echo base_url(); ?>Investor_master/financial_category_edit/<?php echo $director[0]['id']; ?>"
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
        </div>
        <div class="col-md-6">
        </div>

    </div>

</div>

<div class="box-footer">
    <button class="btn btn-primary" style="float:right;" type="submit">Update</button>
    <a href="<?php echo base_url(); ?>Investor_master/financial_category_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
</div>

</form>

</div>
</div>
</div>
</section>
