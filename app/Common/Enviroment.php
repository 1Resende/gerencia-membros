<?php 

namespace App\Common;

class Enviroment
{

    /**
     * Método responsável por carregar as variáveis de ambiente do projeto
     * @param string $dir -> Caminho absoluto da pasta do arquivo .env
     *
     */
    public static function load($dir)
    {
        //Verifica se o arquivo .env existe
        if(!file_exists($dir . '/.env'))
        {
            return false;
        }

        //Define as variáveis de ambiente
        $lines = file($dir . '/.env');
        foreach($lines as $line)
        {
            putenv(trim($line));
        }
        echo '<pre>';
        print_r($lines);
        echo '</pre>';
    }
}