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


</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<style>
    .box.box-solid{border: 1px solid #ccc;}
    .box-header.with-border{border-bottom: 1px solid #ccc;}
</style>
<section class="content-header">
    <h1>
       Handover Item Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>inventory/Handover_item"><i class="fa fa-users"></i>Handover Item List</a></li>
        <li class="active">Add Handover Item</li>
    </ol>
</section>
 
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">

             <!--    <p class="help-block" style="color:red;"><?php
                    if (isset($error)) {
                        echo $error;
                    }
                    ?></p> -->
                <?php if ($this->session->flashdata('duplicate')) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $this->session->flashdata('duplicate'); ?>
                    </div>
                <?php } ?>
                <div class="box-body">
                   
                    <div class="col-md-6">
                        <!-- form start -->
                        <form role="form" action="<?php echo base_url(); ?>inventory/Handover_item/add" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="stock_id" value="" id="stcok">

                            <div class="form-group">
                                <label for="name">Branch Name</label><span style="color:red">*</span>
                                <select name="branch_fk" class="chosen chosen-select" data-placeholder="choose a language..." onchange="reagent();" id="branch_id">
                                     <option value="">--Select--</option>
                               <?php foreach($branch_list as $key=>$val){ ?>
                                <option value="<?php echo $val['id'];?>"><?php echo $val['branch_name'];?></option>
                               <?php } ?>
                                </select>
                                <span style="color:red"><?php echo form_error('branch_fk'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Item Name</label><span style="color:red">*</span>
                                <select name="item_fk" class="chosen chosen-select" data-placeholder="choose a language..." id="item_id" onchange="Batch();">
                                     <option value="">--Select--</option>
                               
                                </select>
                                 <span style="color:red"><?php echo form_error('item_fk'); ?></span>
                            </div>
                           <div class="form-group">
                                <label for="name">Batch</label><span style="color:red">*</span>
                                <select name="batch_fk" class="chosen chosen-select" data-placeholder="choose a language..." id="batch_id" onchange="sub_data()">
                                     <option value="">--Select--</option>
                               
                                </select>
                                 <span style="color:red"><?php echo form_error('batch_fk'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Quantity</label>
                                <input type="text" name="quantity" class="form-control" placeholder="Enter Quantity" value="<?php echo set_value('quantity');?>">
                                
                              <span style="color:red"><?php echo form_error('quantity'); ?>
                                  <?php
                    if (isset($error)) {
                        echo $error[0];
                    }
                    ?>
                              </span>

                            </div>
                           <div class="form-group">
                                <label for="name">Handover </label><span style="color:red">*</span>
                                <select name="handover_to" class="chosen chosen-select" data-placeholder="choose a language..." id="handover_to">
                                     <option value="">--Select--</option>
                                     <?php  foreach($user_list as $user){ 
                                           if(!in_array($user['id'],$use)){
                                         ?>
                                      <option value="<?php echo $user['id'];?>"><?php echo $user['name'];?></option>
                                           <?php  }
                                           $use[] = $user['id'];
                                           
                                           } ?>
                                </select>
                                 <span style="color:red"></span>
                            </div>
                            <div class="form-group">
                                <label for="name">Remark</label>
                                <textarea name="remark" class="form-control"></textarea>
 <span style="color:red"><?php echo form_error('sub'); ?></span>
                            </div>
                          
                            <button class="btn btn-primary" type="submit">Add</button>

                        </form>
                    </div><!-- /.box -->
                </div>	
            </div>
        </div>
    </div>
</section>
</div>
</div>
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
    function reagent(){ 
        var id = $('#branch_id').val();
        $.ajax({
            url:"<?php echo base_url();?>inventory/Handover_item/getreagent",
            type:"POST",
            data:{'id':id},
            success:function(data){
                if(data !=''){
                $('#item_id').html(data);
                    $nc('.chosen').trigger("chosen:updated");
                }else{
                    $('#item_id').html('');
                     $('#batch_id').html('');
                       $nc('.chosen').trigger("chosen:updated");
                }
            }
        });
    }
    function Batch(){ 
        var reg_id = $('#item_id').val();
        
        $.ajax({
            url:"<?php echo base_url();?>inventory/Handover_item/getBatch",
            type:"POST",
            data:{'id':reg_id},
            success:function(data){ 
               if(data !=''){
                $('#batch_id').html(data);
               $nc('.chosen').trigger("chosen:updated");
            }else{

            }
            }
        });

    }
    function sub_data(){
       var reg_id = $('#batch_id').val();
       $('#stcok').val(reg_id);
    }
</script>