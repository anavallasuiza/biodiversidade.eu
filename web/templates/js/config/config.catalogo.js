/*jslint browser:true */
/*global google, $, mapas, config */

/**
 * Modulo de configuracion del catalogo
 */
config.catalogo = {
	
	// Estilo personalizado para el mapa, obtenido mediante el style wizard
	ESTILO_MAPA: [
		{"featureType": "road", "elementType": "geometry", "stylers": [{ "weight": 0.3 }, { "color": "#cbcccb" }]},
		{"featureType": "landscape.man_made", "stylers": [{ "weight": 0.1 }, { "color": "#808080" }]},
		{"featureType": "road", "elementType": "labels", "stylers": [{ "visibility": "off" }]},
		{"featureType": "administrative", "elementType": "geometry", "stylers": [{ "weight": 0.7 }]},
		{"featureType": "administrative", "elementType": "labels", "stylers": [{ "visibility": "on" }]},
		{"featureType": "administrative.country", "elementType": "geometry.stroke", "stylers": [{ "visibility": "on" }, { "color": "#808080" }, { "weight": 1 }]},
		{"featureType": "administrative.province", "elementType": "geometry.stroke", "stylers": [{ "visibility": "on" }, { "color": "#808080" }, { "weight": 1 }]},
		{"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{ "visibility": "on" }, { "color": "#9acb86" }]},
		{"featureType": "landscape.natural", "stylers": [{ "hue": "#b2ff00" }]},
		{"featureType": "landscape.natural.terrain", "stylers": [{ "saturation": 12 }, { "color": "#b1a480" }, { "lightness": 47 }]},
		{"featureType": "water", "stylers": [{ "lightness": 43 }, { "hue": "#007fff" }]}
	],
	
	MAP_TYPES: {
		'mapa': window.google ? google.maps.MapTypeId.ROADMAP : null,
		//'satelite': window.google ? google.maps.MapTypeId.SATELLITE : null,
		'relieve': window.google ? google.maps.MapTypeId.TERRAIN : null,
        'satelite': window.google ? google.maps.MapTypeId.HYBRID : null
	},
	
	DEFAULT_CENTER: window.google ? new google.maps.LatLng(41.424772343082076, -7.0669921871) : null,
	
	DEFAULT_ZOOM: 7,
	
	MIN_ZOOM: 1,
	
	HEIGHT: 650,

	// Configuracion del grid
	GRID_CONFIG: {

		// Zonas del grid
		grid: [
			{
				'zone': 29,
				'hemisphere': 'N',
				'easting': {from: 200000, to: 800000},
				'northing': {from: 3900000, to: 4900000},
				'clip': {'tl': {'lat': 44, 'lng': -10}, 'br': {'lat': 35.5, 'lng': -6}}
			},
			{
				'zone': 30,
				'hemisphere': 'N',
				'easting': {from: 200000, to: 800000},
				'northing': {from: 3900000, to: 4900000},
				'clip': {'tl': {'lat': 44, 'lng': -6}, 'br': {'lat': 35.5, 'lng': -0}}
			},
			{
				'zone': 31,
				'hemisphere': 'N',
				'easting': {from: 200000, to: 800000},
				'northing': {from: 3900000, to: 4900000},
				'clip': {'tl': {'lat': 44, 'lng': -0}, 'br': {'lat': 35.5, 'lng': 4.50}}
			}
		],

		// Tamanho del grid en base al zoom
		sizes:  [{zoom: 6, width: 50000}, {zoom: 10, width: 10000}, {zoom: 20, width: 1000}],

		drawGuides: false,

		// Estilos del grid
		styles: {
			'lines': {'stroke': '#739900', 'stroke-width': 1, 'opacity': 0.4},
			'guides': {'stroke': '#355039', 'stroke-width': 1},
			'cells': {'stroke': '#8E4A87', 'fill': '#8E4A87', 'opacity': 0.5}
		}
	},

	// Urls del kml del libro, temporalmente dropbox
	URL_LAYER_AGL: 'https://dl.dropbox.com/s/fkfb1zlo58lw6jp/agl.kml', //'<?php echo fileWeb("templates|img/agl.kml", false, true); ?>'

	// Estilos y colores para las especies selecionadas del mapa
	SELECTED: [
		{ cssClass: 'selected-b', colors: '#DC8607', icons: '<?php echo fileWeb("templates|img/marker-orange.png"); ?>'},
		{ cssClass: 'selected-c', colors: '#8E4A87', icons: '<?php echo fileWeb("templates|img/marker-violet.png"); ?>'},
		{ cssClass: 'selected-d', colors: '#E13860', icons: '<?php echo fileWeb("templates|img/marker-red.png"); ?>'},
		{ cssClass: 'selected-a', colors: '#85b200', icons: '<?php echo fileWeb("templates|img/marker-green.png"); ?>'}
	],
	
	// Configuracion del tooltip de especie
	QTIP_CONF: {
		content: {
	        text: function (api) {
				"use strict";
				
				var $this = $(this), $tooltip = $('<div class="tooltip-especie"></div>');

				var name = $this.find('span').data('name');
				name = (typeof name === 'undefined') ? '' : name;
				var comun = $this.data('comun');
				comun = (typeof comun === 'undefined') ? '' : comun;
				var sinonimos = $this.data('sinonimos');
				sinonimos = (typeof sinonimos === 'undefined') ? '' : sinonimos;

				if ((name === '') && (comun === '') && (sinonimos === '')) {
					return api.destroy();
				}

				if (name) {
					$('<p><strong><?php __e("Nome"); ?>: </strong>' + $this.find('span').data('name') + '</p>').appendTo($tooltip);
				} if (comun) {
					$('<p><strong><?php __e("Nome común"); ?>: </strong>' + $this.data('comun') + '</p>').appendTo($tooltip);
				} if (sinonimos) {
					$('<p><strong><?php __e("Sinonimos"); ?>: </strong>' + $this.data('sinonimos') + '</p>').appendTo($tooltip);
				}

				return $tooltip[0].outerHTML;
			}
		},
		position: {
			my: 'right center',
			at: 'left center',
			container: $('.content-mapa')
		},
		style: { classes: 'qtip-bootstrap' },
		show: { event: 'mouseenter', delay: 300, solo: true },
		hide: { fixed: true, event: 'mouseleave', delay: 300 }
    },
	
	NUMERO_MAX_ESPECIES: 4,
	
	URL_BUSCADOR: '<?php echo path("get-listado-selector"); ?>',
	
	ESPECIE_ALTO: 26,
	
	MARGEN_PANEL_REVERSE: 20,
	
	URL_AVISTAMENTOS: '<?php echo path("get-avistamentos"); ?>',
	
	// AGL Image overlay
	AGL_IMAGE: '<?php echo fileWeb("templates|img/mapa-gallaecia.png"); ?>',
	AGL_IMAGE_SW: [40.46216372908227, -9.538916015225027],
	AGL_IMAGE_NE: [43.87271449229606, -5.847509765225027],
	AGL_IMAGE_MAP_WIDTH: 847,
	AGL_IMAGE_MAP_HEIGHT: 622,
	
	ZOOM_CENTROIDES1: 8,
    
    PERSISTS: true,
    
    AREA_MOVE_TIMER: 300,
    URL_AVISTAMENTOS_AREA: '<?php echo path("get-avistamentos-area"); ?>',
    CONFIG_SHAPE_AREA: {
        draggable: true,
        editable: true, 
        fillOpacity: 0,
        clickable: false,
        geodesic: false,
        strokeWeight: 1
    },
    MAX_KM_PER_AREA: 2000000000,
    
    ICON_MARKER: '<?php echo fileWeb("templates|img/marker-orange.png", false, true); ?>',    
    COLOR_SHAPE: '#DC8607',
    
    URL_ESPECIES_CODIGO: '<?php echo path("get-especies-area"); ?>',
    
    MINISLIDER_SIZE: 360,
    
    URL_PUNTOS_AREA: '<?php echo path("get-puntos-area"); ?>',

    URL_AVISTAMENTOS_PUNTOS_AREA: '<?php echo path("get-avistamentos-puntos-area"); ?>'
};
