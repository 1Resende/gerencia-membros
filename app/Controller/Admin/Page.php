<?php 

namespace App\Controller\Admin;

use App\Utils\View;

class Page
{
    //Módulos disponíveis no painel
    private static $modules = [
        'home' =>[
            'label' => 'Inicial',
            'link' => URL . '/admin'
        ],
        'testimonies' =>[
            'label' => 'Depoimentos',
            'link' => URL . '/admin/testimonies'
        ],
        'users' =>[
            'label' => 'Usuários',
            'link' => URL . '/admin/users'
        ]
    ];

    /**
     * Método responsável por retornar o conteúdo(view) da estrutura genérica ded página do painel 
     * @param string $title
     * @param string $content
     * @return string
     * 
     */
    public static function getPage($title,$content)
    {
        return View::render('admin/page', [
            'title' => $title,
            'content' => $content
        ]);
    }

    /**
     * Método responsável por renderizar a view do menu do painel
     * @param string $currentModule
     * @return string
     * 
     */
    private static function getMenu($currentModule)
    {
        //Links do menu
        $links = '';

        //Itera os módulos
        foreach(self::$modules as $hash => $module)
        {
            $links .= View::render('admin/menu/link',[
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $hash == $currentModule ? 'text-primary' : ''
            ]);
        }

        //Retorna a renderização do menu
        return View::render('admin/menu/box',[
            'links' => $links
        ]);
    }

    /**
     * Método responsável por renderizar a view do painel com conteúdos dinâmicos
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     * 
     */
    public static function getPanel($title, $content, $currentModule)
    {
        //Renderiza a view do painel
        $contentPanel = View::render('admin/panel',[
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        //Retorna a página renderizada
        return self::getPage($title, $contentPanel);
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
            $links .= View::render('/admin/pagination/link',[
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
    
        }
        //Renderiza box de paginação
        return View::render('admin/pagination/box',[
            'links' => $links
        ]);
    }
}