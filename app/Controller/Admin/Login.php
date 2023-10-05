<?php 

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page
{

    /**
     * Método responspável por retornar a renderização da página de login
     * @param Request $request
     * @parm string $errorMessage
     * @return string
     * 
     */
    public static function getLogin($request, $errorMessage = null)
    {
        //Status
        $status = !is_null($errorMessage) ? View::render('admin/login/status', [
            'message' => $errorMessage
        ]) : '';

        //Conteúdo da página de login
        $content = View::render('admin/login', [
            'status' => $status
        ]);
        //Retorna a página completa
        return parent::getPage('Login', $content);
    }

    /**
     * Método responsável por definir o login do usuário
     * @param Request $request
     * 
     */
    public static function setLogin($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        //Busca o usuário pelo email
        $obUser = User::getUserByEmail($email);

        if(!$obUser instanceof User)
        {
            return self::getLogin($request, 'E-mail ou senha inválidos!');
        }

        //Verifica a senha do usuário
        if(!password_verify($password, $obUser->password)){
            return self::getLogin($request, 'E-mail ou senha inválidos!');
        }

        //Cria a sessão de login
        SessionAdminLogin::login($obUser);

        //Redireciona o usuário para a home do admin
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Método responsável por deslogar o usuário
     * @param Request $request
     * 
     */
    public static function setLogout($request)
    {
        //Destroi a sessão de login
        SessionAdminLogin::logout();

        //Redireciona o usuário para a tela de login
        $request->getRouter()->redirect('/admin/login');
    }
}