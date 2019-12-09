<?php
defined('ANS') or die();

$url = $Vars->var['url'] ?: $user['nome']['url'];

$usuario = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'nome-url' => $url,
        'activo' => 1
    )
));

if (empty($usuario)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('perfil'));
}

if (in_array('nivel-3', arrayKeyValues($user['roles'], 'code')) && $usuario['solicita_importar']) {
    
    $res = $Db->relate(array(
        'tables' => array(
            array(
                'table' => 'usuarios',
                'conditions' => array(
                    'id' => $usuario['id']
                )
            ),
            array(
                'table' => 'roles',
                'limit' => 1,
                'conditions' => array(
                    'code' => 'nivel-2'
                )
            )
        )
    ));
    
    $res = $Db->update(array(
        'table' => 'usuarios',
        'data' => array(
            'solicita_importar' => 0
        ),
        'conditions' => array(
            'id' => $usuario['id']
        )
    ));
    
    $Vars->message(__('O usuario xa ten permisos para importar bloques de datos'), 'ok');

    $Data->execute('actions|mail.php', array(
        'to' => array($usuario['usuario']),
        'subject' => __('Xa tes permisos para importar bloques de datos'),
        'text' => array(
            'code' => 'usuario-importador-permiso',
            'profile' => absolutePath('perfil', $usuario['nome']['url'])
        )
    ));
}

redirect(path('perfil', $usuario['nome']['url']));
