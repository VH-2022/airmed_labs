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
            Branch
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
                        <h3 class="box-title">Branch List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>Branch_Master/Branch_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                                                <!--<a style="float:right;" href='<?php echo base_url(); ?>manage_master/manage_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                                                <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
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
                            <?php echo form_open("Branch_Master/Branch_list", array("method" => 'GET')); ?>
                            <table id="example3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>

                                        <th>No</th>
                                        <th>Branch Name</th>

                                        <th>Test City</th>
                                        <th>Branch Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>

                                        <td><span style="color:red;">#</span></td>
                                        <td>
                                            <input type="text" name="branch_name" placeholder="Branch Name" value="<?= $branch_name; ?>" class="form-control"/>
                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1" name="test_city">
                                                <option value="">Select Test City</option>
                                                <?php foreach ($city as $br) { ?>
                                                    <option value="<?php echo $br['cid']; ?>" <?php
                                                    if ($test_city == $br['cid']) {
                                                        echo " selected";
                                                    }
                                                    ?> ><?php echo $br['city_name']; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control chosen-select" tabindex="-1" name="branch_type">
                                                <option value="">Select Branch Type</option>
                                                <?php foreach ($branch_type as $btype) { ?>
                                                    <option value="<?php echo $btype['id']; ?>" <?php
                                                    if ($btype_select == $btype['id']) {
                                                        echo " selected";
                                                    }
                                                    ?> ><?php echo $btype['name']; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="submit" name="search" class="btn btn-success" value="Search" />
                                            <a class="btn btn-primary" href="<?= base_url() . "Branch_Master/Branch_list"; ?>">Reset</a>
                                        </td>
                                    </tr>

                                    <?php
                                    $cnt = 1;

                                    foreach ($query as $row) {
                                        /*  echo "<pre>";
                                          print_r($row['test_name']);
                                          exit; */
										  $temp = "";
                                        if (empty($row['processing_center'])) {
                                            $temp = "background-color: #FFF1BC;background-color: #FFBDBC;";
                                        }
                                        ?>
                                        <tr style="<?= $temp ?>">
                                            <td><?php echo $cnt; ?> </td>
                                            <td><?php echo ucfirst($row['branch_name']); ?></td>

                                            <td><?php echo ucfirst($row['city_name']); ?></td>
                                            <td><?php echo ucfirst($row['BranchType']); ?></td>
                                            <td><?php if ($row['status'] == 1) { ?><span class="label label-success ">Active</span><?php } ?><?php if ($row['status'] == 2) { ?><span class="label label-danger ">Deactive</span><?php } ?>
											<?php if (empty($row['processing_center'])) { ?><br/><span class="label label-warning">Parameter Not Set</span><?php } ?>
											</td>
                                    <style>
                                        .fa
                                        {
                                            margin-right:10px;
                                        }
                                    </style>
                                    <td>
                                        <a href='<?php echo base_url(); ?>Branch_Master/Branch_view/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a> 
                                        <a href='<?php echo base_url(); ?>Branch_Master/Branch_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                        <?php if($login_data['type'] !=1 && $login_data['type'] != 2 ){?>
                                        <a  href='<?php echo base_url(); ?>Branch_Master/Branch_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                        <?php  } ?>
                                        <?php if ($row['id'] != 1) { ?>
                                            <a href='<?php echo base_url(); ?>Branch_Test_Price/edit_test_price/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Test Manage "><i class="fa fa-list"></i></a> 
                                        <?php } ?>

                                        <?php if ($row['status'] == 1) { ?>
                                            <a href='<?php echo base_url(); ?>Branch_Master/Branch_active_deactive/<?php echo $row['id']; ?>/2' data-toggle="tooltip" data-original-title="Deactive" onclick="return confirm('Are you sure?');"><i class="fa fa-toggle-on"></i></a> 
                                        <?php } ?>
                                        <?php if ($row['status'] == 2) { ?>
                                            <a href='<?php echo base_url(); ?>Branch_Master/Branch_active_deactive/<?php echo $row['id']; ?>/1' data-toggle="tooltip" data-original-title="Active" onclick="return confirm('Are you sure?');"><i class="fa fa-toggle-off"></i></a> 
                                        <?php } ?>

                                        <?php if($row['allow_whatsapp'] == 1){ ?>
                                            <a href="<?php echo base_url(); ?>Branch_Master/allow_whatsapp/<?php echo $row['id']; ?>/2" data-toggle="tooltip" 
                                           
                                            ><img src="<?php echo base_url(); ?>double tick4.png" style="width:16px;height:16px;" tooltip="Disable 
                                        Whatsapp Report " title="" data-toggle="tooltip" data-original-title="Disable Whatsapp Report ">
                                            <?php }else{ ?>
                                            <a href="<?php echo base_url(); ?>Branch_Master/allow_whatsapp/<?php echo $row['id']; ?>/1" data-toggle="tooltip" data-original-title="Enable Whatsapp Report "><img src="<?php echo base_url(); ?>whatsapp_icon.png" style="width:15px;height:15px;"></a>
                                            <?php } ?>
                                            &nbsp;
                                        <!-- data-original-title="Send Whatsapp Report Without Payment" -->
                                            <?php if($row['whatsapp_report_send'] == 1){ ?>
                                            <a href="<?php echo base_url(); ?>Branch_Master/whatsapp_report_send/<?php echo $row['id']; ?>/2" data-toggle="tooltip" 
                                           
                                            ><img src="<?php echo base_url(); ?>double tick4.png" style="width:16px;height:16px;" tooltip="Disable 
                                        Whatsapp Report Without Payment" title="" data-toggle="tooltip" data-original-title="Disable Whatsapp Report Without Payment">
                                            <?php }else{ ?>
                                            <a href="<?php echo base_url(); ?>Branch_Master/whatsapp_report_send/<?php echo $row['id']; ?>/1" data-toggle="tooltip" data-original-title="Enable Whatsapp Report Without Payment"><img src="<?php echo base_url(); ?>whatsapp_icon.png" style="width:15px;height:15px;"></a>
                                            <?php } ?>

                                            
                                        
                                    </td>

                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                                if (empty($query)) {
                                    ?>
                                    <tr><td colspan="4"><center>Data not available.</center></td></tr>
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
                                        //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
                                        // $("#cid").chosen('refresh');

</script> 
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