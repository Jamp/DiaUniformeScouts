<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller {

    final protected function initialize()
    {
        // Clausula Internet Explorer
        // if ( strrpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== False ){
        //     View::template('noie');
        // }
    }

    final protected function finalize()
    {
        if ( !isset($this->title) ) {
            $this->title = 'Icterus - '. APP_NAME;
        } else {
            $this->title = $this->title . ' » ' . APP_NAME . ' - Icterus';
        }
    }

}
