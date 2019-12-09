<?php
defined('ANS') or die();

$blogs = $Db->select(array(
    'table' => 'blogs',
    'fields' => '*',
    'sort' => 'data_actualizado DESC',
    'group' => 'blogs.id',
    'conditions' => array(
        'activo' => 1
    ),
    'limit' => 8,
    'pagination' => array(
        'page' => ($Vars->int('p') ?: 1),
        'map' => 10
    ),
    'add_tables' => array(
        array(
            'table' => 'blogs_posts',
            'fields' => '*',
            'limit' => 1,
            'sort' => 'data DESC',
            'conditions' => array(
                'activo' => 1
            ),
            'add_tables' => array(
                'imaxe' => array(
                    'table' => 'imaxes',
                    'fields' => '*',
                    'sort' => 'portada DESC',
                    'limit' => 1,
                    'conditions' => array(
                        'activo' => 1
                    )
                ),
                array(
                    'table' => 'comentarios',
                    'fields' => 'id',
                    'conditions' => array(
                        'activo' => 1
                    )
                )
            )
        )
    )
));

array_walk($blogs, function (&$value) {
    $value['blogs_posts']['blogs'] = array('url' => $value['url']);
});

$proxectos = array();

if ($user) {
    $proxectos['meus'] = $Db->select(array(
        'table' => 'proxectos',
        'fields' => '*',
        'sort' => 'data DESC',
        'conditions' => array(
            'usuarios.id' => $user['id'],
            'activo' => 1
        ),
        'add_tables' => array(
            array(
                'table' => 'cadernos',
                'fields' => 'data_actualizado',
                'sort' => 'data_actualizado DESC',
                'limit' => 1
            )
        )
    ));
} else {
    $proxectos['meus'] = array();
}

$proxectos['todos'] = $Db->select(array(
    'table' => 'proxectos',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'id !=' => simpleArrayColumn($proxectos['meus'], 'id'),
        'activo' => 1,
    ),
    'add_tables' => array(
        array(
            'table' => 'cadernos',
            'fields' => 'data_actualizado',
            'sort' => 'data_actualizado DESC',
            'limit' => 1
        ),
        array(
            'table' => 'usuarios',
            'name' => 'solicitude',
            'limit' => 1,
            'conditions' => array(
                'id' => $user['id']
            )
        )
    )
));

usort($proxectos['todos'], function ($a, $b) {
    return strtotime($a['cadernos']['data_actualizado']) > strtotime($b['cadernos']['data_actualizado']) ? -1 : 1;
});

$Html->meta('title', __('Os blogs'));
