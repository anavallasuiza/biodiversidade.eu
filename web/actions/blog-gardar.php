<?php
defined('ANS') or die();

if (empty($user)) {
    return false;
}

include ($Data->file('acl-blog-editar.php'));

$f = $Vars->var;

$action = $blog ? 'update' : 'insert';

$query = array(
    'table' => 'blogs',
    'data' => array(
        'url' => $f['blogs']['titulo'],
        'titulo' => $f['blogs']['titulo'],
        'texto' => str_replace("\n", '<br />', strip_tags($f['blogs']['texto'])),
        'imaxe' => $f['blogs']['imaxe'],
        'data' => ($blog['data'] ?: date('Y-m-d H:i:s')),
        'activo' => 1
    ),
    'limit' => 1,
    'conditions' => array(
        'id' => $blog['id']
    ),
    'relate' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'limit' => 1,
            'conditions' => array(
                'id' => ($blog['usuarios_autor']['id'] ?: $user['id'])
            )
        ),
        array(
            'table' => 'usuarios',
            'name' => 'vixiar',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
);

$id_blog = $Db->$action($query);

if (empty($id_blog)) {
    $Vars->message($Errors->getList(), 'ko');
    return false;
}

if ($action === 'insert') {
    $Data->execute('actions|sub-logs.php', array(
        'table' => 'blogs',
        'id' => $id_blog,
        'action' => 'crear'
    ));

    $Vars->message(__('O blog foi creado correctamente'), 'ok');
} else {
    $id_blog = $blog['id'];

    $Data->execute('actions|sub-backups.php', array(
        'table' => 'blogs',
        'id' => $blog['id'],
        'action' => 'editar',
        'content' => $blog
    ));

    $Vars->message(__('O blog foi actualizado correctamente'), 'ok');
}

$relate = array(
    array(
        'exists' => $f['proxectos']['id'],
        'table' => 'proxectos',
        'name' => null,
        'conditions' => array(
            'id' => $f['proxectos']['id'],
            'usuarios.id' => $user['id']
        )
    )
);

foreach ($relate as $relation) {
    $Db->unrelate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'blogs',
                'conditions' => array(
                    'id' => $id_blog
                )
            ),
            array(
                'table' => $relation['table'],
                'conditions' => 'all'
            )
        )
    ));

    if (empty($relation['exists'])) {
        continue;
    }

    $Db->relate(array(
        'name' => $relation['name'],
        'tables' => array(
            array(
                'table' => 'blogs',
                'conditions' => array(
                    'id' => $id_blog
                ),
                'limit' => 1
            ),
            array(
                'table' => $relation['table'],
                'conditions' => $relation['conditions'],
                'limit' => $relation['limit']
            )
        )
    ));
}

$blog = $Db->select(array(
    'table' => 'blogs',
    'fields' => 'url',
    'limit' => 1,
    'conditions' => array(
        'id' => ($blog['id'] ?: $id_blog)
    )
));

redirect(path('blog', $blog['url']));
