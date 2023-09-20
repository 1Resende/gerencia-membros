<?php

namespace App\Database;

class Pagination
{
    /**
     * Número máximo de registros por página
     * @var integer
     */
    private $limit; 

    /**
     * Quantidade total de resultados do banco
     * @var integer
     */
    private $results;

    /**
     * Quantidade de páginas
     * @var integer
     */
    private $pages;

    /**
     * Página atual
     * @var integer
     */
    private $currentPage;

    /**
     * Construtor da classe
     * @param integer $results
     * @param integer $currentPage
     * @param integer $limit
     * 
     */
    public function __construct($results, $currentPage = 1, $limit = 5)
    {
        $this->results = $results;
        $this->limit = $limit;
        $this->currentPage = (is_numeric($currentPage) AND $currentPage > 0) ? $currentPage :  1;
        $this->calculate();
    }

    /**
     * Método responsável por calcular a páginação
     * 
     */
    private function calculate()
    {
        //Calcula o total de páginas
        $this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;

        //Verifica se a página atual não excede o número de páginas
        $this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;
    }

    /**
     * Método responsável por retornar a cláusula limit do SQL
     * @return string
     */
    public function getLimit()
    {
        $offset = ($this->limit * ($this->currentPage - 1));
        return $offset . ',' . $this->limit;
    }

    /**
     * Método responsável por retornar as opções de páginas disponíveis
     * @return array
     */
    public function getPages()
    {
        //Não retorna páginas
        if($this->pages == 1) return [];

        //Páginas
        $pages = [];
        for($i = 1; $i <= $this->pages; $i++)
        {
            $pages[] = [
                'page' => $i,
                'current' => $i == $this->currentPage
            ];
        }
        
        return $pages;
    }
}