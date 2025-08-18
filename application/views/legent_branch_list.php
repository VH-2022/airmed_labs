<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Party Ledger Account List
            <small></small>
        </h1>
        <ol class="breadcrumb">
		
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."branch_receive_payment/legent_list"; ?>"><i class="fa fa-users"></i>Party Ledger Account List</a></li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Party Ledger Account List</h3>
						
                            <a  href='<?php echo base_url(); ?>branch_receive_payment/partylegent_brnachadd' class="btn btn-primary btn-sm pull-right" ><i class="fa fa-plus-circle" ></i><strong > Add Branch</strong></a>
							
							<a  href='<?php echo base_url(); ?>branch_receive_payment/party_add' class="btn btn-primary btn-sm pull-right" ><i class="fa fa-plus-circle" ></i><strong > Add Party Ledger</strong></a>
                                   

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            
                        </div>
                      
                        <div class="tableclass">
                             
                                 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
											<th>Party name</th>
											<th>Branch Name</th>
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
												<td><?php echo $row->party_name; ?></td>
                                               
                                                    <td><?php echo $row->branchname; ?></td>
                                                <td>

                                                    <a data-toggle="tooltip" data-original-title="View Assign Lab" href='<?php echo base_url(); ?>branch_receive_payment/legent_branch/<?php echo $row->id; ?>' ><i class="fa fa-eye"></i></a>
													
													 <a href="<?php echo base_url()."branch_receive_payment/party_edit/".$row->id; ?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
													 
                                                    <a href="<?php echo base_url()."branch_receive_payment/party_delete/".$row->id; ?>" data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a> 
													
                                                </td>
                                            </tr>
                                            
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="4">No records found</td>
                                            </tr>
<?php } ?>
                                    
                                    </tbody>
                                </table>
                                 </div>
                          
                        </div>
                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->

</div><!-- /.content-wrapper -->