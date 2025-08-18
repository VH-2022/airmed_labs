<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            jobs wallet history
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>jobwallet_master/jobswallet_list"><i class="fa fa-users"></i>wallet</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Jobs Wallet history List</h3>
					</div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            
                            <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                <span id="msg_success"></span>
                            </div>
                        </div>

                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Reg. No</th>
										<th>Order Id</th>
                                        <th>Customer Name</th>
										<th>Date</th>
										<th>Price</th>
										<th>Case back</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = $counts;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['id']); ?></td>
											<td><?php echo ($row['order_id']); ?></td>
                                          
                                            <td><?php echo ($row['full_name']); ?> </td>
											 <td><?php echo ($row['date']); ?> </td>
											 <td><?php echo ($row['price']); ?> </td>
											 <td><?php echo getcredit($row['id']); ?> </td>
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