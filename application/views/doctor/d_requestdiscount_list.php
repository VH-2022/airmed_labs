<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
         Request Discount List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url()."doctor/dashboard"; ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url()."doctor/request_discount"; ?>"><i class="fa fa-users"></i>Request Discount List</a></li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
					    <h3 class="box-title">Request Discount List</h3>
						
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
											<th>Patient Name</th>
                                            <th>Mobile</th>
                                            <th>Description</th>
											<th>Createddate</th>
											
										</tr>
                                    </thead>
                                    <tbody>
                                       <?php
									  $i=0;
									  foreach($query as $row){ $i++; ?>
									  <tr>
									  <td><?= $i; ?></td>
									  <td><?= ucwords($row["patient_name"]); ?></td>
									  <td><?= $row["mobile"]; ?></td>
									  <td><?= $row["desc"]; ?></td>
									  <td><?= date("d-m-Y",strtotime($row["createddate"])); ?></td>
									  
									  </tr>
										
									  
									  <?php } if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
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