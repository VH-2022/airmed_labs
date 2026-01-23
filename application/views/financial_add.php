<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>

<section class="content-header">
    <h1>Financials</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Investor_master/financial_list">Financials List</a></li>
        <li class="active">Add Financials</li>
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
                      action="<?php echo base_url(); ?>Investor_master/financial_add"
                      method="post"
                      enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Financial Category <span style="color:red">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        <?php foreach($category as $c){ ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['name']  ?></option>
                                        <?php } ?>
                                    </select>
                                    <span style="color:red;"><?= form_error('category_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Financial Year<span style="color:red">*</span></label>
                                    <select name="report_year" class="form-control">
                                        <option value="">Select Financial Year</option>

                                        <?php
                                        $currentYear = date("Y");
                                        for ($i = 0; $i < 10; $i++) {
                                            $start = $currentYear - $i;
                                            $end = $start + 1;
                                            $fy = $start . "-" . substr($end, 2);
                                            echo "<option value='$fy'>$fy</option>";
                                        }
                                        ?>
                                    </select>
                                    <span style="color:red;"><?= form_error('report_year'); ?></span>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Title <span style="color:red">*</span></label>
                                    <input type="text" name="file_title" class="form-control" value="<?php echo set_value('file_title'); ?>">
                                    <span style="color:red;"><?= form_error('file_title'); ?></span>
                                </div>

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
                            <a href="<?php echo base_url(); ?>Investor_master/financial_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
