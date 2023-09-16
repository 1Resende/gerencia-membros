<?php 

require __DIR__ . '/vendor/autoload.php';

use App\Http\Router;
use App\Utils\View;
use App\Common\Enviroment;

Enviroment::load(__DIR__);

define('URL', getenv('URL'));

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

