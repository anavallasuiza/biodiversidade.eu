/*jslint browser:true */
/*global google, $, mapas, config, common, console */

/**
 * Base module
 */
var ameazas = (function () {
	
	'use strict';
    
    var self = {};
    
    function cambioTab(e) {

        var $self = $(e.currentTarget);
        
		$self.find(self.elements.listado + ' ' + self.elements.ameaza).each(function (i, item) {
            
			var $this = $(item),
                id = $this.data('id'),
                dataPuntos = $this.data('puntos'),
                $article = $this.clone(),
                texto,
                puntos,
                shapes,
                polygon,
                j;

			$article.find('span.estado').remove();
			$article.find('.especies').remove();

			texto = '<div class="info-mapa">' +
				$article[0].outerHTML +
				'<div>' + $article.find('h1 a').clone().addClass('btn-link').html("<?php __e('Ver más'); ?>")[0].outerHTML + '</div></div>';
		});
    }
    
    function updatePageHeight(page) {
        var $content = $('.item-listaxe[data-page="' + page  + '"]');
        
        $('.tab-item:visible').find('> .content').animate({
            'height': $content.outerHeight(true) + 'px',
            'overflow': 'hidden'
        });
    }
    
    function cambioPaxina(e) {
        
        var $this = $(e.currentTarget || e),
            $paxinacion = $this.parents('.paxinacion').eq(0),
            $tab = $this.parents('.tab-item').eq(0),
            $items = $tab.find('.item-listaxe'),
            $current = $tab.find('.item-listaxe:visible'),
            currentPage = $current.data('page'),
            gotoPage = $this.data('page'),
            page,
            $newPage;

        if ($this.hasClass('disabled')) {
            return false;
        }

        if (gotoPage === 'prev') {
            page = currentPage - 1;
        } else if (gotoPage === 'next') {
            page = currentPage + 1;
        } else {
            page = gotoPage;
        }

        page = page <= 0 ? 1 : page;
        page = page > $items.length ? $items.length : page;

        if (page === currentPage) {
            return false;
        }

        $newPage = $items.filter('[data-page="' + page + '"]');

        if (!$newPage) {
            return false;
        }

        $current.fadeOut();
        $newPage.fadeIn();

        updatePageHeight(page);

        $paxinacion.find('li a.disabled').removeClass('disabled');

        $paxinacion.find('li a[data-page="' + page + '"]').addClass('disabled');

        if (page === 1) {
            $paxinacion.find('li a[data-page="prev"]').addClass('disabled');
        } else if (page === $items.length) {
            $paxinacion.find('li a[data-page="next"]').addClass('disabled');
        }

        return false;
    }
    
    function showInfo(shape, evt) {
        
        var boxOptions,
            boxData,
            latLng,
            $ameaza,
            info;
        
        boxOptions = {
			infoBoxClearance: new google.maps.Size(60, 60),
			pixelOffset: new google.maps.Size(-157, -30),
			closeBox: $('<i class="icon-remove"></i>')[0],
			title: null
		};
        
        $ameaza = $(self.elements.ameaza + '[data-id="' + shape.ameaza  + '"]');
        
        boxData = { text: $ameaza[0].outerHTML};
        
        if (shape.type === 'marker') {
            boxData.lat = shape.points[0].latitude;
            boxData.lng = shape.points[0].longitude;
        } else {
            latLng = self.shapesList.getPolygonCenter(shape.object);
            boxData.lat = latLng.lat();
            boxData.lng = latLng.lng();
        }
        
        info = self.mapa.showInfo(boxData, null, boxOptions);
    }
    
    function loadMapData(i, item) {
        
        var $this = $(item),
            markers = $this.attr('data-markers'),
            polygons = $this.attr('data-polygons'),
            polylines = $this.attr('data-polylines'),
            kml = $this.attr('data-kml'),
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

        if (kml) {
            loadKML(kml);
        } else {
            self.shapesList.fitMap(12);
        }
        
        self.shapesList.on('shapes.click', showInfo);
    }

    function loadKML(kml) {
        var layer = new google.maps.KmlLayer(kml, {
            suppressInfoWindows: false,
            clickable: true,
            preserveViewport: false
        });

        self.kmlLayers.push(layer);

        layer.setMap(self.mapa.innerMap);
    }

    function clearKML() {
        if ($.isArray(self.kmlLayers)) {
            $.each(self.kmlLayers, function(i, layer){
                layer.setMap(null);

                delete self.kmlLayers[i];
            });
        }

        self.kmlLayers = [];
    }

    function loadAmeazas() {
        
        self.shapesList.clear();
        clearKML();
        
        $(self.elements.tabAmeazas).find(self.elements.ameaza).each(loadMapData);
    }
    
    function loadIniciativas() {
        
        self.shapesList.clear();
        clearKML();
        
        $(self.elements.tabIniciativas).find(self.elements.ameaza).each(loadMapData);
    }
    
    self.shapesList = null;
    self.kmlLayers = null;
    
    self.elements = {
        map: '.mapa',
        
        tabs: '.tabs',
        tabAmeazas: '.tab-ameazas',
        tabIniciativas: '.tab-iniciativas',
        selectedTab: '.tabs a.selected',
        
        itemListado: '.item-listaxe',
        itemListadoAmeazas: '#listado-ameazas .item-listaxe',
        itemListadoIniciativas: '#listado-iniciativas .item-listaxe',
        
        listado: 'ul.listaxe',
        ameaza: 'article.ameaza',
        iniciativa: 'article.iniciativa',
        
        linkPaxinacion: '.paxinacion li a',
        
        toolbarTop: '#mapa-toolbar-top',
        mapLabels: '#toggle-labels'
    };
    
    self.init = function () {
        
        // Create the map
		self.mapa = common.map.init('ameazas', document.querySelector(self.elements.map), config.ameazas);
        
        $(self.elements.tabAmeazas + ', ' + self.elements.tabIniciativas).on('tabShow', cambioTab);
        
        $(self.elements.linkPaxinacion).on('click', cambioPaxina).filter('[data-page="1"]').trigger('click');
        
        $(self.elements.itemListadoAmeazas + ' ' + self.elements.itemListadoIniciativas).hide();
        $(self.elements.selectedTab).trigger('click');
        
        // Inicializamos o alto do contido
        updatePageHeight(1);
        
        // Ocultamos todas as paxinas menso a primeira
        $(self.elements.itemListado).hide();
        $(self.elements.itemListadoAmeazas + '[data-page="1"]').show();
        $(self.elements.itemListadoIniciativas + '[data-page="1"]').show();
        
        // Map labels
        $(self.elements.mapLabels).on('change', function (e) { self.mapa.showLabels(e.currentTarget.checked); }).trigger('change');
        
        // Shapes logic
        self.shapesList = new mapas.Shapes(self.mapa, {
            markerIcon: config.ameazas.ICON_MARKER,
            markerSize: {width: 14, height: 13},
            shapeColor: config.ameazas.COLOR_SHAPE
        });
        
        
        $(self.elements.tabs).on('tabShow', function (e) {
            
            var $this = $(e.currentTarget);
            
            if ($this.find(self.elements.tabAmeazas + '.subcontent').is(':visible')) {
                loadAmeazas();
            } else {
                loadIniciativas();
            }
        }).trigger('tabShow');
    };
    
    $(document).ready(self.init);
	return self;
	
}());