<?php
defined('ANS') or die();

if ($user || $Vars->var['email']) {
    redirect(path(''));
}

if (empty($Vars->var['contrasinal_tmp']) || empty($Vars->var['contrasinal']) || empty($Vars->var['contrasinal_repeat'])) {
    $Vars->message(__('Sorry but Mail Password, New Password and Repeat Password fields can\'t be empty'), 'ko');
    return false;
}

if ($Vars->var['contrasinal'] !== $Vars->var['contrasinal_repeat']) {
    $Vars->message(__('The New password and Repeat password are differents'), 'ko');
    return false;
}

include_once ($Data->file('acl-confirmar-contrasinal.php'));

if (encrypt($Vars->var['contrasinal_tmp']) !== $usuario['contrasinal_tmp']) {
    $Vars->message(__('Sorry but the Mail Password is not correct'), 'ko');
    return false;
}

$Session->execute('passwordEdit', array(
    'id' => $usuario['id'],
    'password' => $Vars->var['contrasinal'],
    'password_repeat' => $Vars->var['contrasinal_repeat']
), 'regular');

$errors = $Errors->getList();

if ($errors) {
    $Vars->message($errors, 'ko');
    return false;
}

$Data->execute('actions|sub-logs.php', array(
    'table' => 'usuarios',
    'id' => $usuario['id'],
    'action' => 'contrasinal-confirmar'
));

$Vars->message(__('Your password was confirmed successfully. Now you can login with your new password from the login page'), 'ok');

redirect(path('entrar'));
