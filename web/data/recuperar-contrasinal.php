<?php
defined('ANS') or die();

if ($user) {
    redirect(path('perfil'));
}

$Html->meta('title', __('Recuperar o contrasinal'));
