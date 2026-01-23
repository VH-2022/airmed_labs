<section class="content-header">
    <h1>AGM</h1>
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
                                    <label>Notice AGM (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="notice_title"
                                           class="form-control"
                                           value="<?= set_value('notice_title'); ?>">
                                    <span style="color:red;"><?= form_error('notice_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Notice AGM (PDF)<span style="color:red;">*</span></label>
                                    <input type="file" name="notice_pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('notice_pdf'); ?></span>
                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Results AGM (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="result_title"
                                           class="form-control"
                                           value="<?= set_value('result_title'); ?>">
                                    <span style="color:red;"><?= form_error('result_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Results AGM (PDF)<span style="color:red;">*</span></label>
                                    <input type="file" name="result_pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('result_pdf'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Transcript (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="transcript_title"
                                           class="form-control"
                                           value="<?= set_value('transcript_title'); ?>">
                                    <span style="color:red;"><?= form_error('transcript_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Transcript (PDF)<span style="color:red;">*</span></label>
                                    <input type="file" name="transcript_pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('transcript_pdf'); ?></span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Add AGM
                        </button>

                        <a href="<?= base_url(); ?>Investor_master/agm_list"
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
