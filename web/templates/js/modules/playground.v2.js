
var Mapa = function() {

	"use strict";

	//-- Private attributes
	//-------------------------

	var MARKER_SELECTED_ICON = 'http://maps.google.com/mapfiles/marker_green.png';

	var GMAPS_BUTTON_STYLE_DEFAULT = {
		'background-color': 'rgb(255, 255, 255)',
		'background-position': 'initial initial',
		'background-repeat': 'initial initial',
		'border': '1px solid rgb(113, 123, 135)',

		'-webkit-box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'-moz-box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'-ms-box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'-os-box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',
		'box-shadow': 'rgba(0, 0, 0, 0.4) 0px 2px 4px',

		'color': 'rgb(51, 51, 51)',
		'cursor': 'default',
		'display': 'inline-block',
		'font-family': 'Arial, sans-serif',
		'font-size': '13px',
		'overflow': 'hidden',
		'padding': '4px',
		'position': 'relative', 
		'text-align': 'left',

	};

	var GMAPS_TEXT_STYLE_DEFAULT = {
		'cursor': 'default',
		'font-family': 'Arial,sans-serif',
		'font-size': '12px',
		'font-weight': 'bold',
		'opacity': '0.7',
		'padding-left': '4px',
		'padding-right': '4px',
	};

	var GMAPS_POLYGON_OPTIONS_DEFAULT = {
		'fillColor': '#333',
		'fillOpacity': 0.6,
		'strokeWeight': 5,
		'clickable': true,
		'zIndex': 1,
		'editable': true
	};

	var GMAPS_POLYLINE_OPTIONS_DEFAULT = {
		'fillColor': '#333',
		'fillOpacity': 0.6,
		'strokeWeight': 5,
		'clickable': true,
		'zIndex': 1,
		'editable': true
	};

	var GMAPS_MARKER_OPTIONS_DEFAULT = {
		'clickable': true,
		'editable': true,
		'draggable': true
	};

	/**
	 * Referencia a la instancia actual
	 * @type Mapa
	 */
	var self = this;

	/**
	 * Map container
	 * @type HTMLElement
	 */
	var node = null;

	/**
	 * Actual configuration
	 * @type Object
	 */
	var defaultConfig = {
		drawing: false,
		type: null,
		map: {
			zoom: 15,
	        center: new google.maps.LatLng(42.8789, -8.5487),
	        mapTypeId: google.maps.MapTypeId.TERRAIN,
	        mapTypeControlOptions: {
		      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		    }
		}
	};

	/**
	 *
	 */
	var selected = null;

	//-- Public attributes
	//-------------------------

	/**
	 *
	 */
	self.node = null;

	/**
	 * 
	 */
	self.config = null;

	/**
	 *
	 */
	self.map = null;

	/**
	 *
	 */
	self.drawing = null;


	//-- Private methods
	//-------------------------

	/**
	 * Load de defaultConfig and override with specified
	 * @param params Object {}
	 */
	function loadConfig(params) {

		// We load the default config first
		self.config = defaultConfig;

		if (!params) {
			return false;
		}

		for (var key in params) {
			self.config[key] = params[key];
		}
	};

	/**
	 *
	 */
	function loadMap() {

	    self.map = new google.maps.Map(self.node, self.config.map);

	    if (self.config.drawing) {
	    	loadDrawing();
	    }
	};

	function newShape(evt) {

        self.drawing.setDrawingMode(null);        
        
        var shape = evt.overlay;
        shape.type = evt.type;        

        if (shape.type === google.maps.drawing.OverlayType.MARKER) {
			google.maps.event.addListener(shape, 'mousedown', function(evt) {
				self.selection(shape);
			});
		} else {
			google.maps.event.addListener(shape, 'click', function(evt) {
				self.selection(shape, evt.vertex);	
			});	
		}

		self.selection(shape);
	};

	/**
	 *
	 */
	function loadDrawing() {

		var options =  {
			drawingControl: true,
			drawingControlOptions: {
				position: google.maps.ControlPosition.TOP_CENTER,
				drawingModes: []
			},
		}

		if (self.config.type === Mapa.TYPE.AREA || self.config.type === Mapa.TYPE.MIXED) {
			
			options['drawingMode'] = google.maps.drawing.OverlayType.POLYGON;
			options['polygonOptions'] = GMAPS_POLYGON_OPTIONS_DEFAULT;

			options['drawingControlOptions']['drawingModes'].push(google.maps.drawing.OverlayType.POLYGON);

		}

		if (self.config.type === Mapa.TYPE.ROUTE || self.config.type === Mapa.TYPE.MIXED) {

			options['drawingMode'] = google.maps.drawing.OverlayType.POLYLINE;
			options['polylineOptions'] = GMAPS_POLYLINE_OPTIONS_DEFAULT;

			options['drawingControlOptions']['drawingModes'].push(google.maps.drawing.OverlayType.POLYLINE);

			//'icons': Array<IconSequence> <- Custom icons for the poitns of the route

		}

		if (self.config.type === Mapa.TYPE.POIS || self.config.type === Mapa.TYPE.MIXED) { 

			options['drawingMode'] = google.maps.drawing.OverlayType.MARKER;
			options['markerOptions'] = GMAPS_MARKER_OPTIONS_DEFAULT;

			options['drawingControlOptions']['drawingModes'].push(google.maps.drawing.OverlayType.MARKER);
		}
		

		self.drawing = new google.maps.drawing.DrawingManager(options);
		self.drawing.setMap(self.map);

		google.maps.event.addListener(self.drawing, 'overlaycomplete', newShape);
		google.maps.event.addListener(self.map, 'click', self.clearSelection);

		self.addCustomButtons([{
			'text': 'Eliminar', 
			'css': 'delete-selection',
			'click': function(){
				self.remove();
			}
		}]);
	};

	/**
	 * Create shapes bases on json
	 * @param shape Object
	 */
	function createShape(shape) {
		var result = null;

		if (shape.type === Mapa.TYPE.POIS) {
			
			var position = new google.maps.LatLng(shape.position[0], shape.position[1]);

			var options = GMAPS_MARKER_OPTIONS_DEFAULT;

			if (!self.config.drawing) {
				options.draggable = false;
				options.editable = false;
			}

			result = new google.maps.Marker(options);
			result.setPosition(position);

			google.maps.event.addListener(result, 'mousedown', function(evt) {
				self.selection(result);
			});

		} else if (shape.type === Mapa.TYPE.AREA) {

			var options = GMAPS_POLYGON_OPTIONS_DEFAULT;

			if (!self.config.drawing) {
				options.editable = false;
			}

			result = new google.maps.Polygon(GMAPS_POLYGON_OPTIONS_DEFAULT);
			var path = new google.maps.MVCArray();

			for(var i = 0; i < shape.path.length; i++) {
				path.push(new google.maps.LatLng(shape.path[i][0], shape.path[i][1]));
			}

			result.setPath(path);
		}

		return result;
	};

	/**
	 * Constructor de la clase mapa
	 * @param params Object {element, params}
	 */
	function init(node, params) {

		if (!node) {
			throw new Error('You should specify one node');
		}
		
		if (window.jQuery && node.constructor === window.jQuery) {
			self.node = node[0];
		} else {
			self.node = node;	
		}		

		loadConfig(params);
		loadMap();

	};



	//-- Public methods
	//-------------------------

	self.selection = function(shape, vertex) {

		if (!shape) {
			return selected;
		}

		self.clearSelection();

		

		if (self.config.drawing) {
			if (shape.setEditable) {
				shape.setEditable(true);
			}

			if (vertex) {
				shape.selectedVertex = vertex;
			}

			var $toolbar = $(self.getToolbar().getArray());
			$toolbar.find('.delete-selection div').css({'opacity': 1});
		}

		if (shape.type === google.maps.drawing.OverlayType.MARKER) {
			shape.setIcon(MARKER_SELECTED_ICON);
		} else if (shape.type === google.maps.drawing.OverlayType.POLYGON) {

		} else if (shape.type === google.maps.drawing.OverlayType.POLYLINE) {

		}
		
		selected = shape;
	};

	self.clearSelection = function() {
		if (!selected) {
			return true;
		}

		if (self.config.drawing) {
			if (selected.setEditable) {
				selected.setEditable(false);
			}

			selected.selectedVertex = null;

			var $toolbar = $(self.getToolbar().getArray());
			$toolbar.find('.delete-selection div').css({'opacity': '0.7'});
		}

		if (selected.type === google.maps.drawing.OverlayType.MARKER) {
			selected.setIcon(null);
		} else if (selected.type === google.maps.drawing.OverlayType.POLYGON) {

		} else if (selected.type === google.maps.drawing.OverlayType.POLYLINE) {

		}

		selected = null;

		return true;
	};

	self.remove = function(shape) {

		if (!shape) {
			if (selected) {

				if (selected.selectedVertex) {
					selected.getPath().removeAt(selected.selectedVertex);
				} else {
					selected.setMap(null);
				}
				
				self.clearSelection();
				return true;
			}

			return false;
		}

		shape.setMap(null);
		return true;
	};

	self.getToolbar = function(type) {
		return self.map.controls[type || google.maps.ControlPosition.TOP_CENTER];
	};

	/**
	 * Add custom buttons to a toolbar
	 * @param buttons Object [{click: function(){}, text: 'Eliminar', activo: false, css. 'delete-selection ...'},...]
	 * @param toolbar String google.maps.ControlPosition.[TOP_CENTER,...]
	 */
	self.addCustomButtons = function(buttons, toolbar) {

		if (!buttons) {
			return false;
		}

		var toolbar = self.getToolbar(toolbar);
		var $container = $('<div></div>').css('padding-top', '5px');

		for (var i = 0; i < buttons.length; i++) {

			var button = buttons[i];

			var $button = $('<div></div>').css(GMAPS_BUTTON_STYLE_DEFAULT).on('click', button['click']);

			$button.css({'left': '-' + (1 * i) + 'px'});
			$button.addClass(button.css);
			$container.append($button);


			var $text = $('<div></div>').css(GMAPS_TEXT_STYLE_DEFAULT).html(button['text']);

			if (button.activo) {
				$text.css({'opacity': 1});
			}	

			$button.append($text);			
		}

		$container.attr('index', 1);
		toolbar.push($container[0]);
	};

	self.loadShapes = function(shapes) {

		for (var i = 0; i < shapes.length; i++) {
			var shape = shapes[i];
			var newShape = createShape(shape);

			newShape.setMap(self.map);
		}
	};

	// Invocamos al constructor
	init.apply(self, arguments);
};


