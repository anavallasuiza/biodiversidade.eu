<?php
defined('ANS') or die();

$texto = $Db->select(array(
    'table' => 'textos',
    'fields' => '*',
    'limit' => 1,
    'conditions' => array(
        'url' => $Vars->var['url'],
        'activo' => 1
    )
));

if (empty($texto)) {
    $Vars->message(__('Sentímolo, pero parece que este contido xa non é accesible.'), 'ko');
    referer(path(''));
}

if ($texto['menu']) {
	$menu_lateral = $Db->select(array(
		'table' => 'textos',
		'fields' => array('titulo', 'url'),
		'conditions' => array(
			'activo' => 1,
			'menu' => $texto['menu']
		)
	));
}

$Html->meta('title', $texto['titulo']);
