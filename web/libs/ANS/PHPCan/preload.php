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

include_once (filePath('libs|ANS/PHPCan/session-check.php'));

$MobileDetect = new \Mobile_Detect();

define('MOBILE', $MobileDetect->isMobile());
define('TABLET', $MobileDetect->isTablet());

if (MOBILE) {
    if ($Vars->var['mobile']) {
        cookie('mobile', $mobile_enabled = (($Vars->var['mobile'] === 'true') ? true : false));
    } else {
        $mobile_enabled = cookie('mobile');

        if ($mobile_enabled === null) {
            cookie('mobile', $mobile_enabled = MOBILE);
        }
    }

    define('MOBILE_ENABLED', $mobile_enabled);
} else {
    define('MOBILE_ENABLED', false);
}

define('NIVEIS_AMEZA_ESPECIES', 6);
define('NIVEIS_AMEZA_AMEAZAS', 3);
