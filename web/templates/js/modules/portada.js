/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, alert, confirm, CKEDITOR, console */

/**
 * Modulo avistamentos
 */
var portada = (function () {
    
    "use strict";
    
    var self = {};
    
    function enableMap(e) {
        
        var $this = $(e.currentTarget),
            ancho = $this.find(self.elements.portadaIntro).outerWidth();

        $this.css({
            'width': ancho + 'px',
            'right': $this.find(self.elements.portadaIntro).offset().left + 'px'
        });

        $this.addClass('collapsed');

        self.map.addElementToToolbar(document.querySelector(self.elements.toolbar), google.maps.ControlPosition.TOP_LEFT);
        $(self.elements.toolbar).fadeIn();
    }
    
    function showInfo(shape, evt) {
        
        var boxOptions,
            boxData,
            latLng,
            $article,
            info;
        
        boxOptions = {
			infoBoxClearance: new google.maps.Size(60, 60),
			pixelOffset: new google.maps.Size(-232, -30),
			closeBox: $('<i class="icon-remove"></i>')[0],
			title: null
		};
      
        if (shape.ameaza) {
            $article = $(self.elements.ameaza + '[data-id="' + shape.ameaza  + '"]');
        } else {
            $article = $(self.elements.avistamento + '[data-codigo="' + shape.avistamento  + '"]');
        }
        
        boxData = { text: $article[0].outerHTML};
        
        if (shape.type === 'marker') {
            boxData.lat = shape.points[0].latitude;
            boxData.lng = shape.points[0].longitude;
        } else {
            latLng = self.shapesList.getPolygonCenter(shape.object);
            boxData.lat = latLng.lat();
            boxData.lng = latLng.lng();
        }
        
        info = self.map.showInfo(boxData, null, boxOptions);
    }
    
    function fitToRandom(shapes) {
        
        var index,
            list = [],
            id,
            shape,
            latLng,
            offset;
        
        for (id in shapes) {
            if (shapes.hasOwnProperty(id)) {
                list.push(id);
            }
        }
        
        if (list.length) {
            index = Math.round(Math.random() * (list.length - 1));
            shape = shapes[list[index]];
            
            offset = 0.25 * (parseInt(Math.random() * 10) % 2 === 0 ? -1 : 1);
            
            latLng = new google.maps.LatLng(shape.points[0].latitude, shape.points[0].longitude - offset);
            
            self.map.fit([latLng], 9);
        }
    }
    
    function getCentroidPoints(lat, lng, size) {
        
        var utm,
            sw,
            latLngSW,
            ne,
            latLngNE,
            points = [];
        
        
        utm = mapas.utils.converter.latLngToUTM(lat, lng);
			
        sw = {
            easting: Math.floor(utm.easting / size) * size,
            northing: Math.floor(utm.northing / size) * size,
            zone: utm.zone,
            hemisphere: utm.hemisphere
        };
        
        latLngSW = mapas.utils.converter.utmToLatLng(sw);
		
		ne = {
			easting: sw.easting + size,
			northing: sw.northing + size,
			zone: sw.zone,
			hemisphere: sw.hemisphere
		};
        
        latLngNE = mapas.utils.converter.utmToLatLng(ne);
        
        points.push({latitude: latLngSW.lat, longitude: latLngSW.lng});
        points.push({latitude: latLngNE.lat, longitude: latLngSW.lng});
        points.push({latitude: latLngNE.lat, longitude: latLngNE.lng});
        points.push({latitude: latLngSW.lat, longitude: latLngNE.lng});
        
        return points;
    }
    
    self.mapa = null;
    
    self.elements = {
        map: '.mapa-portada',
        content: '.content-portada-intro',
        portadaIntro: '.portada-intro',
        toolbar: '#mapa-toolbar',
        ameaza: 'article.ameaza',
        avistamento: 'article.especie-avistamento'
    };
    
    self.init = function () {
        
        var shapes = [],
            random;
        
        // Map logic
		self.map = common.map.init('portada', document.querySelector(self.elements.map), config.portada);
        
        $(self.elements.content).one('click', enableMap);
        
        // Shapes logic
        self.shapesList = new mapas.Shapes(self.map, {
            markerIcon: config.portada.ICON_MARKER,
            markerSize: {width: 14, height: 13},
            shapeColor: config.portada.COLOR_SHAPE
        });
        
        $(self.elements.ameaza).each(function (i, item) {
            var $this = $(item),
                markers = $this.attr('data-markers'),
                polygons = $this.attr('data-polygons'),
                polylines = $this.attr('data-polylines'),
                shapes,
                j,
                longShapes;
            
            if (markers) {
                shapes = JSON.parse(markers.replace(/\'/ig, '"'));
                self.shapesList.loadShapes(shapes);
            }
            
            if (polygons) {
                shapes = JSON.parse(polygons.replace(/\'/ig, '"'));

                self.shapesList.loadShapes(shapes);
            }
            
            if (polylines) {
                shapes = JSON.parse(polylines.replace(/\'/ig, '"'));
                self.shapesList.loadShapes(shapes);
            }
            
            self.shapesList.on('shapes.click', showInfo);
        });
        
        $(self.elements.avistamento).each(function (i, item) {
            var $this = $(item),
                points = $this.attr('data-puntos'),
                centroides1 = $this.attr('data-centroides1'),
                centroides10 = $this.attr('data-centroides10'),
                shapes,
                shape,
                data,
                j,
                longShapes;
            
            if (points) {
                shapes = JSON.parse(points.replace(/\'/ig, '"'));
                
                for (j = 0, longShapes = shapes.length; j < longShapes; j += 1) {
                    
                    shape = {
                        id: 'avistamento-punto-' + shapes[j].id,
                        code: shapes[j].id,
                        avistamento: $this.attr('data-codigo'),
                        type: 'marker',
                        points: [
                            {latitude: shapes[j].latitude, longitude: shapes[j].lonxitude}
                        ]
                    };
                    
                    self.shapesList.loadShape(shape);
                }
                
            }
            
            if (centroides1) {
                shapes = JSON.parse(centroides1.replace(/\'/ig, '"'));
                
                for (j = 0, longShapes = shapes.length; j < longShapes; j += 1) {
                    shape = {
                        id: 'avistamento-centroide1-' + shapes[j].id,
                        code: shapes[j].id,
                        avistamento: $this.attr('data-codigo'),
                        type: 'polygon',
                        geodesic: true,
                        points: []
                    };
                    
                    shape.points = getCentroidPoints(shapes[j].latitude, shapes[j].lonxitude, 1000);
                    
                    self.shapesList.loadShape(shape);
                }
            }
            
            if (centroides10) {
                shapes = JSON.parse(centroides10.replace(/\'/ig, '"'));
                
                for (j = 0, longShapes = shapes.length; j < longShapes; j += 1) {
                    shape = {
                        id: 'avistamento-centroide10-' + shapes[j].id,
                        code: shapes[j].id,
                        avistamento: $this.attr('data-codigo'),
                        type: 'polygon',
                        geodesic: true,
                        points: []
                    };
                    
                    shape.points = getCentroidPoints(shapes[j].latitude, shapes[j].lonxitude, 10000);
                    
                    self.shapesList.loadShape(shape);
                }
            }
            
            self.shapesList.on('shapes.click', showInfo);
        });
        
        fitToRandom(self.shapesList.shapes);
    };
    
    $(document).ready(self.init);
	return self;
    
}());