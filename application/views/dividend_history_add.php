<section class="content-header">
    <h1>Add Dividend History</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?= base_url(); ?>Investor_master/dividend_history_list">Dividend History List</a></li>
        <li class="active">Add Dividend History</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">

                <form method="post">

                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Financial Year <span style="color:red">*</span></label>
                                    <input type="text" name="year" class="form-control" placeholder="FY 2025" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Dividend Type <span style="color:red">*</span></label>
                                    <select name="dividend_type" class="form-control" required>
                                        <option value="Interim">Interim</option>
                                        <option value="2nd Interim">2nd Interim</option>
                                        <option value="Final">Final</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Amount (₹) <span style="color:red">*</span></label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-primary" style="float:right;" type="submit">Save</button>
                        <a href="<?php echo base_url(); ?>Investor_master/dividend_history_list" class="btn btn-default" style="float:right; margin-right:10px;" > Cancel </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>