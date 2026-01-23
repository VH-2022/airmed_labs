<div class="content-wrapper">
    <section class="content-header">
        <h1>Newspaper Advertisment</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Newspaper Advertisment List</h3>
                <a href="<?= base_url('Investor_master/newspaper_advertisment_add') ?>" class="btn btn-primary btn-sm" style="float:right;">Add</a>
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
                            <th>Type</th>
                            <th>PDf</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($query)) { ?>
                        <?php $i = 1;
                        foreach ($query as $row) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row['type']; ?></td>

                                <td>
                                    <a target="_blank" href="<?= base_url('upload/newspaper_advertisment/' . $row['pdf_path']); ?>">
                                        <?= $row['pdf_title']; ?>
                                    </a>
                                </td>

                                <td>
                                    <a href="<?= base_url('Investor_master/newspaper_advertisment_edit/' . $row['id']); ?>"><i class="fa fa-edit"></i></a>
                                    <a href="<?= base_url('Investor_master/newspaper_advertisment_delete/' . $row['id']); ?>"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">No data found</td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>