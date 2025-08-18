<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;
        color: #FFF;
        text-align: center;
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper" id="extension-settings">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Health Advisor List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Health Advisor List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Health Advisor List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>Health_advisor/export_csv/?phone=<?= $_GET['phone'] ?>&status=<?= $_GET['status'] ?>&search=Search' class="btn btn-primary btn-sm"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>




<!--                        <a style="float:right;" href='<?php //echo base_url(); ?>Health_advisor/add' class="btn btn-primary btn-sm"> Add</a>-->




                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tableclass">
                            <?php echo form_open("health_advisor", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mobile No.</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="color:red;">*</span></td>
                                        <td>
                                            <?php echo form_input(['name' => 'phone', 'class' => 'form-control', 'placeholder' => 'Enter phone', 'value' => $phone]); ?>
                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select status" name="status">
                                                <option value="">Select status</option>
                                                <option value="1" <?php
                                                if ($_GET['status'] == '1') {
                                                    echo "selected";
                                                }
                                                ?>>Pending</option>
                                                <option value="2" <?php
                                                if ($_GET['status'] == '2') {
                                                    echo "selected";
                                                }
                                                ?>>Completed</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
                                        </td>
                                    </tr>
                                    <?php
                                    $cnt = $pages;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php
                                                if ($row['status'] == 1) {
                                                    echo "<span class='label label-danger'>Pending</span>";
                                                } else {
                                                    echo "<span class='label label-success'>Completed</span>";
                                                }
                                                ?></td>
                                            <td>
<!--                                                <a href='<?php //echo base_url(); ?>Health_advisor/edit/<?php //echo $row['ID']; ?>' data-toggle="tooltip" data-original-title='Edit'>Edit</a>-->



                                                <a href='<?php echo base_url(); ?>Health_advisor/delete/<?php echo $row['ID']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a>
                                                <?php if ($row['status'] == 1) { ?>
                                                    <a  href='<?php echo base_url(); ?>Health_advisor/change_status/<?php echo $row['ID']; ?>/<?php echo $row['status']; ?>' data-toggle="tooltip" data-original-title="Change to completed" onclick="return confirm('Are you sure you want to update to completed this data?');"><i style="margin-left:10px; " class="fa fa-thumbs-up"></i></a>
                                                <?php } else { ?>
                                                    <a  href='<?php echo base_url(); ?>Health_advisor/change_status/<?php echo $row['ID']; ?>/<?php echo $row['status']; ?>' data-toggle="tooltip" data-original-title="Change to pending" onclick="return confirm('Are you sure you want to update to pending this data?');"><i style="margin-left:10px; " class="fa fa-thumbs-down"></i></a>
                                                <?php } ?>

                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if ($cnt == '0') {
                                        ?>
                                        <tr>
                                            <td colspan="3"><center>No records found</center></td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script type="text/javascript">
                                                jQuery(".chosen-select").chosen({
                                                    search_contains: true
                                                });
</script>
<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
