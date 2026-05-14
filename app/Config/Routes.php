<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth', 'Auth::auth');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/dashboard/stats', 'Dashboard::stats');
$routes->get('/logout', 'Auth::logout');

// User Accounts routes
$routes->get('/users', 'Users::index');
$routes->post('users/save', 'Users::save');
$routes->get('users/edit/(:segment)', 'Users::edit/$1');
$routes->post('users/update', 'Users::update');
$routes->post('users/delete/(:num)', 'Users::delete/$1');
$routes->post('users/fetchRecords', 'Users::fetchRecords');

$routes->get('/', 'Home::index');


/*
|--------------------------------------------------------------------------
| RESIDENTS
|--------------------------------------------------------------------------
*/
$routes->get('residents',                    'Residents::index');
$routes->post('residents/fetchRecords',      'Residents::fetchRecords');
$routes->get('residents/residentStats',      'Residents::residentStats');   // ← REQUIRED for stat cards
$routes->post('residents/save',             'Residents::save');
$routes->get('residents/get/(:num)',         'Residents::get/$1');
$routes->post('residents/update',           'Residents::update');
$routes->get('residents/delete/(:num)',      'Residents::delete/$1');
$routes->post('residents/delete/(:num)',     'Residents::delete/$1');
$routes->get('residents/lookup',             'Residents::lookup');
$routes->get('residents/getResidentDetails/(:num)', 'Residents::getResidentDetails/$1');
$routes->get('residents/export',             'Residents::export');
$routes->get('residents/printView',          'Residents::printView');

/* |--------------------------------------------------------------------------
| BARANGAY OFFICIALS
|--------------------------------------------------------------------------*/

$routes->get('barangay-officials',                        'BarangayOfficials::index');
$routes->post('barangay-officials/fetchRecords',          'BarangayOfficials::fetchRecords');
$routes->post('barangay-officials/save',                  'BarangayOfficials::save');
$routes->get('barangay-officials/edit/(:num)',            'BarangayOfficials::edit/$1');
$routes->post('barangay-officials/update',                'BarangayOfficials::update');
$routes->post('barangay-officials/delete/(:num)',         'BarangayOfficials::delete/$1');




// Households
$routes->get('households',                          'Households::index');
$routes->post('households/fetchRecords',            'Households::fetchRecords');
$routes->get('households/fetchStats',               'Households::fetchStats');           // ← 
$routes->get('households/fetchAssistancePriority',  'Households::fetchAssistancePriority'); 
$routes->post('households/save',                    'Households::save');
$routes->get('households/get/(:num)',               'Households::get/$1');
$routes->post('households/update',                  'Households::update');
$routes->get('households/delete/(:num)',            'Households::delete/$1');
$routes->post('households/delete/(:num)',           'Households::delete/$1');
//Blotter
$routes->get('blotter', 'Blotter::index');
$routes->post('blotter/save', 'Blotter::save');
$routes->get('blotter/get/(:num)', 'Blotter::get/$1');
$routes->get('blotter/edit/(:num)', 'Blotter::get/$1');
$routes->post('blotter/update', 'Blotter::update');
$routes->post('blotter/delete/(:num)', 'Blotter::delete/$1');
$routes->post('blotter/fetchRecords', 'Blotter::fetchRecords');

//Clearance
 $routes->get('clearances',                    'Clearances::index');
 $routes->get('clearances/pending',            'Clearances::pending');
 $routes->get('clearances/approved',           'Clearances::approved');
 $routes->get('clearances/released',           'Clearances::released');
 $routes->get('clearances/rejected',           'Clearances::rejected');
 $routes->get('clearances/expired',            'Clearances::expired');
 $routes->post('clearances/fetchRecords',      'Clearances::fetchRecords');
 $routes->get('clearances/stats',              'Clearances::stats');
 $routes->post('clearances/save',              'Clearances::save');
 $routes->get('clearances/view/(:num)',        'Clearances::view/$1');
 $routes->get('clearances/edit/(:num)',        'Clearances::edit/$1');
 $routes->post('clearances/update',            'Clearances::update');
 $routes->post('clearances/delete/(:num)',     'Clearances::delete/$1');
 $routes->post('clearances/approve/(:num)',    'Clearances::approve/$1');
 $routes->post('clearances/release/(:num)',    'Clearances::release/$1');
$routes->post('clearances/reject/(:num)',     'Clearances::reject/$1');   
 $routes->get('clearances/getPrintPreview/(:num)', 'Clearances::getPrintPreview/$1');
 $routes->get('clearances/searchResident',    'Clearances::searchResident');



