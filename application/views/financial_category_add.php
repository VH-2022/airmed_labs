<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
    <h1>Financials Category</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/financial_category_list">Financials Category List</a></li>
        <li class="active">Add Category</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <p class="help-block" style="color:red;">
                    <?php if (isset($error)) { echo $error; } ?>
                </p>

                <form role="form"
                      action="<?php echo base_url(); ?>Investor_master/financial_category_add"
                      method="post"
                      enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Name <span style="color:red">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>">
                                    <span style="color:red;"><?= form_error('name'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button class="btn btn-primary" style="float: right;" type="submit">Save</button>
                            <a href="<?php echo base_url(); ?>Investor_master/financial_category_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
