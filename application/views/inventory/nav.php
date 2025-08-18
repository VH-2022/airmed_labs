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
    <nav class="navbar navbar-static-top">
        <div class="">
            <div class="navbar-header">
                        <!--<span id="pending_count_2"/> <span id="pending_count_1"/>-->
                <span id=""></span> <span id=""/></span>
                <a href="<?php echo base_url(); ?>inventory/Dashboard/index" class="navbar-brand" style="padding-top: 0px;padding-bottom: 0px;"><img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="logo" alt="AirmedLabs" style="width:187px;height:50px"></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php if ($login_data["type"] == 8) { ?>
                       <!--   <li><a href="<?php echo base_url(); ?>inventory/Stock_master/stock/">Stock Master</a></li>
                            <li><a href="<?php echo base_url(); ?>inventory/Test_item/test_list/">Test Wise Item</a></li> -->

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Master<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li ><a href="<?php echo base_url(); ?>inventory/Bank_master/bank_list">Bank Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Brand_master/index">Brand Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Consumes_master/consumes_list">Lab Consumables Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Item_master/item_list">Reagent Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Stationary_master/stationary_list">Stationary Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/stock_master">Stock Add</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Unit_master/unit_list">Unit Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Vendor_master/index">Vendor Master</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Tax_master/index">Tax Master</a></li>
                                <li><a href="<?php echo base_url(); ?>Expense_category_master/expense_category_list"> Expenses Category </a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo base_url(); ?>inventory/Machine/machine_list">Machine Master</a></li> 
                         <li><a href="<?php echo base_url(); ?>inventory/Intent_Request/sub_index">Indent Request <span id="indexnrequi" ></span></a></li>
                        <li><a href="<?php echo base_url() . "inventory/intent_genrate/index"; ?>">PO Request <span id="poindexnrequi" ></span></a></li>
                        <li><a href="<?php echo base_url(); ?>inventory/Intent_Inward/inward_list">Inward <span id="poindeeard" ></span></a></li>
    <!--                            <li><a href="<?php echo base_url(); ?>inventory/Stock_master/stock">Stock Master</a></li>-->
                        <li><a href="<?php echo base_url(); ?>bill_master/bill_list">Bill Master</a></li>
                        <li><a href="<?php echo base_url(); ?>inventory/stock_itemmaster">Stock Master</a></li> 
                        <li><a href="<?php echo base_url(); ?>inventory/intent_genrate/vendor_qout_list">Vendor Quotation</a></li>
                     
                             <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                  <li><a href="<?php echo base_url(); ?>inventory/Stock_issue_report/index">Stock/Issue Report</a></li>
                                     <li><a href="<?php echo base_url(); ?>inventory/Stock_issue_report_daily">Daily Stock/Issue Report</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Issue Master<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url(); ?>inventory/indent_usereagent/">Use Reagent</a></li>
                                <li><a href="<?php echo base_url(); ?>inventory/Handover_item">Handover Item</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>

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
                                <p>
                                    <?php echo ucfirst($login_data["name"]); ?>
                                </p>
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
