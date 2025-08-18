<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
    Change Permissions
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Admin_manage/user_list"><i class="fa fa-users"></i>Amdin User List</a></li>
        <li class="active">Change Permissions</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

        <div class="box box-primary" >
                    <div class="box-header" >

                        <!-- form start -->

                        <h3 class="panel-title"><?= $query[0]['name'];?> - Change Permissions</h3>

                    </div>

                    
                    <form role="form" id="leave_form" action="<?php echo base_url(); ?>Admin_manage/updatePermission" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                         
                        <input type="hidden" name="user_id" value="<?php echo $cid  ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div style="float: left;marigin-right:10px" >
                                        <label style="float: right;" for="employee" class="">&nbsp;All</label>
                                        <input style="float:right" type="checkbox" name="all_checkbox" id="all_checkbox">
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $subpermissionArray = [];
                            $modulepermissionArray = [];
                            foreach ($permissions as $module_name => $permissionArray) {
                                    $modulepermissionArray = $module_name;
                                 ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        
                                        <input type="checkbox" value="<?= $permissionArray['id'] ?>" name="module_Checkbox" class="module_Checkbox module<?php echo str_replace(' ', '_', $module_name); ?>" id="module<?php echo str_replace(' ', '_', $module_name); ?>" data-id="<?php echo str_replace(' ', '_', $module_name); ?>" data-sub-id="<?php echo str_replace(' ', '_', $module_name); ?>">&nbsp;<b>
                                            <?= ucwords(str_replace("_", " ", $module_name)); ?>
                                        </b><br>
                                        <?php if($module_name == "Booking Master"){ ?> 
                                            <br>
                                        <div style="display: flex;">
                                            <label>All Booking List</label>
                                            <select style="width: 117px;margin-left: 12px;margin-top: -6px;" name="day_permission" class="form-control" id="day_permission">
                                                <option value="">Select Days</option>
                                                <option value="0" <?php echo ($day_permission[0]['day_permission'] == 0) ? 'selected' : ''; ?>>All</option>
                                                <option value="7" <?php echo ($day_permission[0]['day_permission'] == 7) ? 'selected' : ''; ?>>1 Week</option>
                                                <option value="15" <?php echo ($day_permission[0]['day_permission'] == 15) ? 'selected' : ''; ?>>1/2 Month</option>
                                                <option value="30" <?php echo ($day_permission[0]['day_permission'] == 30) ? 'selected' : ''; ?>>1 Month</option>
                                                <option value="90" <?php echo ($day_permission[0]['day_permission'] == 90) ? 'selected' : ''; ?>>1 Quarter</option>
                                                <option value="180" <?php echo ($day_permission[0]['day_permission'] == 180) ? 'selected' : ''; ?>>1/2 Year</option>
                                                <option value="365" <?php echo ($day_permission[0]['day_permission'] == 365) ? 'selected' : ''; ?>>1 Year</option>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <?php if($module_name == "Report"){ ?> 
                                            <br>
                                        <div style=" display: flex;">
                                            <label for="report_day_permission">View Report</label>
                                            <select style="width: 117px;margin-left: 12px;margin-top: -6px;" name="report_day_permission" class="form-control" id="report_day_permission">
                                                <option value="">Select Days</option>
                                                <option value="0" <?php echo ($day_permission[0]['report_day_permission'] == 0) ? 'selected' : ''; ?>>All</option>
                                                <option value="7" <?php echo ($day_permission[0]['report_day_permission'] == 7) ? 'selected' : ''; ?>>1 Week</option>
                                                <option value="15" <?php echo ($day_permission[0]['report_day_permission'] == 15) ? 'selected' : ''; ?>>1/2 Month</option>
                                                <option value="30" <?php echo ($day_permission[0]['report_day_permission'] == 30) ? 'selected' : ''; ?>>1 Month</option>
                                                <option value="90" <?php echo ($day_permission[0]['report_day_permission'] == 90) ? 'selected' : ''; ?>>1 Quarter</option>
                                                <option value="180" <?php echo ($day_permission[0]['report_day_permission'] == 180) ? 'selected' : ''; ?>>1/2 Year</option>
                                                <option value="365" <?php echo ($day_permission[0]['report_day_permission'] == 365) ? 'selected' : ''; ?>>1 Year</option>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>
                                    
                                    <div class="col-md-8">
                                            <?php
                                            foreach ($permissionArray as $subkey => $permission) { 
                                                $subpermissionArray[] =  $subkey;
                                                ?>
                                                <p style="margin-top: 15px;">
                                                    <b>
                                                    <input type="checkbox" value="<?= $permissionArray['id'] ?>" name="sub_module_Checkbox" id="<?php echo  $subkey ?>" class="sub_module_Checkbox sub_module<?php echo str_replace(' ', '_', $subkey); ?> <?php echo str_replace(' ', '_', $module_name); ?>" id="sub_module<?php echo str_replace(' ', '_', $subkey); ?>" data-id="<?php echo str_replace(' ', '_', $module_name); ?>"
                                                    data-sub-id="<?php echo str_replace(' ', '_', $subkey); ?>">
                                                        <?php
                                                            echo  ucwords(str_replace("_", " ", $subkey)) ; 
                                                        ?>
                                                    </b>
                                                </p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <?php
                                                            foreach ($permission as $key => $suPermision) { 
                                                            ?>
                                                                <div class="col-md-4">
                                                                    <div>
                                                                        <input type="checkbox" value="<?= $suPermision['id'] ?>" <?= $suPermision['checked'] ?> name="permission[]" data-module-id="module<?php echo str_replace(' ', '_', $module_name); ?>"
                                                                        data-sub-module-id="sub_module<?php echo str_replace(' ', '_', $subkey); ?>"
                                                                        data-sub-module-id-new="<?php echo str_replace(' ', '_', $subkey); ?>" class="module_<?= $permissionArray['id'] ?>_permission <?= str_replace(' ', '_', $module_name) ?> <?php echo str_replace(' ', '_', $subkey); ?>">
                                                                        <?php
                                                                        echo  ucwords(str_replace("_", " ", $suPermision['permission'])); ?>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php } ?>        
                                        
                                    </div>
                                </div>
                                <?php } ?>
                                </div>
                                <div class="panel-footer">
                                    <input name="submit" class="btn btn-primary" type="submit" value="Update">
                                </div>
                    </form>
                </div><!-- /.box -->

            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    <?php foreach($subpermissionArray as $subName){ ?>

        var sub_name = '<?= $subName  ?>';
        console.log(sub_name);
        var total_check = $(`.${sub_name}:checked`).length;
        var total_check_box = $(`.${sub_name}`).length;
        var mainModule=  $(`#${sub_name}`).attr('data-id');
       
        if (total_check == total_check_box) {
            $(`#${sub_name}`).prop('checked', true);
        }

        var maincheckBoxCount = $(`.${mainModule}`).length;
        var maincheckedCount = $(`.${mainModule}:checked`).length;
        if(maincheckBoxCount == maincheckedCount){
            $(`#module${mainModule}`).prop('checked', true);
        }

        

    <?php } ?>
    
    $(function() {
        // $('.chosen-select').chosen();
        var checkBoxCount = $('input[name="permission[]"]').length;
        var checkedCount = $('input[name="permission[]"]:checked').length;

        if (checkBoxCount == checkedCount) {
            $('#all_checkbox').prop('checked', true);
            $('input[name="module_Checkbox"]').prop('checked', true);
            $('input[name="sub_module_Checkbox"]').prop('checked', true);
        }

        var admin_user_manage = $('.admin_user_manage').length;
        var admin_user_checked_count = $('.admin_user_manage:checked').length;
        if (admin_user_manage == admin_user_checked_count) {
            $('.sub_moduleadmin_user_manage').prop('checked', true);
        }

        $('.module_Checkbox').on('click', function() {
            var roleId = $(this).attr('data-id');
            var subId = $(this).attr('data-sub-id');
            
            var modules = $('.module' + roleId).is(":checked");

            if (modules == true) {
                $('.' + roleId).prop('checked', true);
                $('.' + subId).prop('checked', true);
                var checkBoxCount = $('input[name="permission[]"]').length;
                var checkedCount = $('input[name="permission[]"]:checked').length;

                if (checkBoxCount == checkedCount) {
                    $('#all_checkbox').prop('checked', true);
                }

            } else {
                $('#all_checkbox').prop('checked', false);
                $('.' + roleId).prop('checked', false);
                $('.' + subId).prop('checked', false);
                // $('.' + subID+'_1').prop('checked', isChecked);
            }
        });

        

        $('.sub_module_Checkbox').on('click', function() {
            var roleId = $(this).attr('data-id');
            var subID = $(this).attr('data-sub-id');
            
            var isChecked = $(this).is(":checked");
            $('.' + subID).prop('checked', isChecked);

            var a = $(`.${roleId}`).length;
            var b = $(`.${roleId}:checked`).length;

            if (a == b) {
                $(`#module${roleId}`).prop('checked', true);
            } else {
                $(`#module${roleId}`).prop('checked', false);
            }

            var checkBoxCount = $('input[name="permission[]"]').length;
            var checkedCount = $('input[name="permission[]"]:checked').length;
            if (checkBoxCount == checkedCount) {
                $('#all_checkbox').prop('checked', true);
            } else {
                $('#all_checkbox').prop('checked', false);
            }
            
        });

        //end code

        $("#all_checkbox").on('click', function() {
            var isChecked = $(this).is(":checked");
            $('input[name="module_Checkbox"]').prop('checked', isChecked);
            $('input[name="sub_module_Checkbox"]').prop('checked', isChecked);
            $('input[name="permission[]"]').prop('checked', isChecked);
        });

        $('input[name="permission[]"]').on('click', function() {
            
            var moduleParmissionChecked = $(this).attr('data-module-id');
            var submoduleParmissionChecked = $(this).attr('data-sub-module-id-new');
            

            var x = $(`.${submoduleParmissionChecked}`).length;
            var y = $(`.${submoduleParmissionChecked}:checked`).length;

            if (x == y) {
                $(`#${submoduleParmissionChecked}`).prop('checked', true);
            } else {
                $(`#${submoduleParmissionChecked}`).prop('checked', false);
            }
            

            var a = $(`.${moduleParmissionChecked}`).length;
            var b = $(`.${moduleParmissionChecked}:checked`).length;

            if (a == b) {
                $(`#${moduleParmissionChecked}`).prop('checked', true);
            } else {
                $(`#${moduleParmissionChecked}`).prop('checked', false);
            }
            var checkBoxCount = $('input[name="permission[]"]').length;
            var checkedCount = $('input[name="permission[]"]:checked').length;
            if (checkBoxCount == checkedCount) {
                $('.' + moduleParmissionChecked).prop('checked', true);
                $('#all_checkbox').prop('checked', true);
            } else {
                $('.' + moduleParmissionChecked).prop('checked', false);
                $('#all_checkbox').prop('checked', false);
            }
        });
    });



    function employee_change() {
        var employee_id = $("#employee_id").val();
        var arr = employee_id.split("-");
        if (arr[1] == 1) {
            $("#divnight").show();
        } else {
            $("#divnight").hide();
        }
    }
</script>
