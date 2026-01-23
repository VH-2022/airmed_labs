<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
    <h1>Dividend Communications </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/dividend_comm_list">Dividend Communications List</a></li>
        <li class="active">Add Dividend Communications</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <p class="help-block" style="color:red;">
                    <?php if (isset($error)) { echo $error; } ?>
                </p>

                <form method="post" enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Pdf Title <span style="color:red">*</span></label>
                                    <input type="text" name="pdf_title" class="form-control" value="<?php echo set_value('pdf_title'); ?>">
                                    <span style="color:red;"><?= form_error('pdf_title'); ?></span>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PDF File <span style="color:red">*</span></label>
                                    <input type="file" name="pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('pdf'); ?></span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button class="btn btn-primary" style="float: right;" type="submit">Save</button>
                            <a href="<?php echo base_url(); ?>Investor_master/dividend_comm_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
