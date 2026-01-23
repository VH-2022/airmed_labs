<section class="content-header">
    <h1>Policies & Programs</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Policies List</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">Policies & Programs PDFs</h3>

                    <a style="float:right;"
                       href="<?php echo base_url(); ?>Investor_master/policies_add"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-plus-circle"></i> Add PDF
                    </a>
                </div>

                <div class="box-body">
                    <?php if($this->session->flashdata('success')){ ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>PDF</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $cnt=1; foreach($query as $row){ ?>
                            <tr>
                                <td><?= $cnt++; ?></td>
                                <td><?= $row['title']; ?></td>
                                <td>
                                    <a href="<?= base_url('upload/policies/'.$row['pdf_file']); ?>"
                                       target="_blank">
                                       View PDF
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= base_url('Investor_master/policies_delete/'.$row['id']); ?>"
                                    class="btn btn-primary "
                                    onclick="return confirm('Are you sure you want to delete this policy?');">
                                    <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(empty($query)){ ?>
                            <tr>
                                <td colspan="4" style="text-align:center;">No records found</td>
                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
