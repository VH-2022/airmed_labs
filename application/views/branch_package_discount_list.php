<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Test
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>Branch_Package_Discount/index"><i class="fa fa-users"></i>Test</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <!--        <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="H4">Import List</h4>
                            </div>
                            <div class="modal-body"> 
        <?php //$attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
        <?php
        //echo form_open_multipart('test_master/importtest_csv', $attributes);
        ?>
        
                                <div class="form-group">
        
                                    <label>Upload</label>
                                    <input type="file" name="testeximport" class="form-controll">
                                    <div style='color:red;' id='admin_name_add_alert'></div>
        
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                                                                <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button>
                                    </form>
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div>-->

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Branch Package Discount List</h3>

                        <a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>Branch_Package_Discount/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
<!--                        <a style="float:right;margin-right:5px;" href='<?php //echo base_url();                ?>test_master/test_csv?city=<?php //echo $city                ?>' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->

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
                        <?php echo form_open('Branch_Package_Discount/index', $attributes); ?>
                        <!--<div class="col-md-3">
                            <input type="text" class="form-control" name="search" placeholder="search" value="<?php
//                            if (isset($search) != NULL) {
//                                echo $search;
//                            }
                        ?>" />
                        </div>-->
<!--                        <div class="col-md-3">
                            <select class="form-control" name="city">
                                <option value="">--Select City--</option>
                                <?php //foreach ($citys as $key) { ?>
                                    <option value="<?php //echo ucfirst($key["id"]); ?>" <?php
//                                    if ($city != '') {
//                                        if ($city == $key["id"]) {
//                                            echo "selected";
//                                        }
//                                    }
                                    ?> ><?php //echo ucfirst($key["name"]); ?></option>
                                        <?php //} ?>
                            </select>
                        </div>

                        <input type="submit" value="Search" class="btn btn-primary btn-md">-->
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>City</th>
                                        <th>Branch</th>
                                        <th>Package</th>
                                        <th>Discount</th>
                                        <th>Test Discount For Self</th>
                                        <th>Test Discount For Family</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = $counts;
                                    foreach ($query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['city_name']; ?></td>
                                            <td><?php echo $row['branch_name']; ?></td>
                                            <td><?php echo $row['package_title']; ?></td>
                                            <td><?php foreach ($row["city_wise_price"] as $key) { ?>
                                                    <?php echo$key["discount"] . "% (" . $key["test_name"] . ")<br>"; ?>
                                                <?php } ?></td>
                                            <td><?php echo $row['other_test_discount_self']; ?>%</td>
                                            <td><?php echo $row['other_test_discount_family']; ?>%</td>
                                            <td>
                                                <a href='<?php echo base_url(); ?>Branch_Package_Discount/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                <a  href='<?php echo base_url(); ?>Branch_Package_Discount/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                            </td>

                                        </tr>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="6">No records found</td>
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
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
    function department_change(tid, did) {
        var con = confirm('Are you sure you want to change department?');
        if (con == true) {
            var url = "<?php echo base_url(); ?>test_master/change_department";
            $.ajax({
                type: "POST",
                url: url,
                data: {"tid": tid, "did": did}, // serializes the form's elements.
                success: function (data)
                {
                    $("#msg_success").html("");
                    $("#msg_success").html("Department Changed Successfully.");
                    $("#profile_msg_suc").show();
                    timeount();
                }
            });
        }
    }
    function timeount() {
        setTimeout(function () {
            $("#profile_msg_suc").hide();
        }, 5000);
    }
</script>
