<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Handover Item List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Handover Item List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Handover Item List</h3>
                             <a style="float:right;" href='<?php echo base_url(); ?>inventory/Handover_item/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong >Add</strong></a>

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
                        <?php echo form_open('inventory/handover_item/index', $attributes); ?>
                        <div class="col-md-3">
                            <select name="user_fk" class="form-control">
                                <option value="">Select User</option>
                                <?php foreach($user_list as $val) {
                                        if(!in_array($val['name'],$use)){
                                    ?>
                                <option value="<?php echo $val['id'];?>" <?php if($user == $val['id']){echo "selected='selected'";};?>><?php echo $val['name'];?></option>
                                <?php }  $use[] = $val['name'];} ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="branch_fk" class="form-control">
                                <option value="">Select Branch</option>
                                <?php foreach($branch_list as $val) {?>
                                <option value="<?php echo $val['id'];?>" <?php if($branch == $val['id']){echo "selected='selected'";};?>><?php echo $val['branch_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                         
                        
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                       <th>No</th>
                                       <th>Handover Name</th>
                                        <th>Branch Name</th>
                                        <th>Reagent Name</th>
                                        <th>Batch No</th>
<!--                                        <th>Quantity</th>-->
                                        <th>Handover Quantity</th>
<!--                                        <th>Stock Availabe</th>-->

                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) { 
                                        ?>
                                        <tr>  <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucfirst($row['name']);?></td>
                                            <td><?php echo ucwords($row['branch_name']); ?></td>
                                            <td><?php echo ucwords($row['reagent_name']); ?></td>
                                            <td><?php echo ucwords($row['Batch']); ?></td>
<!--                                            <td><?php echo $row['Quantity'];?></td>-->
                                            <td><?php echo $row['asIUQuantity'];?></td>
<!--                                              <td><?php $minus = ($row['Quantity']-$row['asIUQuantity']);
                                              if($minus){
                                                $availabe_stock =  $minus;
                                              }else{
                                                $availabe_stock = 'Stock Not available';
                                              }

                                              echo $availabe_stock;?></td>-->
                                         
                                           
                                            <td> <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/Handover_item/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
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

