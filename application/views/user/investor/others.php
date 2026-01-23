<style>
.others-list{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:18px;
    margin-left: 9px;
}

.others-list a{
    background:#fff;
    border-radius:10px;
    padding:14px 12px;
    display:flex;
    align-items:center;
    gap:10px;
    color:#0056b3;
    font-weight:600;
    text-decoration:none;
    box-shadow:0 2px 8px rgba(0,0,0,0.06);
    transition:0.3s;
}

.others-list a:hover{
    background:#fdfdfd;
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.12);
}

.others-list i{
    color:#d32f2f;
    font-size:20px;
}

.no-doc{
    color:#999;
    font-style:italic;
    text-align:center;
    margin-top:20px;
}

/* Mobile responsive */
@media(max-width:768px){
    .others-list{
        grid-template-columns: repeat(1, 1fr);
    }
}
</style>

<div class="others-list">

<?php if(!empty($files)){ ?>

    <?php foreach($files as $f){ ?>
        <a href="<?= base_url('upload/others/'.$f['pdf_path']); ?>" target="_blank">
            <i class="fa fa-file-pdf-o"></i>
            <?= $f['pdf_title']; ?>
        </a>
    <?php } ?>

<?php } else { ?>

    <p class="no-doc">No documents available.</p>

<?php } ?>

</div>
