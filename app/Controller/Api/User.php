<?php 

namespace App\Controller\Api;

use App\Model\Entity\User AS EntityUser;
use App\Database\Pagination;
use Exception;

class User extends Api
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
        $itens = [];

        //Quantidade total de registros
        $quantidadeTotal = EntityUser::getUsers(null,null,null,'COUNT(*) AS qtd')
            ->fetchObject()->qtd;

        //Página atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //Instância de paginação
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5 );

        //Resultado da página
        $results = EntityUser::getUsers(null,'id ASC',$obPagination->getLimit());
        //Renderiza o item
        while($obUser = $results->fetchObject(EntityUser::class))
        {
            $itens[] = [
                'id' => (int)$obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ];
        }

        //Retorna os usuários
        return $itens;
    }

    /**
     * Método responsável por retornar os usuários cadastrados
     * 
     * 
     */
    public static function getUsers($request)
    {
        return [
            'usuarios' => self::getUserItems($request, $obPagination),
            'paginacao' => parent::getPagination($request, $obPagination)
        ];
    }
    
    /**
     * Método responsável por retornar os detalhes de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * 
     */
    public static function getUser($request, $id)
    {
        //Valida o ID do usuário
        if(!is_numeric($id))
        {
            throw new \Exception("O id '". $id ."' não é válido" , 400);
        }

        //Busca usuário
        $obUser = EntityUser::getUserById($id);

        //Valida se o usuário existe
        if(!$obUser instanceof EntityUser)
        {
            throw new \Exception('O usuário ' . $id . ' não foi encontrado!', 404);
        }

        //Retorna os detalhes do usuário
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por retornar o usuário atualmente conectado
     * @param Request $request
     * @return array
     * 
     */
    public static function getCurrentUser($request)
    {
        //Usuário atual
        $obUser = $request->user;

        //Retorna os detalhes do usuário
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por cadastrar um novo usuário
     * @param Request $request
     * 
     */
    public static function setNewUser($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();
        
        //Valida os campos obrigatórios
        if(!isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['password']))
        {
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios!", 400);
        }

        //Valida a duplicação de usuários
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);

        if($obUserEmail instanceof EntityUser)
        {
            throw new \Exception("O e-mail '" . $postVars['email'] . "' já está em uso", 400);
        }

        //Novo usuário
        $obUser = new EntityUser;
        $obUser->nome = $postVars['nome'];
        $obUser->email = $postVars['email'];
        $obUser->password = password_hash($postVars['password'], PASSWORD_DEFAULT);
        $obUser->cadastrar();
        
        //Retorna os detalhes do usuário cadastrado
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email            
        ];
    }

    /**
     * Método responsável por atualizar um usuário
     * @param Request $request
     * @param integer $id
     * 
     */
    public static function setEditUser($request, $id)
    {
        //Post Vars
        $postVars = $request->getPostVars();
        
        //Valida os campos obrigatórios
        if(!isset($postVars['nome']) || !isset($postVars['email']) || !isset($postVars['password']))
        {
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios!", 400);
        }

        //Busca o usuário no banco
        $obUser = EntityUser::getUserById($id);

        //Valida se o usuário existe
        if(!$obUser instanceof EntityUser)
        {
            throw new \Exception('O usuário ' . $id . ' não foi encontrado!', 404);
        }

        //Valida a duplicação de usuários
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $obUser->id)
        {
            throw new \Exception("O e-mail '" . $postVars['email'] . "' já está em uso", 400);
        }
        //Atualiza o usuário
        $obUser->nome = $postVars['nome'];
        $obUser->email = $postVars['email'];
        $obUser->password = password_hash($postVars['password'], PASSWORD_DEFAULT);
        $obUser->atualizar();
        
        //Retorna os detalhes do usuário atualizados
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por exluir um usuário
     * @param Request $request
     * @param integer $id
     * 
     */
    public static function setDeleteUser($request, $id)
    {
        //Busca o usuário no banco
        $obUser = EntityUser::getUserById($id);

        //Valida a instância
        if(!$obUser instanceof EntityUser)
        {
            throw new \Exception('O usuário ' . $id . ' não foi encontrado!', 404);
        }
        //Impede a exclusão do próprio cadastro
        if($obUser->id == $request->user->id)
        {
            throw new \Exception("Não é possível excluir o cadastro atualmente conectado", 400);
        }

        //Exclui o usuário
        $obUser->excluir();
        
        //Retorna o sucesso da exclusão do usuário
        return [
            'sucesso' => true
        ];
    }
}