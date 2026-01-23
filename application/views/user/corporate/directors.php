<?php if (!empty($directors)) {
    $first = $directors[0];
?>

    <!-- MAIN DETAIL SECTION -->
    <div class="row" id="director-detail">
        <div class="col-sm-8">
            <h4 id="d-name"><?php echo $first['name']; ?></h4>
            <p><strong id="d-position"><?php echo $first['position']; ?></strong></p>
            <!-- <p id="d-description"><?php //echo $first['description']; ?></p> -->
             <div id="d-description"><?php echo $first['description']; ?></div>
        </div>

        <div class="col-sm-4">
            <img id="d-image"
                src="<?php echo base_url('upload/board/' . $first['image']); ?>"
                class="img-responsive"
                style="border-radius:10px;">
        </div>
    </div>

    <hr>

    <h3 class="text-center">Meet Them All</h3>

    <div class="row" style="margin-top:30px;">
        <?php foreach ($directors as $row) { ?>
            <div class="col-sm-3 text-center director-card"
                style="margin-bottom:30px; cursor:pointer;"
                data-name="<?= $row['name'] ?>"
                data-position="<?= $row['position'] ?>"
                data-description="<?= base64_encode($row['description']) ?>"
                data-image="<?php echo base_url('upload/board/' . $row['image']); ?>">

                <img src="<?php echo base_url('upload/board/' . $row['image']); ?>"
                    class="img-responsive img-circle"
                    style="margin:auto; height:180px; width:180px; object-fit:cover;">

                <h5 style="margin-top:10px;"><?php echo $row['name']; ?></h5>
                <p><?php echo $row['position']; ?></p>
            </div>
        <?php } ?>
    </div>

<?php } else { ?>
    <p>No directors found.</p>
<?php } ?>