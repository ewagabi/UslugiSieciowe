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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// DOCTOR
$route['doctor/create'] = 'doctor/create';											// 1
$route['doctor/(:num)/edit'] = 'doctor/edit/$1';									// 2
$route['doctor/(:num)/delete'] = 'doctor/delete/$1';								// 3
$route['doctor/(:num)'] = 'doctor/read/$1';											// 4
$route['doctor/(:num)/appointment'] = 'doctor/read_appointments/$1';				// 5, 7
$route['doctor/(:num)/appointment/(:num)'] = 'doctor/read_appointment/$1/$2';		// 6
$route['doctor'] = 'doctor/read_all';												// 8
$route['doctor/speciality/(:num)'] = 'doctor/read_by_speciality/$1';				// 9 

// PATIENT
$route['patient/create'] = 'patient/create';										// 10
$route['patient/(:num)/edit'] = 'patient/edit/$1';										// 11
$route['patient/(:num)/delete'] = 'patient/delete/$1';								// 12
$route['patient/(:num)'] = 'patient/read/$1';										// 13
$route['patient/(:num)/appointment'] = 'patient/read_appointments/$1';				// 14, 16, 17
$route['patient/(:num)/appointment/(:num)'] = 'patient/read_appointment/$1/$2';		// 15
$route['patient'] = 'patient/read_all';												// 18

// APPOINTMENT
$route['appointment/create'] = 'appointment/create';								// 19
$route['appointment/(:num)/edit'] = 'appointment/edit/$1';							// 20
$route['appointment/(:num)/delete'] = 'appointment/delete/$1';						// 21
$route['appointment/(:num)'] = 'appointment/read/$1';									// 22
$route['appointment'] = 'appointment/read_all';										// 23

// SPECIALITY
$route['speciality/(:num)/edit'] = 'speciality/edit/$1';							// 24
$route['speciality/(:num)'] = 'speciality/read/$1';									// 25
$route['speciality'] = 'speciality/read_all';										// 26
