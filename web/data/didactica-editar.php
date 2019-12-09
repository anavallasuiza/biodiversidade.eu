<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-didactica-editar.php'));

if ($didactica) {
    $adxuntos = $Db->select(array(
        'table' => 'adxuntos',
        'fields' => '*',
        'conditions' => array(
            'didacticas.id' => $didactica['id']
        )
    ));
} else {
    $adxuntos = array();
}

if ($Data->actions['didactica-gardar'] === null) {
    $Vars->var['didacticas'] = $didactica;
}

$Html->meta('title', $didactica['titulo'] ?: __('Alta de Actividade'));
