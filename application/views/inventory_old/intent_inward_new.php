<style>
    .round {
        display: inline-block;
        height: 30px;
        width: 30px;
        line-height: 30px;
        -moz-border-radius: 15px;
        border-radius: 15px;
        background-color: #222;    
        color: #FFF;
        text-align: center;  
    }
    .round.round-sm {
        height: 10px;
        width: 10px;
        line-height: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        font-size: 0.7em;
    }
    .round.blue {
        background-color: #3EA6CE;
    }


</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Inward 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Inward List</a></li>
            <li class="active">Inward List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <div class="widget">
            <?php if (isset($success) != NULL) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $success['0']; ?>
                </div>
            <?php } ?>

        </div>
       <div class="row">
            <span style="float: right;"><a href="javascript:void(0);" onclick="$('#myModal').modal('show');" class="btn btn-primary btn-sm">Add</a></span>    
      </div>

 
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="<?php echo base_url(); ?>inventory/Intent_invert/invert_list" method="get" enctype="multipart/form-data">
                    <table id="" class="table table-bordered table-striped">
                        <thead>
                        <th>No</th>
                        <th>Intent Code</th>
                        <th>Branch Name</th>
                        <th>Machine Name</th>
                        
                        <th>Category Name</th>
                       
                        </thead>
                         <tbody>
                          
                            <?php
                            $cnt = 1;
                            foreach ($final_array as $valTest) { 
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $cnt; ?>
                                    </td>
                                    <td>
                                        <?php echo "<small><b>Intent Code-</b>".$valTest['intent_id'] . '<br><small><b>Added By-</b>' . $valTest["admin_name"] . '</small>'; ?>
                                    </td>
                                    <td><?php echo $valTest['branch_name'];?></td>
                                    <td><?php if($valTest['machine_name'] !='') { 
                                        $new_machine_add = ucfirst($valTest['machine_name']);
                                    }else{
                                        $new_machine_add = 'N/A';
                                    }
                                    echo $new_machine_add;?></td>
                                    
                                    <td>
                                        <?php foreach ($valTest['details'] as $key) {
                                            ?>
                                            <?php if ($key["cat"] != $old_Cat) { ?>
                                                <b><?= ucfirst($key["cat"] . '<br>') ?></b>
                                            <?php } $old_Cat = $key["cat"]; ?>
                                                <?php echo $key["reagent_name"]; ?><?php echo (!empty($key["unit"]))?"(".$key["unit"].")":""; ?> &nbsp;: <?php echo $key["quantity"] . '<br>'; ?>
                                        <?php } ?>
                                    </td>


                                 
                                </tr>
                                <?php
                                $cnt++;
                            }if (empty($query)) {
                                    ?>
                                    <tr><td colspan="4"><center>Data not available.</center></td></tr>
                                <?php } ?>
                          
                        </tbody>
                </form>
                </table>
            </div>
        </div>
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<!-- Modal -->

