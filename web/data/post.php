<?php
defined('ANS') or die();

include ($Data->file('acl-blog.php'));
include ($Data->file('acl-post.php'));

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'blogs_posts.id' => $post['id'],
        'activo' => 1,
        'usuarios(autor).activo' => 1
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*',
            'limit' => 1
        )
    )
));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => 'portada desc',
    'conditions' => array(
        'blogs_posts.id' => $post['id'],
        'activo' => 1
    )
));

$posts = $Db->select(array(
    'table' => 'blogs_posts',
    'fields' => '*',
    'limit' => 3,
    'sort' => 'data DESC',
    'conditions' => array(
        'activo' => 1,
        'blogs.id' => $blog['id'],
        'id !=' => $post['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'usuarios',
            'name' => 'autor',
            'fields' => '*'
        )
    )
));

array_walk($posts, function (&$value) use ($blog) {
    $value['blogs'] = $blog;
});

$Html->meta('title', $post['titulo']);
