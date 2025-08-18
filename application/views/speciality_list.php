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


<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Speciality Wise Test
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Speciality Wise Test</a></li>
            <li class="active">Speciality Wise Test List</li>
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
                 <form role="form" action="<?php echo base_url(); ?>Speciality_master/index" method="get" enctype="multipart/form-data">
             <table id="example3" class="table table-bordered table-striped">
                  <thead>
                      <th>No</th>
                       <th>Name</th>
                      <th>Test</th>
                  
                      <th>Action</th>
                  </thead>
                  <tbody>
                     <tr>
                                            <td><span style="color:red;">*</span></td>
                                            
                                            <td><input type="text" placeholder="Name" class="form-control" name="name" value="<?php
                                                if (isset($intenet_id)) {
                                                    echo $intenet_id;
                                                }
                                                ?>"/></td>
                                                 <td></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                    <?php
                  
                $cnt =1;
                foreach ($query as $valTest) { 
                 
                    ?>
                    <tr>
                        <td>
                            <?php echo $cnt;?>
                        </td>
                        <td>
                            <?php echo ucwords($valTest['name']);?>
                        </td>
                         
                        <td><?php echo $valTest['TestID'];?></td>

                      
                        <td><a href="javascript:void(0);" onclick="edit('<?php echo $valTest['IDS'];?>')"><i class="fa fa-edit"></i></a>
                      <a href="<?php echo base_url();?>Speciality_master/delete/<?php echo $valTest['IDS'];?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                    </tr>
                    <?php  $cnt++;
                }
               ?>
                  </tbody>
              </form>
              </table>
            </div>
        </div>
    </section><!-- /.content -->
    <div class="clearfix"></div>
</div><!-- /.content-wrapper -->
<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add</h4>
            </div>
            <?php echo form_open("Speciality_master/add", array("method" => "POST", "role" => "form","id"=>'submit_id')); ?>
            <div class="modal-body">
        
                <div id="items_list">
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Name:</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label">Add Test:</label>
                        <select class="chosen chosen-select" id="consumer_id" onchange="select_item();">
                            <option value="">--Select--</option>
                            <?php
 
                            foreach ($test_list as $mkey) {
                                $test_list = $test_list . '<option value="' . $mkey["id"] . '">' . $mkey["test_name"] . '</option>';
                                ?>
                                <option value="<?= $mkey["id"] ?>"><?= $mkey["test_name"] ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="item_list" value="<?= htmlspecialchars($test_list, ENT_QUOTES); ?>"/>
                    </div>
                
                    <table class="table">
                        <thead>
                            <tr>
                                
                                <th>Test</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="selected_items">
                            <tr>
                                <td></td>
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
            <?php echo form_open("Speciality_master/update/", array("method" => "POST", "role" => "form")); ?>
            <div class="modal-body">
               
                <div id="items_list_edit">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<?php /* END */ ?>

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
    function select_item() {
     
        var skillsSelect = document.getElementById('consumer_id');
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text; 
        var prc = selectedText;
        var pm = skillsSelect.value;
    
        var explode = pm.split('-');
        $("#selected_items").append('<tr id="tr_' + $city_cnt + '"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td><a href="javascript:void(0);" onclick="delete_city_price(\'' + $city_cnt + '\',\'' + prc + '\',\'' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
        //$("#test option[value='1']").remove();
        var old_dv_txt = $("#hidden_test").html();
        $city_cnt = $city_cnt + 1;
       
         
          $("#consumer_id option[value='" + skillsSelect.value + "']").remove();
 
        $nc('.chosen').trigger("chosen:updated");
        //$("#selected_item").val('').trigger('chosen:updated');
    }

    function delete_city_price(id, name, value) {
        var tst = confirm('Are you sure?');
        if (tst == true) {
            /*Total price calculate start*/
            $('#selected_item').append('<option value="' + value + '">' + name + '</option>').trigger("chosen:updated");
            $("#tr_" + id).remove();
        }
    }

    /*EDIT start*/

    function edit(tid) {
        
       
        /*AJAX start*/
        $.ajax({
            url: '<?php echo base_url(); ?>Speciality_master/edit/' + tid,
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
    }

    $city_cnt_edit = 0;
    function machine_select_edit() {
        var selected_ids = $("#machine_id_edit").val();
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
    function select_item_edit() {

        var skillsSelect = document.getElementById('selected_item_edit');
        var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
        var prc = selectedText;
        var pm = skillsSelect.value;
        var explode = pm.split('-');
        $("#selected_items_edit").append('<tr id="tr_edit_' + $city_cnt + '"><td>' + prc + '<input type="hidden" name="item[]" value="' + pm + '"></td><td><a href="javascript:void(0);" onclick="delete_city_price_edit(\'' + $city_cnt + '\',' + pm + '\')"><i class="fa fa-trash"></i></a></td></tr>');
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