$routes->get('permits', 'Permits::index');
$routes->get('permits/pending', 'Permits::pending');
$routes->get('permits/payment', 'Permits::payment');
$routes->get('permits/print', 'Permits::print');
$routes->get('permits/stats', 'Permits::stats');
$routes->get('permits/view/(:num)', 'Permits::view/$1');
$routes->get('permits/get/(:num)', 'Permits::get/$1');
$routes->get('permits/edit/(:num)', 'Permits::edit/$1');
$routes->get('permits/getPrintPreview/(:num)', 'Permits::getPrintPreview/$1');

$routes->post('permits/save', 'Permits::save');
$routes->post('permits/update', 'Permits::update');
$routes->post('permits/delete/(:num)', 'Permits::delete/$1');
$routes->post('permits/fetchRecords', 'Permits::fetchRecords');
$routes->post('permits/approve/(:num)', 'Permits::approve/$1');
$routes->post('permits/reject/(:num)', 'Permits::reject/$1');
$routes->post('permits/markPaid/(:num)', 'Permits::markPaid/$1');
$routes->post('permits/markActive/(:num)', 'Permits::markActive/$1');
$routes->post('permits/printPermit/(:num)', 'Permits::printPermit/$1');

//Indigents
$routes->get('indigents',          'Indigents::index');
$routes->get('indigents/pending',  'Indigents::pending');
$routes->get('indigents/approved', 'Indigents::approved');
$routes->get('indigents/released', 'Indigents::released');

$routes->post('indigents/fetchRecords', 'Indigents::fetchRecords');


$routes->get('indigents/stats', 'Indigents::stats');


$routes->post('indigents/save',            'Indigents::save');
$routes->get('indigents/edit/(:num)',      'Indigents::edit/$1');
$routes->get('indigents/get/(:num)',       'Indigents::get/$1');
$routes->post('indigents/update',          'Indigents::update');
$routes->post('indigents/delete/(:num)',   'Indigents::delete/$1');


$routes->get('indigents/view/(:num)', 'Indigents::view/$1');


$routes->post('indigents/approve/(:num)',  'Indigents::approve/$1');
$routes->post('indigents/reject/(:num)',   'Indigents::reject/$1');
$routes->post('indigents/complete/(:num)', 'Indigents::complete/$1');


$routes->get('indigents/searchResident',              'Indigents::searchResident');
$routes->get('indigents/getResidentInfo/(:num)',       'Indigents::getResidentInfo/$1');


$routes->get('indigents/getPrintPreview/(:num)', 'Indigents::getPrintPreview/$1');

//Reports
$routes->get('reports', 'Reports::index');
$routes->get('reports/reportStats', 'Reports::reportStats');
$routes->post('reports/fetchRecords', 'Reports::fetchRecords');

$routes->get('/dashboard', 'Dashboard::index');
$routes->get('dashboard/stats', 'Dashboard::stats');
$routes->get('csrf/refresh', 'BaseController::refreshCsrf');

// Logs routes for admin
$routes->get('/log', 'Logs::log');

// Settings routes
$routes->get('settings', 'Dashboard::index');

// Blotter routes
$routes->group('blotter', function($routes) {
    // Pages
    $routes->get('/', 'Blotter::index');
    
    // AJAX
    $routes->post('fetchRecords', 'Blotter::fetchRecords');
    $routes->get('getDashboardStats', 'Blotter::getDashboardStats');
    
    // CRUD
    $routes->post('save', 'Blotter::save');
    $routes->get('get/(:num)', 'Blotter::get/$1');
    $routes->post('update', 'Blotter::update');
    $routes->post('delete/(:num)', 'Blotter::delete/$1');
    
    // Workflow
    $routes->post('advanceStatus/(:num)', 'Blotter::advanceStatus/$1');
    $routes->post('dismiss/(:num)', 'Blotter::dismiss/$1');
    $routes->post('restore/(:num)', 'Blotter::restore/$1');
    
    // Timeline
    $routes->get('getTimeline/(:num)', 'Blotter::getTimeline/$1');
    
    // Hearings
    $routes->get('getHearings/(:num)', 'Blotter::getHearings/$1');
    $routes->post('saveHearing', 'Blotter::saveHearing');
    $routes->post('updateHearing', 'Blotter::updateHearing');
    $routes->post('deleteHearing/(:num)', 'Blotter::deleteHearing/$1');
    
    // Evidence
    $routes->get('getAttachments/(:num)', 'Blotter::getAttachments/$1');
    $routes->post('uploadAttachment', 'Blotter::uploadAttachment');
    $routes->post('deleteAttachment/(:num)', 'Blotter::deleteAttachment/$1');
    
    // Notifications
    $routes->get('getNotifications', 'Blotter::getNotifications');
    $routes->post('markNotificationRead/(:num)', 'Blotter::markNotificationRead/$1');
    $routes->post('markAllNotificationsRead', 'Blotter::markAllNotificationsRead');
    
    // Print
    $routes->get('print/(:num)', 'Blotter::print/$1');
    
    // Escalation
    $routes->post('escalateCase/(:num)', 'Blotter::escalateCase/$1');
    
    // Resident History
    $routes->get('getResidentHistory/(:num)', 'Blotter::getResidentHistory/$1');
});

