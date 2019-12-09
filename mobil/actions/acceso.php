<?php
defined('ANS') or die();

if ($user) {
    redirect(path('') . '?' . time());
}

$Session->login('regular', array(
    'username' => $Vars->var['usuario'],
    'password' => $Vars->var['contrasinal'],
    'maintain' => $Vars->var['recordar']
));

if ($Session->logged()) {
    $user = $Session->user('');

    $Data->execute('actions|session-log.php', array(
        'action' => 'login',
        'status' => true
    ));

    $referer = $Vars->var['referer'];

    if ($referer && !strstr($referer, path('entrar'))) {
        redirect($referer . '?' . time());
    } else {
        redirect(path('') . '?' . time());
    }
} else {
    $Vars->message($Errors->getList(), 'ko');

    $Data->execute('actions|session-log.php', array(
        'action' => 'login',
        'status' => false
    ));

    $Session->logout('regular', false);
}

return false;
