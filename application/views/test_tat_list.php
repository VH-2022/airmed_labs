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
            Test/Package TAT
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Test/Package TAT</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <a style="float:right;margin-left:5px"  href='<?php echo base_url(); ?>Test_tat_master/export_csv?test=<?php echo $test_name; ?>&search=Search' class="btn btn-success btn-sm" >Export</a>
                        <a style="float:right;margin-left:5px" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm"> Import</a>
                        <a style="float:right;" href='<?php echo base_url(); ?>Test_tat_master/add' class="btn btn-primary btn-sm" > Add</a>

                    </div>
                    <div class="box-body">
                        <?php $session = $this->session->userdata('success'); ?>
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success; ?>
                                </div>
                            <?php } ?>
                        </div>


                        <div class="tableclass">
                            <?php echo form_open("Test_tat_master/index", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Test/Package Name</th>
                                        <th>Type</th>
                                        <th>TAT (Hour)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span style="color:red;">#</span></td>
                                        <td>
                                            <input type="text" name="test" placeholder="Test Name" value="<?= $test_name; ?>" class="form-control"/>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <input type="submit" name="search" class="btn btn-success btn-sm" value="Search" />
                                            <a class="btn btn-primary btn-sm" href="<?= base_url() . "Test_tat_master/index"; ?>">Reset</a>
                                        </td>
                                    </tr>

                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php
                                                if ($row['type'] == '1') {
                                                    echo ucfirst($row['test_name']);
                                                } else {
                                                    echo ucfirst($row['title']);
                                                }
                                                
                                                ?></td>
                                            <td><?php
                                                if ($row['type'] == '1') {
                                                    echo "Test";
                                                } else {
                                                    echo "Package";
                                                }
                                                ?></td>
                                            <td><?php echo ucfirst($row['tat']); ?></td>

                                    <style>
                                        .fa
                                        {
                                            margin-right:10px;
                                        }
                                    </style>
                                    <td>                                        
                                        <a href='<?php echo base_url(); ?>Test_tat_master/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                        <a  href='<?php echo base_url(); ?>Test_tat_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                                if (empty($query)) {
                                    ?>
                                    <tr><td colspan="12"><center>Data not available.</center></td></tr>
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


<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="H4">Import</h4>
            </div>
            <div class="modal-body">
                <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'import_frm'); ?>
<?php echo form_open_multipart('Test_tat_master/import_csv', $attributes); ?>

                <div class="form-group">
                    <label>Upload</label>
                    <input type="file" name="id_browes" id="id_browes" class="form-controll">
                    <div style='color:red;' id='admin_name_add_alert'></div>
                    <span class="errclass" id="id_broweserror" style="color:red"></span>
                </div>

                <div class="modal-footer">
                    <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $("#import_frm").submit(function (event) {
        var id_browes = $("#id_browes").val();
        $(".errclass").html("");

        var err = 0;
        if (id_browes == "") {
            err = 1;
            $("#id_broweserror").html("Please select file");
        }
        if (err == "1") {
            return false;
        } else {
            return true;
        }

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
    }, 4000);</script>