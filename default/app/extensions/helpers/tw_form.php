<?php

/**
 * Helper para Formularios
 *
 * @category   KumbiaPHP
 * @package    Helpers
 */
class TwForm extends Form
{
    protected static $_defaultForm = 'class="form-horizontal"';
    protected static $_full = False;
    private static $_filter = False;
    private static $_formGroup = '';
    private static $_code = array(212 =>'0212', 234 =>'0234', 235 =>'0235', 237 =>'0237', 238 =>'0238', 239 =>'0239', 240 =>'0240', 241 =>'0241', 242 =>'0242', 243 =>'0243', 244 =>'0244', 245 =>'0245', 246 =>'0246', 247 =>'0247', 248 =>'0248', 249 =>'0249', 251 =>'0251', 252 =>'0252', 253 =>'0253', 254 =>'0254', 255 =>'0255', 256 =>'0256', 257 =>'0257', 258 =>'0258', 259 =>'0259', 261 =>'0261', 262 =>'0262', 263 =>'0263', 264 =>'0264', 265 =>'0265', 266 =>'0266', 267 =>'0267', 268 =>'0268', 269 =>'0269', 271 =>'0271', 272 =>'0272', 273 =>'0273', 274 =>'0274', 275 =>'0275', 276 =>'0276', 277 =>'0277', 278 =>'0278', 279 =>'0279', 281 =>'0281', 282 =>'0282', 283 =>'0283', 284 =>'0284', 285 =>'0285', 286 =>'0286', 287 =>'0287', 288 =>'0288', 289 =>'0289', 291 =>'0291', 292 =>'0292', 293 =>'0293', 294 =>'0294', 295 =>'0295');

    public static function _getAttr($attrs, $filters = NULL) {
        $class = "class=\"";
        $replace = "class=\"form-control $filters ";
        $pos = strpos($attrs, $class);

        if ($pos === false) {
            $result = " $replace\" $attrs";
        } else {
            $result = str_replace($class, $replace, $attrs);
        }
        return $result;
    }

    public static function open($action = NULL, $attrs = NULL, $method = 'post')
    {
        if ( empty($attrs) ) $attrs = self::$_defaultForm;
        return parent::open($action, $method, $attrs)."\n";
    }

    public static function openFull($name = NULL,$action = NULL, $attrs = NULL, $legend = NULL, $method = 'post')
    {
        self::$_full = True;
        if ( empty($attrs) ) $attrs = self::$_defaultForm;
        if ( !empty($name) ) self::$_formGroup = $name.'.';
        $legend = ( empty($attrs) )?NULL:$legend;
        return parent::open($action, $method, $attrs)."\n<fieldset>\n".self::legend($legend).parent::hidden(self::$_formGroup.'id');
    }

    public static function close()
    {
        $close = (self::$_full)?"</fieldset>\n":'';
        $close .= "</form>\n";
        $close .= (self::$_filter)?Tag::js('jquery.keyfilter'):'';

        self::$_defaultForm = 'class="form-horizontal"';
        self::$_full = False;
        self::$_filter = False;
        self::$_formGroup = '';
        return $close."\n";
    }

    public static function legend($text=NULL)
    {
        if ( empty($text) ) $text = ucfirst(Router::get('action')).' '.ucfirst(Router::get('controller'));
        return "<legend>$text</legend>\n";
    }

    public static function input($type, $field, $size=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL, $value=NULL)
    {
        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(parent::getFieldData(self::$_formGroup.$field, $value), EXTR_OVERWRITE);
        $group = "<div class=\"$size\">";

        if (!empty($filters)) self::$_filter = True;
        $attrs = self::_getAttr($attrs, $filters);
        $attrs .= ($required)?" required=\"required\" ":'';

        $group .= "<input id=\"$id\" name=\"$name\" type=\"$type\" value=\"$value\" $attrs/>";

        $group .= ($help)?'<span id="'.$id.'_help" class="help-block">'.$help.'</span>':'';
        $group .= "</div>\n";
        return $group;
    }

    public static function text($field, $size=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL, $value=NULL)
    {
        return self::input('text', $field, $size, $help, $required, $filters, $attrs, $value);
    }

    public static function email($field, $size=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL, $value=NULL)
    {
        $filters .= ' mask-email';
        return self::input('email', $field, $size, $help, $required, $filters, $attrs, $value);
    }

