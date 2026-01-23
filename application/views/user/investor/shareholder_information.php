<style>
.info-box{
    background:#fff;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    overflow:hidden;
    margin-left: -6px;
}

.info-header{
    background:#bf2d37;
    color:#fff;
    padding:14px 22px;
    font-weight:700;
    cursor:pointer;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:16px;
}

.info-content{
    padding:22px;
    display:none;
}

/* PDF Grid */
.info-files{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:15px;
}

.info-files a{
    background:#f9f9f9;
    border-radius:10px;
    padding:14px 12px;
    color:#0056b3;
    font-weight:600;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    transition:0.3s;
}

.info-files a:hover{
    background:#fff;
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.12);
}

.info-files i{
    color:#d32f2f;
    font-size:20px;
}

/* Table Styling */
.info-content table{
    margin-top:10px;
}

.info-content table th{
    background:#f3f3f3;
    font-weight:700;
}

.info-content table th,
.info-content table td{
    text-align:center;
    vertical-align:middle;
    font-size:14px;
}

/* Section Headings */
.info-content h4{
    font-weight:700;
    color:#bf2d37;
    margin-bottom:12px;
}

/* Mobile */
@media(max-width:768px){
    .info-files{
        grid-template-columns: repeat(1, 1fr);
    }
}
/* .row {
    margin-left: -14px !important;
} */
</style>

<div class="col-md-12">

    <!-- AGM -->
    <div class="info-box">
        <div class="info-header">
            AGM
            <span class="toggle-icon">▲</span>
        </div>

        <div class="info-content">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Particulars / Financial Year</th>
                        <th>Notice - AGM</th>
                        <th>Results - AGM</th>
                        <th>Transcript</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($agm)) { foreach($agm as $row){ ?>
                    <tr>
                        <td><?= $row['particulars']; ?></td>
                        <td>
                            <?php if($row['notice_pdf']){ ?>
                                <a target="_blank" href="<?= base_url('upload/agm/'.$row['notice_pdf']); ?>">
                                    <?= $row['notice_title']; ?>
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row['result_pdf']){ ?>
                                <a target="_blank" href="<?= base_url('upload/agm/'.$row['result_pdf']); ?>">
                                    Results
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row['transcript_pdf']){ ?>
                                <a target="_blank" href="<?= base_url('upload/agm/'.$row['transcript_pdf']); ?>">
                                    Transcript
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="4" class="text-center">No AGM data found</td></tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- Credit Rating -->
    <div class="info-box">
        <div class="info-header">
            Credit Rating
            <span class="toggle-icon">▲</span>
        </div>

        <div class="info-content">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Facilities / Type</th>
                        <th>Amount (₹ Cr)</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($credit_rating)){ foreach($credit_rating as $cr){ ?>
                    <tr>
                        <td><?= $cr['facilities_type_of_rating']; ?></td>
                        <td><?= $cr['amount']; ?></td>
                        <td><?= $cr['rating']; ?></td>
                        <td><?= $cr['rating_status']; ?></td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="4" class="text-center">No Credit Rating Found</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- EGM -->
    <div class="info-box">
        <div class="info-header">
            EGM / CCM Notices
            <span class="toggle-icon">▲</span>
        </div>

        <div class="info-content">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Particulars / Financial Year</th>
                        <th>Notice</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($egm)){ foreach($egm as $e){ ?>
                    <tr>
                        <td><?= $e['particulars']; ?></td>
                        <td>
                            <a target="_blank" href="<?= base_url('upload/egm/'.$e['notice_pdf']); ?>">
                                <?= $e['notice_title']; ?>
                            </a>
                        </td>
                        <td>
                            <?php if($e['result_pdf']){ ?>
                                <a target="_blank" href="<?= base_url('upload/egm/'.$e['result_pdf']); ?>">Results</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="3" class="text-center">No EGM Data Found</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Newspaper Ads -->
    <div class="info-box">
        <div class="info-header">
            Newspaper Advertisement
            <span class="toggle-icon">▲</span>
        </div>

        <div class="info-content">
            <?php
            $newsTypes = [];
            foreach($newspaper_advertisment as $n){
                $newsTypes[$n['type']][] = $n;
            }
            ?>

            <?php foreach($newsTypes as $type => $ads){ ?>
                <h4><?= $type; ?></h4>
                <div class="info-files">
                    <?php foreach($ads as $ad){ ?>
                        <a target="_blank" href="<?= base_url('upload/newspaper/'.$ad['pdf_path']); ?>">
                            <i class="fa fa-file-pdf-o"></i>
                            <?= $ad['pdf_title']; ?>
                        </a>
                    <?php } ?>
                </div>
                <hr>
            <?php } ?>
        </div>
    </div>

    <!-- Shareholder Info -->
    <?php
    $types = [];
    foreach($shareholder_info as $s){
        $types[$s['type']][] = $s;
    }
    ?>

    <?php foreach($types as $type => $rows){ ?>
        <div class="info-box">
            <div class="info-header">
                <?= $type; ?>
                <span class="toggle-icon">▲</span>
            </div>

            <div class="info-content">
                <div class="info-files">
                    <?php foreach($rows as $r){ ?>
                        <a target="_blank" href="<?= base_url('upload/shareholder/'.$r['pdf_path']); ?>">
                            <i class="fa fa-file-pdf-o"></i>
                            <?= $r['pdf_title']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

</div>


<script>
$('.info-header').click(function(){

    // Close others
    $('.info-content').not($(this).next()).slideUp();
    $('.toggle-icon').text("▲");

    // Toggle current
    $(this).next('.info-content').slideToggle();

    // Change icon
    $(this).find('.toggle-icon').text(function(i, text){
        return text === "▲" ? "▼" : "▲";
    });

});
</script>
