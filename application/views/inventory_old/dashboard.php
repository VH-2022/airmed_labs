<section class="content-header">
    <h1>
        Dashboard             
    </h1>
    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
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
                   <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reagent In Branch &nbsp;&nbsp;<span id="bid"></span></h3><br>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <table id="sub_total" style="width: 610px;">
                               
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 10 Test Performed Branch &nbsp;&nbsp;<span id="tid"></span></h3><br>
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
         
                 <input type="button" name="search" class="btn btn-success" value="Search" onclick="searchData();" />          
                            </div>
                    </div>

                      
                    <div class="box-body">
                        <div class="chart">

                            <table id="columnchart_material" style="width: 610px;">

                              <!-- <thead>
                                  <th>No</th>
                                  <th>Test Name</th>
                                  <th>Branch Name</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                <?php $cnt =1;foreach($new_sub_array as $val){ 
                                    ?>
                                <tr>
                                    <td><?php echo $cnt;?>
                                    </td>
                                    <td><?php echo $val['test_name'];?></td>
                                    <td><?php echo $val['branch_name'];?></td>
                                    <td><?php echo $val['Total'];?></td>
                                </tr>
                                <?php $cnt++;}if(empty($new_sub_array)){ ?>
                                    <tr><td colspan="4">Record Not Found</td></tr>
                                <?php } ?>
                                  
                              </tbody> -->
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
         
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Expiry Consumables/ Stationary for Next 15 Days &nbsp;&nbsp;<span id="lid"></span></h3><br>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <table id="lab_stationary" style="width: 610px;">
                                 <!--  <thead>
                                  <th>No</th>
                                  <th>Reagent Name</th>
                                  <th>Branch Name</th>
                                  <th>Category Name</th>
                                  <th>Unit</th>
                              </thead>
                              <tbody>
                                <?php $cnt =1;foreach($new_sub_reagent as $lab_stationary){ 
                                    ?>
                                <tr>
                                    <td><?php echo $cnt;?>
                                    </td>
                                    <td><?php echo $lab_stationary['reagent_name'];?></td>
                                    <td><?php echo $lab_stationary['branch_name'];?></td>
                                    <td><?php echo $lab_stationary['Category'];?></td>
                                    <td><?php echo $lab_stationary['Unit'];?></td>
                                </tr>
                                <?php $cnt++;}if(empty($new_sub_reagent)) {?>
                                   <tr><td colspan="5">Record Not Found</td></tr>
                                <?php } ?>
                                  
                              </tbody> -->
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
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
                             <input type="button" name="search" class="btn btn-success" value="Search" onclick="poData();" />  
                            </div>
                   
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <table id="po_id" style="width: 610px;">
                           <!--        <thead>
                                  <th>No</th>
                                  <th>PO Number</th>
                                  <th>Branch Name</th>
                                  <th>Vendor Name</th>
                              </thead>
                               <tbody>
                                <?php $cnt =1;foreach($query as $ponumber){ 
                                    ?>
                                <tr>
                                    <td><?php echo $cnt;?>
                                    </td>
                                    <td><?php echo $ponumber['ponumber'];?></td>
                                    <td><?php echo $ponumber['branch_name'];?></td>
                                    <td><?php echo $ponumber['vendor_name'];?></td>
                                </tr>
                                <?php $cnt++;}if(empty($query)){?>
                                <tr><td colspan="4">Record Not Found</td></tr>
                                <?php } ?>
                              </tbody> -->
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
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
            data: {'sub_id': sub},
            success: function (data) {
                    $('#bid').html(selectedText);
                console.log(data);
                if (data != '') {
                    $("#sub_total").html(data);
                } else {
                    $("#sub_total").html("Record Not Found");
                }
            }
        });

         //End Code reagent list of Branch Wise
          //Start Branch wise total test
          $.ajax({
            url: "<?php echo base_url(); ?>inventory/dashboard/total_test",
            type: "get",
            data: {'sub_id': sub},
            success: function (data) {
                console.log(data);
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
                console.log(data);
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
                console.log(data);
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
            data: {'sub_id': sub,'start_date':start,'end_date':end},
            success: function (data) {
                   $('#tid').html(selectedText);
                if (data != '') {
                    $("#columnchart_material").html(data);
                } else {
                    $("#columnchart_material").html("Record Not Found");
                }
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
            data: {'sub_id': sub,'start_date':start,'end_date':end},
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

</script>