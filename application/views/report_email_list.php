<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add User
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>test-master/test-list"><i class="fa fa-users"></i>Add User</a></li>
        </ol>
    </section>
    <style>
        .multiselect{width:100%;text-align: left;}
        .wordwrap { 
            white-space: pre-wrap;      /* CSS3 */   
            white-space: -moz-pre-wrap; /* Firefox */    
            white-space: -pre-wrap;     /* Opera <7 */   
            white-space: -o-pre-wrap;   /* Opera 7 */    
            word-wrap: break-word;      /* IE */
        }
        .multiselect-container.dropdown-menu {
            max-height: 400px;
            min-height: 100px;
            overflow-wrap: break-word;
            overflow-x: hidden;
            overflow-y: scroll;
            width: 100%;
        }
        a .checkbox {
            white-space: pre-line;
        }
        ul .active-result {
            white-space: pre-line;
            word-wrap: break-word; 
            width:100% !important;
        }
        .text_highlight:hover{
            text-decoration: underline;
            /*font-weight: bold;
            font-size: 12px;*/
        }
        span.multiselect-selected-text {
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            float: left;
            white-space:pre-line;
        }   
        .multiselect-native-select .btn-group button{width:245px;}
    </style>
    <!-- Main content -->
    <section class="content">
        <?php /*
          <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="H4">Add User</h4>
          </div>

          <?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST'); ?>
          <?php
          echo form_open_multipart('test_master/importtest_csv', $attributes);
          ?>
          <div class="modal-body">
          <div class="form-group">
          <label for="recipient-name" class="col-form-label">Recipient:</label>
          <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
          <label for="message-text" class="col-form-label">Message:</label>
          <textarea class="form-control" id="message-text"></textarea>
          </div>
          </div>
          <div class="modal-footer">
          <button type="button" id="model_close" class="btn btn-danger" data-dismiss="modal">Close</button>
          <input type="submit" id="add_admin_submit" name="add_menu" class="btn btn-primary" value="Upload"/>
          <!--                            <button type="button" id="add_admin_submit"  data-dismiss="modal"  onclick="sub('admin_add');" name="add_menu" class="btn btn-primary" disabled=''> Add </button>-->
          <?= form_close(); ?>
          </div>


          </div>
          </div>
          </div> */ ?>

        <div class="modal fade" id="exampleModalLabel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php echo form_open("Daily_dsr/add_user", array("method" => "POST", "role" => "form")); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="email" name="email" required="">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Branch:</label>
                            <select class="multiselect-ui form-control" title="Select Branch" multiple="multiple" name="branch[]">
                                <?php foreach ($branchlist as $bkey) { ?>
                                    <option value="<?= $bkey["id"] ?>"><?= $bkey["branch_name"] ?></option><?php
                                }
                                ?>
                            </select>
                        </div>
<!--                        <div class="form-group">
                            <label for="message-text" class="col-form-label">City:</label>
                            <select class="multiselect-ui form-control" title="Select Branch" multiple="multiple" name="city[]">
                                <?php
                                foreach ($city as $bkey) {
                                    ?>
                                    <option value="<?= $bkey["id"] ?>"><?= $bkey["name"] ?></option> 
                                    <?php
                                }
                                ?>
                            </select>
                        </div>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" value="Save" class="btn btn-primary"/>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Test List</h3>
                        <a style="float:right;margin-right:5px;" onclick="$('#exampleModalLabel').modal('show');"  class="btn btn-primary btn-sm" ><strong > Add</strong></a>
                        <a style="float:right;margin-right:5px;" href="<?=base_url();?>Daily_dsr/send_report"  class="btn btn-primary btn-sm" ><strong > Test Report</strong></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                <span id="msg_success"></span>
                            </div>
                        </div>

                        <div class="tableclass">
                            <table id="example4" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Assign Branch</th>
<!--                                        <th width="20%">Assign City</th>-->
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = $counts;
                                    foreach ($new_query as $row) {
                                        $cnt++;
                                        ?>
                                        <tr> <td><?php echo $cnt; ?></td>
                                            <td><?php echo ucwords($row['email']); ?></td>
                                        <?php /*    <td><?php echo $row["cityes"][0]["name"]; ?></td> */ ?>
                                            <td><?php echo $row["branches"][0]["name"]; ?></td>
                                            <td>
                                                <a  href='<?php echo base_url(); ?>Daily_dsr/delete_user/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>      
                                            </td>
                                        </tr>
                                    <?php }if (empty($query)) {
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
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<Script>
                                                function get_csv_link() {
                                                    var form = $("#job_search");
                                                    var recursiveDecoded = decodeURIComponent(form.serialize());
                                                    window.location.href = "<?= base_url(); ?>/job_master/export_csv?" + recursiveDecoded;
                                                }
                                                $(function () {
                                                    $('.multiselect-ui').multiselect({
                                                        includeSelectAllOption: true,
                                                        nonSelectedText: 'Select Branch'
                                                    });
                                                });
                                                $(function () {
                                                    $('.chosen').chosen();
                                                });
                                                jQuery(".chosen-select").chosen({
                                                    search_contains: true
                                                });
</script>
