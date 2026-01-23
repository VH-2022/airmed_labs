<section class="content-header">
    <h1>Stock Exchanges</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title">Stock Exchange List</h3>
            <a style="float:right;" href="<?= base_url(); ?>Investor_master/stock_add"
                class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i> Add
            </a>
        </div>

        <div class="box-body">
            <?php $session = $this->session->userdata('success'); ?>
            <div class="widget">
                <?php if (isset($success[0]) != NULL) { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $success[0]; ?>
                    </div>
                <?php } ?>

            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Symbol</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($query)) { ?>
                        <?php $cnt = 1;
                        foreach ($query as $row) { ?>
                            <tr>
                                <td><?= $cnt++; ?></td>
                                <td><?= $row['name']; ?></td>
                                <td><?= $row['address']; ?></td>
                                <td><?= $row['symbol']; ?></td>
                                <td>
                                    <a href="<?= base_url('Investor_master/stock_edit/' . $row['id']); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;
                                    <a href="<?= base_url('Investor_master/stock_delete/' . $row['id']); ?>"
                                        onclick="return confirm('Are you sure?');">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No records found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="box-tools pull-right">
                <?= $links; ?>
            </div>

        </div>
    </div>
</section>