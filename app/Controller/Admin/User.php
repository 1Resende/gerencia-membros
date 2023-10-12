<?php 

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use App\Database\Pagination;

class User extends Page
{

    /**
     * Método responsável por obter e renderizar os itens de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getUserItems($request, &$obPagination)
    {
        //Usuários
        $itens = '';

        //Quantidade total de registros
        $quantidadeTotal = EntityUser::getUsers(null,null,null,'COUNT(*) AS qtd')
            ->fetchObject()->qtd;

        //Página atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //Instância de paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5 );

        //Resultado da página
        $results = EntityUser::getUsers(null,'id DESC',$obPagination->getLimit());
        //Renderiza o item
        while($obUser = $results->fetchObject(EntityUser::class))
        {
            //View de depoimentos
            $itens .= View::render('admin/modules/users/item',[
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ]);
        }

        //Retorna os depoimentos
        return $itens;
    }

    /**
     * Método responsável por renderizar a View de listagem de usuários
     * @param Request $request
     * @return string
     * 
     */
    public static function getUsers($request) 
    {
        //Conteúdo da home
        $content = View::render('admin/modules/users/index', [
            'items' => self::getUserItems($request, $obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
            'status' => self::getStatus($request)            
        ]);

        //Retorna a página completa
        return parent::getPanel('Usuários - Admin', $content, 'users');
    }
   

    /**
     * Método responsável por retornar o formulário de de cadastro de usuário
     * @param Request $request
     * @return string
     * 
     */
    public static function getNewUser($request)
    {
        //Conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
            'title' => 'Cadastrar novo usuário',
            'nome' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('Cadastrar usuário - Admin', $content, 'users');
    }

    /**
     * Método responsável por cadastrar novo usuário no banco
     * @param Request $request
     * @return string
     * 
     */
    public static function setNewUser($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();
        $nome = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        //Valida o e-mail do usuário
        $obUser = EntityUser::getUserByEmail($email);
        if($obUser instanceof EntityUser)
        {
            //Redireciona o usuário
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        //Nova instancia de usuário
        $obUser = new EntityUser;
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->password = password_hash($password, PASSWORD_DEFAULT);
        $obUser->cadastrar();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=created');
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
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail digitado já está sendo utilizado!');
                break;    
        }
    }

    /**
     * Método responsável por retornar o formulário de edição de um usuário
     * @param Request $request
     * @return integer $id
     * @return string
     * 
     */
    public static function getEditUser($request, $id)
    {
        //Obtém o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);

        //Valida instancia
        if(!$obUser instanceof EntityUser)
        {
            $request->getRouter()->redirect('/admin/users');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
            'title' => 'Editar usuário',
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('Editar usuário - Admin', $content, 'users');
    }

    /**
     * Método responsável por gravar a atualização de um usuário
     * @param Request $request
     * @return integer $id
     * @return string
     * 
     */
    public static function setEditUser($request, $id)
    {
        //Obtém o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);

        //Valida instancia
        if(!$obUser instanceof EntityUser)
        {
            $request->getRouter()->redirect('/admin/users');
        }

        //Post vars
        $postVars = $request->getPostVars();
        $nome = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        //Valida o e-mail do usuário
        $obUserEmail = EntityUser::getUserByEmail($email);
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $id)
        {
            //Redireciona o usuário
            $request->getRouter()->redirect('/admin/users/'. $id . '/edit?status=duplicated');
        }

        //Atualiza a instância
        $obUser->nome =  $nome;
        $obUser->email = $email;
        $obUser->password = password_hash($password, PASSWORD_DEFAULT);
        $obUser->atualizar();

        //Redireciona o usuário 
        $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @return integer $id
     * @return string
     * 
     */
    public static function getDeleteUser($request, $id)
    {
        //Obtém o depoimento do banco de dados
        $obUser = EntityUser::getUserById($id);

        //Valida instancia
        if(!$obUser instanceof EntityUser)
        {
            $request->getRouter()->redirect('/admin/users');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/users/delete', [
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ]);

        //Retorna a página completa
        return parent::getPanel('Excluir usuário - Admin', $content, 'testimonies');
    }

    /**
     * Método responsável por excluir um usuário
     * @param Request $request
     * @return integer $id
     * @return string
     * 
     */
    public static function setDeleteUser($request, $id)
    {
        //Obtém o depoimento do banco de dados
        $obUser = EntityUser::getUserById($id);

        //Valida instancia
        if(!$obUser instanceof EntityUser)
        {
            $request->getRouter()->redirect('/admin/users');
        }

        //Exclui o depoimento
        $obUser->excluir();

        //Redireciona o usuário 
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
    
}