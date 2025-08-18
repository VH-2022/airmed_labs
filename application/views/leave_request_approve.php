
<?php
//echo $_SERVER['HTTP_REFERER']; exit;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Employee Leave </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="description" content="">
        <meta name="keywords" content="coco bootstrap template, coco admin, bootstrap,admin template, bootstrap admin,">
        <meta name="author" content="Huban Creative">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>user_assets/images/fav_icon.ico" />

        <link href="http://airmedpathlabs.info/sales/assets/datetime/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="http://airmedpathlabs.info/sales/assets/datetime/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

        <link href="http://airmedpathlabs.info/sales/assets/css/style.css" rel="stylesheet" type="text/css" />
        <!-- Extra CSS Libraries End -->


        <style>
            .login-wrap {
                margin: 20px 0%;
                text-align: left;
                background: rgba(0,0,0,0.1);
                padding: 20px 20px;
                color: #fff;
            }
        </style>
    </head>
    <body class="fixed-left login-page">
        <!-- Modal Start -->
        <!-- Modal Task Progress -->	


        <!-- Modal Logout -->
        <!-- Modal End -->
        <!-- Begin page -->
        <div class="container">
            <div class="full-content-center">
                    <p class="text-center"><a href="#" style="color:white;font-size: 18px;"><!--<img src="http://airmedpathlabs.info/sales/assets/img/logo.png" alt="Logo">--> Employee Leave</a></p>
                <div class="login-wrap animated flipInX">
                    <div class="login-block">
                        
                        <form action="<?php echo base_url() ?>leave_applications/leave_request_approve/<?php echo $cid ?>"  method="post" role="form">
                            <div class="widget">
                                <div class="widget-header">
                                    <h2><strong> Leave</strong></h2>
                                </div>
                                <div class="widget-content padding">
                                    <div class="widget">
                                        <?php
                                        //echo "<pre>"; print_r($success); exit;
                                        if (isset($success) != NULL) {
                                            ?>
                                            <div class="alert alert-success alert-dismissable">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                <?php echo $success[0]; ?>
                                            </div>
                                        <?php } ?>
                                        <div class="alert alert-success" id="profile_msg_suc" style="display: none;">
                                            <span id="msg_success"></span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="leave_id"  name="leave_id"  value="<?php echo $row[0]['id']; ?>" >
                                    <div class="form-group">
                                        <label for="input-text" class="control-label">Employee </label>
                                        <div class="">
                                            <input type="text" id="user_name"  name="user_name" class="form-control fieldset"  value="<?php echo $row[0]['user_name']; ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-text" class="control-label">Start Date <span style="color:red;">*</span></label>
                                        <div class="">
                                            <input type="text" id="startdate"  name="start_date" class="form-control datepicker fieldset"  value="<?php echo $row[0]['start_date']; ?>" >
                                            <span style="color: red;"><?= form_error('start_date'); ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="input-text" id="datetimepicker7" class="control-label ">End date <span style="color:red;">*</span></label>
                                        <div class="">
                                            <input type="text"  name="end_date" id="enddate" value="<?php echo $row[0]['end_date']; ?>" class="form-control datepicker fieldset">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="input-text" class="control-label fieldset">Remark</label>
                                        <textarea name="remark" disabled class="form-control"><?php echo $row[0]['remark']; ?></textarea>
                                        <span style="color: red;"><?= form_error('remark'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="input-text" class="control-label">Admin Remark</label>
                                        <div class="">
                                            <textarea name="admin_remark" class="form-control fieldset" placeholder="Enter Remark"></textarea>
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="input-text" class="control-label">Status</label>
                                        <div class="">
                                            <select name="leave_status" class="form-control fieldset1">
                                                <option value="">Select status</option>
                                                <option value="1" <?php
                                                if ($row[0]['leave_status'] == '1') {
                                                    echo "selected";
                                                }
                                                ?>>Approve</option>
                                                <option value="2" <?php
                                                if ($row[0]['leave_status'] == '2') {
                                                    echo "selected";
                                                }
                                                ?>>Disapprove</option>
                                                <option value="0" <?php
                                                if ($row[0]['leave_status'] == '0') {
                                                    echo "selected";
                                                }
                                                ?>>Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <input type="submit" class="btn btn-success btn-block fieldset1" value="Submit">
                                    </div>

                                </div>
                            </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- the overlay modal element -->
        <div class="md-overlay"></div>
        <!-- End of eoverlay modal -->

        <script src="http://airmedpathlabs.info/sales/assets/libs/jquery/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="http://airmedpathlabs.info/sales/assets/datetime/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="http://airmedpathlabs.info/sales/assets/datetime/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
        <script type="text/javascript">
            $(function () {
                $('#startdate').datetimepicker({
                    format: 'yyyy-mm-dd hh:ii'
                });
                $('#enddate').datetimepicker({
                    format: 'yyyy-mm-dd hh:ii'
                });
            });

<?php if ($row[0]['leave_status'] == "1" || $row[0]['leave_status'] == "2") { ?>
                $('.fieldset').prop('readonly', true);
                $('.fieldset1').prop('disabled', true);
<?php } ?>
        </script>
    </body>
</html>