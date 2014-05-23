<?php

/**
 * Conjunto de Helpers para operaciones varias
 * <code>
 * - ***
 * </code>
 *
 * @package helpers
 * @author jamp
 * @version 0.1
 *
 */
class Utils {

    /*
     * Metodo para obtener la ip real del cliente
     */
    public static function getIp() {

        if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
            $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
            reset($entries);
            while (list(, $entry) = each($entries)) {
                $entry = trim($entry);
                if ( preg_match("/^([0-9]+\\.[0-9]+\\.[0-9]+\\.[0-9]+)/", $entry, $ip_list) ) {
                    $private_ip = array(
                                        '/^0\\./',
                                        '/^127\\.0\\.0\\.1/',
                                        '/^192\\.168\\..*/',
                                        '/^172\\.((1[6-9])|(2[0-9])|(3[0-1]))\\..*/',
                                        '/^10\\..*/');
                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
                    if ($client_ip != $found_ip) {
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
        }
        else {
            $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" );
        }

        return $client_ip;
    }

    /**
     * Helpers para grid de datos segun un resultado de consulta
     *
     *
     * @var $modelo Array Datos del Modelo, ejemplo: array($model, $method, $arg)
     * @var $opciones Boolean Columnas de Opciones
     * @var $arrayOpciones Array Lista de Opciones, ejemplo: array('Ver' => '/registro/see/', 'Modificar' => '/registro/edit/' )
     */
    public static function grid($modelo, $opciones = False, $arrayOpciones = NULL){

        // $elementos = get_object_vars($rs);
        print "<table class=\"table stripped table-hover table-striped\">\r\n\t<thead><tr>\r\n";

        if ( !is_array($modelo) ) {
            throw new Exception("Esperabamos un Array como modelo", 1);
        }

        $model = $modelo[0];
        $method = $modelo[1];
        array_shift($modelo); // Sacamos el modelo
        array_shift($modelo); // Sacamos el metodo
        $arg = @$modelo;

        $classModel = Load::model($model);
        $rs = call_user_func_array(array($classModel, $method), $arg);

        if ( $opciones && !is_array($arrayOpciones) ) {
            throw new Exception("Esperabamos un Array como datos", 1);
        }

        if (!$rs) {
            print "\t\t<td class=\"text-center\">Vacío</td>\r\n\t</tr>\r\n</table>\r\n";
            return;
        }

        $header = False;
        $registro = 1;
        $numOpciones = count($arrayOpciones);
        foreach ($rs as $elemento) {
            // Imprimir Cabecera //
            $elementos = array_keys( get_object_vars($elemento) );

            $first = 0;
            $current = 0;
            $last = count($elementos) - 1;
            if ( !$header ) {
                foreach ($elementos as $key) {
                    $key = Util::humanize($key);
                    if ( $current == $first ) {
                        print "\t\t<th class=\"text-center\">ID</th>\r\n";
                    } else {
                        print "\t\t<th>" . ucfirst($key) . "</th>\r\n";
                    }
                    if ( $opciones && $current == $last ) print "\t\t<th colspan=\"$numOpciones\" class=\"text-center\">Opciones</th>\r\n";
                    $current++;
                }
                print "\t</tr></thead><tbody>\r\n";
                $header = True;
            }
            // Imprimir Cabecera //

            // Imprimir Elementos //
            $first = 0;
            $current = 0;
            $last = count($elementos) - 1;
            foreach ($elementos as $key) {
                if ( $current == $first ) {
                    print "\t<tr>\r\n";
                    print "\t\t<td class=\"text-center\">" . $registro . "</td>\r\n";
                }
                if ( $key != "id" ) print "\t\t<td>" . $elemento->$key . "</td>\r\n";
                if ( $opciones && $current == $last ) {
                    foreach ($arrayOpciones as $texto => $link) {
                        $link .= ( substr($link, -1, 1) == '/' ) ? '' : '/';
                        print "\t\t<td class=\"text-center\">" . Html::link( $link.$elemento->id, $texto) . "</td>\r\n";
                    }
                }
                if ( $current == $last ) print "\t</tr>\r\n";
                $current++;
            }
            $registro++;
            // Imprimir Elementos //
        }
        print "</tbody></table>\r\n";

    }

    /**
     * Función para convertir fechas dd/mm/yyyy a yyyy-mm-dd
     *
     * @var $fecha String fecha en formato dd/mm/yyyy
     * @return String fecha en formato yyyy-mm-dd
     *
     */
    public static function ConversionFecha($fecha) {
        // FIXME: Resolver problema de la conversión de fecha con algo más contundente
        $d = explode('/', $fecha);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
        //return strftime("%d/%m/%Y", strtotime($fecha));
    }

    /**
    * Obtener el lugar de donde vienes
    *
    * @return String
    */
    public static function getBack() {
        $here = $_SERVER['HTTP_REFERER'];

        $protocol = 'http';
        if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
          $protocol = 'https';
        } elseif (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443')) {
          $protocol = 'https';
        }

        $base = str_replace("default/public/index.php", '', $_SERVER['SCRIPT_NAME']);
        $url = sprintf('%s://%s%s',
          $protocol,
          $_SERVER['SERVER_NAME'],
          $base
        );

        return str_replace($url, '', $here);
    }

    /**
     * Obtener el lugar de donde vienes para paginación
     *
     * @return String
     */
    public static function getUrl($route){
        $url = explode('pag', $route);
        $rs = $url[0];
        return trim($rs,'/');
    }

    public static function getLink($action)
    {
        return PUBLIC_PATH . "$action";
    }

    /**
     * Paginación asincrona con jQuery, jQuery.tmpl
     * TODO: Convertir completamente en un Grid Asincrono
     *
     * @param  String   $ruta   Ruta de donde obtener los datos
     * @param  Boolean  $buscar Si posee buscar
     * @param  Boolean  $borrar Si posee borrar
     * @param  Integer  $campos Cantidad de Columnas de la tabla
     *
     */
    public static function paginacion($ruta,$buscar,$borrar,$campos){
        // Configuración
        $partial = 'asincronos/paginar';
        $cache = 86400;

        if ( !is_string($ruta) ) {
            throw new Exception("Esperabamos un String como dato para ruta", 1);
        }
        if ( !is_bool($buscar) ) {
            throw new Exception("Esperabamos un Booleano como dato para buscar", 2);
        }
        if ( !is_bool($borrar) ) {
            throw new Exception("Esperabamos un Booleano como dato para borrar", 3);
        }
        if ( !is_int($campos) ) {
            throw new Exception("Esperabamos un Entero como dato", 4);
        }
        $ruta = self::getLink($ruta);

        View::partial(
            $partial,
            $cache,
            array(
                'ruta' => $ruta,
                'buscar'=>$buscar,
                'borrar'=>$borrar,
                'campos'=>$campos
            )
        );
    }
}