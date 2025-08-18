<header class="main-header">   
    <div style="
         background: white;
         text-align: center;
         font-weight: bolder;
         ">Please contact IT support for any query  : (M) 74348 77700 &nbsp;(Mon To Sat (10Am to 7Pm))   &nbsp;  Email : it.support@airmedlabs.com</div>
    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            -webkit-border-radius: 0 6px 6px 6px;
            -moz-border-radius: 0 6px 6px;
            border-radius: 0 6px 6px 6px;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        .dropdown-submenu>a:after {
            display: block;
            content: " ";
            float: right;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
            border-width: 5px 0 5px 5px;
            border-left-color: #ccc;
            margin-top: 5px;
            margin-right: -10px;
        }

        .dropdown-submenu:hover>a:after {
            border-left-color: #fff;
        }

        .dropdown-submenu.pull-left {
            float: none;
        }

        .dropdown-submenu.pull-left>.dropdown-menu {
            left: -100%;
            margin-left: 10px;
            -webkit-border-radius: 6px 0 6px 6px;
            -moz-border-radius: 6px 0 6px 6px;
            border-radius: 6px 0 6px 6px;
        }
        .skin-red .main-header li.user-header{background: #f9f9f9; border-bottom: 1px solid #ccc;}
        .navbar-nav > .user-menu > .dropdown-menu > li.user-header > p{color:#444; font-size:22px;font-weight:500;}
        .navbar-nav > .user-menu > .dropdown-menu > li.user-header > h3{color:#444; font-size:18px; font-weight:normal !important;text-transform:uppercase;}
        .navbar-nav > .user-menu > .dropdown-menu > li.user-header{height:130px;}
        .user-footer a i{margin-right:10px;}
        .user-footer .prfl_red_btn{background-color: #dd4b39; border-color: #dd4b39; color: #fff !important;}
        .user-footer .prfl_red_btn:hover{background-color: #C64536 !important; border-color: #C64536;}
        .navbar-nav > li > .dropdown-menu{ box-shadow: 1px 1px 10px #000 !important;}
        .navbar-nav > li > .dropdown-menu.mstr {
            box-shadow: 1px 1px 10px #000 !important;
            min-height: auto;
            max-height: 580px;
            overflow-y: auto;
        }   

    </style>
    <?php
    $sub = '0';
    if ($login_data['type'] == 6) {
        $sub = 6;
    }
    foreach ($login_data['branch_fk'] as $val) {
        $btype[] = $val['branch_type_fk'];
    }
    ?>
    <nav class="navbar navbar-static-top">
        <div class="">
            <div class="navbar-header">
                        <!--<span id="pending_count_2"/> <span id="pending_count_1"/>-->
                <span id=""/> <span id=""/>
                <a href="<?php echo base_url(); ?>Dashboard" class="navbar-brand" style="
                   padding-top: 0px;
                   padding-bottom: 0px;
                   "><img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="logo" alt="AirmedLabs" style="width:187px;height:50px"></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php if ($login_data["type"] == 4) { ?>
                        <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list">Sample Master</a></li>
                    <?php } ?>
                    <?php if ($login_data["type"] == 1) { ?>
                        <li class="dropdown">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Booking Master <span id="total_pending_count" class="label label-warning"> </span><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>job-master/pending-list"> All Bookings <span id="pending_count" class="label label-warning"> </span> <span class="sr-only">(current)</span></a></li>
    <!--                                <li><a href="<?php echo base_url(); ?>add_result/sample_collected_list">Job Test Result</a></li>-->
                                <li><a href="<?php echo base_url(); ?>job_master/spam_job_list">Spam Bookings</a></li>
                                <li><a href="<?php echo base_url(); ?>job_report/mypayment">My Booking Payment</a></li>
                                <li><a href="<?php echo base_url() . "doctor_appointment?startdate=" . date("d-m-Y") . "&end_date=" . date("d-m-Y") . ""; ?>">Doctor Appointment</a></li>
                            </ul>
                        </li>

                        <li class="active"><a href="<?php echo base_url(); ?>job-master/prescription-report"> Prescription <span id="prescription_count" class="label label-warning"> <span class="sr-only">(current)</span></a></li>
                        <?php /* <li ><a href="<?php echo base_url(); ?>job_master/Package_test_inquiry_list">All Inquiries <span id="test_package_count" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li> */ ?>
                        <li class="dropdown" style="display:none;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Web Inquiries <span id="inquiry_total" class="label label-danger"> </span> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li ><a href="<?php echo base_url(); ?>job_master/Package_test_inquiry_list">All Inquiries <span id="test_package_count" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li>
                                <li ><a href="<?php echo base_url(); ?>customer_master/Package_inquiry_list"> Package Inquiry  <span id="package_inquiry" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li>

                            </ul>
                        </li> 

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Master <span class="caret"></span></a>
                            <ul class="dropdown-menu mstr" role="menu">
                                <li><a href="<?php echo base_url(); ?>Admin_manage/user_list"> Admin User Manage </a></li>
                                <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list">Branch Master</a></li>
                                <li><a href="<?php echo base_url(); ?>Dr_assign_branch">Doctor Assign Branch</a></li>
                                <li><a href="<?php echo base_url(); ?>Admin_assign_branch/index">Admin Assign Branch Mail</a></li>
                                <li><a href="<?php echo base_url(); ?>Auto_approve_test/index">Auto Approve Test Manage</a></li>
                                <li><a href="<?php echo base_url(); ?>Creditors_master/index">Creditors Master</a></li>
                                <li><a href="<?php echo base_url(); ?>location-master/city-list">City</a></li>
                                <li><a href="<?php echo base_url(); ?>Camp_sms">Camp SMS (Weekly)</a></li>
                                <li><a href="<?php echo base_url(); ?>doctor_master/doctor_list"> Doctor </a></li>
                                <li><a href="<?php echo base_url() . "camping/societyadmin"; ?>">Society Camp</a></li>
                                <li><a href="<?php echo base_url() . "camping/society_campconvert"; ?>">Converted report</a></li>
                                <li><a href="<?php echo base_url(); ?>doctor_test_discount/doctor_test_discount_list"> Doctor Test Discount </a></li>
                                <li><a href="<?php echo base_url(); ?>backup/backup_list"> Database Backup <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_category_master/expense_category_list"> Expenses Category </a></li>
                                <li><a href="<?php echo base_url(); ?>health_feed_master/health_feed_list"> Health Feed </a></li>
                                <li><a href="<?php echo base_url(); ?>job_master/mail_chimp"> Mail Chimp</a></li>
                                <li><a href="<?php echo base_url(); ?>offer_master">Offer</a></li>
                                <li><a href="<?php echo base_url(); ?>package_master/package_list"> Packages </a></li>
                                <li><a href="<?php echo base_url() . "Branch_Package_Discount"; ?>"> Branch Package Discount </a></li>
                                <li><a href="<?php echo base_url(); ?>Package_category/package_category_list"> Package Category </a></li>
                                <li><a href="<?php echo base_url(); ?>Payment_type"> Payment Type</a></li>
                                <li><a href="<?php echo base_url(); ?>phlebo_master/phlebo_list"> Phlebo </a></li>
                                <li><a href="<?php echo base_url(); ?>relation_master/relation_list">Relation Master</a></li>
                                <li><a href="<?php echo base_url(); ?>sample_from/sample_list"> Sample From</a></li>
                                <li><a href="<?php echo base_url(); ?>cms_master/index"> Site Setting </a></li>
                                <li><a href="<?php echo base_url(); ?>cms_master/sms"> SMS Setting </a></li>
                                <li><a href="<?php echo base_url(); ?>source_master/source_list"> Source </a></li>
                                <li><a href="<?php echo base_url(); ?>job_master/all_pushnotification"> Send New Notification</a></li>
                                <li><a href="<?php echo base_url(); ?>location-master/state-list">State</a></li>
                                <li><a href="<?php echo base_url(); ?>test_panel/test_panel_list">Test Panel</a></li>
                                <li><a href="<?php echo base_url(); ?>test-master/test-list"> Test Master </a></li>
                                <li><a href="<?php echo base_url(); ?>test_method/index"> Test Method</a></li>
                                <li><a href="<?php echo base_url(); ?>parameter_master/parameter_list"> Test Parameter </a></li>
                                <li><a href="<?php echo base_url(); ?>test_department_master"> Test Department </a></li>
                                <li><a href="<?php echo base_url(); ?>testimonials_master/index"> Testimonials </a></li>
                                <li><a href="<?php echo base_url(); ?>unit_master"> Unit Master </a></li>
                                <li><a href="<?php echo base_url(); ?>outsource_master/outsource_list"> Outsource Master </a></li>
                                <li><a href="<?php echo base_url() . "LabGroup_List/labgroup_list"; ?>">Lab map</a></li>
                                <li><a href="<?php echo base_url(); ?>Daily_dsr/user_list">Daily Branch Report</a></li>
                                <li><a href="<?php echo base_url() . "Speciality_master/index"; ?>">Speciality Wise Test</a></li>
                                <li><a href="<?= base_url(); ?>Pine_lab_terminal_master/">Pine Labs Terminal Master</a></li>
                                <li><a href="<?php echo base_url() . "Pitch_master"; ?>">Pitch Master</a></li>
                                <li><a href="<?php echo base_url(); ?>Branch_sample_type/index">Test Sample Type</a></li>
                                <li><a href="<?php echo base_url(); ?>Nabltestscope_master/nabltestscope_list">NABL Test Scope Master</a></li>
                                <li><a href="<?php echo base_url(); ?>Test_tat_master/index">Test/Package TAT</a></li>
                                <li><a href="<?php echo base_url(); ?>Techrevenuecollection_master/techrevenuecollection_list">AirmedTech Revenue Collection</a></li>
                                <li><a href="<?php echo base_url(); ?>Callreason_master/index">Call Reason Master</a></li>
                                <li><a href="<?php echo base_url(); ?>Pro_master/pro_list">PRO Master</a></li>
    <!--<li><a href="<?php echo base_url(); ?>banner-group/group-list"> Banner Group</a></li>
    <li><a href="<?php echo base_url(); ?>slider/slider_list">Banner</a></li>-->
    <!--<li><a href="<?php echo base_url(); ?>creative_master/creative_list"> Creative </a></li>-->
                            </ul>
                        </li> 

                                                                        <!--                        <li><a href="<?php echo base_url(); ?>Btob_job_master/pending_list">B2B Bookings</a></li>-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">B2B<span class="caret"></span></a>


                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list">Sample Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/lab_list">Lab Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Lab_bankdeposit/lab_bankdeposit_list">Bank Deposit Details</a></li>

                                <li><a href="<?php echo base_url(); ?>b2b/invoice_master/generate_invoice">Transaction Statement</a></li>
                                <li><a href="<?php echo base_url(); ?>receive_payment/receive_payment_list">Collect cash from Lab</a></li>
                                <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily  Lab Collection Report</a></li>
                                <li><a href="<?php echo base_url() . "b2b/Sample_print_master/sub_sample_list"; ?>">Client Print Permission</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Creditors_master/index">Creditor Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/branchcreditors_all">Creditor Report</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Dailyreport_master/job_received">B2B Daily Collection Report(BETA)</a></li>
                                <li><a href="<?php echo base_url(); ?>receive_payment/b2bcollection_report">B2B Daily Collection Report</a></li>
                            </ul>


                        </li>
                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Report<span class="caret"></span></a>
                            <?php /*    <ul class="dropdown-menu" role="menu">
                              <li><a href="<?php echo base_url(); ?>support_system"> Support System <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                              <li ><a href="<?php echo base_url(); ?>wallet_master/account_history"> Wallet History</a></li>
                              <li ><a href="<?php echo base_url(); ?>customer_master/partner_with_us"> Partner with us</a></li>
                              <li><a href="<?php echo base_url(); ?>SupportDoc_system">Query</a></li>
                              <li ><a href="<?php echo base_url(); ?>user_call_master"> User Call</a></li>
                              <li ><a href="<?php echo base_url(); ?>user_call_master/send_quote_list">Send Quotation List</a></li>
                              <li><a href="<?= base_url(); ?>doctor_req/request_list">Doctor Request</a></li>
                              <li><a href="<?= base_url(); ?>job_master/job_report">Job Report</a></li>
                              <li ><a href="<?php echo base_url(); ?>contact_us_master"> Contact Us <span id="contact_us" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                              <li><a href="<?php echo base_url(); ?>visit_master"> Visit List </a></li>
                              <li><a href="<?php echo base_url(); ?>Phlebo_punchin_punchout"> Phlebotomy Punch In/Out Report </a></li>
                              <li><a href="<?php echo base_url(); ?>Just_dial_master/index"> Just Dial Data </a></li>
                              </ul> */ ?>
                            <ul class="dropdown-menu" role="menu"> 
                                <li ><a href="<?php echo base_url(); ?>contact_us_master"> Contact Us <span id="contact_us" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li><a href="<?= base_url(); ?>doctor_req/request_list">Doctor Request</a></li>
                                <li><a href="<?php echo base_url(); ?>Just_dial_master/index"> Just Dial Data </a></li>
                                <li><a href="<?= base_url(); ?>job_master/job_report">Booking Report</a></li>
                                <li ><a href="<?php echo base_url(); ?>customer_master/partner_with_us"> Partner with us</a></li>
                                <li><a href="<?php echo base_url(); ?>Phlebo_punchin_punchout"> Phlebotomy Punch In/Out Report </a></li>
                                <li><a href="<?= base_url(); ?>phlebo_report/phlebo_report">Phlebo Visit Report</a></li>
                                <li><a href="<?php echo base_url(); ?>SupportDoc_system">Query</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pathoplus Reports</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url() . "dailybill_register_report/report"; ?>"> Daily Bill Register </a></li>
                                        <li><a href="<?php echo base_url() . "accountdailybillregister_report/report"; ?>"> AccountWise Daily Bill Register </a></li>
                                        <li><a href="<?php echo base_url() . "paymentdue_registerreport/report"; ?>">Payment Due Register </a></li>
                                        <li><a href="<?php echo base_url() . "discount_registerreport/report"; ?>"> Discount Register </a></li>
                                        <li><a href="<?php echo base_url() . "doctor_summary_report/report"; ?>"> Referring Dr.Summary Report </a></li>
                                        <li><a href="<?php echo base_url() . "referringdr_register_report/report"; ?>"> Referring Dr. Wise Register </a></li>
                                        <li><a href="<?php echo base_url() . "threec_register_report/report"; ?>"> 3c Register</a></li>

                                    </ul>
                                </li>
                                <li ><a href="<?php echo base_url(); ?>Send_sms/index">SMS Notification</a></li>
                                <li><a href="<?php echo base_url(); ?>support_system"> Support System <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li ><a href="<?php echo base_url(); ?>user_call_master/send_quote_list">Send Quotation List</a></li>
                                <li ><a href="<?php echo base_url(); ?>user_call_master"> User Call</a></li>
                                <li><a href="<?php echo base_url(); ?>visit_master"> Visit List </a></li>
                                <li ><a href="<?php echo base_url(); ?>wallet_master/account_history"> Wallet History</a></li>
                                <li><a href="<?php echo base_url(); ?>jobwallet_master/jobswallet_list">Booking Wallet history</a></li>
                                <li><a href="<?php echo base_url(); ?>lead_manage_master">Manage Lead</a></li>
                                <li><a href="<?= base_url(); ?>clint_master/client_list">Client Manage</a></li>
                                <li><a href="<?= base_url(); ?>Feedback_master/Feedback_list">Feedback</a></li>
                                <li><a href="<?= base_url(); ?>Health_advisor">Health Advisor</a></li>
                                <li><a href="<?= base_url(); ?>report_master/test_status">Patient Report Status</a></li>
                                <li><a href="<?= base_url() . "tat_report_master/tat_report"; ?>">TAT Report</a></li>
                                <li><a href="<?= base_url() . "Test_parameter_map/index"; ?>">Parameter Map</a></li>
                                <li><a href="<?= base_url(); ?>report_master/techlogin_report">Tech login report</a></li>
                                <li><a href="<?= base_url(); ?>Doctor_sample_report/index">Doctor Sample Collection Report</a></li>
                                <li><a href="<?= base_url(); ?>Hardcopydelivery_report/index">Hard Copy Delivery Status Report</a></li>
                                <li><a href="<?= base_url(); ?>job_report/Tat_report">New Tat Report</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Manage<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">

                                <?php if ($login_data['email'] == "brijesh@virtualheight.com") { ?>
                                    <li><a href="<?php echo base_url(); ?>leave_applications"> Leave Application <span class="sr-only">(current)</span></a></li>
                                <?php } ?>
                                <li><a href="<?php echo base_url(); ?>phlebo_master_page"> Phlebo Master Manage <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>customer-master/customer-list"> Customer Manage <span class="sr-only">(current)</span></a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Phlebo</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url(); ?>Phlebo"> Manage </a></li>
                                        <li><a href="<?php echo base_url(); ?>Timeslot_master"> Time Slot </a></li>
                                        <li><a href="<?php echo base_url(); ?>Day_master/day_list"> Days </a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>phlebo/visit-request"> Visit Request <span class="sr-only">(current)</span></a></li>
                            </ul>
                        </li>
            <!--<li class="active"><a href="<?php echo base_url(); ?>issue_master"> Queries and Issues <span class="sr-only">(current)</span></a></li> -->
                        <li ><a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking"> Tele Caller</a></li>
                        <li ><a href="<?php echo base_url(); ?>test-master/price-list"> Price List</a></li>
    <!--                        <li><a href="<?php echo base_url(); ?>registration_admin">Register & Test</a></li>-->
    <!--                        <li><a href="<?php echo base_url(); ?>remains_book_master">Remains Book User</a></li>-->
                        <!-- <li><a href="<?php echo base_url(); ?>Data_autorisation_scnd/index">Data Capture</a></li> -->
                        <li><a href="<?php echo base_url(); ?>Data_autorisation/index">DC2</a></li>
                        <?php /* if (in_array($login_data["id"], array('11', '12', '13', '17', '9'))) { ?>   <li><a href="<?php echo base_url(); ?>Dailyreport_master/job_received">Daily Collection Report (Beta)</a></li>
                          <?php } */ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Payment<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php /* <li><a href="<?php echo base_url(); ?>job_report/mypayment"> My Payment <span class="sr-only">(current)</span></a></li>
                                  <li><a href="<?php echo base_url(); ?>job_report/payment"> My Branch <span class="sr-only">(current)</span></a></li>
                                  <li><a href="<?php echo base_url(); ?>job_report/mypayment_with_type"> Type Wise <span class="sr-only">(current)</span></a></li> */ ?>
                                <li><a href="<?php echo base_url(); ?>job_report/jobpayment"> Booking Payment <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>job_report/branchpaymentnew2"> Daily Collection <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>doctor_report/payment"> Doctor Payment<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>branch_report/payment"> Branch Business Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>branch_report/report"> Branch Collection Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>report_master"> Day Month Year wise Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_master/expense_list">Expense Manage</a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_master/expense_report">Expense Report</a></li>
                                <li><a href="<?php echo base_url(); ?>bill_master/bill_list">Bill Manage </a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/panel"> Panel Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/branchcreditors_all"> Creditor Report</a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/test_report"> Test Report</a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/doctor_test_report">Doctor Test Report</a></li>
                                <li><a href="<?php echo base_url(); ?>Dailyreport_master/job_received">Daily Collection Report (Beta)</a></li>
                                <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily B2B Lab Collection Report</a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/outsource_report">Outsource Report</a></li>
                                <li><a href="<?php echo base_url(); ?>Business_Report/business_list">Business Report</a></li>

                                <li><a href="<?php echo base_url(); ?>Airmed_tech_report">Airmed Tech report</a></li>
                                <li><a href="<?php echo base_url(); ?>Branch_collections">Branch Collection Logs</a></li>

                            </ul>
                        </li>

                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Inventory <span id="maininventry" > </span><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li ><a href="<?php echo base_url(); ?>inventory/Bank_master/bank_list">Bank Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Consumes_master/consumes_list">Lab Consumables Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Stationary_master/stationary_list">Stationary Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list">Machine Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"> Indent Request <span id="indexnrequi" ></span></a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Intent_Inward/inward_list">Inward <span id="poindeeard" ></span></a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Tax_master/index">Tax Master</a></li>
                                <li ><a href="<?php echo base_url() . "inventory/intent_genrate/index"; ?>">PO Request <span id="indentpore" ></span></a></li>
                                <li ><a href="<?php echo base_url() . "inventory/stock_master"; ?>">Stock</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/indent_usereagent/">Use Reagent</a></li>
                                <li ><a href="<?php echo base_url() . "inventory/Handover_item"; ?>">Handover Item</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/stock_itemmaster">Stock Master</a></li> 

                            </ul>

                        </li>

                    <?php } ?>
                    <?php if ($login_data["type"] == 2) { ?>
                        <li class="dropdown">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Booking Master <span id="total_pending_count" class="label label-warning"> </span><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>job-master/pending-list"> All Bookings <span id="pending_count" class="label label-warning"> </span> <span class="sr-only">(current)</span></a></li>
    <!--                                <li><a href="<?php echo base_url(); ?>add_result/sample_collected_list">Job Test Result</a></li>-->
                                <li><a href="<?php echo base_url(); ?>job_master/spam_job_list">Spam Booking</a></li>
                                <li><a href="<?php echo base_url(); ?>job_report/mypayment">My Booking Payment</a></li>
                                <li><a href="<?php echo base_url() . "doctor_appointment?startdate=" . date("d-m-Y") . "&end_date=" . date("d-m-Y") . ""; ?>">Doctor Appointment</a></li>
                            </ul>
                        </li>
    <!--                        <li><a href="<?php echo base_url(); ?>Btob_job_master/pending_list">B2B Bookings</a></li>-->
                        <li class="active"><a href="<?php echo base_url(); ?>job-master/prescription-report"> Prescription <span id="prescription_count" class="label label-warning"> <span class="sr-only">(current)</span></a></li>
                        <?php /* <li ><a href="<?php echo base_url(); ?>job_master/Package_test_inquiry_list">All Inquiries <span id="test_package_count" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li> */ ?>
                        <li class="dropdown" style="display:none;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Web Inquiries <span id="inquiry_total" class="label label-danger"> </span> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li ><a href="<?php echo base_url(); ?>job_master/Package_test_inquiry_list">All Inquiries <span id="test_package_count" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li>
                                <li ><a href="<?php echo base_url(); ?>customer_master/Package_inquiry_list"> Package Inquiry  <span id="package_inquiry" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li>

                            </ul>
                        </li> 

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Master <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <!-- <li><a href="<?php echo base_url(); ?>Admin_manage/user_list"> Admin User Manage </a></li> -->
                                <li><a href="<?php echo base_url() . "Speciality_master/index"; ?>">Speciality Wise Test</a></li>
                                <li><a href="<?php echo base_url() . "Pitch_master"; ?>">Pitch Master</a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_category_master/expense_category_list"> Expenses Category </a></li>
                                <li><a href="<?php echo base_url(); ?>test-master/test-list"> Test Master </a></li>
                                <li><a href="<?php echo base_url(); ?>package_master/package_list"> Packages </a></li>
                            </ul>
                        </li> 
                        <!-- <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">B2B<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list">Sample Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/lab_list">Lab Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Lab_bankdeposit/lab_bankdeposit_list">Bank Deposit Details</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/invoice_master/generate_invoice">Transaction Statement</a></li>
                                <li><a href="<?php echo base_url(); ?>receive_payment/receive_payment_list">Collect cash from Lab</a></li>
                                <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily Lab Collection Report</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Creditors_master/index">Creditor Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/branchcreditors_all">Creditor Report</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Dailyreport_master/job_received">B2B Daily Collection Report(BETA)</a></li>
                                <li><a href="<?php echo base_url(); ?>receive_payment/b2bcollection_report">B2B Daily Collection Report</a></li>
                                <li><a href="<?php echo base_url() . "Branch_Package_Discount"; ?>"> Branch Package Discount </a></li>
                               
                            </ul>
                        </li> -->

                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Report<span class="caret"></span></a>
                            <?php /*    <ul class="dropdown-menu" role="menu">
                              <li><a href="<?php echo base_url(); ?>support_system"> Support System <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                              <li ><a href="<?php echo base_url(); ?>wallet_master/account_history"> Wallet History</a></li>
                              <li ><a href="<?php echo base_url(); ?>customer_master/partner_with_us"> Partner with us</a></li>
                              <li><a href="<?php echo base_url(); ?>SupportDoc_system">Query</a></li>
                              <li ><a href="<?php echo base_url(); ?>user_call_master"> User Call</a></li>
                              <li ><a href="<?php echo base_url(); ?>user_call_master/send_quote_list">Send Quotation List</a></li>
                              <li><a href="<?= base_url(); ?>doctor_req/request_list">Doctor Request</a></li>
                              <li><a href="<?= base_url(); ?>job_master/job_report">Job Report</a></li>
                              <li ><a href="<?php echo base_url(); ?>contact_us_master"> Contact Us <span id="contact_us" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                              <li><a href="<?php echo base_url(); ?>visit_master"> Visit List </a></li>
                              <li><a href="<?php echo base_url(); ?>Phlebo_punchin_punchout"> Phlebotomy Punch In/Out Report </a></li>
                              <li><a href="<?php echo base_url(); ?>Just_dial_master/index"> Just Dial Data </a></li>
                              </ul> */ ?>
                            <ul class="dropdown-menu" role="menu">
                                <li ><a href="<?php echo base_url(); ?>contact_us_master"> Contact Us <span id="contact_us" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li><a href="<?= base_url(); ?>doctor_req/request_list">Doctor Request</a></li>
                                <li><a href="<?php echo base_url(); ?>Just_dial_master/index"> Just Dial Data </a></li>
                                <li><a href="<?= base_url(); ?>job_master/job_report">Booking Report</a></li>
                                <li ><a href="<?php echo base_url(); ?>customer_master/partner_with_us"> Partner with us</a></li>
                                <li><a href="<?php echo base_url(); ?>Phlebo_punchin_punchout"> Phlebotomy Punch In/Out Report </a></li>
                                <li><a href="<?= base_url(); ?>phlebo_report/phlebo_report">Phlebo Visit Report</a></li>
                                <li><a href="<?php echo base_url(); ?>SupportDoc_system">Query</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pathoplus Reports</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url() . "dailybill_register_report/report"; ?>"> Daily Bill Register </a></li>
                                        <li><a href="<?php echo base_url() . "accountdailybillregister_report/report"; ?>"> AccountWise Daily Bill Register </a></li>
                                        <li><a href="<?php echo base_url() . "paymentdue_registerreport/report"; ?>">Payment Due Register </a></li>
                                        <li><a href="<?php echo base_url() . "discount_registerreport/report"; ?>"> Discount Register </a></li>
                                        <li><a href="<?php echo base_url() . "doctor_summary_report/report"; ?>"> Referring Dr.Summary Report </a></li>
                                        <li><a href="<?php echo base_url() . "referringdr_register_report/report"; ?>"> Referring Dr. Wise Register </a></li>
                                        <li><a href="<?php echo base_url() . "threec_register_report/report"; ?>"> 3c Register</a></li>

                                    </ul>
                                </li>
                                <li ><a href="<?php echo base_url(); ?>Send_sms/index">SMS Notification</a></li>
                                <li><a href="<?php echo base_url(); ?>support_system"> Support System <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li ><a href="<?php echo base_url(); ?>user_call_master/send_quote_list">Send Quotation List</a></li>
                                <li ><a href="<?php echo base_url(); ?>user_call_master"> User Call</a></li>
                                <li><a href="<?php echo base_url(); ?>visit_master"> Visit List </a></li>
                                <li><a href="<?php echo base_url(); ?>lead_manage_master">Manage Lead</a></li>
                                <li><a href="<?= base_url(); ?>clint_master/client_list">Client Manage</a></li>
                                <li><a href="<?= base_url(); ?>Feedback_master/Feedback_list">Feedback</a></li>
                                <li><a href="<?= base_url(); ?>report_master/test_status">Patient Report Status</a></li>
                                <li><a href="<?= base_url() . "tat_report_master/tat_report"; ?>">TAT Report</a></li>
                                <li><a href="<?= base_url(); ?>report_master/techlogin_report">Tech login report</a></li>
                                <li><a href="<?= base_url(); ?>Doctor_sample_report/index">Doctor Sample Collection Report</a></li>
                                <li><a href="<?= base_url(); ?>Hardcopydelivery_report/index">Hard Copy Delivery Status Report</a></li>
                                 <li><a href="<?= base_url(); ?>job_report/Tat_report">New Tat Report</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Manage<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
    <!--                                <li><a href="<?php echo base_url(); ?>leave_applications"> Leave Application <span class="sr-only">(current)</span></a></li>-->
                                <li><a href="<?php echo base_url(); ?>phlebo_master_page"> Phlebo Master Manage <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>customer-master/customer-list"> Customer Manage <span class="sr-only">(current)</span></a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Phlebo</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url(); ?>Phlebo"> Manage </a></li>
                                        <li><a href="<?php echo base_url(); ?>Timeslot_master"> Time Slot </a></li>
                                        <li><a href="<?php echo base_url(); ?>Day_master/day_list"> Days </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
            <!--<li class="active"><a href="<?php echo base_url(); ?>issue_master"> Queries and Issues <span class="sr-only">(current)</span></a></li> -->
                        <li ><a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking"> Tele Caller</a></li>
                        <li ><a href="<?php echo base_url(); ?>test-master/price-list"> Price List</a></li>
    <!--                        <li><a href="<?php echo base_url(); ?>registration_admin">Register & Test</a></li>-->
    <!--                        <li><a href="<?php echo base_url(); ?>remains_book_master">Remains Book User</a></li>-->
                        <li><a href="<?php echo base_url(); ?>Data_autorisation_scnd/index">Data Capture</a></li>
                        <li><a href="<?php echo base_url(); ?>Data_autorisation/index">DC2</a></li>
    <!--                        <li><a href="<?php echo base_url(); ?>report_master/creditors_all"> Creditor Report</a></li>-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Payment<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php /* <li><a href="<?php echo base_url(); ?>job_report/mypayment"> My Payment <span class="sr-only">(current)</span></a></li>
                                  <li><a href="<?php echo base_url(); ?>job_report/payment"> My Branch <span class="sr-only">(current)</span></a></li>
                                  <li><a href="<?php echo base_url(); ?>job_report/mypayment_with_type"> Type Wise <span class="sr-only">(current)</span></a></li> */ ?>
                                <li><a href="<?php echo base_url(); ?>job_report/jobpayment"> Booking Payment <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>job_report/branchpaymentnew2"> Daily Collection <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>doctor_report/payment"> Doctor Payment<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>branch_report/payment"> Branch Business Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>branch_report/report"> Branch Collection Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>report_master"> Day Month Year wise Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_master/expense_list">Expense Manage</a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_master/expense_report">Expense Report</a></li>
                                <li><a href="<?php echo base_url(); ?>bill_master/bill_list">Bill Manage </a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/panel"> Panel Report<span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/branchcreditors_all"> Creditor Report</a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/test_report"> Test Report</a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/doctor_test_report">Doctor Test Report</a></li>
                                <li><a href="<?php echo base_url(); ?>Dailyreport_master/job_received">Daily Collection Report (Beta)</a></li>
                                <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily B2B Lab Collection Report</a></li>
                                <li><a href="<?php echo base_url(); ?>report_master/outsource_report">Outsource Report</a></li>
                                <li><a href="<?php echo base_url(); ?>Business_Report/business_list">Business Report</a></li>
                                <li><a href="<?php echo base_url(); ?>Airmed_tech_report">Airmed Tech report</a></li>
                            </ul>
                        </li>   
                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Inventory <span id="maininventry" > </span><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li ><a href="<?php echo base_url(); ?>inventory/Bank_master/bank_list">Bank Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Consumes_master/consumes_list">Lab Consumables Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Stationary_master/stationary_list">Stationary Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list">Machine Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"> Indent Request <span id="indexnrequi" ></span></a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Intent_Inward/inward_list">Inward <span id="poindeeard" ></span></a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Tax_master/index">Tax Master</a></li>
                                <li ><a href="<?php echo base_url() . "inventory/intent_genrate/index"; ?>">PO Request <span id="indentpore" ></span></a></li>
                                <li ><a href="<?php echo base_url() . "inventory/stock_master"; ?>">Stock</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/indent_usereagent/">Use Reagent</a></li>
                                <li ><a href="<?php echo base_url() . "inventory/Handover_item"; ?>">Handover Item</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/stock_itemmaster">Stock Master</a></li> 

                            </ul>

                        </li>
                    <?php } ?>
                    <?php if ($login_data["type"] == 5) { ?>
                        <li><a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking">Registration</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Reporting Master<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>job-master/pending-list"> Reporting </a></li>
    <!--                                <li><a href="<?php echo base_url(); ?>add_result/sample_collected_list">Reporting Result</a></li>-->
                            </ul>
                        </li>
    <!--                        <li><a href="<?php echo base_url(); ?>Btob_job_master/pending_list">B2B Bookings</a></li>
                        <li><a href="<?php echo base_url(); ?>job_master/find_job_list"> Find Booking (Print Report) </a></li>-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">B2B<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list">Sample Master</a></li>
                                <li><a href="<?php echo base_url(); ?>b2b/Lab_bankdeposit/lab_bankdeposit_list">Bank Deposit Details</a></li>

                                <li><a href="<?php echo base_url(); ?>b2b/invoice_master/generate_invoice">Transaction Statement</a></li>
                                <li><a href="<?php echo base_url(); ?>receive_payment/receive_payment_list">Collect cash from Lab</a></li>
                                <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily Lab Collection Report</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Payment<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>job_report/branchpaymentnew2"> Daily Collection <span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>Dailyreport_master/job_received">Daily Collection Report (Beta)</a></li>
                                <li><a href="<?php echo base_url(); ?>receive_payment/b2bcollection_report">B2B Daily Collection Report</a></li>
                                <?php if (in_array($login_data["id"], array(38))) { ?> 
                                    <li class="dropdown-submenu">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pathoplus Reports</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url() . "dailybill_register_report/report"; ?>"> Daily Bill Register </a></li>
                                            <li><a href="<?php echo base_url() . "accountdailybillregister_report/report"; ?>"> AccountWise Daily Bill Register </a></li>
                                            <li><a href="<?php echo base_url() . "paymentdue_registerreport/report"; ?>">Payment Due Register </a></li>
                                            <li><a href="<?php echo base_url() . "discount_registerreport/report"; ?>"> Discount Register </a></li>
                                            <li><a href="<?php echo base_url() . "doctor_summary_report/report"; ?>"> Referring Dr.Summary Report </a></li>
                                            <li><a href="<?php echo base_url() . "referringdr_register_report/report"; ?>"> Referring Dr. Wise Register </a></li>
                                            <li><a href="<?php echo base_url() . "threec_register_report/report"; ?>"> 3c Register</a></li>
                                            <li><a href="<?php echo base_url(); ?>Business_Report/business_list">Business Report</a></li>

                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php /* <li><a href="<?php echo base_url(); ?>job_report/mypayment"> My Payment <span class="sr-only">(current)</span></a></li>
                                  <li><a href="<?php echo base_url(); ?>job_report/payment"> My Branch <span class="sr-only">(current)</span></a></li>
                                  <li><a href="<?php echo base_url(); ?>job_report/mypayment_with_type"> Type Wise <span class="sr-only">(current)</span></a></li> */ ?>
    <!--                                <li><a href="<?php echo base_url(); ?>job_report/jobpayment"> Job Payment <span class="sr-only">(current)</span></a></li>
    <li><a href="<?php echo base_url(); ?>job_report/branchpaymentnew2"> Daily Collection <span class="sr-only">(current)</span></a></li>
    <li><a href="<?php echo base_url(); ?>report_master"> Day Month Year wise Report<span class="sr-only">(current)</span></a></li>
    <li><a href="<?php echo base_url(); ?>Expense_master/expense_list">Expense Manage</a></li>
    <li><a href="<?php echo base_url(); ?>report_master/test_report"> Test Report</a></li>
    <li><a href="<?php echo base_url(); ?>report_master/doctor_test_report">Doctor Test Report</a></li>
    </li>
                                --> 
                                <li><a href="<?php echo base_url(); ?>report_master/branchcreditors_all"> Creditor Report</a>	
                                    <?php if (in_array($login_data["id"], array(123, 85, 12, 45))) { ?>
                                    <li><a href="<?php echo base_url(); ?>Dailyreport_master/job_received">Daily Collection Report (Beta)</a></li>
                                <?php } ?>
                            </ul> 
                        </li>
                        <li><a href="<?= base_url(); ?>job_master/job_report">Report</a></li>
                        <li><a href="<?php echo base_url(); ?>Data_autorisation_scnd/index">Data Capture</a></li>
                        <li><a href="<?php echo base_url(); ?>Data_autorisation/index">DC2</a></li>
                        <li><a href="<?php echo base_url(); ?>job_master/outsource">Outsource Booking</a></li>
                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Inventory <span id="maininventry" > </span> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                               <!--  <li ><a href="<?php echo base_url(); ?>inventory/Bank_master/bank_list">Bank Master</a></li> -->
                                <li ><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"> Indent Request <span id="indexnrequi" ></span></a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Intent_Inward/inward_list"> Inward <span id="poindeeard" ></span></a></li>
                                <!--  <li ><a href="<?php echo base_url(); ?>inventory/Tax_master/index">Tax Master</a></li> -->
                                <li ><a href="<?php echo base_url() . "inventory/intent_genrate/index"; ?>">PO Request <span id="indentpore" ></span></a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list">Machine Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Consumes_master/consumes_list">Lab Consumables Master</a></li>
                                <li ><a href="<?php echo base_url(); ?>inventory/Stationary_master/stationary_list">Stationary Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/indent_usereagent/">Use Reagent</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Handover_item">Handover Item</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/stock_itemmaster">Stock Master</a></li> 
                                <li><a href="<?php echo base_url() . "inventory/stock_master"; ?>">Stock</a></li>
                            </ul>
                        </li>
    <!--                        <li><a href="<?php echo base_url(); ?>leave_applications">Leave Application</a></li>-->


                    <?php } ?>
                    <?php if ($login_data["type"] == 6) { ?>

                        <?php if (in_array($sub, $btype)) { ?>
                            <li><a href="<?php echo base_url(); ?>sample_booking/TelecallerCallBooking">Registration</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Reporting Master<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo base_url(); ?>tech/job_master/pending_list"> Reporting </a></li>
        <!--                                <li><a href="<?php echo base_url(); ?>add_result/sample_collected_list">Reporting Result</a></li>-->
                                </ul>
                            </li>
                            <li><a href="<?php echo base_url(); ?>tech/Data_autorisation_scnd/index">Data Capture</a></li>
                            <li><a href="<?php echo base_url(); ?>job_report/mypayment">My Booking Payment</a></li>

                            <li><a href="<?php echo base_url(); ?>Airmed_tech_report/index">Payment Report</a></li>

                        <?php } else { ?>
                            <li><a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking">Registration</a></li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">B2B<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list">Sample Master</a></li>
                                    <li><a href="<?php echo base_url(); ?>b2b/Logistic/lab_list">Lab Master</a></li>
                                    <li><a href="<?php echo base_url(); ?>b2b/Lab_bankdeposit/lab_bankdeposit_list">Bank Deposit Details</a></li>
                                    <li><a href="<?php echo base_url(); ?>b2b/invoice_master/generate_invoice">Transaction Statement</a></li>
                                    <li><a href="<?php echo base_url(); ?>receive_payment/receive_payment_list">Collect cash from Lab</a></li>
                                    <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily Lab Collection Report</a></li>
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Reporting Master<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo base_url(); ?>job-master/pending-list"> Reporting </a></li>
        <!--                                <li><a href="<?php echo base_url(); ?>add_result/sample_collected_list">Reporting Result</a></li>-->
                                </ul>
                            </li>

                                                                <!--                        <li><a href="<?php echo base_url(); ?>Btob_job_master/pending_list">B2B Bookings</a></li>-->
                                                                  <!-- <li><a href="<?php echo base_url(); ?>job_master/find_job_list"> Find Booking (Print Report) </a></li>-->

                            <li><a href="<?php echo base_url(); ?>Data_autorisation_scnd/index">Data Capture</a></li>
                            <li><a href="<?php echo base_url(); ?>Data_autorisation/index">DC2</a></li>
                            <?php /* <li><a href="<?php echo base_url(); ?>report_master/creditors_all"> Creditor Report</a></li> */ ?>
                            <li><a href="<?php echo base_url(); ?>job_report/mypayment">My Booking Payment</a></li>
                            <li><a href="<?php echo base_url(); ?>job_master/outsource">Outsource Booking</a></li>
                            <!-- <li><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index">Indent Request</a></li> -->
                            <li class="dropdown">

                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Inventory<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <!-- <li ><a href="<?php echo base_url(); ?>inventory/Bank_master/bank_list">Bank Master</a></li> -->
                                    <li ><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"> Indent Request</a></li>
                                    <li ><a href="<?php echo base_url(); ?>inventory/Intent_Inward/inward_list">Inward</a></li>

                                    <li ><a href="<?php echo base_url() . "inventory/intent_genrate/index"; ?>">PO Request</a></li>
                                </ul>
                            </li>
        <!--                            <li><a href="<?php echo base_url(); ?>leave_applications">Leave Application</a></li>-->
                            <?php
                        }
                    }
                    ?>
                    <?php if ($login_data["type"] == 7) { ?>
                        <li><a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking">Registration</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Reporting Master<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>job-master/pending-list"> Reporting </a></li>
                            </ul>
                        </li> 
                        <?php /* <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">B2B<span class="caret"></span></a>
                          <ul class="dropdown-menu">
                          <li><a href="<?php echo base_url(); ?>b2b/Logistic/sample_list">Sample Master</a></li>
                          <li><a href="<?php echo base_url(); ?>b2b/Lab_bankdeposit/lab_bankdeposit_list">Bank Deposit Details</a></li>

                          <li><a href="<?php echo base_url(); ?>b2b/invoice_master/generate_invoice">Transaction Statement</a></li>
                          <li><a href="<?php echo base_url(); ?>receive_payment/receive_payment_list">Collect cash from Lab</a></li>
                          <li><a href="<?php echo base_url(); ?>daily_lab_report/report">Daily Lab Collection Report</a></li>
                          </ul>
                          </li> */ ?>
                      <!--  <li><a href="<?php echo base_url(); ?>job_master/find_job_list"> Find Booking (Print Report) </a></li> -->
                        <li><a href="<?php echo base_url(); ?>job_report/mypayment">My Booking Payment</a></li>
                        <li><a href="<?php echo base_url(); ?>report_master/branchcreditors_all"> Creditor Report</a></li>
    <!--                        <li><a href="<?php echo base_url(); ?>leave_applications">Leave Application</a></li>-->
                        <?php /* <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Inventory<span class="caret"></span></a>
                          <ul class="dropdown-menu" role="menu">
                          <li ><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index"> Indent Request</a></li>
                          <li ><a href="<?php echo base_url()."inventory/intent_genrate/index"; ?>">PO Request</a></li>
                          <li ><a href="<?php echo base_url(); ?>inventory/Intent_Inward/inward_list">Inward</a></li>
                          </ul>
                          </li> */ ?>
                    <?php } ?>

                    <?php if ($login_data["type"] == 10) { ?>
                       
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Reporting Master<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>job-master/pending-list"> Reporting </a></li>
                            </ul>
                        </li> 
                      
                   
                    <?php } ?>
<!--					<li><a href="<?php echo base_url(); ?>job-master/pending-list"> All Jobs <span id="pending_count" class="label label-warning"> </span> -->
                    <?php if ($login_data["type"] != 10) { if (!in_array($sub, $btype)) { ?>  <li><a href="<?php echo base_url(); ?>bill_master/bill_list">Bill Manage </a></li><?php } } ?>

                    <li><a href="<?php echo base_url(); ?>login/logout">Sign out</a></li>
            </div><!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->



                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?php echo base_url(); ?>user_assets/images/user.png" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php
                                $words = explode(" ", $login_data["name"]);
                                $acronym = "";

                                foreach ($words as $w) {
                                    $acronym .= ucfirst($w[0]);
                                }
                                echo $acronym;
                                ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->  
                            <li class="user-header">
                                <!----<img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="" style="  width: 100%;" alt="User Image" />---->
                                <p>
                                    <?php echo ucfirst($login_data["name"]); ?>
                                </p>
                                <h3>Today's Collection <span style="color:#dd4b39;" id="todays_collection">Rs.0.00 </span></h3>
                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo base_url(); ?>admin-profile-update" class="btn btn-default btn-flat prfl_red_btn"><i class="fa fa-briefcase"></i>Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>login/logout" class="btn btn-default btn-flat prfl_red_btn"><i class="fa fa-power-off"></i>Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-custom-menu -->
        </div><!-- /.container-fluid -->
    </nav>
</header>
<!-- Full Width Column -->
<div class="content-wrapper">
    <div class="">
