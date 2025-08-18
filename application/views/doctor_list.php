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


                        <?php if (array_search('doctor_add', $login_data["permissions"])) { ?>
                        <a style="float:right;" href='<?php echo base_url(); ?>doctor_master/doctor_add' class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a> <?php } ?>
                        <?php if (array_search('doctor_import', $login_data["permissions"])) { ?>
                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import"  class="btn btn-primary btn-sm" ><strong > Import</strong></a>
                        <?php } ?>
						<a style="float:right;margin-right:5px;" href='<?php echo base_url(); ?>doctor_master/export_csv_doctor/?name=<?= $name; ?>&email=<?= $email; ?>&mobile=<?= $mobile; ?>&city=<?= $city_id; ?>&sales_person=<?= $selected_person; ?>&status=<?=$status?>&search=Search' class="btn btn-primary btn-sm"><i class="fa fa-download"></i><strong> Export To CSV</strong></a>

                        <a style="float:right;margin-right:5px;" data-toggle="modal"  data-target="#import_new"  class="btn btn-primary btn-sm" ><strong > Update PRO</strong></a>

                        <a style="float:right;margin-right:5px;" data-toggle="modal" data-target="#import_speciality" class="btn btn-primary btn-sm" ><strong > Update Speciality</strong></a>
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
                                            <th>Notify Report</th>
											<th>Status</th>

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
                <td><div class="form-group">

                <select class="form-control" name="notify">
                    <option  value="">All</option>
                <option value="Yes" <?php

                                    if ($notifyReport == "Yes") {

                                        echo "selected";

                                    }

                                    ?>>Yes</option>
                                     <option value="No" <?php

                            if ($notifyReport == "No") {

                                echo "selected";

                            }

                            ?>>No</option>
                </select>
                </div>
                </td>
<td><div class="form-group">

<select class="form-control" name="status">
    <option  value="">All</option>
