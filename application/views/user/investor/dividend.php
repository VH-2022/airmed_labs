<style>
.dividend-box{
    background:#fff;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    overflow:hidden;
    margin-left: -6px;
}

.dividend-header{
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

.dividend-content{
    padding:22px;
    display:none;
}

/* PDF Grid */
.dividend-files{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:15px;
}

.dividend-files a{
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

.dividend-files a:hover{
    background:#fff;
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.12);
}

.dividend-files i{
    color:#d32f2f;
    font-size:20px;
}

/* Table Styling */
.dividend-content table{
    margin-top:10px;
}

.dividend-content table th{
    background:#f3f3f3;
    font-weight:700;
}

.dividend-content table th,
.dividend-content table td{
    text-align:center;
    vertical-align:middle;
}

/* Mobile */
@media(max-width:768px){
    .dividend-files{
        grid-template-columns: repeat(1, 1fr);
    }
}
</style>
<div class="col-md-12">

    <!-- Dividend Communications -->
    <div class="dividend-box">

        <div class="dividend-header">
            Dividend Communications
            <span class="toggle-icon">▲</span>
        </div>

        <div class="dividend-content">

            <div class="dividend-files">
                <?php foreach($communications as $row){ ?>
                    <a href="<?= base_url('upload/dividend/'.$row['pdf_path']); ?>" target="_blank">
                        <i class="fa fa-file-pdf-o"></i>
                        <?= $row['pdf_title']; ?>
                    </a>
                <?php } ?>
            </div>

        </div>
    </div>


    <!-- Dividend History -->
    <div class="dividend-box">

        <div class="dividend-header">
            Dividend History
            <span class="toggle-icon">▲</span>
        </div>

        <div class="dividend-content">

            <p style="font-weight:600; color:#555;">
                The Dividend history of the Company, since listing of its Equity Shares in the year 2015, is given below:
            </p>

            <?php
            $years = [];
            foreach($history as $h){
                $years[$h['year']][] = $h;
            }
            ?>

            <?php foreach($years as $year => $rows){ ?>
                <h5 style="font-weight:700; color:#bf2d37; margin-top:20px;">
                    <?= $year; ?>
                </h5>

                <table class="table table-bordered">
                    <tr>
                        <?php foreach($rows as $r){ ?>
                            <th><?= $r['type']; ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php foreach($rows as $r){ ?>
                            <td><?= $r['amount']; ?></td>
                        <?php } ?>
                    </tr>
                </table>
            <?php } ?>

        </div>
    </div>

</div>
<script>
$('.dividend-header').click(function(){

    // Close other sections
    $('.dividend-content').not($(this).next()).slideUp();
    $('.toggle-icon').text("▲");

    // Toggle current
    $(this).next('.dividend-content').slideToggle();

    // Change icon
    $(this).find('.toggle-icon').text(function(i, text){
        return text === "▲" ? "▼" : "▲";
    });

});
</script>