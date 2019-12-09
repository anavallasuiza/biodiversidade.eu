<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$config['actions'] = array(
    'rexistro' => 'rexistro.php',
	'acceso' => 'acceso.php',
    'sair' => 'sair.php',
    'feedback' => 'feedback.php',

    'ameaza-estado' => array(
        'file' => 'ameaza-estado.php',
        'redirect' => true
    ),

    'ameaza-notificar' => array(
        'file' => 'ameaza-notificar.php',
        'redirect' => true
    ),

    'ameaza-gardar' => array(
        'file' => 'ameaza-gardar.php',
        'redirect' => true
    ),

    'ameaza-eliminar' => array(
        'file' => 'ameaza-eliminar.php',
        'redirect' => true
    ),

    'comunidade-gardar' => array(
        'file' => 'comunidade-gardar.php',
        'redirect' => true
    ),

    'comunidade-eliminar' => array(
        'file' => 'comunidade-eliminar.php',
        'redirect' => true
    ),

    'iniciativa-gardar' => array(
        'file' => 'iniciativa-gardar.php',
        'redirect' => true
    ),

    'iniciativa-eliminar' => array(
        'file' => 'iniciativa-eliminar.php',
        'redirect' => true
    ),

	'avistamento-gardar' => array(
        'file' => 'avistamento-gardar.php',
        'redirect' => true
    ),

    'avistamento-eliminar' => array(
        'file' => 'avistamento-eliminar.php',
        'redirect' => true
    ),

    'avistamento-validar' => array(
        'file' => 'avistamento-validar.php',
        'redirect' => true
    ),

    'arquivo-gardar' => array(
        'file' => 'arquivo-gardar.php',
        'redirect' => true
    ),

    'importacion-observacions-gardar' => array(
        'file' => 'importacion-observacions-gardar.php',
        'redirect' => false
    ),

    'especie-gardar' => array(
        'file' => 'especie-gardar.php',
        'redirect' => true
    ),

    'especie-eliminar' => array(
        'file' => 'especie-eliminar.php',
        'redirect' => true
    ),

    'especie-restaurar' => array(
        'file' => 'especie-restaurar.php',
        'redirect' => true
    ),

    'especie-validar' => array(
        'file' => 'especie-validar.php',
        'redirect' => true
    ),

    'vixiar' => array(
        'file' => 'vixiar.php',
        'redirect' => true
    ),

    'comentar' => array(
        'file' => 'comentar.php',
        'redirect' => true
    ),

    'comentario-eliminar' => array(
        'file' => 'comentario-eliminar.php',
        'redirect' => true
    ),

    'votar' => array(
        'file' => 'votar.php',
        'redirect' => true
    ),

    'espazo-gardar' => array(
        'file' => 'espazo-gardar.php',
        'redirect' => true
    ),

    'espazo-eliminar' => array(
        'file' => 'espazo-eliminar.php',
        'redirect' => true
    ),

    'espazo-validar' => array(
        'file' => 'espazo-validar.php',
        'redirect' => true
    ),

    'fix-stuff' => array(
        'file' => 'fix-stuff.php',
        'redirect' => true
    ),

    'conversion-playground' => array(
        'file' => 'conversion-playground.php',
        'redirect' => true 
    ),

    'perfil-gardar' => array(
        'file' => 'perfil-gardar.php',
        'redirect' => true
    ),

    'perfil-gardar-nivel' => array(
        'file' => 'perfil-gardar-nivel.php',
        'redirect' => true
    ),

    'solicitar-importar' => array(
        'file' => 'solicitar-importar.php',
        'redirect' => true
    ),
    
    'perfil-importar' => array(
        'file' => 'perfil-importar.php',
        'redirect' => true
    ),

    'mensaxe' => array(
        'file' => 'mensaxe.php',
        'redirect' => true
    ),

    'recuperar-contrasinal' => array(
        'file' => 'recuperar-contrasinal.php',
        'redirect' => true
    ),

    'confirmar-contrasinal' => array(
        'file' => 'confirmar-contrasinal.php',
        'redirect' => true
    ),

    'blog-gardar' => array(
        'file' => 'blog-gardar.php',
        'redirect' => true
    ),

    'blog-eliminar' => array(
        'file' => 'blog-eliminar.php',
        'redirect' => true
    ),

    'blog-editores' => array(
        'file' => 'blog-editores.php',
        'redirect' => true
    ),

    'post-gardar' => array(
        'file' => 'post-gardar.php',
        'redirect' => true
    ),

    'post-eliminar' => array(
        'file' => 'post-eliminar.php',
        'redirect' => true
    ),

    'nova-gardar' => array(
        'file' => 'nova-gardar.php',
        'redirect' => true
    ),

    'nova-eliminar' => array(
        'file' => 'nova-eliminar.php',
        'redirect' => true
    ),

    'evento-gardar' => array(
        'file' => 'evento-gardar.php',
        'redirect' => true
    ),

    'evento-eliminar' => array(
        'file' => 'evento-eliminar.php',
        'redirect' => true
    ),

    'rota-validar' => array(
        'file' => 'rota-validar.php',
        'redirect' => true
    ),

    'rota-gardar' => array(
        'file' => 'rota-gardar.php',
        'redirect' => true
    ),

    'rota-eliminar' => array(
        'file' => 'rota-eliminar.php',
        'redirect' => true
    ),

    'proxecto-gardar' => array(
        'file' => 'proxecto-gardar.php',
        'redirect' => true
    ),

    'proxecto-editores' => array(
        'file' => 'proxecto-editores.php',
        'redirect' => true
    ),

    'proxecto-solicitude' => array(
        'file' => 'proxecto-solicitude.php',
        'redirect' => true
    ),

    'proxecto-eliminar' => array(
        'file' => 'proxecto-eliminar.php',
        'redirect' => true
    ),

    'caderno-gardar' => array(
        'file' => 'caderno-gardar.php',
        'redirect' => true
    ),

    'caderno-restaurar' => array(
        'file' => 'caderno-restaurar.php',
        'redirect' => true
    ),

    'equipo-gardar' => array(
        'file' => 'equipo-gardar.php',
        'redirect' => true
    ),

    'equipo-eliminar' => array(
        'file' => 'equipo-eliminar.php',
        'redirect' => true
    ),

    'equipo-editores' => array(
        'file' => 'equipo-editores.php',
        'redirect' => true
    ),

    'equipo-solicitude' => array(
        'file' => 'equipo-solicitude.php',
        'redirect' => true
    ),

    'didactica-gardar' => array(
        'file' => 'didactica-gardar.php',
        'redirect' => true
    ),

    'didactica-eliminar' => array(
        'file' => 'didactica-eliminar.php',
        'redirect' => true
    ),

    'nota-gardar' => array(
        'file' => 'nota-gardar.php',
        'redirect' => true
    ),

    'nota-eliminar' => array(
        'file' => 'nota-eliminar.php',
        'redirect' => true
    ),

    'familia-gardar' => array(
        'file' => 'familia-gardar.php',
        'redirect' => true
    ),

    'xenero-gardar' => array(
        'file' => 'xenero-gardar.php',
        'redirect' => true
    ),

    'asociar-avistamentos-rota' => array(
        'file' => 'asociar-avistamentos-rota.php',
        'redirect' => false
    ),

    'asociar-avistamentos-espazo' => array(
        'file' => 'asociar-avistamentos-espazo.php',
        'redirect' => false
    ),

    'asociar-avistamentos-ameaza' => array(
        'file' => 'asociar-avistamentos-ameaza.php',
        'redirect' => false
    ),

    'asociar-avistamentos-iniciativa' => array(
        'file' => 'asociar-avistamentos-iniciativa.php',
        'redirect' => false
    )
);
