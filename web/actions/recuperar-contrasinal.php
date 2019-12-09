<?php
defined('ANS') or die();

if ($user || $Vars->var['email']) {
    redirect(path(''));
}

if (empty($Vars->var['usuario'])) {
    $Vars->message(__('User are required to recover your password'), 'ko');
    return false;
}

$usuario = $Session->execute('userExists', array(
    'usuario' => $Vars->var['usuario']
), 'regular');

if (empty($usuario['regular'])) {
    $Vars->message(__('Sorry but we can\'t find any user with this data, please check that you provided the correct data'), 'ko');
    return false;
}

$usuario = $usuario['regular'];

$pass = ucfirst(substr(md5(microtime()), 4, 8));

$Db->update(array(
    'table' => 'usuarios',
    'limit' => 1,
    'data' => array(
        'contrasinal_tmp' => encrypt($pass)
    ),
    'conditions' => array(
        'id' => $usuario['id']
    ),
));

$Data->execute('actions|mail.php', array(
    'to' => array($usuario['usuario'], $usuario['nome']['title']),
    'text' => array(
        'code' => 'mail-recuperar-contrasinal',
        'pass' => $pass,
        'key' => absolutePath('confirmar', 'contrasinal', urlencode(encrypt($usuario['id'].'|'.$usuario['usuario'])))
    )
));

$Vars->message(__('The mail to recover your password was sent. Please check it and check also the SPAM folder.'), 'ok');

redirect(path(''));
