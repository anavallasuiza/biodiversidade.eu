<?php
defined('ANS') or die();

foreach (array('usuario', 'mensaxe') as $campo) {
    $Vars->var[$campo] = trim($Vars->var[$campo]);

    if (empty($Vars->var[$campo])) {
        $Vars->message(__('Sorry, the <strong>"%s"</strong> field is required.', __($campo)), 'ko');
        return false;
    }
}

$destinatario = $Db->select(array(
    'table' => 'usuarios',
    'limit' => 1,
    'conditions' => array(
        'id' => $Vars->var['usuario']
    )
));

if (!$destinatario) {
    $Vars->message(__('O usuario destinatario non existe'), 'ko');
    return false;
}

$nomeDestinatario = $destinatario['nome']['title'];
if ($destinatario['apelido1']) {
    $nomeDestinatario .= ' ' . $destinatario['apelido1'];
}
if ($destinatario['apelido2']) {
    $nomeDestinatario .= ' ' . $destinatario['apelido2'];
}

$nomeUsuario = $user['nome']['title'];
if ($user['apelido1']) {
    $nomeUsuario .= ' ' . $user['apelido1'];
}
if ($user['apelido2']) {
    $nomeUsuario .= ' ' . $user['apelido2'];
}


if ($Vars->var['mensaxe']) {
    $Data->execute('actions|mail.php', array(
        'to' => array($destinatario['usuario'], $nomeDestinatario),
        'replyto' => array($user['usuario'], $nomeUsuario),
        'subject' => __('Biodiversidade.eu: Nova mensaxe recibida de') . ' ' . $nomeUsuario,
        'body' => $Vars->var['mensaxe']
    ));

    $Vars->message(__('A túa mensaxe foi enviada.'), 'ok');
}
