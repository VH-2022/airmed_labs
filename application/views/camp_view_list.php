
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" rel="stylesheet" />
<style>
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

    .full_bg{background: rgba(0,0,0,0.3); width:100%; height:100%; float:left; padding:250px; position:fixed; z-index:9; top:0; bottom:0;}
    .full_bg .loader img{width:70px; height:70px;}
    .text_highlight:hover{
        text-decoration: underline;
        /*font-weight: bold;
        font-size: 12px;*/
    }	
</style>
<div class="full_bg" style="display:none;" id="loader_div">
    <div class="loader">
        <center><img src="http://static.heart.org/riskcalc/app/assets/img/loading.gif"></center>
    </div>
</div>
<section class="content-header">
    <h1>
        List Camp SMS
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Camp_sms"><i class="fa fa-users"></i>Camp SMS List</a></li>
        <li class="active">View Camp SMS</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">

                <?php if ($this->session->flashdata("success") != "") { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $this->session->flashdata("success"); ?>
                    </div>
                <?php } ?>

                <div class="box-body">

                    <div class="col-md-6">


                        <button class="btn btn-danger btn-sm pull-left" id="del_all">Delete selected</button>
                        <input type="hidden" value="<?php echo $list_view[0]['sms_fk']; ?>" id="test_id">
                        <table id="example3" class="table table-bordered table-striped">
                            <thead>

                            <th><input id="selecctall" type="checkbox"></th>
                            <th>No</th>
                            <th>SMS</th>
                            <th>Mobile</th>
<!--                            <th>Status</th>-->
                            <th>Action</th>
                            </thead>
                            <tbody>

                                <?php $cnt = 1;
                                foreach ($list_view as $key => $val) { ?>

                                    <tr>
                                        <td><input name="checkbox[]" class="checkbox1" type="checkbox" id="checkbox[]" value="<?php echo $val['id'] ?>"></td>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo ucfirst($val['sms']); ?></td>
                                        <td><?php echo $val['mobile']; ?></td>
    <!--                                    <td><?php
                                        if ($val['send'] == '1') {
                                            $status = "<span class='label label-warning '>Pending</span>";
                                        } else {
                                            $status = "<span class='label label-success '>Send</span>";
                                        }
                                        echo $status;
                                        ?></td>-->
                                        <td><a href="<?php echo base_url(); ?>Camp_sms/view_delete/<?php echo $val['id']; ?>/<?php echo $val['sms_fk']; ?>" onclick="return confirm('Are you sure?');"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                    </tr>
    <?php $cnt++;
} ?>
                            </tbody>
                        </table>

                    </div>	
                </div>


            </div>
        </div>
</section>
<script>
    $("#startdate").datepicker({
        todayBtn: 1,
        autoclose: true, format: 'dd-mm-yyyy', endDate: '+0d'
    });
</script>

<script>

    $(document).ready(function () {

        resetcheckbox();

        $('#selecctall').click(function (event) {  //on click

            if (this.checked) { // check select status

                $('.checkbox1').each(function () { //loop through each checkbox

                    this.checked = true;  //select all checkboxes with class "checkbox1"             

                });

            } else {

                $('.checkbox1').each(function () { //loop through each checkbox

                    this.checked = false; //deselect all checkboxes with class "checkbox1"                     

                });

            }

        });

        $("#del_all").on('click', function (e) {
            var sub_id = $('#test_id').val();

            e.preventDefault();

            var checkValues = $('.checkbox1:checked').map(function ()

            {

                return $(this).val();

            }).get();

            console.log(checkValues);



            $.each(checkValues, function (i, val) {

                $("#" + val).remove();

            });

//                    return  false;

            $.ajax({

                url: '<?php echo base_url() ?>Camp_sms/sub_delete',

                type: 'post',

                data: 'ids=' + checkValues

            }).done(function (data) {

                $("#respose").html(data);
                window.location.href = '<?php echo base_url(); ?>Camp_sms/viewlist/' + sub_id;
                $('#selecctall').attr('checked', false);

            });

        });




        function  resetcheckbox() {

            $('input:checkbox').each(function () { //loop through each checkbox

                this.checked = false; //deselect all checkboxes with class "checkbox1"                     

            });

        }

    });

</script>