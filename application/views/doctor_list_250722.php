<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Doctor
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>location-master/country-list"><i class="fa fa-users"></i>Doctor</a></li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="H4">Import List</h4>
                    </div>
                    <div class="modal-body"> 
                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
                        <?php
                        echo form_open_multipart('doctor_master/import_list', $attributes);
                        ?>

                        <div class="form-group">

                            <label>Upload</label>
                            <input type="file" name="id_browes" class="form-controll">
                            <div style='color:red;' id='admin_name_add_alert'></div>

                        </div>
                          <div class="form-group">
                                <label for="exampleInputFile">State</label>
                                <select class="form-control" name="state" id="state">
                                    <option value="">Select State</option>
                                    <?php foreach ($state as $cat) { ?>

                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($cat['id'] == $query[0]["state"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['state_name']); ?></option>
                                            <?php } ?>
                                </select>

                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">City</label>
                                <select class="form-control" name="city" id="city">
                                    <option value="">Select City</option>
                                    <?php foreach ($city as $cat) { ?>

                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($cat['id'] == $query[0]["city"]) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['name']); ?></option>
                                            <?php } ?>
                                </select>

                            </div>

                        <div class="modal-footer">
                            <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                            <!--                            <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button>-->
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Doctor List</h3>

                        <a style="float:right;" href='<?php echo base_url(); ?>doctor_master/doctor_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>
						<a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>doctor_master/export_csv_doctor/?name=<?= $name; ?>&email=<?= $email; ?>&mobile=<?= $mobile; ?>&city=<?= $city_id; ?>&sales_person=<?= $selected_person; ?>&search=Search' class="btn btn-primary btn-sm"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                        <?php /*
                          <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>

                          <?php echo form_open('admin_master/admin_list', $attributes); ?>

                          <div class="col-md-3">
                          <input type="text" class="form-control" name="user" placeholder="Username" value="<?php if(isset($username) != NULL){ echo $username; } ?>" />
                          </div>
                          <div class="col-md-3">
                          <input type="text" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email) != NULL){ echo $email; } ?>"/>
                          </div>
                          <input type="submit" value="Search" class="btn btn-primary btn-md">


                          </form>

                          <br>
                         */ ?>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>doctor_master/doctor_list" method="get" enctype="multipart/form-data">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th width="30%">Address</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Sales Person</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php echo $name; ?>"/></td>
                                            <td><input type="text" placeholder="Email" class="form-control" name="email" value="<?php echo $email; ?>"/></td>
                                            <td><input type="text" placeholder="Mobile" class="form-control" name="mobile" value="<?php echo $mobile; ?>"/></td>
                                            
                                            <td></td>
                                            <td></td>
											<td><div class="form-group">
                              
                                <select class="form-control" name="city" id="city">
                                    <option value="">Select City</option>
                                    <?php foreach ($city as $cat) { ?>

                                        <option value="<?php echo $cat['city_fk']; ?>" <?php
                                        if ($city_id == $cat['city_fk']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['name']); ?></option>
                                            <?php } ?>
                                </select>

                                    
                            </div>
</td>
<td><div class="form-group">
    <select class="form-control" name="sales_person">
                                    <option value="">Select Sales Persone</option>
                                    <?php foreach ($sales_person as $cat) { ?>

                                        <option value="<?php echo $cat['id']; ?>" <?php
                                        if ($selected_person == $cat['id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo ucwords($cat['first_name'].''. $cat['last_name']); ?></option>
                                            <?php } ?>
                                </select>

</div></td>
                                            <td>												
												<input type="submit" name="search" class="btn btn-success" value="Search" />												
												<a style="margin-top:10px;" class="btn btn-primary" href="<?php echo base_url(); ?>doctor_master/doctor_list">Reset</a>
											</td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>

                                            <tr>
                                                <td><?php echo $cnt+$page; ?></td>
                                                <td><?php echo ucwords($row['full_name']); ?></td>
                                                <td><?php echo ucwords($row['email']); ?></td>
                                                <td><?php echo ucwords($row['mobile']); ?></td>
                                                <td><?php echo ucwords($row['address']); ?></td>
                                                <td><?php echo ucwords($row['state']); ?></td>
<!--                                               <td><?php //echo ucwords($row['cityname']); ?></td>-->
                                                <td><?php echo ucwords($row['city']); ?></td>
                                                 <td><?php echo ucwords($row['first_name'] .''. $row['last_name']); ?></td>
                                                <td>

                                                    <a href='<?php echo base_url(); ?>doctor_master/doctor_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a> 
													
													<a href='<?php echo base_url(); ?>doctor_timslot/index/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Slot"><i class="fa fa-calendar-o"></i></a>

                                                    <a  href='<?php echo base_url(); ?>doctor_master/doctor_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
                                                    <?php if ($row['status'] == "1") { ?>
                                                        <a  href='<?php echo base_url(); ?>doctor_master/doctor_deactive/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Deactive" ><span class="label label-success">Active</span></a>   
                                                    <?php } else { ?>
                                                        <a  href='<?php echo base_url(); ?>doctor_master/doctor_active/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="active" ><span class="label label-danger">Deactive</span> </a>   
                                                    <?php } ?>  
													<a  href='<?php echo base_url(); ?>doctor_master/setdpermission/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="<?php if($row['app_permission'] == 1) { echo "Deactive Permissions"; }else{  echo "Active Permissions"; } ?>" onclick="return confirm('Are you sure?');"><i class="<?php if ($row['app_permission'] == 1) { echo "fa fa-toggle-on"; }else{ echo "fa fa-toggle-off"; } ?>"></i></a>
													
													 <br>
													<a href='<?php echo base_url()."camping/index/".$row['id']; ?>' data-toggle="tooltip" data-original-title="Talk camp"><span class="label label-primary">Talk camp</span></a> | <a href='<?php echo base_url()."camping/society/".$row['id']; ?>' data-toggle="tooltip" data-original-title="Society camp"><span class="label label-info">Society camp</span></a>
													
                                                </td>
                                            </tr>
                                            <?php $cnt++;
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
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10
        });
    });
</script>