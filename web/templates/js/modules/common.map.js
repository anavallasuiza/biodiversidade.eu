/*jslint browser:true */
/*global google, $, jQuery, mapas, common, config, catalogo, Blob, BlobBuilder, console, alert, confirm, i18n */

/**
 * Common ui module, auto initializes
 */
common.map = (function () {
	
	"use strict";
	
	var self = {};
    
    
    function typeChanged(e) {
			
		var $relatedControls = $(self.elements.toggleLabels + ', ' + self.elements.toggleLimits),
			type = $(e.target).attr('data-value');

        self.mapa.setType(self.config.MAP_TYPES[type]);

		if (type === 'satelite') {
			$relatedControls.attr('disabled', 'disabled').parent().addClass('disabled');
		} else {
			$relatedControls.removeAttr('disabled').parent().removeClass('disabled');
		}
	}
	
	function isFullscreen() {
		
		return (document.webkitFullscreenElement || document.webkitFullScreenElement ||
				document.mozFullscreenElement || document.mozFullScreenElement ||
				document.fullscreenElement) ? true : false;
	}
	
	function fullscreen(e) {
		
		var $element = $(self.element).parent(),
            $grid = $('#grid-catalogo');

        e.preventDefault();
        e.stopPropagation();
        
		if (isFullscreen()) {
			
			$element.addClass('fullscreen');
			$(document.body).addClass('fullscreen');
			$element.data('width', self.mapa.width);
		    $element.data('height', self.mapa.height);
			
		    self.mapa.resize('100%', '100%');

            var w_width = $(window).width(),
                w_height = $(window).height();

            $grid
                .css('width', 'auto')
                .css('height', 'auto')
                .attr('width', w_width)
                .attr('height', w_height)
                .find('> svg')
                .attr('width', w_width)
                .attr('height', w_height);
		} else {
			$element.removeClass('fullscreen');
			$(document.body).removeClass('fullscreen');

            var w_width = $element.data('width'),
                w_height = $element.data('height');

            self.mapa.resize(w_width, w_height);

            $grid
                .css('width', 'auto')
                .css('height', 'auto')
                .attr('width', w_width)
                .attr('height', w_height)
                .find('> svg')
                .attr('width', w_width)
                .attr('height', w_height);
		}
        
        self.trigger('fullscreen', [isFullscreen()]);
        
        return false;
	}
	
	function fullscreenClick(e) {
		var $element = $(self.element).parent(), element = $element[0];
	
		if (isFullscreen()) {
			(document.cancelFullScreen || (document.mozCancelFullScreen || document.webkitCancelFullScreen)).call(document);
		} else {
			(element.requestFullscreen || (element.mozRequestFullScreen || element.webkitRequestFullscreen)).call(element);
		}
	}
    
    self.mapa = null;
    
    self.config = null;
    
    self.elements = {
        toolbarTop: '#mapa-toolbar-top',
		toolbarBottom: '#mapa-toolbar-bottom',
        
        loadingSpinner: '#map-loading-spinner',
        
        zoomPlus: '#zoom-plus',
		zoomMinus: '#zoom-minus',
		
		mapType: '#map-type',
        
        fullscreen: '#fullscreen'
    };
    
    self.init = function (alias, element, params) {
        
        var options = {
            style: {},
            mapOptions: {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                scaleControl: true,
                scaleControlOptions: { position: google.maps.ControlPosition.BOTTOM_LEFT }
            }
        };
        
        // Apply event management mixin to this instance
        mapas.utils.EventsMixin.call(self);
        
        if (params.HEIGHT) {
            options.style.height = params.HEIGHT;
        }
        
        if (params.DEFAULT_ZOOM) {
            options.mapOptions.zoom = params.DEFAULT_ZOOM;
        }
            
        if (params.MIN_ZOOM) {
            options.mapOptions.minZoom = params.MIN_ZOOM;
        }
            
        if (params.DEFAULT_CENTER) {
            options.mapOptions.center = params.DEFAULT_CENTER;
        }
        
        if (params.ESTILO_MAPA) {
            options.mapOptions.styles = params.ESTILO_MAPA;
        }
        
        if (params.PERSISTS) {
            options.persists = true;
        }
        
        // Avoid scroll until users click on the map
        options.mapOptions.scrollwheel = false;
        
         // Create the map
		self.mapa = mapas.create(alias, element, options);
        
        google.maps.event.addListenerOnce(self.mapa.innerMap, 'mousedown', function () {
            self.mapa.innerMap.setOptions({scrollwheel: true});
        });
        
        // Zoom controls
		$(self.elements.zoomMinus).on('click', function () { self.mapa.zoomOut(); });
		$(self.elements.zoomPlus).on('click', function () { self.mapa.zoomIn(); });
		
		// Type of the map
		$(self.elements.mapType).on('change', typeChanged);
		
		// Clicked fullscreen button
		$(self.elements.fullscreen).on('click', fullscreenClick);
        
        if (element.requestFullscreen) {
            $(document).on('fullscreenchange', fullscreen);
        } else if (element.mozRequestFullScreen) {
            $(document).on('mozfullscreenchange', fullscreen);
        } else if (element.webkitRequestFullscreen) {
            $(document).on('webkitfullscreenchange', fullscreen);
        }
        
		
        
        // Save the element and the config
        self.element = element;
        self.config = params;
        
        // Stuff to do when the map is fully loaded
		$(window).on('load', function () {
            
            var toolbarTop = document.querySelector(self.elements.toolbarTop),
                toolbarBottom = document.querySelector(self.elements.toolbarBottom),
                loading = document.querySelector(self.elements.loadingSpinner);
            
            // If exists Add the toolbars to the map
            if (toolbarTop) {
                self.mapa.addElementToToolbar(toolbarTop, google.maps.ControlPosition.TOP_LEFT);
            }
            
            // If exists Add the toolbars to the map
            if (toolbarBottom) {
                self.mapa.addElementToToolbar(toolbarBottom, google.maps.ControlPosition.LEFT_BOTTOM);
            }
            
            // Add the loading indicator too
            if (loading) {
                self.mapa.addElementToToolbar(loading, google.maps.ControlPosition.TOP_CENTER);
            }
        });
        
        return self.mapa;
    };
    
    
    self.mapDataMixin = function () {
        
        var self = this,
            defaultProps,
            list,
            centroids = {1000: [], 10000: []},
            corners = [];
        
        /**
         * Parse a json string coming from a html element attribute
         * @method
         * @private
         * @param {String} string
         * @return {Object}
         */
        function parseJSON(string) {
            return JSON.parse(string.replace(/\'/g, '"'));
        }
        
        /**
         * Create a new object by combining all the objects provided as arguments
         * @method
         * @private
         * @param {Object} 
         * @return {Object}
         */
        function createObject() {
            
            var result = {},
                i,
                object,
                key;
            
            for (i = 0; i < arguments.length; i += 1) {
                
                object = arguments[i];
                
                for (key in object) {
                    if (object.hasOwnProperty(key)) {
                        result[key] = object[key];
                    }
                }
            }
            
            return result;
        }
        
        /**
         * Get the sw corner code for a lat/lng centroid for the given size
         * @method
         * @private
         * @param {Number} lat
         * @param {Number} lng
         * @param {Number} size
         * @return {String}
         */
        function getCornerCode(lat, lng, size) {
            var corner = mapas.utils.getCellSWCorner({lat: lat, lng: lng}, size);
            return mapas.utils.getCornerCode(corner);
        }
        
        /**
         * Load the given data in the map using the list object
         * @method
         * @private
         * @param {Object/String} data Data of the shape. Can be as json string or an object.
         * @param {String} type Type of the shape, marker or polygon atm.
         * @param {Object} props Object with the default properties for the shape
         * @param {Number} size Size of the shape when its a polygon
         */
        function loadData(shapes, type, props, size, callback) {
            
            var i,
                longShapes,
                codigo,
                shape,
                corner,
                data,
                result = [];
            
            if (shapes.constructor === String) {
                shapes = parseJSON(shapes);
            }
                        
            for (i = 0, longShapes = shapes.length; i < longShapes; i += 1) {
            
                data = shapes[i];
                codigo = type + '-' + data.id;
                
                if (!self.shapes.list.shapes[codigo]) {
            
                    shape = {
                        id: codigo,
                        code: data.id,
                        type: type,
                        size: size
                    };
                    
                    shape = createObject(shape, props);
                    shape.context = data.context;
                    
                    if (type === 'marker') {
                        shape.points = [{latitude: parseFloat(data.latitude), longitude: parseFloat(data.lonxitude)}];
                        self.shapes.list.loadShape(shape);
                    } else {
                        corner = getCornerCode(data.latitude, data.lonxitude, size);
                        shape.corner = corner;
                        
                        if (size <= 1000) {
                            shape.parent = getCornerCode(data.latitude, data.lonxitude, 10000);
                        }
                        
                        corners[corner] = corners[corner] || [];
                        
                        if (!centroids[size][corner]) {
                                            
                            centroids[size][corner] = [];
                            
            
                            shape.points = mapas.utils.getCentroidPoints(parseFloat(data.latitude), parseFloat(data.lonxitude), size);
                            self.shapes.list.loadShape(shape);
                         
                            // Fix zindex for polygons
                            shape.object.setOptions({zIndex: size > 1000 ? 0 : 1});
                        }
                        
                        centroids[size][corner].push(shape);
                        corners[corner].push(shape);
                    }
                    
                    if (callback && callback.constructor === Function) {
                        callback.call(self, shape);
                    }
                    
                    result.push(shape);
                }
            }
            
            return result;
        }
        
        self.shapes = {
        
            init: function (config) {
                
                // Add the shapes namespace the ability to handle events
                mapas.utils.EventsMixin.apply(self.shapes);
                
                
                self.shapes.list = new mapas.Shapes(self.map, config);
                self.shapes.list.on('shapes.click', function (shape, evt) { self.shapes.trigger('click', [evt, shape]); });
            },
            
            /**
             * Load data onte the map using a shapes list element
             * @method
             * @public
             * @param {Object} data List of object with items, type and size attribute for each type of data to load
             * @param {Object} props Object with the default properties for the shape
             * @param {Boolean} clear Clears the list before loads the data if true
             */
            load: function (data, props, callback) {
                
                var key,
                    result = [],
                    shapes;
                
                for (key in data) {
                    if (data.hasOwnProperty(key)) {
                        shapes = loadData(data[key].items, data[key].type, props, data[key].size, callback);
                        result = result.concat(shapes);
                    }
                }
                
                return result;
            },
            
            /**
             * Remove all the loaded shapes form map
             * @method
             * @public
             */
            clear: function () {
                if (self.shapes.list) {
                    self.shapes.list.clear();
                }
                
                centroids = {1000: [], 10000: []};
                corners = [];
            },
            
            /**
             * Get all the loaded shapes
             * @method
             * @public
             * @return {Object} Collection of the loaded shapes
             */
            getAll: function () {
                return self.shapes.list.shapes;
            },
            
            /**
             * Return de centroi corners for the given size
             * @method
             * @public
             * @param {Number} size
             * @return {Object}
             */
            getCentroids: function (size) {
                
                var result = centroids;
                
                if (size) {
                    result = centroids[size];
                }
                
                return result;
            },
            
            /**
             * Get all the shapes for a corner
             * @method
             * @public
             * @return {Object} Collection of shapes for the corner
             */
            getCorner: function (code) {
                return corners[code];
            },
            
            fit: function (zoom) {
                self.shapes.list.fitMap(zoom);
            }
        };
    };
    
	return self;
    
}());