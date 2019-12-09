<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-blog-editar.php'));
include ($Data->file('acl-post-editar.php'));

if ($post) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'sort' => array('portada DESC'),
        'conditions' => array(
            'blogs_posts.id' => $post['id'],
            'activo' => 1
        ),
        'add_tables' => array(
            array(
                'table' => 'imaxes_tipos',
                'fields' => '*',
                'limit' => 1,
                'conditions' => array(
                    'activo' => 1
                )
            )
        )
    ));
} else {
    $imaxes = array();
}

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

if ($Data->actions['post-gardar'] === null) {
    $Vars->var['posts'] = $post;
}

$Html->meta('title', $post['titulo'] ?: __('Novo Post en %s', $blog['titulo']));
