<?php
defined('ANS') or die();

$url = $Vars->var['url'];

$usuario = $Db->select(array(
    'table' => 'usuarios',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'nome-url' => $url,
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'roles',
            'fields' => '*',
            'conditions' => array(
                'enabled' => 1
            )
        )
    )
));

if (empty($usuario)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    return false;
}

foreach ($usuario['roles'] as $relatedRole) {
    $res = $Db->unrelate(array(
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
                    'id' => $relatedRole['id']
                )
            )
        )
    ));
}

$niveis = $Db->select(array(
    'table' => 'roles',
    'fields' => '*',
    'conditions' => array(
        'enabled' => 1,
        'code !=' => 'editor'
    )
));

foreach ($niveis as $nivel) {
    if ($Vars->var[$nivel['code']] != 0) {
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
                        'id' => $nivel['id']
                    )
                )
            )
        ));
    }
}

$Vars->message(__('O nivel foi actualizado correctamente'), 'ok');

return true;
