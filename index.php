<?php 

require_once __DIR__ . '/includes/app.php';

use App\Http\Router;
use App\Database;

//Inicia o Router
$obRouter = new Router(URL);

//Inclui as rotas de páginas
include __DIR__ . '/routes/pages.php';
//Inclui as rotas do painel admin
include __DIR__ . '/routes/admin.php';

//Imprime o Response da rota
$obRouter->run()->sendResponse();