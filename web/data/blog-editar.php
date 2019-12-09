<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-blog-editar.php'));

$proxectos = $Db->select(array(
    'table' => 'proxectos',
    'sort' => 'titulo ASC',
    'conditions' => array(
        'usuarios.id' => $user['id'],
        'activo' => 1
    )
));

if ($Data->actions['blog-gardar'] === null) {
    $Vars->var['blogs'] = $blog;
    $Vars->var['proxectos'] = simpleArrayColumn($blog['proxectos'], 'id');
}

$Html->meta('title', $blog['titulo'] ?: __('Novo Blog'));
