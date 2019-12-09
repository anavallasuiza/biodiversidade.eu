<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$config['routes'] = array(
    '*' => array(
        'templates' => array(
        	'js' => array(
        		'common|jquery/jquery.min.js',
        		'jquery.mobile-1.3.2/jquery.mobile-1.3.2.min.js',
                'helpers.js'
        	),
        	'css' => array(
        		'templates|js/jquery.mobile-1.3.2/jquery.mobile-1.3.2.min.css',
        		'templates|js/jquery.mobile-1.3.2/jquery.mobile.structure-1.3.2.min.css',
        		'templates|js/jquery.mobile-1.3.2/themes/biodiv.min.css',
        		'estilos.css',
        	),
            'base' => 'html.php'
        )
    ),

    'login' => array(
    	'templates' => array(
    		'content' => 'content-login.php'
    	)
    ),

    'index' => array(
        'templates' => array(
            'js' => array(
                'notas.js',
                'scripts-notas.js',
                'scripts-home.js'
            ),
            'content' => 'content-home.php'
        ),

        'data' => 'home.php'
    ),

    'acerca-de' => array(
        'templates' => array(
            'content' => 'content-acerca-de.php'
        )
    ),

    'notificar-autoridades' => array(
        'templates' => array(
            'content' => 'content-notificar-autoridades-mail.php'
        ),

        'data' => 'notificar-autoridades.php'
    ),

    'detalle/especie/$url' => array(
        'templates' => array(
            'js' => 'scripts-detalle.js',
            'content' => 'content-detalle-especie.php'
        ),

        'data' => 'detalle-especie.php'
    ),

    'detalle/rota/$url' => array(
        'templates' => array(
            'js' => 'scripts-detalle.js',
            'content' => 'content-detalle-rota.php'
        ),

        'data' => 'detalle-rota.php'
    ),

    'resultado/especies' => array(
    	'templates' => array(
    		'content' => 'content-resultado-especies.php'
    	),

        'data' => 'resultado-especies.php'
    ),

    'resultado/rotas' => array(
    	'templates' => array(
    		'content' => 'content-resultado-rotas.php'
    	),

        'data' => 'resultado-rotas.php'
    )
);
