<?php 

namespace App\Database;

use PDO;
use PDOException;

class Database
{

    private $table; //Nome da tabela a ser manipulada

    //Instancia de conexão PDO
    private $connection;

    //Define a tabela, instancia e conexão 
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    //Metódo responsável por criar a conexão com o BD
    private function setConnection()
    {
        try{
            $this->connection = new PDO(
                'mysql:host=' . getenv('DB_HOST') .
                ';dbname=' . getenv('DB_NAME'),
                getenv('DB_USER'),
                getenv('DB_PASS')      
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Houve um erro ao se conectar ao banco" . $e->getMessage());
        }
    }

    /** 
    *Metódo responsável por executar query's dentro do banco
    *@param string $query
    *@param array $params
    *@return PDOStatement
    */
    public function execute($query, $params = [])
    {
        try{
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        }catch(PDOException $e){
            die("Houve um erro: " . $e->getMessage());
        }
    }


    /** 
    *Metódo responsável por inserir dados no banco
    *@param array $values[field => value]
    *@return integer ID inserido
    */
    public function insert($values)
    {
        //Dados da query
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');
        //Monta a query
        $query = 'INSERT INTO ' . $this->table . '(' .implode(',',$fields) . ')' . 'VALUES(' .implode(',', $binds) . ')';
        //Executa o insert
        $this->execute($query, array_values($values));
        //Retorna o Id inserido
        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por executar uma consulta no banco
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //Dados dad query
        $where = strlen($where) ? 'WHERE ' .$where : '';
        $order = strlen($order) ? 'ORDER BY ' .$order : '';
        $limit = strlen($limit) ? 'LIMIT ' .$limit : '';

        //Monta a query
        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        //Executa a query
        return $this->execute($query);
    }

    /**
     * Método responsável por executar atualização no banco
     * @param string $where
     * @param array $values [ field => value]
     * @return boolean
     */
    public function update($where, $values)
    {
        //Dados da query 
        $fields = array_keys($values);

        //Monta a query
        $query = 'UPDATE ' . $this->table . ' SET '. implode('=?,',$fields) .'=? WHERE ' . $where;

        //Executa a query
        $this->execute($query,array_values($values));

        return true;
    }

    /**
     * Método responsável por excluir dados no banco
     * @param string $where
     * @return boolean
     */
    public function delete($where)
    {
        //Monta a query
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        //Executa a query
        $this->execute($query);

        return true;
    }
}