<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Bank List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Bank List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Bank List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>inventory/Bank_master/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Bank Add</strong></a>
                        <!--<a style="float:right;" href='<?php echo base_url(); ?>test_master/test_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success')[0]; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('inventory/Bank_master/bank_list', $attributes); ?>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="bank_name" placeholder="Enter Bank Name" value="<?php echo $bank_name; ?>" tabindex='1'/>
                        </div>
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="branch_name" placeholder="Enter Branch Name" value="<?php echo $branch_name; ?>" tabindex='2'/>
                        </div>
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="account_no" placeholder="Enter Account No" value="<?php echo $account; ?>" tabindex='3'/>
                        </div>
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="city" placeholder="Enter City Name" value="<?php echo $city; ?>" tabindex='4'/>
                        </div>

                        <input type="submit"  value="search" class="btn btn-primary btn-md">
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bank Name</th>
                                        <th>Branch Name</th>
                                        <th>Account No</th>
                                        <th>City</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['bank_name']); ?></td>
                                               <td><?php echo ucwords($row['branch_name']); ?></td>
                                                 <td><?php echo ucwords($row['account_no']); ?></td>
                                                   <td><?php echo ucwords($row['city']); ?></td>
                                          
                                            <td><a href='<?php echo base_url(); ?>inventory/Bank_master/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/bank_master/bank_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                        <?php $cnt++; ?>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Records Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php ?>
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

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>

