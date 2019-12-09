/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, alert, confirm, console, CKEDITOR, Raphael, avistamento */

/**
 * Modulo rota
 */
var rota = (function () {

    'use strict';

    var self = {};

    function closeInfo() {
        if (self.info) {
            self.info.close();
        }
    }

    function showInfo(shape) {

        var data = {text: ''},
            info,
            latLng,
            $text,
            shapeData;

        closeInfo();

        shapeData = self.shapesList.shapes[shape.id];

        $text = $(self.elements.infoWindow).clone(true).removeAttr('id').removeClass('hidden');
        $text.attr('data-id', shape.id);
        $text.find('h1').html(shapeData.nome);
        $text.find('p').html(shapeData.texto);
        data.text = $text[0].outerHTML;

        if (!shape.getPosition) {
            latLng = self.shapesList.getPolygonCenter(shape);

            data.lat = latLng.lat();
            data.lng = latLng.lng();
        }

        self.info = self.map.showInfo(data, shape, {
            infoBoxClearance: config.rota.INFOBOX_CLEARANCE,
            pixelOffset: config.rota.PIXEL_OFFSET,
            closeBox: config.rota.CLOSE_BOX,
            title: ''
        });
    }

    function initializeShapes(shape) {

        var type = self.shapesList.getType(shape),
            tipoPOI;

        if (type === self.shapesList.types.MARKER) {
            google.maps.event.addListener(shape, 'click', function (evt) { showInfo(shape);  });
        }
    }

    function clickLabel(e) {
        e.preventDefault();

        var $this = $(e.currentTarget);

        $('#' + $this.attr('for')).prop('checked', true);
    }

    function drawChart() {

        var points,
            dataY,
            dataX,
            i,
            data,
            options,
            values = [];

        points = $(self.elements.elevation).attr('data-points');

        if (points) {

            points = JSON.parse(points.replace(/\'/ig, '"'));

            if (points.length) {
                dataY = [];
                for (i = 0; i < points.length; i += 1) {
                    dataY.push([i, parseInt(points[i].altitude, 10)]);
                    values.push(parseInt(points[i].altitude, 10));
                }


                // Create and populate the data table.
                data = google.visualization.arrayToDataTable(dataY);
                options = {
                    colors: ['#DC8607'],
                    curveType: "function",
                    width: 280,
                    height: 80,
                    chartArea: {
                        top: '0',
                        left: '0',
                        height: 80,
                        width: 260
                    },

                    axisFontSize : 0,

                    legend: {position: 'none'},

                    hAxis: {
                        gridlines: {
                            color: '#ffffff',
                            count: 0
                        },

                        baselineColor: '#FFFFFF',
                        textColor: '#ffffff',
                        textPosition: 'none'
                    },

                    vAxis: {

                        gridlines : {
                            color: '#ffffff',
                            count: 0
                        },

                        baselineColor: '#FFFFFF',
                        textColor: '#ffffff',
                        textPosition: 'none'
                    }
                };

                // Create and draw the visualization.
                self.chart = new google.visualization.LineChart(document.querySelector(self.elements.elevation + ' .chart'));
                self.chart.draw(data, options);
            } else {
                $(self.elements.elevation).hide();
            }
        } else {
            $(self.elements.elevation).hide();
        }
    }

    function loadKML() {

        var $kml = $(self.elements.kml),
            url = $kml.attr('href');

        if (!url) {
            return;
        }

        self.kml = new google.maps.KmlLayer(url, {
            suppressInfoWindows: false,
            clickable: true,
            preserveViewport: false
        });

        self.kml.setMap(self.map.innerMap);

        /*$.ajax('http://localhost/biodiversidade/uploads/espazos/kml/0e5/54450f2872-543c5c825c-terras-do-mino.kml').done(function(xml){
            var kmlBoxer = new KmlBoxer();

            var query = kmlBoxer.boxesQuery(xml, config.rota.PATH_BBOX_SIZE);

            $.ajax({
                type: 'POST',
                url: document.URL + '?phpcan_action=asociar-avistamentos-rota',
                data: query
            }).done(function(data) {
              console.log(data);
            });
        });*/

        google.maps.event.addListener(self.kml, 'metadata_changed', function() {
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
                    url: document.URL + '?phpcan_action=asociar-avistamentos-rota',
                    data: query
                }).done(function(data) {
                  console.log(data);
                });
            }, 2000);
        });
    }

    function resyncAvistamentos() {
        setTimeout(function() {
            var line = self.shapesList.getByType(self.shapesList.types.POLYLINE);

            if (line.length > 0) {
                var $form = $(self.elements.form),
                    path = line[0].object,
                    boxer = new RouteBoxer(),
                    distance = config.rota.PATH_BBOX_SIZE, // in km
                    boxes,
                    bounds,
                    ne,
                    sw,
                    query = '';

                console.log(path);

                boxes = boxer.box(path, distance);

                for (var i = 0; i < boxes.length; i++) {
                    bounds = boxes[i];
                    ne = bounds.getNorthEast();
                    sw = bounds.getSouthWest();

                    if (i > 0) {
                        query += '&';
                    }

                    query += 'boxes[' + i + '][ne][lat]=' + ne.lat() +
                    '&boxes[' + i + '][ne][lng]=' + ne.lng() +
                    '&boxes[' + i + '][sw][lat]=' + sw.lat() +
                    '&boxes[' + i + '][sw][lng]=' + sw.lng();
                }

                $.ajax({
                    type: 'POST',
                    url: document.URL + '?phpcan_action=asociar-avistamentos-rota',
                    data: query
                }).done(function(data) {
                  console.log(data);
                });
            }
        }, 2000);
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

    function filtrarEspecie(e) {
        self.filter($(e.currentTarget).val());
    }

    function toggleAvistamentos(e) {
        var $this = $(e.currentTarget),
            $select = $(self.elements.filtroEspecie);

        if ($this.is(':checked')) {
            $select.select2('enable').trigger('change');
        } else {
            $select.select2('disable');
            self.shapes.clear();
        }
    }

    self.map = null;

    self.paper = null;

    self.chart = null;

    self.kml = null;

    self.elements = {
        map: '.mapa',
        mapLabels: '#toggle-labels',
        infoWindow: '#infoWindow',
        rating: '.rating-box',
        ratingLabel: '.rating-box label',
        elevation: '#elevation',
        toolbarBottom: '#mapa-toolbar-bottom',
        kml: '#link-kml',
        avistamentos: '#avistamentos',
        avistamento: 'article.especie-avistamento',
        especies: 'ul.rota-especies',
        paxinacion: '.paxinacion-cliente',
        tabEspecies: '#especies',
        filtroEspecie: '#filtro-especie',
        toggleAvistamentos: '#toggle-avistamentos'
    };

    self.init = function () {

        var markers,
            polylines;

        // Create the map
		self.map = common.map.init('proxecto', document.querySelector(self.elements.map), config.rota);

        avistamento.common.infoboxMixin.apply(self);
        self.avistamentos.init(i18n.catalogo, self.map);

        common.map.mapDataMixin.apply(self);

        self.shapes.init({
            markerIcon: config.rota.ICON_MARKER_OPACITY,
            markerSize: {width: 14, height: 14},
            shapeColor: config.rota.COLOR_SHAPE,
            fillOpacity: config.rota.FILL_OPACITY,
            strokeOpacity: config.rota.STROKE_OPACITY
        });

        self.shapes.on('click', self.avistamentos.showInfo);

        // Shapes logic
        self.shapesList = new mapas.Shapes(self.map, {
            markerIcon: config.rota.ICON_MARKER_OPACITY,
            markerSize: {width: 16, height: 26},
            shapeColor: config.rota.COLOR_SHAPE,
            fillOpacity: 0.2,
            strokeOpacity: 0.4
        });

        self.shapesList.on('shapes.new', function (shape) {
            initializeShapes(shape.object);
        });

        markers = $(self.elements.map).attr('data-markers');
        if (markers) {
            console.log(markers);
            self.shapesList.loadShapes(JSON.parse(markers.replace(/\'/ig, '"')));
        }

        polylines = $(self.elements.map).attr('data-polylines');
        if (polylines) {
            self.shapesList.defaults({shapeColor: config.rota.COLOR_SHAPE_SELECTED});

            console.log(JSON.parse(polylines.replace(/\'/ig, '"')));

            self.shapesList.loadShapes(JSON.parse(polylines.replace(/\'/ig, '"')));

            self.shapesList.defaults({shapeColor: config.rota.COLOR_SHAPE});
        }

        if ($(self.elements.kml).length) {
            loadKML();
        } else {
            self.shapesList.fitMap(14);

            resyncAvistamentos();
        }

        $(self.elements.ratingLabel).on('click', clickLabel);

        // Load charts
        if (google && google.setOnLoadCallback) {
            google.setOnLoadCallback(drawChart);
        }

        $(self.elements.avistamentos).find(self.elements.avistamento).each(loadAvistamentos);

        $(self.elements.avistamentos).on('tabShow', function () {
            $(self.elements.paxinacion + ' *[data-page="1"]').eq(0).trigger('click');
        });

        $(self.elements.especies).masonry({});

        $(self.elements.filtroEspecie).on('change', filtrarEspecie);
        $(self.elements.toggleAvistamentos).on('change', toggleAvistamentos).trigger('change');
    };

    self.filter = function (especie) {
        self.shapes.clear();
        $(self.elements.avistamentos).find(self.elements.avistamento + (especie ? '[data-especie="' + especie + '"]' : '')).each(loadAvistamentos);
    };


    // Load module on ready and return public interface
	$(document).ready(self.init);
	return self;

}());