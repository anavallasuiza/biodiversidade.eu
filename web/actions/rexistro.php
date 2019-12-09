<?php
defined('ANS') or die();

if ($user || $Vars->var['mail'] || $Vars->var['email']) {
    redirect(path(''));
}

foreach (array('usuario', 'nome', 'contrasinal', 'contrasinal_repeat') as $campo) {
    $Vars->var[$campo] = trim($Vars->var[$campo]);

    if (empty($Vars->var[$campo])) {
        $Vars->message(__('Sorry, the <strong>"%s"</strong> field is required.', __($campo)), 'ko');
        return false;
    }
}

$existe = $Session->execute('userExists', array(
    'usuario' => $Vars->var['usuario']
), 'regular');

if ($existe['regular']) {
    $Vars->message(__('Sorry but there is already someone registered with that user'), 'ko');

    return false;
}

$usuarios_id = $Session->execute('userAdd', $Vars->var, 'regular');

if (!$usuarios_id['regular']) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

$user = array('id' => $usuarios_id['regular']);

$Db->relate(array(
    'tables' => array(
        array(
            'table' => 'usuarios',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'roles',
            'limit' => 1,
            'conditions' => array(
                'code' => 'nivel-1'
            )
        )
    )
));

if ($Vars->var['importar']) {
    $Db->update(array(
        'table' => 'usuarios',
        'data' => array(
            'solicita_importar' => 1
        ),
        'conditions' => array(
            'id' => $user['id']
        )
    ));

    /*$admins = $Db->select(array(
        'table' => 'usuarios',
        'fields' => 'usuario',
        'conditions' => array(
            'roles.code' => 'editor'
        )
    ));*/

    $admins = $Db->select(array(
        'table' => 'usuarios',
        'fields' => array('usuario', 'nome', 'apelido1', 'apelido2'),
        'group' => 'usuarios.id',
        'conditions' => array(
            'nome-url' => $Vars->var['usuarios_importar'],
            'roles.code' => array('nivel-3', 'editor'),
            'activo' => 1
        )
    ));

    array_walk($admins, function (&$value) {
        $value['nome_completo'] = trim($value['nome']['title'].' '.$value['apelido1'].' '.$value['apelido2']);
    });

    $usuario = $Db->select(array(
        'table' => 'usuarios',
        'limit' => 1,
        'conditions' => array(
            'id' => $user['id']
        )
    ));

    $Data->execute('actions|mail.php', array(
        'bcc' => array(simpleArrayColumn($admins, 'usuario')),
        'subject' => __('Rexistro de usuario que quere ser importador'),
        'text' => array(
            'code' => 'usuario-importador',
            'nome' => $usuario['nome']['title'],
            'email' => $usuario['usuario'],
            'profile' => absolutePath('perfil', $usuario['nome']['url']),
            'admins' => implode(', ', simpleArrayColumn($admins, 'nome_completo')),
            'mensaxe' => $Vars->var['mensaxe']
        )
    ));
}

$Session->login('regular', array(
    'username' => $Vars->var['usuario'],
    'password' => $Vars->var['contrasinal'],
    'maintain' => true
));

$Data->execute('actions|session-log.php', array(
    'action' => 'login',
    'status' => true
));

$Vars->message(__('Congratulations! Your user has been registered successfully'), 'ok');

$Data->execute('actions|mail.php', array(
    'to' => array($Vars->var['usuario'], $Vars->var['nome']),
    'text' => array(
        'code' => 'mail-rexistro',
        'name' => $Vars->var['nome'],
        'user' => $Vars->var['usuario'],
        'profile' => absolutePath('perfil')
    )
));

redirect(path('').'?'.time());
