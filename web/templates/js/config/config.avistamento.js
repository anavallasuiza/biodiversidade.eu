/*jslint browser:true */
/*global google, $, mapas, config, i18n */

/**
 * Modulo de configuracion del catalogo
 */
config.avistamento = {
	
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
		'mapa': google.maps.MapTypeId.ROADMAP,
		//'satelite': google.maps.MapTypeId.SATELLITE,
		'relieve': google.maps.MapTypeId.TERRAIN,
        'satelite': window.google ? google.maps.MapTypeId.HYBRID : null
	},
	
	DEFAULT_CENTER: new google.maps.LatLng(41.99105516989249, -7.757684375000049),
	
	DEFAULT_ZOOM: 6,
	
	MIN_ZOOM: 1,
	
	HEIGHT: 400,
    
    // Estilos y colores para las especies selecionadas del mapa
	SELECTED: [
		{ cssClass: 'selected-b', colors: '#DC8607', icons: '<?php echo fileWeb("templates|img/marker-orange.png"); ?>'},
		{ cssClass: 'selected-c', colors: '#8E4A87', icons: '<?php echo fileWeb("templates|img/marker-violet.png"); ?>'},
		{ cssClass: 'selected-d', colors: '#E13860', icons: '<?php echo fileWeb("templates|img/marker-red.png"); ?>'},
		{ cssClass: 'selected-a', colors: '#85b200', icons: '<?php echo fileWeb("templates|img/marker-green.png"); ?>'}
	],
    
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
    
    ZOOM_CENTROIDES1: 8,
	
	MAP_MARKER: function () {
		'use strict';
		
		this.text = i18n.avistamento.LUGAR_AVISTAMENTO;
		this.icon = '<?php echo fileWeb("templates|img/marker-orange.png"); ?>';
		this.draggable = true;
		this.events = [{
			'type': 'position_changed',
			'handler': function (evt) {
				var latLng = this.getPosition();
				$('#latitude').val(latLng.lat());
				$('#lonxitude').val(latLng.lng());
			}
		}];
	},
    
    ICON_MARKER: '<?php echo fileWeb("templates|img/marker-orange.png", false, true); ?>',
    COLOR_SHAPE: '#DC8607',
    
    FILTRO_COMUNIDADE_PT: 'Distrito de',
    URL_GET_DIRECCION: '<?php echo path("get-direccion"); ?>',
    
    SUSTITUCIONS_DIRECCION: [
        {from: 'Oporto', to: 'Porto'}
    ]
};