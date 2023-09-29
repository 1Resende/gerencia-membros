<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Utils\View;
use App\Common\Enviroment;
use App\Http\Middleware\Queue;

Enviroment::load(__DIR__ . '/../');

define('URL', getenv('URL'));

//Define o valor padrão das variáveis
View::init([
    'URL'  => URL
]);

//Define o mapeamento de middlewares
Queue::setMap([
    'maintenance' => App\Http\Middleware\Maintenance::class
]);

//Define o mapeamento de middlewares padrões(executado em todas as rotas)
Queue::setDefault([
    'maintenance'
]);