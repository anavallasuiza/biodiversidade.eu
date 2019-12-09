<?php
defined('ANS') or die();

list($id, $usuario) = explode('|', decrypt($Vars->var['code']));

if (empty($id) || empty($usuario)) {
    $Vars->message(__('Sorry but this link don\'t seem to be correct, please copy and paste the link from your mail'), 'ko');
    redirect(path(''));
}

$usuario = $Session->execute('userExists', array(
    'id' => $id,
    'usuario' => $usuario
), 'regular');

$usuario = $usuario['regular'];

if (empty($usuario)) {
    $Vars->message(__('Sorry but we can\'t find any user with this data, please check that you provided the correct data'), 'ko');
    redirect(path(''));
}

if (empty($usuario['contrasinal_tmp'])) {
    $Vars->message(__('Sorry but this link has been visited before. If you forgot your password again, please come back to make the recovery process.'), 'ko');
    redirect(path(''));
}
