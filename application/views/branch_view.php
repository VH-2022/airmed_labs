<div class="full_bg" style="display:none;" id="loader_div1">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<style>
    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:350px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .nav-justified>li{width:auto !important;}
    .nav-justified>li.active{background:#eee; border-top:3px solid #3c8dbc;}
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <section class="content-header">
                            <h1>
                                <strong><?php echo ucfirst($view_data[0]['branch_name']); ?></strong>(<?php echo ucfirst($view_data[0]['name']); ?> ) 
                                <small></small>
                                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Branch Settings</button>
                            </h1>
                            <ol class="breadcrumb">
                                <span style="margin-right:30px; font-size:20px;"><?php
                                    /* $old_date = $view_data[0]['createddate'];

                                      $explode_date_time = explode(' ', $old_date);
                                      $date = explode('-', $explode_date_time[0]);
                                      $new_date = $date[2] . '-' . $date[1] . '-' . $date[0] . ' ' . $explode_date_time[1];
                                      echo $new_date; */
                                    ?>  </span>
                            </ol>
                        </section>
                        <section class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                        <div class="widget">
                                            <?php if (!empty($success['0']) != NULL) { ?>
                                                <div class="alert alert-success alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <?php echo $success['0']; ?>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($unsuccess['0']) != NULL) { ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                    <?php echo $unsuccess['0']; ?>
                                                </div>
                                            <?php } ?>
                                            <div id="updatesuccess" ></div>
                                        </div>
                                        <div class="box-header">
                                            <!-- form start -->
                                            <h3 class="box-title">Branch Details</h3>
                                        </div>

                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Branch Type:</label>
                                                                <?php echo ucfirst($view_data[0]['name']); ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Branch Code:</label>
                                                                <?php echo ucfirst($view_data[0]['branch_code']); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Address:</label>
                                                                <?php
                                                                if ($view_data[0]['address'] != '') {
                                                                    $address = ucfirst($view_data[0]['address']);
                                                                } else {
                                                                    $address = 'N/A';
                                                                }
                                                                echo $address;
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Bank Name:</label>
                                                                <?php
                                                                if ($view_data[0]['bank_name'] != '') {
                                                                    $bank_name = ucfirst($view_data[0]['bank_name']);
                                                                } else {
                                                                    $bank_name = 'N/A';
                                                                }
                                                                echo $bank_name;
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Bank Account No:</label>
                                                                <?php
                                                                if ($view_data[0]['bank_acc_no'] != '') {
                                                                    $bank_acc_no = $view_data[0]['bank_acc_no'];
                                                                } else {
                                                                    $bank_acc_no = '0';
                                                                }
                                                                echo $bank_acc_no;
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Bank Detail:</label>
                                                                <?php
                                                                if ($view_data[0]['bankdetils'] != '') {
                                                                    $bankdetils = ucfirst($view_data[0]['bankdetils']);
                                                                } else {
                                                                    $bankdetils = 'N/A';
                                                                }
                                                                echo $bankdetils;
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">City Name:</label>
                                                                <?php echo $view_data[0]['city_name']; ?>
                                                            </div>
                                                        </div>
                                                        <?php if ($view_data[0]['parent'] != '') { ?>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="exampleInputFile">Parent:</label>
                                                                    <?php
                                                                    if ($view_data[0]['parent'] != '') {
                                                                        $parent = ucfirst($view_data[0]['parent']);
                                                                    } else {
                                                                        $parent = 'N/A';
                                                                    }
                                                                    echo $parent;
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="exampleInputFile">Parent Node:</label>
                                                                    <?php
                                                                    $cnt = 1;
                                                                    foreach ($parent_node as $val) {
                                                                        $child_node = '<br>' . $cnt++ . ':-' . ucfirst($val['parent_id']);

                                                                        echo $child_node;
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>

                                                        <?php } ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Client Name:</label>
                                                                <?php
                                                                /* if($query[0]['ClientName'] != ''){    
                                                                  $old_record =explode(',',$query[0]['ClientName']);

                                                                  $cnt = 1;
                                                                  foreach($old_record as $val){
                                                                  $client_name = '<br>'.$cnt++.':-'.ucfirst($val);
                                                                  echo $client_name;
                                                                  }
                                                                  }else{
                                                                  $client_name  ='N/A';
                                                                  echo $client_name;
                                                                  } */
                                                                if ($getlaball[0]->labname != "") {

                                                                    $old_record = explode(',', $getlaball[0]->labname);

                                                                    $cnt = 1;
                                                                    foreach ($old_record as $val) {
                                                                        $client_name = '<br>' . $cnt++ . ':-' . ucfirst($val);
                                                                        echo $client_name;
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <?php /* if($getlaball[0]->labname != ""){ ?>

                                                      <div class="row">
                                                      <div class="col-md-6">
                                                      <div class="form-group">
                                                      <label for="exampleInputFile">Lab Name:</label>
                                                      <?= ucwords($getlaball[0]->labname);   ?>
                                                      </div>
                                                      </div>

                                                      </div>
                                                      <?php } */ ?>


                                                </div>

                                            </div>
                                        </div>


                                    </div>




                                </div>
                            </div>


                            <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Branch Settings</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> 
                                        </div>
                                        <?php if (empty($processing_center)) { ?>
                                            <?php echo form_open("Branch_Master/branch_setting/" . $view_data[0]['id'], array("role" => "form", "method" => "post", "id" => "setting_form")); ?>
                                        <?php } ?>
                                        <div class="modal-body">

                                            <div class="form-group">
                                                <label for="recipient-name" class="col-form-label">Branch Type:</label>
                                                <select class="form-control" name="type" onchange="check_branch_type(this);">
                                                    <option value="1">PLM/TECH</option>
                                                    <option value="2">OTHER(psc,fpsc,sns,hlm)</option>
                                                </select>
                                            </div>
                                            <div class="form-group" id="one_div">
                                                <label for="recipient-name" class="col-form-label">Copy Parameter To:</label>
                                                <select class="form-control" name="cparameter" id="type">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($getProcessBranch as $pkey) { ?>
                                                        <option value="<?= $pkey->id ?>"><?= $pkey->branch_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group" id="two_div" style="display: none;">
                                                <label for="recipient-name" class="col-form-label">Select Processing Center:</label>
                                                <select class="form-control" name="processing">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($getProcessBranch as $pkey) { ?>
                                                        <option value="<?= $pkey->id ?>"><?= $pkey->branch_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="recipient-name" class="col-form-label">Copy Letterpad To:</label>
                                                <select class="form-control" name="letterpad">
                                                    <option value="">--Select--</option>
                                                    <?php foreach ($getPdfBranch as $lkey) { ?>
                                                        <option value="<?= $lkey->id ?>"><?= $lkey->branch_name ?></option>
                                                    <?php } ?>
                                                </select>
                                                <small><b>Note :-</b> Copy parameters take few minutes. please wait while copying.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <?php if (empty($processing_center)) { ?>

                                                <center id="fa_loader" style="display:none;"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i><br><span>Copying...</span></center>
                                                <div id="m_button"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" onclick="form_send();" class="btn btn-primary">Update</button></div>

                                            <?php } else { ?>
                                                <center><b>You already saved branch settings.</b></center>
                                            <?php } ?>
                                        </div>
                                        <?= form_close(); ?>
                                    </div>
                                </div>
                            </div>


                        </section>

                    </div>
                </div>
            </div>
        </div><!-- /.tab-content -->
    </div><!-- nav-tabs-custom -->
</section>
<!-- /.row -->
<script>
    function check_branch_type(val) {
        if (val.value == 1) {
            $("#one_div").attr("style", "display:block");
            $("#two_div").attr("style", "display:none");
        }
        if (val.value == 2) {
            $("#one_div").attr("style", "display:none");
            $("#two_div").attr("style", "display:block");
        }
    }
    function form_send() {
        var frm_data = $('#setting_form');
        var path = $('#setting_form').attr('action');

        $.ajax({
            url: path,
            type: "post",
            data: frm_data.serialize(),
            beforeSend: function () {
                $("#fa_loader").attr("style", "");
                $("#m_button").attr("style", "display:none;");
            },
            success: function (data) {
                if (data == '1') {
                    alert("Setting successfully saved.");
                } else {
                    alert("Oops somthing wrong.");
                }
                window.location.reload();
            },
            error: function () {
                alert("Oops somthing wrong.");
                $("#fa_loader").attr("style", "display:none;");
                $("#m_button").attr("style", "");
            },
            complete: function () {
                $("#fa_loader").attr("style", "display:none;");
                $("#m_button").attr("style", "");
            },
        })
    }
</script>