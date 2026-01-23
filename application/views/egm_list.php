<div class="content-wrapper">
    <section class="content-header">
        <h1>EGM/CCM Notice</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">EGM/CCM Notice List</h3>
                <a href="<?= base_url('Investor_master/egm_add') ?>" class="btn btn-primary btn-sm" style="float:right;">Add AGM</a>
            </div>

            <div class="box-body">
                <div class="widget">
                    <?php if (isset($success) != NULL) { ?>
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $success; ?>
                        </div>
                    <?php } ?>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Particulars / FY</th>
                            <th>Notice</th>
                            <th>Results</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($query)) { ?>
                        <?php $i = 1;
                        foreach ($query as $row) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['particulars']; ?></td>

                                <td>
                                    <a target="_blank" href="<?= base_url('upload/egm/' . $row['notice_pdf']); ?>">
                                        <?= $row['notice_title']; ?>
                                    </a>
                                </td>

                                <td>
                                    <a target="_blank" href="<?= base_url('upload/egm/' . $row['result_pdf']); ?>">
                                        <?= $row['result_title']; ?>
                                    </a>
                                </td>

                                <td>
                                    <a href="<?= base_url('Investor_master/egm_edit/' . $row['id']); ?>"><i class="fa fa-edit"></i></a>
                                    <a href="<?= base_url('Investor_master/egm_delete/' . $row['id']); ?>"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No data found</td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>