<style>
  .policy-card{
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

.policy-card:hover{
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

.policy-card i{
    font-size:24px;
    color:#d32f2f;
}

.policy-card a{
    font-size:14px;
    font-weight:600;
    color:#0056b3;
    text-decoration:none;
}

.policy-card a:hover{
    text-decoration:underline;
}
</style>
<h4 class="section-title">
    Policies & Programs
</h4>

<div class="row">

<?php foreach($policies as $row){ ?>
  <div class="col-md-6">
    <div class="policy-card">
      <i class="fa fa-file-pdf-o"></i>

      <a target="_blank"
         href="<?= base_url('upload/policies/'.$row['pdf_file']); ?>">
         <?= $row['title']; ?>
      </a>
    </div>
  </div>
<?php } ?>

</div>