    public static function hidden($field, $attrs=NULL, $value=NULL)
    {
        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(parent::getFieldData(self::$_formGroup.$field, $value), EXTR_OVERWRITE);
        return "<input id=\"$id\" name=\"$name\" type=\"hidden\" value=\"$value\"/>";
    }

    public static function textarea($field, $size=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL, $value=NULL) {

        if (!empty($filters)) self::$_filter = True;
        $attrs = self::_getAttr($attrs, $filters);
        $attrs .= ($required)?" required=\"required\" ":'';
        $group = "<div class=\"$size\">";
        $group .= parent::textarea(self::$_formGroup.$field, $attrs, $value);
        $group .= ($help)?'<span id="'.$id.'_help" class="help-block">'.$help.'</span>':'';
        $group .= "</div>\n";
        return $group;
    }

    public static function groupInput($type, $field, $size=NULL, $label=NULL, $placeholder=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL, $value=NULL)
    {

         // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(parent::getFieldData(self::$_formGroup.$field, $value), EXTR_OVERWRITE);

        $group = '<div class="form-group">';
        $group .= (empty($label))?'':parent::label($label, $id, 'class="col-md-4 control-label"');
        $group .= "<div class=\"$size\">";

        if (!empty($filters)) self::$_filter = True;
        $attrs = self::_getAttr($attrs, $filters);
        $attrs .= ($required)?" required=\"required\" ":'';
        $attrs .= ($placeholder)?" placeholder=\"$label\" ":'';

        $group .= "<input id=\"$id\" name=\"$name\" type=\"$type\" value=\"$value\" $attrs/>";

        $group .= ($help)?'<span id="'.$id.'_help" class="help-block"></span>':'';
        $group .= "</div></div>\n";
        return $group;
    }

    public static function groupText($id, $size=NULL, $label=NULL, $placeholder=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL)
    {
        return self::groupInput('text', $id, $size, $label, $placeholder, $help, $required, $filters, $attrs);
    }

    public static function groupPassword($id, $size=NULL, $label=NULL, $placeholder=NULL, $help=NULL, $required=NULL, $filters=NULL, $attrs=NULL)
    {
        return self::groupInput('password', $id, $size, $label, $placeholder, $help, $required, $filters, $attrs);
    }

    public static function ActionButtons($url_cancel){

        $group = '<div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-4">';
        $group .= '<button id="crear" name="crear" class="btn btn-success"><i class="fa fa-check"></i> Guardar</button> ';
        $group .= Html::link($url_cancel, '<i class="fa fa-times"></i> Cancelar', 'class="btn btn-danger"');
        $group .= '</div></div>';
        return $group;
    }

    public static function groupSelect($field, $data, $size=NULL, $label=NULL, $help=NULL, $required=NULL, $attrs=NULL, $value=NULL)
    {

         // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(parent::getFieldData(self::$_formGroup.$field, $value), EXTR_OVERWRITE);

        $group = '<div class="form-group">';
        $group .= (empty($label))?'':parent::label($label, $id, 'class="col-md-4 control-label"');
        $group .= "<div class=\"$size\">";

        $attrs = self::_getAttr($attrs);
        $attrs .= ($required)?" required=\"required\" ":'';

        $options = '';
        foreach ($data as $k => $v) {
            $k = htmlspecialchars($k, ENT_COMPAT, APP_CHARSET);
            $options .= "<option value=\"$k\"";
            // Si es array $value para select multiple se seleccionan todos
            if (is_array($value)) {
                if (in_array($k, $value)) {
                    $options .= ' selected="selected"';
                }
            } else {
                if ($k == $value) {
                    $options .= ' selected="selected"';
                }
            }
            $options .= '>' . htmlspecialchars($v, ENT_COMPAT, APP_CHARSET) . '</option>';
        }

        $group .= "<select id=\"$id\" name=\"$name\" $attrs>$options</select>";

        $group .= ($help)?'<span id="'.$id.'_help" class="help-block"></span>':'';
        $group .= "</div></div>\n";
        return $group;
    }

