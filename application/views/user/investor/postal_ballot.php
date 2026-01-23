<style>
.postal-box{
    background:#fff;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    overflow:hidden;
    margin-left: 10px;
}

.postal-header{
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

.postal-files{
    padding:22px;
    display:none;

    /* display:grid; */
    grid-template-columns: repeat(3, 1fr);
    gap:15px;
}
/* .row {
    margin-left: 0px !important;
} */

.postal-files a{
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

.postal-files a:hover{
    background:#fff;
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.12);
}

.postal-files i{
    color:#d32f2f;
    font-size:20px;
}

.no-doc{
    color:#999;
    font-style:italic;
}

/* Mobile responsive */
@media(max-width:768px){
    .postal-files{
        grid-template-columns: repeat(1, 1fr);
    }
}
</style>

<?php foreach($ballots as $b){ ?>
<div class="postal-box">

    <div class="postal-header">
        <?= $b['title'] ?>
        <span class="toggle-icon">▲</span>
    </div>

    <div class="postal-files">
        <?php if(!empty($b['files'])){ ?>
            <?php foreach($b['files'] as $f){ ?>
                <a href="<?= base_url('upload/postal_ballot/'.$f['pdf_path']) ?>" target="_blank">
                    <i class="fa fa-file-pdf-o"></i>
                    <?= $f['pdf_title'] ?>
                </a>
            <?php } ?>
        <?php } else { ?>
            <p class="no-doc">No documents found.</p>
        <?php } ?>
    </div>

</div>
<?php } ?>

<script>
$('.postal-header').click(function(){

    // Close all other open sections
    $('.postal-files').not($(this).next()).slideUp();
    $('.toggle-icon').text("▲");

    // Toggle current
    $(this).next('.postal-files').slideToggle();

    // Change icon
    $(this).find('.toggle-icon').text(function(i, text){
        return text === "▲" ? "▼" : "▲";
    });

});
</script>