<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
<style>
    .ui-btn.ui-icon-carat-d.ui-btn-icon-right.ui-corner-all.ui-shadow
	{
		background: none !important;
    background-image:none !important;
    background-image: none !important;
    background-image: none !important;
    background-image: none !important;
    background-image: none !important;
    background-image:none !important;
    border-color:#ccc !important;   
    text-shadow: none;
    background-color: white;
	}
	
	.ui-btn.ui-icon-carat-d.ui-btn-icon-right.ui-corner-all.ui-shadow .form-control
	{
		border: transparent !important;
    height: inherit !important;
    line-height: inherit !important;
    padding: 0 10px !important;
    font-weight: normal;
    font-size: 13px;
    text-align: left;
	}
    .padding-left-0{padding-left:0}
	.content{min-height:inherit;}
    .no-padding{padding:0;}
    .repeat-img{    width: 100px;
                    height: 100px;
                    overflow: hidden;
                    position: relative;
                    padding: 10px 10px 0 0;}
    .repeat-img img{width:100%;height:auto;overflow:hidden;margin:0 !important;}
    .repeat-img a {
        position: absolute;
        top: 0px;
        right: 5px;
        width: 25px;
        height: 25px;
        background: #e4e1e1;
        border-radius: 50%;
        text-align: center;
        vertical-align: middle;
        line-height: 25px;
        border: 1px solid #e0e0e0;
    }
    .repeat-img a i{color:#dd4b39;}
	.full-div{background:#f1f2f3;display:inline-block;margin-bottom:0px;width:100%;}
	.page-header {margin: 10px 0 00px 0; }


    @media only screen and (max-width:1024px) {
        .page-header {font-size: 17px;}
        /* Force table to not be like tables anymore */
        table,thead,tbody,th,td, tr { display: block; 	}

        /* Hide table headers (but not display: none;, for accessibility) */
        table thead tr { 
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        table tr { border: 1px solid #ccc; }
        table td input{width:100%}

        table td,table th { 
            /* Behave  like a "row" */
            border: none;		
            position: relative;
            padding-left: 50% !important; 
            white-space: normal;
            text-align:left;
        }

        table td:before ,table th:before{ 
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            top: 6px;
            left: 6px;
            width: 45%; 
            padding-right: 10px; 
            white-space: nowrap;
            text-align:left;
            font-weight: bold;
        } 


        .table-mobile td:nth-of-type(1):before {
            content: "Code";

        }
        .table-mobile td:nth-of-type(2):before {
            content: "Parameter Name";

        }
        .table-mobile td:nth-of-type(3):before {
            content: "Value";

        }
        .table-mobile td:nth-of-type(4):before {
            content: "Parameter Range";

        }
        .table-mobile td:nth-of-type(5):before {
            content: "Parameter Unit";

        }
        .table-mobile td:nth-of-type(6):before {
            content: "Condition";

        }





        @media(max-width:400px){
            .repeat-img{    width: 120px;
                            height: 120px;
                            overflow: hidden;
                            position: relative;
                            padding: 10px 10px 0 0;}}
            </style>

<div data-role="header" data-position="fixed" >
		<h1>Approve Result</h1>
		<a id="backurl" data-rel="back" data-icon="carat-l" data-ajax="false" data-iconpos="notext"></a>
		 <a href="#panel-reveal" data-icon="bars" class="ui-btn-right" data-iconpos="notext">Menu</a>
		
	</div>
	
<div data-role="content" class="jqm-content">

<div id="overlay" data-role="popup" data-overlay-theme="b" class="ui-content">
  
</div>

<div id="successapprove" data-theme="c"  data-role="popup" data-overlay-theme="b" class="ui-content">
 

		<div role="main" class="ui-content">
		<h4>Test successfully Approved.</h4>
		
			<a href="<?= base_url()."pathologist/dashboard/patient_job_test?job_fk=".$cid; ?>" class="ui-btn ui-shadow ui-corner-all ui-btn-b">Ok</a>
		</div>
</div>
 <?php echo form_open_multipart("pathologist/dashboard/add_value_exists2", array("id" => "add_result", "method" => "post", "onsubmit" => "return check_hundred()")); ?>
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="row">

                <div class="col-md-12">
                    <div class="box box-primary">
                       
                        <div class="box-header">
                            <h3 class="box-title">Approve Result</h3>
                        </div>
                        <div class="box-body">
                            <?php if ($success[0] != '') { ?>
                                <div class="widget">
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                        <?php echo $success['0']; ?>
                                    </div> 
                                </div>
                            <?php } ?>

                            <div class="full-div" style="">
                                <div class="form-group col-sm-2">
                                    <label for="exampleInputFile">Reg NO :-</label>
                                    <?= $query[0]['id']; ?>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="exampleInputFile">Customer Name :-</label>
                                    <?= ucfirst($user_data[0]['full_name']); ?>  
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="exampleInputFile">Doctor :-</label>
                                    <?= $query[0]['dname']; ?>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="exampleInputFile">Gender :-</label>
                                    <?= ucfirst($user_data[0]["gender"]) ?> (  <?php
                                    echo $user_data[0]["age"] . " " . $user_data[0]["age_type"];
                                    ?> )
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="exampleInputFile">Note :-</label>
                                    <?= $query[0]['note']; ?>
                                </div>

                            </div>
                        </div>
                     
		  </div>
             </div>
		   </div>
	  </section>
	  <section class="content">
	         <div class="row">
			        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissable" id="msg_cancel" style="display:none;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                Results Not Add
                            </div>

                            <div class="alert alert-success alert-dismissable" id="msg_success" style="display:none;">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                Results Add Successfully
                            </div>
						<div class="box box-primary">
                            <div class="box-header">
                                <?php
                                $tabindex = 100;
                                $ts = explode('#', $query[0]['testname']);
                                $tid = explode(",", $query[0]['testid']);
                                $last_array = array();
                                $cnt = 0;
                                $cnnt = 0;
                                foreach ($new_data_array as $testidp) {
                                    //print_r($testidp); die();
                                    // if ($parameter_list[$cnt][0]['test_fk'] == $testidp) {
                                    //echo "<pre>";print_r($testidp); die();
                                    if ($cnt != 0) {
                                        echo "</br></br>";
                                    }
                                    ?>
                                    <input type="hidden" name="tid[]" value="<?= $testidp["test_fk"] ?>"/>
                                    <h2 class="page-header">
                                        <i class="fa fa-list-alt"></i> Add Result For <?php
                                        if (empty($testidp["PRINTING_NAME"])) {
                                            echo $testidp["test_name"];
                                        } else {
                                            echo ucfirst($testidp["PRINTING_NAME"]);
                                        }
                                        ?>
        <!--                                    <small class="pull-right"><input type="button" value="Add Parameter" data-toggle="modal" data-target="#myModal_view" onclick="add_reference('<?php echo $testidp["test_fk"]; ?>', 'add');" class="btn btn-sm btn-primary"></small>-->
                                    </h2>
								</div>
								<div class="box-body">
                                    <input type="hidden" name="test_fk[]" value="<?= $testidp["test_fk"] ?>"/>
									<div class="inline-checkbox">
                                    <input type="checkbox" id="is_formula_<?php echo $cnnt; ?>" name="use_formula_<?= $testidp["test_fk"] ?>" value="1" <?php
                                    if ($testidp['use_formula'] == 1 || $testidp['use_formula'] == '') {
                                        echo "checked";
                                    }
                                    ?> tabindex="<?php echo $tabindex++; ?>" onchange="DelayedCallMe(200, '<?= $cnnt ?>', '<?php echo $testidp[0]["parameter"][0]['id']; ?>');"/><label> Use Formula?</label></div> 
                                    <table id="example4" class="table table-bordered table-striped table-mobile">
                                        <thead>
                                            <tr>
                                                <?php /*  <th width="4%">Order</th> */ ?>
                                                <th width="4%">Code</th>
                                                <th width="15%">Parameter Name</th>
                                                <?php if ($testidp["report_type"] == 2) { ?>
                                                    <th>SENSITIVITY</th>
                                                    <th width="12%">ZONE OF INHIBITION</th>
                                                <?php } else { ?>
                                                    <th width="12%">Value</th>
                                                <?php } ?>
                                                <th width="20%">Parameter Range</th>
                                                <th width="10%">Parameter Unit</th>
                                                <th width="25%">Condition</th>
                                                <?php /* <th width="10%">action</th> */ ?>
                                        <input type="hidden" name="para_job_id" value="<?php echo $cid; ?>">
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cn = 0;

                                            $para_array = array();
                                            foreach ($testidp[0]["parameter"] as $parameter) {
                                                $para_array[] = $parameter["id"];
                                                if (!empty($parameter['parameter_name'])) {
                                                    echo '<input type="hidden" name="para[]" value="' . $parameter['test_parameter_id'] . '"/>';
                                                    if (isset($parameter["para_ref_rng"][0]['id'])) {
                                                        /* Start cal */
                                                        if (count($parameter['user_val']) > 0) {
                                                            $status = "Normal";
                                                            if ($parameter["para_ref_rng"][0]['absurd_low'] > $parameter['user_val'][0]["value"]) {
                                                                $status = "Emergency";
                                                            }
                                                            if ($parameter["para_ref_rng"][0]['ref_range_low'] > $parameter['user_val'][0]["value"]) {
                                                                $status = $parameter["para_ref_rng"][0]['low_remarks'];
                                                            }
                                                            if ($parameter["para_ref_rng"][0]['critical_low'] > $parameter['user_val'][0]["value"]) {
                                                                $status = $parameter["para_ref_rng"][0]['critical_low_remarks'];
                                                            }
                                                            if ($parameter["para_ref_rng"][0]['ref_range_high'] < $parameter['user_val'][0]["value"]) {
                                                                $status = $parameter["para_ref_rng"][0]['high_remarks'];
                                                            }
                                                            if ($parameter["para_ref_rng"][0]['critical_high'] < $parameter['user_val'][0]["value"]) {
                                                                $status = $parameter["para_ref_rng"][0]['critical_high_remarks'];
                                                            }
                                                        } else {
                                                            $status = "Emergency";
                                                        }
                                                        if (!empty(trim($parameter["para_ref_rng"][0]["ref_range"]))) {
                                                            $status = $parameter["para_ref_rng"][0]["ref_range"];
                                                        }
                                                        /* End cal */
                                                        ?>

                                                        <tr>
                                                            <?php /* <td><input type="text" class="number" value="<?php echo $parameter['new_order']; ?>" style="width:100%" name="order[]"/></td> */ ?>
                                                            <?php if ($parameter["is_group"] != 1) { ?><td><b><?php echo $parameter['id']; ?></b></td><?php } ?>
                                                            <td <?php
                                                            if ($parameter["is_group"] == 1) {
                                                                echo 'colspan="6"';
                                                            }
                                                            ?>><?php echo $parameter['parameter_name']; ?></td>
                                                                <?php if ($parameter["is_group"] != 1) { ?>
                                                                <td>
                                                                    <input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                                                    <input type="hidden" name="parameter_id_<?php echo $cnt; ?>" value="<?php echo $parameter['id']; ?>">
                                                                    <input type="text" autocomplete="off" class="number hundread<?php echo $parameter["is_hundred"]; ?>" tabindex="<?php echo $tabindex++; ?>"  formula="<?php echo ( $parameter['formula'] != "") ? "true" : "false"; ?>" name="parameter_value_<?php echo $cnt; ?>" onkeyup="DelayedCallMe(200, '<?= $cnnt ?>', '<?php echo $parameter['id']; ?>');" id="<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?= $parameter['user_val'][0]["value"]; ?>">
                                                                </td>
                                                                <?php if ($testidp["report_type"] == 2) { ?>
                                                                    <td> <input type="text" class="number" tabindex="<?php echo $tabindex++; ?>" style="width:83%;float: left;" name="parameter2_value_<?php echo $cnt; ?>" value="<?= $parameter['user_val'][0]["value2"]; ?>"></td>
                                                                <?php } ?>
                                                        <input type="hidden" name="p_low" id="p_start_<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?php echo $parameter["para_ref_rng"][0]['ref_range_low']; ?>"/>
                                                        <input type="hidden" name="p_high" id="p_end_<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?php echo $parameter["para_ref_rng"][0]['ref_range_high']; ?>"/>
                                                        <td><?php echo $parameter["para_ref_rng"][0]['ref_range_low']; ?>-<?php echo $parameter["para_ref_rng"][0]['ref_range_high']; ?></td>
                                                        <td><?php
                                                            echo $parameter['parameter_unit'];
                                                            if (empty($parameter['parameter_unit'])) {
                                                                echo "-";
                                                            }
                                                            ?></td>
                                                        <td>
                                                            <?php
                                                            echo $status;
                                                            if (empty($status)) {
                                                                echo "-";
                                                            }
                                                            ?> 
                                                        </td>
                                                    <?php } ?>
                                                    <?php $last_array[] = array("cnt" => $cnnt, "pid" => $parameter['id']); ?>
                <!--                                                <td>
                                                    <?php /* <a href='javascript:void(0);' onclick="add_reference('<?php echo $parameter['pid']; ?>');" data-toggle="tooltip" data-original-title="Add Reference Range" > <span class="label label-primary"><i class="fa fa-plus"> </i></span> </a>
                                                      <a  href='#' onclick="update_parameter('<?php echo $parameter['pid']; ?>');" data-toggle="modal" data-target="#update_par" data-original-title="Edit"> <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a> */ ?>
                <a href='javascript:void(0);' onclick="edit_parameter('<?php echo $testidp["test_fk"]; ?>', '<?php echo $parameter['id']; ?>');" data-toggle="tooltip" data-original-title="Edit Parameter" > <span class="label label-primary"><i class="fa fa-edit"> </i></span> </a>
                <a  onclick="return confirm('Are you sure you want to spam this parameter?');" href='<?php echo base_url(); ?>add_result/delete_parameter/<?php echo $parameter['id']; ?>/<?php echo $cid; ?>/<?php echo $testidp["test_fk"]; ?>' data-toggle="tooltip" data-original-title="Spam Parameter" > <span class="label label-danger"><i class="fa fa-trash"> </i></span> </a>
                </td>-->
                                                    </tr>

                                                <?php } else { ?>
                                                    <tr>
                                                        <?php /* <td><input type="text" class="number" value="<?php echo $parameter['new_order']; ?>" style="width:100%" name="order[]"/></td> */ ?>
                                                        <?php if ($parameter["is_group"] != 1) { ?><td><b><?php echo $parameter['id']; ?></b></td><?php } ?>
                                                        <td <?php
                                                        if ($parameter["is_group"] == 1) {
                                                            echo "colspan='6'";
                                                        }
                                                        ?>><?php echo $parameter['parameter_name']; ?></td>
                                                            <?php if ($parameter["is_group"] != 1) { ?>
                                                            <td><input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                                                <input type="hidden" name="parameter_id_<?php echo $cnt; ?>" value="<?php echo $parameter['id']; ?>">
                                                                <?php if (!empty($parameter["para_ref_status"])) { ?>
                                                                    <select  tabindex="<?php echo $tabindex++; ?>"   class="form-control" name="parameter_value_<?php echo $cnt; ?>" <?php
                                                                    if (!empty($parameter['user_val'][0]["value"])) {
                                                                        echo "";
                                                                    }
                                                                    ?>><option value="">--Select--</option>
                                                                                 <?php foreach ($parameter["para_ref_status"] as $kky) { ?>
                                                                            <option value="<?= $kky["id"] ?>" <?php
                                                                            if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                                                echo "selected";
                                                                            }
                                                                            ?>><?= $kky["parameter_name"]; ?></option>
                                                                                <?php } ?>
                                                                    </select>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                                                    <input type="hidden" name="parameter_id_<?php echo $cnt; ?>" value="<?php echo $parameter['id']; ?>">
                                                                    <input type="text" autocomplete="off" class="number hundread<?php echo $parameter["is_hundred"]; ?>"  tabindex="<?php echo $tabindex++; ?>"   formula="<?php echo ( $parameter['formula'] != "") ? "true" : "false"; ?>"  name="parameter_value_<?php echo $cnt; ?>" onkeyup="DelayedCallMe(200, '<?= $cnnt ?>', '<?php echo $parameter['id']; ?>');" id="<?= $cnnt ?>_<?php echo $parameter['id']; ?>" value="<?= $parameter['user_val'][0]["value"]; ?>">
                                                                    <?php
                                                                }
                                                                ?>
                                                            </td>
                                                            <?php if ($testidp["report_type"] == 2) { ?>
                                                                <td> <input type="text" class="number" tabindex="<?php echo $tabindex++; ?>" style="width:83%;float: left;" name="parameter2_value_<?php echo $cnt; ?>" value="<?= $parameter['user_val'][0]["value2"]; ?>"></td>
                                                            <?php } ?>
                                                            <td><?php
                                                                echo $parameter['parameter_range'];
                                                                if (empty($parameter['parameter_range'])) {
                                                                    echo "-";
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                echo $parameter['parameter_unit'];
                                                                if (empty($parameter['parameter_unit'])) {
                                                                    echo "-";
                                                                }
                                                                ?></td>
                                                            <td>
                                                                <?php
                                                                if (empty($parameter["para_ref_rng"][0]["ref_range"])) {
                                                                    foreach ($parameter["para_ref_status"] as $kky) {
                                                                        ?>
                                                                        <?php
                                                                        if ($parameter['user_val'][0]["value"] == $kky["id"]) {
                                                                            echo $kky["parameter_name"];
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo $parameter["para_ref_rng"][0]["ref_range"];
                                                                }
                                                                ?>
                                                            </td>
                                                        <?php } ?>
               
                                                    <tr>

                                                        <?php
                                                    }
                                                    $para_id_vals = json_encode($para_array);
                                                }  $cnt++;
                                            }
                                            ?>
                                        <div style="display:none;"  id='parameter_ids_<?= $cnnt ?>'><?= $para_id_vals ?></div>
                                        <?php ?>
                                        </tbody>
                                    </table>
                                    <div class="form-group form-group col-sm-12 no-padding">
                                        <?php
                                        if (!empty($testidp['graph'])) {
                                            ?><input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>"><?php
                                            foreach ($testidp['graph'] as $g_key) {
                                                ?>
                                                <div class="col-sm-1 col-xs-3 padding-left-0 repeat-img">
                                                    <div id="graph_dv_<?= $g_key['id'] ?>" style="float:left;">
                                                        <img src="<?= base_url(); ?>upload/report/graph/<?= $g_key['pic'] ?>" class="margin">
                                                        <a onclick="delete_graph('<?= $g_key['id'] ?>');" href="javascript:void(0);" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php if ($testidp["report_type"] == 2) { ?>
                                        <div class="form-group col-sm-12">
                                            <textarea class="ckeditor" name="culture_design_<?php echo $testidp["test_fk"]; ?>" id="culture_design_<?php echo $testidp["test_fk"]; ?>"><?= $parameter["user_culture_val"][0]["result"] ?></textarea>
                                            <input type="hidden" name="test_id_<?php echo $cnt; ?>" value="<?php echo $testidp["test_fk"]; ?>">
                                        </div>
                                    <?php } ?>
                                    <?php /*  <div class="form-group form-group col-sm-12">
                                      <label for="exampleInputFile">Upload Graph</label>
                                      <input type="file" name="graph_<?php echo $testidp["test_fk"]; ?>[]" multiple=""/>
                                      <p class="help-block"><small>(Only .gif,.jpg,.png are allowed)</small></p>
                                      <input type="hidden" id="old_graph_<?= $testidp['graph_id'] ?>" name="current_graph_<?php echo $testidp["test_fk"]; ?>" value="<?= $testidp['graph'] ?>"/>
                                      </div> */ ?>
                                    <?php
                                    $cnt++;
                                    $cnnt++;
                                }
                                ?>
                                <input type="hidden" name="count" value="<?php echo $cnt; ?>">
                            </div>
                        
                        <div class="box-footer">
                            <span id="loader_div1" style="display:none;"><img src="http://www.airmedlabs.com/upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px;float:right;"></span>
   
                            <input style="float:right;" id="change1" class="btn btn-primary" value="Approve" type="button" onclick="add_results();">
   
  
                        </div>
                        
						</div>
						
                    </div>
               </div>
	  </section>
    <?= form_close(); ?>
	<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script type="text/javascript">
        function check_hundred() {
            var $j_object = $(".hundread1");
            $sum = 0;
            $j_object.each(function (i) {
                if ($j_object[i].value != '') {
                    $sum += parseFloat($j_object[i].value);
                }
            });
            if ($sum == 0 || $sum == 100) {
                return true;
            } else {
               
				$("#overlay").html("<p>ERROR : CBC parameter value error. Please enter parameter correctly.</p>");

					$("#overlay").popup("open");
                return false;
            }
        }
        
        function delete_graph(pid) {
            var cnfrm = confirm("Are you sure?");
            if (pid != '' && cnfrm == true) {
                var url = "<?= base_url(); ?>Add_result/delete_graph/" + pid + "/<?= $cid ?>";
                $.ajax({
                    url: url,
                    success: function (data)
                    {
                        $('#graph_dv_' + pid).html("");
                        $("#old_graph_" + pid).val("");
                        alert("Graph successfully deleted.");
                    }
                });
            }
        }
        
       
       
        
        function add_results() {
			
			
            var chk = check_hundred();
			
             if (chk == true) {
				 
                var frm_data = document.getElementById("add_result");
                $.ajax({
                    url: frm_data.action,
                    type: 'post',
                    data: $("#add_result").serialize(),
                    beforeSend: function () {
                        $("#change1").attr("disabled", "disabled");
                        $("#loader_div1").attr("style", "");
                    },
                    success: function (data) {
						
						$("#successapprove").popup("open");
			
                        
                    },
                    error: function (jqXhr) {

                    },
                    complete: function () {
                        $("#change1").removeAttr("disabled");
                        $("#loader_div1").attr("style", "display:none;");
                        
                    }
                });
            }
        }
        
       
        /*Nishit code start*/
        var _timer = 0;

        function DelayedCallMe(num, ids, paraid) {
            if (_timer)
                window.clearTimeout(_timer);
            _timer = window.setTimeout(function () {
                if (document.getElementById("is_formula_" + ids).checked == true) {
                    calculate_result_status(ids, paraid);
                    calculate_result(ids);
                }
            }, 2000);
        }
        function calculate_result_status(ids, paraid) {
            var p_start = $("#p_start_" + ids + "_" + paraid).val();
            var p_end = $("#p_end_" + ids + "_" + paraid).val();
            var p_val = $("#" + ids + "_" + paraid).val();
            p_start = parseFloat(p_start);
            p_end = parseFloat(p_end);
            p_val = parseFloat(p_val);
            setTimeout(function () {
                if (p_val != 0 && p_val != '' && p_start != '' && p_end != '') {
                    if (p_val >= p_start && p_val <= p_end) {
                        $("#" + ids + "_" + paraid).attr("style", "");
                    } else if (p_val < p_start) {
                        $("#" + ids + "_" + paraid).attr("style", "background-color:yellow;");
                    } else if (p_val > p_end) {
                        $("#" + ids + "_" + paraid).attr("style", "background-color:red;color:white;");
                    }

                } else {
                    $("#" + ids + "_" + paraid).attr("style", "");
                }
            }, 1000);
        }
        function calculate_result(ids) {
            var get_parameter_ids = $("#parameter_ids_" + ids).html();
            var id_ary = [];
            var jsondata = JSON.parse(get_parameter_ids);

            for (var j = 0; j < jsondata.length; j++) {
                var parameter_val = $("#" + ids + "_" + jsondata[j]).val();
                console.log($("#" + ids + "_" + jsondata[j]).attr("formula"));

                if ($("#" + ids + "_" + jsondata[j]).attr("formula") == "true") {
                    parameter_val = "";
                }

                id_ary.push(jsondata[j] + "%&%" + parameter_val);
            }
            var url = "<?php echo base_url(); ?>add_result/formula_calculation";
            if (jsondata.length > 1) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"para_id": id_ary, result_num: ids}, // serializes the form's elements.
                    success: function (data)
                    {
                        var json_data = JSON.parse(data);
                        if (json_data.status == 1) {
                            for (var i = 0; i < json_data.data.length; i++) {
                                $("#" + json_data.data[i].val + "_" + json_data.data[i].pid).val(json_data.data[i].res);
                                calculate_result_status(json_data.data[i].val, json_data.data[i].pid);
                            }
                        }
                        if (json_data.status == 0) {
                            for (var i = 0; i < json_data.data.length; i++) {
                                $("#" + json_data.num + "_" + json_data.data[i].id).val(json_data.data[i].val);
                                calculate_result_status(json_data.num, json_data.data[i].id);
                            }

                        }
                    }
                });
            }
        }
<?php foreach ($last_array as $key) { ?>
            calculate_result_status('<?= $key["cnt"] ?>', '<?= $key["pid"] ?>');
<?php } ?>
        /*Nishit code end*/
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red'
        });
    </script>	

</body>
</html>