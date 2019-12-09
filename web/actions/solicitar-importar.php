<?php
defined('ANS') or die();

foreach (array('importar', 'usuarios', 'grupos', 'mensaxe') as $campo) {
    if (is_string($Vars->var[$campo])) {
        $Vars->var[$campo] = trim($Vars->var[$campo]);
    }

    if (empty($Vars->var[$campo])) {
        $Vars->message(__('Sorry, the <strong>"%s"</strong> field is required.', __($campo)), 'ko');
        return false;
    }
}

if ($Vars->var['importar']) {
    $grupos = $Db->select(array(
        'table' => 'grupos',
        'conditions' => array(
            'nome' => $Vars->var['grupos'],
            'activo' => 1
        )
    ));

    $admins = $Db->select(array(
        'table' => 'usuarios',
        'fields' => array('usuario', 'nome', 'apelido1', 'apelido2'),
        'group' => 'usuarios.id',
        'conditions' => array(
            'nome-url' => $Vars->var['usuarios'],
            'roles.code' => array('nivel-3', 'editor'),
            'activo' => 1
        )
    ));

    array_walk($admins, function (&$value) {
        $value['nome_completo'] = trim($value['nome']['title'].' '.$value['apelido1'].' '.$value['apelido2']);
    });

    $Data->execute('actions|mail.php', array(
        'bcc' => array(simpleArrayColumn($admins, 'usuario')),
        'subject' => __('Solicitude de importar bloques de datos dun usuario'),
        'text' => array(
            'code' => 'usuario-importador',
            'nome' => $user['nome']['title'],
            'email' => $user['usuario'],
            'profile' => absolutePath('perfil', $user['nome']['url']),
            'grupos' => implode(', ', simpleArrayColumn($grupos, 'nome')),
            'admins' => implode(', ', simpleArrayColumn($admins, 'nome_completo')),
            'mensaxe' => $Vars->var['mensaxe']
        )
    ));

    $Db->update(array(
        'table' => 'usuarios',
        'data' => array(
            'solicita_importar' => 1
        ),
        'conditions' => array(
            'id' => $user['id']
        )
    ));

    $Vars->message(__('A túa solicitude vai ser revisada.'), 'ok');
}
