<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Lab Consumables List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Lab Consumables List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Consumables List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>inventory/Consumes_master/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong >Lab Consumables Add</strong></a>
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
                        <?php echo form_open('inventory/Consumes_master/consumes_list', $attributes); ?>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="search" placeholder="search" value="<?php echo $lab_name; ?>"/>
                        </div>
                          <div class="col-md-2">
                            <select name="city" class="form-control">
                                <option value="">Select City</option>
                                <?php foreach($city_list as $val) { ?>
                                <option value="<?php echo $val['id'];?>"<?php if($city_fk == $val['id']){echo "selected ='selected'";}?>><?php echo $val['name'];?></option>
                                <?php } ?>
                           </select>
                        </div>
                           <div class="col-md-2">
                            <select name="unit_name" class="form-control">
                                <option value="">Select Unit</option>
                                <?php foreach($unit_list as $val) { ?>
                                <option value="<?php echo $val['id'];?>"<?php if($unit_name == $val['id']){echo "selected ='selected'";}?>><?php echo $val['name'];?></option>
                                <?php } ?>
                           </select>
                        </div>
                         <div class="col-md-2">
                            <select name="brand_name" class="form-control">
                                <option value="">Select Brand</option>
                                <?php foreach($brand_list as $val) { ?>
                                <option value="<?php echo $val['id'];?>"<?php if($brand_name == $val['id']){echo "selected ='selected'";}?>><?php echo $val['brand_name'];?></option>
                                <?php } ?>
                           </select>
                        </div>
                        
                        <div class="col-md-2">
                            <select name="type1" class="form-control">
                                <option value="">Type</option>
                                <option value="1" <?php
                                if ($type1 == "1") {
                                    echo "selected";
                                }
                                ?>>Pathology</option>
                                <option value="2" <?php
                                if ($type1 == "2") {
                                    echo "selected";
                                }
                                ?>>Radiology</option>
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
                                        <th>Name</th>
                                        <th>City Name</th>
                                        <th>Branch Name</th>
                                         <th>Unit Name</th>
                                      <th>Brand Name</th>
                                        <th>Quantity</th>
                                        <th>Box Price</th>
                                        <th>HSN Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr> <td><?php echo $counts+$cnt; ?></td>
                                            <td><?php echo ucwords($row['reagent_name']); ?></td>
                                            <td><?php echo ucwords($row['name']); ?></td>
                                            <td><?php echo ucwords($row['branch_name']); ?></td>
                                              <td><?php echo ucwords($row['UnitName']); ?></td>
                                               <td><?php echo ucwords($row['BrandName']); ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo $row['box_price']; ?></td>
                                            <td><?php echo $row['hsn_code']; ?></td>
                                            <td><a href='<?php echo base_url(); ?>inventory/Consumes_master/consumes_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/Consumes_master/consumes_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
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

