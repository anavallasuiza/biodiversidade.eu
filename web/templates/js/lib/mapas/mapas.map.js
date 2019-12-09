
/*jslint browser:true */
/*global google, $, mapas, catalogo, indexOf, Blob, BlobBuilder, alert, confirm, console */

mapas.Map = function () {

	"use strict";

	// Constants
	// -------------------
	
	var DEFAULT_CONFIG = {
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		center: new google.maps.LatLng(42.8789, -8.5487)
	};

	// Private attributes
	// ------------------
	
	var labelDefaultStyles;

	/**
	 * Reference to the map instance
	 * @type {mapas.Map}p
	 */
	var self = this;

	// ------------------

	// Public attributes
	// ------------------
	
	self.SHAPES = {
		MARKER: 'marker',
		POLYGON: 'polygon',
		POLYLINE: 'polyline'
	};

	/**
	 * Reference to the google maps instance
	 * @type {google.maps.Map}
	 */
	self.innerMap = null;

	/**
	 * Width of the map
	 * @type {Number}
	 */
	self.width = 0;

	/**
	 * Height of the map
	 * @type {Number}
	 */
	self.height = 0;

	/**
	 * Array with the styles for the map
	 * @type {Array}
	 */
	self.styles = [];

	/**
	 * Shapes list
	 * @type {Array}
	 */
	self.shapes = [];

	/**
	 * Active info window of the map
	 * @type {google.maps.InfoWindow}
	 */
	self.info = null;

	// ------------------


	// Private methods
	// ------------------

	/**
	 * Create a shape of the given type with the given data
	 * @param  {mapas.Map.SHAPES} type Type of the shape
	 * @param  {Object} data Shape data
	 * @return {Object} Created shape
	 */
	function createShape(type, data) {

		var shape;

		if (type === self.SHAPES.MARKER) {
			shape = createMarker(data);
		} else {
			throw new Error('Shape type "' + type + '" not supported');
		}

		return shape;
	}

	/**
	 * Create a marker
	 * @param {Object} data Marker data
	 * @return {Object} Marker
	 */
	function createMarker(data) {

		

		var options = {
			'clickable': true,
			'zIndex': 2
		};

		if (data.editable) {
			options.editable = true;
		}

		if (data.draggable) {
			options.draggable = true;
		}

		if (data.icon) {
			options.icon = data.icon;

			if (data.shadow) {
				options.shadow = data.shadow;
			}
		}

		var marker = new google.maps.Marker(options);

		var latLng = data.latLng;

		if (!latLng) {
			latLng = new google.maps.LatLng(data.lat, data.lng);
		}

		marker.setPosition(latLng);

		if (data.events) {
			for (var i = 0, longEvents = data.events.length; i < longEvents; i++) {
				google.maps.event.addListener(marker, data.events[i].type, data.events[i].handler);
			}
		}

		marker.setMap(self.innerMap);

		return marker;
	}

	// Constructor
	// ------------------

	/**
	 * Constructor
	 * @constructor
	 * @param {String} id Id of the map
	 * @param {HTMLElement} node Html element used as the map container
	 * @param {Object} config Configuration object
	 */
	function init(id, node, config) {

        var zoom,
            lat,
            lng;
        
        // TODO: Move google maps event handling and use class based mixin for general purpouse
        // Apply event management mixin to this instance
        //mapas.utils.EventsMixin.call(this);
        
		if (!config || !config.mapOptions) {
			throw new Error('You need to specify a config object width mapOptions.');
		}

		if (config.style && config.style.width) {
			node.style.width = config.style.width + 'px';
			self.width = config.style.width;
		} else {
			self.width = node.offsetWidth;
		}

		if (config.style && config.style.height) {
			node.style.height = config.style.height + 'px';
			self.height = config.style.height;
		} else {
			self.height = node.offsetHeight;
		}

		// Visual refresh for google maps.
		//google.maps.visualRefresh = true;

		self.mapOptions = config.mapOptions;
		self.styles = config.mapOptions.styles || [];
		self.node = node;

		// Tge the default administrative style
		labelDefaultStyles = [self.getFeature('administrative', 'labels'), self.getFeature('poi', 'labels'), self.getFeature('water', 'labels')];

		self.innerMap = new google.maps.Map(node, config.mapOptions);
        
        if (config.persists && window.hasOwnProperty('localStorage')) {
            
            self.on('zoom_changed', function () {
                localStorage.setItem('catalogo-mapa-zoom', self.innerMap.getZoom());
            });
            
            self.on('center_changed', function () {
                var latLng = self.innerMap.getCenter();
                
                localStorage.setItem('catalogo-mapa-center-lat', latLng.lat());
                localStorage.setItem('catalogo-mapa-center-lng', latLng.lng());
            });
            
            zoom = localStorage.getItem('catalogo-mapa-zoom');
            lat = localStorage.getItem('catalogo-mapa-center-lat');
            lng = localStorage.getItem('catalogo-mapa-center-lng');
            
            if (zoom) {
                self.innerMap.setZoom(parseInt(zoom));
            }
            
            if (lat && lng) {
                self.innerMap.setCenter(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
            }
        }
	};

	// -------------------

	// Public methods
	// -------------------
	
	/**
	 * Add an event listener to the map
	 * @param  {String}   type     Type of the event
	 * @param  {Function} callback Event handling function
	 * @return {Object} Listener identifier
	 */
	self.on = function(type, callback) {
		return google.maps.event.addListener(self.innerMap, type, callback);
	}

	/**
	 * Remove an event listener based in the identifier
	 * @param  {Object} identifier Event listener identifier
	 */
	self.off = function(identifier) {
		google.maps.event.removeListener(identifier);
	}

	/**
	 * Set the map options, you can specify a whole new options object or update the map using the current one
	 * @param {google.maps.MapOptions} options Options object
	 */
	self.updateMapOptions = function(options) {

		if (options) {
			self.mapOptions = options;
		}

		self.innerMap.setOptions(self.mapOptions);
	};

	self.getFeature = function(feature, element) {

		var result = false;

		for (var i = 0, longStyles = self.styles.length; i < longStyles; i++){
			if (self.styles[i]['featureType'] === feature && self.styles[i]['elementType'] === element) {
				result = self.styles[i];
				break;
			}
		}

		return result;
	};

	/**
	 * Set the features of the map
	 * @param  {Array} features Array width the features
	 */
	self.setFeatures = function(features, updateMap, remove) {

		for (var i = 0, longFeatures = features.length; i < longFeatures; i++) {

			if (features[i]) {

				// Remove the old feature
				if (remove) {
					self.removeFeature(features[i]['featureType'], features[i]['elementType']);
				}

				// Set the new one
				self.styles.push(features[i]);
			}
		}
		
		if (updateMap) {
			self.updateMapOptions({ styles: self.styles });
		}
	};

	self.removeFeature = function(feature, element) {

		for (var j = 0, longStyles = self.styles.length; j < longStyles; j++){
			if (self.styles[j]['featureType'] === feature && self.styles[j]['elementType'] == element) {
				self.styles.splice(j, 1);
				break;
			}
		}
	};

	/**
	 * Show or hide the map labels using map style options
	 * @param  {Boolean} show True to show the labels
	 */
	self.showLabels = function(show) {

		var visibility = show ? 'on' : 'off';
		
		self.setFeatures([
			{"featureType": "administrative", "elementType": "labels", "stylers": [{ "visibility": visibility }]},
			{"featureType": "poi", "elementType": "labels", "stylers": [{ "visibility": visibility }]},
			{"featureType": "water", "elementType": "labels", "stylers": [{ "visibility": visibility }]}
		], true, true);
	};

	/**
	 * Show or hide the administrative limits
	 * @param  {Boolean} show True to show, false to hide
	 */
	self.showLimits = function(show) {

		var visibility = show ? 'on' : 'off';

		self.setFeatures([
			{"featureType": "administrative", "elementType": "geometry", "stylers": [{ "visibility": visibility }]}
		], true, true);
	};

	/**
	 * Load markers in the map
	 * @param {Array} shapes Array of markers
	 * @return {Array} List of markers created
	 */
	self.loadMarkers = function(markers, click) {

		if (!markers) {
			return [];
		}
		
		var result = [], marker;
		for (var i = 0; i < markers.length; i++) {
			
			marker = createShape(self.SHAPES.MARKER, markers[i]);
			marker.data = markers[i];

			if (click) {
				google.maps.event.addListener(marker, 'click', click);
			}

			result.push(marker);
			self.shapes.push(marker);
		}

		return result;
	};

	/**
	 * Remove all the shapes form the map
	 */
	self.clearShapes = function() {
		for (var i = 0, longShapes = self.shapes.length; i < longShapes; i++) {
			self.shapes[i].setMap(null);
		}

		self.shapes = [];
	}

	/**
	 * [showInfo description]
	 * @param  {[type]} content [description]
	 * @param  {[type]} shape   [description]
	 * @return {[type]}         [description]
	 */
	self.showInfo = function(data, shape, options) {

		var position, offset;

		if (shape && shape.getPosition) {
			position = shape.getPosition();
			offset = new google.maps.Size(8, -12, 'px', 'px');
		} else {
			position = new google.maps.LatLng(data.lat, data.lng);
		}

		if (self.info) {
			self.closeInfo();
		}

		/*
		self.info = new google.maps.InfoWindow({ 
			content: data.text,
			position: position,
			pixelOffset: offset
		});
		*/
	
		var config = {
			content: data.text,
			position: position,
			pixelOffset: offset,
			alignBottom: true
		};

		for (var key in options) {
			config[key] = options[key];
		}

		self.info = new mapas.InfoBox(config);

		self.info.open(self.innerMap);

		return self.info;
	};
	
	/**
	 * [closeInfo description]
	 * @return {[type]} [description]
	 */
	self.closeInfo = function() {

		if (self.info) {
			self.info.setMap(null);
			self.info = null;
		}
	};

	/**
	 * Return the specified toolbar form the map
	 * @public
	 * @param type {String}
	 * @return Return the toolbar
	 */
	self.getToolbar = function(type) {
		return self.innerMap.controls[type || google.maps.ControlPosition.TOP_CENTER];
	};

	self.addElementToToolbar = function(element, position) {
		var toolbar = self.getToolbar(position);

		toolbar.push(element);
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
	 * Reisze the map to the given size
	 * @param  {[type]} width  [description]
	 * @param  {[type]} height [description]
	 * @return {[type]}        [description]
	 */
	self.resize = function(width, height) {

		if (width) {
			self.node.style.width = width.constructor === Number ? width + 'px' : width;
			self.width = width;
		}

		if (height) {
			self.node.style.height = height.constructor === Number ? height + 'px' : height;
			self.height = height;
		}

		google.maps.event.trigger(self.innerMap, 'resize');
	};

	/**
	 * Make the map fit the bounds of all the loaded shapes
	 * @return {[type]} [description]
	 */
	self.fitToShapes = function(maxZoom){

		var list = [];

		for (var i = 0, longShapes = self.shapes.length; i < longShapes; i++) {
			list.push(self.shapes[i].getPosition());
		}

		self.fit(list, maxZoom);
	};
    
    self.fit = function(list, maxZoom) {
        
        var bounds = new google.maps.LatLngBounds();
        
        for (var i = 0, longList = list.length; i < longList; i++) {
			bounds.extend(list[i]);
		}
        
        /*
        self.innerMap.panToBounds(bounds);
		self.innerMap.setCenter(bounds.getCenter());
        */
        self.innerMap.fitBounds(bounds);

        google.maps.event.addListenerOnce(self.innerMap, 'idle', function () {
            if (self.innerMap.getZoom() > maxZoom) {
                self.innerMap.setZoom(maxZoom);
            }
        });
		
    };
	
	self.setType = function(type) {
		self.innerMap.setMapTypeId(type);
	};
	
	self.zoomIn = function () {
		self.innerMap.setZoom(self.innerMap.getZoom() + 1);
	};
	
	self.zoomOut = function () {
		self.innerMap.setZoom(self.innerMap.getZoom() - 1);
	};
    
    self.getLocationFromPoint = function (lat, lng, success, error) {
        
        var latLng = new google.maps.LatLng(lat, lng);
        self.getLocationFromLatLng(latLng, success, error);
    };
    
    self.getLocationFromLatLng = function (latLng, success, error) {
        
        var geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({latLng: latLng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                success.call(self, results);
            } else {
                error.call(self, status);
            }
      });
    };

    /**
     * Conver a latLng object into a point object based in the current map projection
     * @method
     * @public
     * @param {google.maps.LatLng} latLng
     * @return {google.maps.Point}
     */
    self.latLngToPixels = function(latLng) {
        
        var projection = self.innerMap.getProjection(),
            scale = Math.pow(2, self.innerMap.getZoom()),
            result;
        
        result = projection.fromLatLngToPoint(latLng);
        result.x = result.x * scale;
        result.y = result.y * scale;
        
        return result;
    };
    
    /**
     * Conver a point object into a LatLng object based in the current map projection
     * @method
     * @public
     * @param {google.maps.Point} point
     * @return {google.maps.LatLng}
     */
    self.pixelsToLatLng = function(point) {
        
        var projection = self.innerMap.getProjection(),
            scale = Math.pow(2, self.innerMap.getZoom()),
            result;
        
        point.x = point.x / scale;
        point.y = point.y / scale;
        
        result = projection.fromPointToLatLng(point);
        
        return result;
    };
    
	// -------------------

	// Call the constructor
	init.apply(self, arguments);
};