<option value="1" <?php

                    if ($status == 1) {

                        echo "selected";

                    }

                    ?>>Active</option>
                     <option value="2" <?php

            if ($status == 2) {

                echo "selected";

            }

            ?>>Deactive</option>
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

                                                 <td><?php if($row['notify']==1){
                                                    echo "Yes";

                                                 }else{
                                                    echo "No";
                                                 } ?></td>

												 <td><input type="text" style="width:100px;" id="doc_alias_<?php echo $row['id']; ?>" onchange="aliaskeyup('<?php echo $row['id']; ?>')" value="<?php echo $row['doctor_code']; ?>" placeholder="Code" >

                                                    <!-- <input type="text" style="width:100px;" id="doc_speciality_<?php echo $row['id']; ?>" onkeyup="aliaskeyup('<?php echo $row['id']; ?>')" value="<?php echo $row['doctor_speciality']; ?>" placeholder='Speciality' > !-->

                                                    <select id="sel_doc_speciality_<?php echo $row['id']; ?>" style="width:100px;"  onchange="aliaskeyup('<?php echo $row['id']; ?>')">

                                                        <option value="-1">Speciality</option>

                                                        <?php foreach ($specialitylist as $speciality) { ?>

                                                            <option value="<?php echo $speciality["id"] ?>" <?php echo $row['doctor_speciality'] == $speciality["doctor_speciality_title"] ? 'selected' : ''?>> <?php echo $speciality["doctor_speciality_title"] ?></option>

                                                        <?php } ?>                                                        

                                                    </select>

                                                    <!-- <input type="text" style="width:100px;" id="doc_pro_<?php echo $row['id']; ?>" onkeyup="aliaskeyup('<?php echo $row['id']; ?>')" value="<?php echo $row['doctor_pro']; ?>" placeholder='PRO' > !-->

                                                    <select id="sel_doc_pro_<?php echo $row['id']; ?>" style="width:100px;" onchange="aliaskeyup('<?php echo $row['id']; ?>')">

                                                        <option value="-1">PRO</option>

                                                        <?php foreach ($prolist as $pro) { ?>

                                                            <option value="<?php echo $pro["id"] ?>" <?php echo ($row['doctor_pro'] == $pro["pro_name"] || $row['doctor_pro_id'] == $pro["id"] ) ? 'selected' : ''?>> <?php echo $pro["pro_name"] ?></option>

                                                        <?php } ?>

                                                    </select>

                                                    <input type="text" style="width:100px;" id="doc_ratio_<?php echo $row['id']; ?>" onkeyup="aliaskeyup('<?php echo $row['id']; ?>')" value="<?php echo $row['doctor_ratio']; ?>" placeholder='RA'  onchange="aliaskeyup('<?php echo $row['id']; ?>')" >

                                                    <input type="text" style="width:100px;" id="doc_area_<?php echo $row['id']; ?>" onkeyup="aliaskeyup('<?php echo $row['id']; ?>')" value="<?php echo $row['doctor_area']; ?>" placeholder='AREA'  onchange="aliaskeyup('<?php echo $row['id']; ?>')" >                                                    

                                                    <a style="color:blue;display:none;" id="alias_save_<?php echo $row['id']; ?>" onclick="savealias('<?php echo $row['id']; ?>')">Save</a></td>

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
        <!---new_import_model-->
        <div class="modal fade" id="import_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="H4">Update PRO</h4>
                    </div>
                    <div class="modal-body"> 
                        <?php $attributes = array('class' => 'form-horizontal1', 'method' => 'POST'); ?>
                        <?php
                        echo form_open_multipart('doctor_master/update_pro_name', $attributes);
                        ?>

                        <div class="form-group">

                            <label>Upload</label>
                            <input type="file" name="id_browes" class="form-controll">
                            <div style='color:red;' id='admin_name_add_alert'></div>

                        </div>

                        <div class="modal-footer">
                            <a href='<?php echo base_url(); ?>doctor_master/test_csv' class="btn btn-primary" > Sample CSV</a>
                            <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
      
                                <!-- <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button> -->
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!------end new_import_model------->
          <!---import_speciality_model-->
          <div class="modal fade" id="import_speciality" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="H4">Update Speciality</h4>
                    </div>
                    <div class="modal-body"> 
                        <?php $attributes = array('class' => 'form-horizontal1', 'method' => 'POST'); ?>
                        <?php
                        // echo form_open_multipart('doctor_master/import_speciality', $attributes);
                        ?>
            <form  action="<?php echo base_url(); ?>doctor_master/import_speciality" method="POST" class="form-horizontal1" enctype="multipart/form-data">
                        <div class="form-group">

                            <label>Upload</label>
                            <input type="file" name="import_speciality_file" class="form-controll">
                            <div style='color:red;' id='admin_name_add_alert'></div>

                        </div>

                        <div class="modal-footer">
                            <a href='<?php echo base_url(); ?>doctor_master/test_csv_speciality' class="btn btn-primary" > Sample CSV</a>
                            <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
                            
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!------end new_import_model------->

      

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

	

	function savealias(docid)

    {

        if(document.getElementById('doc_alias_' + docid).value != '' || 

        document.getElementById('sel_doc_speciality_' + docid).selectedIndex > 0 || 

        document.getElementById('sel_doc_pro_' + docid).selectedIndex > 0 ||         

        document.getElementById('doc_ratio_' + docid).value != '' || 

        document.getElementById('doc_area_' + docid).value != '' )

        {

            //doctor_alias

            setTimeout(function () {

                code = document.getElementById('doc_alias_' + docid).value;

                //speciality = document.getElementById('doc_speciality_' + docid).value;

                speciality = '';

                if(document.getElementById('sel_doc_speciality_' + docid).selectedIndex != 0)

                    speciality = document.getElementById('sel_doc_speciality_' + docid).options[document.getElementById('sel_doc_speciality_' + docid).selectedIndex].text;

                //pro = document.getElementById('doc_pro_' + docid).value;

                pro = '';
                pro_id="";

                if(document.getElementById('sel_doc_pro_' + docid).selectedIndex != 0)

                    pro = document.getElementById('sel_doc_pro_' + docid).options[document.getElementById('sel_doc_pro_' + docid).selectedIndex].text;
                    pro_id = document.getElementById('sel_doc_pro_' + docid).options[document.getElementById('sel_doc_pro_' + docid).selectedIndex].value;

                //subpro = '';

                //if(document.getElementById('sel_doc_sub_pro_' + docid).selectedIndex != 0)

                //    subpro = document.getElementById('sel_doc_sub_pro_' + docid).options[document.getElementById('sel_doc_sub_pro_' + docid).selectedIndex].text;

                

                ratio = document.getElementById('doc_ratio_' + docid).value;

                area = document.getElementById('doc_area_' + docid).value;

                $.ajax({

                url: '<?php echo base_url(); ?>doctor_master/doctor_alias/' + docid,

                        type: 'post',

                        data: {code: code, speciality: speciality, pro : pro,pro_id : pro_id, ratio : ratio, area : area },

                        success: function (data) {

                            alert(data);

                            document.getElementById('alias_save_' + docid).style.display = "none";

                            //var json_data = JSON.parse(data);

                            //$("#referral_by").html(json_data.refer);

                            //$('.chosen').trigger("chosen:updated");

                        },

                        error: function (jqXhr) {

                            $("#referral_by").html("");

                        },

                        complete: function () {

                        },

                });

                }, 1000);

        }

        

    }



    function aliaskeyup(docid)

    {

        //if(document.getElementById('doc_alias_' + docid).value == '' && document.getElementById('doc_speciality_' + docid).value == '' && document.getElementById('doc_pro_' + docid).value == '')

        //    document.getElementById('alias_save_' + docid).style.display = "none";

        //else

            document.getElementById('alias_save_' + docid).style.display = "block";

    } 

</script>