<?php ?>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add</h4>
            </div>
            <?php echo form_open("inventory/Intent_invert/invert_add", array("method" => "POST", "role" => "form", "id" => 'submit_id')); ?>
            <div class="modal-body">
            
           <input type="hidden" name="intent_id" value="<?php echo $generate[0]['Intent'];?>">
                <div id="items_list">
                     <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Branch:</label>
                        <select class="chosen chosen-select branch_id" name="branch_fk" onchange="getMachine();">
                            <option value="">--Select--</option>
                            <?php
                           $item_list_array = array();
                            foreach ($branch_list as $val) {
                                   
                                    $item_list_array = $item_list_array . '<option value="' . $val["BranchId"] . '">' . $val["BranchName"] . '</option>';
                                    
                                    ?>

                                    <option value="<?= $val["BranchId"] ?>"><?= $val["BranchName"] ?></option>
                            <?php }
                            ?>
                        </select>
                      
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Machine:</label>
                        <select class="chosen chosen-select machine_sub" name="machine_fk"  onchange="subChange();">
                            <option value="">--Select--</option>
                            <?php /*
                            $item_list_array = array();
                            foreach ($machine_array[0] as $val) {                           
                                    $item_list_array = $item_list_array . '<option value="' . $val["MachinId"] . '">' . $val["MachinName"] . '</option>';
                                    
                                    ?>

                                    <option value="<?= $val["MachinId"] ?>"><?= $val["MachinName"] ?></option>
                            <?php } */
                            ?>
                        </select>
                        
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Reagent:</label>
                        <select class="chosen chosen-select" name="category_fk" id="selected_item" onchange="select_item('Reagent', this);">
                            <option value="">--Select--</option>
                            <?php /*
                            $item_list_array = array();
                            foreach ($machine_array as $val) {
                                   
                                    $item_list_array = $item_list_array . '<option value="' . $val["MachinId"] . '">' . $val["MachinName"] . '</option>';
                                    ?>

                                    <option value="<?= $val["MachinId"] ?>"><?= $val["MachinName"] ?></option>
                            <?php } */
                            ?>
                        </select>
                        <input type="hidden" id="item_list" value="<?= htmlspecialchars($item_list_array, ENT_QUOTES); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Consum:</label>
                        <select class="chosen chosen-select" name="category_fk" id="reagent_sub_id" onchange="select_item('Consumables', this);">
                            <option value="">--Select--</option>
                            <?php
                            foreach ($lab_consum as $mkey) {
                                $lab_consum = $lab_consum . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
<?php } ?>
                        </select>
                        <input type="hidden" id="item_list" value="<?= htmlspecialchars($lab_consum, ENT_QUOTES); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Stationary:</label>
                        <select class="chosen chosen-select" name="category_fk" id="yahoo_id" onchange="select_item('Stationary', this);">
                            <option value="">--Select--</option>
                            <?php
                            foreach ($stationary_list as $mkey) {
                                $stationary_list = $stationary_list . '<option value="' . $mkey["id"] . '">' . $mkey["reagent_name"] . '</option>';
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["reagent_name"] ?></option>
<?php } ?>
                        </select>
                        <input type="hidden" id="item_list" value="<?= htmlspecialchars($stationary_list, ENT_QUOTES); ?>"/>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Batch No</th>
                                <th>Expire Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr><th>Intent Code</th><td><input type="text" name="intent_code"></td></tr>
                        </tfoot>
                        <tbody id="selected_items">
                            <tr>
                                <td><span id="error_test"></span></td>
                            </tr>
                          
                        </tbody>
                    </table>
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


<?php /* Nishit Edit changes are start */ ?>
<div id="myModalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update</h4>
            </div>
