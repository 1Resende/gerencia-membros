<?php 

use App\Http\Response;
use App\Controller\Admin;

//Rota Admin
$obRouter->get('/admin', [
    'middlewares' => [
        'required-admin-login'
    ],
    function()
    {
        return new Response(200, 'Admin');
    }
]);

//Rota de Login
$obRouter->get('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request)
    {
        return new Response(200,Admin\Login::getLogin($request));
    }
]);

//Rota de Login(POST)
$obRouter->post('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request)
    {
        return new Response(200,Admin\Login::setLogin($request));
    }
]);

//Rota de Logout
$obRouter->get('/admin/logout', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request)
    {
        return new Response(200,Admin\Login::setLogout($request));
    }
]);