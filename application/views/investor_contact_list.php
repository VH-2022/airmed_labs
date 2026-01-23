<div class="content-wrapper">
    <section class="content-header">
        <h1>Investor Contact</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Investor Contact</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <a style="float:right;" href="<?php echo base_url(); ?>Investor_master/investor_contact_add" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus-circle"></i> Add
                        </a>
                    </div>

                    <?php $session = $this->session->userdata('success'); ?>
                    <div class="widget">
                        <?php if (isset($success[0]) != NULL) { ?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $success[0]; ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Telephone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $cnt=1; foreach($query as $row){ ?>
                                <tr>
                                    <td><?= $cnt++; ?></td>
                                    <td><?= $row['title']; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['designation']; ?></td>
                                    <td><?= $row['telephone']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td>
                                        <a href="<?= base_url('Investor_master/investor_contact_edit/'.$row['id']); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="<?= base_url('Investor_master/investor_contact_delete/'.$row['id']); ?>"
                                           onclick="return confirm('Are you sure?');">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if(empty($query)){ ?>
                                <tr>
                                    <td colspan="7" style="text-align:center;">No records found</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-lm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
