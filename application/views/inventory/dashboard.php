<style type="text/css">
  .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }
/* pending_job_detail responsive table */
    .pending_job_list_tbl {width: 100%; float: left;}
    .pending_job_list_tbl table {width: 100%; border-collapse: collapse; float: left;}
    .pending_job_list_tbl th {background-color: #e5e5e5; color: #3e3e3e; font-size: 16px; font-weight: 600; text-align: center; vertical-align: middle; border: 1px solid #b1b1b1;}
    .pending_job_list_tbl td, th {padding:2px 6px; border: 1px solid #ccc; text-align: left;}
    .pending_job_list_tbl td {padding: 4px 4px; font-size: 13px; color: #505050;} 
    @media (max-width: 980px) {
        .pending_job_list_tbl table, .pending_job_list_tbl thead, .pending_job_list_tbl tbody, .pending_job_list_tbl th, .pending_job_list_tbl td, .pending_job_list_tbl tr {display: block;}
        .pending_job_list_tbl thead tr {position: absolute; top: -9999px; left: -9999px;}
        .pending_job_list_tbl tr {border: 1px solid #ccc !important;}
        .pending_job_list_tbl td {border: none; border-bottom: 1px solid #eee; position: relative; padding-left: 60%; text-align: left;}
        .pending_job_list_tbl td:before {position: absolute; top: 6px; left: 6px; width: 45%; padding-right: 10px; white-space: nowrap;}
        .pending_job_list_tbl tr{margin-bottom:15px;}
        .table-responsive.pending_job_list_tbl{border:none !important;}

        .pending_job_list_tbl td:nth-of-type(1):before {content: "No";}
        .pending_job_list_tbl td:nth-of-type(2):before {content: "Reg No";}
        .pending_job_list_tbl td:nth-of-type(3):before {content: "Order Id";}
        .pending_job_list_tbl td:nth-of-type(4):before {content: "Customer Name";}
        .pending_job_list_tbl td:nth-of-type(5):before {content: "Doctor";}
        .pending_job_list_tbl td:nth-of-type(6):before {content: "Test/Package Name";}
        .pending_job_list_tbl td:nth-of-type(7):before {content: "Date";}
        .pending_job_list_tbl td:nth-of-type(8):before {content: "Payable Amount / Price";}
        .pending_job_list_tbl td:nth-of-type(9):before {content: "Job Status";}
        .pending_job_list_tbl td:nth-of-type(10):before {content: "Action";}
    }
    /* End pending_job_detail responsive table */	
</style>
<section class="content-header">
    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content">
    <form action="<?php echo base_url();?>inventory/Dashboard" method="get">
    <div class="row">
         <div class="col-md-2" style="margin-left: 2px;margin-bottom:20px;">
        <select name="branch" class="form-control" id="branch_id" onchange="branchlist(this);">
            <?php 
            foreach ($branch_list as $val) {
                ?>
                <option value="<?php echo $val['id']; ?>" <?php
                if ($branch == $val['id']) {
                    echo "selected='selected'";
                }
                ?>><?php echo $val['branch_name']; ?></option>
<?php } ?> 
        </select>
    </div>
        <div class="col-md-12">
            <div class="row">
               <div id="branch_wise_id">
                   <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reagent In Branch &nbsp;&nbsp;<span id="bid"></span></h3>&nbsp;&nbsp;&nbsp;
                      <input type="button" name="print_branch" class="btn btn-success" value="Print" onclick="printDiv();" />
                    </div>
                    <div class="box-body">
                        <div class="chart table-responsive pending_job_list_tbl">
                            <table id="sub_total">
                               
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
          </div>
          <div id="test_branch_id">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Test Performed Branch &nbsp;&nbsp;<span id="tid"></span></h3><br>
                       <div class="btn-group" style="margin-top: 10px;">
                              
                      
                  
            <input type="text" style="width: 30%;float: left;margin-right: 9px;" id="start_date" placeholder="Start Date" class="form-control datepicker-input" name="start_date" value="<?php
            if(isset($start_date)){
                echo $start_date;
            }else{
                echo date('d/m/Y');
            }
         ?>"/>    
                            
            <input type="text" id="sub_end" name="end_date" class="form-control datepicker-input"  style="width: 30%;float: left;margin-right: 9px;"  value="<?php
            if(isset($end_date)){
                echo $end_date;
            }else{
                echo date('d/m/Y');
            }
         ?>"/>
         
                 <input type="button" name="search" class="btn btn-success" value="Search" onclick="searchData();" style="margin-right: 10px;" /> 
                 <input type="button" name="print_sub" class="btn btn-success" value="Print" onclick="BranchDiv();" />          
                            </div>
                    </div>

                      
                    <div class="box-body">
                        <div class="chart table-responsive pending_job_list_tbl">

                            <table id="columnchart_material">
                            </table>
                        </div>
						
						
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
         </div>
        </div>

        <div class="row">
          <div id="expiry_id">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Expiry Consumables/ Stationary for Next 15 Days &nbsp;&nbsp;<span id="lid"></span></h3><br>
                         <input type="button" name="print_expiry" class="btn btn-success" value="Print" onclick="ExpiryDiv();" />
                    </div>
                    <div class="box-body">
                        <div class="chart table-responsive pending_job_list_tbl">
                            <table id="lab_stationary">
                            
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
          </div>
            <div id="purchase_id">
            <div class="col-md-6">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Purchase Order &nbsp;&nbsp;<span id="pid"></span></h3><br>
                      
                            <div class="btn-group" style="margin-top: 10px;">
                                <input type="text" style="width: 30%;float: left;margin-right: 9px;" id="new_date" placeholder="Start Date" class="form-control datepicker-input" name="start_date" value="<?php
            if(isset($start_date)){
                echo $start_date;
            }else{
                echo date('d/m/Y');
            }
         ?>"/>
            <input type="text" name="end_date" class="form-control datepicker-input" id="sub_end_date" style="width: 30%;float: left;margin-right: 9px;"  value="<?php
            if(isset($end_date)){
                echo $end_date;
            }else{
                echo date('d/m/Y');
            }
         ?>"/>
                             <input type="button" name="search" class="btn btn-success" value="Search" style="margin-right: 10px;" onclick="poData();" />  
                               <input type="button" name="print_po" class="btn btn-success" value="Print" onclick="PurchaseDiv();" />
                            </div>
                   
                    </div>
                    <div class="box-body">
                        <div class="chart table-responsive pending_job_list_tbl">
                            <table id="po_id">
                         
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
          </div>
        </div>
        </div><!-- /.col (RIGHT) -->
    </div><!-- /.row -->
</form>

</section><!-- /.content -->
<section class="content">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
         <!--    <img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="" style="  " alt="User Image" /> -->
        </div>
    </div>
</section>
</div></div>
  

<script type="text/javascript">
    function branchlist(){
          var id = document.getElementById('branch_id');
         var sub = id.options[id.selectedIndex].value;
          var selectedText = id.options[id.selectedIndex].text;
         //Start Code reagent list of Branch Wise

         //Start Code reagent list of Branch Wise
          $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/total_reagent",
            type: "get",
             beforeSend: function(){
                $('#loader_div').show();
            },
            data: {'sub_id': sub},
            success: function (data) {
                    $('#bid').html(selectedText);
                if (data != '') {
                    $("#sub_total").html(data);
                } else {
                    $("#sub_total").html("Record Not Found");
                }
            },
             complete: function(){
        $('#loader_div').hide();
    },
        });

         //End Code reagent list of Branch Wise
          //Start Branch wise total test
          $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/total_test",
            type: "get",
            data: {'sub_id': sub},
            success: function (data) {
                    $('#tid').html(selectedText);
                if (data != '') {
                    $("#columnchart_material").html(data);
                } else {
                    $("#columnchart_material").html("Record Not Found");
                }
            }
        });
           //End Branch wise total test
           //Start Lab consume and stationary Next 15 Days Expire date
           $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/total_expire",
            type: "get",
            data: {'sub_id': sub},
            success: function (data) {
                    $('#lid').html(selectedText);
                if (data != '') {
                    $("#lab_stationary").html(data);
                } else {
                    $("#lab_stationary").html("Record Not Found");
                }
            }
        });
             //End Lab consume and stationary Next 15 Days Expire date
              $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/getpodays",
            type: "get",
            data: {'sub_id': sub},
            success: function (data) {
                    $('#pid').html(selectedText);
                if (data != '') {
                    $("#po_id").html(data);
                } else {
                    $("#po_id").html("Record Not Found");
                }
            }
        });
    }
    branchlist();
    function searchData(){
         var id = document.getElementById('branch_id');
        var sub = id.options[id.selectedIndex].value;
          var selectedText = id.options[id.selectedIndex].text;
        var start = $('#start_date').val();
         var end = $('#sub_end').val();
           $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/total_test",
            type: "get",
            beforeSend: function(){
                $('#loader_div').show();
            },
            data: {'sub_id': sub,'start_date':start,'end_date':end},
            success: function (data) {
                   $('#tid').html(selectedText);
                if (data != '') {
                    $("#columnchart_material").html(data);
                } else {
                    $("#columnchart_material").html("Record Not Found");
                }
            },
            complete:function(){
               $('#loader_div').hide();
            }
        });
    }
    function poData(){
         var id = document.getElementById('branch_id');
        var sub = id.options[id.selectedIndex].value;
          var selectedText = id.options[id.selectedIndex].text;
        var start = $('#new_date').val();
         var end = $('#sub_end_date').val();
           $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/getpodays",
            type: "get",
              beforeSend: function(){
                $('#loader_div').show();
            },
            data: {'sub_id': sub,'start_date':start,'end_date':end},
            success: function (data) {
              $('#pid').html(selectedText);
                if (data != '') {
                    $("#po_id").html(data);
                } else {
                    $("#po_id").html("Record Not Found");
                }
            },
            complete:function(){
               $('#loader_div').hide();
            }
        });
    }

</script>
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById('branch_wise_id').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
function BranchDiv(divName) {
    var printContents = document.getElementById('test_branch_id').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
function ExpiryDiv(divName) {
    var printContents = document.getElementById('expiry_id').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
function PurchaseDiv(divName) {
    var printContents = document.getElementById('purchase_id').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>