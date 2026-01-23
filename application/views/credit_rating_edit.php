<section class="content-header">
    <h1>Edit Credit Rating</h1>
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
                                    <label>Facilities/Type of Rating<span style="color:red;">*</span></label>
                                    <input type="text" name="facilities_type_of_rating"
                                           class="form-control"
                                           value="<?= set_value('facilities_type_of_rating', $row['facilities_type_of_rating']); ?>">
                                    <span style="color:red;"><?= form_error('facilities_type_of_rating'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Rating<span style="color:red;">*</span></label>
                                    <input type="text" name="rating"
                                           class="form-control"
                                           value="<?= set_value('rating', $row['rating']); ?>">
                                    <span style="color:red;"><?= form_error('rating'); ?></span>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount<span style="color:red;">*</span></label>
                                    <input type="text" name="amount"
                                           class="form-control"
                                           value="<?= set_value('amount', $row['amount']); ?>">
                                    <span style="color:red;"><?= form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Rating Status<span style="color:red;">*</span></label>
                                    <select name="rating_status" class="form-control">
                                        <option value="">Select Report Status</option>
                                        <option value="Pending" <?= (set_value('rating_status', $row['rating_status']) == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Assign" <?= (set_value('rating_status', $row['rating_status']) == 'Assign') ? 'selected' : ''; ?>>Assign</option>
                                        <option value="Completed" <?= (set_value('rating_status', $row['rating_status']) == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                    <span style="color:red;"><?= form_error('rating_status'); ?></span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Update
                        </button>

                        <a href="<?= base_url(); ?>Investor_master/credit_rating_list"
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
