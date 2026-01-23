<style>
  .section-title{
    font-size:18px;
    font-weight:700;
    color:#bf2d37;
    margin-bottom:20px;
}

.tc-card{
    background:#fff;
    border-radius:12px;
    padding:16px 18px;
    display:flex;
    align-items:center;
    gap:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    margin-bottom:20px;
    transition:0.3s;
}

.tc-card:hover{
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

.tc-card i{
    font-size:24px;
    color:#d32f2f;
}

.tc-card a{
    font-size:14px;
    font-weight:600;
    color:#0056b3;
    text-decoration:none;
}

.tc-card a:hover{
    text-decoration:underline;
}
</style>
<h4 class="section-title">
    Terms & Conditions of Appointment of Independent Director
</h4>

<div class="row">

<?php foreach($tc as $row){ ?>
  <div class="col-md-6">
    <div class="tc-card">
      <i class="fa fa-file-pdf-o"></i>

      <a target="_blank"
         href="<?= base_url('upload/tc_id/'.$row['pdf_file']); ?>">
         <?= $row['title']; ?>
      </a>
    </div>
  </div>
<?php } ?>

</div>