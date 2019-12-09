/*jslint browser:true */
/*global google, $, mapas, config */

/**
 * Modulo de configuracion del catalogo
 */
config = {
	
	// Default speed for jquery animations
	ANIMATION_SPEED: 'fast',
	
	ELASTISLIDE: {
		orientation : 'hot',
		speed : 500,
		easing : 'ease-in-out',
		minItems : 3,
		start : 0,
		 
		// click item callback
		
		onClick : function() {},
		onReady : function() {},
		onBeforeSlide : function() {},
		onAfterSlide : function() {}
	},
	
	DATEPICKER: {
		dateFormat:'dd-mm-yy'
	},
	
	DATETIMEPICKER: {
		dateFormat:'dd-mm-yy',
		timeFormat: 'HH:mm z',
		showTimezone: true
	},
	
	URL_ESPECIES: '<?php echo path("suggest-especies"); ?>',
    URL_XENEROS: '<?php echo path("get-xeneros"); ?>',
	
	URL_LISTADO_XENEROS: '<?php echo path("get-listado-xeneros"); ?>',
	URL_LISTADO_FAMILIAS: '<?php echo path("get-listado-familias"); ?>',
	URL_LISTADO_ORDES: '<?php echo path("get-listado-ordes"); ?>',
	URL_LISTADO_CLASES: '<?php echo path("get-listado-clases"); ?>',
	
	URL_PROVINCIAS: '<?php echo path("get-provincias"); ?>',
	URL_CONCELLOS: '<?php echo path("get-concellos"); ?>',
    
    CKEDITOR_TOOLBARS: [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'align' ] },
        { name: 'links' }
    ],
    
    TRANSPARENT_IMAGE: '<?php echo fileWeb("templates|img/transparent.gif"); ?>'
};