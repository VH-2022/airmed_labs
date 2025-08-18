<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Master Salary Structure<small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>hrm/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><i class="fa fa-rocket"></i> Add Salary Structure</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-primary" style="border-color:#bf2d37;">
                    <div class="panel-heading" style="background-color:#bf2d37;">
                        <h3 class="panel-title">Add Salary Structure</h3>

                    </div>
                    <form role="form" id="department_form" action="<?php echo base_url(); ?>hrm/Salary_structure/add" method="post" enctype="multipart/form-data">
                        <div class="panel-body">

                            <div class="form-group col-sm-8">
                                <label for="exampleInputFile" class="col-sm-3 pdng_0">Salary Structure Name </label>
                                <div class="col-sm-9 pdng_0">
                                    <input type="text" id="salary_stru_name" name="salary_stru_name" placeholder="Salary Structure Name" value="<?php echo set_value('salary_stru_name'); ?>" class="form-control"/>
                                    <span style="color:red;" id="salary_stru_name_error"></span>
                                </div>
                            </div>

                            <br/><br/><br/><br/>                            
                            <div class="col-md-8">
                                <table id="example4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Add/Remove</th>
                                            <th>Salary Name</th>
                                            <th>Cut Off Type</th>
                                            <th>Value</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_body1">
                                        <tr>
                                            <td>
                                                <a class="srch_view_a" href="javascript:void(0)" onclick="add_field1();"><i class="fa fa-plus-square"></i></a>
                                            </td>
                                            <td>
                                                <input type="text" name="salary_name_1[]" placeholder="Salary Name" id="salary_name_1_0" class="form-control pricevalue">
                                                <span id="salary_name_1_error_0" style="color:red;"></span>
                                            </td>
                                            <td>
                                                <select name="cuttype[]" class="form-control" id="">
                                                    <option value="1">Fix</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="salary_value[]" placeholder="value" id="salary_value_0" class="form-control pricevalue">
                                            </td>
                                            <td>
                                                <select name="type[]" class="form-control">
                                                    <option value="1">Plus</option>
                                                    <option value="2">Minus</option>
                                                </select>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="button" onclick="submit_button1();">Add</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
    $(function () {
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 5000);
    });
</script>
<script>
    function add_field1() {
        var cnt = $('#example4 tbody tr').length;

//        var lastprchild = cnt - 1;
//        
//        var lastscond = $('#table_body1 tr:nth-child(' + lastprchild + ')').attr('id');
        //        var tresplit = lastscond.split("_");
        //        var cnt = parseInt(tresplit[1]) + 1;

        var coun = "'" + cnt + "'";
        $('#table_body1 tr:nth-child(' + cnt + ')').after('<tr id="tr_' + cnt + '"><td><a href="javascript:void(0)" onclick="row_remove(' + coun + ');"><i style="color:red;" class="fa fa-minus-square"></i></a></td><td><input type="text" name="salary_name_1[]" placeholder="Salary Name" id="salary_name_1_' + cnt + '" class="form-control"><span id="salary_name_1_error_' + cnt + '" style="color:red;"></span></td><td><select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td><td><input type="text" placeholder= "value" name="salary_value[]" id="salary_value_' + cnt + '" class="form-control"></td><td><select name="type[]" class="form-control"><option value="1">Plus</option><option value="2">Minus</option></select></td></tr>');

        /* $('#count_designation1').val(cnt); */
    }

    function row_remove(val) {
        $('#tr_' + val).remove();
        cnt--;
//        $('#count_designation').val(cnt);
    }

    function submit_button1() {
//        var count = $('#count_designation1').val();
        var temp = 1;
        /* var j = 1;
         for (j = 1; j <= count; j++) {
         var name = $('#designation_1' + j).val();
         $("#designation_error_" + j).html("");
         if (name == "") {
         $("#designation_error_" + j).html("Field Required.");
         temp = 0;
         }
         } */
        $("input[name='salary_name_1[]']").each(function (index, element) {

            var getval = $(element).val().trim();
            var spliid = this.id.split("_");

            var id = spliid[3];

            $("#salary_name_1_error_" + id).html("");
            if (id != '0') {
                if (getval.trim() == '') {
                    $("#salary_name_1_error_" + id).html("Field is Required.");
                    temp = 0;
                }
            }
        });

        if ($("#salary_stru_name").val().trim() == "") {
            $("#salary_stru_name_error").html("Field is Required.");
            temp = 0;
        }
        if (temp == 1) {
            $("#department_form").submit();
        }
    }
</script>