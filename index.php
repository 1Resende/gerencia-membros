<?php 

require __DIR__ . '/vendor/autoload.php';

use App\Common\Enviroment;
use App\Http\Router;
use App\Utils\View;

Enviroment::load(__DIR__);

define('URL','http://localhost/php/gerencia-membros');

//Define o valor padrão das variáveis
View::init([
    'URL'  => URL
]);

//Inicia o Router
$obRouter = new Router(URL);

//Inclui as rotas de páginas
include __DIR__ . '/routes/pages.php';

//Imprime o Response da rota
$obRouter->run()->sendResponse();

