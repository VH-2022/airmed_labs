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
    .multiselect-native-select .btn-group.open{width:100%; float:left;}
    .multiselect-native-select .btn-group{width:100%; float:left;}
    span.multiselect-native-select {
        width: 100%;
        float: left;
        margin-left: 0;
    }


</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Send Notification SMS
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li>Send Notification SMS</li>

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                    </div><!-- /.box-header -->
                    <?php echo form_open("Send_sms/index", array("method" => "POST", "role" => "form", 'id' => "send_notification_id")); ?>
                    <div class="box-body">

                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- Search start-->
                        <div class="col-md-12"> 
                            <div class="col-md-5">

                                <div class="form-group">
                                    <label for="exampleInputFile" class="pull-left">Admin user :<span style="color:red;">*</span></label><br/>
                                    <select class="multiselect-ui form-control" id="branch" multiple="multiple" name="email[]">
                                        <?php                                         
                                        foreach ($admin as $branch1) {
                                            if (!empty($branch_list_select)) {
                                                if (in_array($branch1['id'], $branch_list_select)) {
                                                    ?>
                                        <option value="<?php echo $branch1['id']; ?>" <?php if(in_array($branch1['id'], set_value("email"))){ echo "selected"; } ?> ><?php echo ucwords($branch1['name']); ?></option>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                <option value="<?php echo $branch1['id']; ?>" <?php if(in_array($branch1['id'], set_value("email"))){ echo "selected"; } ?>><?php echo ucwords($branch1['name']); ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                    <span style="color:red;" id="phone_error"><?php echo form_error('email[]'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">SMS :<span style="color:red;">*</span></label>
                                    <textarea name="note" class="form-control" placeholder="Description"><?= set_value("note"); ?></textarea>
                                    <span style="color:red;" id="email_error"><?php echo form_error('note'); ?></span>
                                </div>

                                <!--Search end--> 	
                            </div><!-- /.box-body -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="box-footer">
                        <div class="col-md-6">
                            <input type="submit" name="search" class="btn btn-sm btn-primary" value="Send SMS" />
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
        search_contains: true
    });
    $(function () {
        $('.chosen').chosen();
    });
    //  $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
    // $("#cid").chosen('refresh');
    function printData()
    {
        var divToPrint = document.getElementById("prnt_rpt");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script> 

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<Script>
    $(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Select Admin'
        });
    });
</script>