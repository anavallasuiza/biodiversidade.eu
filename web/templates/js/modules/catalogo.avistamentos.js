/*jslint browser:true */
/*global google, $, mapas, common, config, catalogo, alert, confirm, i18n, console */

/**
 * Map module
 */
catalogo.avistamentos = (function () {
	
	'use strict';
	
	/**
	 * Public interface
	 */
	var self = {},
		buscador = false,
		listado = false,
		listeners = {};
	
	function showBuscador() {
		
		var $listado = $(self.elements.panel);
		
		if ($listado.data('visible')) {
			catalogo.mapa.hidePanel($listado, true);
			listado = true;
		}
		
		buscador = true;
	}
	
	function hideBuscador() {
		
		var $listado = $(self.elements.panel);
		
		if (listado) {
			catalogo.mapa.showPanel($listado, true);
		}
		
		buscador = false;
	}
	
	function show(e) {
		
		var $buscador = $(catalogo.especies.elements.panel);
		
		if ($buscador.data('visible')) {
			catalogo.mapa.hidePanel($buscador, true);
			buscador = true;
		}
		
		listado = true;
	}
	
	function hide(e) {
		
		var $buscador = $(catalogo.especies.elements.panel);
		
		if (buscador) {
			catalogo.mapa.showPanel($buscador, true);
		}
		
		listado = false;
	}
	
	function enableFilters() {
		$(self.elements.filtros).removeClass('disabled');
	}
	
	function disableFilters() {
		$(self.elements.filtros).addClass('disabled');
	}
	
	function parseJSON(data) {
		
		var result = {};
		
		if (data) {
			result = JSON.parse(data.replace(/\'/ig, '"'));
		}
		
		return result;
	}
	
	function addDataToSpecies(specie, points, centroids1, centroids10, shapes, $item) {
		
		var i,
			longItems,
			item,
			especie;
		
		especie = catalogo.especies.especies[specie];
		
		if (especie) {
		
			longItems = points.length;
			for (i = 0; i < longItems; i += 1) {
				item = points[i];
				item.dom = $item;
                
                self.puntos.push(new google.maps.LatLng(item.latitude, item.lonxitude));
                
				especie.puntos.push(item);
			}
			
			longItems = centroids1.length;
			for (i = 0; i < longItems; i += 1) {
				item = centroids1[i];
				item.dom = $item;
                
                self.puntos.push(new google.maps.LatLng(item.latitude, item.lonxitude));
				
				especie.centroides1.push(item);
			}
			
			longItems = centroids10.length;
			for (i = 0; i < longItems; i += 1) {
				item = centroids10[i];
				item.dom = $item;
                
                self.puntos.push(new google.maps.LatLng(item.latitude, item.lonxitude));
				
				especie.centroides10.push(item);
			}
			
			longItems = shapes.length;
			for (i = 0; i < longItems; i += 1) {
				item = shapes[i];
				item.dom = $item;
				
				especie.shapes.push(item);
			}
		}
	}
	
	function clearSpecies() {
		
		var code;
		
		for (code in catalogo.especies.especies) {
			if (catalogo.especies.especies.hasOwnProperty(code)) {
				catalogo.especies.especies[code].puntos = [];
				catalogo.especies.especies[code].centroides1 = [];
				catalogo.especies.especies[code].centroides10 = [];
				catalogo.especies.especies[code].shapes = [];
			}
		}
	}
	
	/**
	 * Parse the sighting list and to store the data in catalogo.avistamentos.avistamentos and catalogo.avistamentos.especies
	 * @method
	 * @private
	 */
	function parse() {
		
		var $listado = $(self.elements.listado).find(self.elements.avistamento),
			$avistamento,
			i,
			longListado = $listado.length,
			$item,
			code,
			specie,
            parent,
			points,
			centroids1,
			centroids10,
			shapes;
		
		self.avistamentos = {};
		self.especies = {};
		self.puntos = [];
        
		clearSpecies();
		
		for (i = 0; i < longListado; i += 1) {
			
			$item = $listado.eq(i);
			
			$avistamento = $item.find(self.elements.fichaAvistamento);
			
			code = $avistamento.attr('data-codigo');
			specie = $avistamento.attr('data-especie');
            parent = $avistamento.attr('data-parent');
            
            if (catalogo.especies.especies[parent]) {
                specie = parent;
            }
			
			points = parseJSON($avistamento.attr('data-puntos'));
			centroids1 = parseJSON($avistamento.attr('data-centroides1'));
			centroids10 = parseJSON($avistamento.attr('data-centroides10'));
			shapes = parseJSON($avistamento.attr('data-shapes'));
            
			self.avistamentos[code] = {
				especie: specie,
				puntos: points,
				centroides1: centroids1,
				centroides10: centroids10,
				shapes: shapes,
				doms: $avistamento
			};
			
			addDataToSpecies(specie, points, centroids1, centroids10, shapes, $item);
			
			if (!self.especies[specie]) {
				self.especies[specie] = [];
			}
			
			self.especies[specie].push(code);
		}
        
        if (catalogo.mapa && !self.loadedFromStorage) {
            catalogo.mapa.mapa.fit(self.puntos, 9);
        }
	}
	
	function loadAvistamentos(data) {
		
		var $panel = $(self.elements.panel),
			$listado = $(self.elements.listado),
			$vacio = $(self.elements.vacio),
			$listaxe,
			step;
		
		$listado.hide().html(data);
		
		if ($listado.find('li').length > 0) {
			$listado.slideDown(config.ANIMATION_SPEED, function () {
				if ($panel.data('visible')) {
					catalogo.mapa.updatePanel($panel);
				}
			});
			
			$listaxe = $listado.find(self.elements.ul);
			step = $listado.find('article').eq(0).outerWidth(true);
			
			$listaxe.miniSlider({
				stepWidth: config.catalogo.MINISLIDER_SIZE
			});
			
			$listado.find(self.elements.prev).on('click', function () {
                var position = parseInt($listaxe.data('index').$tray.css('left').replace('px', ''), 10) + step;
                console.log($listaxe.data('miniSlider').$tray.css('left'));
                
				$listaxe.miniSlider('goto', -1 * position + 'px');
			});
			$listado.find(self.elements.next).on('click', function () {
                var position = parseInt($listaxe.data('miniSlider').$tray.css('left').replace('px', ''), 10) - step;
                console.log(parseInt($listaxe.data('miniSlider').$tray.css('left').replace('px', ''), 10));
                console.log(step);
                console.log(position);
                
				$listaxe.miniSlider('goto', -1 * position + 'px');
			});
			
			parse();
			
		} else {
			$vacio.slideDown(config.ANIMATION_SPEED, function () {
				if ($panel.data('visible')) {
					catalogo.mapa.updatePanel($panel);
				}
			});
			
			clearSpecies();
		}
		
		enableFilters();
		
		self.trigger('update');
	}
    
    function showPanel(e) {
        
        if (!self.loadedFromStorage) {
            catalogo.mapa.showPanel($(self.elements.panel));
        }
    }
	
	/**
	 * List of sightings
	 */
	self.avistamentos = {};
	
	/**
	 * List os species with the specie code and the sighting code
	 * ie: { 'cannabis-sativa-l': ['cannabis-sativa-l-1', 'cannabis-sativa-l-2', 'cannabis-sativa-l-3']}
	 */
	self.especies = {};
    
    self.puntos = [];
    
    self.loadedFromStorage = false;
	
	self.elements = {
		panel: '.panel.row-avistamentos',
		form: '.panel.row-avistamentos form.subcontent-filter',
		vacio: '#container-listaxe-avistamentos p.sen-especie',
		listado: '#listaxe-avistamentos',
		items: '.item-listaxe',
		ul: '.listaxe',
		next: '.next',
		prev: '.previous',
		avistamentos: '.listaxe article',
		filtros: '.panel.row-avistamentos .desplegable',
		avistamento: '.datos-avistamento',
		fichaAvistamento: '.especie-avistamento',
		fichaEspecie: 'article.especie'
	};
	
	/**
	 * Initialization method
	 */
	self.init = function () {
		
		var $panel = $(self.elements.panel),
			$buscador = $(catalogo.especies.elements.panel),
			$form = $(self.elements.form);
		
		$panel.on('show-panel', show);
		//$panel.on('hide-panel', hide);
		
		$buscador.on('show-panel', showBuscador);
		//$buscador.on('hide-panel', hideBuscador);
		
		$form.find('.desplegable').on('change', self.update);
		
		catalogo.especies.on('update', self.update);
        //catalogo.especies.on('added', showPanel);
	};
	
	self.update = function () {
		
		var data,
			code,
			$selects,
			$form = $(self.elements.form),
			$vacio = $(self.elements.vacio),
			$listado = $(self.elements.listado),
			$panel = $(self.elements.panel);
		
		self.avistamentos = {};
		self.especies = {};
		
		if (catalogo.especies.selected) {
		
			catalogo.mapa.mapIsBusy(true);
			
			// Get the data for the request
			data = $form.serialize();
			
			for (code in catalogo.especies.especies) {
				if (catalogo.especies.especies.hasOwnProperty(code)) {
					data += '&especies[]=' + code;
				}
			}
			
			$form.find('.desplegable').each(function (i, item) {
				
				var $item = $(item),
					value = $item.attr('data-value'),
					name = $item.attr('data-name');

				data += '&' + name + '=' + value;
			});
			
			$vacio.slideUp(config.ANIMATION_SPEED);
			
			// Show loading indicators
			$listado.hide().html(
				'<div class="cargando"><i class="icon-spinner icon-spin"></i>&nbsp;' + i18n.LOADING + '</div>'
			).slideUp(config.ANIMATION_SPEED);
			
			// Make the request
			$.ajax({
				url: config.catalogo.URL_AVISTAMENTOS,
				type: 'get',
				dataType: 'html',
				data: data,
                cache: false,
				success: function (data, status, xhr) {
					loadAvistamentos.call(self, data);
				},
				error: function (xhr, status, error) {
					alert(i18n.catalogo.ERROR_AVISTAMENTOS);
				},
				complete: function () {
					catalogo.mapa.mapIsBusy(false);
                    self.loadedFromStorage = false;
				}
			});
			
		} else {
			
			if (!$vacio.is(':visible')) {
				$vacio.slideDown(config.ANIMATION_SPEED);
				$listado.slideUp(config.ANIMATION_SPEED, function () {
					if ($panel.data('visible')) {
						catalogo.mapa.updatePanel($panel);
					}
				});
			}
			
			disableFilters();
			
			self.trigger('update');
            
            self.loadedFromStorage = false;
		}
	};
	
	self.on = function (type, handler) {
		
		if (!listeners[type]) {
			listeners[type] = {};
		}
		
		if (!listeners[type][handler.toString()]) {
			listeners[type][handler.toString()] = handler;
		}
		
	};
	
	self.off = function (type, handler) {
		
		if (listeners[type] && listeners[type][handler.toString()]) {
			delete listeners[type][handler.toString()];
		}
	};
	
	self.trigger = function (type, context) {
		var handlers = listeners[type],
			code;
		
		for (code in handlers) {
			if (handlers.hasOwnProperty(code)) {
				handlers[code].call(this, context);
			}
		}
	};
	
	
	return self;
	
}());