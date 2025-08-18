<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>lib/jquery.mousewheel.pack.js?v=3.1.3"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
    var jq = $.noConflict();
    jq(document).ready(function () {

        jq(".fancybox-effects-a").fancybox({
            'type': 'iframe',
            helpers: {
                title: {
                    type: 'outside'
                },
                overlay: {
                    speedOut: 0
                }
            }
        });
    });
</script>
<style>
    .fancybox-skin{
        height: 600px;
    }
    .fancybox-inner{
        height: auto !important;
    }
    .fancybox-image, .fancybox-iframe {
        display: block;
        height: 600px;
        width: 100%;
    }
</style>
<style>
    tr.odd input.form-control {
        width: 100% !important;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Call
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li class="active">User Call</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">User Call List</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>
                            <?php if (isset($unsuccess) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $unsuccess['0']; ?>
                                </div>
                            <?php } ?>
                        </div>
						
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>user_call_master/index" method="get" enctype="multipart/form-data">
								<div class="row">
									<div class="col-sm-2">
										<select class="form-control" data-placeholder="Select Reason" tabindex="-1" id="reason1" name="reason1">
											<option value="">--Select Reason--</option>
										</select>								
									</div>
								</div>
								<a style="float:right;margin-left:5px"  href='#<?php echo base_url(); ?>user_call_master/export_csv/#' class="btn btn-success btn-sm" >Export</a>
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Caller Name</th>
                                            <th>Call From</th>
                                            <th>Call To</th>
                                            <th>Direction</th>
                                            <th>Start Time</th>
                                            <th>Duration</th>
                                            <th>Call Type</th>
                                            <th>Call Date & Time</th>
                                            <th>Agent</th>
                                            <th>Agent Number</th>
                                            <th>Call Recording</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span style="color:red;">*</span></td>
                                            <td><?php /* <select class="form-control chosen-select" tabindex="-1"  data-placeholder="Select Customer" name="caller">
                              <option value="">Select Customer</option>
                              <?php foreach ($customer_list as $cat) { ?>
                              <option value="<?php echo $cat['id']; ?>" <?php
                              if ($caller_fk == $cat['id']) {
                              echo "selected";
                              }
                              ?> ><?php echo ucwords($cat['full_name']); ?></option>
                              <?php } ?>
                              </select> */ ?>

                                                <input type="text" placeholder="Call From" class="form-control" name="caller" id="caller" value="<?php
                                                if (isset($caller_fk)) {
                                                    echo $caller_fk;
                                                }
                                                ?>"/>
                                            </td>
                                            <td><input type="text" placeholder="Call From" class="form-control" name="call_from" value="<?php
                                                if (isset($call_from)) {
                                                    echo $call_from;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" placeholder="Call To" class="form-control" name="call_to" value="<?php
                                                if (isset($call_to)) {
                                                    echo $call_to;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" placeholder="Direction" class="form-control" name="direction" value="<?php
                                                if (isset($direction)) {
                                                    echo $direction;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" name="start_date" placeholder="Select Date" class="form-control" id="date1" autocomplete="off" value="<?php
                                                if (isset($start_date)) {
                                                    echo $start_date;
                                                }
                                                ?>" /></td>
                                            <td><input type="text" placeholder="Duration" class="form-control" name="duration" value="<?php
                                                if (isset($duration)) {
                                                    echo $duration;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" placeholder="Call Type" class="form-control" name="call_type" value="<?php
                                                if (isset($call_type)) {
                                                    echo $call_type;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" name="call_date" autocomplete="off" placeholder="Select Date" class="form-control" id="date" value="<?php
                                                if (isset($call_date)) {
                                                    echo $call_date;
                                                }
                                                ?>" /></td>
                                            <td><input type="text" placeholder="Agent" class="form-control" name="agent" value="<?php
                                                if (isset($agent)) {
                                                    echo $agent;
                                                }
                                                ?>"/></td>
                                            <td><input type="text" placeholder="Agent Number" class="form-control" name="agent_number" value="<?php
                                                if (isset($agent_number)) {
                                                    echo $agent_number;
                                                }
                                                ?>"/></td>
                                            <td><input type="submit" name="search" class="btn btn-success" value="Search" /></td>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php
                                                    echo ucwords($row['customer'][0]['full_name']);
                                                    /* foreach ($customer_list as $customer) {
                                                      if (substr($row['CallFrom'], 1) == $customer['mobile']) {
                                                      echo ucwords($customer['full_name']);
                                                      break;
                                                      }
                                                      } */
                                                    ?></td>
                                                <td><?php echo $row['CallFrom']; ?></td>
                                                <td><?php echo $row['CallTo']; ?></td>
                                                <td><?php echo $row['Direction']; ?></td>
                                                <td><?php echo $row['StartTime']; ?></td>
                                                <td><?php echo gmdate("i:s", $row['DialCallDuration']); ?></td>
                                                <td><?php
                                                    if ($row['DialCallDuration'] != 0) {
                                                        echo $row['CallType'];
                                                    } else {
                                                        echo "Missed call";
                                                    }
                                                    ?></td>
                                                <td><?php echo $row['CurrentTime']; ?></td>
                                                <td><?php echo $row['AgentEmail']; ?></td>
                                                <td><?php echo $row['DialWhomNumber']; ?></td>
                                                <td><!--<?php if ($row['RecordingUrl'] != NULL) { ?><a class="branch_select" rel="group" href='<?php echo $row['RecordingUrl']; ?>' data-toggle="tooltip" data-original-title="Play"><?php } else { ?><a href="#" data-toggle="tooltip" data-original-title="Not Available"><?php } ?><i class="fa fa-play-circle-o"></i></a>-->
                                                    <?php if ($row['RecordingUrl'] != NULL) { ?>
                                                        <a class="fancybox-effects-a" data-toggle="tooltip" data-original-title="Play" href="<?php echo $row['RecordingUrl']; ?>"><?php } else { ?><a href="#" data-toggle="tooltip" data-original-title="Not Available"><?php } ?><i class="fa fa-play-circle-o"></i></a>
                                                        <a class="fancybox-effects-a" data-toggle="tooltip" data-original-title="Send Quote" href="<?= base_url(); ?>User_call_master/send_quotation/<?php echo $row['id']; ?>"><i class="fa fa-envelope-o"></i></a>
                                                        <a data-toggle="tooltip" data-original-title="Call Details" href="javascript:void(0)" onclick="call_details('<?php echo $row['id'] ?>')"><i class="fa fa-info"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }if (empty($query)) {
                                            ?>
                                            <tr>
                                                <td colspan="12">No records found</td>
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



<div class="modal fade" id="call_modal" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <?php echo form_open("User_call_master/update_details", array("method" => "post", "role" => "form")); ?>
            <input type="hidden" id="id" name="id" value=""/>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Call Details</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <textarea class="form-control" id="note" name="note" rows="3" placeholder="Call Note"></textarea>
                </div>

                <div class="form-group">
                    <select class="form-control" data-placeholder="Select Reason" tabindex="-1" id="reason" name="reason">
                        <option value="">--Select Reason--</option>
                    </select>
<!--                    <textarea class="form-control" id="reason" name="reason" rows="2" placeholder="Call Reason" ></textarea>-->
                </div>

                <br/>
                <div class="form-group">
                    <input type="submit" class="btn btn-sm btn-primary" value="Save"/>
                </div>
            </div>
            <!--div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div-->
            <?= form_close(); ?>
        </div>

    </div>
</div>


<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    function call_details(id) {
        $("#id").val(id);
        $.ajax({
            url: '<?php echo base_url(); ?>User_call_master/get_call_details',
            dataType: 'json',
            type: 'post',
            data: {id: id},
            success: function (data) {
                if (data != "") {
                    console.log(data);
                    $("#note").html(data.data[0].note);
//                  $("#reason").html(data[0].reason);
                    
                    var $el = $("#reason");
                    $el.empty();
                    $el.append($("<option></option>").attr("value", '').text('Select Reason'));
                    
                    var reason = '';
                    if (data.data[0]) {
                        reason = data.data[0]['reason'];
                    }
                    var select = '';
                    $.each(data.reason_data, function (index, data) {
                        if (data['id'] == reason) {
                            select = "selected";
                        } else {
                            select = "";
                        }
                        $('#reason').append('<option value="' + data['id'] + '"' + select + '>' + data['reason'] + '</option>');
                    });
                }
                $("#call_modal").modal('show');
            },
            error: function (jqXhr) {
            },
        });
    }

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');
</script>
<script type="text/javascript">
    $(function () {
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10,
            "searching": false
        });
    });
</script>
<script>
    /*	$( document ).ready(function() {
     $.ajax({
     url: "<?php echo base_url(); ?>user_call_master/call_details",
     error: function (jqXHR, error, code) {
     // alert("not show");
     },
     success: function (data) {
     if(data != '') {
     document.getElementById('content').innerHTML = "";
     document.getElementById('content').innerHTML = data;
     $("#myModal").modal('show');
     } else {
     $("#myModal").modal('hide');
     }
     }
     });
     }); */
    $fncybx = $(".branch_select").click(function () {
        $.fancybox.open({
            href: 'add_fancybox?branch=' + old_branch,
            type: 'iframe',
            padding: 5,
            'width': 700,
            'height': 600
        });
    });
</script>
<script>
    $(document).ready(function () {
        $("#caller").attr("style", "");
        var date_input = $('input[name="start_date"]'); //our date input has the name "date"
        var date_input1 = $('input[name="call_date"]'); //our date input has the name "date"

        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
        date_input1.datepicker({
            format: 'dd/mm/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
		
		$.ajax({
            url: '<?php echo base_url(); ?>User_call_master/get_call_reasons',
            dataType: 'json',
            type: 'get',
            success: function (data) {
                if (data != "") {
                    //console.log(data);
                    //$("#note").html(data.data[0].note);
					
					$("#reason1").html(data.reason_data);
                    
                    var $el = $("#reason1");
                    $el.empty();
                    $el.append($("<option></option>").attr("value", '').text('Select Reason'));
                    
                    var reason = '';
                    if (data.reason_data) {
                        reason = data.reason_data['reason'];
                    }
                    var select = '';
                    $.each(data.reason_data, function (index, data) {
                        if (data['id'] == reason) {
                            select = "selected";
                        } else {
                            select = "";
                        }
                        $('#reason1').append('<option value="' + data['id'] + '"' + select + '>' + data['reason'] + '</option>');
                    });
                }
                //$("#call_modal").modal('show');
            },
            error: function (jqXhr) {
            },
        });
		
		//$("#reason1").val(<?php echo $reason; ?>);
    });
    function close_frame() {
        jq.fancybox.close();
        window.location.reload();
    }
</script>