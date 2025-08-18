<!-- Page Heading -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<!-- Vishal Add Css -->
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<!-- VIshal End Css -->

<style>
    .multiselect-native-select .btn-group{width:100%; float:left;    margin-bottom: 15px;}
    .multiselect-native-select button{width:100%; text-align:left;}
    .multiselect-native-select button span{width: 100%; display: inline-block;}
    .multiselect-native-select .btn-group .multiselect-container{width:100%;min-height: 100px; max-height: 250px;
                                                                 overflow-y: scroll;word-wrap: break-word;}
.padng-left{
            padding-left: 0;
        }
        .label_font{
            font-size: 13px;
        }
</style>
<section class="content-header">
    <h1>
        Machine Edit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list"><i class="fa fa-users"></i>Machine List</a></li>
        <li class="active">Machine Edit</li>
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
                        <form role="form" action="<?php echo base_url(); ?>inventory/machine/machine_edit/<?= $eid; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Name</label><span style="color:red">*</span>
                                <input type="text"  name="name" value="<?php echo $query[0]['name']; ?>" class="form-control">
                                <?php echo form_error('name'); ?>
                            </div>
                            <input type="hidden" name="id" value="<?= $eid; ?>"/>
                            <div class="form-group">
                                <label for="name">Branch</label>
                                <select class="multiselect-ui form-control" id="branch" title="Select Branch" multiple="multiple" name="branch[]">
                                    <?php foreach ($branchlist as $bkey) { ?>
                                        <option value="<?= $bkey["id"] ?>" <?php
                                        if (in_array($bkey["id"], explode(',', $assign_branch[0]["branch"]))) {
                                            echo "selected";
                                        }
                                        ?>><?= $bkey["branch_name"] ?></option>
                                            <?php } ?>
                                </select>
                                <?php echo form_error('branch[]'); ?>
                            </div>

                            <div id="field_wrapper">


                                <?php
                                foreach ($item as $val) { 
                                    ?>

                                    <div class="copy_id">
                                        <div class="col-md-11 padng-left">  
                                            <div class="col-md-2 padng-left">
                                                <div class="form-group">
                                                    <label for="name" class="label_font">Reagent Name</label> 
                                                    <input type="text" name="reagent_name[]"  class="form-control" placeholder="Reagent Name" value="<?php echo $val['InventoryName']; ?>"/>

                                                </div>
                                                <?php echo form_error('reagent_name[]'); ?>
                                            </div>
                                             <div class="col-md-1 padng-left">
                                    <div class="form-group">
                                          <label for="name" class="label_font">Pack Size</label> 
                                    <input type="text" name="per_pack[]" class="form-control" placeholder="Pack Size" value="<?php echo $val['per_pack']; ?>">
                                </div>
                                </div>
                                         
                                            <div class="col-md-2 padng-left">
                                                <div class="form-group">
                                                    <label for="name" class="label_font">Unit</label> 
                                                    <select name="unit_fk[]" class="chosen-select form-control" data-placeholder="choose a language..." id="unit_id">
                                                        <option value="">Select Unit</option>
                                                        <?php foreach ($unit_list as $cat) { ?>
                                                            <option value="<?php echo $cat['id']; ?>" <?php
                                                            if ($cat['id'] == $val["unit_fk"]) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo ucwords($cat['name']); ?></option>
                                                                <?php } ?>
                                                    </select>
                                                    <?php echo form_error('unit_fk[]'); ?>
                                                </div>
                                            </div>
                                            <input type="hidden" name="old_item[]" value="<?=$val["Inventory"]?>"/>
                                            <div class="col-md-1 padng-left">
                                                <div class="form-group">
                                                    <label for="name" class="label_font">Test Quantity</label> 
                                                    <input type="text" name="test_quantity[]" value="<?php echo $val['test_quantity']; ?>" class="form-control" placeholder="Test Quantity"/>
                                                    <?php echo form_error('quantity[]'); ?>
                                                </div>
                                            </div>
                                              <div class="col-md-1">
                                    <div class="form-group">
                                          <label for="name" class="label_font">Price</label> 
                                    <input type="text" name="box_price[]" class="form-control" placeholder="Enter Box Price" value="<?php echo $val['box_price']; ?>">
                                </div>
                                </div>
                                 <div class="col-md-1 padng-left">
                                        <div class="form-group">
                                          <label for="name" class="label_font">Brand</label> 
                                        <select name="brand_fk[]" class="chosen-select form-control" data-placeholder="choose a language..." id="unit_id">
                                                        <option value="">Select Brand</option>
                                                        <?php foreach($brand_list as $val_new){  ?>
                                                              <option value="<?php echo $val_new['id']; ?>" <?php
                                                    if ($val['brand_fk'] == $val_new['id']) {
                                                        echo " selected";
                                                    }
                                                    ?> ><?php echo ucfirst($val_new['brand_name']); ?></option>
                                                      
                                                        <?php } ?>
                                                     </select>
                                                 </div>
                                        </div>
                            
                                <div class="col-md-1 padng-left">
                                    <div class="form-group">
                                          <label for="name" class="label_font">HSN Code</label> 
                                    <input type="text" name="hsn_code[]" class="form-control" placeholder="Enter HSN Code" value="<?php echo $val['hsn_code']; ?>">
                                </div>
                                </div>
                                    <div class="col-md-2 padng-left">
                                    <div class="form-group">
                                          <label for="name" class="label_font">Remark</label> 
                                    <input type="text" name="remark[]" class="form-control" placeholder="Enter Remark" value="<?php echo $val['remark']; ?>">
                                </div>
                                </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="name" style="margin-top:40px;"></label> 
                                            <button class="btn btn-primary remove_button" title="Remove field" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>

                                        </div>
                                    </div>
                                <?php } ?>

                            </div>   
                            <div class="col-md-1">
                                <label for="name" style="margin-top:40px;"></label> 
                                <button class="btn btn-primary add_button" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>

                            </div>
                            <div class="col-md-3 pdng_0">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </form>
                    </div><!-- /.box -->
                </div>  
            </div>
        </div>
