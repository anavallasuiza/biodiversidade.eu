
/**
 * Class fot paint a UTM grid
 */
mapas.ImageOverlay = function() {

	"use strict";

	// Private atttributes
	// -------------------
	
	/**
	 * Reference to the class
	 * @type {Object}
	 */
	var myself = mapas.UTMGrid;

	/**
	 * Reference to the instance of the class
	 * @type {mapas.UTMGrid}
	 */
	var self = this;

	/**
	 * Prototype for the parent class for tyhe grid (google.maps.OvarlayView)
	 * @type {Object}
	 */
	var parent = null;
	
	
	// Public attributes
	// -------------------
	
	/**
	 * Original configuration
	 * @type {Object}
	 */
	self.config;

	/**
	 * Id for the grid
	 * @type {String}
	 */
	self.id;

	/**
	 * Image url
	 * @type {String}
	 */
	self.image;
	
	/**
	 * Image bounds
	 * @type {google.maps.LatLngBounds}
	 */
	self.bounds;
	
	/**
	 * Instance of google maps
	 * @type {google.maps.Map}
	 */
	self.map;

	/**
	 * Projection for the OverlayView
	 * @type {google.maps.MapProjection}
	 */
	self.projection;

	/**
	 * HTMLElement used as container for the grid.
	 * @type {HTMLElement}
	 */ 
	self.node = null;

	/**
	 * List of the listeners attached by the grid so we can remove them if necesary
	 * @type {Array}
	 */ 
	self.listeners = [];
	
	/**
	 * Id for the base html element
	 * @type {String}
	 */
	self.code;
	
	/**
	 * Pane used to inject the grid (google.maps.MapPanes)
	 * @type {String}
	 */
	self.pane = 'overlayMouseTarget';
	
	
	// Private methods
	// -------------------
	
	/**
	 * Crreate the drawing node
	 * @return {HTMLElement} Drawing html node
	 */
	function createNode() {

		var node = document.createElement('div');		
		node.id = self.code;
		node.className = self.code;

		node.style.position = 'absolute';
		node.style.borderWidth = 0;

		var img = document.createElement('img');
		img.src = self.image;
		img.style.height = '100%';
		img.style.width = '100%';
		img.style.position = 'absolute';
		img.style.opacity = '0.6';
		
		node.appendChild(img);
		
		return node;
	};
	
	/**
	 * Constructor
	 * @type {String} id 		Id of the grid
	 * @type {Object} config 	Configuration object
	 */
	function init(id, config) {

		self.id = id;
		self.config = config;

		if (config) {

			if (config.url) {
				self.image = config.url
			}
			
			if (config.bounds) {
				self.bounds = config.bounds;
			}
		}

		// Save the parent class
		parent = myself.parent;

		// Set the code and create base node for the grid
		self.code = 'imageoverlay-' + id;
		self.node = createNode();
	};
	
	
	// Public methods
	// -------------------
	
	/**
	 * Add the grid ot the map
	 */
	self.onAdd = function() {

		// Get the map
		self.map = this.getMap();

		// Get the projection
		self.projection = self.getProjection();

		// Add the basic node to the pane
		self.getPanes()[self.pane].appendChild(self.node);
	};
	
	/**
	 * Draw the map
	 * @type {Boolean} fromPrepare	Indicates that the method is called from a timeout
	 */
	self.draw = function(fromPrepare) {

		var sw,
			ne;
		
		ne = self.projection.fromLatLngToDivPixel(self.bounds.getNorthEast());
		sw = self.projection.fromLatLngToDivPixel(self.bounds.getSouthWest());
		
		self.node.style.left = sw.x + 'px';
		self.node.style.top = ne.y + 'px';
		self.node.style.width = (ne.x - sw.x) + 'px';
		self.node.style.height = (sw.y - ne.y) + 'px';
		
		return true;
	};
	
	/**
	 * Remove the grid and the listeners
	 */
	self.onRemove = function() {

		var node = document.getElementById(self.code);

		if (node) {
			node.parentNode.removeChild(node);
			self.node = null;
		}

		for (var i = 0; i < self.listeners.length; i++) {
			google.maps.event.removeListener(self.listeners[i]);
		}

		self.listeners = [];
	};

	// -------------------

	// Call the constructor
	init.apply(this, arguments);
};

// When libraries are ready extend the overlayview with the grid
mapas.actionOnLibraryLoad.push(function(){
	mapas.ImageOverlay.prototype = new google.maps.OverlayView;
	mapas.ImageOverlay.parent = google.maps.OverlayView.prototype;
	mapas.ImageOverlay.constructor = mapas.ImageOverlay;
});