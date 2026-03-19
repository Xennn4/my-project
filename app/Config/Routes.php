<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::index');
$routes->get('logout', 'Auth::logout');
$routes->get('blocked', 'Auth::forbiddenPage');
$routes->get('unauthorized', 'Auth::unauthorized');

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registration');

// -----------------------------
// ALL LOGGED-IN USERS
// -----------------------------
$routes->group('', ['filter' => ['auth']], static function ($routes) {
    $routes->get('dashboard', 'Home::index');

    // ONLY ONE PROFILE SECTION
    $routes->get('profile', 'ProfileController::show');
    $routes->get('profile/edit', 'ProfileController::edit');
    $routes->post('profile/update', 'ProfileController::update');
});

// -----------------------------
// STUDENT ONLY
// -----------------------------
$routes->group('', ['filter' => ['auth', 'student']], static function ($routes) {
    $routes->get('student/dashboard', 'StudentController::dashboard');
});

// -----------------------------
// TEACHER + ADMIN
// -----------------------------
$routes->group('', ['filter' => ['auth', 'teacher']], static function ($routes) {
    $routes->get('records', 'Records::index');
    $routes->get('records/create', 'Records::create');
    $routes->post('records/store', 'Records::store');
    $routes->get('records/edit/(:num)', 'Records::edit/$1');
    $routes->post('records/update/(:num)', 'Records::update/$1');
    $routes->get('records/delete/(:num)', 'Records::delete/$1');

    $routes->get('students', 'StudentManagementController::index');
    $routes->get('students/show/(:num)', 'StudentManagementController::show/$1');
});

// -----------------------------
// ADMIN ONLY
// -----------------------------
$routes->group('admin', ['filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('roles', 'Admin\RoleController::index');
    $routes->get('roles/create', 'Admin\RoleController::create');
    $routes->post('roles/store', 'Admin\RoleController::store');
    $routes->get('roles/edit/(:num)', 'Admin\RoleController::edit/$1');
    $routes->post('roles/update/(:num)', 'Admin\RoleController::update/$1');
    $routes->get('roles/delete/(:num)', 'Admin\RoleController::delete/$1');

    $routes->get('users', 'Admin\UserAdminController::index');
    $routes->post('users/assign-role/(:num)', 'Admin\UserAdminController::assignRole/$1');
});

// -----------------------------
// MENU MANAGEMENT (ADMIN)
// -----------------------------
$routes->group('menu-management', ['filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('/', 'Settings::menuManagement');
    $routes->post('create-menu-category', 'Settings::createMenuCategory');
    $routes->post('create-menu', 'Settings::createMenu');
    $routes->post('create-submenu', 'Settings::createSubMenu');
});

$routes->get('menu', 'Menu::index', ['filter' => ['auth', 'admin']]);