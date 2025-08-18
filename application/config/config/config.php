<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");


date_default_timezone_set('Asia/Kolkata');
ini_set('memory_limit', '-1');
// $config['base_url'] = 'https://www.airmedlabs.com/';
// $config['base_url'] = 'https://www.airmedlabs.com/';
$config['base_url'] = 'http://localhost/Airmed_labs/';
$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language'] = 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-,()'; 
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['log_threshold'] = 2;
$config['log_path'] = '';
$config['log_file_extension'] = 'php';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = '';
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = APPPATH . 'cache/sessions/'; ;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 7200;
$config['sess_regenerate_destroy'] = true;
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = FALSE;

$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();


$config['compress_output'] = FALSE;

$config['time_reference'] = 'local';

$config['rewrite_short_tags'] = FALSE;

$config['proxy_ips'] = '';
$config['cpatcha_key'] = '6Ld5_x8UAAAAAPoCzraL5sfQ8nzvvk3e5EIC1Ljr';
$config['title'] = "AirmedLabs";
/*PayUmoney details start*/
//$config['payumoneydetail']= array ('MERCHANT_KEY'=>'J2zDE9Ot','SALT'=>'w7HTARWBKq','MERCHANT_ID'=>'5444035');
$config['payumoneydetail'] = array('MERCHANT_KEY' => 'IcZRGO7S', 'SALT' => 'spjyl2ubfa', 'MERCHANT_ID' => '5669867',"URL"=>'https://secure.payu.in',"service_provider" => "payu_paisa");//Live
/*$config['payumoneydetail'] = array('MERCHANT_KEY' => 'gtKFFx', 'SALT' => 'eCwWELxi', 'MERCHANT_ID' => '5669867','URL'=>'https://test.payu.in/_payment',"service_provider" => ""); //test*/
/*Payumoney details end*/
/* Admin details set start */
$config['admin_booking_email'] = 'booking.airmed@gmail.com';
//$config['admin_alert_phone'] = '8511153892';/*Namrata*/
//$config['admin_alert_phone'] = array('8511153892', '9726689090');
$config['admin_report_sms'] = array('9879572294','9979774646','8511195347');
$config['admin_alert_phone'] = array('8511153892','8511195347', '8980650365'); 
$config['booking_alert_phone'] = array('9898463664','8980650365','9979774646','8511153892','7878015433');
$config['sample_not_collect_alert'] = array('9898463664','8980650365','9979774646','8511153892');
$config['phlebo_accept_job_alert'] = array('9898463664','8980650365','9979774646','8511153892');
//$config['admin_alert_phone'] = '9879572294';/*Nishit(Developer)*/
/*Admin details set end*/