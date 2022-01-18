<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post('/login', 'LoginController@index');



// filial router
$router->get('/', 'FilialController@index');
$router->post('/', 'FilialController@create');
$router->post('/filialphoto', 'FilialController@photo');
$router->delete('/{id}', 'FilialController@delete');



// Oblast router
$router->get('/oblast', 'OblastController@index');
$router->post('/oblast', 'OblastController@create');
$router->delete('/oblast/{id}', 'OblastController@delete');


// Gorod router
$router->get('/gorod', 'GorodController@index');
$router->post('/gorod', 'GorodController@create');
$router->delete('/gorod/{id}', 'GorodController@delete');


// MFY router
$router->get('/MSG', 'MFYController@index');
$router->post('/MSG', 'MFYController@create');
$router->delete('/MSG/{id}', 'MFYController@delete');

// Klient router
$router->get('/klientlar', 'KlientController@index');
$router->post('/klientlar', 'KlientController@create');
$router->delete('/klientlar/{id}', 'KlientController@delete');
$router->get('/reester', 'KlientController@reester');


$router->get("/alldata", "AllDataController@index");


// hisobot router
$router->post('/hisobot', 'HisobotController@index');

// sms routers
$router->get('/sms', 'SMSController@sms');
$router->post('/sms', 'SMSController@index');
