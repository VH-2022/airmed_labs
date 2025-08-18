<!-- Page Heading -->
<style type="text/css">
    .errmsg{
        color:red;
    }
    .errmsg1{
        color:red;
    }
    .errmsg2{
        color:red;
    }
    .errmsg3{
        color:red;
    }
    .errmsg4{
        color:red;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Branch Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_master/Branch_list"><i class="fa fa-users"></i>Branch List</a></li>
        <li class="active">Edit Branch</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">  
                <div class="box-body">
                    <div class="col-md-6">
                        <!-- form start -->

                        <?php $success = $this->session->flashdata('success'); ?>

                        <?php if (isset($success) != NULL) { ?>
                            <div class="alert alert-success alert-autocloseable-success">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $this->session->userdata('success'); ?>
                            </div>
                        <?php } ?>

                        <?php
                        foreach ($view_data as $data)
                        // echo "<pre>";
                        //   print_r($data);
                        //   exit; 
                            $id = $data['id'];
                        {
                            ?> 


                            <!-- form start -->
                            <?php
                            echo form_open_multipart('Branch_Master/Branch_edit/' . $id . '', ['method' => 'post', 'class' => 'form-horizontal',
                                'id' => 'target', 'enctype' => 'multipart/form-data', 'onsubmit' => "return jvalidation();"]);
                            ?>

                            <div class="form-group">
                                <label for="type">Branch Type</label><span style="color:red">*</span>

                                <input type="hidden" name="btype" id="btype">
                                <select class="form-control" name="branch_type_fk" id="branch_type" onchange="getBranch();">
                                    <option value="">Select Branch Type</option>
                                    <?php
                                    $ttype = 0;
                                    foreach ($branch_type as $row) {
                                        if ($row > 0) {
                                            ?>
                                            <option value="<?php echo $row['id']; ?>-<?php echo $row['type']; ?>" <?php
                                            if ($data['branch_type_fk'] == $row['id']) {
                                                echo "selected";
                                                $ttype = $row['type'];
                                            }
                                            ?>><?php echo ucwords($row['name']); ?></option>
                                                    <?php
                                                } else {
                                                    echo '<option value="">city is Not Avaliable</option>';
                                                }
                                            }
                                            ?> 

                                </select>
                                <span class="errmsg"></span>
                            </div>

                            <div class="form-group" id="branch_type_id" <?php if ($ttype == 2) { ?> style="display: none;" <?php } ?>>
                                <label for="type">Select PLM</label><span style="color:red">*</span>

                                <select class="form-control"  name="parent_fk" id="parent_id">
                                    <option value="">Select PLM</option>
                                    <?php
                                    foreach ($branch_list as $row) {
                                        if ($row > 0) {
                                            ?>
                                            <option value="<?php echo $row['id']; ?>" <?php
                                            if ($data['parent_fk'] == $row['id'] && $ttype != 2) {
                                                echo "selected";
                                            }
                                            ?>><?php echo ucwords($row['branch_name']); ?></option>

                                            <?php
                                        } else {
                                            echo '<option value="">Branch is Not Avaliable</option>';
                                        }
                                    }
                                    ?> 
                                </select>
                                <span class="errmsg1"></span>
    <?php echo form_error('parent_fk'); ?>
                            </div>


                            <div class="form-group">
                                <label for="name">Branch Code</label><span style="color:red">*</span>


    <?php
    echo form_input(['name' => 'branch_code', 'class' => 'form-control', 'placeholder' => 'Branch Code',
        'value' => $data['branch_code']]);
    ?>
    <?php echo form_error('branch_code'); ?>
                                <span class="errmsg2"></span>

                            </div>

                            <div class="form-group">
                                <label for="name">Branch Name</label><span style="color:red">*</span>


    <?php
    echo form_input(['name' => 'branch_name', 'class' => 'form-control', 'placeholder' => 'Branch Name',
        'value' => $data['branch_name'], 'id' => 'branch_id']);
    ?>
    <?php echo form_error('branch_name'); ?>

                                <span class="errmsg3"></span>
                            </div>




                            <div class="form-group">
                                <label for="type">City</label><span style="color:red">*</span>


                                <select class="form-control" id="city" onchange="test(this.value)" name="city">
                                    <?php
                                            foreach ($city as $row) {
                                                if ($row > 0) {
                                                    ?>
                                            <option value="<?php echo $row['cid']; ?>" <?php
                                                    if ($data['cid'] == $row['cid']) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo ucwords($row['city_name']); ?></option>
            <?php
        } else {
            echo '<option value="">city is Not Avaliable</option>';
        }
    }
    ?> 

                                </select>
                                <span class="errmsg4"></span>
                            </div>


                            <div class="form-group">
                                <label for="type">Address</label>

    <?php echo form_textarea(['class' => 'form-control', 'rows' => '3', 'cols' => '4', 'name' => 'address', 'value' => urldecode($data['address'])]); ?>

                                <?php echo form_error('address'); ?>

                            </div>
                            <div class="form-group">
                                <label for="name">Bank Name</label>

                                <input type="text"  name="bank_name" class="form-control" id="branch_name" placeholder="Bank Name"  value="<?php echo $data['bank_name']; ?>" >
                                <?php echo form_error('bank_name'); ?>

                            </div>

                            <div class="form-group">
                                <label for="name">Bank Account No</label>

                                <input type="text"  name="bank_acc_no" class="form-control" id="branch_name" placeholder="Bank Account No"  value="<?php echo $data['bank_acc_no']; ?>" >
    <?php echo form_error('bank_acc_no'); ?>

                            </div>
                            <div class="form-group">
                                <label for="name">Bank account details</label>

                                <?php echo form_textarea(['class' => 'form-control', 'rows' => '3', 'cols' => '4', 'name' => 'bankdetils', 'value' => urldecode($data['bankdetils'])]); ?>

                            </div>

                             <div class="form-group" id="auto_completed_div">
                                <label for="name">Auto Complete Job</label> &nbsp;&nbsp;
                                <input type="radio" name="auto_completejob" value="1" <?php
                                if ($data['auto_completejob'] == 1) {
                                    echo "checked";
                                }
                                ?>> Yes &nbsp;&nbsp;
                                <input type="radio" name="auto_completejob" value="0" <?php
                            if ($data['auto_completejob'] == 0) {
                                echo "checked";
                            }
                                ?>> No
                            </div>
                            
<!--                            <div class="form-group" id="sms_email" style="display: none;">-->
                            <div class="form-group" id="sms_email">
                                <input type="checkbox" name="sms_alert" id="sms_alert" value="1" <?php
                                if ($data['smsalert'] == 1) {
                                    echo "checked";
                                }
                                ?>> SMS Alert &nbsp;&nbsp;
                                <input type="checkbox" name="email_alert" id="email_alert" value="1" <?php
                                if ($data['emailalert'] == 1) {
                                    echo "checked";
                                }
                                ?>> Email Alert
                            </div>

                            <div class="form-group" id="sms_email">
                             
                                <input type="checkbox" name="ipd" id="ipd" value="1" <?php
                                if ($data['ipd'] == 1) {
                                    echo "checked";
                                }
                                ?>> IPD
                            </div>


                        </div>
                    </div>  

                    <div class="box-footer">

                        <button  class="btn btn-primary" name="button" type="submit">Update</button>

                        <input type="hidden" id="idh" name="idh" value="<?php echo $id; ?>">            
                    </div>


                </div>
            </div>

    <?php echo form_close(); ?>
    <?php
}
?>


        <script type="text/javascript">

            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
        </script>





        <script type="text/javascript">
<?php //if ($data['name'] == "AIRMED TECH") { ?>
                //$('#sms_email').show();
<?php //} ?>

            $('#state').on('change', function () {
                var stateID = $(this).val();
                //alert(stateID);
                if (stateID) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url(); ?>Doctor_master/cities/' + stateID,
                        data: {state_id: stateID},
                        success: function (html) {
                            $('#city').html(html);

                        }
                    });
                } else {

                    $('#city').html('<option value="">Select state first</option>');
                }
            });


            /* function test(stateID){
             alert(stateID); 
             $.ajax({
             type:'POST',
             url: "<?php echo base_url(); ?>Doctor_master/cities/"+stateID,
             data:'state_id='+stateID,
             error: function(jqXHR, error, code) {
             alert("not show");
             },
             success: function(data) {
             $('#city').html(html);
             }
             });
             }  */
        </script>     




        <script  type="text/javascript">
            $(document).ready(function () {
                $("#showHide").click(function () {
                    if ($("#password").attr("type") == "password") {
                        $("#password").attr("type", "text");
                    } else {
                        $("#password").attr("type", "password");
                    }

                });
            });
        </script>
        <script type="text/javascript">
            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 4000);
        </script>
        <!-- Vishal COde Start -->
        <script type="text/javascript">
            function getBranch() {
                var branch_id = $('#branch_type').val();
                $('#sms_alert').prop('checked', false);
                $('#email_alert').prop('checked', false);

                var arr = branch_id.split('-');

                if (arr[1] == '1') {
                    $('#branch_type_id').show();
                    $("#btype").val("1");

                } else {
                    $('#branch_type_id').hide();
                    $("#btype").val("0");
                }
//                if (arr[0] == '6') {
//                    $('#sms_email').show();
//                } else {
//                    $('#sms_email').hide();
//                    $('#sms_alert').prop('checked', true);
//                    $('#email_alert').prop('checked', true);
//                }
            }

            // getBranch();

            function jvalidation() {
                var branch_id = $('#branch_type').val();
                var id = branch_id.split('-');
                var parent = $('#parent_id').val();
                var branch_code = $('#branch_code').val();
                var branch_name = $('#branch_id').val();

                var city = $('#city').val();
                $('.errmsg').html("");
                $('.errmsg1').html("");
                $('.errmsg2').html("");
                $('.errmsg3').html("");
                $('.errmsg4').html("");
                var error_cnt = 0;
                if (branch_id == '') {
                    $('.errmsg').html("Branch Type Required");
                    error_cnt = 1;
                }
                if (id[1] == "1") {
                    if (parent == '') {
                        $('.errmsg1').html("PLM Required");

                        error_cnt = 1;
                    }
                }
                if (branch_code == '') {
                    $('.errmsg2').html("Branch Code Required");
                    error_cnt = 1;

                } else {

                }
                if (branch_name == '') {
                    $('.errmsg3').html("Branch Name Required");
                    error_cnt = 1;

                }

                var city = $('#city').val();
                if (city == '') {
                    $('.errmsg4').html("City Required");
                    error_cnt = 1;
                }
                if (error_cnt == 1) {
                    return false;
                }
            }
        </script>
        <!-- Vishal COde End -->

</section>