// Indigents fetchRecords route  
$routes->post('indigents/fetchRecords', 'Indigents::fetchRecords');

/*
|--------------------------------------------------------------------------
| EVENTS - PRODUCTION READY BARANGAY EVENTS SYSTEM
|--------------------------------------------------------------------------
*/
$routes->group('events', function($routes) {
    // Pages
    $routes->get('/', 'Events::index');
    $routes->get('draft', 'Events::draft');
    $routes->get('scheduled', 'Events::scheduled');
    $routes->get('ongoing', 'Events::ongoing');
    $routes->get('completed', 'Events::completed');
    $routes->get('cancelled', 'Events::cancelled');

    // AJAX
    $routes->post('fetchRecords', 'Events::fetchRecords');
    $routes->get('stats', 'Events::stats');

    // CRUD
    $routes->post('save', 'Events::save');
    $routes->get('get/(:num)', 'Events::get/$1');
    $routes->get('edit/(:num)', 'Events::edit/$1');
    $routes->post('update', 'Events::update');
    $routes->post('delete/(:num)', 'Events::delete/$1');

    // View full record
    $routes->get('view/(:num)', 'Events::view/$1');

    // Workflow
    $routes->post('markScheduled/(:num)', 'Events::markScheduled/$1');
    $routes->post('markOngoing/(:num)', 'Events::markOngoing/$1');
    $routes->post('markCompleted/(:num)', 'Events::markCompleted/$1');
    $routes->post('cancelEvent/(:num)', 'Events::cancelEvent/$1');

    // Print
    $routes->get('getPrintPreview/(:num)', 'Events::getPrintPreview/$1');
});
$routes->post('events/delete/(:num)', 'Events::delete/$1');
$routes->get('events/upcoming', 'Events::upcoming');

/*
|--------------------------------------------------------------------------
| CLEARANCES - PRODUCTION READY BARANGAY CLEARANCE ISSUANCE SYSTEM
|--------------------------------------------------------------------------
*/
$routes->group('clearances', function($routes) {
    // Pages
    $routes->get('/', 'Clearances::index');
    $routes->get('pending', 'Clearances::pending');
    $routes->get('approved', 'Clearances::approved');
    $routes->get('paid', 'Clearances::paid');
    $routes->get('released', 'Clearances::released');

    // AJAX
    $routes->post('fetchRecords', 'Clearances::fetchRecords');
    $routes->get('stats', 'Clearances::stats');
    $routes->get('searchResident', 'Clearances::searchResident');

    // CRUD
    $routes->post('save', 'Clearances::save');
    $routes->get('get/(:num)', 'Clearances::get/$1');
    $routes->get('edit/(:num)', 'Clearances::edit/$1');
    $routes->post('update', 'Clearances::update');
    $routes->post('delete/(:num)', 'Clearances::delete/$1');

    // View full record
    $routes->get('view/(:num)', 'Clearances::view/$1');

    // Workflow
    $routes->post('approve/(:num)', 'Clearances::approve/$1');
    $routes->post('markPaid/(:num)', 'Clearances::markPaid/$1');
    $routes->post('release/(:num)', 'Clearances::release/$1');

    // Print
    $routes->get('getPrintPreview/(:num)', 'Clearances::getPrintPreview/$1');
});

/*
|--------------------------------------------------------------------------
| SETTINGS - SYSTEM CONFIGURATION PANEL
|--------------------------------------------------------------------------
*/
$routes->group('settings', function($routes) {
    // Pages
    $routes->get('/', 'Settings::index');

    // AJAX
    $routes->get('getSettings/(:segment)', 'Settings::getSettings/$1');
    $routes->get('getAllSettings', 'Settings::getAllSettings');
    $routes->post('update', 'Settings::update');
    $routes->post('uploadImage', 'Settings::uploadImage');

    // Backup & Restore
    $routes->post('backupDatabase', 'Settings::backupDatabase');
    $routes->get('listBackups', 'Settings::listBackups');
    $routes->post('restoreDatabase', 'Settings::restoreDatabase');
    $routes->post('deleteBackup', 'Settings::deleteBackup');

    // System
    $routes->post('toggleMaintenance', 'Settings::toggleMaintenance');
    $routes->post('clearCache', 'Settings::clearCache');
});
