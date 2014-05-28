<?php


/**
*
*/
class AprobarController extends AppController
{

    public function index()
    {
        $this->list = Load::model('photos')->PendientPhoto();
    }

    public function aprobado($id) {
        if (Load::model('photos')->Valid($id)) {
            Flash::valid('Aprobado');
        }
        Router::redirect('aprobar/');
    }

    public function eliminar($id) {
        if (Load::model('photos')->Error($id)) {
            Flash::valid('Eliminado');
        }
        Router::redirect('aprobar/');
    }

}

?>