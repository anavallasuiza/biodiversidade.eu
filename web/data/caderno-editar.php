<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-proxecto-editar.php'));
include ($Data->file('acl-caderno-editar.php'));

if ($caderno) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'sort' => array('portada DESC'),
        'conditions' => array(
            'cadernos.id' => $caderno['id'],
            'activo' => 1
        )
    ));
} else {
    $imaxes = array();
}

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

if ($Data->actions['caderno-gardar'] === null) {
    $Vars->var['cadernos'] = $caderno;
}

$Html->meta('title', $caderno['titulo'] ?: __('Novo caderno en %s', $proxecto['titulo']));
