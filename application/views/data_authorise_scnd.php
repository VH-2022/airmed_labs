<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script> 


<style>
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="#"><i class="fa fa-users"></i>Data Authorisation</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Data Authorization</h3>
                        
                        <a style="float:right;" href='<?php echo base_url(); ?>data_autorisation_scnd' class="btn btn-primary btn-sm" ><strong> Refresh</strong></a>
                        <a style="float:center;" target="_blank" href='<?php echo base_url(); ?>test_parameter_map' class="btn btn-primary btn-xs"> Set Parameter </a>&nbsp;&nbsp;
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">

                        </div>

                        <?php echo form_open("data_autorisation_scnd/index", array("method" => "GET", "role" => "form")); ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" name="start_date" placeholder="Start date" class="form-control datepicker" id="start_date"  value="<?= $start_date ?>" />
                            </div>

                            <div class="col-sm-3">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker" id="end_date"  value="<?= $end_date ?>" />
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control chosen chosen-select" data-placeholder="Select Branch" name="branch" id="branch">
                                    <option value="" >Select Branch</option>
                                    <?php
                                    foreach ($branch_list as $b) {
                                        ?>
                                        <option value = "<?php echo $b['id']; ?>" <?php
                                        if ($b['id'] == $branch1) {
                                            echo "selected";
                                        }
                                        ?>> <?php echo $b['branch_name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                            </div>
                        </div>
                        <br>
                        <?php echo form_close(); ?>

                        <div class="tableclass">
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Branch</th>
                                        <th>Patient Name</th>
                                        <th>Date</th>
                                        <th style="width: 10px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($query as $datas) { 
                                        //echo "<pre>"; print_r($datas); exit;?>
                                        <tr>
                                            <td><?php echo $datas['barcode']; ?></td>
                                            <td><?php echo $datas['branch_name']; ?></td>
                                            <td><?php echo $datas['full_name']; ?></td>
                                            <td><?php echo date("d/m/Y", strtotime($datas['testdate'])); ?><br>
                                                <a href="javascript:void(0)" onclick="view_all('<?php echo $datas['barcode']; ?>');">view more</a></td>
                                            <td><button type="button" id="show_more_<?php echo $datas['id']; ?>" class="btn btn-default button_show" onclick="parameter_show('<?php echo $datas['id']; ?>', '<?php echo $datas['barcode']; ?>', '<?php echo $datas['branch_fk']; ?>','<?php echo $datas['job_fk']; ?>');"><i class="fa fa-chevron-right" aria-hidden="true"></i></button></td>
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
            <div class="col-xs-6">
                <div class="box box-primary" style="display: none;" id="show_parameters">
                    <div class="box-header">
                        <h3 class="box-title">Parameter Value</h3>
                    </div>
                    <form method="post" action="<?php echo base_url(); ?>data_autorisation_scnd/accept_results" id="add_result">
                        <div class="box-body">
                            <div class="widget">
                                <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                    <span id="msg_success"></span>
                                </div>
                                <div class="alert alert-danger" id="profile_msg_sucun" style="display: none;">
                                    <span id="msg_success"></span>
                                </div>
                            </div>
                            <div class="tableclass">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_parameter_value">
                                    </tbody>

                                </table>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button style="float:right" type="button" onclick="submit_parameter();" class="btn btn-primary btn-sm" >Accept</button>
                        </div>
                    </form>
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
            "bAutoWidth": false
        });
    });
    function parameter_show(id, barcode, branch,job_id) {
        
        $(".button_show").removeClass('btn-danger');
        $(".button_show").addClass('btn-default');
        $("#show_more_" + id).removeClass('btn-default');
        $("#show_more_" + id).addClass('btn-danger');
        var url = "<?php echo base_url(); ?>data_autorisation_scnd/get_data_parameter";
        $.ajax({
            type: "POST",
            url: url,
            data: {"barcode": barcode, "branch": branch,"job_id":job_id}, // serializes the form's elements.
            success: function (data)
            {
                $("#show_parameter_value").empty();
                $("#show_parameter_value").html(data);
                $("#show_parameters").show();
            },
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            complete: function () {
                $("#loader_div").attr("style", "display:none;");
            },
        });
    }
    function submit_parameter() {
        <?php if($_GET["debug"]==1){ ?>
        var frm_data = '<?php echo base_url(); ?>data_autorisation_scnd/accept_results_new?debug=1&debugt=1';
        <?php }else{ ?>
            var frm_data = '<?php echo base_url(); ?>data_autorisation_scnd/accept_results_new';
        <?php } ?>
        $.ajax({
            url: frm_data,
            type: 'post',
            data: $("#add_result").serialize(),
            success: function (data) {
                $("#msg_success").html("");
                $("#msg_success").html("Parameter value successfully Accepted.");
                $("#profile_msg_suc").show();
                timeount();

            },
            error: function (jqXhr) {
                $("#msg_unsuccess").html("");
                $("#msg_unsuccess").html("Please Try Again.");
                $("#profile_msg_sucun").show();
                timeount();
            },
            beforeSend: function () {
                $("#loader_div").attr("style", "");
            },
            complete: function () {

                $("#loader_div").attr("style", "display:none;");
                setTimeout(function () {
                    <?php if($_GET["debug"]!=1){ ?>
                    location.reload();
                    <?php } ?>
                }, 1000);
            },
        });
    }
    function timeount() {
        setTimeout(function () {
            $("#profile_msg_suc").hide();
        }, 1000);
        setTimeout(function () {
            $("#profile_msg_sucun").hide();
        }, 1000);
    }
    function view_all(value) {
        var myWindow = window.open("<?php echo base_url(); ?>data_autorisation_scnd/view_details/" + value, "Data Authorisation", "width=800, height=800");
    }

    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
    });
    
    $("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    $nc = $.noConflict();
    $nc(function () {
        $nc('.chosen-select').chosen();
    });
</script>