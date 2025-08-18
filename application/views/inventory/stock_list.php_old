<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Stock Manage<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><i class="fa fa-users"></i>Stock Manage</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Stock List</h3>
                        <!-- <a style="float:right;" href='javascript:void(0);' onclick="$('#myModal').modal('show');" class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a> -->
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
                        <?php echo form_open('inventory/Stock_master/stock', $attributes); ?>
                        <div class="col-md-2" style="padding-left:0px;width: 15%;">
                           <select name="branch_name" class="form-control">
                            <option value="">Select Branch</option>
                            <?php foreach($branch_list as $val) { ?>
                            <option value="<?php echo $val['id'];?>"<?php if($branch_name == $val['id']){
                                echo "selected='selected'";}?>><?php echo $val['branch_name'];?>
                            <?php } ?>
                           </select>
                        </div>
                         <div class="col-md-2" style="padding-left:0px;width: 13%;">
                            <input type="text" class="form-control" name="search" placeholder="Search Item" value="<?php
                            if (isset($item_name)) {
                                echo $item_name;
                            }
                            ?>"/>
                        </div>
                        <div class="col-md-2" style="padding-left:0px;width:  15%;">
                           <select name="machine_name" class="form-control">
                            <option value="">Select Machine</option>
                            <?php foreach($machine as $val) { ?>
                            <option value="<?php echo $val['id'];?>"<?php if($machine_name == $val['id']){
                                echo "selected='selected'";}?>><?php echo $val['name'];?>
                            <?php } ?>
                           </select>
                        </div>
                         <div class="col-md-2" style="padding-left:0px;width:  15%;">
                            <input type="text" class="form-control" name="category_name" placeholder="Search Category" value="<?php
                            if (isset($category_name)) {
                                echo $category_name;
                            }
                            ?>"/>
                        </div>
                        <div class="col-md-1" style="padding-left:0px;width:  10%;">
                            <input type="text" class="form-control" name="stock" placeholder="Search stock" value="<?php
                            if (isset($stock)) {
                                echo $stock;
                            }
                            ?>"/>
                        </div>
                        <div class="col-md-2" style="padding-left:0px;width: 12%;">
                            <input type="text" class="form-control datepicker" name="start_date" placeholder="Search Added Date" value="<?php
                            if (isset($start_date)) {
                                echo $start_date;
                            }
                            ?>"/>
                        </div>
                        <div class="col-md-2" style="padding-left:0px;width: 12%;">
                            <input type="text" class="form-control datepicker" name="expiry" placeholder="Search Expiry Date" value="<?php
                            if (isset($expiry)) {
                                echo $expiry;
                            }
                            ?>"/>
                        </div>
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
                        </form>
                        <br> 
                        <div id="hidden_test"></div>
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Branch Name</th>
                                        <th>Item Name</th>
                                        <th>Machine Name</th>
                                        <th>Category Name</th>
                                        <th>Available Stock (Now)</th>
                                         <th>Added Date</th>
                                         <th>Expiry Date</th>
<!--                                        <th>Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($final_array as $row) {
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row[0]['Branchname']);?></td>
                                            <td><?php echo ucwords($row[0]['item_name']); ?></td>
                                            <td><?php echo ucwords($row[0]['Machinename']); ?></td>
                                            <td><?php echo ucwords($row[0]['category_name']); ?></td>
                                            <td <?php if($row[0]['total']<11){ ?> style="background-color: #f2dede" <?php } ?>><?php if($row[0]['unit_name'] !=''){
                                                $new = '('.$row[0]['unit_name'].')';
                                            } echo ucwords($row[0]['total'].$new); ?></td>
<!--                                            <td><a href='<?php echo base_url(); ?>inventory/Test_item/test_manage/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Manage"><i class="fa fa-list"></i></a></td> --> 
                                        <td><?php 
                                            $date = $row[0]['created_date'];
                                            $timstamp = explode(" ",$date);
                                            $old = $timstamp[0];
                                            $suy = date("d-m-Y",strtotime($old));
                                          
                                        echo $suy;?></td>
                                        <td><?php echo date("d-m-Y",strtotime($row[0]['expire_date']));?></td>
                                        </tr>
                                        <?php $cnt++; ?>
                                    <?php }if (empty($final_array)) {
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


                        <?php /* Modal START */ ?>
                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Manage Stock</h4>
                                    </div>
                                    <?php echo form_open("inventory/Stock_master/add_stock/" . $id, array("method" => "POST", "role" => "form")); ?>
                                    <div class="modal-body">
                                        <div id="items_list">
                                            <div class="form-group">
                                                <label for="message-text" class="form-control-label">Add Item:<span style="color:red">*</span></label>
                                                <select class="chosen chosen-select" name="item" required="">
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    $item_list = '<option value="">--Select--</option>';
                                                    foreach ($item as $mkey) {
                                                        $item_list = $item_list . '<option value="' . $mkey["id"] . '">' . $mkey["name"] . '</option>';
                                                        ?>
                                                        <option value="<?= $mkey["id"] ?>"><?= $mkey["name"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="message-text" class="form-control-label">Type:<span style="color:red">*</span></label>
                                                <select class="chosen chosen-select" name="type" required="">
                                                    <option value="1">Credit</option>
                                                    <option value="2">Debit</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="message-text" class="form-control-label">Quantity:<span style="color:red">*</span></label>
                                                <input type="number" value="0" name="quantity" class="form-control" required=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Add</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    <?php echo form_close(); ?>
                                </div>

                            </div>
                        </div>
                        <?php /* Modal END */ ?>


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
    $('.chosen').trigger("chosen:updated");
</script> 
<script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
 $('.datepicker').datepicker({
                                    dateFormat: 'dd-mm-yyyy'
                                 
                                });
</script>