Mapa.TYPE = {
	AREA: 'area',
	ROUTE: 'route',
	POIS: 'pois',
	MIXED: 'mixed'
};

$(document).ready(function(){
	
	window.mapa = new Mapa($('#mapa'), {
		drawing: true,
		type: Mapa.TYPE.MIXED,
		map: {
			zoom: 7,
	        center: new google.maps.LatLng(42.54, -7.54),
	        mapTypeId: google.maps.MapTypeId.TERRAIN,
	        mapTypeControlOptions: {
		      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		    }
		}, 
	});


	window.mapa.loadShapes([
		{
			type: Mapa.TYPE.POIS,
			position: [42.308, -7.913],
			data: {'codigo': 'a3fced5'},
			text: 'Lorme ipsum dolor sit amet'
		},
		{
			type: Mapa.TYPE.AREA,
			path: [
				[42.258, -7.913], 
				[42.298, -7.903], 
				[42.322, -7.915]
			],
			data: {'codigo': 'a3fced5'},
		}

	]);
	
});



//-------------------------------------------------------
//-------------------------------------------------------
//-------------------------------------------------------
//-------------------------------------------------------

var selectedShape;

function clearSelection() {
	if (selectedShape) {
	  selectedShape.setEditable(false);
	  selectedShape = null;
	}
}

