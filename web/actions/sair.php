<?php
defined('ANS') or die();

$Session->logout();

$Data->execute('actions|session-log.php', array(
    'action' => 'logout',
    'status' => true
));

unset($user);

if (PATH_0 === 'perfil') {
    redirect(path('').'?'.time());
} else {
    redirect(path().'?'.time());
}
