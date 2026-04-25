<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/logout', 'Auth::logout');

// User Acounts routes
$routes->get('/users', 'Users::index');
$routes->post('users/save', 'Users::save');
$routes->get('users/edit/(:segment)', 'Users::edit/$1');
$routes->post('users/update', 'Users::update');
$routes->delete('users/delete/(:num)', 'Users::delete/$1');
$routes->post('users/fetchRecords', 'Users::fetchRecords');

$routes->get('/', 'Home::index');


/*
|--------------------------------------------------------------------------
| RESIDENTS
|--------------------------------------------------------------------------
*/
$routes->group('residents', function($routes) {
    $routes->get('/', 'Residents::index');
    $routes->post('fetchRecords', 'Residents::fetchRecords');
    $routes->post('save', 'Residents::save');
    $routes->get('get/(:num)', 'Residents::get/$1');
    $routes->post('update', 'Residents::update');
    $routes->post('delete/(:num)', 'Residents::delete/$1');
});
/*
|--------------------------------------------------------------------------
| BARANGAY OFFICIALS
|--------------------------------------------------------------------------
*/
$routes->get('barangay-officials', 'BarangayOfficials::index');
$routes->post('barangay-officials/save', 'BarangayOfficials::save');
$routes->get('barangay-officials/edit/(:num)', 'BarangayOfficials::edit/$1');
$routes->post('barangay-officials/update', 'BarangayOfficials::update');
$routes->post('barangay-officials/delete/(:num)', 'BarangayOfficials::delete/$1');
$routes->post('barangay-officials/fetchRecords', 'BarangayOfficials::fetchRecords');

/*
|--------------------------------------------------------------------------
| HOUSEHOLDS
|--------------------------------------------------------------------------
*/
$routes->get('households', 'Households::index');
$routes->post('households/save', 'Households::save');
$routes->get('households/edit/(:num)', 'Households::edit/$1');
$routes->post('households/update', 'Households::update');
$routes->post('households/delete/(:num)', 'Households::delete/$1');
$routes->post('households/fetchRecords', 'Households::fetchRecords');

/*
|--------------------------------------------------------------------------
| BLOTTER
|--------------------------------------------------------------------------
*/
$routes->get('blotter', 'Blotter::index');
$routes->post('blotter/save', 'Blotter::save');
$routes->get('blotter/edit/(:num)', 'Blotter::edit/$1');
$routes->post('blotter/update', 'Blotter::update');
$routes->post('blotter/delete/(:num)', 'Blotter::delete/$1');
$routes->post('blotter/fetchRecords', 'Blotter::fetchRecords');

/*
|--------------------------------------------------------------------------
| CLEARANCES
|--------------------------------------------------------------------------
*/
$routes->get('clearances', 'Clearances::index');
$routes->post('clearances/save', 'Clearances::save');
$routes->get('clearances/edit/(:num)', 'Clearances::edit/$1');
$routes->post('clearances/update', 'Clearances::update');
$routes->post('clearances/delete/(:num)', 'Clearances::delete/$1');
$routes->post('clearances/fetchRecords', 'Clearances::fetchRecords');

/*
|--------------------------------------------------------------------------
| PERMITS
|--------------------------------------------------------------------------
*/
$routes->get('permits', 'Permits::index');
$routes->post('permits/save', 'Permits::save');
$routes->get('permits/edit/(:num)', 'Permits::edit/$1');
$routes->post('permits/update', 'Permits::update');
$routes->post('permits/delete/(:num)', 'Permits::delete/$1');
$routes->post('permits/fetchRecords', 'Permits::fetchRecords');

/*
|--------------------------------------------------------------------------
| INDIGENTS
|--------------------------------------------------------------------------
*/
$routes->get('indigents', 'Indigents::index');
$routes->post('indigents/save', 'Indigents::save');
$routes->get('indigents/edit/(:num)', 'Indigents::edit/$1');
$routes->post('indigents/update', 'Indigents::update');
$routes->post('indigents/delete/(:num)', 'Indigents::delete/$1');
$routes->post('indigents/fetchRecords', 'Indigents::fetchRecords');

/*
|--------------------------------------------------------------------------
| REPORTS
|--------------------------------------------------------------------------
*/
$routes->get('reports', 'Reports::index');
// Logs routes for admin
$routes->get('/log', 'Logs::log');