<div class="content-wrapper">
    <section class="content-header">
        <h1>Unclaimed-Unpaid Amount</h1>
    </section>

    <section class="content">
        <div class="box box-primary">

            <div class="box-header">
                <a style="float:right;" href="<?= base_url(); ?>Investor_master/unclaimed_add" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus-circle"></i> Add
                </a>
            </div>

            <?php if (isset($success[0])) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button class="close" data-dismiss="alert">×</button>
                    <?= $success[0]; ?>
                </div>
            <?php } ?>

            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($query)){ ?>
                        <?php $cnt = 1;
                        foreach ($query as $row) { ?>
                            <tr>
                                <td><?= $cnt++; ?></td>
                                <td><?= $row['title']; ?></td>
                                <td>
                                    <a href="<?= base_url('Investor_master/unclaimed_edit/' . $row['id']); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('Investor_master/unclaimed_delete/' . $row['id']); ?>"
                                        onclick="return confirm('Are you sure?');">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="3" style="text-align:center; font-weight:600;">
                                    No data found
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div style="text-align:right;">
                    <?= $links; ?>
                </div>

            </div>
        </div>
    </section>
</div>