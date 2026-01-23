<section class="content-header">
    <h1>Add Newspaper Advertisment</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <form method="post" enctype="multipart/form-data">

                    <div class="box-body">

                        <?php if(!empty($error)){ ?>
                            <div class="alert alert-danger">
                                <?= $error; ?>
                            </div>
                        <?php } ?>

                        <div class="row">

                            <!-- LEFT COLUMN -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Type<span style="color:red;">*</span></label>
                                    <select name="type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="Board Meeting">Board Meeting</option>
                                        <option value="Shareholders Meeting">Shareholders Meeting</option>
                                        <option value="Financial Results">Financial Results</option>
                                    </select>
                                    <span style="color:red;"><?= form_error('type'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>PDF Title<span style="color:red;">*</span></label>
                                    <input type="text" name="pdf_title"
                                           class="form-control"
                                           value="<?= set_value('pdf_title'); ?>">
                                    <span style="color:red;"><?= form_error('pdf_title'); ?></span>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PDF<span style="color:red;">*</span></label>
                                    <input type="file" name="pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('pdf'); ?></span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Save
                        </button>

                        <a href="<?= base_url(); ?>Investor_master/newspaper_advertisment_list"
                           class="btn btn-default"
                           style="float:right; margin-right:10px;">
                           Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
