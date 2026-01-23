<div class="main-content">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">

<style>
body { background:#f5f6f8; }

.investor-section{
    background:#fff;
    padding:40px 0;
}

.investor-tabs li a{
    background:#eaf1fb;
    color:#000;
    border-radius:30px;
    margin-right:10px;
    padding:10px 28px;
    font-weight:600;
}

.nav-pills li.active>a,
.nav-pills li>a:hover{
    background:#bf2d37 !important;
    color:#fff !important;
}

.investor-box{
    background:#fff;
    padding:30px;
    margin-top:25px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
}

.filter-row{
    margin:25px 0;
}

.bootstrap-select .dropdown-toggle{
    border:1px solid #ccc;
    height:42px;
    border-radius:8px;
    font-weight:600;
}

.bootstrap-select .dropdown-menu{
    z-index:99999 !important;
}

/* Committee */
.committee-box{
    background:#fff;
    padding:22px 20px;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:25px;
    height:420px;
    display:flex;
    flex-direction:column;
}

.committee-title{
    font-weight:700;
    margin-bottom:15px;
    color:#bf2d37;
}

.member-list{
    overflow-y:auto;
    padding-right:10px;
}

.member-item{
    padding:12px 0;
    border-bottom:1px dashed #ddd;
}

.member-item strong{
    font-size:14px;
}

.member-item .designation{
    font-size:13px;
    color:#666;
    margin-top:2px;
}

.member-list::-webkit-scrollbar{
    width:6px;
}

.member-list::-webkit-scrollbar-thumb{
    background:#bf2d37;
    border-radius:10px;
}

.corporate-header{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    gap:12px;
}

.corporate-title{
    margin:0;
    font-size:22px;
    font-weight:700;
    color:#222;
}

.corporate-dropdown{
    width:280px;
}

.corporate-dropdown .bootstrap-select{
    width:100% !important;
}

.select2-selection__arrow{
    display: none !important;
}
.investor-header{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    gap:12px;
}

.investor-title{
    margin:0;
    font-size:22px;
    font-weight:700;
    color:#222;
}

.investor-dropdown{
    width:280px;
}

.investor-dropdown .bootstrap-select{
    width:100% !important;
}

.financial-header{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    gap:14px;
}

.financial-title{
    margin:0;
    font-size:22px;
    font-weight:700;
    color:#222;
}

.financial-filters{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.financial-filters .bootstrap-select{
    width:240px !important;
}
</style>

<section class="investor-section">
<div class="container">

    <!-- Tabs -->
    <ul class="nav nav-pills investor-tabs">
        <li class="active"><a data-toggle="pill" href="#financials">Financials</a></li>
        <li><a data-toggle="pill" href="#investor-info">Investors Information</a></li>
        <li><a data-toggle="pill" href="#corporate">Corporate Governance</a></li>
    </ul>

    <div class="tab-content investor-box">

        <!-- ================= FINANCIALS ================= -->
        <div id="financials" class="tab-pane fade in active">

            <div class="financial-header">
                <h3 class="financial-title">
                    Disclosures under Regulation 46 of the SEBI (LODR) Regulations, 2015
                </h3>

                <div class="financial-filters">
                    <select id="financial-type" class="form-control selectpicker">
                        <?php foreach($category as $c){ ?>
                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                        <?php } ?>
                    </select>

                    <select id="financial-year" class="form-control selectpicker">
                        <?php
                        $currentYear = date("Y");
                        for ($i = 0; $i < 6; $i++) {
                            $start = $currentYear - $i;
                            $end = $start + 1;
                            $fy = $start . "-" . substr($end, 2);
                            echo "<option value='$fy'>$fy</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div id="financial-data" class="row" style="margin-top:20px;"></div>

        </div>

        <!-- ================= INVESTOR INFO ================= -->
        <div id="investor-info" class="tab-pane fade">
            <div class="investor-header">
                <h3 class="investor-title">
                    Disclosures under Regulation 46 of the SEBI (LODR) Regulations, 2015
                </h3>

                <div class="investor-dropdown">
                    <select id="investor-info-dropdown"
                            class="form-control selectpicker"
                            data-size="6"
                            title="Select Category">
                        <option value="corporate_presentation">Corporate Presentation</option>
                        <option value="dividend">Dividend</option>
                        <option value="investor_contact">Investor Contact</option>
                        <option value="listing_on_stock_exchanges">Listing on Stock Exchanges</option>
                        <option value="postal_ballot">Postal Ballot</option>
                        <option value="rhp">RHP</option>
                        <option value="shareholder_information">Shareholder Information</option>
                        <option value="stock_exchange">Stock Exchange Disclosures</option>
                        <option value="unclaimed">Unclaimed-Unpaid Amount</option>
                        <option value="others">Others</option>
                    </select>
                </div>
            </div>

            <div class="row" id="investor-docs" style="margin-top:20px;"></div>
        </div>

        <!-- ================= CORPORATE ================= -->
        <div id="corporate" class="tab-pane fade">
            <div class="corporate-header">
                <h3 class="corporate-title">
                    Disclosures under Regulation 46 of the SEBI (LODR) Regulations, 2015
                </h3>

                <div class="corporate-dropdown">
                    <select id="corporate-dropdown" class="form-control selectpicker">
                        <option value="directors">Board of Directors</option>
                        <option value="committees">Committees</option>
                        <option value="tc_id">T & C of Appointment of ID</option>
                        <option value="policies">Policies</option>
                    </select>
                </div>
            </div>

            <div id="corporate-content" style="margin-top:20px;"></div>
        </div>

    </div>
</div>
</section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

<script>
$(function(){
    $('.selectpicker').selectpicker();
});

/* Corporate */
$(document).on('change', '#corporate-dropdown', function(){
    $.ajax({
        url: "<?= base_url('User_master/getCorporateData'); ?>",
        type: "POST",
        data: { type: $(this).val() },
        beforeSend: function(){
            $('#corporate-content').html('<p>Loading...</p>');
        },
        success: function(response){
            $('#corporate-content').html(response);
        }
    });
});

/* Financial */
function loadFinancialData(){
    $.ajax({
        url: "<?= base_url('User_master/getFinancialData'); ?>",
        type: "POST",
        data: {
            report_type: $('#financial-type').val(),
            report_year: $('#financial-year').val()
        },
        beforeSend: function(){
            $('#financial-data').html('<p>Loading...</p>');
        },
        success: function(response){
            $('#financial-data').html(response);
        }
    });
}

$('#financial-type, #financial-year').on('change', loadFinancialData);

/* Investor Info */
function loadInvestorsInformationData(){
    $.ajax({
        url: "<?= base_url('User_master/getInvestorData'); ?>",
        type: "POST",
        data: { type: $('#investor-info-dropdown').val() },
        beforeSend: function(){
            $('#investor-docs').html('<p>Loading...</p>');
        },
        success: function(response){
            $('#investor-docs').html(response);
        }
    });
}

$('#investor-info-dropdown').on('change', loadInvestorsInformationData);

/* Defaults */
$(document).ready(function(){
    $('#corporate-dropdown').val('directors').change();
    $('#investor-info-dropdown').val('corporate_presentation').change();
    loadFinancialData();
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '.director-card', function() {

    $('#d-name').text($(this).data('name'));
    $('#d-position').text($(this).data('position'));
    $('#d-description').html("");

    var decodedDesc = atob($(this).data('description'));
    $('#d-description').html(decodedDesc);


    $('#d-image').attr('src', $(this).data('image'));

    $('html, body').animate({
        scrollTop: $("#director-detail").offset().top - 50
    }, 500);
});
</script>
