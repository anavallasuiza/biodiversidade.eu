<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

include ($Data->file('acl-ameaza.php'));

if ($ameaza['usuarios_autor']['notificacions']
&& ($ameaza['usuarios_autor']['notificacions'] !== $user['id'])) {
    $Data->execute('actions|mail.php', array(
        'to' => $ameaza['usuarios_autor']['usuario'],
        'subject' => __('Cambio de estado dunha ameaza creada por ti'),
        'text' => __('O usuario %s notificou un cambio no estado da túa ameaza %s', $user['name'], $Html->a($ameaza['titulo'], absolutePath('ameaza', $ameaza['url'])))
    ));
}

$Data->execute('actions|sub-logs.php', array(
    'table' => 'ameazas',
    'id' => $ameaza['id'],
    'action' => ('ameazas-estado-'.($ameaza['estado'] ? 'desactiva' : 'activa'))
));

$Vars->message(__('O autor desta ameaza foi avisado do cambio de estado.'), 'ok');

return true;
