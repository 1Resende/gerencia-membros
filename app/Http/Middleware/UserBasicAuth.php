<?php 

namespace App\Http\Middleware;

use App\Model\Entity\User;

class UserBasicAuth
{
    /**
     * Método responsável por retornar uma instância de usuário autenticado
     * @return User 
     * 
     */
    private function getBasicAuthUser()
    {
        //Verifica a existência dos dados de acesso
        if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']))
        {
            return false;
        }

        //Busca o usuário pelo e-mail
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        
        //Verifica a instância
        if(!$obUser instanceof User)
        {
            return false;
        }

        //Valida a senha e retorna o usuário
        return password_verify($_SERVER['PHP_AUTH_PW'],$obUser->password) ? $obUser : false;
    }


    /**
     * Método responsável por validar o acesso via HTTP basic auth
     * @param Request $request
     * 
     */
    private function basicAuth($request)
    {
        //Verifica o usuário recebido
        if($obUser = $this->getBasicAuthUser())
        {
            $request->user = $obUser;
            return true;
        }
        //Emite o erro de usuário inválido
        throw new \Exception('Usuário não autorizado!', 403);
    }


    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     * 
     */
    public function handle($request, $next)
    {
        //Realiza a validação do acesso via basic auth
        $this->basicAuth($request);

        //Executa o próximo nível do middleware
        return $next($request);
    }
}