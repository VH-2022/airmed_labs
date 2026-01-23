<section class="content-header">
    <h1>Dividend Communications</h1>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title">Dividend Communications List</h3>
            <a style="float:right;" href="<?= base_url(); ?>Investor_master/dividend_comm_add"
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
                    <th>Title</th>
                    <th>PDF</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($query)) { ?>
                    <?php $cnt = 1;
                    foreach ($query as $row) { ?>
                        <tr>
                            <td><?= $cnt++; ?></td>
                            <td><?= $row['pdf_title']; ?></td>
                            <td>
                                <a href="<?= base_url('upload/dividend/' . $row['pdf_path']); ?>" target="_blank">
                                    View PDF
                                </a>
                            </td>
                            <td>
                                <a href="<?= base_url('Investor_master/dividend_comm_delete/' . $row['id']); ?>"
                                    onclick="return confirm('Are you sure?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No records found</td>
                    </tr>
                <?php } ?>
            </table>

            <div class="box-tools pull-right">
                <?= $links; ?>
            </div>

        </div>
    </div>
</section>