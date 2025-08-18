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
                    foreach ($phlebo_list1 as $mkey) {
                        ?>
                        <option value="<?= $mkey["id"] ?>" <?php
                        if ($_GET['phlebo_name'] == $mkey["id"]) {
                            echo "selected";
                        }
                        ?>><?= ucwords($mkey["name"]) ?></option>
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
                            <li class="nav-item active">
                                <a class="nav-link" href="javascript:void(0)" role="tab">Phlebo Visit Report</a>
                            </li>
                            <li class="nav-item">
                                <?php if ($_GET['phlebo_name'] != '') {
                                    ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_added_jobs/index?start_date=&end_date=&city=&phlebo_name=<?php echo $_GET['phlebo_name'] ?>&sample_collect=&search=Search" role="tab">Phlebo Added Jobs</a>
                                <?php } else { ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_added_jobs" role="tab">Phlebo Added Jobs</a>
                                <?php }
                                ?>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_punchin_punchout/index?phlebo_name=<?php echo $_GET['phlebo'] ?>&search=Search" role="tab">Phlebotomy Punch In/Out Report</a>
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
        .chosen-container {width: 100% !important;}
    </style>
    <script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Phlebo Visit Report
                <small></small>
            </h1>
            <!--            <ol class="breadcrumb">
                            <li><a href="<?php //echo base_url();       ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                            <li>Phlebo Report</li>
            
                        </ol>-->
        </section>
        <?php
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $city = $_GET['city'];
        $phlebo = $_GET['phlebo_name'];
        $s_collect = $_GET['sample_collect'];
        ?>
        <?php //echo "<pre>";print_r($phlebo);die;?>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <div class="widget">
                                <?php if (isset($success) != NULL) { ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $success['0']; ?>
                                    </div>
                                <?php } ?>

                            </div>
                            <!-- Search start-->
                            <?php echo form_open("Phlebo_master_report", array("method" => "GET", "role" => "form")); ?>
                            <div class="row">
                                <div class="col-sm-2">
                                    <input type="text" name="start_date" placeholder="Select date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" style="width:190px;" />
                                </div>

                                <div class="col-sm-2">
                                    <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" style="width:190px;"/>
                                </div>

                                <div class="col-sm-2">
                                    <select class="form-control chosen-select" id="city_id" data-placeholder="Select city" tabindex="-1" name="city" onchange="get_branch1();" style="width:190px;">
                                        <option value="" >All City</option>
                                        <?php foreach ($test_city as $cities) { ?>
                                            <option value="<?php echo $cities['id']; ?>" <?php
                                            if ($city == $cities['id']) {
                                                echo " selected ";
                                            } if ($city == '') {
                                                if ($cities['id'] == $city) {
                                                    echo "selected";
                                                }
                                            }
                                            ?>><?php echo ucwords($cities['name']); ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                                <?php //echo "<prE>";print_r($branch_list);die;  ?>
                                <div class="col-sm-2"> 
                                    <select class="form-control chosen" data-placeholder="Select Branch" tabindex="-1" name="phlebo_name" id="branch1">
                                        <option value="" >All Phlebo</option>
                                        <?php foreach ($phlebo_list as $new_phlebo) { ?>
                                            <option value="<?php echo $new_phlebo['PID']; ?>" <?php
                                            if ($phlebo == $new_phlebo['PID']) {
                                                echo " selected ";
                                            } if ($phlebo == '') {
                                                if ($new_phlebo['PID'] == $phlebo) {
                                                    echo "selected";
                                                }
                                            }
                                            ?>><?php echo ucwords($new_phlebo['PhleboName']); ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2"> 
                                    <select class="form-control chosen-select" id="city_id" data-placeholder="Select Sample Collect" tabindex="-1" name="sample_collect"  style="width:190px;">
                                        <option value="" >All Sample Collect</option>
                                        <?php foreach ($sample_collect as $sample) { ?>
                                            <option value="<?php echo $sample['id']; ?>" <?php
                                            if ($s_collect == $sample['id']) {
                                                echo " selected ";
                                            } if ($s_collect == '') {
                                                if ($sample['id'] == $s_collect) {
                                                    echo "selected";
                                                }
                                            }
                                            ?>><?php echo ucwords($sample['name']); ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="submit" name="search" class="btn btn-success" value="Search" />
                                    <?php if ($start_date != '' || $end_date != '' || $phlebo != '' || $city != '' || $s_collect != '') { ?>
                                        <a href="<?php echo base_url(); ?>Phlebo_master_report/export?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&city=<?php echo $city; ?>&phlebo=<?php echo $phlebo; ?>&sample_collect=<?php echo $s_collect; ?>" class="btn btn-primary">Export</a><?php } ?>
                                </div>
                            </div>
                            <br>
                            <?php echo form_close(); ?>
                            <!--Search end-->

                            <div class="tableclass">
                                <div class="table-responsive" id="prnt_rpt">
                                    <?php if ($start_date != '' || $end_date != '' || $phlebo != '' || $city != '' || $s_collect != '') { ?>
                                        <table id="example4" class="table table-bordered table-striped">
                                            <h2><?php if (trim($start_date) != '' || trim($end_date) != '') { ?><?= $start_date ?> To <?= $end_date ?><?php } ?></h2>

                                            <thead>
                                                <tr>
                                                    <th width="5%"><h4>No.</h4></th>
                                            <th width="10%"><h4>Phlebo Name</h4></th>
                                            <th width="13%"><h4>Reg No</h4></th>
                                            <th width="15%"><h4>Patient Name</h4></th>
                                            <th width="15%"><h4>Doctor Name</h4></th>
                                            <th width="17%"><h4>Date Time</h4></th>
                                            <th width="20%"><h4>Address</h4></th>
                                            <th width="15%"><h4>Note</h4></th>
                                            <th width="10%"><h4>Sample Collect</h4></th>
                                            <th width="5%"><h4>Price</h4></th>
                                            <th width="5%"><h4>Status</h4></th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cnt = 1;
                                                foreach ($view_report as $am_br) {
                                                    if ($am_br['start_time'] != '' && $am_br['end_time'] != '') {
                                                        $all_time = $am_br['start_time'] . '&nbsp;&nbsp;To&nbsp;&nbsp;' . $am_br['end_time'];
                                                    } else {
                                                        $all_time = $am_br['time'];
                                                    }
                                                    if ($am_br['note'] != '') {
                                                        $note = $am_br['note'];
                                                    } else {
                                                        $note = "N/A";
                                                    }
                                                    if ($am_br['DoctorName'] != '') {
                                                        $doctor = $am_br['DoctorName'];
                                                    } else {
                                                        $doctor = "N/A";
                                                    }
                                                    if ($am_br['SampleName'] != '') {
                                                        $sm_collect = $am_br['SampleName'];
                                                    } else {
                                                        $sm_collect = "N/A";
                                                    }

                                                    $old_date = $am_br['date'];
                                                    $new_date = date('d-m-Y', strtotime($old_date));
                                                    $first_name = strtolower($am_br['full_name']);
                                                    $new_phlebo_name = strtolower($am_br['PhleboName']);
                                                    $new_address = strtolower($am_br['address']);
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo ucwords($new_phlebo_name); ?></td>
                                                        <td><a href="<?php echo base_url(); ?>job-master/job-details/<?php echo $am_br['jid']; ?>" target="_blank"><?php echo $am_br['jid'] . '&nbsp;(' . $am_br['order_id'] . ')'; ?>  
                                                        </td>
                                                        <td><?php echo ucwords($first_name); ?></td>
                                                        <td><?php echo $doctor; ?></td>

                                                        <td><?php echo $new_date . '&nbsp;&nbsp;&nbsp;' . $all_time; ?></td>
                                                        <td><?php echo ucwords($new_address); ?></td>
                                                        <td><?php echo $note; ?></td>
                                                        <td><?php echo $sm_collect; ?></td>
                                                        <td><?php echo "Rs." . $am_br["price"]; ?></td>
                                                        <td><?php
                                                            if ($am_br['is_accept'] == 1) {
                                                                echo "<span class='label label-success '>Accepted</span>";
                                                            } else {
                                                                echo "<span class='label label-warning '>Pending</span>";
                                                            }
                                                            ?></td>

                                                    </tr>

                                                    <?php
                                                    $cnt++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>   
                                    <?php
                                    if (empty($view_report)) {
                                        echo "<tr><td colspan='8'><center>Record not found.</center></td></tr>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
    <script  type="text/javascript">
                                        jQuery(".chosen-select").chosen({
                                            search_contains: true
                                        });
                                        $(function () {
                                            $('.chosen').chosen();
                                        });

    </script> 
    <script type="text/javascript">
        $(function () {

            $('#example3').dataTable({
                //"bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": false,
                "bInfo": false,
                "bAutoWidth": false
            });
        });
        function get_branch1() {
            var city_id = $('#city_id').val();

            $.ajax({
                url: '<?php echo base_url(); ?>Phlebo_master_report/getPhleboName',
                type: 'post',
                data: {city_id: city_id},
                success: function (data) {

                    $("#branch1").html(data);
                    $('.chosen').trigger("chosen:updated");

                },
                error: function (jqXhr) {
                    $("#branch1").html("");
                },
                complete: function () {
                },
            });
        }
    </script>