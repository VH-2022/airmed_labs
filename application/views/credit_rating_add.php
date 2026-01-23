<section class="content-header">
    <h1>Add Credit Rating</h1>
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
                                    <label>Facilities/Type of Rating<span style="color:red;">*</span></label>
                                    <input type="text" name="facilities_type_of_rating"
                                           class="form-control"
                                           value="<?= set_value('facilities_type_of_rating'); ?>">
                                    <span style="color:red;"><?= form_error('facilities_type_of_rating'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Rating<span style="color:red;">*</span></label>
                                    <input type="text" name="rating"
                                           class="form-control"
                                           value="<?= set_value('rating'); ?>">
                                    <span style="color:red;"><?= form_error('rating'); ?></span>
                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount<span style="color:red;">*</span></label>
                                    <input type="text" name="amount"
                                           class="form-control"
                                           value="<?= set_value('amount'); ?>">
                                    <span style="color:red;"><?= form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label>Rating Status<span style="color:red;">*</span></label>
                                    <select name="rating_status" class="form-control">
                                        <option value="">Select Report Status</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Assign">Assign</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                    <span style="color:red;"><?= form_error('rating_status'); ?></span>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Save
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
