<style>
    .rhp-box{
    background:#fff;
    border-radius:14px;
    padding:25px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

.rhp-content{
    font-size:14px;
    color:#444;
    line-height:1.7;
}

.rhp-download{
    margin-top:20px;
}

.rhp-btn{
    background:#bf2d37;
    color:#fff;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    display:inline-flex;
    align-items:center;
    gap:8px;
    transition:0.3s;
}

.rhp-btn:hover{
    background:#a9242d;
    color:#fff;
    text-decoration:none;
}

.no-record{
    color:#999;
    font-style:italic;
    text-align:center;
    margin-top:20px;
}
</style>
<?php if(!empty($rhp)){ ?>

<div class="rhp-box">

    <div class="rhp-content">
        <?= $rhp['content']; ?>
    </div>

    <?php if(!empty($rhp['pdf_file'])){ ?>
        <div class="rhp-download">
            <a href="<?= base_url('upload/rhp/'.$rhp['pdf_file']); ?>"
               target="_blank"
               class="rhp-btn">
                <i class="fa fa-download"></i> Download RHP PDF
            </a>
        </div>
    <?php } ?>

</div>

<?php } else { ?>

<p class="no-record">No RHP data available.</p>

<?php } ?>