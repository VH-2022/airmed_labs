
<div class="main-content">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
<style>
body{
    background:#f5f6f8;
}
.investor-section{
    background:#fff;
    padding:30px 0;
}
.investor-tabs li a{
    background:#eaf1fb;
    color:#000;
    border-radius:25px;
    margin-right:10px;
    padding:10px 25px;
}
.nav-pills li.active > a,
.nav-pills li > a:hover{
    background:#bf2d37 !important;
    color:#fff !important;
}
.investor-box{
    background:#fff;
    padding:30px;
    margin-top:20px;
    border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,0.08);
}
.filter-row{
    margin:25px 0;
}
.doc-item{
    padding:15px 0;
    border-bottom:1px solid #ddd;
    min-height:70px;
}
.doc-item i{
    color:#f4b400;
    font-size:20px;
    margin-right:8px;
}
.doc-item a{
    color:#0056b3;
}
.bootstrap-select .dropdown-toggle{
    border:1px solid #ccc;
    height:42px;
    border-radius:6px;
}
.bootstrap-select .dropdown-menu{
    z-index:99999 !important;
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
<h3 class="text-center">Disclosures under Regulation 46 of the SEBI (LODR) Regulations, 2015</h3>

<div class="row filter-row">
    <div class="col-sm-4">
        <select class="form-control selectpicker">
            <option>Annual Report</option>
            <option>Quarterly Result</option>
            <option>Subsidiary Financials</option>
        </select>
    </div>
    <div class="col-sm-4">
        <select class="form-control selectpicker">
            <option>2024-25</option>
            <option>2023-24</option>
            <option>2022-23</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="doc-item">
            <i class="fa fa-file-pdf-o"></i>
            <a href="#">PathLabs Unifiers Pvt Ltd - Standalone</a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="doc-item">
            <i class="fa fa-file-pdf-o"></i>
            <a href="#">PathLabs Unifiers Pvt Ltd - Consolidated</a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="doc-item">
            <i class="fa fa-file-pdf-o"></i>
            <a href="#">APRL PathLabs Pvt Ltd</a>
        </div>
    </div>
</div>
</div>

<!-- ================= INVESTOR INFO ================= -->
<div id="investor-info" class="tab-pane fade">
<h3>Disclosures under Regulation 46 of the SEBI (LODR) Regulations, 2015</h3>

<div class="row filter-row">
    <div class="col-sm-6">
        <select class="form-control selectpicker"
                data-size="6"
                title="Select Category">
            <option>Corporate Presentation</option>
            <option>Dividend</option>
            <option>Investor Contact</option>
            <option>Listing on Stock Exchanges</option>
            <option>Postal Ballot</option>
            <option>RHP</option>
            <option>Shareholder Information</option>
            <option>Stock Exchange Disclosures</option>
            <option>Unclaimed-Unpaid Amount</option>
            <option>Others</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="doc-item">
            <i class="fa fa-file-pdf-o"></i>
            <a href="#">Corporate-Presentation-Q4FY24</a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="doc-item">
            <i class="fa fa-file-pdf-o"></i>
            <a href="#">Corporate-Presentation-Q3FY24</a>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="doc-item">
            <i class="fa fa-file-pdf-o"></i>
            <a href="#">Corporate-Presentation-Q2FY24</a>
        </div>
    </div>
</div>
</div>

<!-- ================= CORPORATE ================= -->
<div id="corporate" class="tab-pane fade">
<h3>Disclosures under Regulation 46 of the SEBI (LODR) Regulations, 2015</h3>

<select class="form-control selectpicker">
    <option>Board of Directors</option>
    <option>Committes</option>
    <option>T&C of Appointment of ID</option>
    <option>Policies and Programs</option>
</select>

<div class="row" style="margin-top:20px;">
    <div class="col-sm-8">
        <h4>(Hon) Brig. Dr. Arvind Lal</h4>
        <p><strong>Executive Chairman</strong></p>
        <p>
            Dr. Lal is the Executive Chairman of Dr. Lal PathLabs and a pioneer
            in diagnostic services in India.
        </p>
    </div>
    <div class="col-sm-4">
        <img src="https://uat-cdn.drlallab.com/2023-09/Dr-arvind-lal.png" class="img-responsive">
    </div>
</div>
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
</script>