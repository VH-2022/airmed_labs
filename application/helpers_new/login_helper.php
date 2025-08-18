<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function is_loggedin() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With");
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

function loginuser() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('logged_in_user');
}

?>