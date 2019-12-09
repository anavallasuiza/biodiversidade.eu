<?php
/**
* phpCan - http://idc.anavallasuiza.com/
*
* phpCan is released under the GNU Affero GPL version 3
*
* More information at license.txt
*/

defined('ANS') or die();

$mapsApi = 'http://maps.googleapis.com/maps/api/js?sensor=false&v=3.13&key=';

$config['routes'] = array(
	'*' => array(
		'templates' => array(
			'base' => 'html.php',
			'css' => array(
				'csans.css',
				'csans.helpers.css',
				'templates|js/lib/fancybox/jquery.fancybox.css',
				'common|jquery.colorbox/example2/colorbox.css',
				'elastislide.css',
				'templates|js/lib/select2/select2.css',
				'jquery-ui-1.10.2.custom.min.css',
				'jquery-ui-timepicker-addon.css',
				'templates|js/lib/cookiebar/jquery.cookiebar.css',
				'$estilos.css',
				'$layout.css',
			),
			'js' => array(
				'common|jquery/jquery.min.js',
				'common|jquery.colorbox/jquery.colorbox.min.js',
				'common|ckeditor/ckeditor.js',

				'lib/fancybox/jquery.fancybox.pack.js',
				'lib/cookiebar/jquery.cookiebar.js',
				'lib/select2/select2.js',
				'lib/jquery.ansslider.js',
				'lib/modernizr.custom.17475.js',
				'lib/jquery.scrollTo.min.js',
				'lib/jquerypp.custom.js',
				'lib/jquery.elastislide.js',
				'lib/jquery-ui-1.10.2.custom.min.js',
				'lib/jquery-ui-timepicker-addon.js',
				'lib/jquery-ui-timepicker-es.js',
				'lib/jquery-ui-timepicker-gl.js',
				'lib/jquery-ui-timepicker-pt.js',
                '$lib/feedback.js',

				'$i18n/i18n.js',
				'$config/config.js',

				'modules/common.js',
				'modules/common.ui.js',
                'modules/login.js'/*,

                'lib/togeojson.js',
                'modules/kml.boxer.js'*/
			)
		),
		'templates#ajax' => array(
			'base' => 'html-ajax.php'
		)
	),

	'index' => array(
		'templates' => array(
			'contido' => 'contido-index.php',
			'js' => array(
				$mapsApi.'&libraries=drawing',
				'lib/raphael-min.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

                '$config/config.portada.js',

                'modules/common.map.js',
                'modules/portada.js'
			)
		),

		'data' => 'index.php'
	),

	'buscar' => array(
		'templates' => array(
			'contido' => 'contido-buscar.php'
		),

		'data' => 'buscar.php'
	),

	'axuda' => array(
		'templates' => array(
			'contido' => 'contido-axuda.php'
		),

		'data' => 'axuda.php'
	),

	'axuda-pregunta' => array(
		'templates' => array(
			'contido' => 'contido-axuda-pregunta.php'
		),

		'data' => 'axuda-pregunta.php'
	),

	'perfil/$url?' => array(
		'templates' => array(
			'contido' => 'contido-perfil.php'
		),

		'data' => 'perfil.php'
	),

	'perfil-editar' => array(
		'templates' => array(
			'contido' => 'contido-perfil-editar.php',
            'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js'
            )
		),

		'data' => 'perfil-editar.php'
	),

    'perfil/$url/mensaxe' => array(
        'templates' => array(
            'contido' => 'contido-perfil-mensaxe.php'
        ),

        'data' => 'perfil-mensaxe.php'
    ),

    'nota/$url' => array(
        'templates' => array(
            'js' => array(
                'http://maps.googleapis.com/maps/api/js?sensor=false',
                'lib/map-simple.js'
            ),
            'contido' => 'contido-nota.php',
        ),

        'data' => 'nota.php'
    ),

    'editar/nota/$url' => array(
        'templates' => array(
            'contido' => 'contido-form-nota.php'
        ),

        'data' => 'nota-editar.php'
    ),

	'equipo/$url' => array(
		'templates' => array(
			'contido' => 'contido-equipo.php'
		),

		'data' => 'equipo.php'
	),

	'editar/equipo/$url?' => array(
		'templates' => array(
            'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js'
            ),
			'contido' => 'contido-form-equipo.php'
		),

		'data' => 'equipo-editar.php'
	),

	'editores-equipo/$url' => array(
		'templates' => array(
			'contido' => 'contido-editores-equipo.php',
            'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js'
            )
		),

		'data' => 'editores-equipo.php'
	),

	'equipos' => array(
		'templates' => array(
			'contido' => 'contido-equipos.php'
		),

		'data' => 'equipos.php'
	),

	'entrar' => array(
		'templates' => array(
			'contido' => 'contido-entrar.php',
            'js' => array(
                '$i18n/i18n.js',
                'config/$config.js',

                'modules/login.js'
            )
		),

		'data' => 'entrar.php'
	),

    'recuperar/contrasinal' => array(
        'templates' => array(
            'contido' => 'contido-recuperar-contrasinal.php',
        ),

        'data' => 'recuperar-contrasinal.php'
    ),

    'confirmar/contrasinal/$code' => array(
        'templates' => array(
            'contido' => 'contido-confirmar-contrasinal.php',
        ),

        'data' => 'confirmar-contrasinal.php'
    ),

	'editores-blog/$blog' => array(
		'templates' => array(
			'contido' => 'contido-editores-blog.php',
            'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js'
            )
		),

		'data' => 'editores-blog.php'
	),

	'editar/especie/$url?' => array(
		'templates' => array(
			'js' => array(
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                '$i18n/i18n.especie.js',

                'config/$config.js',
                'config/$config.especie.js',

				'modules/common.forms.js',
                'modules/especie.crear.js'
			),

			'contido' => 'contido-form-especie.php'
		),

		'data' => 'especie-editar.php'
	),

	'editar/ameaza/$url?' => array(
		'templates' => array(
			'js' => array(
                $mapsApi.'&libraries=drawing',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
                'lib/raphael-min.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.drawing.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.ameazas.js',
				'$config/config.ameaza.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/ameaza.editar.js'
			),

			'contido' => 'contido-form-ameaza.php'
		),

		'data' => 'ameaza-editar.php'
	),

	'editar/iniciativa/$url?' => array(
		'templates' => array(
			'js' => array(
                $mapsApi.'&libraries=drawing',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
                'lib/raphael-min.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.drawing.js',
                'lib/mapas/mapas.shapes.js',

				'$config/config.iniciativa.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/iniciativa.editar.js'
			),

			'contido' => 'contido-form-iniciativa.php'
		),

		'data' => 'iniciativa-editar.php'
	),

	'editar/rota/$url?' => array(
		'templates' => array(
			'js' => array(
                $mapsApi.'&libraries=drawing,geometry',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
                'lib/raphael-min.js',
                'lib/RouteBoxer.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.drawing.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.rota.js',
				'$config/config.rota.js',
                '$config/config.rota-editar.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/rota.editar.js'

			),

			'contido' => 'contido-form-rota.php'
		),

		'data' => 'rota-editar.php'
	),

	'editar/blog/$blog?' => array(
		'templates' => array(
			'contido' => 'contido-form-blog.php',
            'js' => array(
				'lib/jquery.imagelist.js',
				'lib/jquery.fileinput.js',
				'modules/common.forms.js'
			)
		),

		'data' => 'blog-editar.php'
	),

	'editar/proxecto/$proxecto?' => array(
		'templates' => array(
			'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
                '$i18n/i18n.js',
                'config/$config.js',
				'modules/common.forms.js'
			),

			'contido' => 'contido-form-proxecto.php'
		),

		'data' => 'proxecto-editar.php'
	),

	'editores-proxecto/$proxecto' => array(
		'templates' => array(
			'contido' => 'contido-editores-proxecto.php',
            'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js'
            )
		),

		'data' => 'editores-proxecto.php'
	),

	'editar/evento/$url?' => array(
		'templates' => array(
			'js' => array(
				'lib/jquery.imagelist.js',
				'lib/jquery.fileinput.js',
				'modules/common.forms.js'
			),
			'contido' => 'contido-form-evento.php'
		),

		'data' => 'evento-editar.php'
	),

	'editar/espazo/$url?' => array(
		'templates' => array(
			'contido' => 'contido-form-espazo.php',
			'js' => array(
                $mapsApi.'&libraries=drawing',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.drawing.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.espazo.js',
				'$config/config.espazo.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/espazo.editar.js'
			)
		),

		'data' => 'espazo-editar.php'
	),

	'editar/avistamento/$url?' => array(
		'templates' => array(
			'js' => array(
                $mapsApi.'&libraries=drawing',

				'lib/raphael-min.js',
				'lib/jquery.imagelist.js',
				'lib/jquery.fileinput.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.avistamento.js',
				'$config/config.avistamento.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/avistamento.editar.js'
			),

			'contido' => 'contido-form-avistamento.php'
		),

		'data' => 'avistamento-editar.php'
	),

	'editar-grupo/avistamento' => array(
		'templates' => array(
			'contido' => 'contido-form-grupo-avistamento.php'
		),

		'data' => 'avistamento-editar-grupo.php'
	),

	'catalogo/$taxon?/$subtaxon?' => array(
		'templates' => array(
			'contido' => 'contido-catalogo.php',
			'js' => array(
				'$i18n/i18n.catalogo.js',
				'$config/config.catalogo.js',
				'$config/config.catalogo-listado.js',

				'modules/catalogo.js',
				'modules/catalogo.listado.js',
				'modules/catalogo.especies.js'
			)
		),

		'data' => 'catalogo.php'
	),

	'get-especies-catalogo' => array(
		'templates' => array(
			'base' => 'aux-items-catalogo.php'
		),

		'data' => 'get-especies-catalogo.php'
	),

	'catalogo/mapa/$especie?' => array(
		'templates' => array(
			'contido' => 'contido-catalogo-mapa.php',
			'css' => array('jquery.qtip.min.css'),
			'js' => array(
                $mapsApi.'&libraries=drawing',

				'lib/jquery.waitforimages.min.js',
				'lib/jquery.ansminislider.js',
				'lib/jquery.qtip.min.js',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

				'lib/raphael-min.js',

				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
				'lib/mapas/mapas.grid.js',
				'lib/mapas/mapas.infobox.js',
				'lib/mapas/mapas.imageoverlay.js',

				'$i18n/i18n.catalogo.js',
				'$config/config.catalogo.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/catalogo.mapa.js',
				'modules/catalogo.especies.js',
				'modules/catalogo.avistamentos.js',
				'modules/catalogo.puntos.js'
			)
		),

		'data' => 'catalogo-mapa.php'
	),

    'catalogo/areas' => array(
		'templates' => array(
			'contido' => 'contido-catalogo-area.php',
			'css' => array('jquery.qtip.min.css'),
			'js' => array(
                $mapsApi.'&libraries=drawing,geometry',

				'lib/jquery.waitforimages.min.js',
				'lib/jquery.ansminislider.js',
				'lib/jquery.qtip.min.js',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

				'lib/raphael-min.js',

				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
				'lib/mapas/mapas.grid.js',
				'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.drawing.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.catalogo.js',
				'$config/config.catalogo.js',

				'modules/common.forms.js',
                'modules/common.map.js',
                'modules/avistamento.common.js',
				'modules/catalogo.mapa.js',
                'modules/catalogo.area.js'
			)
		),

		'data' => 'catalogo-area.php'
	),

	'catalogo/sen-identificar' => array(
		'templates' => array(
			'contido' => 'contido-sen-identificar.php'
		),
		'data' => 'sen-identificar.php'
	),

	'documentacion' => array(
		'templates' => array(
			'contido' => 'contido-documentacion.php'
		),

		'data' => 'documentacion.php'
	),

	'actividades-didacticas' => array(
		'templates' => array(
			'contido' => 'contido-actividades-didacticas.php'
		),

		'data' => 'actividades-didacticas.php'
	),

	'editar/didactica/$url?' => array(
		'templates' => array(
            'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js'
            ),
			'contido' => 'contido-form-didactica.php'
		),

		'data' => 'didactica-editar.php'
	),

	'didactica/$url' => array(
		'templates' => array(
			'contido' => 'contido-didactica.php'
		),

		'data' => 'didactica.php'
	),

	'get-especies' => array(
		'data' => 'get-especies.php'
	),

    'get-especie-imaxes/$codigo' => array(
        'templates' => array(
			'base' => 'aux-gallery.php'
		),

		'data' => 'get-especie-imaxes.php',
	),

    'especie-backup/$id' => array(
        'templates' => array(
            'js' => array(
                'modules/especie.backup.js'
            ),
            'contido' => 'content-backup-especie.php',
        ),

        'data' => 'backup-especie.php'
    ),

    'solicitar-importar' => array(
        'templates' => array(
            'contido' => 'contido-solicitar-importar.php',
            'js' => array(
                '$i18n/i18n.js',
                'config/$config.js',

                'modules/login.js'
            )
        ),

        'data' => 'solicitar-importar.php'
    ),

    'actualizar-nivel/$id' => array(
        'templates' => array(
            'contido' => 'contido-actualizar-nivel.php',
            'js' => array(
                '$i18n/i18n.js',
                'config/$config.js',

                'modules/login.js'
            )
        ),

        'data' => 'actualizar-nivel.php'
    ),

	'get-provincias/$codigo' => array(
		'data' => 'get-listado-provincias.php'
	),

	'get-concellos/$codigo' => array(
		'data' => 'get-listado-concellos.php'
	),

	'get-avistamentos' => array(
		'templates' => array(
			'base' => 'aux-avistamentos-listado.php'
		),
		'data' => 'get-avistamentos.php'
	),

	'get-familias' => array(
		'templates' => array(
			//'base' => 'aux-familias-listado.php'
			'base' => 'aux-items-selector.php'
		),
		'data' => 'get-familias.php'
	),

    'get-listado-reinos' => array(
		'templates' => array(
			'base' => 'aux-items-selector.php'
		),
		'data' => 'get-listado-reinos.php'
	),

	'get-listado-clases' => array(
		'templates' => array(
			'base' => 'aux-items-selector.php'
		),
		'data' => 'get-listado-clases.php'
	),

	'get-listado-ordes' => array(
		'templates' => array(
			'base' => 'aux-items-selector.php'
		),
		'data' => 'get-listado-ordes.php'
	),

	'get-listado-familias' => array(
		'templates' => array(
			'base' => 'aux-items-selector.php'
		),
		'data' => 'get-listado-familias.php'
	),

    'get-xeneros' => array(
        'data' => 'get-xeneros.php'
    ),

	'get-listado-xeneros' => array(
		'templates' => array(
			'base' => 'aux-items-selector.php'
		),
		'data' => 'get-listado-xeneros.php'
	),

	'get-listado-especies' => array(
		'templates' => array(
			'base' => 'aux-especies-selector.php'
		),
		'data' => 'get-listado-especies.php'
	),

	'get-listado-selector/$catalogo?' => array(
		'templates' => array(
			'base' => 'aux-filtro-selector.php'
		),
		'data' => 'get-listado-selector.php'
	),

	'get-usuarios' => array(
		'data' => 'get-usuarios.php'
	),

	'get-ameazas' => array(
		'data' => 'get-ameazas.php'
	),

	'get-espazos' => array(
		'data' => 'get-espazos.php'
	),

    'get-iniciativas' => array(
        'data' => 'get-iniciativas.php'
    ),

    'get-especie-comparador/$reino/$especie' => array(
        'data' => 'get-especie-comparador.php',
        'templates' => array(
            'base' => 'aux-fila-comparador.php'
        )
    ),

	'avistamentos' => array(
		'templates' => array(
			'contido' => 'contido-avistamentos.php'
		),

		'data' => 'avistamentos.php'
	),

	'comparador/$reino' => array(
		'templates' => array(
			'contido' => 'contido-comparador.php',
            'js' => array(
                '$config/config.comparador.js',
                '$i18n/i18n.comparador.js',

                'modules/comparador.js'
            )
		),
        'data' => 'comparador.php'
	),

	'avistamento/$url' => array(
		'templates' => array(
			'contido' => 'contido-avistamento.php',
			'js' => array(
                $mapsApi.'&libraries=drawing',

				'lib/raphael-min.js',

				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
				'lib/mapas/mapas.grid.js',

                '$config/config.avistamento.js',

                'modules/common.map.js',
                'modules/avistamento.js'
			)
		),

		'data' => 'avistamento.php'
	),

	'coroloxia' => array(
		'templates' => array(
			'contido' => 'contido-coroloxia.php'
		),

		'data' => 'avistamentos.php'
	),

	'xermoplasma' => array(
		'templates' => array(
			'contido' => 'contido-xermoplasma.php'
		),

		'data' => 'avistamentos.php'
	),

	'familia' => array(
		'templates' => array(
			'contido' => 'contido-familia.php'
		),

		'data' => 'familia.php'
	),

	'rotas' => array(
		'templates' => array(
			'contido' => 'contido-rotas.php'
		),

		'data' => 'rotas.php'
	),

    'rss/rotas' => array(
        'templates' => array(
            'base' => 'html-rss.php'
        ),

        'data' => 'rotas-rss.php'
    ),

	'rota/$url' => array(
		'templates' => array(
			'contido' => 'contido-rota.php',
            'js' => array(
                $mapsApi.'&libraries=drawing,geometry',
                'http://www.google.com/jsapi?autoload={"modules":[{"name":"visualization","version":"1","packages":["corechart"]}]}',
                'lib/masonry.pkgd.min.js',
                'lib/jquery.ansminislider.js',
                'lib/jquery.waitforimages.min.js',
                'lib/RouteBoxer.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.rota.js',
                '$i18n/i18n.catalogo.js',
				'$config/config.rota.js',

                'modules/common.map.js',
                'modules/avistamento.common.js',
				'modules/rota.js'
            )
		),

		'data' => 'rota.php'
	),

	'espazos' => array(
		'templates' => array(
			'contido' => 'contido-espazos.php'
		),

		'data' => 'espazos.php'
	),

    'rss/espazos' => array(
        'templates' => array(
            'base' => 'html-rss.php'
        ),

        'data' => 'espazos-rss.php'
    ),

	'espazo/$url' => array(
		'templates' => array(
			'contido' => 'contido-espazo.php',
			'js' => array(
                $mapsApi.'&libraries=drawing',
                'lib/jquery.ansminislider.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.espazo.js',
                '$i18n/i18n.catalogo.js',
				'$config/config.espazo.js',

                'modules/common.map.js',
                'modules/avistamento.common.js',
				'modules/espazo.js'
			)
		),

		'data' => 'espazo.php'
	),

	'novas' => array(
		'templates' => array(
			'contido' => 'contido-novas.php'
		),

		'data' => 'novas.php'
	),

	'rss/novas' => array(
		'templates' => array(
			'base' => 'html-rss.php'
		),

		'data' => 'novas-rss.php'
	),

	'nova/$url' => array(
		'templates' => array(
			'contido' => 'contido-nova.php'
		),

		'data' => 'nova.php'
	),

	'editar/nova/$url?' => array(
		'templates' => array(
			'js' => array(
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
				'modules/common.forms.js'
			),
			'contido' => 'contido-form-nova.php',
		),

		'data' => 'nova-editar.php'
	),

	'caderno/$proxecto/$url' => array(
		'templates' => array(
			'contido' => 'contido-caderno.php'
		),

		'data' => 'caderno.php'
	),

    'caderno-backup/$id' => array(
        'data' => 'backup-caderno.php',

        'templates' => array(
            'contido' => 'content-backup-caderno.php'
        )
    ),

	'editar/caderno/$proxecto/$url?' => array(
		'templates' => array(
			'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
                '$i18n/i18n.js',
                'config/$config.js',
				'modules/common.forms.js'
			),
			'contido' => 'contido-form-caderno.php',
		),

		'data' => 'caderno-editar.php'
	),

	'ameazas' => array(
		'templates' => array(
			'contido' => 'contido-ameazas.php',
			'js' => array(
                $mapsApi.'&libraries=drawing',
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
                'lib/raphael-min.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

				'$i18n/i18n.ameazas.js',
				'$config/config.ameazas.js',

				'modules/common.forms.js',
                'modules/common.map.js',
				'modules/ameazas.js'
			)
		),

		'data' => 'ameazas.php'
	),

    'rss/ameazas' => array(
        'templates' => array(
            'base' => 'html-rss.php'
        ),

        'data' => 'ameazas-rss.php'
    ),

	'ameaza/$url' => array(
		'templates' => array(
			'contido' => 'contido-ameaza.php',
			'js' => array(
                $mapsApi.'&libraries=drawing',
				'lib/raphael-min.js',
                'lib/jquery.ansminislider.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

                '$i18n/i18n.ameaza.js',
                '$i18n/i18n.catalogo.js',
                '$config/config.catalogo.js',
				'$config/config.ameaza.js',

                'modules/common.map.js',
                'modules/avistamento.common.js',
				'modules/ameaza.js'
			)
		),

		'data' => 'ameaza.php'
	),

    'ameaza/$url/notificar' => array(
        'templates' => array(
            'contido' => 'contido-ameaza-notificar.php'
        ),

        'data' => 'ameaza-notificar.php'
    ),

	'iniciativa/$url' => array(
		'templates' => array(
			'contido' => 'contido-iniciativa.php',
			'js' => array(
                $mapsApi.'&libraries=drawing',
				'lib/raphael-min.js',
                'lib/jquery.ansminislider.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

                '$i18n/i18n.catalogo.js',
                '$config/config.catalogo.js',
				'$config/config.iniciativa.js',

                'modules/common.map.js',
                'modules/avistamento.common.js',
				'modules/iniciativa.js'
			)
		),

		'data' => 'iniciativa.php'
	),

	'blogs' => array(
		'templates' => array(
			'contido' => 'contido-blogs.php'
		),

		'data' => 'blogs.php'
	),

	'rss/blog/$blog' => array(
		'templates' => array(
			'base' => 'html-rss.php'
		),

		'data' => 'blog-rss.php'
	),


	'blog/$blog' => array(
		'templates' => array(
			'contido' => 'contido-blog.php',
			'base' => 'html-blog.php'
		),

		'data' => 'blog.php'
	),

	'post/$blog/$url' => array(
		'templates' => array(
			'contido' => 'contido-post.php',
			'base' => 'html-blog.php'
		),

		'data' => 'post.php'
	),

	'editar/post/$blog/$url?' => array(
		'templates' => array(
			'js' => array(
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',
				'modules/common.forms.js'
			),
			'contido' => 'contido-form-post.php',
			'base' => 'html-blog.php'
		),

		'data' => 'post-editar.php'
	),

	'proxecto/$proxecto' => array(
		'templates' => array(
			'contido' => 'contido-proxecto.php',
            'js' => array(
                $mapsApi.'&libraries=drawing',
                'lib/jquery.waitforimages.min.js',
				'lib/jquery.ansminislider.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
                'lib/mapas/mapas.infobox.js',
                'lib/mapas/mapas.shapes.js',

				'$config/config.proxecto.js',
                '$i18n/i18n.catalogo.js',
                '$i18n/i18n.proxecto.js',

				'modules/common.map.js',
                'modules/avistamento.common.js',
                'modules/proxecto.js'
			)
		),

		'data' => 'proxecto.php'
	),

	'eventos/*' => array(
		'templates' => array(
			'contido' => 'contido-eventos.php'
		),

		'data' => 'eventos.php'
	),


	'rss/eventos' => array(
		'templates' => array(
			'base' => 'html-rss.php'
		),

		'data' => 'eventos-rss.php'
	),

	'evento/$url' => array(
		'templates' => array(
			'contido' => 'contido-evento.php'
		),

		'data' => 'evento.php'
	),

	'especie/$url' => array(
		'templates' => array(
			'contido' => 'contido-especie.php',
			'js' => array(
				'lib/jquery.trocar.js',
				'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                'i18n/$i18n.js',
                'i18n/$i18n.especie.js',

                'config/$config.js',
                'config/$config.especie.js',

				'modules/common.forms.js',
                'modules/especie.js',
			)
		),

		'data' => 'especie.php'
	),

	'resultados' => array(
		'templates' => array(
			'contido' => 'contido-results.php'
		)
	),

	'avistamentos/editar/$url?' => array(
		'templates' => array(
			'contido' => 'contido-avistamentos-editar.php'
		),

		'data' => 'avistamentos-editar.php'
	),

	'arquivo/editar/$url?' => array(
		'templates' => array(
			'css' => array(
				'templates|js/datatables/css/jquery.dataTables.css',
				'$estilos-subir-arquivo.css'
			),

			'js' => array(
				'lib/datatables/js/jquery.dataTables.js'
			),

			'contido' => 'contido-arquivo-editar.php'
		),

		'data' => 'arquivo-editar.php'
	),

	'importar-observacions' => array(
		'templates' => array(
			'contido' => 'contido-importar-observacions.php',
			'css' => '$estilos-importar-observacions.css'
		),
		'data' => 'importar-observacions-arquivo.php'
	),

	'importar-observacions/previsualizar' => array(
		'templates' => array(
			'contido' => 'contido-importar-observacions-previsualizar.php',
			'css' => array(
				'$estilos-importar-observacions.css'
			),
			'js' => array(
                'lib/jquery.fileinput.js',
                'lib/jquery.imagelist.js',

                '$i18n/i18n.js',
                'config/$config.js',

                'modules/common.forms.js',
				'$modules/importar-observacions.js'
			)
		),
		'data' => 'importar-observacions.php'
	),

	'importar-observacions/resultado' => array(
		'templates' => array(
			'contido' => 'contido-importar-observacions-resultado.php',
			'css' => '$estilos-importar-observacions.css',
		),
	),

	'info/$url' => array(
		'templates' => array(
			'contido' => 'contido-info.php',
		),

		'data' => 'info.php'
	),

    'editores' => array(
		'templates' => array(
			'contido' => 'contido-expertos.php',
            'css' => '$estilos-editores.css'
		),

		'data' => 'expertos.php'
	),

	'autocomplete/*' => array(
		'data' => 'autocomplete.php'
	),

	'tools/mix-portugaliza' => array(
		'data' => 'tools/mix-portugaliza.php'
	),

	'tools/mix-taxonomy' => array(
		'data' => 'tools/mix-taxonomy.php'
	),

	'tools/mix-ipni' => array(
		'data' => 'tools/mix-ipni.php'
	),

	'tools/fill-especies' => array(
		'data' => 'tools/fill-especies.php'
	),

	'tools/mix-listado-especies-ipni-portugaliza' => array(
		'data' => 'tools/mix-listado-especies-ipni-portugaliza.php'
	),

	'tools/mix-portugaliza-col' => array(
		'data' => 'tools/mix-portugaliza-col.php'
	),

	'tools/mix-portugaliza-col-cg' => array(
		'data' => 'tools/mix-portugaliza-col-cg.php'
	),

	'tools/mix-portugaliza-col-cg-duplicates' => array(
		'data' => 'tools/mix-portugaliza-col-cg-duplicates.php'
	),

	'tools/mix-portugaliza-col-cg-authors' => array(
		'data' => 'tools/mix-portugaliza-col-cg-authors.php'
	),

	'tools/mix-portugaliza-final' => array(
		'data' => 'tools/mix-portugaliza-final.php'
	),

	'tools/fill-db' => array(
		'data' => 'tools/fill-db.php'
	),

	'tools/utm2ll' => array(
		'data' => 'tools/utm2ll.php'
	),

	'tools/ipsum' => array(
		'data' => 'tools/ipsum.php'
	),

	'tools/fill-localizacions' => array(
		'data' => 'tools/fill-localizacions.php'
	),

	'tools/fill-especies-completo' => array(
		'data' => 'tools/fill-especies-completo.php'
	),

	'tools/update-especies-completo' => array(
		'data' => 'tools/update-especies-completo.php'
	),

	'tools/tables2gettext' => array(
		'data' => 'tools/tables2gettext.php'
	),

    /*'tools/fix-ed50' => array(
        'data' => 'tools/fix-ed50.php'
    ),*/

    'tools/fix-mgrs-ed50' => array(
        'data' => 'tools/fix-mgrs-ed50.php'
    ),

    'tools/fix-10x10' => array(
        'data' => 'tools/fix-10x10.php'
    ),

	'suggest-especies' => array(
		'data' => 'suggest-especies.php'
	),

    'get-autores' => array(
        'data' => 'get-autores.php'
    ),

    'get-nome-especies' => array(
        'data' => 'get-nome-especies.php'
    ),

    'get-nome-subespecies' => array(
        'data' => 'get-nome-subespecies.php'
    ),

    'get-direccion' => array(
        'data' => 'get-direccion.php'
    ),

    'get-avistamentos-area' => array(
        'data' => 'get-avistamentos-area.php',
        'templates' => array(
			'base' => 'aux-avistamentos-listado-area.php'
		)
    ),

    'get-especies-area' => array(
		'templates' => array(
			'base' => 'aux-especies-area.php'
		),

		'data' => 'get-especies-area.php'
	),

    'get-puntos-area' => array(
		'data' => 'get-puntos-area.php'
	),

    'get-avistamentos-puntos-area' => array(
        'data' => 'get-avistamentos-puntos-area.php',
        'templates' => array(
            'base' => 'avistamentos-puntos-area.php'
        )
    ),

	'playground' => array(
		'templates' => array(
			'contido' => 'playground.php',
			'css' => '$playground.css',
			'js' => array(
                $mapsApi.'&libraries=drawing',
				'lib/raphael.js',
				'lib/mapas/mapas.js',
				'lib/mapas/mapas.utils.js',
				'lib/mapas/mapas.map.js',
				'lib/mapas/mapas.grid.js',
                'lib/mapas/mapas.shapes.js',
                'lib/mapas/mapas.drawing.js',

                '$config/config.playground.js',

                'modules/common.map.js',
                'modules/playground.js'
			)
		),
	),

    'check-especie-tipo' => array(
        'data' => array('check-especie-tipo.php')
    ),

    'feedback' => array(
        'templates' => array(
            'base' => 'contido-feedback.php'
        )
    ),

	'comunidade' => array(
		'templates' => array(
			'contido' => 'contido-comunidade.php'
		),

        'data' => 'comunidade.php'
	),

    'comunidade/$url' => array(
        'templates' => array(
            'contido' => 'contido-comunidade-interior.php'
        ),

        'data' => 'comunidade-interior.php'
    ),

	'editar/comunidade/$url?' => array(
		'templates' => array(
			'contido' => 'contido-form-comunidade.php',

            'js' => array(
				'lib/jquery.imagelist.js',
				'lib/jquery.fileinput.js',
				'modules/common.forms.js'
			)
		),

        'data' => 'comunidade-editar.php'
	),

    'editar/familia' => array(
        'templates' => array(
            /*'js' => array(
                'modules/common.forms.js',
                'modules/familia.crear.js'
            ),*/

            'contido' => 'contido-form-familia.php'
        ),
        'data' => 'editar-familia.php'
    ),

    'editar/xenero' => array(
        'templates' => array(
            'contido' => 'contido-form-xenero.php'
        ),
        'data' => 'editar-xenero.php'
    ),

    'achegas' => array(
        'templates' => array(
            'contido' => 'contido-achegas.php'
        ),
        'data' => 'achegas.php'
    ),

    'undefined' => array(
        'templates' => array(
            'contido' => 'contido-404.php'
        ),
        'data' => array(
            '404.php'
        )
    )
);
