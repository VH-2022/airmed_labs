<div class="content-wrapper">
    <section class="content-header">
        <h1>Board of Directors</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Board of Directors</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title">Board Members</h3>
                        <a style="float:right;" href="<?php echo base_url(); ?>Investor_master/board_of_directors_add" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus-circle"></i> Add
                        </a>
                    </div>

                    <div class="box-body">
                        <?php if (isset($success[0])) { ?>
                        <div class="alert alert-success alert-dismissable">
                            <button class="close" data-dismiss="alert">×</button>
                            <?= $success[0]; ?>
                        </div>
                    <?php } ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $cnt=1; foreach($query as $row){ ?>
                                <tr>
                                    <td><?= $cnt++; ?></td>

                                    <td>
                                        <img src="<?php echo base_url(); ?>upload/board/<?php echo $row['image']; ?>"
                                          alt="Profile Pic" style="width:50px; height:50px;"/>
                                    </td>

                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['position']; ?></td>
                                    <td><?= $row['display_order']; ?></td>
                                    <td>
                                        <a href="<?= base_url('Investor_master/board_of_directors_edit/'.$row['id']); ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a href="<?= base_url('Investor_master/board_of_directors_delete/'.$row['id']); ?>"
                                           onclick="return confirm('Are you sure?');">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if(empty($query)){ ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No records found</td>
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