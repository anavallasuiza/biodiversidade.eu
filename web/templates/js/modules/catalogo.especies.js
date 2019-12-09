/*jslint browser:true */
/*global google, $, mapas, common, config, catalogo, alert, confirm, i18n */

/**
 * Species module
 */
catalogo.especies = (function () {
	
	'use strict';
	
	/**
	 * Public interface
	 */
	var self = {},
		listeners = {};
	
	
	function checkTimer() {
		
		if (catalogo.especies.searchTimer) {
			window.clearTimeout(catalogo.especies.searchTimer);
		}

		catalogo.especies.searchTimer = window.setTimeout(catalogo.especies.loadTree, 500);
	}
	
	function navigateTree(e) {
		$(e.delegateTarget).scrollTo($(e.currentTarget).parent(), 300, {offset: {top: -10}});
	}
	
	function selectTree(e) {
		var $span = $(e.target),
			$li = $span.parents('li').eq(0),
			especie;
		
		especie = {
			'code': $span.attr('data-codigo'),
			'name': $span.attr('data-name'),
			'comun': $li.attr('data-comun'),
			'sinonimos': $li.attr('data-sinonimos')
		};
		
		if (!self.add(especie)) {
            $li.removeClass('selected');
        }/* else {
            selectSubespecie($li);
        }*/
		
		return false;
	}

    /*function selectSubespecie($liEspecie) {
        var nextLi = $liEspecie.next();
        var $li = $(nextLi);

        if ($li.hasClass('subespecie')) {
            if (!$li.hasClass('selected')) {
                var $span = $li.children('span').eq(0);

                var especie = {
                    'code': $span.attr('data-codigo'),
                    'name': $span.attr('data-name'),
                    'comun': $li.attr('data-comun'),
                    'sinonimos': $li.attr('data-sinonimos'),
                    'subespecie': true
                };

                if (self.add(especie)) {
                    selectSubespecie($li);
                }
            } else {
                selectSubespecie($li);
            }
        }
    }*/
	
	function deselectTree(e) {
		var $icon = $(e.target),
			$span = $icon.parent();
		
		self.remove($span.attr('data-codigo'));
		
		return false;
	}
	
	function toggleAvistamentos() {
		$(self.elements.tree).toggleClass(self.cssClasses.filtroAvistamentos);
	}
	
	function createEspecie(especie) {
		
		var $li = $('<li class="selected">'),
			$span = $('<span class="especie">'),
			$i = $('<i class="icon-remove"></i>'),
			$img = $('<img/>');

		$i.appendTo($span);
		
		$img.attr('src', especie.icon);
		$img.addClass(self.cssClasses.listadoEspecie);
		$img.appendTo($span);
		
		
		$span.append('&nbsp;' + especie.name);
		$span.attr('data-codigo', especie.code);
		$span.attr('data-name', especie.name);
		$span.appendTo($li);
		
		$li.attr('data-sinonimos', especie.sinonimos);
		$li.attr('data-comun', especie.comun);
		
		return $li;
	}
	
	function updateTreeEspecie(especie) {
		
		var $span,
			$tree = $(self.elements.tree),
			$img = $('<img>');
		
		
		$span = $tree.find(self.elements.treeEspecie + ' li span[data-codigo="' + especie.code + '"]');
		
		if ($span.length) {
			$span.parent().addClass('selected').find(self.elements.iconDatos).hide();
			
			$img.attr('src', especie.icon);
			$img.addClass(self.cssClasses.listadoEspecie);
			$img.appendTo($span);
			$img.prependTo($span);
		}
	}
	
	function updateTreeSize() {
		
		var $tree = $(self.elements.tree),
			offset = config.catalogo.ESPECIE_ALTO * (self.selected - 1);
		
		if (self.selected) {
			$tree.css({'height': ($tree.data('height') - offset) + 'px'});
		}
	}
	
	function update(dontTrigger) {
		
		var $listado = $(self.elements.listado),
			$ul = $listado.find('ul'),
			$vacio = $listado.find(self.elements.vacio),
			$tree = $(self.elements.tree),
			i,
			code,
			$li,
			$especie,
			$span;
		
		// Reset
		$ul.html('');
		$li = $tree.find(self.elements.treeEspecie + ' li.selected');
		$li.removeClass('selected').find(self.elements.iconDatos).show();
		$li.find('span > img').remove();
		
		if (self.selected === 0) {
			$vacio.show();
			$ul.hide();
		} else {
			
			$vacio.hide();
			$ul.show();
			
			i = 0;
			for (code in self.especies) {
				
				if (self.especies.hasOwnProperty(code)) {
					
					// Set the color and the icon
					self.especies[code].color = config.catalogo.SELECTED[i].colors;
					self.especies[code].icon = config.catalogo.SELECTED[i].icons;
					
					$especie = createEspecie(self.especies[code]);
					$ul.append($especie);
					
					updateTreeEspecie(self.especies[code]);
					
					i += 1;
				}
			}
			
			updateTreeSize();
		}
		
        if (!dontTrigger) {
            self.trigger('update');
        }
	}
	
	function search() {
		
		var data = {},
			$tree = $(self.elements.tree),
			$buscador = $(self.elements.buscador),
			$panel = $(self.elements.panel);
		
		data.texto = $(self.elements.search).val();
		
		$.ajax({
			url: config.catalogo.URL_BUSCADOR,
			type: 'get',
			dataType: 'html',
			data: data,
			success: function (data, status, xhr) {

                var $liEspecie;
                
				if (catalogo.mapa) {
					catalogo.mapa.showPanel($panel);
				}

				$tree.html(data);

				$tree.find('li.selected ' + self.elements.grupo).trigger('click');
				$liEspecie = $tree.find(self.elements.treeEspecie + ' li');
                
				if ($.fn.qtip && $liEspecie.length > 0) {
					$liEspecie.qtip(config.catalogo.QTIP_CONF);
				}
                
                $tree.on('tree.load-data', function () {
                    
                    var $liEspecie = $tree.find(self.elements.treeEspecie + ' li');
                    
                    if ($.fn.qtip && $liEspecie.length > 0) {
                        $liEspecie.qtip(config.catalogo.QTIP_CONF);
                    }
                });
				
				update(true);
			},
			error: function (xhr, status, error) {
				alert(i18n.catalogo.ERROR_BUSCADOR);
			},
			complete: function () {
				$tree.find('li.cargando').remove();
				$buscador.find(self.elements.cargando).hide();
			}
		});
	}
    
    function changeEstado(e) {
        
        var $this = $(e.currentTarget),
            value = $this.attr('data-value');
        
        $(self.elements.tree).removeClass('so-validadas so-sen-validar');
        
        if (value === 'validadas') {
            $(self.elements.tree).addClass('so-validadas');
        } else if (value === 'sen-validar') {
            $(self.elements.tree).addClass('so-sen-validar');
        }
    }
    
    function changeProtexidas(e) {
        
        var $this = $(e.currentTarget);
        
        if ($this.is(':checked')) {
            $(self.elements.tree).addClass('so-protexidas');
        } else {
            $(self.elements.tree).removeClass('so-protexidas');
        }
    }
	
	/**
	 * List of elements for the module
	 */
	self.elements = {
		panel: '.row-listado.panel',
		buscador: '.row-buscador',
		search: '#texto-buscar',
		cargando: '.cargando',
		tree: '.tree',
		avistamentos: '#con-avistamentos',
		treeEspecie: '.especies-menu-selector',
		treeRequest: '.request',
		listado: '.lista-especies-seleccionadas',
		vacio: 'p.sen-especies',
        grupo: 'span.grupo',
		reino: 'span.reino',
		iconDatos: 'i.especie-con-datos',
        estadoEspecies: '#estado-especies',
        protexidas: '#protexidas'
        //tipoProteccion: '#tipo-proteccion'
	};
	
	/**
	 * List of css classes used
	 */
	self.cssClasses = {
		filtroAvistamentos: 'so-avistamentos',
		listadoEspecie: 'imaxe-especie-seleccionada'
	};
	
	/**
	 * Timer used in species search
	 */
	self.searchTimer = null;
	
	/**
	 * List of species currently selected
	 */
	self.especies = {};
	
	/**
	 * Numeber of selected species
	 */
	self.selected = 0;
	
	/**
	 * Initialization method
	 */
	self.init = function () {
		
		var $tree = $(self.elements.tree),
			$listado = $(self.elements.listado + ' ul');
		
		$(self.elements.search).on('keyup', checkTimer);
        $(self.elements.estadoEspecies).on('change', changeEstado);
        $(self.elements.protexidas).on('click', changeProtexidas);
		
		//
		$tree.on('click', 'li > span' + self.elements.treeRequest + ':not(.cargando, .custom)', navigateTree);
	
		// Select specie
		$tree.on('click', self.elements.treeEspecie + ' li:not(.cargando) span', selectTree);
	
		// Remove specie
		$tree.on('click', self.elements.treeEspecie + ' li i.icon-remove', deselectTree);
		$(self.elements.listado).on('click', 'i.icon-remove', deselectTree);
	
		// Filter toggle
		$(self.elements.avistamentos).on('change', toggleAvistamentos);
		
		if ($listado.children().length > 0) {
			
			if (catalogo.mapa) {
				catalogo.mapa.showPanel($(self.elements.panel), false, function () {
					$listado.find('li span').each(function (i, item) {
						$(item).one('click', selectTree).trigger('click');
					});
				});
			} else {
				
				$listado.find('li span').each(function (i, item) {
					$(item).one('click', selectTree).trigger('click');
				});
			}
		}
		
        
        if ($(self.elements.listado).find('ul li').length <= 0 && window.hasOwnProperty('localStorage')) {
            self.on('update', function () {
                
                var especies = {},
                    code;
                
                for (code in self.especies) {
                    if (self.especies.hasOwnProperty(code)) {
                        especies[code] = {
                            'code': code,
                            'name': self.especies[code].name,
                            'comun': self.especies[code].comun,
                            'sinonimos': self.especies[code].sinonimos
                        };
                    }
                }
                
                localStorage.setItem('catalogo-especies', JSON.stringify(especies));
            });
            
            
            $(window).on('load', function () {
                
                var especies,
                    code,
                    especie,
                    i = 0;
                
                especies = localStorage.getItem('catalogo-especies');
                
                if (especies && catalogo.avistamentos) {
                    
                    especies = JSON.parse(especies);
                    catalogo.avistamentos.loadedFromStorage = true;
                    
                    for (code in especies) {
                        
                        if (especies.hasOwnProperty(code)) {
                            especie = {
                                'code': code,
                                'name': especies[code].name,
                                'comun': especies[code].comun,
                                'sinonimos': especies[code].sinonimos
                            };
                            
                            self.add(especie, true);
                            i += 1;
                        }
                    }
                    
                    update();
                    
                    if (i > 0) {
                        catalogo.mapa.showPanel($(self.elements.panel));
                    }
                }
            });
        }
        
		$(window).on('load', function () {
			// Save the original height for the tree
			$tree.data('height', $tree.height());
            updateTreeSize();
		});
	};
	
	self.loadTree = function () {
		
		var $buscador = $(self.elements.buscador),
			$tree = $(self.elements.tree),
			$loading = $('<li class="cargando"></li>');
		
		$buscador.find(self.elements.cargando).show();
		
		$loading.html('<i class="icon-spin icon-spinner"></i>&nbsp;' + i18n.LOADING);
		$tree.height($tree.height()).html('').append($loading);
		
		search();
	};
	
	/**
	 * Add the specified specie to the selected list
	 * @method
	 * @public
	 * @param {Object} especie Specie data
	 */
	self.add = function (especie, avoidUpdate) {
		/*var checkNumMaxEspecies = true;

        if (especie.hasOwnProperty('subespecie')) {
            if (especie.subespecie) {
                checkNumMaxEspecies = false;
            }
        }*/

		if (self.selected >= config.catalogo.NUMERO_MAX_ESPECIES) {
			alert(i18n.catalogo.LIMITE_ESPECIES);
            return false;
		}
		
		if (!self.especies[especie.code]) {
			
			self.selected += 1;
			
			self.especies[especie.code] = especie;
			
			self.especies[especie.code].puntos = [];
			self.especies[especie.code].centroides1 = [];
			self.especies[especie.code].centroides10 = [];
			self.especies[especie.code].shapes = [];
			
            if (!avoidUpdate) {
                update();
                catalogo.mapa.hidePanel($(self.elements.panel));
            }
            
            self.trigger('added');
		}
        
        return true;
	};
	
	/**
	 * Remvoe the specified specie from the selected list
	 * @method
	 * @public
	 * @param {String} code Codigo da especie
	 */
	self.remove = function (code) {
		
		if (self.especies[code]) {
			
			delete self.especies[code];
			
			self.selected -= 1;
			
			update();
			self.trigger('removed');
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