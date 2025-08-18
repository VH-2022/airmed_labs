<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
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
    .multiselect{width:100%;text-align: left;}
    .multiselect-container.dropdown-menu {
        max-height: 250px;
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
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
    .multiselect-native-select .btn-group{width:100%; float:left;    margin-bottom: 15px;}
    #field_wrapper{width:100%; display: inline-block;}
    #field_wrapper .copy_id{width:100%; display: inline-block;}
    .padng-left{
        padding-left: 0;
    }
    .label_font{
        font-size: 13px;
    }
</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Nabl Test Scope Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Nabltestscope_master/nabltestscope_list"><i class="fa fa-users"></i>Nabl Test Scope List</a></li>
        <li class="active">Add Nabl Test Scope</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
			
                <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p>
				<div class="box-body">
				
				<div class="col-md-6">
                <!-- form start -->
                <form role="form" action="<?php echo base_url(); ?>Nabltestscope_master/nabltestscope_add" method="post" enctype="multipart/form-data">	
					<div class="form-group">
						<label for="testcity">Test City</label><span style="color:red">*</span>
						<select class="form-control chosen-select" name="testcity">
                            <option value="">--Select--</option>
							<?php foreach ($testcity as $val) { ?>
								<option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
							<?php } ?>					                                                      
						</select>
						<span style="color: red;"><?= form_error('testcity'); ?></span>
					</div>
					<div class="form-group">
						<label for="branch">Branch</label><span style="color:red">*</span>
						<!--select class="multiselect-ui form-control" title="Select Branch" multiple="multiple" id="branch" name="branch[]"-->
						<select class="form-control chosen-select" id="branch" name="branch">
							<option value="">--Select--</option>
							<?php foreach ($branch as $val) { ?>
								<option value="<?php echo $val['id'];?>"><?php echo $val['branch_name'];?></option>
							<?php } ?>					                                                      
						</select>
						<span style="color: red;"><?= form_error('branch'); ?></span>
					</div>
					<div class="form-group">
						<label for="test">Test</label><span style="color:red">*</span>
						<select class="form-control multiselect-ui" title="Select Test" multiple="multiple" id="test" name="test[]">
                            <!--option value="">--Select--</option-->
							<?php foreach ($tests as $ts) { ?>
								<option value="<?= $ts["id"] ?>"><?= $ts["test_name"]; ?></option>
							<?php } ?>
						</select>
						<span style="color: red;"><?= form_error('test[]'); ?></span>
					</div>
					<div class="form-group">
						<label for="testscope">Test Scope</label><span style="color:red">*</span>
						<select class="form-control chosen-select" name="testscope">
                            <option value="">--Select--</option>
							<option value="1">IS NABL</option>
							<option value="2">IS NOT NABL</option>
							<option value="3">REFERRAL</option>
						</select>
						<span style="color: red;"><?= form_error('testscope'); ?></span>
					</div>
					<!--div class="form-group">
						<label for="pattern">Pattern</label><span style="color:red">*</span>
						<input type="text"  name="pattern" class="form-control">
						<?php echo form_error('pattern');?>
					</div>
					<div class="form-group">
						<label for="pattern_repeat">Pattern Repeat</label><span style="color:red">*</span>
						<input type="text"  name="pattern_repeat" class="form-control">
						<?php echo form_error('pattern_repeat');?>
					</div-->
		
						<button class="btn btn-primary" type="submit">Add</button>
				
				</form>
		</div><!-- /.box -->
		</div>	
    </div>
	</div>
	</div>
</section>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<Script>
    $(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
			enableCaseInsensitiveFiltering: true,
            nonSelectedText: 'Select Test'
        });
    });
    // $(function () {
    //     $('.chosen').chosen();
    // });
    // jQuery(".chosen-select").chosen({
    //     search_contains: true
    // });
</script>
<script type="text/javascript">
	window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);
</script>