<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Sentímolo, pero este contido é só accesible para usuarios rexistrados.'), 'ko');
    referer(path(''));
}

include ($Data->file('acl-especie-editar.php'));

$Data->execute('acl-action.php', array('action' => 'especie-eliminar'));

$Data->execute('actions|sub-backups.php', array(
    'table' => 'especies',
    'id' => $especie['id'],
    'action' => 'eliminar',
    'content' => $especie
));

$avistamentos = $Db->select(array(
	'table' => 'avistamentos',
    'fields' => 'url',
	'conditions' => array(
		'especies.id' => $especie['id']
	)
));

foreach ($avistamentos as $avistamento) {
	$Data->execute('actions|avistamento-eliminar.php', array(
		'url' => $avistamento['url']
	));
}

//TODO: Add amenaza delete

$Db->delete(array(
    'table' => 'especies',
    'limit' => 1,
    'conditions' => array(
        'id' => $especie['id']
    )
));

$Vars->message(__('A especie foi eliminada correctamente.'), 'ok');

redirect(path('catalogo', 'mapa'));