    public static function cmbPaises($field, $attrs=NULL, $value=NULL)
    {
        $paises = "ABJASIA","ACROTIRI Y DHEKELIA","AFGANISTÁN","ALBANIA","ALEMANIA","ANDORRA","ANGOLA","ANGUILA","ANTIGUA Y BARBUDA","ARABIA SAUDITA","ARGELIA","ARGENTINA","ARMENIA","ARUBA","AUSTRALIA","AUSTRIA","AZERBAIYÁN","BAHAMAS","BARÉIN","BANGLADÉS","BARBADOS","BÉLGICA","BELICE","BENÍN","BERMUDAS","BIELORRUSIA","BIRMANIA","BOLIVIA","BOSNIA Y HERZEGOVINA","BOTSUANA","BRASIL","BRUNÉI","BULGARIA","BURKINA FASO","BURUNDI","BUTÁN","CABO VERDE","CAIMÁN, ISLAS","CAMBOYA","CAMERÚN","CANADÁ","CATAR","CENTROAFRICANA, REPÚBLICA","CHAD"," CHECA, REPÚBLICA","CHILE","CHINA","CHIPRE","CHIPRE DEL NORTE","COCOS, ISLAS","COLOMBIA","COMORAS","CONGO, REPÚBLICA DEL CONGO","REPÚBLICA DEMOCRÁTICA DEL COOK, ISLAS","COREA DEL NORTE","COREA DEL SUR","COSTA DE MARFIL","COSTA RICA","CROACIA","CUBA","CURAZAO","DINAMARCA","DOMINICA","DOMINICANA, REPÚBLICA","ECUADOR","EGIPTO","EL SALVADOR","EMIRATOS ÁRABES UNIDOS","ERITREA","ESLOVAQUIA","ESLOVENIA","ESPAÑA","ESTADOS UNIDOS","ESTONIA","ETIOPÍA","FEROE, ISLAS","FILIPINAS","FINLANDIA","FIYI","FRANCIA","GABÓN","GAMBIA","GEORGIA","GHANA","GIBRALTAR","GRANADA","GRECIA","GROENLANDIA","GUAM","GUATEMALA","GUERNSEY","GUINEA","GUINEA-BISÁU","GUINEA ECUATORIAL","GUYANA","HAITÍ","HONDURAS","HONG KONG","HUNGRÍA","INDIA","INDONESIA","IRAK","IRÁN","IRLANDA","ISLANDIA","ISRAEL","ITALIA","JAMAICA","JAPÓN","JERSEY","JORDANIA","KAZAJISTÁN","KENIA","KIRGUISTÁN","KIRIBATI","KOSOVO","KUWAIT","LAOS","LESOTO","LETONIA","LÍBANO","LIBERIA","LIBIA","LIECHTENSTEIN","LITUANIA","LUXEMBURGO","MACAO","MACEDONIA","MADAGASCAR","MALASIA","MALAUI","MALDIVAS","MALÍ","MALTA","MALVINAS"," ISLAS MAN","ISLA DE MARIANAS DEL NORTE, ISLAS","MARRUECOS","MARSHALL, ISLAS","MAURICIO","MAURITANIA","MÉXICO","MICRONESIA","MOLDAVIA","MÓNACO","MONGOLIA","MONTENEGRO","MONTSERRAT","MOZAMBIQUE","NAGORNO KARABAJ","NAMIBIA","NAURU","NAVIDAD, ISLA DE","NEPAL","NICARAGUA","NÍGER","NIGERIA","NIUE","NORFOLK, ISLA","NORUEGA","NUEVA CALEDONIA","NUEVA ZELANDA","OMÁN","OSETIA DEL SUR","PAÍSES BAJOS","PAKISTÁN","PALAOS","PALESTINA","PANAMÁ","PAPÚA NUEVA GUINEA","PARAGUAY","PERÚ"," PITCAIRN, ISLAS","POLINESIA FRANCESA","POLONIA","PORTUGAL","PUERTO RICO","REINO UNIDO","RUANDA","RUMANIA","RUSIA","SAHARA OCCIDENTAL","SALOMÓN, ISLAS","SAMOA","SAMOA AMERICANA","SAN BARTOLOMÉ","SAN CRISTÓBAL Y NIEVES","SAN MARINO","SAN MARTÍN (FRANCIA)","SAN MARTÍN (PAÍSES BAJOS)","SAN PEDRO Y MIQUELÓN","SAN VICENTE Y LAS GRANADINAS","SANTA ELENA, ASCENSIÓN Y TRISTÁN DE ACUÑA","SANTA LUCÍA","SANTO TOMÉ Y PRÍNCIPE","SENEGAL","SERBIA","SEYCHELLES","SIERRA LEONA","SINGAPUR","SIRIA","SOMALIA","SOMALILANDIA","SRI LANKA","SUAZILANDIA","SUDÁFRICA","SUDÁN","SUDÁN DEL SUR","SUECIA","SUIZA","SURINAM","SVALBARD","TAILANDIA","TAIWÁN","TANZANIA","TAYIKISTÁN","TIMOR ORIENTAL","TOGO","TOKELAU","TONGA","TRANSNISTRIA","TRINIDAD Y TOBAGO","TÚNEZ","TURCAS Y CAICOS, ISLAS","TURKMENISTÁN","TURQUÍA","TUVALU","UCRANIA","UGANDA","URUGUAY","UZBEKISTÁN","VANUATU","VATICANO, CIUDAD DEL","VENEZUELA","VIETNAM VÍRGENES BRITÁNICAS, ISLAS VÍRGENES DE LOS ESTADOS UNIDOS, ISLAS","WALLIS Y FUTUNA","YEMEN","YIBUTI","ZAMBIA","ZIMBABUE";

        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(parent::getFieldData(self::$_formGroup.$field, $value), EXTR_OVERWRITE);
        // $group .= "<div class=\"$size\">";
        #$data = self::$_code;
        $attrs = self::_getAttr($attrs);

        $options = '';
        foreach ($paises as $k => $v) {
            $k = htmlspecialchars($k, ENT_COMPAT, APP_CHARSET);
            $options .= "<option value=\"$k\"";
            // Si es array $value para select multiple se seleccionan todos
            if (is_array($value)) {
                if (in_array($k, $value)) {
                    $options .= ' selected="selected"';
                }
            } else {
                if ($k == $value) {
                    $options .= ' selected="selected"';
                }
            }
            $options .= '>' . htmlspecialchars($v, ENT_COMPAT, APP_CHARSET) . '</option>';
        }

        return "<select id=\"$id\" name=\"$name\" $attrs>$options</select>";
    }

