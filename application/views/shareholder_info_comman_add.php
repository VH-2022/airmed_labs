<section class="content-header">
    <h1>Shareholder Info Common</h1>
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

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Type<span style="color:red;">*</span></label>
                                    <select name="type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="Annual Return">Annual Return</option>
                                        <option value="Frequently Asked Questions">Frequently Asked Questions</option>
                                        <option value="Other Notices">Other Notices</option>
                                        <option value="Online Dispute Resolution (ODR) Mechanism for Investors">Online Dispute Resolution (ODR) Mechanism for Investors</option>
                                        <option value="Memorandum and Articles of Association">Memorandum and Articles of Association</option>
                                    </select>
                                    <span style="color:red;"><?= form_error('type'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>PDF Upload<span style="color:red;">*</span></label>
                                    <input type="file" name="pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('pdf'); ?></span>
                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PDF Title<span style="color:red;">*</span></label>
                                    <input type="text" name="pdf_title"
                                           class="form-control"
                                           value="<?= set_value('pdf_title'); ?>">
                                    <span style="color:red;"><?= form_error('pdf_title'); ?></span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Save
                        </button>

                        <a href="<?= base_url(); ?>Investor_master/shareholder_info_comman_list"
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
