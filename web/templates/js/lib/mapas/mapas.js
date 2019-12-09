
/**
 * "Mapas.js" namespace
 */
var mapas = {

	// 'Constants'
	// -------------------

	/**
	 * Base url to load maps library
	 * @property
	 * @type {String}
	 */
	GMAPS_URL: 'http://maps.googleapis.com/maps/api/js?sensor=false',

	/**
	 * Default callback to use when map libraries are loaded
	 * property
	 * @type {String}
	 */
	DEFAULT_LOAD_CALLBACK: 'mapas.processActions',

	// Attributes
	// -------------------

	/**
	 * List of maps
	 * @property
	 * @type {Object}
	 */
	maps: {

	},

	/**
	 * Force the dinamic load of google maps libraries if they are not previously loaded using
	 * google.load
	 * @type {Boolean}
	 */
	loadLibsIfNotPresent: true,

	/**
	 * True if the google maps librearyn is laoding dinamically
	 * @property
	 * @type {Boolean}
	 */
	delayed : false,

	/**
	 * List of delayed actions to execute when the google maps library gets loaded
	 * @property
	 * @type {Array}
	 */
	actionQueue: [

	],

	actionOnLibraryLoad: [

	],

	/**
	 * List of aditional libraries to load with google maps
	 * @property
	 * @type {Array}
	 */ 
	libraries: [],

	/**
	 * List of the grids
	 * @type {Object}
	 */
	grids: {},

	// -------------------

	// Methods
	// -------------------

	/**
	 * Creates a new map in the specified node with the specified options
	 * @method
	 *
	 * @param {Integer} Id Id used for map creation
	 * @param {HTMLElement} Node Html node used as a container for the map
	 * @param {Object} config Object with the options for map creation
	 *
	 * @return {mapas.Map} Instance os the map if everything is ok
	 */
	create: function(id, node, config) {

		"use strict";

		if (!node) {
			throw new Error('Yout should specify one html node for the map!');
		}

		if (this.maps[id]) {
			throw new Error('There is already a map with id "' + id + '".');
		}

		if (!mapas.utils.checkNamespace(['google', 'maps'])) {

			if (this.loadLibsIfNotPresent) {
				this.actionQueue.push({
					method: 'create',
					params: arguments
				});

				if (this.delayed === false) {
					this.loadLibraries();
				}

				return true;
			} else {
				throw new Error('You need to load google maps first!');	
			}
			
		} else {
			this.processActions(true);	
		}

		var instance = new mapas.Map(id, node, config);
		this.maps[id] = instance;

		return instance;
	},

	/**
	 * [makeGrid description]
	 * @param  {[type]} id     [description]
	 * @param  {[type]} type   [description]
	 * @param  {[type]} config [description]
	 * @return {[type]}        [description]
	 */
	makeGrid: function(id, type, config) {

		if (type === 'utm') {
			var instance = new mapas.UTMGrid(id, config);
		} else {
			throw new Error('Grid type "' + type + '" is not supperted!');
		}



		return instance;
	},

	/**
	 * [destoryGrid description]
	 * @param  {[type]} id [description]
	 * @return {[type]}    [description]
	 */
	destroyGrid: function(id) {

		if (id && this.grids[id]) {
			this.grids[id].setMap(null);
		} else {

			for (var id in this.grids) {
				this.grids[id].setMap(null);
			}
		}
	},

	/**
	 * Dinamically load google maps library
	 * @method
	 * @param {Function}	callback Callback to fire when the library is loaded 
	 */
	loadLibraries: function(callback) {

		var loadCallback = callback || this.DEFAULT_LOAD_CALLBACK;

		var script = document.createElement('script');
		script.src =  mapas.GMAPS_URL + '&libraries=' + this.libraries.join(',') + '&callback=' + loadCallback;

		document.body.appendChild(script);
	},

	/**
	 * Process and execute all the queued actions. To be fired when the libraries are loaded
	 */
	processActions: function(libsOnLoadOnly) {

		if (!libsOnLoadOnly) {
			var action;
			for (var i = 0, length = this.actionQueue.length; i < length; i++) {
				action = this.actionQueue[i];
				mapas[action.method].apply(this, action.params);
			}
		}

		for (var i = 0, length = this.actionOnLibraryLoad.length; i < length; i++) {
			this.actionOnLibraryLoad[i].apply(this);
		}
	}


	// -------------------
};
