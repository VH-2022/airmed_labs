<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function is_loggedin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return (bool) $CI->session->userdata('logged_in');
}

function logindata() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('logged_in');
}

function is_userloggedin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return (bool) $CI->session->userdata('logged_in_user');
}

function is_lablogin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('lab_logged_in');
}

function loginuser() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('logged_in_user');
}

function is_labloggedin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return (bool) $CI->session->userdata('lab_logged_in');
}

function loginlab() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('lab_logged_in');
}

function is_hrmlogin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('hrm_logged_in');
}

function is_doctorlogin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('doctor_logged_in');
}

function is_receptionlogin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('reception_logged_in');
}
function is_pathologist() {
    $CI = & get_instance();

    $CI->load->library('session');

    return  $CI->session->userdata('pathologist_logged_in');
}

function is_vendorlogin() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('vendor_logged_in');
}

?>