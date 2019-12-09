/*jslint browser:true */
/*global google, $, mapas, catalogo, indexOf, Blob, BlobBuilder, alert, confirm, console */

/**
 * @classDescription Class for the draing tool used to draw different shapes over the given map
 * @author kieleras
 * @version 0.1
 */
mapas.DrawingTool = function () {

    'use strict';

    //- Private properties
    
    /**
     * Reference to the instance of the class
     * @property {mapas.Map}
     * @private
     */
    var self = this,
        
        DESELETC_KEY = [27],
        FINISH_DRAW_KEY = [13, 27],   // Enter, Esc
        DELETE_KEY = [8, 46],         // Return, Supr

        previousColor = '',
        defaultColor = '#000',
        selectedColor = '#d00d0d',
        
        previousMarker = '',
        defaultMarker = '', // Marker selected icon
        selectedMarker = '', // Marker previous icon
        sizeMarker = null; // Size for the markers (falsy for auto)    
    
    //- Private methods
    
    /**
     * Get the type of a shape based on his constructor
     * @method
     * @private
     * @param {google.maps.MVCObject} shape
     */
    function getShapeType(shape) {
        
        var type;
        
        if (shape.constructor === google.maps.Marker) {
            type = self.types.MARKER;
        } else if (shape.constructor === google.maps.Circle) {
            type = self.types.CIRCLE;
        } else if (shape.constructor === google.maps.Rectangle) {
            type = self.types.RECTANGLE;
        } else if (shape.constructor === google.maps.Polyline) {
            type = self.types.POLYLINE;
        } else if (shape.constructor === google.maps.Polygon) {
            type = self.types.POLYGON;
        } else {
            throw new Error('I can´t get the type of something that is not a shape!!!!');
        }
        
        return type;
    }
    
    function getMarkerIcon(url) {
        
        var icon = url;
        
        if (sizeMarker) {
            icon = new google.maps.MarkerImage(url, null, null, null, new google.maps.Size(sizeMarker.width, sizeMarker.height));
        }
        
        return icon;
    }
    
    function getPolyContainer(shape, path) {
        return null;
        //return shape.B ? shape.B.d[path || 0].J : null;
    }
    
    function getPolyVertexElement(shape, vertex, path) {
        var vertexParent = getPolyContainer(shape, path),
            node;
        
        node = vertexParent.querySelector('[data-vertex="' + vertex  + '"]');
        
        if (node === null) {
            node = vertexParent.childNodes[vertex];
        }
        
        return node.firstChild;
    }
    
    function updatePolyChildsIndex(shape, path) {
        var container = getPolyContainer(shape, path),
            i,
            childsLength;
        
        if (container) {
            
            childsLength = container.childNodes.length;
            
            for (i = 0; i < childsLength; i += 1) {
                container.childNodes[i].setAttribute('data-vertex', i);
            }
        }
    }
    
    function setMarkerIcon(icon) {
        self.manager.setOptions({
            markerOptions: {
                icon: getMarkerIcon(icon)
            }
        });
    }
    
    function setPolylineColor(color) {
        self.manager.setOptions({polylineOptions: {strokeColor: color, fillColor: color}});
        self.color = color;
    }
    
    function setCircleColor(color) {
        self.manager.setOptions({circleOptions: {strokeColor: color, fillColor: color}});
        self.color = color;
    }
    
    function setRectangleColor(color) {
        self.manager.setOptions({rectangleOptions: {strokeColor: color, fillColor: color}});
        self.color = color;
    }
    
    function setPolygonColor(color) {
        self.manager.setOptions({polygonOptions: {strokeColor: color, fillColor: color}});
        self.color = color;
    }
    
    function setNewPolyChildIndex(shape, vertex) {
        
        var container = getPolyContainer(shape, 0),
            newNode = container.lastChild,
            hasDataIndex = container.querySelectorAll('[data-vertex]').length > 0,
            i,
            childsLength = container.childNodes.length,
            node,
            index;
        
        for (i = 0; i < childsLength - 1; i += 1) {
            
            index = i;
            if (i >= vertex) {
                index = i + 1;
            }
            
            if (hasDataIndex) {
                node = container.querySelectorAll('[data-vertex="' + i + '"]');
                
                if (node.length >= 2) {
                    node = node[1].getAttribute('data-moved') ? node[0] : node[1];
                } else {
                    node = node[0];
                }
                
            } else {
                node = container.childNodes[i];
            }
            
            
            
            if (node) {
                node.setAttribute('data-vertex', index);
                node.setAttribute('data-moved', 'true');
            }
        }
        
        for (i = 0; i < childsLength; i += 1) {
            container.childNodes[i].removeAttribute('data-moved');
        }
        
        newNode.setAttribute('data-vertex', vertex);
    }
    
    function reindexPolyChilds(shape, vertex, oldPoint) {
        
        var container = getPolyContainer(shape, 0),
            i,
            childsLength = container.childNodes.length,
            node,
            index;
        
        for (i = 0; i < childsLength; i += 1) {
            
            index = i;
            if (i >= vertex) {
                index = i + 1;
            }
            
            node = container.querySelector('[data-vertex="' + index + '"]');
            
            if (node === null) {
                node = container.childNodes[i];
            }
            
            node.setAttribute('data-vertex', i);
        }
    }
    
    
    /**
     * Star the drawing mode in the map
     * @method
     * @private
     * @param {google.maps.drawing}
     */
    function draw(type, options) {
        
        // If we are drawing cancel and start again 
        if (self.drawing) {
            self.finish();
        }
        
        self.manager.setDrawingMode(type);
        
        if (options) {
            self.manager.setOptions(options);
        }
        
        
        self.drawing = true;
        self.type = type;
        
        self.trigger('drawing.start');
    }
    
    function selectVertex() {
        
        var element = getPolyVertexElement(self.shape, self.vertex, self.path);
        element.style.borderColor = selectedColor;
        
        //TODO: Move this to config or params or something
        element.style.borderWidth = '2px';
        element.style.marginTop = '-1px';
        element.style.marginLeft = '-1px';
    }

    function deselectVertex() {
        var element = getPolyVertexElement(self.shape, self.vertex, self.path);
        element.style.borderColor = defaultColor;
        
        //TODO: Move this to config or params or something
        element.style.borderWidth = '1px';
        element.style.marginTop = '0px';
        element.style.marginLeft = '0px';
    }
    
    function selectMarker(evt) {
        
        var pixels = self.map.latLngToPixels(self.shape.getPosition()),
            position,
            max,
            scale;
        
        pixels.y = pixels.y - (sizeMarker.height / 2);
        position = self.map.pixelsToLatLng(pixels);
        
        max = Math.max(sizeMarker.height, sizeMarker.width);

        scale = 10 + Math.round(max % 10);
        
        self.circle = new google.maps.Marker({
            map: self.innerMap,
            position: position,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: scale,
                strokeWeight: 2,
                strokeColor: selectedColor
            },
            clickable: false
        });
        
        // Update marker selection when zoomed in/out
        google.maps.event.addListenerOnce(self.innerMap, 'zoom_changed', function () {
            if (self.circle) {
                self.circle.setMap(null);
                selectMarker();
            }
        });
    }
    
    function deselectMarker(evt) {
        if (self.circle) {
            self.circle.setMap(null);
            self.circle = null;
        }
    }
    
    function selectCanvas() {
        self.shape.setOptions({
            strokeColor: selectedColor,
            fillColor: selectedColor
        });
    }
    
    function deselectCanvas() {
        self.shape.setOptions({
            strokeColor: self.shape.color || defaultColor,
            fillColor: self.shape.color || defaultColor
        });
    }
    
    function deselectShape(evt, shape) {
        
        var type = getShapeType(shape);
        
        if ((type === self.types.POLYGON || type === self.types.POLYLINE) && self.vertex !== null) {
            deselectVertex();
        } else if (type === self.types.MARKER) {
            deselectMarker();
        } else {
            deselectCanvas();
        }
        
        self.shape = null;
        self.vertex = null;
        self.path = null;
        self.edge = null;
        
        self.trigger('drawing.deselected');
    }
    
    function selectShape(evt, shape) {
        
        mapSelected();
        
        if (self.shape) {
            deselectShape({}, self.shape);
        }
        
        self.shape = evt.overlay || shape;
        self.type = getShapeType(shape);
        self.vertex = evt.vertex >= 0 ? evt.vertex : null; // Clik on a joint of a polything
        self.path = evt.path || 0; // Clik on both join or add of a polything
        self.edge = evt.edge || null; // Click on a add point of a polything
        
        if ((self.type === self.types.POLYGON || self.type === self.types.POLYLINE) && self.vertex !== null) {
            selectVertex();
        } else if (self.type === self.types.MARKER) {
            selectMarker();
        } else {
            selectCanvas();
        }
        
        self.trigger('drawing.selected', {shape: self.shape, vertex: self.vertex, path: self.path});
    }
    
    function initializeShape(shape, type) {
        var clickEvent = 'mousedown';
        
        if (self.drawing) {
            self.finish();
        }
        
        //TODO: Change this, try to be more specific and use click when posible
        if (type === self.types.MARKER) {
            clickEvent = 'mouseup';
            google.maps.event.addListener(shape, 'mousedown', function (evt) { deselectMarker(evt); });
            google.maps.event.addListener(shape, 'position_changed', function (evt) { self.trigger('drawing.move', [evt]); });
        }
        
        google.maps.event.addListener(shape, clickEvent, function (evt) {selectShape(evt, shape); });
        
        // If it is a polygon reorder the childNodes when a new one is created
        if (type === self.types.POLYGON || type === self.types.POLYLINE) {
            
            updatePolyChildsIndex(shape, 0);
            google.maps.event.addListener(shape.getPath(), 'insert_at', function (vertex) { window.setTimeout(function () { setNewPolyChildIndex(shape, vertex); }); });
            google.maps.event.addListener(shape.getPath(), 'remove_at', function (vertex, oldPoint) { reindexPolyChilds(shape, vertex, oldPoint); });
            
            google.maps.event.addListener(shape.getPath(), 'set_at', function (evt) {
                
                if (self.__moveTimer) {
                    window.clearTimeout(self.__moveTimer);
                }
                
                self.__moveTimer = window.setTimeout(function () {
                    self.trigger('drawing.move', [evt, shape]); 
                }, 200);
            });
            google.maps.event.addListener(shape.getPath(), 'insert_at', function (evt) { self.trigger('drawing.change', [evt, shape]); });
            google.maps.event.addListener(shape.getPath(), 'remove_at', function (evt) { self.trigger('drawing.change', [evt, shape]); });
        }
        
        if (self.editAll) {
            shape.setOptions({draggable: true, editable: true});
        }
    }
    
    /**
     * Event hanlder used when a new shape is finished
     * @method
     * @private
     * @param {Object} e Object with type(google.maps.OverlayType) and the overlay (depends on the type of the overlay)
     */
    function shapeCreated(e) {
        
        e.overlay.id = self.type + '-newshape' + self.counter;
        self.counter += 1;
        
        initializeShape(e.overlay, self.type);
        
        if (self.type === self.types.MARKER) {
            //google.maps.event.trigger(e.overlay, clickEvent);
            selectShape({}, e.overlay);
            
            // Restore icon to default
            setMarkerIcon(defaultMarker);
        } else {
            // Select the created shape
            selectShape({}, e.overlay);
            
            e.overlay.color = self.color;
            
            if (self.type === self.types.POLYLINE) {
                setPolylineColor(defaultColor);
            } else if (self.type === self.types.CIRCLE) {
                setCircleColor(defaultColor);
            } else if (self.type === self.types.RECTANGLE) {
                setRectangleColor(defaultColor);
            } else if (self.type === self.types.POLYGON) {
                setPolygonColor(defaultColor);
            }
        }
        
        self.trigger('drawing.created', e);
    }
    
    /**
     * Event handler for map keyup
     * @method
     * @private
     * @param {Event} e
     */
    function handleKeyUp(e) {
        
        var key = e.keyCode || e.which;
        
        if (mapas.utils.indexOf.call(FINISH_DRAW_KEY, key) >= 0 && self.focused && self.drawing) {
            self.finish();
        }
        
        if (mapas.utils.indexOf.call(DESELETC_KEY, key) >= 0 && self.shape) {
            deselectShape({}, self.shape);
        }
    }
    
    function handleKeyDown(e) {
        
        var key = e.keyCode || e.which;
        
        // DO always to avoid mistakes and loose all the work?
        if (mapas.utils.indexOf.call(DELETE_KEY, key) >= 0 && self.focused) { //&& self.focused) {
            self.remove();
            
            if (e.stopPropagation) {
                e.stopPropagation();
            }
            
            if (e.preventDefault) {
                e.preventDefault();
            }
            
            return false;
        }
    }
    
    
    function deleteSelection() {
        
        if (self.shape) {
            
            if (self.vertex !== null && self.shape.getPaths) {
                //TODO: self.shpae.getPaths <- throw an error
                self.shape.getPath().removeAt(self.vertex);
                self.trigger('drawing.change', self.shape.id);
            } else if (self.vertex !== null && !self.shape.getPaths) {
                self.shape.getPath().removeAt(self.vertex);
                self.trigger('drawing.change', self.shape.id);
            } else {
                self.shape.setMap(null);
                self.trigger('drawing.deleted', self.shape.id);
            }
            
            self.shape = null;
            self.vertex = null;
            self.shape = null;
            self.edge = null;
            
            if (self.circle) {
                self.circle.setMap(null);
                self.circle = null;
            }
        }
    }
    
    function mapSelected() {
        self.focused = true;
    }
    
    function mapDeselected() {
        self.focused = false;
    }
    
    function debugShape() {
        
        var type = getShapeType(self.shape),
            parent,
            i,
            longChilds,
            path,
            point,
            marker;
        
        
        if (self.debugMarkers) {
            for (i = 0; i < self.debugMarkers.length; i += 1) {
                self.debugMarkers[i].setMap(null);
            }
        }
        
        self.debugMarkers = [];
        
        if (type === self.types.POLYGON) {
            
            path = self.shape.getPath();
            parent = getPolyContainer(self.shape, 0);
            for (i = 0, longChilds = parent.childNodes.length; i < longChilds; i += 1) {
                point = path.getAt(i);
                
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(point.lat(), point.lng()),
                    map: self.map.innerMap,
                    title: 'Marker ' + i
                });
                
                self.debugMarkers.push(marker);
            }
        }
    }
    
    //- Public properties
    
    /**
     * Reference to mapas.Map where we ant to draw
     * @property {mapas.Map}
     * @public
     */
    self.map = null;
    
    /**
     * Reference to the google maps intance
     * @property {google.maps.Map}
     * @public
     */
    self.innerMap = null;
    
    /**
     * Reference to the drawing manager for the map
     * @property {google.maps.drawing.DrawingManager}
     * @public
     */
    self.manager = null;
    
    /**
     * True if mpa is in drawing mode
     * @property {Boolean}
     * @public
     */
    self.drawing = false;
    
    /**
     * Code for the different types of shapes you cand raw
     * @property {Object}
     * @public
     */
    self.types = google.maps.drawing.OverlayType;
    
    /**
     * Current type being used to draw when drawing mdoe is enabled
     * @property {google.maps.drawing.OverlayType}
     * @public
     */
    self.type = null;
    
    /**
     * Selected shape
     * @property {Object}
     * @public
     */
    self.shape = null;
    
    /**
     * Selected vertex
     * @property {Number}
     * @public
     */
    self.vertex = null;
    
    /**
     * Selected path
     * @property {Number}
     * @public
     */
    self.path = null;
    
    /**
     * If true then the map is selected
     * @property {Boolean}
     * @public
     */
    self.focused = false;
    
    /**
     * A Simpli counter of the number of shapes added
     * @property {Number}
     * @public
     */
    self.counter = 0;
    
    /**
     * Current drawing color
     * @property {String}
     * @public
     */
    self.color = '#000';
    
    self.editAll = true;
    
    //- Constructor
    
    /**
     * Constructor
     * @param {mapas.Map} map Instance of the map being used to draw
     * @param {Object} config JSON object with the tool configuration
     */
    function init(map, config) {
        
        var icon,
            polyOptions;
        
        // Apply event management mixin to this instance
        mapas.utils.EventsMixin.call(self);
        
        self.map = map;
        self.innerMap = map.innerMap;
        
        defaultColor = config.defaultColor || defaultColor;
        selectedColor = config.selectedColor || selectedColor;
        defaultMarker = config.defaultMarker || defaultMarker;
        selectedMarker = config.selectedMarker || selectedMarker;
        sizeMarker = config.sizeMarker || sizeMarker;
        self.editAll = config.avoidEditAll ? false : true;
        
        self.color = defaultColor;
        
        polyOptions = {draggable : true, editable: true, strokeColor: defaultColor, fillColor: defaultColor, geodesic: config.geodesic};
        
        icon = getMarkerIcon(defaultMarker);
        self.manager = new google.maps.drawing.DrawingManager({
            map: self.innerMap,
            drawingControl : false,
            markerOptions: {draggable : true, icon: icon},
            circleOptions: config.circleOptions || polyOptions,
            rectangleOptions: config.rectangleOptions || polyOptions,
            polylineOptions: config.polylineOptions || polyOptions,
            polygonOptions: config.polygonOptions || polyOptions
        });
        
        google.maps.event.addListener(self.manager, 'overlaycomplete', shapeCreated);
        
        // Make the map focusable
        self.map.node.setAttribute('tabindex', '-1');
        mapas.utils.on(self.map.node, 'focus', mapSelected);
        mapas.utils.on(self.map.node, 'blur', mapDeselected);
        
        mapas.utils.on(document.body, 'keyup', handleKeyUp);
        mapas.utils.on(document.body, 'keydown', handleKeyDown);
    }
    
    
    //- Public methods
    
    /**
     * Star point drawing mode
     * @method
     * @public
     */
    self.drawPoint = function (icon, options) {
        
        if (icon) {
            setMarkerIcon(icon);
        }
        
        draw(self.types.MARKER, options);
    };
    
    /**
     * Start line drawing mode
     * @method
     * @public
     */
    self.drawLine = function (color, options) {
        
        if (color) {
            setPolylineColor(color);
        }
        
        draw(self.types.POLYLINE, options);
    };
    
    /**
     * Start circle drawing mode
     * @method
     * @public
     */
    self.drawCircle = function (color, options) {
        
        if (color) {
            setCircleColor(color);
        }
        
        draw(self.types.CIRCLE, options);
    };
    
    /**
     * Start polygon drawing mode
     * @method
     * @public
     */
    self.drawPolygon = function (color, options) {
        
        if (color) {
            setPolygonColor(color);
        }
        
        draw(self.types.POLYGON, options);
    };
    
    /**
     * Start rectangle drawing mode
     * @method
     * @public
     */
    self.drawRectangle = function (color, options) {
        
        if (color) {
            setRectangleColor(color);
        }
        
        draw(self.types.RECTANGLE, options);
    };
    
    /**
     * Load a existing shape
     */
    self.loadShape = function (shape) {
        
        if (shape) {
            initializeShape(shape, getShapeType(shape));
        }
    };
    
    /**
     * Finish the current draw mode
     * @method
     * @public
     */
    self.finish = function () {
        
        // Draw empty so the manager finish the figure
        self.manager.setDrawingMode(null);
        
        // Set flag to false
        self.drawing = false;
        
        self.trigger('drawing.end');
    };
    
    self.remove = function () {
        
        deleteSelection();
    };
    
    self.debugShape = function () {
        debugShape();
    };
    
    init.apply(self, arguments);
};