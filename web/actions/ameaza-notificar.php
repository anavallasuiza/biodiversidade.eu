<?php
defined('ANS') or die();

if (empty($user)) {
    $Vars->message(__('Precisas estar logueado para poder realizar esta acción'), 'ko');
    return false;
}

include ($Data->file('acl-ameaza.php'));

if ($ameaza['notificada']) {
    return true;
}

$paises = $Vars->var['paises'];
$territorios = $Vars->var['territorios'];
$texto = $Vars->var['texto'];

$idsPaises = array();
foreach ($paises as $index => $value) {
    if ($value == 1) {
        array_push($idsPaises, $index);
    }
}

$idsTerritorios = array();
foreach ($territorios as $index => $value) {
    if ($value == 1) {
        array_push($idsTerritorios, $index);
    }
}

if (empty($idsPaises) && empty($idsTerritorios)) {
    $Vars->message(__('Debes seleccionar alomenos unha autoridade a que notificar'), 'ko');
    return false;
}

$rexistrosPaises = $Db->select(array(
    'table' => 'paises',
    'conditions' => array(
        'id' => $idsPaises
    )
));

$rexistrosTerritorios = $Db->select(array(
    'table' => 'territorios',
    'conditions' => array(
        'id' => $idsTerritorios
    )
));

$emails = array();
$emails = array_merge($emails, arrayKeyValues($rexistrosPaises, 'email'));
$emails = array_merge($emails, arrayKeyValues($rexistrosTerritorios, 'email'));

if (!empty($emails)) {
    $Db->update(array(
        'table' => 'ameazas',
        'data' => array(
            'notificada' => 1,
        ),
        'limit' => 1,
        'conditions' => array(
            'id' => $ameaza['id']
        )
    ));
    
    $mailText = __('Un usuario de biodiversidade desexa notificarvos a seguinte ameaza %s', $Html->a($ameaza['titulo'], absolutePath('ameaza', $ameaza['url'])));
    
    if ($texto) {
        $mailText .= __(', e adxunta a seguinte mensaxe: %s', $texto);
    }

    $Data->execute('actions|mail.php', array(
        'to' => $emails,
        'subject' => __('Notificación de ameaza %s', $ameaza['titulo']),
        'body' => $mailText
    ));
}

$Vars->message(__('A notificación foi enviada. Moitas grazas polá túa colaboración.'), 'ok');

return true;
