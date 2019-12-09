/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, config, console, alert, confirm */

/**
 * Modulo avistamentos
 */
var avistamento = (function () {
	
	'use strict';
	
	var self = {};
	
    function getSmallCellSize() {
		var size;
	
		// If the map is zoom out render 1km2 centroides as 10km2 centroides so we can see them
		size = 1000;
		if (self.mapa.innerMap.getZoom() < config.avistamento.ZOOM_CENTROIDES1) {
			size = 10000;
		}
	
		return size;
	}
    
    function typeChanged(e) {
        
        var $relatedControls = $(self.elements.toggleLabels + ', ' + self.elements.toggleLimits),
			type = $(e.target).data('value');
		self.mapa.setType(config.avistamento.MAP_TYPES[type]);
		
		if (type === 'satelite') {
			$relatedControls.attr('disabled', 'disabled').parent().addClass('disabled');
		} else {
			$relatedControls.removeAttr('disabled').parent().removeClass('disabled');
		}
    }
    
    function restoreMiniMapa() {

		var $miniMapa = $('#mini-mapa');

		self.mapa.resize($miniMapa.data('width'), $miniMapa.data('height'));
		$miniMapa.appendTo($('#mini-mapa-container'));
		$miniMapa.removeClass('modal');

		google.maps.event.trigger(self.mapa.innerMap, 'resize');
		self.mapa.innerMap.setZoom($miniMapa.data('zoom') || 8);
		/*
        if (self.points.length) {
			self.mapa.fit(self.points);
		}
        */
	}
    
    function maximize(e) {
        
        var $this = $(e.currentTarget),
            $miniMapa = $(self.elements.miniMapa),
            width = $(window).width() - 100,
			height = $(window).height() - 100,
            alt = $this.data('alt');

		if ($miniMapa.hasClass('modal')) {

			restoreMiniMapa();
			$.fancybox.close();

		} else {
			$miniMapa.data('width', self.mapa.width);
			$miniMapa.data('height', self.mapa.height);
			$miniMapa.data('zoom', self.mapa.innerMap.getZoom());

			self.mapa.resize(width - 40, height - 40);
            

			$miniMapa.addClass('modal');

			$.fancybox({
				href: $miniMapa,
				type: 'inline',
				modal: false,
				closeBtn: true,
				autoSize: true,
				beforeClose: function () {
					restoreMiniMapa();

					var alt = $this.data('alt');
					$this.data('alt', $this.html());
					$this.html(alt);
				},
				afterLoad: function () {
					google.maps.event.trigger(self.mapa.innerMap, 'resize');
					self.mapa.innerMap.setZoom(12);
                    
                    /*
					if (self.points.length) {
                        self.mapa.fit(self.points);
                    }
                    */
				}
			});

			
			$this.data('alt', $this.html());
			$this.html(alt);
		}

        self.grid.reload();
        
		return false;
    }
    
    function drawPoint(especie, punto) {
		
		var point = self.grid.drawPoint(punto.latitude, punto.lonxitude, 6, {
			fill: especie.color,
			stroke: especie.color,
			'stroke-width': 1,
			'fill-opacity': 0.5
		}, punto);
		
		return point;
	}
	
	function drawCell(especies, centroide, size, corner) {
		
		var cell,
			data,
			code,
			especie;
		
		data = {'style': []};
		
		for (especie in especies) {
			
			if (especies.hasOwnProperty(especie)) {
				data.style.push({
				    'fill': especies[especie].color,
				    'stroke': especies[especie].color,
				    'stroke-width': 1,
				    'fill-opacity': 0.5
				});
			}
		}
				
		if (!corner) {
			data.lat = centroide.latitude;
			data.lng = centroide.lonxitude;
		}
		
		cell = self.grid.drawMultiCell(corner, size, data, centroide);
		//cell = self.grid.drawCell(data, size, centroide, corner);
		
		return cell;
	}
    
    function drawData(especie, type, data, size) {
		
		var item;
		
		if (type === 'point') {
			
			item = drawPoint(especie, data);
			self.puntos.push(item);
			
		} else if (type === 'point-cell') {

			item = drawCell(especie, data, size);
			self.puntos.push(item);
			
		} else if (type === 'centroid1') {
			
			item = drawCell(especie, data, size);
			self.centroides1.push(item);
		
		} else if (type === 'centroid10') {
			
			item = drawCell(especie, data, size);
			self.centroides10.push(item);
			
		} else {
			throw new Error('The data type ' + type + ' is not implemented!');
		}
	}
    
    function loadMapData() {
        
        var $mapa = $(self.elements.mapa),
            points = JSON.parse($mapa.attr('data-points').replace(/\'/ig, '"')),
            shapes = JSON.parse($mapa.attr('data-shapes').replace(/\'/ig, '"')),
            centroides1 = JSON.parse($mapa.attr('data-centroides1').replace(/\'/ig, '"')),
            centroides10 = JSON.parse($mapa.attr('data-centroides10').replace(/\'/ig, '"')),
            color = config.avistamento.SELECTED[1].colors,
            icon,
            lat,
            long,
            i,
            style,
            corners,
            size;
						
		style = {
            fill: color,
            stroke: color,
            'stroke-width': 1,
            'fill-opacity': 0.5
        };
        
        self.points = [];

        for (i = 0; i < points.length; i += 1) {
            self.points.push(new google.maps.LatLng(points[i].latitude, points[i].lonxitude));
            
            self.grid.drawPoint(points[i].latitude, points[i].lonxitude, 6, style, points[i]);
        }
		
        
        size = getSmallCellSize();
        for (i = 0; i < centroides1.length; i += 1) {
            
            self.points.push(new google.maps.LatLng(centroides1[i].latitude, centroides1[i].lonxitude));
            
            corners = self.grid.getCellCorners({lat: centroides1[i].latitude, lng: centroides1[i].lonxitude}, [size]);
            self.grid.drawMultiCell(corners[size], size, {style: [style], lat: centroides1[i].latitude, lng: centroides1[i].lonxitude}, centroides1[i]);
        }
        
        size = 10000;
        for (i = 0; i < centroides10.length; i += 1) {
            self.points.push(new google.maps.LatLng(centroides10[i].latitude, centroides10[i].lonxitude));
            
            corners = self.grid.getCellCorners({lat: centroides10[i].latitude, lng: centroides10[i].lonxitude}, [size]);
            self.grid.drawMultiCell(corners[size], size, {style: [style], lat: centroides10[i].latitude, lng: centroides10[i].lonxitude}, centroides10[i]);
        }
    }
    
    self.elements = {
        mapa: '.mapa',
        
        controles: '.controles-mapa',
        
        zoomMinus: '#zoom-minus',
        zoomPlus: '#zoom-plus',
        mapType: '#map-type',
        
        miniMapa: '#mini-mapa',
        maximize: '.ver-mapa-grande'
    };
    
    self.mapa = null;
    
    self.grid = null;
    
    self.points = [];
    
	/**
	 * Initialization method
	 */
	self.init = function () {
		
        var gridConfig = config.avistamento.GRID_CONFIG;
        
        // Create the map
		self.mapa = common.map.init('ameaza', document.querySelector(self.elements.mapa), config.avistamento);
        common.map.on('fullscreen', function () {
            self.grid.clearCells();
			self.grid.clearPoints();
            
            loadMapData();
        });
        
        $(self.elements.maximize).on('click', maximize);
        
        // Initialize grid
        gridConfig.afterDraw = function () {
            self.grid.clearCells();
			self.grid.clearPoints();

            loadMapData();
        };
        
        self.grid = mapas.makeGrid("catalogo", 'utm', gridConfig);
        self.grid.setMap(self.mapa);
        self.grid.transition.speed = 0;
        
        // Stuff to do when the map is fully loaded
		$(window).on('load', function () {
            
			// Add the toolbars to the map
			self.mapa.addElementToToolbar(document.querySelector(self.elements.controles), google.maps.ControlPosition.TOP_LEFT);
            
            window.setTimeout(loadMapData, 0);
            
            if (self.points.length) {
                self.mapa.fit(self.points, 14);
            }
        });
	};
    
    self.load = function () {
        self.grid.clearCells();
        self.grid.clearPoints();
        
        loadMapData();
    };
	
	$(document).ready(self.init);
	return self;
}());