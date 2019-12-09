<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

$proxecto = $Db->select(array(
    'table' => 'proxectos',
    'fields' => array('url', 'titulo'),
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['proxecto'],
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'solicitude',
            'fields' => array('avatar', 'nome'),
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'fields' => array('avatar', 'nome'),
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => 'usuario',
            'limit' => 1
        ),
    )
));

if (empty($proxecto)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path('proxectos'));
}

if ($proxecto['usuarios'] || $proxecto['usuarios_solicitude']) {
    return false;
}

$Db->relate(array(
    'name' => 'solicitude',
    'tables' => array(
        array(
            'table' => 'usuarios',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        ),
        array(
            'table' => 'proxectos',
            'limit' => 1,
            'conditions' => array(
                'id' => $proxecto['id']
            )
        )
    )
));

$Data->execute('actions|mail.php', array(
    'to' => $proxecto['usuarios_autor']['usuario'],
    'subject' => __('%s solicitou a participación no proxecto %s', $user['nome']['title'], $proxecto['titulo']),
    'body' => __('Podes xestionar a súa participación dende %s', absolutePath('editores-proxecto', $proxecto['url']))
));

$Data->execute('actions|sub-logs.php', array(
    'table' => 'proxectos',
    'id' => $proxecto['id'],
    'action' => 'participacion'
));

$Vars->message(__('A túa solicitude foi rexistrada correctamente e o xestor deste proxecto avisado para que a revise o antes posible.'), 'ok');

redirect(path());
