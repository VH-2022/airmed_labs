<section class="content-header">
    <h1>Edit Shareholder Info</h1>
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
                                    <label>Type <span style="color:red;">*</span></label>
                                    <select name="type" class="form-control">
                                        <option value="">Select Type</option>

                                        <option value="Annual Return"
                                            <?= (set_value('type', $row['type']) == 'Annual Return') ? 'selected' : ''; ?>>
                                            Annual Return
                                        </option>

                                        <option value="Frequently Asked Questions"
                                            <?= (set_value('type', $row['type']) == 'Frequently Asked Questions') ? 'selected' : ''; ?>>
                                            Frequently Asked Questions
                                        </option>

                                        <option value="Other Notices"
                                            <?= (set_value('type', $row['type']) == 'Other Notices') ? 'selected' : ''; ?>>
                                            Other Notices
                                        </option>

                                        <option value="Online Dispute Resolution (ODR) Mechanism for Investors"
                                            <?= (set_value('type', $row['type']) == 'Online Dispute Resolution (ODR) Mechanism for Investors') ? 'selected' : ''; ?>>
                                            Online Dispute Resolution (ODR) Mechanism for Investors
                                        </option>

                                        <option value="Memorandum and Articles of Association"
                                            <?= (set_value('type', $row['type']) == 'Memorandum and Articles of Association') ? 'selected' : ''; ?>>
                                            Memorandum and Articles of Association
                                        </option>

                                    </select>

                                    <span style="color:red;"><?= form_error('type'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label>PDF Upload</label>
                                    <input type="file" name="pdf" class="form-control">
                                    <span style="color:red;"><?= form_error('pdf'); ?></span>
                                    <small>View Pdf: <a target="_blank" href="<?= base_url('upload/shareholder_info/' . $row['pdf_path']); ?>">
                                        <?= $row['pdf_title']; ?>
                                    </a></small>
                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>PDF Title<span style="color:red;">*</span></label>
                                    <input type="text" name="pdf_title" class="form-control"
                                           value="<?= set_value('pdf_title', $row['pdf_title']); ?>">
                                    <span style="color:red;"><?= form_error('pdf_title'); ?></span>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Update
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
