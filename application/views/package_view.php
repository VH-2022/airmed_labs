<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Package List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>home"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">Package List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Package List</h3>
                        <a style="float:right;margin-right: 5px;" href='<?php echo base_url(); ?>package_master' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                        <a style="float:right;margin-right: 5px;" href='<?php echo base_url(); ?>package_master/export_csv?title=<?= $package_name; ?>' class="btn btn-primary btn-sm"><i class="fa fa-plus-download" ></i><strong > Export CSV</strong></a>
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
                        <br>
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>package_master/package_list" method="get" enctype="multipart/form-data">

                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Image</th>
                                            <th>Back Image</th>  
                                            <th>package Name</th>
                                            <th>Actual Price</th>
                                            <th>Discount Price</th>
                                            <th>Status</th>
                                            <th>Website </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                         
                                            <td>
                                                <input type="text" name="package_name" placeholder="Package Name" value="<?= $package_name; ?>" class="form-control"/>
                                            </td>
                                            <td>
                                                <select class="form-control chosen-select" tabindex="-1" name="city">
                                                    <option value="">Select Test City</option>
                                                    <?php foreach ($city_list as $br) { ?>
                                                        <option value="<?php echo $br['id']; ?>" <?php
                                                        if ($test_city == $br['id']) {
                                                            echo " selected";
                                                        }
                                                        ?> ><?php echo $br['name']; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </td>
                                            <td></td>
                                            <td>
                                                <select class="form-control chosen-select" tabindex="-1" name="status">
                                                    <option value="">Select Status</option>
                                                    <option value="1" <?php
                                                    if ($status == '1') {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>Active</option>
                                                    <option value="2" <?php
                                                    if ($status == '2') {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>Deactive</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control chosen-select" tabindex="-1" name="dep">
                                                    <option value="">Select Website </option>
                                                    <option value="1" <?php
                                                    if ($dep == '1') {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>Yes</option>
                                                    <option value="0" <?php
                                                    if ($dep == '0') {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>No</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="submit" name="search" class="btn btn-success" value="Search" />
                                                <a class="btn btn-primary" href="<?= base_url() . "package_master/package_list"; ?>">Reset</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            if ($row["price1"] != NULL) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $pages + $cnt; ?></td>
                                                    <td><img src="<?php echo base_url(); ?>upload/package/<?php echo $row['image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
                                                    </td>
                                                    <td><img src="<?php echo base_url(); ?>upload/package/<?php echo $row['back_image']; ?>" alt="Profile Pic" style="width:50px; height:40px;"/>
                                                    </td>
                                                    <td><?php echo $row['title']; ?></td>
                                                    <td><?php
                                                        foreach ($row["price1"] as $key) {
                                                            if ($key["a_price"] > 0) {
                                                                echo "Rs." . $key["a_price"] . " (" . $key["name"] . ")<br>";
                                                            }
                                                        }
                                                        ?></td>
                                                    <td><?php
                                                        foreach ($row["price1"] as $key) {
                                                            if ($key["d_price"] > 0) {
                                                                echo "Rs." . $key["d_price"] . " (" . $key["name"] . ")<br>";
                                                            }
                                                        }
                                                        ?></td>
                                                    <td>
                                                        <?php if ($row['is_active'] == '1') { ?>
                                                            <span class='label label-success'>Active</span>
                                                        <?php } if ($row['is_active'] == '2') { ?>
                                                            <span class='label label-danger'>Deactive</span>
                                                        <?php } ?>
                                                    </td> 
                                                    <td>
                                                        <?php if ($row['is_view'] == '1') { ?>
                                                            <span class='label label-success'>Yes</span>
                                                        <?php } else{ ?>
                                                            <span class='label label-danger'>No</span>
                                                        <?php } ?>
                                                    </td> 
                                                    <td>
                                                    <?php if($login_data["type"] != 2) {?>
                                                        <?php if ($row["is_active"] == '2') { ?>
                                                            <a href='<?php echo base_url(); ?>package_master/isdeactive/2/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Active" onclick="return confirm('Are You Sure ?')"><i class="fa fa-toggle-off"></i></a>
                                                        <?php } else { ?>
                                                            <a href='<?php echo base_url(); ?>package_master/isdeactive/1/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Deactive" onclick="return confirm('Are You Sure ?')"><i class="fa fa-toggle-on" style="font-size:12px"></i></a>
                                                        <?php } } ?>
                                                        <a href='<?php echo base_url(); ?>package_master/package_edit/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a href='<?php echo base_url(); ?>package_master/package_test_add/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Add Test"><i class="fa fa-plus"></i></a>
                                                        <?php if($login_data["type"] != 2) {?>
                                                        <a  href='<?php echo base_url(); ?>package_master/package_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>  
                                                        <?php } ?>      
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
