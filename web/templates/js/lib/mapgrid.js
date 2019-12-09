
/**
 * Representacion of a grid overlay in a specified map
 * @version 0.2
 */
var MapGrid = function(tileSize) {

	"use strict";

	//-- Private attributes
	//-------------------------

	/**
	 * Current instance of grid
	 * @type {MapGrid}
	 */
	var self = this;

	/**
	 * Constant with the default tile height for the overlay
	 * @type {Number}
	 */
	var DEFAULT_TILE_WIDTH = 256;

	/**
	 * Constant with the default tile height for the overlay
	 * @type {Number}
	 */
	var DEFAULT_TILE_HEIGHT = 256;
		

	//-- Public attributes
	//-------------------------

	/**
	 * Position of the overlay in the overlayMapTypes array for the current map
	 * @type {Number}
	 */
	self.id = null;

	/**
	 * Tilesize for the grid (google.maps.MapType interface)
	 * @type {google.maps.Size}
	 */
	self.tileSize = null;

	/**
	 * Reference to the map for the grid
	 * @type {google.maps.Map}
	 */
	self.map = null;

	/**
	 * Callback for when the tile get requested
	 * @type {Function}
	 */
	self.tileCallback = null;


	//-- Private attributes
	//-------------------------

	

	/**
	 * Constructor
	 * @param node {HTMLElement}
	 * @param width {google.maps.Size/Number} Integer width or google.maps.Size
	 * @param height {Number} Integer 
	 */
	function init(width, height, tileCallback) {

		if (arguments.length === 2 || arguments.length === 3) {
			tileSize = new google.maps.Size(width, height);
		} else if (arguments.length === 1) {
			tileSize = new google.maps.Size(width, width);
		} else {
			tileSize = new google.maps.Size(DEFAULT_TILE_WIDTH, DEFAULT_TILE_HEIGHT);
		}

		self.tileSize = tileSize;

		self.tileCallback = tileCallback;
	}


	//-- Public methods
	//-------------------------


	/**
	 * Return the current tile (google.maps.MapType interface)
	 * @param coord {google.maps.Size} Coordinates (not latlng) for the current tile
	 * @param zoom {Number} Zoom for the current tile
	 * @param ownerDocument {HTMLElement} Parent fot the result tile overlay html
	 *
	 * return Html overlay for the tile
	 */
	self.getTile = function(coord, zoom, ownerDocument) {
		
		var pointTop = MapGrid.coordsToPoint(coord.x, coord.y, zoom, self.tileSize.width, self.tileSize.height);
		var latLngTop = self.map.getProjection().fromPointToLatLng(pointTop);

		var pointBottom = MapGrid.coordsToPoint(coord.x + 1, coord.y + 1, zoom, self.tileSize.width, self.tileSize.height);
		var latLngBottom = self.map.getProjection().fromPointToLatLng(pointBottom);

		var bounds = new google.maps.LatLngBounds();
		bounds.extend(latLngTop);
		bounds.extend(latLngBottom);



		//var dest = new Proj4js.Proj('EPSG:23029');  ED50
		//var source = new Proj4js.Proj('EPSG:4326'); //<-- lat/ln mercator
		//'EPSG:32629' <- UTM 29N Datum WGS84
		//'EPSG:23029' <- UTM 29N Datum ED50

		//console.log('Get tile!');
		
		/*
		var source = new Proj4js.Proj('GOOGLE');
		var dest = new Proj4js.Proj('EPSG:32629'); 

		var p = new Proj4js.Point(42.8771,-8.5482);
		Proj4js.transform(source, dest, p);
		*/

		//-------------------------------------------------------
		//------------------------------------------------------

		//EPSG:23029
		//http://www.spatialreference.org/ref/epsg/23029/

		//EPSG:900913 <-- Google/GMaps
		//SR-ORG:7483
		//EPSG:3857
		/*
		Proj4js.defs["EPSG:32629"] = "+proj=utm +zone=29 +ellps=WGS84 +datum=WGS84 +units=m +no_defs";
		Proj4js.defs["EPSG:23029"] = "+proj=utm +zone=29 +ellps=intl +units=m +no_defs";

		var source = new Proj4js.Proj('GOOGLE');
		var dest = new Proj4js.Proj('EPSG:32629'); 

		var p = new Proj4js.Point(latLng.lat(),latLng.lng());
		Proj4js.transform(source, dest, p); 
		*/
		//console.log(p.x, p.y);

		//var res = '' + p.y + '<br/>' + p.x + '<br/>::<br/>' + latLng.lat() + '<br/>' + latLng.lng();
		//var res = 'Tile'
		

		if (self.tileCallback) {
			var $tile = $(self.tileCallback(self.tileSize.width, self.tileSize.height, latLngTop, latLngBottom, bounds));
		} else {
			var res = '' + coord.x + ',' + coord.y + '<br/>' + self.tileSize.width + ',' + self.tileSize.height;
			var res = '';

			var $tile = $('<div></div>').css({
				'width': self.tileSize.width + 'px',
				'height': self.tileSize.height + 'px',
				'font-size': '8px',
				'color': '#AAA',
				'border': '1px dotted #AAAAAA',
			}).html(res);
		}

		return $tile[0];
	}

	self.releaseTile = function(tile) {

	};
	
	// Call the constructor
	init.apply(self, arguments);	
};


//-- Static methods
//-------------------------

/**
 * Convert coords to a google.maps.Point
 * @param x
 * @param y
 * @param zoom
 * @param tileSizeX
 * @param tileSizeY
 * return google.maps.Point for the map with the specified zoom
 */
MapGrid.coordsToPoint = function(x, y, zoom, tileSizeX, tileSizeY) {
	var numTiles = 1 << zoom;
	return new google.maps.Point((x * tileSizeX) / numTiles, (y * tileSizeY) / numTiles);
};