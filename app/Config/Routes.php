<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'TimerController::index');
$routes->get('timer', 'TimerController::index');
$routes->get('api/timers', 'ApiController::getTimers');