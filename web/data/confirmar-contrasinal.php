<?php
defined('ANS') or die();

if ($user) {
    redirect(path(''));
}

$Html->meta('title', __('Recupera o teu contrasinal'));

include_once ($Data->file('acl-confirmar-contrasinal.php'));
