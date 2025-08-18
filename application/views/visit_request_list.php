<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Visit Request
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="#"><i class="fa fa-users"></i>Visit Request</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Visit Request List</h3>
                                                
                        <!-- <a style="float:right;margin-right: 10px;" href='<?php //echo base_url(); ?>customer-master/customer-add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a> -->
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
                        
                        <div class="tableclass table-responsive">
                            <form role="form" action="<?php echo base_url(); ?>phlebo/visit-request" method="get" enctype="multipart/form-data">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Phlebo Name</th>
                                            <th>From Location</th>
                                            <th>To Location</th>
                                            <th>Trip Type</th>
                                            <th>KM</th>
                                            <th>Price</th>
                                            <th>Request Type</th>
                                            <th>Created At</th>
                                            <th>Action By</th>
                                            <th>Action Date Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                            <input type="text" name="date" placeholder="Date" class="form-control datepicker-input" id="date"  value="<?= $date ?>" />
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <select name="status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="pending" <?php if(isset($status) && $status == 'pending') echo 'selected'; ?>>Pending</option>
                                                    <option value="approved" <?php if(isset($status) && $status == 'approved') echo 'selected'; ?>>Approved</option>
                                                    <option value="rejected" <?php if(isset($status) && $status == 'rejected') echo 'selected'; ?>>Rejected</option>
                                                </select>
                                            </td>
                                            <td><input type="submit" name="search" class="btn btn-success btn-sm px-4" value="Search" />
                                            <a href="<?php echo base_url(); ?>phlebo/visit-request" class="btn btn-primary btn-sm px-4">Reset</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = 0;
                                        foreach ($query as $row) {
                                            $cnt++;
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt + $page; ?></td>
                                                <td><?php echo isset($row['phlebo_name']) ? ucfirst($row['phlebo_name']): ""; ?></td>
                                                <td><?php echo $row['from_location']; ?> </td>
                                                <td><?php echo $row['to_location']; ?></td>
                                                <td><?php echo $row['trip_type']; ?></td>
                                                <td><?php echo $row['kilometers']; ?></td>
                                                <td>
                                                    <?php 
                                                      if($row['trip_type'] == "Single"){
                                                        $show_price = $row['kilometers'] * $phlebo_km_wise_rs;
                                                      }else{
                                                        $multiple = $row['kilometers'] * 2;
                                                        $show_price = $multiple * $phlebo_km_wise_rs;
                                                      } 
                                                      
                                                      echo $show_price; 
                                                    ?>
                                                </td>
                                                <td><?php echo $row['request_type']; ?></td>
                                                <td><?php echo $row['date']; ?></td>
                                                <td><?php echo !empty($row['action_by_name']) ? ucfirst($row['action_by_name']) : '-'; ?></td>
                                                <td><?php echo !empty($row['action_date']) ? $row['action_date'] : '-'; ?></td>
                                                <td>
                                                    <?php
                                                        $status = strtolower($row['status']); 
                                                        $labelClass = '';

                                                        if ($status == 'approved') {
                                                            $labelClass = 'label label-success';
                                                        } elseif ($status == 'rejected') {
                                                            $labelClass = 'label label-danger';
                                                        } elseif ($status == 'pending') {
                                                            $labelClass = 'label label-warning';
                                                        } else {
                                                            $labelClass = 'label label-default';
                                                        }
                                                    ?>
                                                    <span class="<?php echo $labelClass; ?>"><?php echo ucfirst($status); ?></span>
                                                </td>
                                                
                                                <td>
                                                    <?php if($row['status'] == "pending"){ ?>
                                                    <button type="button" class="btn btn-success btn-xs" onclick="updateStatus('<?php echo $row['id']; ?>', 'approved')">Approve</button>
                                                    <button type="button" class="btn btn-danger btn-xs" onclick="updateStatus('<?php echo $row['id']; ?>', 'rejected')">Reject</button>
                                                    <?php } ?>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($cnt == '0') {
                                            ?>
                                            <tr>
                                                <td colspan="13" class="text-center">No records found</td>
                                            </tr>
                                        <?php }
                                        ?>

                                    </tbody>
                                </table>
                            </form>
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
<script>
    function updateStatus(id, status) {
        if (confirm("Are you sure you want to " + status + " this request?")) {
            $.ajax({
                url: "<?= base_url('Phlebo/update_request_status') ?>",
                type: "POST",
                data: { id: id, status: status },
                success: function(response) {
                    alert("Request " + status + " successfully!");
                    location.reload();
                },
                error: function(xhr) {
                    alert("Something went wrong.");
                }
            });
        }
    }
</script>
