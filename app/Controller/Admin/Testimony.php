<?php 

namespace App\Controller\Admin;

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
            $itens .= View::render('admin/modules/testimonies/item',[
                'id' => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y à\s H:i:s', strtotime($obTestimony->data))
            ]);
        }

        //Retorna os depoimentos
        return $itens;
    }

    /**
     * Método responsável por renderizar a View de listagem de depoimentos
     * @param Request $request
     * @return string
     * 
     */
    public static function getTestimonies($request) 
    {
        //Conteúdo da home
        $content = View::render('admin/modules/testimonies/index', [
            'items' => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request,$obPagination)
        ]);

        //Retorna a página completa
        return parent::getPanel('Depoimentos - Admin', $content, 'testimonies');
    }
   

    /**
     * Método responsável por retornar o formulário de de cadastro de depoimento
     * @param Request $request
     * @return string
     * 
     */
    public static function getNewTestimony($request)
    {
        //Conteúdo do formulário
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Cadastrar novo depoimento',
            'nome' => '',
            'mensagem' => '',
            'status' => ''
        ]);

        //Retorna a página completa
        return parent::getPanel('Cadastrar depoimento - Admin', $content, 'testimonies');
    }

    /**
     * Método responsável por cadastrar novo depoimento no banco
     * @param Request $request
     * @return string
     * 
     */
    public static function setNewTestimony($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();
    
        //Nova instancia de depoimento
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['name'] ?? '';
        $obTestimony->mensagem = $postVars['message'] ?? '';
        $obTestimony->cadastrar();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=created');
    }
    
    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     * 
     */
    private static function getStatus($request)
    {
        //Query Params
        $queryParams = $request->getQueryParams();
        
        //Status
        if(!isset($queryParams['status'])) return '';

        //Mensagem de status
        switch($queryParams['status'])
        {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de edição de um depoimento
     * @param Request $request
     * @return integer $id
     * @return string
     * 
     */
    public static function getEditTestimony($request, $id)
    {
        //Obtém o depoimento do banco de dados
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //Valida instancia
        if(!$obTestimony instanceof EntityTestimony)
        {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Editar depoimento',
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('Editar depoimento - Admin', $content, 'testimonies');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @return integer $id
     * @return string
     * 
     */
    public static function setEditTestimony($request, $id)
    {
        //Obtém o depoimento do banco de dados
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //Valida instancia
        if(!$obTestimony instanceof EntityTestimony)
        {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        //Post vars
        $postVars = $request->getPostVars();

        //Atualiza a instancia
        $obTestimony->nome = $postVars['name'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['message'] ?? $obTestimony->mensagem;
        $obTestimony->atualizar();

        //Redireciona o usuário 
        $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=updated');
    }
    
}