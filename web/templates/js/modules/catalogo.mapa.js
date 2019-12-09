/*jslint browser:true */
/*global google, $, mapas, common, config, catalogo, console */

/**
 * Map module
 */
catalogo.mapa = (function () {
	
	'use strict';
	
	//- Private vars
	//--------------------------
	
	// Local var definition
	var self = {},
		alias = 'catalogo';
	
	
	//- Private methods
	//--------------------------
	
	/**
	 * Hanlder for the map mousemove
	 */
	function mousemove(evt) {

		var utm;

		$(self.elements.latLng).html(evt.latLng.lat() + ', ' + evt.latLng.lng());

		// Convert latLng to utm (WGS84)
		utm = mapas.utils.converter.latLngToUTM(evt.latLng.lat(), evt.latLng.lng());
		
		$(self.elements.utmZone).html(utm.zone + utm.hemisphere);
		$(self.elements.utmEasting).html(utm.easting);
		$(self.elements.utmNorthing).html(utm.northing);
	}
	
	/**
	 * Hanlder for the map mousemove
	 */
	function mouseout(evt) {

		$(self.elements.latLng).html('');
		$(self.elements.utmZone).html('');
		$(self.elements.utmEasting).html('');
		$(self.elements.utmNorthing).html('');
	}
    
    function fullscreen() {
        
        // Update points
		if (catalogo && catalogo.puntos && catalogo.puntos.grid) {
			catalogo.puntos.grid.reload();
		}
    }
	
	function toggleLabel(e) {
		self.mapa.showLabels(e.target.checked);
	}
	
	function toggleLimits(e) {
		self.mapa.showLimits(e.target.checked);
	}
	
	function agl(load) {
		
		if (load) {
			
			self.mapIsBusy(true);
			
			self.aglLayer = new google.maps.KmlLayer(config.catalogo.URL_LAYER_AGL, {
				suppressInfoWindows: false,
				clickable: true,
				preserveViewport: true
			});
			
			google.maps.event.addListener(self.aglLayer, 'status_changed', function () {
				self.mapIsBusy(false);
			});
			
			self.aglLayer.setMap(self.mapa.innerMap);
		} else if (self.aglLayer) {
			self.aglLayer.setMap(null);
			self.aglLayer = null;
		}
	}
	
	function toggleAgl(e) {
		agl(e.target.checked);
	}
	
	function toggleGrid(e) {
		
		if (catalogo.puntos && catalogo.puntos.grid) {
			catalogo.puntos.showGrid(e.target.checked);
		}
	}
	
	function toggleImageOverlay() {
		
		var bounds,
			ne,
			sw,
			$imageSize = $(self.elements.toggleImageSize);
		
		if (self.imageOverlay) {
			
			self.imageOverlay.setMap(null);
			self.imageOverlay = null;
			$imageSize.prop('checked', false).attr('disabled', 'disabled');
			
		} else {
			
			sw = new google.maps.LatLng(config.catalogo.AGL_IMAGE_SW[0], config.catalogo.AGL_IMAGE_SW[1]);
			ne = new google.maps.LatLng(config.catalogo.AGL_IMAGE_NE[0], config.catalogo.AGL_IMAGE_NE[1]);
			bounds = new google.maps.LatLngBounds(sw, ne);
			
			self.imageOverlay = new mapas.ImageOverlay('aghlimage', { url: config.catalogo.AGL_IMAGE, bounds: bounds});
			
			self.imageOverlay.setMap(catalogo.mapa.mapa.innerMap);
			
			$imageSize.removeAttr('disabled');
		}
		
	}
	
	function toggleImageOverlaySize() {
		
		var $node = $(self.imageOverlay.node),
			bounds,
			latLng;
		
		
		if (this.checked) {
			
			// Resize to fit the image
			self.mapa.prevWidth = self.mapa.width;
			self.mapa.prevHeight = self.mapa.height;
			
			self.mapa.resize($node.outerWidth(), $node.outerHeight() + 30);
			
			catalogo.puntos.grid.node.style.border = '1px solid #999';
			catalogo.puntos.grid.resize($node.outerWidth(), $node.outerHeight());
			catalogo.puntos.grid.paper.setSize($node.outerWidth(), $node.outerHeight());
			
			// Pan to image
			bounds = new google.maps.LatLngBounds();

			latLng = new google.maps.LatLng(config.catalogo.AGL_IMAGE_NE[0], config.catalogo.AGL_IMAGE_NE[1]);
			bounds.extend(latLng);
			
			self.mapa.innerMap.setCenter(latLng);
			
			latLng = new google.maps.LatLng(config.catalogo.AGL_IMAGE_SW[0], config.catalogo.AGL_IMAGE_SW[1]);
			bounds.extend(latLng);
	
			self.mapa.innerMap.panToBounds(bounds);
			
			//Hide panels
			$(self.elements.panel).hide();
			$(self.elements.controles).hide().next().hide();
			$(catalogo.especies.elements.buscador).hide();
			$(self.elements.columnaControles).css('marginTop', '-70px');
			
		} else {
			self.mapa.resize(self.mapa.prevWidth, self.mapa.prevHeight);
			
			catalogo.puntos.grid.node.style.border = '';
			catalogo.puntos.grid.resize(self.mapa.width, self.mapa.height);
			catalogo.puntos.grid.paper.setSize(self.mapa.width, self.mapa.height);
			
			//Show panels
			$(self.elements.panel).show();
			$(self.elements.controles).show().next().show();
			$(catalogo.especies.elements.buscador).show();
			$(self.elements.columnaControles).css('marginTop', '0');
		}
		
	}
	
	function togglePanels(e) {
		
		var $this = $(e.currentTarget),
			$panel = $this.parents(self.elements.panel).eq(0);

		if ($panel.hasClass(self.cssClasses.panelVisible)) {
			self.hidePanel($panel);
		} else {
			self.showPanel($panel);
		}
	}
	
	function changeDataVis(evt) {
		
		if (catalogo.puntos) {
			
			catalogo.puntos.visualization = $(evt.target).attr('data-value');
			
			if (catalogo.puntos.visualization === 'mixed') {
				$(self.elements.dataVisSize).removeClass('disabled');
			} else {
				$(self.elements.dataVisSize).addClass('disabled');
			}
			
			catalogo.puntos.update();
		}
	}
	
	function changeDataVisSize(evt) {
		
		if (catalogo.puntos) {
			catalogo.puntos.visualizationSize = $(evt.target).attr('data-value');
			catalogo.puntos.update();
		}
	}
    
    function changeGridType(e) {
        var $this = $(e.currentTarget),
            type = $this.val();
        
        
        if (catalogo.puntos) {
			
			catalogo.puntos.visualization = type;
			
			if (catalogo.puntos.visualization === 'mixed') {
				$(self.elements.dataVisSize).removeClass('disabled');
			} else {
				$(self.elements.dataVisSize).addClass('disabled');
			}
			
            if (catalogo.puntos.visualization === 'grid') {
                $(self.elements.gridSize).removeClass('disabled');
                catalogo.puntos.gridSize = $this.attr('data-size');
            } else {
                catalogo.puntos.gridSize = 'all';
                $(self.elements.gridSize).addClass('disabled');
            }
            
			catalogo.puntos.update();
		}
    }
    
    function changeGridSize(e) {
        
        var $this = $(e.currentTarget);
        
        if (catalogo.puntos) {
            
            catalogo.puntos.gridSize = $this.attr('data-value');
            catalogo.puntos.update();
        }
    }
	
	//- Public vars
	//--------------------------
	
	/**
	 * List of elements for the module
	 */
	self.elements =  {
		map: '.mapa',
		
		exportGrids: '#export-grid',
		
		zoomPlus: '#zoom-plus',
		zoomMinus: '#zoom-minus',
		
		mapType: '#map-type',
		
		toggleLabels: '#toggle-labels',
		toggleLimits: '#toggle-limits',
		toggleAgl: '#toggle-agl',
		toggleImage: '#toggle-agl-image',
		toggleImageSize: '#toggle-agl-image-size',
		
		fullscreen: '#fullscreen',
		
		toggleGrid: '#toggle-grella-completa',
		
		panel: '.panel',
		togglePanels: '.panel .panel-toggle',
		buttonView: '.panel-view',
		buttonHide: '.panel-hide',
		panelContent: '.panel-content',
		
		controles: '.row-controles',
		columnaControles: '.columnas.controles-mapa',
		
		loadingSpinner: '#map-loading-spinner',
		
		viewPanel: '.opcion-ver',
		hidePanel: '.opcion-ocultar',
		
		latLng: '#map-latlng',
		utmZone: '#utm-zone',
		utmEasting: '#utm-easting',
		utmNorthing: '#utm-northing',
		
		toolbarTop: '#mapa-toolbar-top',
		toolbarBottom: '#mapa-toolbar-bottom',
		
		dataVis: '#datavis-type',
		dataVisSize: '#datavis-type-size',
        gridSize: '#grid-size',
        
        gridType: '.tipo-grella'
	};
	
	/**
	 * List of css clases  used in the module
	 */
	self.cssClasses = {
		panelVisible: 'panel-visible',
		panelHidden: 'panel-hidden',
		panelReverse: 'panel-reverse'
	};
	
	/**
	 * Reference to the map object
	 */
	self.mapa = null;
	
	
	
	//- Public methods
	//--------------------------
	
	/**
	 * Initialization method
	 */
	self.init = function () {
		
		// Create the map
		self.mapa = common.map.init(alias, document.querySelector(self.elements.map), config.catalogo);
        
		// Control de mouse movement to show the coordinates at the bottom
		self.mapa.on('mousemove', mousemove);
		self.mapa.on('mouseout', mouseout);
		
		// Disable export button
		$(self.elements.exportGrids + '.disabled').on('click', function () { return false; });
		
        $(document).on('mozfullscreenchange, fullscreenchange, webkitfullscreenchange', fullscreen);
		
		// Show/Hide labels and country/province limits on the map
		$(self.elements.toggleLabels).on('change', toggleLabel).trigger('change');
		$(self.elements.toggleLimits).on('change', toggleLimits).trigger('change');
	
		// Show/Hide Agl limits
		$(self.elements.toggleAgl).on('change', toggleAgl).trigger('change');
		
		// Show/Hide full utm grid
		$(self.elements.toggleGrid).on('change', toggleGrid).trigger('change');
		
		// Show/Hide map panels
		$(self.elements.togglePanels).on('click', togglePanels);
		
		$(self.elements.toggleImage).on('click', toggleImageOverlay);
		
		$(self.elements.toggleImageSize).on('click', toggleImageOverlaySize);
		
		$(self.elements.dataVis).on('change', changeDataVis).trigger('change');
		$(self.elements.dataVisSize).on('change', changeDataVisSize).trigger('change');
        
        $(self.elements.gridType).on('click', changeGridType);
        $(self.elements.gridType + ':checked').trigger('click');
        
        $(self.elements.gridSize).on('change', changeGridSize);
	};
	
	/**
	 * Shows or hides the loading spinner for the map
	 * @param {Boolean} value True to show or falsy to hide
	 */
	self.mapIsBusy = function (value) {
		(value ? $.fn.fadeIn : $.fn.fadeOut).call($(self.elements.loadingSpinner), config.ANIMATION_SPEED);
	};
	
	/**
	 * Show the specified panel
	 */
	self.showPanel = function ($panel, dontTrigger, callback) {
		
		var $ver = $panel.find(self.elements.buttonView),
            $ocultar = $panel.find(self.elements.buttonHide),
			$content = $panel.find(self.elements.panelContent),
			top = $panel.outerHeight(true) + config.catalogo.MARGEN_PANEL_REVERSE;

		$ocultar.show();
		$ver.hide();
		
		if ($panel.hasClass(self.cssClasses.panelReverse)) {
			$content.show();
			$panel.animate({'top': '-' + top + 'px'}, config.ANIMATION_SPEED, callback);
		} else {
			$content.slideDown(config.ANIMATION_SPEED, callback);
		}
		
		$panel.addClass(self.cssClasses.panelVisible);
		$panel.removeClass(self.cssClasses.panelHidden);
		
		$panel.data('visible', true);
		
		if (!dontTrigger) {
			$panel.trigger('show-panel');
		}
	};
	
	
	self.updatePanel = function ($panel) {
		
		var top = $panel.outerHeight(true) + config.catalogo.MARGEN_PANEL_REVERSE;
		if ($panel.hasClass(self.cssClasses.panelReverse)) {
			$panel.animate({'top': '-' + top + 'px'}, config.ANIMATION_SPEED);
		}
	};
	
	/**
	 * Hide the specified panel
	 */
	self.hidePanel = function ($panel, dontTrigger, callback) {
		var $ver = $panel.find(self.elements.buttonView),
            $ocultar = $panel.find(self.elements.buttonHide),
			$content = $panel.find(self.elements.panelContent);

		//$content.data('height', $content.outerHeight(true));

		$ver.show();
		$ocultar.hide();
		
		if ($panel.hasClass(self.cssClasses.panelReverse)) {
			$panel.animate({'top': 0}, config.ANIMATION_SPEED, callback);
		} else {
			$content.slideUp(config.ANIMATION_SPEED, callback);
		}
		
		$panel.addClass(self.cssClasses.panelHidden);
		$panel.removeClass(self.cssClasses.panelVisible);
		
		$panel.data('visible', false);
		
		if (!dontTrigger) {
			$panel.trigger('hide-panel');
		}
	};
	
	// Return the public interface for the module
	return self;
	
}());