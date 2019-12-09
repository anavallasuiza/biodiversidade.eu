<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

define('LANGUAGE', $Vars->getLanguage());

$route = $Vars->getRoute();

if ($route) {
    define('ROUTE', implode('/', $route));

    foreach ($route as $route_key => $route_value) {
        define('ROUTE_'.$route_key, $route_value);
    }
} else {
    define('ROUTE', 'undefined');
    define('ROUTE_0', 'undefined');
}

unset($route);

define('SECTION', (ROUTE_0 === 'editar') ? ROUTE_1 : ROUTE_0);

include_once (WEB_PATH.'libs/ANS/PHPCan/functions.php');
include_once (WEB_PATH.'libs/ANS/PHPCan/session-check.php');

define('NIVEIS_AMEZA_ESPECIES', 6);
define('NIVEIS_AMEZA_AMEAZAS', 3);
