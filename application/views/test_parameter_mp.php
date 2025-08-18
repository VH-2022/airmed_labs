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
            <?php echo $branch_name[0]->branch_name ?> Test Parameter Mapping 
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Test Parameter Mapping</li>
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
                        //print_r();
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
                            <?php echo form_open("Test_parameter_map/index", array("method" => 'GET')); ?>
                            <div class="col-md-3">
                                <input type="text" name="code_id" placeholder="Parameter Code" value="<?php echo $code_id; ?>" class="form-control"/>
                            </div>
                            <!--                            <div class="col-md-3">
                                                            <select class="form-control" name="center_name">
                                                                <option value="">Select Center</option>
                                                                <option <?php
//                                    if ($ttype == 2) {
//                                        echo "selected";
//                                    }
                            ?> value="2" >PACKAGE</option>
                                                            </select>
                                                        </div>-->
                            <!--                            <div class="col-md-3">
                                                            <input type="text" name="param_name" placeholder="Parameter Name" value="<?php //echo $param_name;       ?>" class="form-control"/>
                                                        </div>-->

                            <div class="col-md-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search"/>
<!--                                <button type="button" class="btn btn-sm btn-primary" onclick="window.location = '<?php //echo base_url() . "Branch_Test_Price/edit_test_price/$cid"       ?>'" value="Reset"><i class="fa fa-refresh"></i> Reset</button>-->
                            </div>
                            <br/>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
<!--                                        <th>ID</th>-->
                                        <th>Parameter Code ID</th>
<!--                                        <th>Center Name</th>-->
                                        <th>Parameter Name</th>
                                        <th>Mapping Code</th>
                                        <th>Multiply By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        //echo "<pre>"; print_r($row); exit;
                                        ?>
                                        <tr>
    <!--                                            <td><?php //echo $cnt;       ?> </td>-->
                                            <td><?php echo ucfirst($row['id']); ?></td>
    <!--                                            <td><?php //echo ucfirst($row['branch_name']);       ?></td>-->
                                            <td>
                                                <?php echo ucfirst($row['parameter_name']); ?>
                                            </td>
                                            <td>
                                                <?php echo ucfirst($row['code']);?>
                                            </td>
                                            <td>
                                                <?php echo ucfirst($row['multiply_by']); ?>
                                            </td>
                                            <td>
                                                <a href='javascript:void(0)' data-toggle="tooltip" data-original-title="Edit" onclick="edit_price('<?php echo $row['id'] ?>', '<?php echo $row['code'] ?>', '<?php echo $row['multiply_by'] ?>');"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                    if (empty($query)) {
                                        ?>
                                        <tr><td colspan="5"><center>No records found.</center></td></tr>
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


<form role="form" id="department_form1" action="<?php echo base_url(); ?>Test_parameter_map/index" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="family_model_edit" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Parameter Code</h4>
                </div>
                <div class="modal-body">
                    <table id="example4_edit" class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>Parameter Code</td>
                                <td>
                                    <input type="text" name="code" id="code" class="form-control">
                                    <span id="code_error" style="color:red;"></span>
                                </td>
                            </tr>
                            <tr>
                                <td>Multiply By</td>
                                <td>
                                    <input type="text" name="mult_by" id="mult_by" class="form-control">
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

<script>
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

    function edit_price(id, code, mult_by) {
        $("#test_price_id").val(id);

        if (id != "") {
            $("#code").val(code);
            $("#mult_by").val(mult_by);

            $("#family_model_edit").modal('show');
        }
    }

    function check_input() {
        var temp = 1;
        var code = $("#code").val();
        $("#code_error").html("");
        if (code == "") {
            temp = 0;
            $("#code_error").html("Please enter parameter code.");
        }

        if (temp == 1) {
            $("#department_form1").submit();
        }
    }
</script>
