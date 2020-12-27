<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Users');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Users::index');
$routes->get('logout', 'Users::logout');
$routes->match(['get','post'], 'profile', 'Users::profile', ['filter' => 'auth']);

$routes->match(['get','post'], 'admin', 'Admin::index', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'admin/getDepartmentsTree', 'Admin::getDepartmentsTree', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'admin/employees', 'Admin::employees', ['filter' => 'adminauth']);

$routes->match(['get','post'], 'department/update(:any)', 'Department::update', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'department/new', 'Department::new', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'department/delete', 'Department::delete', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'department/hasChilds', 'Department::hasChilds', ['filter' => 'adminauth']);

$routes->match(['get','post'], 'employee/update(:any)', 'Employee::update', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'employee/new', 'Employee::new', ['filter' => 'adminauth']);
$routes->match(['get','post'], 'employee/delete', 'Employee::delete', ['filter' => 'adminauth']);

$routes->match(['get','post'], 'chat', 'Chat::index', ['filter' => 'auth']);
$routes->match(['get','post'], 'chat/new', 'Chat::new', ['filter' => 'auth']);
$routes->match(['get','post'], 'chat/recents', 'Chat::recents', ['filter' => 'auth']);
$routes->match(['get','post'], 'chat/messages', 'Chat::messages', ['filter' => 'auth']);
$routes->match(['get','post'], 'chat/send', 'Chat::send', ['filter' => 'auth']);
$routes->match(['get','post'], 'chat/newUpdates', 'Chat::newUpdates', ['filter' => 'auth']);
$routes->match(['get','post'], 'chat/received', 'Chat::received', ['filter' => 'auth']);



/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
