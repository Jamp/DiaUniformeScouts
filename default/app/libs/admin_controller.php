<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador para proteger los controladores que heredan
 * Para empezar a crear una convención de seguridad y módulos
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
Load::lib('Auth');
Load::lib('ACL');
class AdminController extends Controller
{

    final protected function initialize()
    {
        if(!Auth::is_valid()){

        }
    }

    final protected function finalize()
    {

    }

}
