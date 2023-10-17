<?php 

use App\Http\Response;
use App\Controller\Pages;

//Rota Home
$obRouter->get('/', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function()
    {
        return new Response(200, Pages\Home::getHome());
    }
]);

//Rota Sobre
$obRouter->get('/sobre', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function()
    {
        return new Response(200, Pages\About::getAbout());
    }
]);

//Rota Depoimentos
$obRouter->get('/depoimentos', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function($request)
    {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

//Rota Depoimentos (INSERT)
$obRouter->post('/depoimentos', [
    function($request)
    {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);