<?php 

namespace App\Controller\Pages;

use App\Utils\View;

class Page
{
    /**
    * Método responsável por renderizar o header da página do site
    * @return string
    */
    private static function getHeader() 
    {
        return View::render('pages/header');
    }

    /**
    * Método responsável por renderizar o footer da página do site
    * @return string
    */
    private static function getFooter() 
    {
        return View::render('pages/footer');
    }

    /**
     * Método responsável por renderizar o layout de paginação
     * 
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination)
    {
        //Páginas
        $pages = $obPagination->getPages();

        //Verificar a quantidade de páginas
        if(count($pages) <= 1) return '';
        
        //Links
        $links = '';

        //URL atual (sem GET)
        $url = $request->getRouter()->getCurrentUrl();
    
        //GET 
        $queryParams = $request->getQueryParams();
        //Renderiza os links
        foreach($pages as $page)
        {
            //Altera a página
            $queryParams['page'] = $page['page'];
            
            //Link
            $link = $url . '?' . http_build_query($queryParams);

            //View
            $links .= View::render('/pages/pagination/link',[
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
    
        }
        //Renderiza box de paginação
        return View::render('pages/pagination/box',[
            'links' => $links
        ]);
    }

    /**
    * Método responsável por retornar o conteúdo(view) da página do site
    * @param string $title
    * @param array $content 
    * @return string
    */

    public static function getPage($title, $content)
    {
        return View::render('pages/page',[
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }
}