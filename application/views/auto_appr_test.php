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
    .chosen-container {width: 100% !important;}

    span.multiselect-native-select {
        position: relative
    }
    span.multiselect-native-select select {
        border: 0!important;
        clip: rect(0 0 0 0)!important;
        height: 1px!important;
        margin: -1px -1px -1px -3px!important;
        overflow: hidden!important;
        padding: 0!important;
        position: absolute!important;
        width: 1px!important;
        left: 50%;
        top: 30px
    }
    .multiselect-container {
        position: absolute;
        list-style-type: none;
        margin: 0;
        padding: 0
    }
    .multiselect-container .input-group {
        margin: 5px
    }
    .multiselect-container>li {
        padding: 0
    }
    .multiselect-container>li>a.multiselect-all label {
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group label {
        margin: 0;
        padding: 3px 20px 3px 20px;
        height: 100%;
        font-weight: 700
    }
    .multiselect-container>li.multiselect-group-clickable label {
        cursor: pointer
    }
    .multiselect-container>li>a {
        padding: 0
    }
    .multiselect-container>li>a>label {
        margin: 0;
        height: 100%;
        cursor: pointer;
        font-weight: 400;
        padding: 3px 0 3px 30px
    }
    .multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
        margin: 0
    }
    .multiselect-container>li>a>label>input[type=checkbox] {
        margin-bottom: 5px
    }
    .btn-group>.btn-group:nth-child(2)>.multiselect.btn {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px
    }
    .form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
        padding: 3px 20px 3px 40px
    }
    .form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
        margin-left: -20px;
        margin-right: 0
    }
    /* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: justify; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    @media (max-width: 980px) {
        .pending_job_list_tbl table, .pending_job_list_tbl thead, .pending_job_list_tbl tbody, .pending_job_list_tbl th, .pending_job_list_tbl td, .pending_job_list_tbl tr {display: block;}
        .pending_job_list_tbl thead tr {position: absolute; top: -9999px; left: -9999px;}
        .pending_job_list_tbl tr {border: 1px solid #ccc !important;}
        .pending_job_list_tbl td {border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 60%; text-align: left;}
        .pending_job_list_tbl td:before {position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}
        .pending_job_list_tbl tr{margin-bottom:15px;}
        .table-responsive.pending_job_list_tbl{border:none !important;}

        .pending_job_list_tbl td:nth-of-type(1):before {content: "No";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Indent No";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Branch Name";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Item";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Created Date";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Status";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Action";}


    }
    /* End pending_job_detail responsive table */
    .box.box-primary button i{margin-right:5px;}
    .morecontent span {
        display: none;
    }
    .morelink {
        display: block;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style><div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Auto Approve Test<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>

            <li><i class="fa fa-users"></i>Auto Approve Test List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Auto Approve Test List</h3>
                        <a style="float:right;" href="javascript:void(0);" onclick="$('#myModal').modal('show');"  class="btn btn-primary btn-sm" ><i class="fa fa-plus-circle" ></i><strong > Add</strong></a> 
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
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <?php $attributes = array('class' => 'form-horizontal', 'method' => 'get', 'role' => 'form'); ?>
                        <?php echo form_open('Auto_approve_test', $attributes); ?>
                        <!--                        <div class="col-md-3">
                                                    <select name="branch_name" class="form-control">
                                                        <option value="">Select Branch</option>
                        <?php //foreach($branch_type as $val){ ?>
                                                        <option value="<?php //echo $val['id'];             ?>" <?php //if($branch_name == $val['id']) { echo "selected='selected'";}             ?>><?php //echo $val['branch_name'];             ?></option>
                        <?php //} ?>
                                                    </select>
                                                </div>-->
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="test" placeholder="Enter Test" value="<?php echo $test; ?>"/>
                        </div>
                        <input type="submit"  value="search" class="btn btn-primary btn-md">
						<a href="<?= base_url();?>Auto_approve_test/index" class="btn btn-primary btn-md">Reset</a>
                        </form>
                        <br> 
                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Test Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($query as $row) {
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['test_name']; ?></td>
                                            <td><?php
                                                if ($row['status'] == '1') {
                                                    echo '<span class="label label-success">Active<span>';
                                                } else {
                                                    echo '<span class="label label-danger">Inactive<span>';
                                                }
                                                ?></td>
                                            <td><a href='javascript:void(0)' data-toggle="tooltip" data-original-title="Edit" onclick="edit('<?php echo $row['id']; ?>', '<?php echo $row['test_fk']; ?>')"><i class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }if (empty($query)) {
                                        ?>
                                        <tr>
                                            <td colspan="4">No records found</td>
                                        </tr>
                                    <?php } ?>
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

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add</h4>
            </div>
            <?php echo form_open("Auto_approve_test/add", array("method" => "POST", "role" => "form", "id" => 'submit_id')); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Test Name</label>
                    <select class="chosen chosen-select" id="test" name="test">
                        <option value="">--Select Test---</option>
                        <?php
                        foreach ($test_list as $val) {
                            ?>
                            <option value="<?= $val["id"] ?>"><?= $val["test_name"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span style="color:red" id="branch_error_id"></span>
                </div>
                <div class="form-group">
                    <label>Auto Approve</label>&nbsp;&nbsp;
                    <input type="radio" name="status" value="1" checked> Yes &nbsp;
                    <input type="radio" name="status" value="0"> No<br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitData();">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<div id="myModalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
            </div>
            <?php echo form_open("Auto_approve_test/update", array("method" => "POST", "role" => "form", "id" => 'edit_submit_id')); ?>
            <input type="hidden" name="tp_id" value="" class="sub_id" id="tp_id">
            <div class="modal-body">
<!--                <div class="form-group">
                    <label>Test Name</label>
                    <select class="chosen chosen-select branch_select test" id="test" name="test_edit">
                        <option value="">--Select Test---</option>
                        <?php
                        //foreach ($test_list as $val) {
                            ?>
                            <option value="<?php //echo  $val["id"] ?>"><?php //echo $val["test_name"] ?></option>
                        <?php //}
                        ?>
                    </select>

                    <span style="color:red" id="edit_branch_error_id"></span>
                    <input type="hidden" name="branch_fk" value="" class="branch_id">
                </div>-->
                <div class="form-group">
                    <label>Auto Approve</label>&nbsp;&nbsp;
                    <input type="radio" name="status_edit" value="1"> Yes &nbsp;
                    <input type="radio" name="status_edit" value="0"> No<br>
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
<span id="drop_id" style="display:none;">
    <textarea name="email" placeholder="Ente Email" id="email_id" class="form-control email_sub_id" value="" ></textarea>


</span>
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

</script>

<script>
    function submitData() {
        var frm_data = $('#submit_id');
        var path = frm_data.attr('action');
        var status = $('.name').val();
        var test = $('#test').val();
        $('#branch_error_id').html('');
        $('#email_error_id').html('');
        var cnt = 0;
        if (test == '') {
            $('#branch_error_id').html('Please select test.');
            cnt = 1;
        }

        if (cnt == 1) {
            return false;
        }
        if (test != '') {
            $.ajax({
                url: path,
                type: 'post',
                data: frm_data.serialize(),
                success: function (data) {
                    if (data == '1') {
                        window.location.href = '<?php echo base_url(); ?>Auto_approve_test';
                    }
                    if (data == '0') {
                        $('#span_error').html("please check record");
                    }
                }
            });
        }
    }
    function edit(tid, cid) {
        $(".branch_select").val(cid);
        $("#tp_id").val(tid);
        
//        $(".branch_select").attr("disabled", "disabled");
        /*AJAX start*/
        $.ajax({
            url: '<?php echo base_url(); ?>Auto_approve_test/edit/' + tid,
            type: 'GET',
            success: function (data) {
                var myObj = JSON.parse(data);

                var eid = myObj.test_fk;
                var status = myObj.status;
                $('.branch_select').val(cid);
                if(status == '1'){
                    $("input[name=status_edit][value='1']").prop('checked', true);
                }else{
                    $("input[name=status_edit][value='0']").prop('checked', true);
                }
                
                $nc('.chosen').trigger("chosen:updated");
            }
        });
        /*AJAX end*/
        $("#myModalEdit").modal("show");

    }

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".add_button").click(function () {
            var fieldHTML = "<div class='copy_id'><div class='form-group'><label>Email</label><textarea name='email[]' placeholder='Ente Email' id='email_id' class='form-control email_sub_id' value=''></textarea><div>Separete by comma(,)</div><span style='color:red' id='edit_email_error_id'></span></div><span id='span_error'></span><div class=''><label for='name' style='margin-top:40px;'></label> <button class='btn btn-primary remove_button' type='button'><i class='fa fa-minus-circle' aria-hidden='true'></i></button></div></div>";
            $('#field_wrapper').append(fieldHTML);
        });
        $("#field_wrapper").on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(this).parents('.copy_id').remove(); //Remove field html
        });
    });
</script>