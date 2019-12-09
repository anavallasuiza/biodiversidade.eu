
var Map = function() {

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
		'fillColor': '#E03D3D',
		'fillOpacity': 0.6,
		'strokeColor': '#F22222',
		'strokeWeight': 2,
		'clickable': true,
		'zIndex': 1,
		'editable': true
	};

	var GMAPS_POLYGON_OPTIONS_SELECTED = {
		'fillColor': '#99CC00',
		'fillOpacity': 0.6,
		'strokeColor': '#608000',
		'strokeWeight': 2,
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
		'draggable': true,
		'zIndex': 2
	};

	var DEFAULT_GRID_TILE_SIZE = 140;

	var DEFAULT_GRID_ZOOM = 15;

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

	/**
	 * 
	 */
	self.info = null;

	/**
	 * Object with all the loades shapes
	 * @type {Object}
	 */
	self.shapes = {};

	/**
	 * Grid list
	 */
	self.grids = [];

	//-- Private methods
	//-------------------------

	/**
	 * Load de defaultConfig and override with specified
	 * @private
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
	 * Create the map and load the needed tools
	 * @private
	 */
	function loadMap() {

	    self.map = new google.maps.Map(self.node, self.config.map);

	    google.maps.event.addListener(self.map, 'zoom_changed', changeZoom);

	    if (self.config.drawing) {
	    	loadDrawing();
	    }
	};

	/**
	 * Draw a completely new shape using editing tools from the map
	 * @private
	 * @param evt {Event}
	 */
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
		self.shapes[shape.id] = shape;
	};

	/**
	 * Load drawing tools for the map
	 * @private
	 */
	function loadDrawing() {

		var options =  {
			drawingControl: true,
			drawingControlOptions: {
				position: google.maps.ControlPosition.TOP_CENTER,
				drawingModes: []
			},
		}

		if (self.config.type === Map.TYPE.AREA || self.config.type === Map.TYPE.MIXED) {
			
			options['drawingMode'] = google.maps.drawing.OverlayType.POLYGON;
			options['polygonOptions'] = GMAPS_POLYGON_OPTIONS_DEFAULT;

			options['drawingControlOptions']['drawingModes'].push(google.maps.drawing.OverlayType.POLYGON);

		}

		if (self.config.type === Map.TYPE.ROUTE || self.config.type === Map.TYPE.MIXED) {

			options['drawingMode'] = google.maps.drawing.OverlayType.POLYLINE;
			options['polylineOptions'] = GMAPS_POLYLINE_OPTIONS_DEFAULT;

			options['drawingControlOptions']['drawingModes'].push(google.maps.drawing.OverlayType.POLYLINE);

			//'icons': Array<IconSequence> <- Custom icons for the poitns of the route

		}

		if (self.config.type === Map.TYPE.POIS || self.config.type === Map.TYPE.MIXED) { 

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
	 * Create shapes bases on a json object
	 * @private
	 * @param shape Object
	 * @return Shape
	 */
	function createShape(shape) {
		var result = null;

		if (shape.type === Map.TYPE.POIS) {
			
			var position = new google.maps.LatLng(shape.position[0], shape.position[1]);

			var options = GMAPS_MARKER_OPTIONS_DEFAULT;

			if (!self.config.drawing) {
				options.draggable = false;
				options.editable = false;
			}

			if (shape.color) {
				var iconShadow = createIconWithShadow(shape.color);
				options.icon = iconShadow.icon;
				options.shadow = iconShadow.shadow;
			}

			if (shape.icon) {
				options.icon = shape.icon;
			}

			result = new google.maps.Marker(options);
			result.setPosition(position);
			result.type = google.maps.drawing.OverlayType.MARKER;

			// Dont apply selection logic if we hace custom color
			if (!shape.color && !shape.icon) {
				google.maps.event.addListener(result, 'mousedown', function(evt) {
					self.selection(result);
				});
			}

			if (!self.config.drawing) {
				google.maps.event.addListener(result, 'click', function(evt) {
					self.clearInfo();
					self.showInfo(result, shape.text);
				});
			}

		} else if (shape.type === Map.TYPE.AREA) {

			var options = GMAPS_POLYGON_OPTIONS_DEFAULT;

			if (!self.config.drawing) {
				options.editable = false;
			}

			if (shape.options) {
				for (var key in shape.options) {
					options[key] = shape.options[key]
				}
			}

			result = new google.maps.Polygon(GMAPS_POLYGON_OPTIONS_DEFAULT);
			var path = new google.maps.MVCArray();

			for(var i = 0; i < shape.path.length; i++) {
				path.push(new google.maps.LatLng(shape.path[i][0], shape.path[i][1]));
			}
			
			result.setPath(path);
			result.type = google.maps.drawing.OverlayType.POLYGON;

			if (shape.canSelect) {
				google.maps.event.addListener(result, 'click', function(evt) {
					self.selection(result, evt.vertex);

					if (!self.config.drawing) {
						self.clearInfo();
						self.showInfo(result, shape.text);
					}
				});
			}
		}

		result.id = shape.id;

		return result;
	};

	function changeZoom() {

		var zoom = self.map.getZoom();
		var tileSize = DEFAULT_GRID_TILE_SIZE;

		tileSize = calculateTileSize(zoom);

		for(var i = 0; i < self.grids.length; i++) {
		
			self.grids[i].tileSize = tileSize;
		}

		return true;
	};

	function calculateTileSize(zoom) {

		var result = DEFAULT_GRID_TILE_SIZE;

		/*
		if (zoom !== DEFAULT_GRID_ZOOM) {
			result = DEFAULT_GRID_TILE_SIZE / (2 * (DEFAULT_GRID_ZOOM - zoom))
		}
		*/

		result = Math.ceil((DEFAULT_GRID_TILE_SIZE * zoom) / DEFAULT_GRID_ZOOM);		
		
		result = new google.maps.Size(result, result);
		return result;
	};

	/**
	 * Create a marker icon using charts api
	 * @private
	 * @param backgorund {String} Background color
	 * @param stroke {String} Stroke color
	 * return Icon
	 */
	function createIconWithShadow(background) {

		// Remove the # from color
		var color = background.replace('#', '');

		// Url from Charts api to get the icons. 
		// API is deprecated!!!!
		var ICON_URL = "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|";
		var SHADOW_URL = "http://chart.apis.google.com/chart?chst=d_map_pin_shadow";

		// Create the icon
		var icon = new google.maps.MarkerImage(ICON_URL + color,
        	new google.maps.Size(21, 34),
        	new google.maps.Point(0,0),
        	new google.maps.Point(10, 34)
        );

		// Create the sahdow
		var shadow = new google.maps.MarkerImage(SHADOW_URL,
        	new google.maps.Size(40, 37),
        	new google.maps.Point(0, 0),
        	new google.maps.Point(12, 35)
        );

		// Return both
		return  {
			icon: icon,
			shadow: shadow
		}
	}


	/**
	 * Constructor
	 * @param node {HTMLElement}
	 * @param params {Object }
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

	/**
	 * Get and set the current selection
	 * @public
	 * @param shape {Object} Shape to be selected
	 * @param vertex  {Object} Vertex of the polygon to be selected if any
	 * @return {Boolean}
	 */
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
			var options = GMAPS_POLYGON_OPTIONS_SELECTED;
			delete options['editable'];

			shape.setOptions(GMAPS_POLYGON_OPTIONS_SELECTED);
		} else if (shape.type === google.maps.drawing.OverlayType.POLYLINE) {

		}
		
		selected = shape;

		return true;
	};

	/**
	 * Clear the current shape selection
	 * @public
	 * @return {Boolean}
	 */
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
			var options = GMAPS_POLYGON_OPTIONS_DEFAULT;
			delete options['editable'];

			selected.setOptions(GMAPS_POLYGON_OPTIONS_DEFAULT);
		} else if (selected.type === google.maps.drawing.OverlayType.POLYLINE) {

		}

		selected = null;

		return true;
	};

	/**
	 * Remove a shape form the map
	 * @public
	 * @param shape {Object} Shape to be removed
	 * @return {Boolean}
	 */
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

	/**
	 * Return the specified toolbar form the map
	 * @public
	 * @param type {String}
	 * @return Return the toolbar
	 */
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

	/**
	 * Load shapes from a JSON object
	 * @public
	 * @param shapes {Object}
	 */
	self.loadShapes = function(shapes) {

		for (var i = 0; i < shapes.length; i++) {
			var shape = shapes[i];
			var newShape = createShape(shape);

			self.shapes[newShape.id] = newShape;

			newShape.setMap(self.map);
		}
	};

	/**
	 * Clear shapes from map
	 * @public
	 * @param shapes {Object}
	 */
	self.clearShapes = function() {

		var shape;
		for (var id in self.shapes) {
			shape = self.shapes[id];
			shape.setMap(null);
		}
	};

	self.showInfo = function(shape, info) {

		var position = null;
		var offset = null;

		if (shape.type === google.maps.drawing.OverlayType.MARKER) {
			position = shape.getPosition();
			offset = new google.maps.Size(10, -35, 'px', 'px');
		} else {
			position = Map.getPolygonCenter(shape.getPath().getArray());
		}

		self.info = new google.maps.InfoWindow({ 
			content: info,
			position: position,
			pixelOffset: offset
		});

		self.info.open(self.map);
	};

	self.clearInfo = function() {

		if (!self.info) {
			return false;
		}

		self.info.setMap(null);
		self.info = null;

		return true;
	};

	self.createMarkerInPosition = function(shape, clearPrevious) {

		if (clearPrevious) {
			self.clearShapes();
		}

		loadShapes([shape]);
	};

	/**
	 * Add a single marker in the user current position
	 * @param errorcallback {Function} Method to invoke when an error happened dutring the process
	 */ 
	self.addMarkerFromMyPosition = function(clearPrevious, errorCallback) {

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position){
				self.createMarkerInPosition({
					
				}, clearPrevious);
			}, errorCallback);
		} else {
			errorCallback.apply(this);
		}
	};

	self.addGrid = function(grid, ignoreArray) {
		if (!grid) {
			return false;
		}

		self.map.overlayMapTypes.push(grid);
		grid.map = self.map;

		if (!ignoreArray) {
			self.grids.push(grid);
		}
	};

	self.removeGrids = function(ignoreArray) {
		self.map.overlayMapTypes.clear();

		if (!ignoreArray) {
			self.grids = [];
		}
	};

	self.reloadGrids = function() {
		self.map.overlayMapTypes.clear();

		for(var i = 0; i < self.grids.length; i++) {
			self.grids[i].map = null;
			self.map.overlayMapTypes.push(self.grids[i]);
			self.grids[i].map = self.map;
		}
	};

	// Call the constructor
	init.apply(self, arguments);
};

