<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Machine List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard1"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Machine List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Machine List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>inventory/machine/machine_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <!--<a style="float:right;" href='<?php echo base_url(); ?>test_master/test_csv' class="btn btn-primary btn-sm" ><strong > Export</strong></a>
                        <a style="float:right;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>-->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if ($this->session->flashdata('unsuccess')) { ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('unsuccess'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $this->session->flashdata('success')[0]; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('inventory/machine/machine_list', $attributes); ?>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="search" placeholder="Enter Machine Name" value="<?php echo $item_name; ?>"/>
                        </div>
<!--                        Vishal COde Start -->
                        <div class="col-md-3">
                           <select name="branch_name" class="chosen chosen-select" data-placeholder="choose a language...">
                                                         <option value="">--Select Branch--</option>
                                                        <?php foreach($branchlist as $val){ ?>
                                                              <option value="<?php echo $val['id']; ?>" <?php
                                                    if ($branch_id == $val['id']) {
                                                        echo " selected";
                                                    }
                                                    ?> ><?php echo $val['branch_name']; ?></option>
                                                      
                                                        <?php } ?>
                                                     </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="reagent_name" placeholder="Enter Reagent Name" value="<?php echo $reagent_name; ?>" />
                        </div>
                      
                       <!-- Vishal COde End -->
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Branch</th>
                                        <th>Reagent Name</th>
                                       
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                         ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['NAME']); ?></td>
                                            <td><?php 
                                                $old_branch = explode(',', $row['BranchName']);
                                                foreach($old_branch as $branch){
                                                    echo ucwords($branch)."<br>";
                                                }
                                             ?></td>
                                            <td><?php 
                                            $old_agent = explode(',', $row['ReagentName']);
                                            foreach($old_agent as $agent_name){
                                                 echo ucwords($agent_name)."<br>";
                                            } ?></td>
                                             
                                            <td><a href='<?php echo base_url(); ?>inventory/machine/add_test/<?php echo $row["id"];?>' data-toggle="tooltip" data-original-title="Add Test"><i class="fa fa-plus"></i></a>
                                                <a href='<?php echo base_url(); ?>inventory/machine/machine_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/machine/machine_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                        <?php $cnt++; ?>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Records Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php ?>
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
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                        $nc = $.noConflict();
                        $nc(function () {

                            $nc('.chosen-select').chosen();

                        });

</script> 

