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
            Branch Collection Log Details
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Branch Collection Log Details</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo 'Branch:- ' . $query[0]['branch_name'] ?></h3>
                        <a style="float:right; margin-left:5px" href='<?php echo base_url(); ?>Branch_collections/export_installment/<?php echo $cid; ?>' class="btn btn-primary btn-sm"> Export</a>
                        <a style="float:right;" href='<?php echo base_url(); ?>Branch_collections/add_installment/<?php echo $cid; ?>' class="btn btn-primary btn-sm" > Add Installment</a>
<!--                        <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
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
                            <?php echo form_open("Branch_collections/index", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date Of Receipt</th>
                                        <th>Mode Of Payment</th>
                                        <th>Payment Details</th>
                                        <th>Amount (Rs.)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    $sum = 0;
                                    foreach ($query as $row) {
                                        $sum += $row['installment'];
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo $row['receipt_date']; ?></td>
                                            <td><?php echo $row['payment_mode']; ?></td>
                                            <td><?php echo $out = strlen($row['remark']) > 80 ? substr($row['remark'], 0, 80) . "..." : $row['remark']; ?></td>
                                            <td><?php echo $row['installment']; ?></td>
                                    <style>
                                        .fa
                                        {
                                            margin-right:10px;
                                        }
                                    </style>
                                    <td>
    <!--                                        <a target="_blank" href='<?php echo base_url(); ?>Branch_collections/details/<?php echo $row['id']; ?>' class="btn btn-primary btn-sm">Manage Installment</a> &nbsp;-->
                                        <a href='<?php echo base_url(); ?>Branch_collections/edit_installment/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                        <a  href='<?php echo base_url(); ?>Branch_collections/delete_installment/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                                if (empty($query)) {
                                    ?>
                                    <tr><td colspan="7"><center>Data not available.</center></td></tr>
                                <?php } ?>
                                <?php if (!empty($query)) { ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th>Total</th>
                                        <td><b><?php echo $sum; ?></b></td>
                                        <td></td>
                                    </tr>
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