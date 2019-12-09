<?php
defined('ANS') or die();

$config['routes2tables'] = array(
    'evento' => array(
        'table' => 'eventos',
        'url' => 'url'
    ),
    'nova' => array(
        'table' => 'novas',
        'url' => 'url'
    ),
    'ameaza' => array(
        'table' => 'ameazas',
        'url' => 'url',
        'vixiar' => 'ameazas(vixiar).id'
    ),
    'iniciativa' => array(
        'table' => 'iniciativas',
        'url' => 'url',
        'vixiar' => 'iniciativas(vixiar).id'
    ),
    'avistamento' => array(
        'table' => 'avistamentos',
        'url' => 'url',
        'vixiar' => 'avistamentos(vixiar).id'
    ),
    'caderno' => array(
        'table' => 'cadernos',
        'url' => 'url',
        'vixiar' => 'proxectos(vixiar).cadernos.id'
    ),
    'especie' => array(
        'table' => 'especies',
        'url' => 'url',
        'vixiar' => 'especies(vixiar).id'
    ),
    'rota' => array(
        'table' => 'rotas',
        'url' => 'url',
        'vixiar' => 'rotas(vixiar).id'
    ),
    'espazo' => array(
        'table' => 'espazos',
        'url' => 'url',
        'vixiar' => 'espazos(vixiar).id'
    ),
    'post' => array(
        'table' => 'blogs_posts',
        'url' => 'url',
        'vixiar' => 'blogs(vixiar).blogs_posts.id'
    ),
    'proxecto' => array(
        'table' => 'proxectos',
        'url' => 'url',
        'var' => 'proxecto',
        'vixiar' => 'proxectos.id'
    )
);

$config['tables2routes'] = array(
    'novas' => array(
        'route' => 'nova',
        'title' => 'titulo',
        'url' => 'url'
    ),
    'ameazas' => array(
        'route' => 'ameaza',
        'url' => 'url'
    ),
    'iniciativas' => array(
        'route' => 'iniciativa',
        'url' => 'url'
    ),
    'avistamentos' => array(
        'route' => 'avistamento',
        'url' => 'url'
    ),
    'cadernos' => array(
        'route' => 'caderno',
        'url' => 'url'
    ),
    'especies' => array(
        'route' => 'especie',
        'url' => 'url'
    ),
    'rotas' => array(
        'route' => 'rota',
        'url' => 'url'
    ),
    'espazos' => array(
        'route' => 'espazo',
        'url' => 'url'
    ),
    'eventos' => array(
        'route' => 'evento',
        'url' => 'url'
    ),
    'blogs_posts' => array(
        'route' => 'post',
        'url' => 'url'
    ),
    'proxectos' => array(
        'route' => 'proxecto',
        'url' => 'url'
    )
);