function setSelection(shape) {
	clearSelection();
	selectedShape = shape;
	shape.setEditable(true);
	//selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function deleteSelectedShape() {
	if (selectedShape) {
	  selectedShape.setMap(null);
	}
}

$(document).ready(function(){

	return true;
	var myOptions = {
        zoom: 15,
        center: new google.maps.LatLng(42.8789, -8.5487),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControlOptions: {
	      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
	    }
    }

    //var map = new google.maps.Map(document.getElementById("mapa"), myOptions);

    var drawingManager = new google.maps.drawing.DrawingManager({
	  drawingMode: google.maps.drawing.OverlayType.MARKER,
	  drawingControl: true,
	  drawingControlOptions: {
	    position: google.maps.ControlPosition.TOP_CENTER,
	    drawingModes: [
	      /*google.maps.drawing.OverlayType.MARKER,
	      google.maps.drawing.OverlayType.CIRCLE,*/
	      google.maps.drawing.OverlayType.POLYGON,
	      /*google.maps.drawing.OverlayType.POLYLINE,
	      google.maps.drawing.OverlayType.RECTANGLE*/
	    ]
	  },
	  markerOptions: {
	    icon: new google.maps.MarkerImage('http://www.example.com/icon.png')
	  },
	  /*circleOptions: {
	    fillColor: '#ffff00',
	    fillOpacity: 1,
	    strokeWeight: 5,
	    clickable: true,
	    zIndex: 1,
	    editable: true
	  },*/
	  /*
	  markerOptions: {editable: true},
	  */
	  polygonOptions: {
	  	fillColor: '#333',
	    fillOpacity: 0.6,
	    strokeWeight: 5,
	    clickable: true,
	    zIndex: 1,
	    editable: true

	  }/*,*/
	  /*
	  polylineOptions: {editable: true},
	  rectangleOptions: {editable: true}*/
	});			

	drawingManager.setMap(map);

	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
        // Switch back to non-drawing mode after drawing a shape.
        drawingManager.setDrawingMode(null);

        // Add an event listener that selects the newly-drawn shape when the user
        // mouses down on it.
        var newShape = e.overlay;
        newShape.type = e.type;
        google.maps.event.addListener(newShape, 'click', function(evt) {
          setSelection(newShape);
        });

        setSelection(newShape);
      }
    });

	google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    //google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);


	// Create a div to hold the control.
	var controlDiv = document.createElement('div');

	// Set CSS styles for the DIV containing the control
	// Setting padding to 5 px will offset the control
	// from the edge of the map.
	controlDiv.style.padding = '5px';

	// Set CSS for the control border.
	var controlUI = document.createElement('div');
	/*controlUI.style.backgroundColor = 'white';
	controlUI.style.borderStyle = 'solid';
	controlUI.style.borderWidth = '2px';
	controlUI.style.cursor = 'pointer';
	controlUI.style.textAlign = 'center';
	controlUI.title = 'Click to set the map to Home';*/
	controlUI.setAttribute('style','direction: ltr; overflow: hidden; text-align: left; position: relative; color: rgb(51, 51, 51); font-family: Arial, sans-serif; -webkit-user-select: none; font-size: 13px; background-color: rgb(255, 255, 255); padding: 4px; border: 1px solid rgb(113, 123, 135); -webkit-box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px; box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px; font-weight: normal; background-position: initial initial; background-repeat: initial initial;cursor: default;');
	controlDiv.appendChild(controlUI);
	

	// Set CSS for the control interior.
	var controlText = document.createElement('div');
	controlText.style.fontFamily = 'Arial,sans-serif';
	controlText.style.fontSize = '12px';
	controlText.style.paddingLeft = '4px';
	controlText.style.paddingRight = '4px';
	controlText.style.cursor = 'default';
	controlText.innerHTML = '<strong>Eliminar</strong>';
	controlUI.appendChild(controlText);

	//var myControl = new MyControl(controlDiv);
	controlDiv.index = 1;

	map.controls[google.maps.ControlPosition.TOP_CENTER].push(controlDiv);
});