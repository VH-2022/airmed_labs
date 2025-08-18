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
        Machine Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list"><i class="fa fa-users"></i>Machine List</a></li>
        <li class="active">Machine Item</li>
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
                <?php if ($this->session->flashdata('duplicate')) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $this->session->flashdata('duplicate'); ?>
                    </div>
                <?php } ?>
                <div class="box-body">
                    <div class="col-md-12">
                        <!-- form start -->
                        <form role="form" id="machinform" action="<?php echo base_url(); ?>inventory/machine/machine_add" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name" >Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" class="form-control">
                                <?php echo form_error('name'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Branch</label>
                                <select class="multiselect-ui form-control" id="branch" title="Select Branch" multiple="multiple" name="branch[]">
                                    <?php foreach ($branchlist as $bkey) { ?>
                                        <option value="<?= $bkey["id"] ?>"><?= $bkey["branch_name"] ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('branch[]'); ?>
                            </div>
                            <div id="field_wrapper">
                                <div class="copy_id">
                                    <div class="col-md-11 padng-left">
                                        <div class="col-md-2 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Reagent Name</label> 
                                                <input type="text" name="reagent_name[]" value="" class="form-control" placeholder="Reagent Name"/>
                                                <?php echo form_error('reagent_name[]'); ?>
                                            </div>    
                                        </div>
                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Pack Size</label> 
                                                <input type="text" name="per_pack[]" class="form-control" placeholder="Pack Size">
                                            </div>
                                        </div>
                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Unit</label> 
                                                <select name="unit_fk[]" class="chosen-select form-control" data-placeholder="choose a language..." id="unit_id">
                                                    <option value="">Select Unit</option>
                                                    <?php foreach ($unit_list as $val) { ?>
                                                        <option value="<?php echo $val['id']; ?>" <?php
                                                        if ($branch_id == $val['id']) {
                                                            echo " selected";
                                                        }
                                                        ?> ><?php echo $val['name']; ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Test Quantity</label> 
                                                <input type="text" name="test_quantity[]" class="form-control" placeholder="Test Quantity">
                                            </div>
                                        </div>

                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Price</label> 
                                                <input type="text" name="box_price[]" class="form-control calution" placeholder="Enter Box Price">
												
                                            </div>
                                        </div>
                                        <div class="col-md-2 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Brand</label> 
                                                <select name="brand_fk[]" class="chosen-select form-control" data-placeholder="choose a language..." id="unit_id">
                                                    <option value="">Select Brand</option>
                                                    <?php foreach ($brand_list as $val) { ?>
                                                        <option value="<?php echo $val['id']; ?>" <?php
                                                        if ($branch_id == $val['id']) {
                                                            echo " selected";
                                                        }
                                                        ?> ><?php echo ucfirst($val['brand_name']); ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">HSN Code</label> 
                                                <input type="text" name="hsn_code[]" class="form-control" placeholder="Enter HSN Code">
                                            </div>
                                        </div>
                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Remark</label> 
                                                <input type="text" name="remark[]" class="form-control" placeholder="Enter Remark">
                                            </div>
                                        </div>
                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Location</label> 
                                                <input type="text" name="location[]" class="form-control" placeholder="Enter Location">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1 padng-left">
                                            <div class="form-group">
                                                <label for="name" class="label_font">Type</label>
                                                <select name="type[]" class="chosen-select form-control" id="type">
                                                    <option value="1" selected>Pathology</option>
                                                    <option value="2">Radiology</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>  
                                    <div class="col-md-1">
                                        <label for="name" style="margin-top:40px;"></label> 
                                        <button class="btn btn-primary add_button" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 pdng_0">
                                <button class="btn btn-primary" type="submit">Add</button>
                            </div>

                        </form>
                    </div><!-- /.box -->
                </div>	
            </div>
        </div>
    </div>
</section>
</div>
</div>
<span id="drop_id" style="display:none;">


    <option value="">Select Unit</option>
    <?php foreach ($unit_list as $val) { ?>
        <option value="<?php echo $val['id']; ?>" <?php
        if ($branch_id == $val['id']) {
            echo " selected";
        }
        ?> ><?php echo $val['name']; ?></option>

    <?php } ?>

</span>
<span id="quantity_id" style="display:none;">
    <input type="text" name="test_quantity[]" class="form-control" placeholder="Test Quantity">                       
</span>
<span id="per_pack_id" style="display:none;">
    <input type="text" name="per_pack[]" class="form-control" placeholder="Pack Size">                       
</span>
<span id="box_id" style="display: none;">
    <input type="text" name="box_price[]" class="form-control calution" placeholder="Enter Box Price">
</span>
<span id="remark_id" style="display: none;">
    <input type="text" name="remark[]" class="form-control" placeholder="Enter Remark">
</span>

<span id="location_id" style="display: none;">
    <input type="text" name="location[]" class="form-control" placeholder="Enter Location">
</span>

<span id="type_id" style="display: none;">
    <option value="1" selected>Pathology</option>
    <option value="2">Radiology</option>
</span>

<span id="brand_id" style="display: none;">

    <option value="">Select Brand</option>
    <?php foreach ($brand_list as $val) { ?>
        <option value="<?php echo $val['id']; ?>"><?php echo ucfirst($val['brand_name']); ?></option>
    <?php } ?>

</span>
<span id="hsn_id" style="display: none;">
    <input type="text" name="hsn_code[]" class="form-control" placeholder="Enter HSN Code">
</span>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<Script>
    $(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            nonSelectedText: 'Select Branch'
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
    window.setTimeout(function () {
        $(".alert").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 4000);
</script>
<script type="text/javascript">

    $(document).ready(function () {



        $(".add_button").click(function () {
            var i = $('.copy_id').length;
            var dropdown_id = $('#drop_id').html();
            var test_quantity = $('#quantity_id').html();
            var per_pack = $('#per_pack_id').html();
            var box_price = $('#box_id').html();
            var brand = $('#brand_id').html();
            var remark = $('#remark_id').html();
            var hsn = $('#hsn_id').html();
            var location = $('#location_id').html();
            var type = $('#type_id').html();
            //var fieldHTML = '<div class="copy_id"><div class="col-md-11 padng-left"><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Reagent Name</label> <input type="text" class="form-control" name="reagent_name[]" value="" placeholder="Reagent Name"/></div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Pack Size</label> ' + per_pack + '</div></div><div class="col-md-2 padng-left"><label for="name" class="label_font">Unit</label><select name="unit_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="unit_id_' + i + '">' + dropdown_id + '</select></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Test Quantity</label>' + test_quantity + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Price</label>' + box_price + '</div></div><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Brand</label><select name="brand_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="brand_id_' + i + '">' + brand + '</select></div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">HSN Code</label>' + hsn + '</div></div><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Remark</label>' + remark + '</div></div></div><div class="col-md-1"><button class="btn btn-primary remove_button" title="Remove field" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></div></div>';
            //var fieldHTML = '<div class="copy_id"><div class="col-md-11 padng-left"><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Reagent Name</label> <input type="text" class="form-control" name="reagent_name[]" value="" placeholder="Reagent Name"/></div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Pack Size</label> ' + per_pack + '</div></div><div class="col-md-2 padng-left"><label for="name" class="label_font">Unit</label><select name="unit_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="unit_id_' + i + '">' + dropdown_id + '</select></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Test Quantity</label>' + test_quantity + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Price</label>' + box_price + '</div></div><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Brand</label><select name="brand_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="brand_id_' + i + '">' + brand + '</select></div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">HSN Code</label>' + hsn + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Remark</label>' + remark + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Location</label>' + location + '</div></div></div><div class="col-md-1" style="margin-top:20px;"><button class="btn btn-primary remove_button" title="Remove field" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></div></div></div>';
            var fieldHTML = '<div class="copy_id"><div class="col-md-11 padng-left"><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Reagent Name</label> <input type="text" class="form-control" name="reagent_name[]" value="" placeholder="Reagent Name"/></div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Pack Size</label> ' + per_pack + '</div></div><div class="col-md-1 padng-left"><label for="name" class="label_font">Unit</label><select name="unit_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="unit_id_' + i + '">' + dropdown_id + '</select></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Test Quantity</label>' + test_quantity + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Price</label>' + box_price + '</div></div><div class="col-md-2 padng-left"><div class="form-group"><label for="name" class="label_font">Brand</label><select name="brand_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="brand_id_' + i + '">' + brand + '</select></div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">HSN Code</label>' + hsn + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Remark</label>' + remark + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Location</label>' + location + '</div></div><div class="col-md-1 padng-left"><div class="form-group"><label for="name" class="label_font">Type</label><select name="type[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="type_' + i + '">' + type + '</select></div></div></div><div class="col-md-1" style="margin-top:20px;"><button class="btn btn-primary remove_button" title="Remove field" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></div></div></div>';
            
            $('#field_wrapper').append(fieldHTML);
            $('#unit_id_' + i).chosen();
            $('#brand_id_' + i).chosen();
            i++;

        });
        $("#field_wrapper").on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(this).parents('.copy_id').remove(); //Remove field html

        });

    });
$(document).on('keydown', '.calution', function(e) {		
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
$(document).on('keyup', '.calution', function(e) {
var val=parseInt($(this).val());
/* if(val > 0){ }else{ alert('in'); } */
if(val==0){ $(this).val(''); }
});	
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">
    $(document).ready(function () {
        $('.chosen-select').chosen();
		 $('#machinform').on('submit', function(e){
        e.preventDefault();
		$(".text-danger").remove();
		var error =[];
		$('form .calution').each(function() {
   if($(this).val() == ""){ $(this).after('<span class="text-danger">The Price field is required.</span>'); error.push(1); }
	
	
});
 if (error.length === 0) {
 this.submit();
} 
/* $('form input[name="box_price[]"]').each(function() {
    alert($(this).val());
}); */
		/* $("input.calution").after('hello'); */


        /* var len = $('#username').val().length;
        if (len < 6 && len > 1) {
            this.submit();
        } */
    });
		
    })

</script>   