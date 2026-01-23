

<style>
   @media (max-width: 479px) and (min-width: 455px){
	.package-div { height: auto; min-height: auto;}
   }

</style>
<!-- Start main-content -->
<div class="main-content">
    <section id="about" class="package-div our_team">
        <div class="gray-overlay desc_full_width_img">
            <img src="<?php echo base_url(); ?>user_assets/images/new/pathologists.jpg">
        </div>
        <div class="container" style="padding:0">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <center>
                        <h1 class="font-40 font-w res_mrgn_top_5" style="line-height:38px; color:#BF2D37;text-shadow:1px -1px #fff;">Team Pathology</h1>
                        <div class="border-btn">
                            <span></span>
                        </div>
                    </center>
                </div>
            </div>
        </div>


    </section>

    <section>
        <div class="container pb-0">
            <div class="section-content team_photos">
                <div class="row multi-row-clearfix">

                <?php if (!empty($team_list)) { ?>
                    <?php foreach ($team_list as $row) { ?>

                        <div class="col-sm-12 col-md-12 mb-60 sm-text-center"
                            style="background:#f1f2f3; box-shadow:1px 1px 10px #ccc; padding:10px 0; float:left;width:100%;">

                            <div class="col-sm-3">
                                <div class="my_team_lft_div">
                                    <img src="<?php echo base_url(); ?>upload/team/<?php echo $row['image']; ?>"
                                        style="width:100%; border-radius:5px;">
                                </div>
                            </div>

                            <div class="col-sm-9">
                                <div class="my_team_rgt_div">
                                    <h1><?php echo $row['title']; ?></h1>
                                    <h2><?php echo $row['designation']; ?></h2>

                                    <div class="dcsrptn">
                                        <p><?php echo $row['desc']; ?></p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    <?php } ?>
                <?php } else { ?>
                    <div class="col-sm-12 text-center">
                        <h3 style="color:red;">No Team Members Found</h3>
                    </div>
                <?php } ?>

                </div>

            </div>
        </div>
    </section>
</div>
<script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>
<script>
function toggleIcon(e) {
    $(e.target)
    .prev('.panel-heading')
    .find(".short-full")
    .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);
</script>
