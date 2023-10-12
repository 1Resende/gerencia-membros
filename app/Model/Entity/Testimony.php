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
     * Mensagem responsável por atualizar os dados do banco com a instância atual
     * @return boolean
     * 
     */
    public function atualizar()
    {
        //Atualiza o depoimento no banco
        return (new Database('depoimentos'))->update('id = ' . $this->id, [
            'nome'      => $this->nome,
            'mensagem'  => $this->mensagem
        ]);

        //Sucesso
        return true;
    }

    /**
     * Mensagem responsável por excluir um depoimento do banco de dados
     * @return boolean
     * 
     */
    public function excluir()
    {
        //Exclui o depoimento no banco
        return (new Database('depoimentos'))->delete('id = ' . $this->id);

        //Sucesso
        return true;
    }

    /**
     * Método responsável por retornar um depoimento com base no seu ID
     * @param integer $id
     * @return Testimony
     * 
     */
    public static function getTestimonyById($id)
    {
        return self::getTestimonies('id = ' . $id)->fetchObject(self::class); 
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