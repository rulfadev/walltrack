<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Landing::index');

$routes->get('/signup', 'Auth::register', ['filter' => 'guest']);
$routes->post('/signup', 'Auth::processRegister', ['filter' => 'guest']);

$routes->get('/login', 'Auth::login', ['filter' => 'guest']);
$routes->post('/login', 'Auth::processLogin', ['filter' => 'guest']);

$routes->get('/logout', 'Auth::logout', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/api/data', 'Dashboard::data');

    // Wallets
    $routes->get('/wallets', 'Wallet::index');
    $routes->get('/wallets/create', 'Wallet::create');
    $routes->post('/wallets/store', 'Wallet::store');
    $routes->get('/wallets/edit/(:num)', 'Wallet::edit/$1');
    $routes->post('/wallets/update/(:num)', 'Wallet::update/$1');
    $routes->post('/wallets/set-default/(:num)', 'Wallet::setDefault/$1');
    $routes->post('/wallets/delete', 'Wallet::delete');

    // Categories
    $routes->get('/categories', 'Category::index');
    $routes->get('/categories/create', 'Category::create');
    $routes->post('/categories/store', 'Category::store');
    $routes->get('/categories/edit/(:num)', 'Category::edit/$1');
    $routes->post('/categories/update/(:num)', 'Category::update/$1');
    $routes->post('/categories/delete', 'Category::delete');

    // Transactions
    $routes->get('/transactions', 'Transaction::index');
    $routes->get('/transactions/create', 'Transaction::create');
    $routes->post('/transactions/store', 'Transaction::store');
    $routes->get('/transactions/edit/(:num)', 'Transaction::edit/$1');
    $routes->post('/transactions/update/(:num)', 'Transaction::update/$1');
    $routes->post('/transactions/delete', 'Transaction::delete');

    // Profile
    $routes->get('/profile', 'User::profile');

    // Reports
    $routes->get('/reports', 'Report::index');
    $routes->get('/reports/export/csv', 'Report::exportCsv');
    $routes->get('/reports/export/excel', 'Report::exportExcel');

    // Budgets
    $routes->get('/budgets', 'Budget::index');
    $routes->get('/budgets/create', 'Budget::create');
    $routes->post('/budgets/store', 'Budget::store');
    $routes->get('/budgets/edit/(:num)', 'Budget::edit/$1');
    $routes->post('/budgets/update/(:num)', 'Budget::update/$1');
    $routes->post('/budgets/delete', 'Budget::delete');
});
