<section class="content-header">
    <h1>Add EGM/CCM Notice</h1>
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
                                    <label>Particulars / Financial Year<span style="color:red;">*</span></label>
                                    <input type="text" name="particulars"
                                           placeholder="Ex : 2025-26"
                                           class="form-control"
                                           value="<?= set_value('particulars'); ?>">
                                    <span style="color:red;"><?= form_error('particulars'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Notice (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="notice_title"
                                           class="form-control"
                                           value="<?= set_value('notice_title'); ?>">
                                    <span style="color:red;"><?= form_error('notice_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Notice (PDF)<span style="color:red;">*</span></label>
                                    <input type="file" name="notice_pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('notice_pdf'); ?></span>
                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Results (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="result_title"
                                           class="form-control"
                                           value="<?= set_value('result_title'); ?>">
                                    <span style="color:red;"><?= form_error('result_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Results (PDF)<span style="color:red;">*</span></label>
                                    <input type="file" name="result_pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('result_pdf'); ?></span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Save
                        </button>

                        <a href="<?= base_url(); ?>Investor_master/egm_list"
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
