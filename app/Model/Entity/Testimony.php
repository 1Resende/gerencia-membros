<?php 

namespace App\Model\Entity;

use App\Database\Database;

class Testimony
{
    public $id;
    public $nome;
    public $mensagem;
    public $data;


    /**
     * Mensagem responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * 
     */
    public function cadastrar()
    {
        //Define a data
        $this->data = date('Y-m-d H:i:s');

        //Insere o depoimento no banco
        $this->id = (new Database('depoimentos'))->insert([
            'nome'      => $this->nome,
            'mensagem'  => $this->mensagem,
            'data'      => $this->data
        ]);

        //Sucesso
        return true;
    }

    /**
     * Método responsável por retornar depoimentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * 
     * @return PDOStatement
     */
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('depoimentos'))->select($where,$order,$limit,$fields);
    }
}