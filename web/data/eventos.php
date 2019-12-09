<?php
defined('ANS') or die();

if ($Vars->var[0]) {
    list($year, $month) = explode('-', $Vars->var[0]);

    $year = intval($year);
    $month = intval($month);
}

if (empty($Vars->var[0]) || !checkdate($month, 1, $year)) {
    $seguinte = setRowsLanguage(array(
        'table' => 'eventos',
        'fields' => 'data',
        'limit' => 1,
        'sort' => 'data ASC',
        'conditions' => array(
            'data >=' => date('Y-m-d H:i:s'),
            'activo' => 1
        )
    ));

    $year = date('Y', strtotime($seguinte['data']));
    $month = date('m', strtotime($seguinte['data']));
}

$month = sprintf('%02d', $month);

$ym = $year.'-'.$month;

$eventos = setRowsLanguage(array(
    'table' => 'eventos',
    'fields' => '*',
    'sort' => 'data ASC',
    'conditions' => array(
        'data LIKE' => $ym.'%',
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
            'fields' => 'id'
        )
    )
));

$calendario = array();

foreach ($eventos as $evento) {
    $texto = '<article class="actividade actividade-calendario">';
    $texto .= '<header>';
    $texto .= '<h1><a href="'.path('evento', $evento['url']).'">'.$evento['titulo'].'</a></h1>';

    if ($evento['lugar']) {
        $texto .= '<p class="actividade-lugar">'.$evento['lugar'].'</p>';
    }

    $texto .= '</header></article>';

    $calendario[date('Y-m-d', strtotime($evento['data']))][] = $texto;
}

$Html->meta('title', __('Calendario de eventos'));
