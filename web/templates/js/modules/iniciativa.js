/*jslint browser:true */
/*global google, $, mapas, config, common, console */

/**
 * Base module
 */
var iniciativa = (function () {
	
	'use strict';
    
    var self = {};
    
    
    self.map = null;
    self.kml = null;
    self.elements = {
        map: '.mapa',
        toolbarTop: '#mapa-toolbar-top',
        mapLabels: '#toggle-labels',
        iniciativa: 'article.ameaza',
        kml: '#link-kml',
        avistamentos: '#avistamentos',
        avistamento: 'article.especie-avistamento'
    };
    
    self.init = function () {
        
        var markers,
            polygons,
            polylines,
            showMap = true;
        
        // Create the map
		self.map = common.map.init('iniciativas', document.querySelector(self.elements.map), config.iniciativa);
        
        // Map labels
        $(self.elements.mapLabels).on('change', function (e) { self.map.showLabels(e.currentTarget.checked); }).trigger('change');

        avistamento.common.infoboxMixin.apply(self);
        self.avistamentos.init(i18n.catalogo, self.map);
            
        common.map.mapDataMixin.apply(self);
        
        self.shapes.init({
            markerIcon: config.iniciativa.ICON_MARKER_OPACITY,
            markerSize: {width: 14, height: 14},
            shapeColor: config.iniciativa.COLOR_SHAPE,
            fillOpacity: config.iniciativa.FILL_OPACITY,
            strokeOpacity: config.iniciativa.STROKE_OPACITY
        });
        
        self.shapes.on('click', self.avistamentos.showInfo);
        
        // Shapes logic
        self.shapesList = new mapas.Shapes(self.map, {
            markerIcon: config.iniciativa.ICON_MARKER,
            markerSize: {width: 14, height: 13},
            shapeColor: config.iniciativa.COLOR_SHAPE
        });
        
        markers = $(self.elements.iniciativa).attr('data-markers');
        if (markers) {
            self.shapesList.loadShapes(JSON.parse(markers.replace(/\'/ig, '"')));
        }
        
        polygons = $(self.elements.iniciativa).attr('data-polygons');
        if (polygons) {
            self.shapesList.loadShapes(JSON.parse(polygons.replace(/\'/ig, '"')));
        }
            
        polylines = $(self.elements.iniciativa).attr('data-polylines');
        if (polylines) {
            self.shapesList.loadShapes(JSON.parse(polylines.replace(/\'/ig, '"')));
        }

        if (JSON.parse(markers.replace(/\'/ig, '"')).length === 0 && 
            JSON.parse(polygons.replace(/\'/ig, '"')).length === 0 && 
            JSON.parse(polylines.replace(/\'/ig, '"')).length === 0)
        {
            showMap = false;
        }

        if (!showMap) {
            $('.content-mapa').hide();
        }
        
        if ($(self.elements.kml).length) {
            loadKML();
        } else {
            self.shapesList.fitMap(14);

            if (showMap) {
                resyncAvistamentos();
            }
        }

        if (showMap) {
            setTimeout(function() {
                var ne = self.map.innerMap.getBounds().getNorthEast(),
                    sw = self.map.innerMap.getBounds().getSouthWest(),
                    query = '';

                query = 'points[0][lat]=' + ne.lat() +
                    '&points[0][lng]=' + sw.lng() +
                    '&points[1][lat]=' + sw.lat() +
                    '&points[1][lng]=' + sw.lng() +
                    '&points[2][lat]=' + sw.lat() +
                    '&points[2][lng]=' + ne.lng() +
                    '&points[3][lat]=' + ne.lat() +
                    '&points[3][lng]=' + ne.lng();

                $.ajax({
                    url: config.catalogo.URL_AVISTAMENTOS_PUNTOS_AREA,
                    type: 'get',
                    dataType: 'html',
                    data: query,
                    cache: false,
                    success: function (data, status, xhr) {
                        $(self.elements.avistamentos).html(data);

                        $(self.elements.avistamentos).find(self.elements.avistamento).each(loadAvistamentos);
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            }, 1500);
        }
    };

    function loadKML() {
        
        var $kml = $(self.elements.kml),
            url = $kml.attr('href');
        
        if (url) {
            self.kml = new google.maps.KmlLayer(url, {
                suppressInfoWindows: false,
                clickable: true,
                preserveViewport: false
            });
            
            self.kml.setMap(self.map.innerMap);

            resyncAvistamentos();
        }
    }

    function loadAvistamentos(i, item) {

        var $this = $(item),
            codigo = $this.attr('data-codigo'),
            codigoShape,
            points = $this.attr('data-puntos'),
            centroides1 = $this.attr('data-centroides1'),
            centroides10 = $this.attr('data-centroides10'),
            shapes,
            shape,
            data,
            j,
            longShapes,
            corner,
            cornerCode,
            especie;
        
        data = [];
        
        if (points) {
            data.push({
                type: 'marker',
                items: points
            });
        }
        
        if (centroides1) {
            data.push({
                type: 'polygon',
                items: centroides1,
                size: 1000
            });
        }
        
        if (centroides10) {
            data.push({
                type: 'polygon',
                items: centroides10,
                size: 10000
            });
        }
        
        especie = $this.attr('data-especie');
        
        shapes = self.shapes.load(data, {avistamento: codigo, especie: especie});
    }

    function resyncAvistamentos() {
        setTimeout(function() {
            var ne = self.map.innerMap.getBounds().getNorthEast(),
                    sw = self.map.innerMap.getBounds().getSouthWest(),
                    query = '';

                query = 'points[0][lat]=' + ne.lat() +
                    '&points[0][lng]=' + sw.lng() +
                    '&points[1][lat]=' + sw.lat() +
                    '&points[1][lng]=' + sw.lng() +
                    '&points[2][lat]=' + sw.lat() +
                    '&points[2][lng]=' + ne.lng() +
                    '&points[3][lat]=' + ne.lat() +
                    '&points[3][lng]=' + ne.lng();

                $.ajax({
                    type: 'POST',
                    url: document.URL + '?phpcan_action=asociar-avistamentos-iniciativa',
                    data: query
                }).done(function(data) {
                  console.log(data);
                });
        }, 2000);
    }

    $(document).ready(self.init);
	return self;
	
}());