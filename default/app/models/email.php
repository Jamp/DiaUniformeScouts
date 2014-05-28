<?php

/**
*
*/
Load::lib('phpmailer/PHPMailerAutoload');

class email extends PHPMailer
{

    function __construct()
    {
        $this->CharSet = 'UTF-8'; // Solución del problema con los acentos
        $this->setFrom('concursos.nacionales@scoutsvenezuela.org.ve', 'Concursos Nacionales');
        $this->addAddress('jampgold@gmail.com', 'Jaro Marval'); // Sin importar que pase siempre enviame un correo
    }

    function enviarNotificacion($title, $message){
        $this->addReplyTo("no-reply@gmail.com", "No Reply");
        $this->msgHTML($message);
        $this->Subject = "[Uniforme] ".$title;
        $this->send();
    }

}

?>