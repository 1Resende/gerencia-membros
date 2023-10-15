<?php 

namespace App\Controller\Api;

use App\Model\Entity\Testimony AS EntityTestimony;
use App\Database\Pagination;

class Testimony extends Api
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
        $itens = [];

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
            $itens[] = [
                'id' => (int)$obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => $obTestimony->data
            ];
        }

        //Retorna os depoimentos
        return $itens;
    }

    /**
     * Método responsável por retornar os depoimentos cadastrados
     * 
     * 
     */
    public static function getTestimonies($request)
    {
        return [
            'depoimentos' => self::getTestimonyItems($request, $obPagination),
            'paginacao' => parent::getPagination($request, $obPagination)
        ];
    }
    
    /**
     * Método responsável por retornar os detalhes de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * 
     */
    public static function getTestimony($request, $id)
    {
        //Valida o ID do depoimento
        if(!is_numeric($id))
        {
            throw new \Exception("O id '". $id ."' não é válido" , 400);
        }

        //Busca depoimento
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //Valida se o depoimento existe
        if(!$obTestimony instanceof EntityTestimony)
        {
            throw new \Exception('O depoimento ' . $id . ' não foi encontrado!', 404);
        }

        //Retorna os detalhes do depoimento
        return [
            'id' => (int)$obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    /**
     * Método responsável por cadastrar um novo depoimento
     * @param Request $request
     * 
     */
    public static function setNewTestimony($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();
        
        //Valida os campos obrigatórios
        if(!isset($postVars['nome']) || !isset($postVars['mensagem']))
        {
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios!", 400);
        }

        //Novo depoimento
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();
        
        //Retorna os detalhes do depoimento cadastrado
        return [
            'id' => (int)$obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    /**
     * Método responsável por atualizar um depoimento
     * @param Request $request
     * @param integer $id
     * 
     */
    public static function setEditTestimony($request, $id)
    {
        //Post Vars
        $postVars = $request->getPostVars();
        
        //Valida os campos obrigatórios
        if(!isset($postVars['nome']) || !isset($postVars['mensagem']))
        {
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios!", 400);
        }

        //Busca o depoimento no banco
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //Valida a instância

        if(!$obTestimony instanceof EntityTestimony)
        {
            throw new \Exception('O depoimento ' . $id . ' não foi encontrado!', 404);
        }

        //Atualiza o depoimento
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->atualizar();
        
        //Retorna os detalhes do depoimento atualizados
        return [
            'id' => (int)$obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    /**
     * Método responsável por atualizar um depoimento
     * @param Request $request
     * @param integer $id
     * 
     */
    public static function setDeleteTestimony($request, $id)
    {
        //Busca o depoimento no banco
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //Valida a instância

        if(!$obTestimony instanceof EntityTestimony)
        {
            throw new \Exception('O depoimento ' . $id . ' não foi encontrado!', 404);
        }

        //Exclui o depoimento
        $obTestimony->excluir();
        
        //Retorna o sucesso da exclusão do depoimento
        return [
            'sucesso' => true
        ];
    }
}