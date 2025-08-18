<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Test Method
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href=""><i class="fa fa-users"></i>Test Method</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Test Method List</h3>

                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_method/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>test_method/test_csv?branch=<?php echo $branch ?>&test=' class="btn btn-primary btn-sm" >Export</a><!--
                        
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
                            <?php echo form_open('test_method/index', $attributes); ?>
                            <div class="col-md-3">
                                <select class="form-control" name="branch">
                                    <option value="">--Select Branch--</option>
                                    <?php foreach ($branch_list as $key) { ?>
                                        <option value="<?php echo ucfirst($key["id"]); ?>" <?php
                                            if ($branch == $key["id"]) {
                                                echo "selected";
                                            }
                                        ?> ><?php echo ucfirst($key["branch_name"]); ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="test" value='<?php echo $test?>' class="form-control" placeholder="Enter Test Name">
                            </div>

                            <input type="submit" value="Search" class="btn btn-primary btn-md">
                            </form>
                            <br> 
                            <div class="tableclass">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Branch Name</th>
                                            <th>Test Name</th>
                                            <th>Method</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = $counts;
                                        foreach ($query as $row) {
                                            $cnt++;
                                            ?>
                                            <tr> 
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo ucwords($row['branch_name']); ?></td>
                                                <td><?php echo ucwords($row['test_name']); ?></td>
                                                <td><?php echo ucwords($row['test_method']); ?></td>
                                                <td>
                                                    <a href='<?php echo base_url(); ?>test_method/edit/<?php echo $row['test_id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                    <a  href='<?php echo base_url(); ?>test_method/delete/<?php echo $row['test_id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        <?php }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
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
