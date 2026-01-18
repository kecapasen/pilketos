<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'VoteController::index');
$routes->post('/auth', 'VoteController::login');
$routes->get('/bilik-suara', 'VoteController::bilikSuara');
$routes->post('/vote', 'VoteController::submitVote');
$routes->get('/sukses', 'VoteController::success');
$routes->get('/logout', 'VoteController::logout');
$routes->group('admin', function($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->post('auth', 'AdminController::auth');
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('logout', 'AdminController::logout');
    $routes->get('api/dashboard-stats', 'AdminController::dashboardStats');
    $routes->get('api/dashboard-stream', 'AdminController::dashboardStream');
    $routes->get('candidates', 'CandidateController::index');
    $routes->get('candidate/create', 'CandidateController::create');
    $routes->post('candidate/store', 'CandidateController::store');
    $routes->get('candidate/edit/(:num)', 'CandidateController::edit/$1');
    $routes->post('candidate/update/(:num)', 'CandidateController::update/$1');
    $routes->post('candidate/delete/(:num)', 'CandidateController::delete/$1');
    $routes->get('voters', 'VoterController::index');
    $routes->post('voter/store', 'VoterController::store');
    $routes->post('voter/generate', 'VoterController::bulkGenerate');
    $routes->get('voter/delete/(:num)', 'VoterController::delete/$1');
    $routes->get('voter/reset/(:num)', 'VoterController::resetStatus/$1');
    $routes->get('voter/clear', 'VoterController::clearAll');
    
    // Voting Results
    $routes->get('results', 'AdminController::results');
});
