<?php 

namespace App\Model\Entity;

use App\Database\Database;

class User
{
    public $id;
    public $nome;
    public $email; 
    public $password;

    public static function getUserByEmail($email)
    {
        /**
         * Método responsável por retornar um  usuário com base em seu e-mail
         * @param string $email
         * @return User 
         * 
         */
        return (new Database('usuarios'))
        ->select('email ="' . $email . '"')
        ->fetchObject(self::class);
    }
}