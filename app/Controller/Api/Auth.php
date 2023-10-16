<?php 

namespace App\Controller\Api;

use App\Model\Entity\User;
use Firebase\JWT\JWT;

class Auth extends Api
{ 
    /**
     * Método responsável por gerar um token JWT
     * @param Requeste $request
     * @return array
     * 
     */
    public static function generateToken($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if(!isset($postVars['email']) || !isset($postVars['password']))
        {
            throw new \Exception("Os campos 'email' e 'password' são obrigatórios!", 400);
        }

        //Busca o usuário pelo email
        $obUser = User::getUserByEmail($postVars['email']);

        if(!$obUser instanceof User)
        {
            throw new \Exception("Usuário ou senha são inválidos!", 400);
        }

        //Valida a senha do usuário
        if(!password_verify($postVars['password'], $obUser->password))
        {
            throw new \Exception("Usuário ou senha são inválidos!", 400);
        }

        //Payload
        $payload = [
            'email' => $obUser->email
        ];

        //Retorna o token gerado
        return [
            "token"=> JWT::encode($payload, getenv('JWT_KEY'), 'HS256')
        ];
    }
}