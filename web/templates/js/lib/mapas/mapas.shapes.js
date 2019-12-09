/*jslint browser:true */
/*global google, $, mapas, indexOf, Blob, BlobBuilder, alert, confirm, console */

/**
 * @classDescription Class representing a collection of shapes for a given map
 * @author kieleras
 * @version 0.1
 */
mapas.Shapes = function () {
    
    'use strict';

    //- Private properties
    
    /**
     * Reference to the instance of the class
     * @property {mapas.Map}
     * @private
     */
    var self = this,
        markerIcon = '',
        markerSize = null,
        shapeColor = '#000',
        fillOpacity = 0.4,
        strokeOpacity = 1;
    
    
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
    
    function createShapeObject(shape) {
        
        var object = {},
            icon,
            point,
            path = [],
            i,
            longPoints;
        
        if (shape.type === self.types.MARKER) {
            
            point = shape.points[0];
            
            icon = new google.maps.MarkerImage((shape.icon || markerIcon), null, null, null,
                markerSize ? new google.maps.Size(markerSize.width, markerSize.height) : null);
            
            object = new google.maps.Marker({
                map: self.innerMap,
                position: new google.maps.LatLng(point.latitude, point.longitude),
                icon: icon
            });
            
        } else if (shape.type === self.types.POLYLINE) {
            
            for (i = 0, longPoints = shape.points.length; i < longPoints; i += 1) {
                point = shape.points[i];
                path.push(new google.maps.LatLng(point.latitude, point.longitude));
            }
            
            object = new google.maps.Polyline({
                map: self.innerMap,
                fillColor: shape.color || shapeColor,
                strokeColor: shape.color || shapeColor,
                path: path,
                geodesic: shape.geodesic
            });
            
            object.color = shape.color;
            
        } else if (shape.type === self.types.POLYGON) {
            
            path.push([]);
            
            for (i = 0, longPoints = shape.points.length; i < longPoints; i += 1) {
                point = shape.points[i];
                path[0].push(new google.maps.LatLng(point.latitude, point.longitude));
            }
            
            object = new google.maps.Polygon({
                map: self.innerMap,
                fillColor: shape.color || shapeColor,
                strokeColor: shape.color || shapeColor,
                //path: path,
                paths: path,
                geodesic: shape.geodesic,
                fillOpacity: shape.fillOpacity || fillOpacity,
                strokeOpacity: shape.strokeOpacity || strokeOpacity
            });
            
            object.color = shape.color;
        }
        
        object.id = shape.id;
        
        return object;
    }
    
    function createShape(object) {
        
        var shape,
            type = getShapeType(object),
            latLng,
            path,
            i,
            longPath;
        
        shape = {
            id: object.id,
            code: '',
            type: type,
            text: '',
            position: []
        };
        
        if (type === self.types.MARKER) {
            latLng = object.getPosition();
            shape.position.push({latitude: latLng.lat(), longitude: latLng.lng()});
        } else if (type === self.types.POLYGON || type === self.types.POLYLINE) {
            path = object.getPath();
            for (i = 0, longPath = path.length; i < longPath; i += 1) {
                latLng = path.getAt(i);
                shape.position.push({latitude: latLng.lat(), longitude: latLng.lng()});
            }
        }
        
        return shape;
    }
    
    function getPointData(shape) {
        
        var object = shape.object,
            type = getShapeType(object),
            points = [],
            latLng,
            path,
            i,
            longPath;
        
        if (type === self.types.MARKER) {
            
            latLng = object.getPosition();
            points.push({latitude: latLng.lat(), longitude: latLng.lng()});
                         
        } else if (type === self.types.POLYLINE || type === self.types.POLYGON) {
            
            path = object.getPath();
            for (i = 0, longPath = path.length; i < longPath; i += 1) {
                latLng = path.getAt(i);
                points.push({latitude: latLng.lat(), longitude: latLng.lng()});
            }
        }
        
        return points;
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
     * Referenco to the list of shapes
     * @property {Array}
     * @public
     */
    self.shapes = {};
    
    /**
     * Code for the different types of shapes you cand raw
     * @property {Object}
     * @public
     */
    self.types = google.maps.drawing.OverlayType;
    
    /**
     * List of all the latlng points showed in the map
     * @property {Array}
     * @public
     */
    self.points = [];
    
    
    //- Constructor
    
    /**
     * Constructor
     * @method
     * @private
     * @param {mapas.Map} map Instance of the map being used to draw
     * @param {Object} config JSON object with the tool configuration
     */
    function init(map, config) {
        
        // Apply event management mixin to this instance
        mapas.utils.EventsMixin.call(self);
        
        self.map = map;
        self.innerMap = map.innerMap;
        
        markerIcon = config.markerIcon || null;
        markerSize = config.markerSize || null;
        shapeColor = config.shapeColor || '#000';
        fillOpacity = config.fillOpacity || 0.4;
        strokeOpacity = config.strokeOpacity || 1;
    }
    
    /**
     * Change or return de faults for the shapes
     * @method
     * @public
     * @param {Object} config Key/Values object with the new config
     */
    self.defaults = function (config) {
        
        if (config) {
            markerIcon = config.markerIcon || markerIcon;
            markerSize = config.markerSize || markerSize;
            shapeColor = config.shapeColor || shapeColor;
            fillOpacity = config.fillOpacity || fillOpacity;
            strokeOpacity = config.strokeOpacity || strokeOpacity;
        }
        
        return {
            'markerIcon': markerIcon,
            'markerSize': markerSize,
            'shapeColor': shapeColor,
            'fillOpacity': fillOpacity,
            'strokeOpacity': strokeOpacity
        };
    }
    
    //- Public methods
    
    /**
     * Load a shape into the map
     * @method
     * @public
     * @param {Object} shape
     */
    self.loadShape = function (shape, object) {
        shape.map = self.map;
        shape.object = object || createShapeObject(shape);
        self.shapes[shape.id] = shape;
        
        google.maps.event.addListener(shape.object, 'click', function (evt) { self.trigger('shapes.click', [shape, evt]); });
        
        self.trigger('shapes.new', shape);
    };
    
    /**
     * Add a list of shapes to the map
     * @method
     * @public
     * @param {Array} shapes
     */
    self.loadShapes = function (shapes) {
        
        var i, longShapes;
        
        for (i = 0, longShapes = shapes.length; i < longShapes; i += 1) {
            self.loadShape(shapes[i]);
        }
    };
    
    self.loadShapeObject = function (object) {
        
        var shape = createShape(object);
        self.loadShape(shape, object);
    };
    
    /**
     * Update the poits for the current shapes
     * @method
     * @public
     */
    self.updatePoints = function () {
        
        var id,
            shape;
        
        for (id in self.shapes) {
            if (self.shapes.hasOwnProperty(id)) {
                shape = self.shapes[id];
                
                shape.points = getPointData(shape);
            }
        }
        
    };
    
    self.fitMap = function (zoom) {
        var id,
            shape,
            type,
            points,
            bounds = [],
            j,
            longPoints,
            point;
        
        for (id in self.shapes) {
            if (self.shapes.hasOwnProperty(id)) {
                shape = self.shapes[id];
                points = getPointData(shape);
                
                for (j = 0, longPoints = points.length; j < longPoints; j += 1) {
                    point = points[j];
                    bounds.push(new google.maps.LatLng(point.latitude, point.longitude));
                }
            }
        }
        
        if (bounds.length) {
            self.map.fit(bounds, zoom);
        }
    };
    
    self.getPolygonCenter = function (shape) {

        var bounds = new google.maps.LatLngBounds(),
            path,
            i;
    
        path = shape.getPath();
        
        for (i = 0; i < path.length; i += 1) {
            bounds.extend(new google.maps.LatLng(path.getAt(i).lat(), path.getAt(i).lng()));
        }
    
        return bounds.getCenter();
    };
    
    self.remove = function (id) {
        
        var shape = self.shapes[id];
        
        if (shape.object) {
            shape.object.setMap(null);
        }
        
        delete self.shapes[id];
    };
    
    self.clear = function () {
        
        var id, shape;
        
        for (id in self.shapes) {
            if (self.shapes.hasOwnProperty(id)) {
                self.remove(id);
            }
        }
    };
    
    self.getType = function (shape) {
        return getShapeType(shape);
    };
    
    self.getByType = function (type) {
        
        var result = [],
            id,
            shape;
        
        for (id in self.shapes) {
            if (self.shapes.hasOwnProperty(id)) {
                shape = self.shapes[id];
                
                if (shape.type === type) {
                    result.push(shape);
                }
            }
        }
        
        return result;
    };
    
    /**
     *
     * @return Distance in meters 
     */
    self.getLength = function (shape) {
        
        if (!google.maps.geometry) {
            throw new Error('Geometry librery not loaded!!!');
        }
        
        return google.maps.geometry.spherical.computeLength(shape.getPath());
    };
    
    
    /**
     * Get the elevation for the given path
     * @method
     * @param {Array} path Array of google.maps.LatLng
     * @param {Number} samples Number of samples for the path. Equals to the number os points for the path if falsy.
     * @param {Function} callback Method to invoke when the elevation service got a response
     */
    self.getPathElevation = function (path, samples, callback) {
        
        var elevator = new google.maps.ElevationService(),
            request,
            list = [],
            i;
        
        samples = samples || path.length;
        
        for (i = 0; i < path.length; i += 1) {
            list.push(path.getAt(i));
        }
        
        request = {
            'path': list,
            'samples': samples
        };
        
        elevator.getElevationAlongPath(request, callback);
    };
    
    init.apply(self, arguments);
};