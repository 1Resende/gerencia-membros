<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Utils\View;
use App\Common\Enviroment;

Enviroment::load(__DIR__ . '/../');

define('URL', getenv('URL'));

//Define o valor padrão das variáveis
View::init([
    'URL'  => URL
]);