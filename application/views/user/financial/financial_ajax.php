<style>
.doc-card{
    background:#fff;
    border-radius:12px;
    padding:18px 15px;
    display:flex;
    align-items:center;
    gap:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    margin-bottom:20px;
    transition:0.3s;
}

.doc-card:hover{
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

.doc-card i{
    font-size:26px;
    color:#d32f2f;
}

.doc-card a{
    font-size:14px;
    font-weight:600;
    color:#0056b3;
    text-decoration:none;
}

.doc-card a:hover{
    text-decoration:underline;
}

.no-record{
    font-weight:600;
    color:#999;
    margin-top:20px;
}
.row{
    margin-left:-8px !important;
}
</style>
<div class="row">
<?php if(!empty($files)){ ?>
    <?php foreach($files as $f){ ?>
        <div class="col-sm-4">
            <div class="doc-card">
                <i class="fa fa-file-pdf-o"></i>

                <a target="_blank"
                   href="<?= base_url('upload/financial/'.$f['file_path']); ?>">
                    <?= $f['file_title']; ?>
                </a>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <div class="col-md-12 text-center">
        <p class="no-record">No records found.</p>
    </div>
<?php } ?>
</div>