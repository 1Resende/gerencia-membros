<?php 

namespace App\Controller\Api;

use App\Http\Request;

class Api
{
    /**
     * Método responsável por retornar os detalhes da API
     * @param Request $request
     * @return array
     * 
     */
    public static function getDetails($request)
    {
        return [
            'nome' => 'API - WDEV',
            'versao' => 'v1.0.0',
            'autor' => 'Matheus Resende'
        ];
    }

    /**
     * Método responsável por retornar os detalhes da paginação
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     * 
     */
    protected static function getPagination($request, $obPagination)
    {
        //Query Params
        $queryParams = $request->getQueryParams();

        //Página
        $pages = $obPagination->getPages();

        //Retorno
        return [
            'paginaAtual' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }
}