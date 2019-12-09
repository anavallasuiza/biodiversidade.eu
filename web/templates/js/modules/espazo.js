/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, alert, confirm, console, espazo, CKEDITOR */

/**
 * Modulo espazo
 */
var espazo = (function () {

    'use strict';

    var self = {};

    function closeInfo() {
        if (self.info) {
            self.info.close();
        }
    }

    function showInfo(shape) {

        var data = {text: 'Lorem ipsum'},
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

        self.info = self.map.showInfo(
            data,
            shape,
            {
                infoBoxClearance: config.espazo.INFOBOX_CLEARANCE,
                pixelOffset: config.espazo.PIXEL_OFFSET,
                closeBox: config.espazo.CLOSE_BOX,
                title: ''
            }
        );
    }

    function initializeShapes(shape) {

        var type = self.shapesList.getType(shape),
            tipoPOI;

        if (type === self.shapesList.types.MARKER) {

            google.maps.event.addListener(shape, 'click', function (evt) { showInfo(shape);  });
        }
    }

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

            google.maps.event.addListener(self.kml, 'metadata_changed', resyncAvistamentos);
        }
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
                url: document.URL + '?phpcan_action=asociar-avistamentos-espazo',
                data: query
            }).done(function(data) {
              console.log(data);
            });
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
    self.kml = null;
    self.elements = {
        map: '.mapa',
        mapLabels: '#toggle-labels',
        infoWindow: '#infoWindow',
        kml: '#link-kml',
        avistamentos: '#avistamentos',
        avistamento: 'article.especie-avistamento',
        paxinacion: '.paxinacion-cliente',
        filtroEspecie: '#filtro-especie',
        toggleAvistamentos: '#toggle-avistamentos'
    };

    self.init = function () {

        var markers,
            polygons;

        // Create the map
		self.map = common.map.init('ameaza', document.querySelector(self.elements.map), config.espazo);

        // Map labels
        $(self.elements.mapLabels).on('change', function (e) { self.map.showLabels(e.currentTarget.checked); }).trigger('change');

        avistamento.common.infoboxMixin.apply(self);
        self.avistamentos.init(i18n.catalogo, self.map);

        common.map.mapDataMixin.apply(self);

        self.shapes.init({
            markerIcon: config.espazo.ICON_MARKER_OPACITY,
            markerSize: {width: 14, height: 14},
            shapeColor: config.espazo.COLOR_SHAPE,
            fillOpacity: config.espazo.FILL_OPACITY,
            strokeOpacity: config.espazo.STROKE_OPACITY
        });

        self.shapes.on('click', self.avistamentos.showInfo);

        // Shapes logic
        self.shapesList = new mapas.Shapes(self.map, {
            markerIcon: config.espazo.ICON_MARKER,
            markerSize: {width: 16, height: 26},
            shapeColor: config.espazo.COLOR_SHAPE
        });

        self.shapesList.on('shapes.new', function (shape) {
            initializeShapes(shape.object);
        });

        markers = $(self.elements.map).attr('data-markers');
        if (markers) {
            self.shapesList.loadShapes(JSON.parse(markers.replace(/\'/ig, '"')));
        }

        polygons = $(self.elements.map).attr('data-polygons');
        if (polygons) {
            self.shapesList.loadShapes(JSON.parse(polygons.replace(/\'/ig, '"')));
        }

        $(self.elements.avistamentos).find(self.elements.avistamento).each(loadAvistamentos);

        $(self.elements.avistamentos).on('tabShow', function () {
            $(self.elements.paxinacion + ' *[data-page="1"]').eq(0).trigger('click');
        });

        if ($(self.elements.kml).length) {
            loadKML();
        } else {
            self.shapesList.fitMap(14);

            resyncAvistamentos();
        }

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