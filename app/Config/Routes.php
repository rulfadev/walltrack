<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Landing::index');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::processRegister');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::processLogin');
$routes->get('/logout', 'Auth::logout');

$routes->get('/dashboard', function () {
    if (!session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    return view('pages/dashboard');
});