<?php echo form_open("inventory/Intent_invert/intent_update/", array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">

                <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Branch:</label>
                        <select class="chosen chosen-select branch_id" id="branch_id_edit" name="branch_fk"  onchange="machine_select_edit();">
                            <option value="">--Select--</option>
                            <?php
                            $item_list_array = array();
                            foreach ($branch_list as $val) {
                                   
                                $item_list_array = $item_list_array . '<option value="' . $val["BranchId"] . '">' . $val["BranchName"] . '</option>';
                                    
                                    ?>

                                    <option value="<?= $val["BranchId"] ?>"><?= $val["BranchName"] ?></option>
                            <?php } 
                            ?>
                        </select>
                      
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Machine:</label>
                        <select class="chosen chosen-select machine_sub_id" name="machine_fk" id="sub_id_edit"  onchange="subChange_sub();">
                            <option value="">--Select--</option>
                            <?php
                            $item_list_array = array();
                            foreach ($machine_array as $val) {    //echo "<pre>";print_r($machine_array);                         
                                    $item_list_array = $item_list_array . '<option value="' . $val["machine_fk"] . '">' . $val["MachineName"] . '</option>';
                                    
                                    ?>

                                    <option value="<?= $val["machine_fk"] ?>"><?= $val["MachineName"] ?></option>
                            <?php } 
                            ?>
                        </select>
                        
                    </div>
                <div id="items_list_edit">
                    
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
<?php echo form_close(); ?>
        </div>

    </div>
</div>
</div>
<?php /* END */ ?>


<span id="input_id" style="display:none;">

 <input type="text" name="expire_date[]" class="form-control datepicker" id="dob1" placeholder="Date of Birth(dd/mm/yyyy)" onkeyup="this.value = this.value.replace(/^([\d]{2})([\d]{2})([\d]{4})$/,'$1-$2-$3'); " maxlength="10" tabindex="3">
</span>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
                            $nc = $.noConflict();
                            $nc(function () {

                                $nc('.chosen-select').chosen();

                            });

</script> 
<script>
    jQuery(document).ready(function () {
        jQuery("#machine_id_edit").chosen();
    });
</script>

<script>
    
    $city_cnt = 0;
    function machine_select() {
        var selected_ids = $("#machine_id").val();
        if (selected_ids != '') {
            $("#items_list").attr("style", "");
        } else {
            $("#items_list").attr("style", "display:none;");
            //$(".chosen").val('').trigger("liszt:updated");
        }
        $("#selected_items").html("");
        $("#selected_item").html("");
        var option_data = $("#item_list").val();
        $("#selected_item").html(option_data);
        $nc('.chosen').trigger("chosen:updated");

    }
    function select_item(val, id) {
    var input_id =$('#input_id').html();
    console.log(input_id)
        var skillsSelect = id;
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $("#selected_items").append('<tr id="tr_' + $city_cnt + '" class="validation"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td>' + val + '</td><td><input type="text" name="quantity[]" required="" class="form-control"/></td><td><input type="text" name="batch_no[]" required="" class="form-control"/></td><td>'+input_id+ '</td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test").html();
        $city_cnt = $city_cnt + 1;
        // $("#selected_item option[value='" + skillsSelect.value + "']").remove();
        //  $("#consumer_id option[value='" + skillsSelect.value + "']").remove();

        // $("#statinary_id option[value='" + skillsSelect.value + "']").remove();
      
    if (val == "Reagent") {
            $("#selected_item option[value='" + skillsSelect.value + "']").remove();
        }
        if (val == "Consumables") {
            $("#reagent_sub_id option[value='" + skillsSelect.value + "']").remove();
        }

        if (val == "Stationary") {
            $("#yahoo_id option[value='" + skillsSelect.value + "']").remove();
        }
        $nc('.chosen').trigger("chosen:updated");
        //$nc('.chosen').trigger("chosen:updated");
    }

    function delete_city_price(id, name, value,val) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            /*Total price calculate start*/
            $('#selected_item').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
            $("#tr_" + id).remove();
        }
    }

    /*EDIT start*/

    function edit(tid, cid,ind_id) {

        $("#branch_id_edit").val(tid);
        
        $("#branch_id_edit").attr("disabled", "disabled");
        /*AJAX start*/
        $.ajax({
            url: '<?php echo base_url(); ?>inventory/Intent_invert/edit/' + tid + '/' + cid +'/' +ind_id,
            type: 'GET',
            success: function (data) {

                console.log(data);
                $("#items_list_edit").html(data);
                $nc('.chosen').trigger("chosen:updated");
            },
            complete: function () {
                jQuery("#machine_id_edit").trigger("chosen:updated");
            },
        });
        /*AJAX end*/
        $nc('.chosen').trigger("chosen:updated");
        $("#myModalEdit").modal("show");
        subBranch_sub(tid,cid);
    }

    $city_cnt_edit = 0;

    function machine_select_edit() {
      
        if (selected_ids != '') {
            $("#items_list_edit").attr("style", "");
        } else {
            $nc("#items_list_edit").attr("style", "display:none;");
            //$(".chosen").val('').trigger("liszt:updated");
        }
        $("#selected_items_edit").html("");
        $("#selected_item_edit").html("");
        var option_data = $("#item_list").val();
        $("#selected_item_edit").html(option_data);
        $nc('.chosen').trigger("chosen:updated");

    }
    function select_item_edit(val, id) {
var input_id =$('#input_id').html();
        var skillsSelect = id;
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $("#selected_items_edit").append('<tr id="tr_edit_' + $city_cnt + '"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td>' + val + '</td><td><input type="text" name="quantity[]" required="" class="form-control"/></td><td><input type="text" name="batch_no[]" required="" class="form-control"/></td><td>'+input_id+'</td><td><a href="javascript:void(0);" onclick="delete_city_price_edit(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test_edit").html();
        $city_cnt = $city_cnt + 1;
        $("#selected_item_edit option[value='" + skillsSelect.value + "']").remove();
        $('')
        $nc('.chosen').trigger("chosen:updated");
        //$("#selected_item").val('').trigger('chosen:updated');
    }

    function delete_city_price_edit(id, name, value) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            /*Total price calculate start*/
            $('#selected_item_edit').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
            $("#tr_edit_" + id).remove();
        }
    }
    /*END*/
    $nc('.chosen').trigger("chosen:updated");

    function OnBlurInput(input) {
        this.value == "";
    }
      function getMachine(){
        
       var id = $('.branch_id').val();
         $.ajax({
            url:'<?php echo base_url();?>inventory/Intent_invert/getMachine',
            type:"POST",
            data:{id:id},
            success:function(data){ 
                $('.machine_sub').html(data);
              
              
                 $nc('.chosen').trigger("chosen:updated");
            }
        })
    }
    function subChange(){
       
       var id = $('.machine_sub').val();
         $.ajax({
            url:'<?php echo base_url();?>inventory/Intent_invert/getReagent',
            type:"POST",
            data:{id:id},
            success:function(data){
               $('#selected_item').html(data);
                 $nc('.chosen').trigger("chosen:updated");
            }
        })
    }

    function subChange_sub(){
   
    var id = $('.machine_sub_id').val();
    
         $.ajax({
            url:'<?php echo base_url();?>inventory/Intent_invert/getReagent',
            type:"POST",
            data:{id:id},
            success:function(data){ 
               $('#selected_item_edit').html(data);
                 $nc('.chosen').trigger("chosen:updated");
            }
        })
    }

    function subBranch_sub(val,cid){
        $.ajax({
            url:'<?php echo base_url();?>inventory/Intent_invert/getMachine',
            type:"POST",
            data:{id:val},
            success:function(data){  

                $('.machine_sub_id').html(data);
               $('.machine_sub_id').val(cid);
              
                 $nc('.chosen').trigger("chosen:updated");
            }
        })
      
    }
    
</script>
<script type="text/javascript">
    $(function () {
        $('#example3').dataTable({
            //"bPaginate": false, 
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
            $(".sub_new_id").keypress(function(){ alert("ddd");
                if ($(this).val().length == 2){
                    $(this).val($(this).val() + "/");
                }else if ($(this).val().length == 5){
                    $(this).val($(this).val() + "/");
                }
            });
});

</script>
<script type="text/javascript">
    function SubmitData(){
        var frm_data = $('#submit_id');
        var path = frm_data.attr('action');

        // $('#error_test').html("");
        // var selected = $(this).closest('.validation').find('.Level3').val();
        // alert(selected);
        // var cnt =0;
        // if(selected.trim() == "" ){
        //     $('#error_test').html("Required");
        //     cnt = cnt +1;
        // }
        // if(cnt >0){
        //     return false;
        // }

        $.ajax({
            type:"POST",
            url:path,
            data:frm_data.serialize(),
            success:function(){
                location.reload();
            }
        })
    }
</script>