<section class="content-header">
    <h1>Dividend History</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title">Dividend History List</h3>
            <a style="float:right;" href="<?= base_url(); ?>Investor_master/dividend_history_add"
                class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i> Add
            </a>
        </div>

        <div class="box-body">
            <div class="widget">
                <?php if (isset($success) != NULL) { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $success['0']; ?>
                    </div>
                <?php } ?>

            </div>
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th>Amount (₹)</th>
                    <th>Action</th>
                </tr>

                <?php if (!empty($query)) { ?>
                    <?php $cnt = 1;
                    foreach ($query as $row) { ?>
                        <tr>
                            <td><?= $cnt++; ?></td>
                            <td><?= $row['year']; ?></td>
                            <td><?= $row['type']; ?></td>
                            <td><?= $row['amount']; ?></td>
                            <td>
                                <a href="<?= base_url('Investor_master/dividend_history_edit/' . $row['id']); ?>">
                                    <i class="fa fa-edit"></i>
                                </a>
                                &nbsp;
                                <a href="<?= base_url('Investor_master/dividend_history_delete/' . $row['id']); ?>"
                                    onclick="return confirm('Are you sure?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No records found</td>
                    </tr>
                <?php } ?>
            </table>

            <div class="box-tools pull-right">
                <?= $links; ?>
            </div>

        </div>
    </div>
</section>