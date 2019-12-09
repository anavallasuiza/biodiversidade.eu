<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

include (filePath('libs|ANS/PHPCan/intranet/functions.php'));

$Config->load('session.php');

$Session = new \ANS\PHPCan\Users\Session('Session');
$Acl = new \ANS\PHPCan\Users\Acl('Acl');

$Session->setSettings();

$Session->add('regular');

$user = array();

if ($Config->data['no-session']) {
    return false;
}

$Session->load();

if ($Session->logged('regular')) {
    $user = $Session->user('');

    include (filePath('libs|ANS/PHPCan/intranet/session-data.php'));

    return true;
} else {
    redirect(path('intranet', 'acceso').get(array('r' => getenv('REQUEST_URI'))));
}
