<?php 

namespace App\Controller\Pages;

use App\Utils\View;

class Testimony extends Page
{
    /**
    * Método responsável por retornar o conteúdo(view) de depoimentos
    * @return string
    */

    public static function getTestimonies()
    {
        //View de depoimentos
        $content = View::render('pages/testimonies',[
            
        ]);

        //Retorna a view da página
        return parent::getPage('Depoimentos', $content);
    }
}