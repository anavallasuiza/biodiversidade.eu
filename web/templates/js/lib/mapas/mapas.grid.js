
/**
 * Class fot paint a UTM grid
 */
mapas.UTMGrid = function() {

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

	// -------------------

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
	 * Id for the base ehtml element
	 * @type {String}
	 */
	self.code;

	/**
	 * Base drawing element for raphael
	 * @type {Object}
	 */
	self.paper;

	/**
	 * Pane used to inject the grid (google.maps.MapPanes)
	 * @type {String}
	 */
	self.pane = 'overlayMouseTarget';

	/**
	 * Grid default configuration
	 * @type {Object}
	 */
	self.grid = [
		{
			'zone': 29,
			'hemisphere': 'N',
			'easting': {from: 200000, to: 800000},
			'northing': {from: 0, to: 9999999}, 		// All the zone for the hemisphere,
			'clip': {
				'tl': {
					'lat': 43,
					'lng': -10
				},
				'br': {
					'lat': 36,
					'lng': -6
				}
			}
		},
		{
			'zone': 30,
			'hemisphere': 'N',
			'easting': {from: 200000, to: 800000},
			'northing': {from: 0, to: 9999999}, 		// All the zone for the hemisphere,
			'clip': {
				'tl': {
					'lat': 43,
					'lng': -6
				},
				'br': {
					'lat': 36,
					'lng': -0
				}
			}
		},
		{
			'zone': 31,
			'hemisphere': 'N',
			'easting': {from: 200000, to: 800000},
			'northing': {from: 0, to: 9999999}, 		// All the zone for the hemisphere,
			'clip': {
				'tl': {
					'lat': 43,
					'lng': -0
				},
				'br': {
					'lat': 36,
					'lng': 4.50
				}
			}
		}
	];

	/**
	 * Sizes of the grid based on the zoom
	 * @type {Object}
	 */
	self.sizes = [
		{zoom: 7, width: 50000},
		{zoom: 10, width: 10000},
		{zoom: 20, width: 1000}
	];

	/**
	 * Size of the grid
	 * @type {Number}
	 */
	self.size;

	/**
	 * Size of the guide lines
	 * @type {Number}
	 */
	self.guides;

	/**
	 * Animation to use in the transition between grid sizes, zoom, position
	 * @type {Boolean}
	 */
	self.transition = {
		speed: 100,
		easing: 'linear'
	};

	/**
	 * Seconds of the draw timeout 
	 * @type {Number}
	 */
	self.drawDelay = 600;

	/**
	 * Default style for the lines
	 * @type {Object}
	 */
	self.lineStyle = {
		stroke: '#999'
	};

	/**
	 * Default style for the guide lines
	 * @type {Object}
	 */
	self.guideStyle = {
		stroke: 'red'
	};

	/**
	 * Default style fot the cells
	 * @type {Object}
	 */
	self.cellStyle = {
		stroke: '#000',
		fill: '#85b200',
		opacity: 0.5
	};

	/**
	 * Offset sued to draw in the paper
	 * @type {Number}
	 */
	self.offset = 0;

	/**
	 * Group with all the raphael elements for the grid
	 * @type {raphael.set}
	 */
	self.allGroup;

	/**
	 * [linesGroup description]
	 * @type {raphael.set}
	 */
	self.linesGroup;

	/**
	 * [guidesGroup description]
	 * @type {raphael.set}
	 */
	self.guidesGroup;

	/**
	 * [cellsGroup description]
	 * @type {raphael.set}
	 */
	self.cellsGroup;

	/**
	 * [pointsGruop description]
	 * @type {raphael.set}
	 */
	self.pointsGroup;
	
	/**
	 * Groups used to represent each one of the zones of hte grid
	 * @type {raphael.set}
	 */
	self.zonesGroup = [];

	/**
	 * If true avoid draing lines on each redraw (to use when you just need the cells)
	 * @type {Boolean}
	 */
	self.drawGuides = true;

	// -------------------


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
		node.style.left = 0;
		node.style.top = 0;
		node.style.zIndex = 2;

		return node;
	}

	/**
	 * Resize the drawing node to the given size or to the map size if no size is given
	 * @param  {Number} width  Width of the ndoe
	 * @param  {Number} height Height of the node
	 * @return {Boolean} True
	 */
	function resizeNode(width, height) {

		var div = self.map.getDiv();

		if (!width) {
			width = div.offsetWidth;
		}

		if (!height) {
			height = div.offsetHeight;
		}
		
		if (width.constructor === Number) {
			width = width + 'px';
		}
		
		if (height.constructor === Number) {
			height = height + 'px';
		}

		self.node.style.width = width;
		self.node.style.height = height;

		return true;
	}

	/**
	 * Move the node to the given position
	 * @param {Number} top
	 * @param {Numebr} left
	 */
	function moveNodeTo(left, top) {

		self.node.style.top = top + 'px';
		self.node.style.left = left + 'px';
	}

	/**
	 * Create the paper object using rapahel
	 * @return {Object} Drawing object
	 */
	function createPaper() {
	
		// Create the paper to draw
		var paper = Raphael(self.node, self.node.offsetWidth, self.node.offsetHeight);
		
		// Create the sets
		self.allGroup = paper.set();
		self.linesGroup = paper.set();
		self.guidesGroup = paper.set();
		self.cellsGroup = paper.set();
		self.pointsGroup = paper.set();

		return paper;

	}

	/**
	 * Clear the paper
	 */
	function clearPaper() {

		// Clear canvas
		self.paper.clear();

		// Recreate the sets
		self.allGroup = self.paper.set();
		self.linesGroup = self.paper.set();
		self.guidesGroup = self.paper.set();
		self.cellsGroup = self.paper.set();
		self.pointsGroup = self.paper.set();
	}

	/**
	 * Draw a line using rapahel path based on an array of points and style or a simple line using 4 points
	 * @param  {Array/Number} points 	Array wth the points of the path
	 * @param  {Object/Number} style 	Object with the styles of the line
	 * @param  {Number} x1				Optional x point when you draw a simple line
	 * @param  {Number} y1				Optional y point when you draw a simple line
	 */
	function drawPathLine(points, style, x1, y1) {

		if (arguments.length === 4) {
			points = [{'x': points, 'y': style}, {'x': x1, 'y': y1}];
		}

		var path = 'M' + points[0].x + ',' + points[0].y;
		for (var i = 1, longPoints = points.length; i < longPoints; i++) {

			if (points[i]) {
				path += 'L' + points[i].x + ',' + points[i].y;
			}
		}

		var element = self.paper.path(path);

		if (style) {
			element.attr(style);
		}

		return element;
	}

	/**
	 * Draw a cell over the grid based on a pair of utm coordinates
	 * @param  {Object} sw    South west coordintes for the cell
	 * @param  {Object} ne    North east coordinates for the cell
	 * @param  {Object} style Styles for the cell
	 * @return {raphaeljs.element} Raphael path element representing the cell
	 */
	function drawUTMCell(sw, ne, style) {

		// Get the other two pair of coordinates
		var se = {
			easting: ne.easting,
			northing: sw.northing,
			zone: sw.zone,
			hemisphere: sw.hemisphere
		};

		var nw = {
			easting: sw.easting,
			northing: ne.northing,
			zone: ne.zone,
			hemisphere: ne.hemisphere
		};

		// Get the pixels for all the coords
		var pixelsSW = utmToPixels(sw);
		var pixelsSE = utmToPixels(se);
		var pixelsNE = utmToPixels(ne);
		var pixelsNW = utmToPixels(nw);

		// And apply the needed offset to palce them in the paper
		var x = pixelsSW.x - self.offset.x;
		var y = pixelsSW.y - self.offset.y;

		var x1 = pixelsSE.x - self.offset.x;
		var y1 = pixelsSE.y - self.offset.y;

		var x2 = pixelsNE.x - self.offset.x;
		var y2 = pixelsNE.y - self.offset.y;

		var x3 = pixelsNW.x - self.offset.x;
		var y3 = pixelsNW.y - self.offset.y;

		// Draw the cell line using drawPathLine method
		return drawPathLine([{x: x, y: y}, {x: x1, y: y1}, {x: x2, y: y2}, {x: x3, y: y3}, {x: x, y: y}], style);
	}

	/**
	 * Method to invoke after we draw all the lines
	 */
	function afterDraw() {}

	/**
	 * Get the size of the grid based on the zoom and the size of the corresponding guides
	 * @param  {Number} zoom Map zoom
	 * @return {Number} Grid size
	 */
	function getSize(zoom) {

		var result = {};

		for (var i = 0, longSizes = self.sizes.length; i < longSizes; i++) {
			if (!self.sizes[i].zoom || zoom < self.sizes[i].zoom) {
				result.size = self.sizes[i].width;
				
				if (self.sizes[i - 1]) {
					result.guides = self.sizes[i - 1].width;
				}

				break;
			}
		}

		if (!result) {
			result.size = mapas.UTMGrid.DEFAULT_SIZE;
		}

		return result;
	}

	/**
	 * Get the correpsonding pixels on for a lat/lng. U can use and object with the lat and lng properties or a google.maps.LatLng
	 * @param  {google.maps.LatLng/Object} latLng Latitude and longitude
	 * @return {Object} Object with the x and y points
	 */
	function latLngToPixels(latLng) {
        
		var position = latLng, 
            projection;

		if (position.lat.constructor !== Function) {
			position = new google.maps.LatLng(latLng.lat, latLng.lng);
		}

		projection = self.getProjection();

		if (projection) {
			return projection.fromLatLngToDivPixel(position);
		}
	}

	/**
	 * Get the pixels on the map for an utm coordinate
	 * @param  {Object} utm UTM coordinates object
	 * @return {Object} Object with lat and lng
	 */
	function utmToPixels(utm) {

		var result;

		var latLng = mapas.utils.converter.utmToLatLng(utm);
		result = latLngToPixels(latLng);

    	return result;
	}

	/**
	 * Check if the bounds of the map are in the grid zone
	 * @param  {Object} ne North east coordintate for the bounds in utm
	 * @param  {Object} sw South west coordinate for the bounds in utm
	 * @return {Boolean} True if its in the grid zone
	 */
	function boundsAreInZone(ne, sw, grid) {

		var result = true;

		if (sw.zone > grid.zone) {
    		result = false;
    	} else if (ne.zone < grid.zone) {
    		result = false;
    	}

    	if (grid.hemisphere === 'N' && ne.hemisphere === 'S') {
    		result = false;
    	} else if (grid.hemisphere === 'S' && sw.hemisphere === 'N') {
    		result = false;
    	}

    	return result;
	}

	/**
	 * Get the utm bounds for the grid based on the config and the map bounds
	 * @param  {Object} ne 	North east coordinates in utm
	 * @param  {Object} sw 	South west coordinates in utm
	 * @return {Object} Object weith the northing and the easting
	 */
	function getGridBounds(ne, sw, grid) {

		var result = {
			northing: {from: grid.northing.from, to: grid.northing.to},
			easting: {from: grid.easting.from, to: grid.easting.to}
		};

		if (sw.zone === grid.zone) {
			
			result.easting.from = Math.max(sw.easting, grid.easting.from);
		}

		if (ne.zone === grid.zone) {
			
			result.easting.to = Math.min(ne.easting, grid.easting.to);
		}

		if (sw.hemisphere === grid.hemisphere) {
			result.northing.from = Math.max(sw.northing, grid.northing.from);
		}

		if (ne.hemisphere === grid.hemisphere) {
			result.northing.to = Math.min(ne.northing, grid.northing.to);
		}
    	
    	return result;
	}

	/**
	 * Draw aquare based grid
	 * @param {Object} neUTM North east utm bound
	 * @param {Object} swUTM South west utm bound
	 * @param {Object} offset Offset used to draw over the grid (offset.x and offset.y)
	 */
	function drawGridSquares(neBounds, swBounds, offset, grid) {

		var size = self.size;
		var guides = self.guides;

		// Extend the bounds to get the outer points of grid
		var ne = {
			easting: Math.ceil(neBounds.easting / size) * size,
			northing: Math.ceil(neBounds.northing / size) * size,
			zone: neBounds.zone,
			hemisphere: neBounds.hemisphere
		};

		var sw = {
			easting: Math.floor(swBounds.easting / size) * size,
			northing: Math.floor(swBounds.northing / size) * size,
			zone: swBounds.zone,
			hemisphere: swBounds.hemisphere
		};

		// If the bounds are not in the grid zone we stop
		if (!boundsAreInZone(ne, sw, grid)) {
			return false;
		}
    	
    	// get thebounds for the grid so we can start from there
    	var bounds = getGridBounds(ne, sw, grid);

    	var northing = bounds.northing.from;
    	var easting; // = bounds.northing.from;

    	//TODO: To allow the use of easting and northings divisible by the grid size
    	// we need to adjust the first iteration to end in teh right spot, wich should be divisible by the grid size
    	// (start + size) % size = width of the first iteration instead of the full size

    	var utm, points, point, npoints = [], epoints = [], i, style, element, lines = self.paper.set();
	    while(northing <= bounds.northing.to) {

    		easting = bounds.easting.from;
    		i = 0;
    		points = [];

    		while(easting <= bounds.easting.to) {

    			// Lines
    			// ----------------
    			
    			utm = {
    				northing: northing,
    				easting: easting,
    				zone: grid.zone,
    				hemisphere: grid.hemisphere
    			};

    			point = utmToPixels(utm);

    			points.push({
    				x: point.x - offset.x,
    				y: point.y - offset.y,
    				utm: utm
    			});

    			if (!npoints[i]) {
    				npoints[i] = {
    					points: [],
    					easting: utm.easting
    				};
    			}

    			npoints[i]['points'].push({
    				x: point.x - offset.x,
    				y: point.y - offset.y,
    			});

    			easting += size;
    			i++;
    		}

    		style = self.lineStyle;
    		
    		element = drawPathLine(points, style);

    		self.linesGroup.push(element);
    		self.allGroup.push(element);
    		lines.push(element);

    		if (guides && (northing % guides === 0)) {
    			element.attr(self.guideStyle);
	    		self.guidesGroup.push(element);
	    		element.toFront();
	    	}

    		// If there is a transition start eith the transition initial value
    		if (self.transition) {
    			element.attr({opacity: 0});
    		}

    		northing += size;
    	}

    	for (var i = 0, longNPoints = npoints.length; i < longNPoints; i++) {

    		style = self.lineStyle;

    		element = drawPathLine(npoints[i]['points'], style);
    		self.linesGroup.push(element);
    		self.allGroup.push(element);
    		lines.push(element);

    		if (guides && (npoints[i].easting % guides === 0)) {
    			element.attr(self.guideStyle);
    			self.guidesGroup.push(element);
    			element.toFront();
    		}

    		// If there is a transition start eith the transition initial value
    		if (self.transition) {
    			element.attr({opacity: 0});
    		}
    	}

    	return lines;
	}

	/**
	 * Get the clip for the given grid
	 * @param  {[type]} grid [description]
	 * @return {[type]}      [description]
	 */
	function getGridClip(tl, br, offset) {

		var x = tl.x - offset.x;
		var y = tl.y - offset.y;

		var width = br.x - tl.x;
		var height = br.y - tl.y;

		return x + ',' + y + ',' + width + ',' + height;
	}

	/**
	 * [clipGrid description]
	 * @param  {[type]} i      Grid position
	 * @param  {[type]} offset [description]
	 * @return {[type]}        [description]
	 */
	function clipGrid(i, offset) {

		var tl = self.grid[i].clip.tl;
		var br = self.grid[i].clip.br;

		var pixelsTL = latLngToPixels(tl);
		var pixelsBR = latLngToPixels(br);

		self.zonesGroup[i].attr('clip-rect', getGridClip(pixelsTL, pixelsBR, offset));

		var element = drawPathLine([
			{x: pixelsTL.x - offset.x, y: pixelsTL.y - offset.y},
			{x: pixelsBR.x - offset.x, y: pixelsTL.y - offset.y},
			{x: pixelsBR.x - offset.x, y: pixelsBR.y - offset.y},
			{x: pixelsTL.x - offset.x, y: pixelsBR.y - offset.y},
			{x: pixelsTL.x - offset.x, y: pixelsTL.y - offset.y},
		]);

		element.attr({
			'stroke': 'red',
			'opacity': 0
		});
		self.allGroup.push(element);
		self.linesGroup.push(element);
	}

	/**
	 * Constructor
	 * @type {String} id 		Id of the grid
	 * @type {Object} config 	Configuration object
	 */
	function init(id, config) {

		self.id = id;
		self.config = config;

		if (config) {

			if (config.grid) {
				for (var key in config.grid) {
					self.grid[key] = config.grid[key];
				}
			}

			if (config.sizes) {
				self.sizes = config.sizes;
			}

			if (config.transition) {
				self.transition = config.transition;
			}

			if (config.styles) {

				if (config.styles.lines) {
					self.lineStyle = config.styles.lines;
				}

				if (config.styles.guides) {
					self.guideStyle = config.styles.guides;
				}

				if (config.styles.cells) {
					self.cellStyle = config.styles.cells;
				}
			}

			if (config.afterDraw) {
				afterDraw = config.afterDraw;
			}

			if (config.drawGuides !== undefined) {
				self.drawGuides = config.drawGuides;
			}
		}

		// Save the parent class
		parent = myself.parent;

		// Set the code and create base node for the grid
		self.code = 'grid-' + id;
		self.node = createNode();
	}

	// -------------------


	// Public methods
	// -------------------
	
	/**
	 * Reload the grid
	 * @param  {Object} config Object with the configuration for the grid. It its not set then it will use the current one.
	 */
	self.reload = function(config) {

		var map = self.map;

		self.onRemove.apply(self);

		init.call(self, self.id, config);

		self.onAdd.apply(self);
		self.draw.apply(self);
	};

	/**
	 * Add the grid ot the map
	 */
	self.onAdd = function() {

		// Get the map
		self.map = this.getMap();

		// Get the projection
		self.projection = this.getProjection();

		// Resize grid node to the size of the map
		resizeNode();

		// Add the basic node to the pane
		self.getPanes()[self.pane].appendChild(self.node);

		// Create the paper used to draw the grid
		self.paper = createPaper();

		// Set the listeners to redraw when neede
		self.listeners = [
			google.maps.event.addListener(self.map, 'center_changed', self.prepareToDraw),
 			google.maps.event.addListener(self.map, 'resize', self.prepareToDraw)
		];

		if (self.transition) {
			self.listeners.push(google.maps.event.addListener(self.map, 'zoom_changed', self.prepareToDraw));
			self.draw(true);
		}
	};

	/**
	 * Try to draw using a timeout, if there is a previous timeout cancel it
	 */
	self.prepareToDraw = function() {

		if (self.timer) {
			window.clearTimeout(self.timer);
		}

		if (self.linesGroup && self.transition) {
			self.linesGroup.animate({'opacity': 0}, self.transition.speed, self.transition.easing);
			self.cellsGroup.animate({'opacity': 0}, self.transition.speed, self.transition.easing);
			self.pointsGroup.animate({'opacity': 0}, self.transition.speed, self.transition.easing);
		}

		self.timer = window.setTimeout(function(){
			self.draw(true);
		}, self.drawDelay);
	};

	/**
	 * Draw the map
	 * @type {Boolean} fromPrepare	Indicates that the method is called from a timeout
	 */
	self.draw = function(fromPrepare) {

		// Avoid the default draw when zooming if there is a transition so we can use it
		if (self.transition && !fromPrepare) {
			return false;
		}

		var map = this.getMap();
		var zoom = map.getZoom();
		self.projection = this.getProjection();

		// Current bounds
		var bounds = map.getBounds();
		var ne = bounds.getNorthEast();
		var sw = bounds.getSouthWest();
		
		// Current center
		var center = self.projection.fromLatLngToDivPixel(map.getCenter());

		// Convert the bounds to UTM
		var neUTM = mapas.utils.converter.latLngToUTM(ne.lat(), ne.lng());
		var swUTM = mapas.utils.converter.latLngToUTM(sw.lat(), sw.lng());

		var sizeData = getSize(zoom);
		
		var size = sizeData.size;
		var guides = sizeData.guides;

		self.size = size;
		self.guides = guides;

		// Get the position in pixels for the current bounds
		var positionNEBounds = latLngToPixels(ne);
    	var positionSWBounds = latLngToPixels(sw);

    	// Move the grid node to match the bounds
    	moveNodeTo(positionSWBounds.x, positionNEBounds.y);

    	// Offset for the points based on the bounds
    	var offset = {
    		x: positionSWBounds.x,
    		y: positionNEBounds.y
    	};

    	self.offset = offset;
	
		// Remove previous grid
		clearPaper();

		if (self.drawGuides) {

			var lines;
			for (var i = 0, longGrids = self.grid.length; i < longGrids; i++) {
				lines = drawGridSquares(neUTM, swUTM, offset, self.grid[i]);

				self.zonesGroup[i] = null;

				if (lines) {
					self.zonesGroup[i] = lines;

					if (self.grid[i].clip) {
						clipGrid(i, offset);
						//self.zonesGroup[i].attr('clip-rect', getGridClip(self.grid[i], offset));
					}
				}
			}
			

	    	if (self.transition) {
	    		self.linesGroup.animate({'opacity': (self.lineStyle.opacity || 1)}, self.transition.speed, self.transition.easing);
	    		self.cellsGroup.animate({'opacity': (self.cellStyle.opacity || 1)}, self.transition.speed, self.transition.easing);
	    		self.pointsGroup.animate({'opacity': (self.cellStyle.opacity || 1)}, self.transition.speed, self.transition.easing);
	    	} else {
	    		self.linesGroup.attr({'opacity': (self.lineStyle.opacity || 1)});
	    		self.cellsGroup.attr({'opacity': (self.cellStyle.opacity || 1)});
	    		self.pointsGroup.attr({'opacity': (self.cellStyle.opacity || 1)});
	    	}
	    }

    	// Invoke afterDraw after all lines are draw
    	afterDraw.call(self);
		
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

	/**
	 * Establish the map
	 * @param {google.maps.Map/mapas.Map} map Map instance
	 */ 
	self.setMap = function(map) {

		if (map && map.innerMap) {
			parent.setMap.call(this, map.innerMap);
		} else {
			parent.setMap.call(this, map);
		}
	};

	self.getCellCorners = function(cell, size) {
		
		var result = {},
			utm,
			sw,
			i;
		
		
		utm = cell.utm || mapas.utils.converter.latLngToUTM(cell.lat, cell.lng);
		
		
		for (i = 0; i < size.length; i += 1) {
			
			sw = {
				easting: Math.floor(utm.easting / size[i]) * size[i],
				northing: Math.floor(utm.northing / size[i]) * size[i],
				zone: utm.zone,
				hemisphere: utm.hemisphere
			};
			
			result[size[i]] = sw;
		}
		
		return result;
	};

	/**
	 * [drawCells description]
	 * @return {[type]} [description]
	 */
	self.drawCells = function(data, size) {

		var cellData, utm, points = {}, size = size || self.size;

		for (var i = 0, longData = data.length; i < longData; i++) {
			/*
			cellData = data[i];
			
			if (cellData.utm) {
				utm = cellData.utm;
			} else {
				utm = mapas.utils.converter.latLngToUTM(cellData.lat, cellData.lng);	
				cellData.utm = utm;
			}
			
			var sw = {
				easting: Math.floor(utm.easting / size) * size,
				northing: Math.floor(utm.northing / size) * size,
				zone: utm.zone,
				hemisphere: utm.hemisphere
			};

			if (!points[sw.easting + '-' + sw.northing]) {
				points[sw.easting + '-' + sw.northing] = {
					corner: sw,
					data: []
				};
			}

			points[sw.easting + '-' + sw.northing].data.push(cellData);
			*/
			
			cellData = data[i];
			
			self.drawCell(cellData, size);
		}

		/*
		var point, ne, element, center;
		for (var key in points) {

			point = points[key];

			ne = {
				easting: point.corner.easting + size,
				northing: point.corner.northing + size,
				zone: point.corner.zone,
				hemisphere: point.corner.hemisphere
			};

			center = {
				easting: point.corner.easting + (size / 2),
				northing: point.corner.northing + (size / 2),
				zone: point.corner.zone,
				hemisphere: point.corner.hemisphere
			};

			element = drawUTMCell(point.corner, ne);

			if (point.data[0].style) {
				element.attr(point.data[0].style);
			} else {
				element.attr(self.cellStyle);
			}

			element.data('center', mapas.utils.converter.utmToLatLng(center));
			element.data('point', point.data);

			self.cellsGroup.push(element);
			self.allGroup.push(element);
		}
		*/

		//self.cellsGroup.attr(self.cellStyle);

		return self.cellsGroup;
	};

	self.drawCell = function (data, size, context, sw) {
		
		var utm, point, size = size || self.size;

		if (!sw) {
			if (!data.utm) {
				data.utm = mapas.utils.converter.latLngToUTM(data.lat, data.lng);	
			}
			
			sw = {
				easting: Math.floor(data.utm.easting / size) * size,
				northing: Math.floor(data.utm.northing / size) * size,
				zone: data.utm.zone,
				hemisphere: data.utm.hemisphere
			};
		}
		
		var ne, element, center;

		ne = {
			easting: sw.easting + size,
			northing: sw.northing + size,
			zone: sw.zone,
			hemisphere: sw.hemisphere
		};

		center = {
			easting: sw.easting + (size / 2),
			northing: sw.northing + (size / 2),
			zone: sw.zone,
			hemisphere: sw.hemisphere
		};

		element = drawUTMCell(sw, ne);

		if (data.style) {
			element.attr(data.style);
		} else {
			element.attr(self.cellStyle);
		}

		element.data('center', mapas.utils.converter.utmToLatLng(center));
		element.data('point', data);

		element.context = context;

		self.cellsGroup.push(element);
		self.allGroup.push(element);

		return element;
	};
	
	self.drawMultiCell = function(corner, size, data, context) {
	    
	    var styles = data.style,
	        length = styles.length,
	        style,
	        i,
	        sw,
	        ne,
	        center,
	        w,
	        h,
	        element,
	        cell = self.paper.set();

        center = {
			easting: corner.easting + (size / 2),
			northing: corner.northing + (size / 2),
			zone: corner.zone,
			hemisphere: corner.hemisphere
		};

        for (i = 0; i < length; i += 1) {
            style = styles[i];
            
            if (i + 1 < length) {
                w = size / 2;
            } else {
                w = (i + 1) % 2 === 0 ? size / 2 : size;
            }
            
            if (length <= 2) {
                h = size;
            } else {
                h = size / 2;
            }
            
            if (!sw) {
                
                sw = {
                    easting: corner.easting,
                    northing: corner.northing,
                    zone: corner.zone,
                    hemisphere: corner.hemisphere
                };
                
            } else {
                if ((i + 1) % 2 === 0) {
                    sw.easting += w;
                } else {
                    sw.easting = corner.easting;
                }
                
                if (i + 1 > 2) {
                    sw.northing = corner.northing + h;
                } else {
                    sw.northing.y = corner.northing;
                }
            }
            
            ne = {
    			easting: sw.easting + w,
    			northing: sw.northing + h,
    			zone: sw.zone,
    			hemisphere: sw.hemisphere
    		};
    
    		element = drawUTMCell(sw, ne, style);
            
            element.data('center', mapas.utils.converter.utmToLatLng(center));
    		element.data('point', data);
    
    		element.context = context;
    
    	    cell.push(element);	
        }
        
        self.cellsGroup.push(cell);
    	self.allGroup.push(cell);
    	
    	return cell;
	    
	};

	self.drawPoint = function(lat, lng, radius, style, context) {
		
		var point = null, pixels;
		
		pixels = latLngToPixels({lat: lat, lng: lng});

		if (!pixels) {
            console.log("mapas.UTMGrid.drawPoint error");
			return;
		}

		point = self.paper.circle(pixels.x - self.offset.x, pixels.y - self.offset.y, radius);
		point.attr(style);
		point.context = context;
		
		self.pointsGroup.push(point);
		self.allGroup.push(point);
		
		return point;
	};

	/**
	 * Remove all the cells from the grid
	 */
	self.clearCells = function() {

		if (self.cellsGroup) {
			self.cellsGroup.remove();
		}
	};

	self.clearPoints = function() {
		if (self.pointsGroup) {
			self.pointsGroup.remove();
		}
	};

	self.resize = function(width, height) {
		resizeNode(width, height);
	};

	// -------------------

	// Call the constructor
	init.apply(this, arguments);
};

// When libraries are ready extend the overlayview with the grid
mapas.actionOnLibraryLoad.push(function(){
	mapas.UTMGrid.prototype = new google.maps.OverlayView;
	mapas.UTMGrid.parent = google.maps.OverlayView.prototype;
	mapas.UTMGrid.constructor = mapas.UTMGrid;
});
