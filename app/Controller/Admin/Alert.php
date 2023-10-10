<?php 

namespace App\Controller\Admin;

use App\Utils\View;

class Alert
{
    
    /**
     * Método responsável por retoranr uma mensagem de erro
     * @param string $message
     * @return string 
     * 
     */
    public static function getError($message)
    {
        return View::render('admin/alert/status',[
            'tipo' => 'danger',
            'message' => $message
        ]);
    }


    /**
     * Método responsável por retoranr uma mensagem de sucesso
     * @param string $message
     * @return string 
     * 
     */
    public static function getSuccess($message)
    {
        return View::render('admin/alert/status',[
            'tipo' => 'success',
            'message' => $message
        ]);
    }

}