//-- Constants
//-----------------------------

/**
 * Diferent map types
 */
Map.TYPE = {
	AREA: 'area',
	ROUTE: 'route',
	POIS: 'pois',
	MIXED: 'mixed'
};


//-- Static methods
//-----------------------------


/**
 * Get the center of a and array os coordinates or a polygon path
 * @static
 * @param path {Array/Object}
 * @return google.maps.LatLng
 */
Map.getPolygonCenter = function(path) {

	"use strict";

	var bounds = new google.maps.LatLngBounds();

	if (path && path[0].constructor === Array) {
		for (var i = 0; i < path.length; i++) {
			bounds.extend(new google.maps.LatLng(path[i][0], path[i][1]));
		}
	} else {
		for (var i = 0; i < path.length; i++) {
			bounds.extend(new google.maps.LatLng(path[i].lat(), path[i].lng()));
		}
	}

	return bounds.getCenter();
};

/**
 * Make the map to fit the bounds of an array os coordinates or a polygon path
 * @static
 * @param path {Array/Object}
 */
Map.fitBounds = function(path) {

	"use strict";

	var bounds = new google.maps.LatLngBounds();

	if (path.constructor === Array) {
		for (var i = 0; i < path.length; i++) {
			bounds.extend(new google.maps.LatLng(path[i][0], path[i][1]));
		}
	} else {
		for (var i = 0; i < path.length; i++) {
			bounds.extend(latlng = new google.maps.LatLng(path[i].lat(), path[i].lng()));
		}
	}

	self.map.fitBounds(bounds);
};

/**
 * Convert coords into a Gmaps point
 * @static
 * @param x
 * @param y
 * @param zoom
 * @param tileSizeX
 * @param tileSizeY
 * @return google.maps.Point
 */
Map.coordsToPoint = function(x, y, zoom, tileSizeX, tileSizeY) {

	"use strict";

	var numTiles = 1 << zoom;

	return new google.maps.Point((x * tileSizeX) / numTiles, (y * tileSizeY) / numTiles);
};

/**
 * Convert latLng to UTM using WGS84 datum
 * @static
 * @param lat {String/Float/google.maps.LatLng} Latitude or LatLng if there is one argument
 * @param lng {String/Float}
 */
/*
Map.convertLatLngToUTM = function(lat, lng) {

	"use strict";

	if (arguments.length === 1) {
		lng = arguments[0].lng();
		lat = arguments[0].lat();
	}

	var zone = Math.floor((parseFloat(lng) + 180.0) / 6) + 1;
};
*/
