<?php

/**
*
*/
class IndexController extends AppController
{

    public function index($actual=NULL)
    {

        if (is_null($actual)){
            $actual = date('Y');
        }

        $this->actual = $actual;
        $this->anos = Load::model('album')->Listar();

        $this->photos = Load::model('photos')->ListPhoto(1, $actual);

    }

    public function paginar($pagina, $ano=NULL) {
        sleep(10);
        if (is_null($ano)) {
            $ano = date('%Y');
        }
        View::select(NULL, 'json');
        $this->data = Load::model('photos')->ListPhoto($pagina, $ano);
    }

    public function subir() {

        if (Input::hasPost('submit')) {
            $archivo = Upload::factory('file', 'image');
            $archivo->setExtensions(array('jpg', 'png', 'gif'));

            if ($archivo->isUploaded()) {
                $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/img/upload/';
                $img = $archivo->saveRandom();
                if ($img) {

                    $size = getimagesize($path.$img);
                    $timg = str_replace('.', '123.', $img);

                    $thumb = new Imagick($path.$img);
                    if ( $size[0] > $size[1] ){
                        $thumb->resizeImage(450, 0, Imagick::FILTER_LANCZOS, 1);
                    } else {
                        $thumb->resizeImage(0, 350, Imagick::FILTER_LANCZOS, 1);
                    }

                    $thumb->writeImage($path.$timg);

                    $thumb->destroy();

                    if (Load::model('photos')->InsertPhoto($timg, Input::post('album_id'))) {
                        Flash::valid('Imagen subida correctamente...!!!');
                    }
                }
            }else{
                Flash::warning('No se ha Podido Subir la imagen...!!!');
            }
        }
        Router::redirect('/');
    }
}

?>