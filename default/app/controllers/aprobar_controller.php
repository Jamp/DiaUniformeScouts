<?php


/**
*
*/
class AprobarController extends AppController
{
    private $user_token = '';
    private $secret_token = '';

    public function index()
    {
        $this->list = Load::model('photos')->PendientPhoto();
    }

    public function aprobado($id) {
        $photos = Load::model('photos');
        if ($photos->Valid($id)) {
            $texto = "Nueva foto en el sistema en línea para subir tus fotos del #DiadelUniformeScout // http://uniforme.scoutsfalcon.org/";
            $path = dirname($_SERVER['SCRIPT_FILENAME'])."/img/upload/1/";
            $image = $path.$photos->imageName($id);
            $twitter = Load::model('twitter');
            $tweet = $twitter->photo_tweet(
                $this->user_token,
                $this->secret_token,
                $image,
                $texto
            );
            if ($tweet) Flash::success('Tweet enviado satisfactoriamente!!!');
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