    public static function codArea($field, $attrs=NULL, $value=NULL)
    {
        // Obtiene name, id y value (solo para autoload) para el campo y los carga en el scope
        extract(parent::getFieldData(self::$_formGroup.$field, $value), EXTR_OVERWRITE);
        // $group .= "<div class=\"$size\">";
        $data = self::$_code;
        $attrs = self::_getAttr($attrs);

        $options = '';
        foreach ($data as $k => $v) {
            $k = htmlspecialchars($k, ENT_COMPAT, APP_CHARSET);
            $options .= "<option value=\"$k\"";
            // Si es array $value para select multiple se seleccionan todos
            if (is_array($value)) {
                if (in_array($k, $value)) {
                    $options .= ' selected="selected"';
                }
            } else {
                if ($k == $value) {
                    $options .= ' selected="selected"';
                }
            }
            $options .= '>' . htmlspecialchars($v, ENT_COMPAT, APP_CHARSET) . '</option>';
        }

        return "<select id=\"$id\" name=\"$name\" $attrs>$options</select>";
    }


    // array('' => '----',
        // 212 =>'0212',
        // 234 =>'0234',
        // 235 =>'0235',
        // 237 =>'0237',
        // 238 =>'0238',
        // 239 =>'0239',
        // 240 =>'0240',
        // 241 =>'0241',
        // 242 =>'0242',
        // 243 =>'0243',
        // 244 =>'0244',
        // 245 =>'0245',
        // 246 =>'0246',
        // 247 =>'0247',
        // 248 =>'0248',
        // 249 =>'0249',
        // 251 =>'0251',
        // 252 =>'0252',
        // 253 =>'0253',
        // 254 =>'0254',
        // 255 =>'0255',
        // 256 =>'0256',
        // 257 =>'0257',
        // 258 =>'0258',
        // 259 =>'0259',
        // 261 =>'0261',
        // 262 =>'0262',
        // 263 =>'0263',
        // 264 =>'0264',
        // 265 =>'0265',
        // 266 =>'0266',
        // 267 =>'0267',
        // 268 =>'0268',
        // 269 =>'0269',
        // 271 =>'0271',
        // 272 =>'0272',
        // 273 =>'0273',
        // 274 =>'0274',
        // 275 =>'0275',
        // 276 =>'0276',
        // 277 =>'0277',
        // 278 =>'0278',
        // 279 =>'0279',
        // 281 =>'0281',
        // 282 =>'0282',
        // 283 =>'0283',
        // 284 =>'0284',
        // 285 =>'0285',
        // 286 =>'0286',
        // 287 =>'0287',
        // 288 =>'0288',
        // 289 =>'0289',
        // 291 =>'0291',
        // 292 =>'0292',
        // 293 =>'0293',
        // 294 =>'0294',
        // 295 =>'0295');
}

?>