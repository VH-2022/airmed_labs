<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Branch Sample Type
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href=""><i class="fa fa-users"></i>Branch Sample Type</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Branch Sample Type List</h3>

                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>Branch_sample_type/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>Branch_sample_type/test_csv?branch=<?php echo $branch ?>&test=<?php echo $test ?>' class="btn btn-primary btn-sm" >Export</a>
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
                            <?php echo form_open('branch_sample_type/index', $attributes); ?>
                            <div class="col-md-3">
                                <select class="form-control chosen chosen-select" name="branch">
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
                                            <th>Sample Type</th>
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
                                                <td><?php echo ucwords($row['sample_type']); ?></td>
                                                <td>
                                                    <a href='<?php echo base_url(); ?>branch_sample_type/edit/<?php echo $row['test_id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                    <a  href='<?php echo base_url(); ?>branch_sample_type/delete/<?php echo $row['test_id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script>
