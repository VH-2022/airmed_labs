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

<link href="<?php echo base_url(); ?>plugins/timepick/css/timepicki.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>/plugins/TimePicki-master/css/timepicki.css" rel="stylesheet">

<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
                    <option value="<?= $mkey["id"] ?>"><?= $mkey["name"] ?></option>
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
                        <li class="nav-item active">
                            <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_page" role="tab">Phlebo Master</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_report" role="tab">Phlebo Visit Report</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0)" role="tab">Phlebo Added Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_punchin_punchout/index?phlebo_name=<?php echo $_GET['phlebo'] ?>&search=Search" role="tab">Phlebotomy Punch In/Out Report</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Add Phlebo</h3>
                </div>

                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>

                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>phlebo_master_page/phlebo_add" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Phlebo Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control"  value="<?php echo set_value('name'); ?>" >
                                <span style="color: red;"><?= form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Email</label><span style="color:red">*</span>
                                <input type="text"  name="email" class="form-control"  value="<?php echo set_value('email'); ?>" >
                                <span style="color: red;"><?= form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Mobile</label><span style="color:red">*</span>
                                <input type="text"  name="mobile" class="form-control"  value="<?php echo set_value('mobile'); ?>" >
                                <span style="color: red;"><?= form_error('mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Password</label><span style="color:red">*</span>
                                <input type="password"  name="password" class="form-control"  value="<?php echo set_value('password'); ?>" >
                                <span style="color: red;"><?= form_error('password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Confirm Password</label><span style="color:red">*</span>
                                <input type="password"  name="confirm_password" class="form-control"  value="<?php echo set_value('confirm_password'); ?>" >
                                <span style="color: red;"><?= form_error('confirm_password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Type</label><span style="color:red">*</span>
                                <select name="type" class="form-control">
                                    <option value="1">Phlebo</option>
                                    <option value="2">Logistic</option>
                                    <option value="3">Society camp</option>
                                </select>
                                <span style="color: red;"><?= form_error('type'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Test City</label><span style="color:red">*</span>
                                <select name="test_city" class="form-control">
                                    <option value="">--Select--</option>
                                    <?php foreach ($test_city as $key) { ?>
                                        <option value="<?= $key["id"] ?>"><?= ucfirst($key["name"]) ?></option>
                                    <?php } ?>
                                </select>
                                <span style="color: red;"><?= form_error('test_city'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="start_time">Start Time</label><span style="color:red">*</span>
                                <input type="text"  name="start_time" class="form-control" id="timepicker1" placeholder="Start Time" onblur="OnBlurInput(id)" value="<?php echo set_value('start_time'); ?>" >
                                <?php echo form_error('start_time'); ?>
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time</label><span style="color:red">*</span>
                                <input type="text"  name="end_time" class="form-control" id="timepicker2" placeholder="End Time" onblur="OnBlurInput(id)" value="<?php echo set_value('end_time'); ?>" >
                                <?php echo form_error('end_time'); ?>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Employee Code</label>
                                <input type="text"  name="empcode" class="form-control"  value="<?php echo set_value('empcode'); ?>" >
                                <span style="color: red;"><?= form_error('empcode'); ?></span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <div class="col-md-6">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </div>
            </div>	
            </form>
        </div><!-- /.box -->
    </div>
</div>
</div>
</section>
<script src="<?php echo base_url(); ?>plugins/timepick/js/timepicki.js"></script>
<script>
                                    $('#timepicker1').timepicki({
                                        show_meridian: false,
                                        min_hour_value: 0,
                                        max_hour_value: 23,
                                        overflow_minutes: true,
                                        increase_direction: 'up',
                                        disable_keyboard_mobile: true
                                    });
                                    $('#timepicker2').timepicki({
                                        show_meridian: false,
                                        min_hour_value: 0,
                                        max_hour_value: 23,
                                        overflow_minutes: true,
                                        increase_direction: 'up',
                                        disable_keyboard_mobile: true});
</script>