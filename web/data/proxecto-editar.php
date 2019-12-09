<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-proxecto-editar.php'));

if ($proxecto) {
    $adxuntos = $Db->select(array(
        'table' => 'adxuntos',
        'fields' => '*',
        'conditions' => array(
            'proxectos.id' => $proxecto['id']
        )
    ));
    
    $ameazas = $Db->select(array(
        'table' => 'ameazas',
        'fields_commands' => '`ameazas`.`titulo-'.LANGUAGE.'` as `text`, `ameazas`.`url` as `id`',
        'sort' => 'titulo ASC',
        'conditions' => array(
            'proxectos.id' => $proxecto['id'],
            'activo' => 1
        )
    ));

    $espazos = $Db->select(array(
        'table' => 'espazos',
        'fields_commands' => '`espazos`.`titulo-'.LANGUAGE.'` as `text`, `espazos`.`url` as `id`',
        'sort' => 'titulo ASC',
        'conditions' => array(
            'proxectos.id' => $proxecto['id'],
            'activo' => 1
        )
    ));

    $iniciativas = $Db->select(array(
        'table' => 'iniciativas',
        'fields_commands' => '`iniciativas`.`titulo-'.LANGUAGE.'` as `text`, `iniciativas`.`url` as `id`',
        'sort' => 'titulo ASC',
        'conditions' => array(
            'proxectos.id' => $proxecto['id'],
            'activo' => 1
        )
    ));

    $especies = $Db->select(array(
        'table' => 'especies',
        'fields_commands' => '`especies`.`nome` as `text`, `especies`.`url` as `id`',
        'sort' => 'nome ASC',
        'conditions' => array(
            'proxectos.id' => $proxecto['id'],
            'activo' => 1
        )
    ));
    
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'conditions' => array(
            'proxectos.id' => $proxecto['id'],
            'activo' => 1
        )
    ));
} else {
    $ameazas = $especies = $espazos = $imaxes = $adxuntos = array();
}

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

if ($Data->actions['proxecto-gardar'] === null) {
    $Vars->var['proxectos'] = $proxecto;
    $Vars->var['ameazas'] = $ameazas;
    $Vars->var['iniciativas'] = $iniciativas;
    $Vars->var['especies'] = $especies;
    $Vars->var['espazos'] = $espazos;
} else {
    if ($Vars->var['ameazas']['url']) {
        $Vars->var['ameazas'] = $Db->select(array(
            'table' => 'ameazas',
            'fields_commands' => '`titulo-'.LANGUAGE.'` as `text`, `url` as `id`',
            'sort' => 'titulo ASC',
            'conditions' => array(
                'url' => explode(',', $Vars->var['ameazas']['url'])
            )
        ));
    } else {
        $Vars->var['ameazas'] = array();
    }

    if ($Vars->var['iniciativas']['url']) {
        $Vars->var['iniciativas'] = $Db->select(array(
            'table' => 'iniciativas',
            'fields_commands' => '`titulo-'.LANGUAGE.'` as `text`, `url` as `id`',
            'sort' => 'titulo ASC',
            'conditions' => array(
                'url' => explode(',', $Vars->var['iniciativas']['url'])
            )
        ));
    } else {
        $Vars->var['iniciativas'] = array();
    }

    if ($Vars->var['espazos']['url']) {
        $Vars->var['espazos'] = $Db->select(array(
            'table' => 'espazos',
            'fields_commands' => '`titulo-'.LANGUAGE.'` as `text`, `url` as `id`',
            'sort' => 'titulo ASC',
            'conditions' => array(
                'url' => explode(',', $Vars->var['espazos']['url'])
            )
        ));
    } else {
        $Vars->var['espazos'] = array();
    }

    if ($Vars->var['especies']['url']) {
        $Vars->var['especies'] = $Db->select(array(
            'table' => 'especies',
            'fields_commands' => '`nome` as `text`, `url` as `id`',
            'sort' => 'nome ASC',
            'conditions' => array(
                'url' => explode(',', $Vars->var['especies']['url'])
            )
        ));
    } else {
        $Vars->var['especies'] = array();
    }
}

$Html->meta('title', $proxecto['nome'] ?: __('Alta de proxecto'));
