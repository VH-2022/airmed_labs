<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email {

    function get_design($content) {
        $header = '<div style="background:#a3a3a3 !important;font-family: ">
<script src="https://use.fontawesome.com/44049e3dc5.js"></script>
    <div style="background: #6b6b6b none repeat scroll 0 0;float: left;padding: 7%;">
        <div style="border:1px solid #f1f1f1;border-bottom:1px solid #67B1A3;padding:1.5%;width:100%;float:left;background:#fff;">
            <div style="float:left;width:64%;">
                <img alt="" src="' . base_url() . 'user_assets/images/airmed_logo_red.png" style="height: 55px;width:auto;"/>
            </div>
            <div style="float:right;text-align: right;width:33%;padding-top:7px;">
            </div>
        </div>
        <div style="padding:1.5%;width:100%;float:left;background-color:#fff;border:1px solid #f1f2f3;">';

        $footer = '</div>
        <div style="width:100%;float:left;background:#F5F5F5;padding:1.5%;color:#828282;font-size:12px;border:1px solid #dfdfdf;text-align:center;">
            <a href="' . base_url() . 'user_master/about_us" style="color:#515151;text-decoration:none;">About Us</a> |
            <a href="' . base_url() . 'user_master/contact_us" style="color:#515151;text-decoration:none;">Contact Us</a> |
            <a href="' . base_url() . 'user_login" style="color:#515151;text-decoration:none;">Login</a> |
            <a href="' . base_url() . 'user_master/collection" style="color:#515151;text-decoration:none;">Home-collection</a> |
            <a href="https://www.facebook.com/airmedpathlabs" style="color:#515151;text-decoration:none;"><i aria-hidden="true"><img src="http://websitedemo.co.in/phpdemoz/patholab/user_assets/images/face_book_icon1.jpeg" style="height:15px;width:15px;"></i></a>                  
        </div>
        <div style="width:100%;float:left;background:#333333;padding:1.5%;color:#ccc;font-size:12px;border:1px solid #323232;text-align:center;">
            Copyright @ 2016-17 AirmedLabs. All rights reserved
        </div>
    </div>
</div>';
        return $header . $content . $footer;
    }

}
