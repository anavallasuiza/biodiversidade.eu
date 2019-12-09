<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-nova-editar.php'));

if ($nova) {
    $imaxes = $Db->select(array(
        'table' => 'imaxes',
        'fields' => '*',
        'conditions' => array(
            'novas.id' => $nova['id'],
            'activo' => 1
        )
    ));
} else {
    $imaxes = array();
}

$tables = $Config->tables[getDatabaseConnection()];
$licenzas = $tables['imaxes']['licenza']['values'];

if ($Data->actions['nova-gardar'] === null) {
    $Vars->var['novas'] = $nova;
}

$Html->meta('title', $nova['titulo'] ?: __('Alta de noticia'));
