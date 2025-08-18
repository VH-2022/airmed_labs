<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Reagent List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Reagent List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Reagent List</h3>
                       
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
                        <?php echo form_open('inventory/Item_master/item_list', $attributes); ?>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" placeholder="Enter Reagent Name" value="<?php echo $item_name; ?>"/>
                        </div>
                        
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="box_price" placeholder="Enter Price" value="<?php echo $box_price; ?>"/>
                        </div>
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="hsn_code" placeholder="Enter HSN Code" value="<?php echo $hsn_code; ?>"/>
                        </div>
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                       <th>No</th>
                                        <th>Machine</th>
                                        <th>Reagent Name</th>
                                        <th>Pack Size</th>
                                        <th>Unit</th>
                                        <th>Test Quantity</th>
                                        <th>Price</th>
                                        <th>Brand</th>
                                         <th>HSN Code</th>
                                         <th>Remark</th>
                                         
                                         
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr>  <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['MachineName']); ?></td>
                                            <td><?php echo ucwords($row['reagent_name']); ?></td>
                                            <td><?php echo ucwords($row['per_pack']); ?></td>
                                            <td><?php echo ucwords($row['UnitName']); ?></td>
                                            <td><?php echo ucwords($row['test_quantity']); ?></td>
                                            <td><?php echo $row['box_price']; ?></td>
                                            <td><?php echo ucfirst($row['BrandName']);?></td>
                                             <td><?php echo $row['hsn_code']; ?></td>
                                            <td><?php echo ucwords($row['remark']); ?></td>
                                           
                                           
                                           <!--  <td><a href='<?php echo base_url(); ?>inventory/Item_master/item_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/Item_master/item_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td> -->
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

