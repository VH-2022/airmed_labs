<div class="content-wrapper">
    <section class="content-header">
        <h1>Committees</h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>Dashboard">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>
            <li>Committees</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title">Committees List</h3>
                        <a style="float:right;"
                           href="<?php echo base_url(); ?>Investor_master/committees_add"
                           class="btn btn-primary btn-sm">
                            <i class="fa fa-plus-circle"></i> Add Committee
                        </a>
                    </div>

                    <div class="box-body">
                        <?php if (isset($success)) { ?>
                            <div class="alert alert-success alert-dismissable">
                                <button class="close" data-dismiss="alert">×</button>
                                <?= $success; ?>
                            </div>
                        <?php } ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Committee Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $cnt=1; foreach($query as $row){ ?>
                                <tr>
                                    <td><?= $cnt++; ?></td>
                                    <td><?= $row['committee_name']; ?></td>
                                    <td>
                                        <a href="<?= base_url('Investor_master/committees_edit/'.$row['id']); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <!-- <a href="<?php //base_url('Investor_master/committees_delete/'.$row['id']); ?>"
                                           onclick="return confirm('Are you sure?');">
                                            <i class="fa fa-trash-o"></i>
                                        </a> -->
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if(empty($query)){ ?>
                                <tr>
                                    <td colspan="5" style="text-align:center;">No records found</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
