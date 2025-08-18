<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Vendor List<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Vendor List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Vendor List</h3>
                        <a style="float:right;" href='<?php echo base_url(); ?>inventory/Vendor_master/add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Vendor Add</strong></a>
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
                        <?php echo form_open('inventory/Vendor_master/index', $attributes); ?>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="search" placeholder="Enter Vendor Name" value="<?php echo $name; ?>"/>
                        </div>
                        <div class="col-md-2">
                            <select name="city" class="form-control">
                                <option value="">Select City</option>
                                <?php foreach($city as $val){ ?>
                                <option value="<?php echo $val['city_fk'];?>" <?php if($city_one == $val['city_fk']){ echo "selected='selected'";}?>><?php echo $val['name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="mobile" placeholder="Enter Mobile No" value="<?php echo $mobile; ?>"/>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="phone_no" placeholder="Enter Contact No" value="<?php echo $phone_no; ?>"/>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="email" placeholder="Enter Email Id" value="<?php echo $email; ?>"/>
                        </div>
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="cp_name" placeholder="Enter Contact Person Name" value="<?php echo $cp_name; ?>"/>
                        </div>
                         <div class="col-md-2">
                            <input type="text" class="form-control" name="cp_email_id" placeholder="Enter Contact Person Email Id" value="<?php echo $cp_email_id; ?>"/>
                        </div>
                                               
                       
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
                        <a style="float:right;" href='<?php echo base_url(); ?>inventory/vendor_master/export_csv?search=<?php echo $name;?>&city=<?php echo $city_one;?>&mobile=<?php echo $mobile;?>&phone_no=<?php echo $phone_no;?>&email=<?php echo $email;?>&cp_name=<?php echo $cp_name;?>&cp_email_id=<?php echo $cp_email_id;?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > Export Csv</strong></a>
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Vendor Name</th>    
                                        <th>City</th>
                                        <th>Address</th>
                                        <th>Mobile</th>
                                        <th>Contact No</th>
                                        <th>Email Id</th>
                                        <th>Contact Person Name</th>
                                        <th>Contact Person Email Id</th>
                                        
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) { 
                                        ?>
                                        <tr> <td><?php echo $count+ $cnt; ?></td>
                                            <td><?php echo ucwords($row['vendor_name']); ?></td>

                                            <td><?php if($row['city_fk'] !=''){
                                                $city= ucwords($row['CityName']);
                                            }else{
                                                $city = ucfirst($row['city_name']);
                                            }
                                            echo $city;
                                             ?></td>
                                            <td><?php echo ucwords($row['address']); ?></td>
                                            <td><?php echo ucwords($row['mobile']); ?></td>
                                            <td><?php echo $row['contact_no_2']; ?></td>
                                             <td><?php echo $row['email_id']; ?></td>
                                              <td><?php echo ucwords($row['cp_name']); ?></td>
                                               <td><?php echo $row['cp_email_id']; ?></td>
                                            <td><a href='<?php echo base_url(); ?>inventory/Vendor_master/edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
                                                <a style="margin-left:12px" href='<?php echo base_url(); ?>inventory/Vendor_master/delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                        <?php $cnt++; ?>
                                    <?php }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="8">No Records Found</td>
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

