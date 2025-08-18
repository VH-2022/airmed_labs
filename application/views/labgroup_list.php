
<script type="text/javascript">
    $(document).ready(function(){
        $('#sub_id').validate({
            rules:{
                labid:{
                    required:true
                }
            }
        })
    })
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Lab Group List
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>LabGroup_List/labgroup_list"><i class="fa fa-users"></i>Lab Group List</a></li>

        </ol>
    </section>
<!-- <?php 
$labid = $_GET['lab'];
$end_date = $_GET['end_date'];
$price = $_GET['price'];
?> -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Lab Group List</h3>
                        <a href="javascript:void(0)" onclick="$('#family_model12').modal('show');" class="btn btn-primary btn-sm" style="float:right;">
                        <i class="fa fa-plus-circle" ></i><strong > Add</strong></a>
                                  <!--   <a style="float:right;" href='<?php echo base_url(); ?>LabGroup_List/delever_export?start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&price=<?php echo $price;?>' class="btn btn-primary btn-sm" ><i class="fa fa-download" ></i><strong > CSV Export</strong></a> -->
                                   

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="widget">
                            <?php if (isset($success) != NULL) { ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    <?php echo $success['0']; ?>
                                </div>
                            <?php } ?>

                        </div>
                      
                        <div class="tableclass">
                            <form role="form" action="<?php echo base_url(); ?>LabGroup_List/labgroup_list" method="get" enctype="multipart/form-data" >
                               <!--  <div class="col-md-3">
                                    <input type="text" placeholder="Delever Date" readonly="" class="form-control datepicker-input" name="start_date" id="start_id" value="<?php 
                                    if(isset($start_date)){
                                    echo $start_date;}else{
                                        echo date('d/m/Y');
                                    }
?>" />
                                  
                                </div> -->
                                <!-- <div class="col-md-3">
                                    <input type="text" placeholder="Delever Date" readonly="" class="form-control datepicker-input" name="end_date" value="<?php if(isset($end_date)){echo $end_date;}else{ echo date('d/m/Y');} ?>" id="birth_date"/>
                                  
                                </div>
                                <div class="col-md-3">
                                    <input type="text" placeholder="Delever Price" class="form-control" name="price" value="<?php echo $price; ?>" id="price_id"/>
                                    <span id="errmsg" class="text-danger"></span>
                                </div>
                                -->
                                <!-- <input type="submit" name="search" class="btn btn-success" value="Search" /> -->
                                 <div id="prnt_rpt">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
											<th>Branch Name</th>
                                            <th>Lab Name</th>
                                            <th>Added By</th>  
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                 
                                        <?php
                                        $cnt = 1;
                                        foreach ($query as $row) {
                                            ?> 

                                            <tr>
                                                <td><?php echo $cnt; ?></td>
												<td><?php echo $row['branch_name']; ?></td>
                                               
                                                    <td><?php echo $row['CollectForm']; ?></td>
                                                   
                                                 <td><?php echo ucwords($row['AdminName']); ?></td>
                                            
                                                <td>
                                                   

                                                    <a  href='<?php echo base_url(); ?>LabGroup_List/labgroup_delete/<?php echo $row['id']; ?>' data-toggle="tooltip" data-original-title="Remove" onclick="return confirm('Are you sure you want to remove this data?');"><i class="fa fa-trash-o"></i></a>
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
                                 </div>
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
<div class="modal fade" id="family_model12" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Lab .</h4>
                    </div>
                    <?php echo form_open("LabGroup_List/labgroup_add/" , array("method" => "POST", "role" => "form","id"=>'sub_id')); ?>
                 
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Branch<i style="color:red;">*</i>:</label>
                            
                            <select name="branch" onchange="getbranchlab(this)" class="form-control" required="">
                                <option value="">Select Branch</option>
                                <?php foreach($branch_list as $bar){ ?>
                                    <option value="<?php echo $bar['id'];?>"><?php echo $bar['branch_name'];?></option>
                                <?php } ?>
                            </select>
                           
                            <span style="color:red;"><?php echo form_error('branch');?></span>
                        </div>
						<div id="labhtml"></div>
						 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="amount_submit_btn">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    <?= form_close(); ?>
                </div>

            </div>
        </div>
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example3').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false
        });
    });
	function getbranchlab(branch){
		var brach=branch.value;
		if(brach != ""){
		$.ajax({
        url: '<?= base_url()."labGroup_List/getblab"; ?>',
        type: 'get',
        data: {brach: brach},
        success: function (data) {
			$("#labhtml").html("");
         	$("#labhtml").html(data);
        },
        error: function (jqXhr) {
            $("#labhtml").html("");
        },
        complete: function () {
        },
    });
		
		}
	}
</script>

