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
            Camp SMS List
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Camp SMS List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Camp SMS List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>camp_sms/add' class="btn btn-primary btn-sm" ><center><i class="fa fa-plus-circle" ></i>&nbsp <strong>Add</strong></center></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php $success = $this->session->flashdata('success'); ?>
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-autocloseable-success">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->userdata('success')[0]; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>camp_sms/index" method="get" enctype="multipart/form-data">
                                <table id="example3" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>SMS</th>
                                            <th>Schedule Date</th>
                                            <th>Sender</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td>
                                                <input type="text" name="sms" class="form-control" value="<?php echo $sms; ?>">
                                            </td>
                                            <td>
                                                <input type="text" placeholder="Enter Start date" name="date" class="form-control" value="<?php echo $date; ?>" style="width:45%;margin-right:10px;float:left;">
                                                <input type="text"  placeholder="Enter End date" name="end_date" class="form-control datepicker-input" value="<?php echo $end_date; ?>" style="width:45%;float:left;">
                                            </td>
                                            <td>
                                                <select name="sender" class="form-control">
                                                    <option value="">Select Sender</option>
                                                    <option value="1"<?php if ($sender == '1') {
                                echo "selected='selected'";
                            } ?>>Mobi blogdns</option>
<!--                                                    <option value="2" <?php if ($sender == '2') {
                                echo "selected='selected'";
                            } ?>>Sender2</option>-->
                                                </select>
                                            </td>
                                            <td>
                                                <select name="status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1" <?php if ($status == '1') {
                                echo "selected='selected'";
                            } ?>>Pending</option>
                                                    <option value="2"<?php if ($status == '2') {
                                echo "selected='selected'";
                            } ?>>Send</option>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                            </td>
                                        </tr>
                                                <?php
                                                $cnt = $pages;
                                                foreach ($query as $row) {
                                                    $cnt++;
                                                    ?>
                                            <tr>
                                                <td><?php echo $cnt; ?> </td>
                                                <td><?php echo ucfirst($row['sms']); ?></td>
                                                <td><?php
                                                if ($row['schedule_date'] != '') {
                                                    $time = $row['schedule_date'];
                                                    $ol = explode(' ', $time);
                                                    $odate = explode('-', $ol[0]);

                                                    $new = $odate[2] . '-' . $odate[1] . '-' . $odate[0] . ' ' . $ol[1];
                                                    echo $new;
                                                }
                                                    ?></td>
                                                <td><?php
                                                    if ($row['sender'] == '1') {
                                                        $sender = "Sender1";
                                                    }if ($row['sender'] == '2') {
                                                        $sender = "Sender2";
                                                    }
                                                    echo $sender;
                                                    ?></td>
                                                <td><?php
                                                     if ($row['schedule_date'] < date("Y-m-d H:i:s")) {
                                                        $status = "<span class='label label-success '>Send</span>";
                                                    }else{
                                                        $status = "<span class='label label-warning '>Pending</span>";
                                                    }
                                                    echo $status;
                                                    ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>Camp_sms/delete/<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    <a href="<?php echo base_url(); ?>Camp_sms/viewlist/<?php echo $row['id']; ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </td>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        if ($cnt == '0') {
                                            ?>
                                            <tr>
                                                <td colspan="3"><center>No records found</center></td>
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
<script type="text/javascript">
    $('.datepicker').datepicker();
</script>