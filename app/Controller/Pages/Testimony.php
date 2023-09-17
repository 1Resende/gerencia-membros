<?php 

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;

class Testimony extends Page
{

    /**
     * Método responsável por obter e renderizar os itens de depoimentos para a página
     * @return string
     */
    public static function getTestimonyItems()
    {
        //Depoimentos
        $itens = '';

        //Resultado da página
        $results = EntityTestimony::getTestimonies(null,'id DESC');
        //Renderiza o item
        while($obTestimony = $results->fetchObject(EntityTestimony::class))
        {
            //View de depoimentos
            $itens .= View::render('pages/testimony/item',[
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }

        //Retorna os depoimentos
        return $itens;
    }
    

    /**
    * Método responsável por retornar o conteúdo(view) de depoimentos
    * @return string
    */
    public static function getTestimonies()
    {
        //View de depoimentos
        $content = View::render('pages/testimonies',[
            'itens' => self::getTestimonyItems()
        ]);

        //Retorna a view da página
        return parent::getPage('Depoimentos', $content);
    }

    /**
     * Método responsável por cadastrar um depoimento
     * @param Request $request
     * @return string
     * 
     */
    public static function insertTestimony($request)
    {
        //Dados do POST
        $postVars = $request->getPostVars();
        
        //Nova instância de depoimento
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();
         
        return self::getTestimonies();
    }
}