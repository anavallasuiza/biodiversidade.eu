<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-evento-editar.php'));

if ($evento) {
    $adxuntos = $Db->select(array(
        'table' => 'adxuntos',
        'fields' => '*',
        'conditions' => array(
            'eventos.id' => $evento['id']
        )
    ));

    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'conditions' => array(
            'eventos.id' => $evento['id'],
            'activo' => 1
        )
    ));
} else {
    $adxuntos = $imaxes = array();
}

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

if ($Data->actions['evento-gardar'] === null) {
    $Vars->var['eventos'] = $evento;
}

$Html->meta('title', $evento['nome'] ?: __('Alta de evento'));
