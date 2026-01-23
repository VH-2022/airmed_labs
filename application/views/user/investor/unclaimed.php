<style>
.postal-box{
    background:#fff;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    overflow:hidden;
    margin-left:7px;
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

/* CLOSED by default */
.postal-files{
    padding:22px;
    display:none;
}

/* OPEN state (grid only when open) */
.postal-files.open{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap:15px;
}

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
    .postal-files.open{
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
                <a href="<?= base_url('upload/unclaimed/'.$f['pdf_path']) ?>" target="_blank">
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
// $('.postal-header').click(function(){
//     $('.postal-files').not($(this).next()).slideUp();
//     $('.toggle-icon').text("▲");
//     $(this).next('.postal-files').slideToggle();
//     $(this).find('.toggle-icon').text(function(i, text){
//         return text === "▲" ? "▼" : "▲";
//     });
// });
$('.postal-header').click(function(){

    let current = $(this).next('.postal-files');

    // Close all others
    $('.postal-files').not(current)
        .slideUp()
        .removeClass('open');

    $('.toggle-icon').text("▲");

    // Toggle current
    if(current.hasClass('open')){
        current.slideUp().removeClass('open');
        $(this).find('.toggle-icon').text("▲");
    }else{
        current
            .addClass('open')
            .hide()
            .slideDown();
        $(this).find('.toggle-icon').text("▼");
    }
});
</script>
