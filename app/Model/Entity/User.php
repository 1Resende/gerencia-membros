<?php 

namespace App\Model\Entity;

use App\Database\Database;

class User
{
    public $id;
    public $nome;
    public $email; 
    public $password;


    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * 
     * 
     */
    public function cadastrar()
    {
        //Insere a instância no banco
        $this->id = (new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'password' => $this->password
        ]);

        //Sucesso
        return true;
    }

     /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * 
     * 
     */
    public function atualizar()
    {
        return (new Database('usuarios'))->update( 'id = ' . $this->id ,[
            'nome' => $this->nome,
            'email' => $this->email,
            'password' => $this->password
        ]);
    }

    /**
     * Método responsável por excluir os dados no banco
     * @return boolean
     * 
     * 
     */
    public function excluir()
    {
        return (new Database('usuarios'))->delete( 'id = ' . $this->id);
    }

    /**
     * Método responsável por retornar uma instância com base no ID
     * @param integer $id
     * @return User
     *
     */
    public static function getUserById($id)
    {
        return self::getUsers('id = ' . $id)->fetchObject(self::class);
    }


    /**
         * Método responsável por retornar um  usuário com base em seu e-mail
         * @param string $email
         * @return User 
         * 
         */
    public static function getUserByEmail($email)
    {
        return self::getUsers('email ="' . $email . '"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * 
     * @return PDOStatement
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('usuarios'))->select($where,$order,$limit,$fields);
    }
}