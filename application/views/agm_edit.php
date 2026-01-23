<section class="content-header">
    <h1>Edit AGM</h1>
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
                                    <input type="text" name="particulars" class="form-control"
                                           value="<?= set_value('particulars', $row['particulars']); ?>">
                                    <span style="color:red;"><?= form_error('particulars'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Notice AGM (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="notice_title" class="form-control"
                                           value="<?= set_value('notice_title', $row['notice_title']); ?>">
                                    <span style="color:red;"><?= form_error('notice_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Notice AGM (PDF)</label>
                                    <input type="file" name="notice_pdf" class="form-control">
                                    <small>View Pdf: <a target="_blank" href="<?= base_url('upload/agm/' . $row['notice_pdf']); ?>">
                                        <?= $row['notice_title']; ?>
                                    </a></small>

                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Results AGM (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="result_title" class="form-control"
                                           value="<?= set_value('result_title', $row['result_title']); ?>">
                                    <span style="color:red;"><?= form_error('result_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Results AGM (PDF)</label>
                                    <input type="file" name="result_pdf" class="form-control">
                                    <small>View Pdf: <a target="_blank" href="<?= base_url('upload/agm/' . $row['result_pdf']); ?>">
                                        <?= $row['result_title']; ?>
                                    </a></small>
                                </div>

                                <div class="form-group">
                                    <label>Transcript (Title)<span style="color:red;">*</span></label>
                                    <input type="text" name="transcript_title" class="form-control"
                                           value="<?= set_value('transcript_title', $row['transcript_title']); ?>">
                                    <span style="color:red;"><?= form_error('transcript_title'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>Transcript (PDF)</label>
                                    <input type="file" name="transcript_pdf" class="form-control">
                                    <small>View Pdf: <a target="_blank" href="<?= base_url('upload/agm/' . $row['transcript_pdf']); ?>">
                                        <?= $row['transcript_title']; ?>
                                    </a></small>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Update
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
