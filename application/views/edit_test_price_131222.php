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
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $branch_name[0]->branch_name ?> Test Price 
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Branch List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <!--                        <h3 class="box-title">Edit Test Price</h3><br/>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php
                        $success = $this->session->flashdata('success');
                        print_r();
                        ?>
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">Ã—</button>
                                    <?php echo $success[0]; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php $ttype = $this->input->get("ttype") ?>

                        <div class="tableclass">
                            <?php echo form_open("Branch_Test_Price/edit_test_price/$cid", array("method" => 'GET')); ?>
                            <div class="col-md-3">
                                <input type="text" name="test_name" placeholder="Test Name" value="<?php echo $test_name; ?>" class="form-control"/>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="ttype">
                                    <option value="1">TEST</option>
                                    <option <?php
                                    if ($ttype == 2) {
                                        echo "selected";
                                    }
                                    ?> value="2" >PACKAGE</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search"/>
                                <button type="button" class="btn btn-sm btn-primary" onclick="window.location = '<?= base_url() . "Branch_Test_Price/edit_test_price/$cid" ?>'" value="Reset"><i class="fa fa-refresh"></i> Reset</button>
                            </div>
                            <a style="float:right;margin-left:3px" href='<?php echo base_url(); ?>Branch_Test_Price/branch_test_export_csv/<?php echo $cid ?>?test_name=<?php echo $this->input->get("test_name"); ?>&ttype=<?= $this->input->get("ttype"); ?>' class="btn btn-sm btn-primary" >Export CSV</a>
                            <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>
                            <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#add_test"  class="btn btn-primary btn-sm"> Add Test</a>
                            <br/><br/>

                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Test Name</th>
                                        <th>Test Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo ucfirst($row->test_name); ?></td>
                                            <td><?php echo ucfirst($row->price); ?></td>
                                            <td>
                                                <?php if ($row->status == '1') { ?>
                                                    <span class='label label-success'>Active</span>
                                                <?php } else { ?>
                                                    <span class='label label-danger'>Inactive</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php  /*if ($row->status == '0') { ?>
                                                    <a href='<?php echo base_url(); ?>Branch_Test_Price/isdeactive/1/<?php echo $row->id; ?>/<?php echo $cid; ?>' data-toggle="tooltip" data-original-title="Active" onclick="return confirm('Are You Sure ?')"><i class="fa fa-toggle-off"></i></a>
                                                <?php } else { ?>
                                                    <a href='<?php echo base_url(); ?>Branch_Test_Price/isdeactive/0/<?php echo $row->id; ?>/<?php echo $cid; ?>' data-toggle="tooltip" data-original-title="Deactive" onclick="return confirm('Are You Sure ?')"><i class="fa fa-toggle-on" style="font-size:12px"></i></a>
                                                <?php } */?>
                                                
                                                <?php if ($row->status == '0') { ?>
                                                    <a href='<?php echo base_url(); ?>Branch_Test_Price/isdeactive/1/<?php echo $row->id; ?>/<?php echo $cid; ?>?test_name=<?php echo $test_name; ?>&ttype=<?php echo $ttype; ?>&search=Search' data-toggle="tooltip" data-original-title="Active" onclick="return confirm('Are You Sure ?')"><i class="fa fa-toggle-off"></i></a>
                                                <?php } else { ?>
                                                    <a href='<?php echo base_url(); ?>Branch_Test_Price/isdeactive/0/<?php echo $row->id; ?>/<?php echo $cid; ?>?test_name=<?php echo $test_name; ?>&ttype=<?php echo $ttype; ?>&search=Search' data-toggle="tooltip" data-original-title="Deactive" onclick="return confirm('Are You Sure ?')"><i class="fa fa-toggle-on" style="font-size:12px"></i></a>
                                                <?php } ?>
                                                &nbsp;
                                                <a href='javascript:void(0)' data-toggle="tooltip" data-original-title="Edit" onclick="edit_price('<?php echo $row->id ?>', '<?php echo $row->price ?>', '<?php echo $row->test_name ?>');"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                    if (empty($query)) {
                                        ?>
                                        <tr><td colspan="4"><center>No records found.</center></td></tr>
                                <?php } ?>
                                </tbody>

                            </table>
                            <div style="text-align:right;" class="box-tools">
                                <ul class="pagination pagination-lm no-margin pull-right">
                                    <?php echo $links; ?>
                                </ul>
                            </div>

                            <?php echo form_close(); ?>
                        </div>



                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<form role="form" id="department_form1" action="<?php echo base_url(); ?>Branch_Test_Price/edit_test_price/<?php echo $cid ?>" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="family_model_edit" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Test Price</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>Test Name</td>
                                <td>
                                    <input type="text" name="test_name" id="test_name" readonly class="form-control" >
                                    <input type="hidden" name="testtype" class="form-control" value="<?= $ttype ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td>Test Price</td>
                                <td>
                                    <input type="text" name="price" id="price" class="form-control">
                                    <span id="price_error" style="color:red;"></span>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
<!--                    <input class="btn btn-primary" type="submit" value="Save"/>-->
                    <button class="btn btn-primary" type="button" onclick="check_input()" >Save</button>
                    <input type="hidden" name="test_price_id" id="test_price_id" class="form-control">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>


<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="H4">Import</h4>
            </div>
            <div class="modal-body"> 
                <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
                <?php
//                echo form_open_multipart("branch_test_price/importprice_csv/$cid?test_name=" . $_GET['test_name'] . "&ttype=" . $_GET['ttype'], $attributes);
                echo form_open_multipart("Branch_Test_Price/importprice_csv/$cid", $attributes);
                ?>

                <div class="form-group">
                    <label>Upload</label>
                    <input type="file" name="testeximport" class="form-controll">
                    <div style='color:red;' id='admin_name_add_alert'></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                    <!--                    <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button>-->
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="add_test" tabindex="-1" role="dialog" style="width:100%">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="H4">Add Test</h4>
            </div>
            <div class="modal-body"> 
                <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'test_form'); ?>
                <?php
                echo form_open_multipart("Branch_Test_Price/add_test/$cid", $attributes);
                ?>

                <div class="form-group">
                    <label>Select Test</label>
                    <select class="chosen chosen-select" name="select_test" id="select_test" onchange="get_test_price()" tabindex="-1">
                        <option value="">--Select--</option>
                        <?php foreach ($test_data_1 as $key) {
                            ?>
                            <option value="<?php echo $key->id ?>_1" ><?php echo $key->test_name ?></option>
                        <?php } ?>
                        <?php foreach ($package_data_1 as $key) {
                            ?>
                            <option value="<?php echo $key->id ?>_2" ><?php echo $key->title ?></option>
                        <?php } ?>
                    </select>

                    <span id="test_1_error" style="color:red;"></span>
                    <div style='color:red;' id='admin_name_add_alert'></div>
                </div>
                <div class="form-group" id="test_name_1">
                    <label>Price</label>
                    <input type="text" id="test_add" name="test_add" class="form-control">
                    <span id="price_1_error" style="color:red;"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" id="add_test_1" name="add_test_1" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $("#add_test_1").click(function (e) {
        var error = 1;
        $('#price_1_error').html("");
        $("#test_1_error").html("");
        var price1 = $("#test_add").val().trim();
        var test_data = $("#select_test").val().trim();
        var numbers = /^[0-9]+$/;
        if (price1 == "") {
            error = 0;
            $("#price_1_error").html("Please enter price");
        }

        if (price1 != '') {
            if (price1.match(numbers)) {

            } else {
                $('#price_1_error').html("Only Number Allowed");
                error = 0;
            }

        }
        if (test_data == "") {
            error = 0;
            $("#test_1_error").html("Please select test");
        }

        if (error == 1) {
            $("#test_form").submit();
        } else {
            return false;
        }
    });
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script>



<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
</script> 

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);

    function edit_price(id, price, test_name) {
        $("#test_price_id").val(id);

        if (id != "") {
            $("#price").val(price);
            $("#test_name").val(test_name);
            $("#family_model_edit").modal('show');
        }
    }

    function check_input() {
        var temp = 1;
        var price = $("#price").val();
        $("#price_error").html("");
        if (price != "") {
            if (isNaN(price) || price <= 0) {
                temp = 0;
                $("#price_error").html("Please enter valid price.");
            }
        } else {
            temp = 0;
            $("#price_error").html("Please enter price.");
        }

        if (temp == 1) {
            $("#department_form1").submit();
        }
    }





//    function get_test_price() {
//        $("#test_name_1").show();
//    }


</script>
