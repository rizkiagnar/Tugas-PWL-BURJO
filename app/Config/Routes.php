<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Customer::index');

// Customer Routes
$routes->post('customer/checkout', 'Customer::checkout');
$routes->post('customer/confirm_order', 'Customer::confirm_order');
$routes->get('customer/payment/(:num)', 'Customer::payment/$1');
$routes->post('customer/simulate_payment/(:num)', 'Customer::simulate_payment/$1');
$routes->get('customer/receipt/(:num)', 'Customer::receipt/$1');

// Admin Routes
$routes->get('admin', 'Admin::login');
$routes->post('admin/login_submit', 'Admin::login_submit');
$routes->get('admin/logout', 'Admin::logout');
$routes->get('admin/orders', 'Admin::orders');
$routes->post('admin/update_payment/(:num)', 'Admin::update_payment/$1');
$routes->post('admin/update_order_status/(:num)', 'Admin::update_order_status/$1');
$routes->get('admin/menus', 'Admin::menus');
$routes->get('admin/menu/create', 'Admin::menu_create');
$routes->post('admin/menu/store', 'Admin::menu_store');
$routes->get('admin/menu/edit/(:num)', 'Admin::menu_edit/$1');
$routes->post('admin/menu/update/(:num)', 'Admin::menu_update/$1');
$routes->get('admin/menu/delete/(:num)', 'Admin::menu_delete/$1');

