<?php 

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use App\Database\Pagination;

class Testimony extends Page
{

    /**
     * Método responsável por obter e renderizar os itens de depoimentos para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getTestimonyItems($request, &$obPagination)
    {
        //Depoimentos
        $itens = '';

        //Quantidade total de registros
        $quantidadeTotal = EntityTestimony::getTestimonies(null,null,null,'COUNT(*) AS qtd')
            ->fetchObject()->qtd;

        //Página atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //Instância de paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5 );

        //Resultado da página
        $results = EntityTestimony::getTestimonies(null,'id DESC',$obPagination->getLimit());
        //Renderiza o item
        while($obTestimony = $results->fetchObject(EntityTestimony::class))
        {
            //View de depoimentos
            $itens .= View::render('pages/testimony/item',[
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y à\s H:i:s', strtotime($obTestimony->data))
            ]);
        }

        //Retorna os depoimentos
        return $itens;
    }
    

    /**
    * Método responsável por retornar o conteúdo(view) de depoimentos
    * @param Resquest $request
    * @return string
    */
    public static function getTestimonies($request)
    {
        //View de depoimentos
        $content = View::render('pages/testimonies',[
            'itens' => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination)
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
         
        //Retorna a página de listagem de depoimentos
        return self::getTestimonies($request);
    }
}