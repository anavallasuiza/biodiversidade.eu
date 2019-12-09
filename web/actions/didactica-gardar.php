<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-didactica-editar.php'));

$f = $Vars->var['didacticas'];

$action = $didactica ? 'update' : 'insert';

$query = array(
    'table' => 'didacticas',
    'data' => array(
        'url' => ($didactica['url'] ?: $f['titulo']),
        'titulo' => $f['titulo'],
        'intro' => $f['intro'],
        'xustificacion' => $f['xustificacion'],
        'desenvolvemento' => $f['desenvolvemento'],
        'obxectivos' => $f['obxectivos'],
        'competencias' => $f['competencias'],
        'duracion' => $f['duracion'],
        'material' => $f['material'],
        'recursos' => $f['recursos'],
        'data' => ($didactica['data'] ?: date('Y-m-d H:i:s')),
        'idioma' => ($didactica['idioma'] ?: LANGUAGE),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $didactica['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($didactica['usuarios_autor']['id'] ?: $user['id'])
            )
        )
    )
);

$query = translateQuery($query, $didactica['id']);

$id_didactica = $Db->$action($query);

if (empty($id_didactica)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'didacticas',
        'id' => $id_didactica,
        'action' => 'crear'
    ));

    $Vars->message(__('A unidade didactica foi creada correctamente'), 'ok');
} else {
    $Data->execute('actions|sub-backups.php', array(
        'table' => 'didacticas',
        'id' => $didactica['id'],
        'action' => 'editar',
        'content' => $didactica
    ));

    $Vars->message(__('A unidade didactica foi actualizada correctamente'), 'ok');
}

$didactica = $Db->select(array(
    'table' => 'didacticas',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($didactica['id'] ?: $id_didactica)
    )
));

$Data->execute('actions|sub-adxuntos.php', array(
    'table' => 'didacticas',
    'id' => $didactica['id'],
    'adxuntos' => $Vars->var['adxuntos']
));

redirect(path('didactica', $didactica['url']));
