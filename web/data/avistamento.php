<?php
defined('ANS') or die();

include ($Data->file('acl-avistamento.php'));

$autor = $avistamento['usuarios_autor'];

if ($avistamento['idcoleccion'] || $avistamento['numero_colleita'] ||$avistamento['colector'] ||
$avistamento['banco_xeoplasma'] || $avistamento['xermoplasma_herbario'] || $avistamento['xermoplasma_herbario_numero'] ||
$avistamento['arquivo_gps'] || $avistamento['profundidade_auga'] || $avistamento['pendente'] ||
$avistamento['orientacion'] || $avistamento['metodo_muestreo'] || $avistamento['adultas_localizadas'] ||
$avistamento['adultas_mostreadas'] || $avistamento['area_mostreada'] || $avistamento['area_ocupacion'] ||
$avistamento['fenoloxia_froito'] || $avistamento['procedencia_semente'] || $avistamento['tipo_vexetacion'] ||
$avistamento['textura_solo'] || $avistamento['observacions_xermoplasma']) {
    $avanzadoXermoplasma = true;
}

if ($avistamento['distancia_umbral'] || $avistamento['definicion_individuo'] || $avistamento['coroloxia_herbario'] ||
$avistamento['coroloxia_herbario_numero'] || $avistamento['numero_exemplares'] || $avistamento['area_presencia_shapefile'] ||
$avistamento['tipo_censo'] || $avistamento['superficie_ocupacion'] || $avistamento['densidade_ocupacion'] ||
$avistamento['area_prioritaria'] || $avistamento['area_potencial'] || $avistamento['superficie_mostreada'] ||
$avistamento['individuos_contados'] || $avistamento['superficie_potencial'] || $avistamento['densidade'] ||
$avistamento['individuos_estimados'] || $avistamento['observacions_coroloxia']) {
    $avanzadoCoroloxia = true;
}

if ($avistamento['idcoleccion'] || $avistamento['numero_colleita'] || $avistamento['colector']) {
    $avanzadoExtra = true;
}

$seguidores = $Db->select(array(
    'table' => 'usuarios',
    'sort' => 'nome ASC',
    'conditions' => array(
        'activo' => 1,
        'avistamentos(vixiar).id' => $avistamento['id']
    )
));

$imaxes = $Db->select(array(
    'table' => 'imaxes',
    'fields' => '*',
    'sort' => array('portada DESC'),
    'conditions' => array(
        'avistamentos.id' => $avistamento['id'],
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

$acompanhantes = $Db->select(array(
    'table' => 'especies',
    'fields' => array('nome', 'url'),
    'sort' => 'nome ASC',
    'conditions' => array(
        'avistamentos(acompanhantes).id' => $avistamento['id'],
        'activo' => 1
    )
));

$puntos = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'puntos_tipos.numero' => 4,
        'avistamentos.id' => $avistamento['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'puntos_tipos',
            'limit' => 1
        )
    )
));

$shapes = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'puntos_tipos.numero' => 3,
        'arquivo >' => 0,
        'avistamentos.id' => $avistamento['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'puntos_tipos',
            'limit' => 1
        )
    )
));

$centroides1 = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'puntos_tipos.numero' => 1,
        'avistamentos.id' => $avistamento['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'puntos_tipos',
            'limit' => 1
        )
    )
));

$centroides10 = $Db->select(array(
    'table' => 'puntos',
    'conditions' => array(
        'puntos_tipos.numero' => 2,
        'avistamentos.id' => $avistamento['id']
    ),
    'add_tables' => array(
        array(
            'table' => 'puntos_tipos',
            'limit' => 1
        )
    )
));

$puntos = preg_replace('/"/', '\'', json_encode($puntos));
$shapes = preg_replace('/"/', '\'', json_encode($shapes));
$centroides10 = preg_replace('/"/', '\'', json_encode($centroides10));
$centroides1 = preg_replace('/"/', '\'', json_encode($centroides1));

if ($avistamento['validado']) {
    $avistamento['validador'] = $avistamento['usuarios_validador']['nome']['title'].' '.$avistamento['usuarios_validador']['apelido1'];
    $avistamento['validador-url'] = $avistamento['usuarios_validador']['nome']['url'];
}

$comentarios = $Db->select(array(
    'table' => 'comentarios',
    'fields' => '*',
    'sort' => 'data DESC',
    'conditions' => array(
        'avistamentos.id' => $avistamento['id'],
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

if ($avistamento['especies']['nome']) {
    $Html->meta('title', $avistamento['especies']['nome']);
} else {
    $Html->meta('title', $avistamento['posible'] ? __('Posible %s', $avistamento['posible']) : __('Sen identificar'));
}

if ($imaxes) {
    $Html->meta('image', fileWeb('uploads|'.$imaxes[0]['imaxe']));
}