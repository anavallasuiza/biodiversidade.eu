<?php
defined('ANS') or die();

include ($Data->file('acl-blog.php'));

$posts = $Db->select(array(
    'table' => 'blogs_posts',
    'fields' => '*',
    'limit' => 6,
    'sort' => 'data DESC',
    'conditions' => array(
        'blogs.id' => $blog['id'],
        'activo' => 1
    ),
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'comentarios',
            'fields' => 'id',
            'conditions' => array(
                'activo' => 1
            )
        ),
        'imaxe' => array(
            'table' => 'imaxes',
            'fields' => '*',
            'sort' => 'portada DESC',
            'limit' => 1,
            'conditions' => array(
                'activo' => 1
            )
        )
    )
));

array_walk($posts, function (&$value) use ($blog) {
    $value['blogs'] = $blog;
});

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'limit' => 4,
    'sort' => 'data DESC',
    'group' => 'comentarios.id',
    'conditions' => array(
        'blogs_posts.blogs.id' => $blog['id'],
        'blogs_posts.activo' => 1,
        'activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        ),
        array(
            'table' => 'blogs_posts',
            'fields' => array('titulo', 'url'),
            'limit' => 1,
            'add_tables' => array(
                array(
                    'table' => 'blogs',
                    'fields' => 'url',
                    'limit' => 1
                )
            )
        )
    )
));

$proxectos = $Db->select(array(
    'table' => 'proxectos',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'blogs.id' => $blog['id'],
        'activo' => 1
    )
));

$Html->meta('title', $blog['titulo']);
