<!-- Page Heading -->
<style type="text/css">
    .errmsg{
        color:red;
    }
    .errmsg1{
        color:red;
    }
    .errmsg2{
        color:red;
    }
    .errmsg3{
        color:red;
    }
    .errmsg4{
        color:red;
    }

</style>
<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<section class="content-header">
    <h1>
        Pitch Add
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>Dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list"><i class="fa fa-users"></i> Pitch Add</a></li>
        <li class="active"> Pitch Add</li>
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
                        <form role="form" action="<?php echo base_url(); ?>Pitch_master/edit/<?php echo $id; ?>" method="post" enctype="multipart/form-data" id="branch_master_id" >
                            <input type="hidden" name="id" value="<?php echo $id; ?>"> 
                            <span id="locations_serviced_detail_id">
                                <?php
                                if (isset($query) && !empty($query)) {
                                    $index = count($query);
                                    foreach ($query as $key => $val) {
                                        ?>
                                        <span class="sub_id">
                                            <div class="form-group">
                                                <label for="type">Description</label>
                                            </div>
                                            <textarea name="description[]" class="form-control editor1"><?php echo $val['description']; ?></textarea>
                                            <div class="col-md-1 col-md-offset-11">
                                                <div class="form-group text-right">
                                                    <a class="btn bg-red remove_link" style="display: <?php echo ($index != 1) ? '' : 'none'; ?>" onclick="remove_locations_serviced(this)"><i class="fa fa-minus"></i></a>
                                                </div>
                                            </div>

                                        </span>


                                    <?php }
                                } else { ?>
                                    <div class="form-group">
                                        <label for="type">Description</label>
                                    </div>
                                    <textarea name="description[]" class="form-control editor1"></textarea>

<?php } ?>
                            </span>
                            <span id="add_locations_serviced_span">
                                <div class="row">
                                    <div class="col-md-1 col-md-offset-11">
                                        <div class="form-group text-right">
                                            <a href="javascript:void(0)" onclick="add_locations_serviced()" class="btn bg-green"> <i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                            </span>
                            <div class="box-footer">
                                <div class="col-md-6">
                                    <button class="btn btn-primary" type="submit">Add</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.box -->
                </div>
            </div>
            </section>


<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/adapters/jquery.js"></script>

<script>
$('textarea.editor1').ckeditor({
    height: "400px",
            toolbarStartupExpanded: true,
            width: "100%"
}
);
                        </script>
<script type="text/javascript">

    function add_locations_serviced()
{
    var html = '';
            var test = $('.sub_id').length;
            html += '<span class="sub_id"><div class="form-group">';
    html += '<label>Description</label><textarea name="description[]" class="form-control" id="editor1_' + test + '"></textarea></div>';
    html += '<div class="col-md-1 col-md-offset-11"><div class="form-group text-right"><a class="btn bg-red remove_link" onclick="remove_locations_serviced(this)"><i class="fa fa-minus"></i></a></div></div><hr></span>';
    $('#locations_serviced_detail_id').append(html);
    CKEDITOR.replace('editor1_' + test);
    var total_span = $('#locations_serviced_detail_id').children().length;
    //alert(total_span);
    if (total_span > 1) {
    $('.remove_link').show();
                    }
                    
                    return false;
                        
                        }
                        function remove_locations_serviced(i)
                        {
            $(i).closest('span').remove();
        var total_span = $('#locations_serviced_detail_id').children().length;
        //alert(total_span);
        if (total_span < 2) {
    $('.remove_link').hide();
        } else {
            $('.remove_link').show();
                 }
                 }
                 
                        </script>
