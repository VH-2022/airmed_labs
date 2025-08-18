<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .nav-justified>li{width:auto !important;}
    .nav-justified>li.active{background:#eee; border-top:3px solid #3c8dbc;}
</style>

<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper" style="margin-top:20px;">
    <section class="content-header">
        <h1>
            Phlebo Master Manage
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Phlebo Master Manage</li>
        </ol>

        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
        <?php echo form_open('Phlebo_master_page/index', $attributes); ?>
        <br/>
        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control" name="phlebo">
                    <option value="">--Select Phlebo--</option>
                    <?php
                    foreach ($phlebo_list as $mkey) {
                        ?>
                        <option value="<?= $mkey["id"] ?>" <?php
                        if ($phlebo == $mkey["id"]) {
                            echo "selected";
                        }
                        ?>><?= $mkey["name"] ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <input type="submit" name="search" class="btn btn-success" value="Search" style="margin-left:10px"/>
        </form>
    </section>
    <section class="content" style="min-height:0px; padding-bottom: 0px">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="nav-tabs-custom">
                        <ul class="nav md-pills nav-justified pills-secondary">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_page" role="tab">Phlebo Master</a>
                            </li>
                            <li class="nav-item">
                                <?php
                                if ($_GET['phlebo_name'] != '') {
                                    ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_report/index?start_date=&end_date=&city=&phlebo_name=<?php echo $_GET['phlebo_name'] ?>&sample_collect=&search=Search" role="tab">Phlebo Visit Report</a>
                                <?php } else { ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_report" role="tab">Phlebo Visit Report</a>
                                <?php }
                                ?>
                            </li>
                            <li class="nav-item">
                                <?php if ($_GET['phlebo_name'] != '') { ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_added_jobs/index?start_date=&end_date=&city=&phlebo_name=<?php echo $_GET['phlebo_name'] ?>&sample_collect=&search=Search" role="tab">Phlebo Visit Report</a>
                                <?php } else { ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_added_jobs" role="tab">Phlebo Added Jobs</a>
                                <?php }
                                ?>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="javascript:void(0)" role="tab">Phlebo Punch-in Punch-out</a>
                            </li>
                            <!--                            <li class="nav-item">
                                                            <a class="nav-link" href="javascript:void(0)" role="tab">Phlebo Manage</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:void(0)" role="tab">Phlebo Time Slot</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="javascript:void(0)" role="tab">Phlebo Days</a>
                                                        </li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
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
    <div class="content-wrapper" id="extension-settings">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Phlebotomy Punch In/Out Report 
            </h1>
<!--            <ol class="breadcrumb">
                <li><a href="<?php //echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li>Phlebotomy Punch In/Out List</li>
            </ol>-->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
<!--                            <h3 class="box-title">Phlebotomy Punch In/Out List</h3>-->
                            <?php if ($_SERVER['QUERY_STRING'] != '') { ?>
                                <a style="float:right;margin-left:3px" href='<?php echo base_url(); ?>Phlebo_master_punchin_punchout/export_csv?<?= $_SERVER['QUERY_STRING'] ?>' class="btn btn-sm btn-primary" ><i class="fa fa-download" ></i><strong > Export To CSV</strong></a>
                            <?php } ?>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="widget">
                                <?php $success = $this->session->flashdata('success'); ?>
                                <?php if (isset($success) != NULL) { ?>
                                    <div class="alert alert-success alert-autocloseable-success">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $this->session->userdata('success'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="tableclass">
                                <?php echo form_open("Phlebo_master_punchin_punchout/index", array("method" => 'GET')); ?>
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Phlebotomy Name</th>
											<!--th>Doctor Name</th-->
                                            <th style="width:220px">Punch In Date</th>
                                            <th>Punch In Time</th>
                                            <th>Punch Out Date</th>
                                            <th>Punch Out Time</th>
                                            <?php /* <th>Time</th> */ ?>
                                            <th>Distance</th>
                                            <th>Total Working Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td>
                                                <?php echo form_input(['name' => 'cfname', 'class' => 'form-control', 'placeholder' => 'Name', 'value' => $cfname]); ?>
                                            </td>
											<!--td></td-->
                                            <td>
                                                <?php echo form_input(['name' => 'date', 'class' => 'form-control', "id" => "fromDate", 'placeholder' => 'From Date', "readonly" => "", 'value' => $date, 'style' => 'width:49%;float:left']); ?>
                                                <?php echo form_input(['name' => 'end_date', "id" => "toDate", 'class' => 'form-control datepicker-input', 'placeholder' => 'To Date', "readonly" => "", 'value' => $end_date, 'style' => 'width:49%;float:left']); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <input type="submit" name="search" class="btn btn-sm btn-success" value="Search" />
                                                <input type="button" onclick="window.location = '<?= base_url() ?>Phlebo_master_punchin_punchout/index';" name="reset" class="btn btn-sm btn-primary" value="Reset" />
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = $pages;
                                        foreach ($query as $row) {
                                            $cnt++;
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?> </td>
                                                <td><?php echo $row['name']; ?></td>
												<!--td><?php echo $row['full_name']; ?></td-->
                                                <td><?php echo date("d-M-Y", strtotime($row['start_date'])); ?></td>
                                                <td><?php echo date("g:i A", strtotime($row['start_time_new'])); ?></td>
                                                <td><?php
                                                    if (!empty($row['stop_date'])) {
                                                        echo date("d-M-Y", strtotime($row['stop_date']));
                                                    } else {
                                                        echo "-";
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    if (!empty($row['stop_time_new'])) {
                                                        echo date("g:i A", strtotime($row['stop_time_new']));
                                                    } else {
                                                        echo "-";
                                                    }
                                                    ?></td>
                                                <?php /* <td><?php if($row['time']!='00:00:00'){ echo $row['time']; }else{ echo "-"; } ?></td> */ ?>
                                                <td><?php
                                                    if ($row['in_riding'] != "" && $row['out_riding'] != "") {
                                                        echo $row['out_riding'] - $row['in_riding'];
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    $time = $row['Total_working_hour'];
                                                    //echo $time;


                                                    $value1 = $time;
                                                    $arr1 = explode(':', $value1);
                                                    $totalMinutes = (int) $arr1[0] * 60 + (int) $arr1[1] + (int) $arr2[0] * 60 + (int) $arr2[1];
                                                    $hours = (int) ($totalMinutes / 60);
                                                    $minutes = $totalMinutes % 60;
                                                    echo abs($hours) . ' Hour ' . abs($minutes) . ' Minutes';
                                                    ?></td>
                                                <td>  
                                                    <?php /* <a  href='<?php echo base_url(); ?>package_category
                                                      /package_category_edit/<?php echo $row['id']; ?>' data-original-title="edit" data-toggle="tooltip" <i style="margin-left:3px; "class="fa fa-edit"> </i> </a>
                                                      <a  href='<?php echo base_url(); ?>package_category
                                                      /package_category_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i style="margin-left:10px; " class="fa fa-trash-o"></i></a> */ ?>
                                                    <a  href='<?php echo base_url(); ?>Phlebo_master_punchin_punchout/details/<?php echo $row['id']; ?>' data-original-title="edit" data-toggle="tooltip" <i style="margin-left:3px;" class="fa fa-eye"> </i> </a>  
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($cnt == '0') {
                                            ?>
                                            <tr>
                                                <td colspan="9"><center>No records found</center></td>
                                        </tr>
                                    <?php }
                                    ?>
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
    <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>

    <script type="text/javascript">
                                                    /*  $(function () {
                                                     
                                                     $('#example3').dataTable({
                                                     //"bPaginate": false,
                                                     "bLengthChange": false,
                                                     "bFilter": false,
                                                     "bSort": false,
                                                     "bInfo": false,
                                                     "bAutoWidth": false
                                                     });
                                                     }); */

    </script>
    <script type="text/javascript">
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 4000);</script>