</section>
</div>
</div>
<span id="drop_id" style="display:none;">
    <option value="">Select Unit</option>
    <?php foreach ($unit_list as $val) { ?>
        <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
    <?php } ?>

</span>
<span id="quantity_id" style="display:none;">
    <input type="text" name="test_quantity[]" class="form-control" placeholder="Test Quantity">                       
</span>

<span id="pack_id" style="display:none;">
    <input type="text" name="per_pack[]" class="form-control" placeholder="Enter Per pack">                       
</span>
  <span id="box_id" style="display: none;">
<input type="text" name="box_price[]" class="form-control" placeholder="Enter Box Price">
    </span>
    <span id="remark_id" style="display: none;">
<input type="text" name="remark[]" class="form-control" placeholder="Enter Remark">
    </span>
    <span id="brand_id" style="display: none;">
       
            <option value="">Select Brand</option>
            <?php foreach($brand_list as $val){?>
            <option value="<?php echo $val['id'];?>"><?php echo ucfirst($val['brand_name']);?></option>
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
    $(function () {
        $('.chosen').chosen();
    });
    jQuery(".chosen-select").chosen({
        search_contains: true
    });
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
            var pack_id = $('#pack_id').html();
              var box_price = $('#box_id').html();
     var brand = $('#brand_id').html();
      var remark = $('#remark_id').html();
       var hsn = $('#hsn_id').html();
          var fieldHTML = '<div class="copy_id"><div class="col-md-11 padng-left"><div class="col-md-2 padng-left"><div class="form-group"><input type="text" class="form-control" name="reagent_name[]" value="" placeholder="Reagent Name"/></div></div><div class="col-md-1 padng-left"><div class="form-group">'+pack_id+'</div></div><div class="col-md-1 padng-left"><select name="unit_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="unit_id_'+i+'">'+dropdown_id+'</select></div><div class="col-md-1 padng-left"><div class="form-group">'+test_quantity+'</div></div><div class="col-md-1 padng-left"><div class="form-group">'+box_price+'</div></div><div class="col-md-1 padng-left"><div class="form-group"><select name="brand_fk[]" class="chosen chosen-select form-control" data-placeholder="choose a language..." id="brand_id_'+i+'">'+brand+'</select></div></div><div class="col-md-2 padng-left"><div class="form-group">'+hsn+'</div></div><div class="col-md-2 padng-left"><div class="form-group">'+remark+'</div></div></div><div class="col-md-1"><button class="btn btn-primary remove_button" title="Remove field" type="button"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></div></div><hr>';

            $('#field_wrapper').append(fieldHTML);
              $('#unit_id_'+i).chosen(); 
              $('#brand_id_'+i).chosen();
            i++;

        });
        $("#field_wrapper").on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(this).parents('.copy_id').remove(); //Remove field html

        });

    });
</script>