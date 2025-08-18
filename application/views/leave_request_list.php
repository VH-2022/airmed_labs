<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Leave Request Master
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href=""><i class="fa fa-users"></i>Leave Request List</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Leave Request List</h3>

                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>Leave_applications/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <!--
                    </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="widget">
                                <?php if (isset($success) != NULL) { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $success['0']; ?>
                                    </div>
                                <?php } ?>
                                <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                    <span id="msg_success"></span>
                                </div>
                            </div>
                            <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                            <?php echo form_open('Leave_applications/index', $attributes); ?>
                            <div class="col-md-3">
                                <input type="text" name="user" value='<?php echo $user ?>' class="form-control" placeholder="Enter User Name">
                            </div>

                            <div class="col-md-3">
                                <input type="text" name="phlebo" value='<?php echo $phlebo ?>' class="form-control" placeholder="Enter Phlebo Name">
                            </div>

                            <div class="col-md-2">
                                <input type="text" name="start_date" value='<?php echo $start_date ?>' class="form-control datepicker-input" placeholder="Enter Start Date">
                            </div>

                            <div class="col-md-2">
                                <input type="text" name="end_date" value='<?php echo $end_date ?>' class="form-control datepicker-input" placeholder="Enter End Date">
                            </div>


                            <input type="submit" value="Search" class="btn btn-primary btn-md">
                            </form>
                            <br> 
                            <div class="tableclass">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Employee</th>
                                            <th>Leave Start Date</th>
                                            <th>Leave End Date</th>
                                            <th>Remark</th>
                                            <th>Admin Remark</th>
                                            <th>Status</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 0;
                                        foreach ($query as $row) {
                                            
                                            $cnt++;
                                            ?>
                                            <tr> 
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php
                                                    if ($row['user_type'] == '1') {
                                                        echo ucwords($row['user_name']);
                                                        $user_type = '1';
                                                    } else {
                                                        echo ucwords($row['phlebo_name']);
                                                        $user_type = '2';
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['start_date']; ?></td>
                                                <td><?php echo $row['end_date']; ?></td>
                                                <td><?php echo $out = strlen($row['remark']) > 50 ? substr($row['remark'], 0, 50) . "..." : $row['remark']; ?></td>
                                                <td><?php echo $out1 = strlen($row['admin_remark']) > 50 ? substr($row['admin_remark'], 0, 50) . "..." : $row['admin_remark']; ?></td>
                                                <td><?php
                                                    if ($row['leave_status'] == "0") {
                                                        echo "<span class='label label-warning'>Pending</span>";
                                                    } else if ($row['leave_status'] == "1") {
                                                        echo "<span class='label label-success'>Approved</span>";
                                                    } else {
                                                        echo "<span class='label label-danger'>Disapprove</span>";
                                                    }
                                                    ?></td>
                                                <td>

                                                    <?php if ($row['leave_status'] == "0") { ?>
                                                        <a href='<?php echo base_url(); ?>Leave_applications/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>                                                        
                                                    <?php }
                                                    ?>

                                                    <?php
                                                    if ($user->email == "brijesh@virtualheight.com") {
                                                        if ($row['leave_status'] == "0") {
                                                            ?>

                                                            <a onclick="return confirm('Are you sure you want to do this?')" href='<?php echo base_url(); ?>Leave_applications/approve/<?php echo $row['id']; ?>/<?php echo $user_type; ?>' data-toggle="tooltip" data-original-title="Approve"><i class="fa fa-thumbs-up"></i></a>
                                                            <a onclick="return confirm('Are you sure you want to do this?')" href='<?php echo base_url(); ?>Leave_applications/disapprove/<?php echo $row['id']; ?>/<?php echo $user_type; ?>' data-toggle="tooltip" data-original-title="Disapprove"><i class="fa fa-thumbs-down"></i></a>
                                                            <a  href='<?php echo base_url(); ?>Leave_applications/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                        <?php }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="8">No records found</td>
                                            </tr>
                                        <?php } ?>
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

<
<script type="text/javascript">
    function timeount() {
        setTimeout(function () {
            $("#profile_msg_suc").hide();
        }, 5000);
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script>