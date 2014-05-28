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
        // // Clausula Internet Explorer
        // if ( strrpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== False ){
        //     View::template('noie');
        // }
        // $this->hoy = $_SERVER['REQUEST_TIME'];
        // // $this->log=Load::model('log');
        // if ( !Auth::is_valid() && Input::request('type')!='json' ) {
        //     if(!(Router::get('controller') == 'index' && Router::get('action') == 'index' )) {
        //         Router::redirect('/');
        //         return False;
        //     }
        // } elseif ( !Auth::is_valid() && Input::request('type')=='json' ) {
        //     header('HTTP/1.0 403 Forbidden');
        //     View::template('json');
        //     return False;
        // } else {
        //     if(Input::request('type')=='json') {
        //         View::template('json');
        //     }
        // }
    }

    final protected function finalize()
    {

    }

}
