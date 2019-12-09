/*jslint browser:true */
/*global google, $, mapas, config */

/**
 * Modulo de configuracion de espazo
 */
config.espazo = {
    
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
		'satelite': window.google ? google.maps.MapTypeId.SATELLITE : null,
		'relieve': window.google ? google.maps.MapTypeId.TERRAIN : null,
        'hybrid': window.google ? google.maps.MapTypeId.HYBRID : null
	},
	
	DEFAULT_CENTER: window.google ? new google.maps.LatLng(41.424772343082076, -7.0669921871) : null,
	
	DEFAULT_ZOOM: 7,
	
	HEIGHT: 500,
    
    COLOR_SHAPE: '#DC8607',
    COLOR_SHAPE_SELECTED: '#E13860',
    
    ICON_MARKER: '<?php echo fileWeb("templates|img/ico-vistas.png", false, true); ?>',
    ICON_MARKER_OPACITY: '<?php echo fileWeb("templates|img/marker-orange-transparent.png", false, true); ?>',
    ICON_MARKER_SELECTED: '<?php echo fileWeb("templates|img/marker-red.png", false, true); ?>',
    
    FILL_OPACITY: 0.1,
    STROKE_OPACITY: 0.2,

    INFOBOX_CLEARANCE: new google.maps.Size(60, 60),
    PIXEL_OFFSET: new google.maps.Size(-158, -46),
    CLOSE_BOX: $('<i class="icon-remove"></i>')[0]
};