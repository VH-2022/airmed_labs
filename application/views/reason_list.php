<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Call Reason Master
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>Callreason_master/index"><i class="fa fa-users"></i>Call Reason Master</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Call Reason Master</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>Callreason_master/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>

                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                        <?php echo form_open('Callreason_master/index', $attributes); ?>

                        <div class="col-md-3">
                            <input type="text" class="form-control" name="reason_name" placeholder="Reason" value="<?php
                            if (isset($reason_name) != NULL) {
                                echo $reason_name;
                            }
                            ?>" />
                        </div>
                        <input type="submit" value="Search" class="btn btn-primary btn-md">


                        </form>

                        <br>

                        <div class="tableclass">
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['reason']); ?></td>
                                            <td>
                                                <a href='<?php echo base_url(); ?>Callreason_master/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                <a  href='<?php echo base_url(); ?>Callreason_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <div style="text-align:right;" class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <?php echo $links; ?>
                            </ul>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
