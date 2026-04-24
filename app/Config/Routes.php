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

// ─── Home / Dashboard ────────────────────────────────────────────────────────
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/home/stats', 'Home::stats');

// ─── Users ───────────────────────────────────────────────────────────────────
$routes->get('/users', 'Users::index');
$routes->post('/users/save', 'Users::save');
$routes->post('/users/update', 'Users::update');
$routes->get('/users/edit/(:num)', 'Users::edit/$1');
$routes->post('/users/delete/(:num)', 'Users::delete/$1');
$routes->post('/users/fetchRecords', 'Users::fetchRecords');

// ─── Residents ────────────────────────────────────────────────────────────────
$routes->get('/residents', 'Residents::index');
$routes->post('/residents/save', 'Residents::save');
$routes->post('/residents/update', 'Residents::update');
$routes->get('/residents/edit/(:num)', 'Residents::edit/$1');
$routes->post('/residents/delete/(:num)', 'Residents::delete/$1');
$routes->post('/residents/fetchRecords', 'Residents::fetchRecords');

// ─── Barangay Officials ───────────────────────────────────────────────────────
$routes->get('/barangay-officials', 'BarangayOfficials::index');
$routes->post('/barangay-officials/save', 'BarangayOfficials::save');
$routes->post('/barangay-officials/update', 'BarangayOfficials::update');
$routes->get('/barangay-officials/edit/(:num)', 'BarangayOfficials::edit/$1');
$routes->post('/barangay-officials/delete/(:num)', 'BarangayOfficials::delete/$1');
$routes->post('/barangay-officials/fetchRecords', 'BarangayOfficials::fetchRecords');

// ─── Households ───────────────────────────────────────────────────────────────
$routes->get('/households', 'Households::index');
$routes->post('/households/save', 'Households::save');
$routes->post('/households/update', 'Households::update');
$routes->get('/households/edit/(:num)', 'Households::edit/$1');
$routes->post('/households/delete/(:num)', 'Households::delete/$1');
$routes->post('/households/fetchRecords', 'Households::fetchRecords');

// ─── Blotter ──────────────────────────────────────────────────────────────────
$routes->get('/blotter', 'Blotter::index');
$routes->post('/blotter/save', 'Blotter::save');
$routes->post('/blotter/update', 'Blotter::update');
$routes->get('/blotter/edit/(:num)', 'Blotter::edit/$1');
$routes->post('/blotter/delete/(:num)', 'Blotter::delete/$1');
$routes->post('/blotter/fetchRecords', 'Blotter::fetchRecords');

// ─── Clearances ───────────────────────────────────────────────────────────────
$routes->get('/clearances', 'Clearances::index');
$routes->post('/clearances/save', 'Clearances::save');
$routes->post('/clearances/update', 'Clearances::update');
$routes->get('/clearances/edit/(:num)', 'Clearances::edit/$1');
$routes->post('/clearances/delete/(:num)', 'Clearances::delete/$1');
$routes->post('/clearances/fetchRecords', 'Clearances::fetchRecords');

// ─── Permits ──────────────────────────────────────────────────────────────────
$routes->get('/permits', 'Permits::index');
$routes->post('/permits/save', 'Permits::save');
$routes->post('/permits/update', 'Permits::update');
$routes->get('/permits/edit/(:num)', 'Permits::edit/$1');
$routes->post('/permits/delete/(:num)', 'Permits::delete/$1');
$routes->post('/permits/fetchRecords', 'Permits::fetchRecords');

// ─── Indigents ────────────────────────────────────────────────────────────────
$routes->get('/indigents', 'Indigents::index');
$routes->post('/indigents/save', 'Indigents::save');
$routes->post('/indigents/update', 'Indigents::update');
$routes->get('/indigents/edit/(:num)', 'Indigents::edit/$1');
$routes->post('/indigents/delete/(:num)', 'Indigents::delete/$1');
$routes->post('/indigents/fetchRecords', 'Indigents::fetchRecords');

// ─── Reports ──────────────────────────────────────────────────────────────────
$routes->get('/reports', 'Reports::index');
$routes->get('/reports/residents', 'Reports::residents');
$routes->get('/reports/blotter', 'Reports::blotter');
$routes->get('/reports/clearances', 'Reports::clearances');
$routes->get('/reports/permits', 'Reports::permits');

// Logs routes for admin
$routes->get('/log', 'Logs::log');