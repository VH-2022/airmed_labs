<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['banner-group/group-list'] = 'banner_group/group_list';
$route['banner-group/group-add'] = 'banner_group/group_add';
$route['banner-group/group-edit/(:num)'] = 'banner_group/group_edit/$1';

$route['job-master/job-details/(:num)'] = 'job_master/job_details/$1';
$route['job-master/completed-list'] = 'job_master/completed_list';
$route['job-master/spam-list'] = 'job_master/spam_list';
$route['job-master/pending-list'] = 'job_master/pending_list';
$route['job-master/prescription-report'] = 'job_master/prescription_report';
$route['job-master/prescription-details/(:num)'] = 'job_master/prescription_details/$1';

$route['customer-master/customer-list'] = 'customer_master/customer_list';
$route['customer-master/customer-add'] = 'customer_master/customer_add';
$route['customer-master/customer-edit/(:num)'] = 'customer_master/customer_edit/$1';
$route['customer-master/customer-details/(:num)'] = 'customer_master/customer_view/$1';
$route['customer-master/customer-all-details/(:num)'] = 'customer_master/customer_all_details/$1';

$route['user-master/home'] = 'user_master';
$route['user-master'] = 'user_master';
$route['test-master/test-list'] = 'test_master/test_list';
$route['test-master/test-list/(:num)'] = 'test_master/test_list/$1';
$route['test-master/price-list'] = 'test_master/price_list';
$route['test-master/price-list/(:num)'] = 'test_master/price_list/$1';
$route['test-master/test-add'] = 'test_master/test_add';
$route['test-master/test-edit/(:num)'] = 'test_master/test_edit/$1';

$route['location-master/city-list'] = 'location_master/city_list';
$route['location-master/city-list/(:num)'] = 'location_master/city_list/$1';
$route['location-master/city-add'] = 'location_master/city_add';
$route['location-master/city-edit/(:num)'] = 'location_master/city_edit/$1';

$route['location-master/state-list'] = 'location_master/state_list';
$route['location-master/state-add'] = 'location_master/state_add';
$route['location-master/state-edit/(:num)'] = 'location_master/state_edit/$1';

$route['location-master/country-list'] = 'location_master/country_list';
$route['phlebo/visit-request'] = 'Phlebo/visit_request';
$route['location-master/country-add'] = 'location_master/country_add';
$route['location-master/country-edit/(:num)'] = 'location_master/country_edit/$1';

$route['banner-group/(:any)/(:num)'] = 'banner_group';
$route['job-master/(:any)/(:num)'] = 'job_master';
$route['customer-master/(:any)/(:num)'] = 'customer_master';
$route['test-master/(:any)/(:num)'] = 'test_master';
$route['location-master/(:any)/(:num)'] = 'location_master';
$route['admin-profile-update'] = 'admin_edit';
$route['Dashboard'] = 'admin/test';
$route['admin-login'] = 'login';
$route['hrm'] = 'hrm_login/index';
$route['default_controller'] = 'user_master';
$route['api/(:any)'] = 'service/service/$1';
$route['doctor_api/(:any)'] = 'service/doctor_api/$1';
/***********Close Api***************
 *$route['api_v2/(:any)'] = 'service/Service_v2/$1';
$route['api_v3/(:any)'] = 'service/Service_v3/$1';
 ************************************/
 
 $route['api_v2/(:any)'] = 'service/Service_v2/$1';
$route['api_v4/(:any)'] = 'service/Service_v4/$1';
$route['api_v5/(:any)'] = 'service/Service_v5/$1';
$route['api_v6/(:any)'] = 'service/Service_v6/$1';
$route['api_v7/(:any)'] = 'service/Service_v7/$1';
$route['api_v8/(:any)'] = 'service/Service_v8/$1';
$route['api_v10/(:any)'] = 'service/Service_v10/$1';
$route['ios_api_v9/(:any)'] = 'service/Service_ios_v9/$1';
$route['ios_api_v10/(:any)'] = 'service/Service_ios_v10/$1';
$route['ios_api_v11/(:any)'] = 'service/Service_ios_v11/$1';
$route['ios_api_v12/(:any)'] = 'service/Service_ios_v12/$1';
$route['instument_api/(:any)'] = 'service/Instument/$1';

$route['phlebo-api/(:any)'] = 'service/Phlebo_service/$1'; 
$route['phlebo-api_v2/(:any)'] = 'service/Phlebo_service_v2/$1';
$route['phlebo-api_v3/(:any)'] = 'service/Phlebo_service_v3/$1'; 
$route['phlebo-api_v4/(:any)'] = 'service/Phlebo_service_v4/$1';
$route['phlebo-api_v5/(:any)'] = 'service/Phlebo_service_v5/$1';
$route['phlebo-api_v6/(:any)'] = 'service/Phlebo_service_v6/$1';
$route['phlebo-api_v7/(:any)'] = 'service/Phlebo_service_v7/$1';
$route['phlebo-api_v8/(:any)'] = 'service/Phlebo_service_v8/$1';
$route['phlebo-api_v9/(:any)'] = 'service/Phlebo_service_v9/$1';
$route['phlebo-api_v10/(:any)'] = 'service/Phlebo_service_v10/$1';
$route['phlebo-api_v11/(:any)'] = 'service/Phlebo_service_v11/$1';
$route['pathologist-api/(:any)'] = 'service/Pathology_api/$1'; 
$route['404_override'] = 'pagenotfound'; // adding custom controller.
$route['advertisement'] = 'user_master/advertisement';
$route['founders'] = 'user_master/my_team';
$route['packages/(:any)'] = 'user_master/sub_package/$1';
$route['translate_uri_dashes'] = FALSE;
$route['lab'] = 'b2b/login1/index';
$route['doctor'] = 'doctor/login/index';
$route['reception'] = 'reception/login/index';
$route['pathologist'] = 'login/pathologistlogin';
$route['vendor'] = 'vendor/login/index';
$route['results_v1/(:any)'] = 'service/Results_v1/$1';