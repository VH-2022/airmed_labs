<style>
.investor-card{
    background:#fff;
    border-radius:14px;
    padding:22px 20px;
    margin-bottom:25px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    min-height:270px;
    transition:0.3s;
    position:relative;
}

.investor-card:hover{
    transform:translateY(-4px);
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}

.investor-card h4{
    font-weight:700;
    margin-bottom:4px;
    color:#bf2d37;
    font-size:18px;
}

.investor-card .designation{
    font-size:14px;
    color:#666;
    margin-bottom:12px;
}

.investor-card hr{
    margin:12px 0;
    border-color:#eee;
}

.investor-card p{
    font-size:14px;
    margin-bottom:6px;
    color:#444;
    line-height:1.5;
}

.investor-card .contact-title{
    font-weight:700;
    color:#222;
}

.investor-card a{
    color:#0056b3;
    text-decoration:none;
    font-weight:600;
}

.investor-card a:hover{
    text-decoration:underline;
}

.investor-icon{
    position:absolute;
    top:18px;
    right:18px;
    font-size:22px;
    color:#bf2d37;
    opacity:0.15;
}
</style>

<div class="row">
<?php foreach($contacts as $c){ ?>
    <div class="col-md-4 col-sm-6">
        <div class="investor-card">

            <i class="fa fa-user investor-icon"></i>

            <h4><?= $c['name'] ?></h4>
            <div class="designation"><?= $c['designation'] ?></div>

            <hr>

            <p class="contact-title"><?= $c['title'] ?></p>

            <p><?= nl2br($c['address']) ?></p>

            <?php if(!empty($c['telephone'])){ ?>
                <p><strong>Tel:</strong> <?= $c['telephone'] ?></p>
            <?php } ?>

            <?php if(!empty($c['fax'])){ ?>
                <p><strong>Fax:</strong> <?= $c['fax'] ?></p>
            <?php } ?>

            <?php if(!empty($c['email'])){ ?>
                <p><strong>Email:</strong>
                    <a href="mailto:<?= $c['email'] ?>">
                        <?= $c['email'] ?>
                    </a>
                </p>
            <?php } ?>

        </div>
    </div>
<?php } ?>
</div>
