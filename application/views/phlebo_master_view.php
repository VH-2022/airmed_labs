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
                            <li class="nav-item active">
                                <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_page" role="tab">Phlebo Master</a>
                            </li>
                            <li class="nav-item">
                                <?php
                                if ($_GET['phlebo'] != '') {
                                    ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_report/index?start_date=&end_date=&city=&phlebo_name=<?php echo $_GET['phlebo'] ?>&sample_collect=&search=Search" role="tab">Phlebo Visit Report</a>
                                <?php } else { ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_report" role="tab">Phlebo Visit Report</a>
                                <?php }
                                ?>
                            </li>
                            <li class="nav-item">
                                <?php if ($_GET['phlebo'] != '') { ?>
                                    <a class="nav-link" href="<?php echo base_url() ?>Phlebo_master_added_jobs/index?start_date=&end_date=&city=&phlebo_name=<?php echo $_GET['phlebo'] ?>&sample_collect=&search=Search" role="tab">Phlebo Added Jobs</a>
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




    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Phlebo List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>phlebo_master_page/phlebo_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>phlebo_master_page" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Test City</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
                                            <td><input type="text" placeholder="Email" class="form-control" name="email" value="<?php echo $email; ?>"/></td>
                                            <td><input type="text" placeholder="Mobile" class="form-control" name="mobile" value="<?php echo $mobile; ?>"/></td>
                                            <td><select name="test_city" class="form-control"><option value="">--All--</option><?php
                                                    foreach ($test_city as $tkey) {
                                                        echo '<option value="' . $tkey["id"] . '" ';
                                                        if ($test_city1 == $tkey["id"]) {
                                                            echo " selected";
                                                        } echo '>' . ucfirst($tkey["name"]) . '</option>';
                                                    }
                                                    ?></select></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo ucwords($row['name']); ?></td>
                                                <td><?php echo ucwords($row['email']); ?></td>
                                                <td><?php echo ucwords($row['mobile']); ?></td>
                                                <td><?php
                                                    foreach ($test_city as $key) {
                                                        if ($key["id"] == $row['test_city']) {
                                                            echo ucfirst($key["name"]);
                                                        }
                                                    }
                                                    ?></td>
                                                <td>
                                                    <a target="_blank" href='<?php echo base_url(); ?>phlebo_master_report/index??start_date=&end_date=&city=&phlebo_name=<?php echo $row['id']; ?>&sample_collect=&search=Search' class="btn btn-primary btn-sm"> Phlebo Visit Report</a>
                                                    <a href='<?php echo base_url(); ?>Phlebo_master_added_jobs/index??start_date=&end_date=&city=&phlebo_name=<?php echo $row['id']; ?>&sample_collect=&search=Search' class="btn btn-primary btn-sm" target="_blank"> Phlebo Added Jobs</a>
                                                    <a href='<?php echo base_url(); ?>phlebo_master_page/phlebo_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                    <?php  if($row['status']=="1"){ ?>
                                                      <a  href='<?php echo base_url(); ?>phlebo_master_page/phlebo_deactive/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Deactive" ><span class="label label-success">Active</span></a>
                                                      <?php }else {?>
                                                      <a  href='<?php echo base_url(); ?>phlebo_master_page/phlebo_active/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="active" ><span class="label label-danger">Deactive</span> </a>
                                                      <?php }  ?>      
                                                    <a  href='<?php echo base_url(); ?>phlebo_master_page/phlebo_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="5">No records found</td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </form>
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
    </section>






