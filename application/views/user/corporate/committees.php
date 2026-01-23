<style>
  .committee-card{
    background:#fff;
    border-radius:14px;
    padding:22px 20px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    transition:0.3s;
}

.committee-card:hover{
    transform:translateY(-4px);
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}

.committee-title{
    font-size:18px;
    font-weight:700;
    color:#bf2d37;
    margin-bottom:15px;
    border-bottom:1px solid #eee;
    padding-bottom:8px;
}

.member-item{
    padding:10px 0;
    border-bottom:1px dashed #ddd;
}

.member-item:last-child{
    border-bottom:none;
}

.member-name{
    font-weight:600;
    color:#333;
    font-size:14px;
}

.member-name .role{
    font-weight:500;
    color:#555;
    font-size:13px;
}

.designation{
    font-size:13px;
    color:#777;
    margin-top:3px;
}

.no-member{
    color:#999;
    font-style:italic;
    text-align:center;
    margin-top:10px;
}
</style>
<div class="row">

<?php foreach($committees as $c){ ?>

  <div class="col-md-4 col-sm-6">
    <div class="committee-card">

      <h4 class="committee-title">
        <?= $c['committee_name']; ?>
      </h4>

      <div class="member-list">

        <?php
        $members = $this->db
          ->get_where('committee_members', ['committee_id' => $c['id']])
          ->result_array();

        if(!empty($members)){
          foreach($members as $m){
        ?>
            <div class="member-item">
              <div class="member-name">
                <?= $m['member_name']; ?>
                <?php if(!empty($m['role'])){ ?>
                  <span class="role">(<?= $m['role']; ?>)</span>
                <?php } ?>
              </div>

              <div class="designation">
                <?= $m['designation']; ?>
              </div>
            </div>
        <?php
          }
        } else {
          echo "<p class='no-member'>No members found</p>";
        }
        ?>

      </div>

    </div>
  </div>

<?php } ?>

</div>