<?php
defined('ANS') or die();

include ($Data->file('acl-blog.php'));

$posts = $Db->select(array(
    'table' => 'blogs_posts',
    'fields' => '*',
    'limit' => 10,
    'sort' => 'data DESC',
    'conditions' => array(
        'data <=' => date('Y-m-d H:i:s'),
        'activo' => 1
    )
));

$listado = array();

foreach ($posts as $post) {
    $listado[] = array(
        'titulo' => $post['titulo'],
        'link' => absolutePath('post', $blog['url'], $post['url']),
        'texto' => $post['texto'],
        'data_publicacion' => $post['data']
    );
}

$Html->meta('title', $blog['titulo']);
