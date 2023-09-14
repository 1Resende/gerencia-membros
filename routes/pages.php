<?php 

use App\Http\Response;
use App\Controller\Pages;

//Rota Home
$obRouter->get('/', [
    function()
    {
        return new Response(200, Pages\Home::getHome());
    }
]);

//Rota Sobre
$obRouter->get('/sobre', [
    function()
    {
        return new Response(200, Pages\About::getAbout());
    }
]);

//Rota dinâmica
$obRouter->get('/pagina/{idPage}/{action}', [
    function($idPage,$action)
    {
        return new Response(200, 'Página ' . $idPage . ' - ' . $action);
    }
]);