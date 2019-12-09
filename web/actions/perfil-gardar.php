<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

$form = $Vars->var['usuarios'];

if ($form['usuario'] !== $user['usuario']) {
    $exists = $Session->execute('userExists', array(
        'usuario' => $form['usuario'],
        'id !=' => $user['id']
    ), 'regular');

    if ($exists['regular']) {
        $Vars->message(__('Sentímolo pero xa existe alguén rexistrado con ese correo.'), 'ko');
        return false;
    }
}

if ($form['contrasinal'] && ($form['contrasinal'] !== $form['contrasinal_repeat'])) {
    $Vars->message(__('O contrasinal e a súa repetición non son iguais!'), 'ko');
    return false;
}

$form['nome_completo'] = trim($form['nome'].' '.$form['apelido1'].' '.$form['apelido2']);

$Session->execute('userEdit', array(
    'id' => $user['id'],
    'data' => $form
), 'regular');

$errors = $Errors->getList();

if ($errors) {
    $errors = $Errors->getList();

    if ($errors) {
        $Vars->message($errors, 'ko');
    } else {
        $Vars->message(__('Non se poideron gardar os datos, por favor, revisa o formulario'), 'ko');
    }

    return false;
}

$Vars->message(__('O teu perfil foi actualizado correctamente'), 'ok');

$Data->execute('actions|sub-logs.php', array(
    'table' => 'usuarios',
    'id' => $user['id'],
    'action' => 'usuarios-editar',
    'public' => 0
));

if (empty($form['contrasinal']) && ($form['usuario'] === $user['usuario'])) {
    return true;
}

if ($form['contrasinal']) {
    $Session->execute('passwordEdit', array(
        'id' => $user['id'],
        'password' => $form['contrasinal'],
        'password_repeat' => $form['contrasinal_repeat']
    ), 'regular');

    $errors = $Errors->getList();

    if ($errors) {
        $Vars->message(__('O teu contrasinal non puido ser cambiado: %s', implode(', ', $errors)), 'ko');
        return false;
    }
}

$user = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'id' => $user['id']
    )
));

if (($form['usuario'] !== $user['usuario']) || $form['contrasinal']) {
    $Session->sessions['regular']->login(array(
        'username' => $form['usuario'],
        'password' => $user['contrasinal']
    ), true);
}

return true;
