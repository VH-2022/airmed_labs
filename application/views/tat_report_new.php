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
    .chosen-container {width: 100% !important;}
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            New Tat Report
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>All Jobs</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">


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
                        <!-- Search start-->
                        <?php echo form_open("job_report/Tat_report", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="from_date" placeholder="Start date" class="form-control datepicker-input" id="date"  value="<?= $from_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="to_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $to_date ?>" />
                            </div>

                            <div class="col-sm-2">
                                <select name="added_by_type" class=form-control>
                                    <option value="">Select Added By</option>
                                    <option value="phlebo" <?php if ($added_by_type == 'phlebo') echo 'selected'; ?>>Phlebo</option>
                                    <option value="user" <?php if ($added_by_type == 'user') echo 'selected'; ?>>User</option>
                                    <option value="online" <?php if ($added_by_type == 'online') echo 'selected'; ?>>Online Booking</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="exportData();" >Export</a>
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>
                        <!--Search end-->
                        <div class="tableclass">
                            <div class="table-responsive">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Reg No</th>
                                            <th>Order Id</th>
                                            <th>Patient Name</th>
                                            <th>Added By</th>
                                            <th>Job Start Time</th>
                                            <th>Job End Time</th>
                                            <th>Tat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $cnt = 1;?>
                                        <?php if(isset($query) && !empty($query) && count($query) > 0) {
                                            $page = isset($page) ? $page : 0;
                                        } else {
                                            $page = 0;
                                        } ?>
                                        <?php foreach ($query as $row) { ?>
                                            <tr>
                                                <td><?php echo $cnt + $page . " "; ?> </td>
                                                <td><?php echo ucwords($row['id']); ?></td>
                                                <td><?php echo ucwords($row['order_id']); ?></td>
                                                <td><?php echo ucwords($row['full_name']); ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($row["phlebo_added_by"])) {
                                                        echo ucfirst($row["phlebo_added_by"]) . " (Phlebo)";
                                                    } else if (!empty($row["added_by"])) {
                                                        echo ucfirst($row["added_by"]);
                                                    } else {
                                                        echo "Online Booking";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo ucwords($row['start_date_time']); ?></td>
                                                <td><?php echo ucwords($row['end_date_time']); ?></td>
                                                <td><?php echo ucwords($row['tat']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['status'] == 1) {
                                                        echo "<span class='label label-danger'>Waiting For Approval</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 6) {
                                                        echo "<span class='label label-warning'>Approved</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 7) {
                                                        echo "<span class='label label-warning'>Sample Collected</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 8) {
                                                        echo "<span class='label label-warning'>Processing</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 2) {
                                                        echo "<span class='label label-success'>Completed</span>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($row['status'] == 0) {
                                                        echo "<span class='label label-danger'>User Deleted</span>";
                                                    }
                                                    ?>
                                                    <br><?php
                                                    if ($row['emergency'] == '1') {
                                                        echo "<span class='label label-danger'>Emergency</span>";
                                                    }
                                                    ?>
                                                    
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }?>
                                        <?php if (empty($query)) { ?>
                                            <tr>
                                                <td colspan="9" style="text-align: center;">No Data Found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script> 
<script type="text/javascript">
    $(function () {

        $('#example3').dataTable({
            //"bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });

    function exportData() {
        // Get your filters from the page
        var from_date = '<?php echo $from_date; ?>';
        var to_date = '<?php echo $to_date; ?>';
        var added_by_type = '<?php echo $added_by_type; ?>';

        // Build URL with query string
        var exportUrl = "<?php echo base_url('job_report/export_tat_report'); ?>?from_date=" + encodeURIComponent(from_date) + "&to_date=" + encodeURIComponent(to_date) + "&added_by_type=" + encodeURIComponent(added_by_type);

        // Trigger download
        window.location.href = exportUrl;
}
</script>