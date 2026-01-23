<style>
    .cp-card{
    background:#fff;
    border-radius:12px;
    padding:18px 16px;
    display:flex;
    align-items:center;
    gap:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    margin-bottom:20px;
    transition:0.3s;
}

.cp-card:hover{
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

.cp-card i{
    font-size:26px;
    color:#d32f2f;
}

.cp-card a{
    font-size:14px;
    font-weight:600;
    color:#0056b3;
    text-decoration:none;
}

.cp-card a:hover{
    text-decoration:underline;
}
</style>
<div class="row">
<?php foreach($docs as $d){ ?>
    <div class="col-sm-4">
        <div class="cp-card">
            <i class="fa fa-file-pdf-o"></i>

            <a href="<?= base_url('upload/corporate_presentation/'.$d['pdf_path']) ?>"
               target="_blank">
                <?= $d['pdf_title'] ?>
            </a>
        </div>
    </div>
<?php } ?>
</div>