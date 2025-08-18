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

function loginuser() {
    $CI = & get_instance();

    $CI->load->library('session');

    return $CI->session->userdata('logged_in_user');
}

?>