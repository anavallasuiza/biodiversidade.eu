/*jslint browser:true, forin: true, devel: true*/
/*global google, $, mapas, common, config, catalogo, alert, confirm, i18n, catalogo, avistamento, indexOf */

/**
 * Species module
 */
catalogo.area = (function () {
	
	'use strict';
	
	/**
	 * Public interface
	 */
	var self = {};
    
    function clickArea(e) {
        var $this = $(e.currentTarget);
        
        if ($this.hasClass('pressed')) {
            self.drawingTool.finish();
        } else {
            
            if (self.shape) {
                self.shape.setMap(null);
                self.shape = null;
            }
            
            self.drawingTool.drawRectangle(null, {
                rectangleOptions: config.catalogo.CONFIG_SHAPE_AREA
            });
        }
        
        $this.toggleClass('pressed');
    }
    
    function clickPoligono(e) {
        var $this = $(e.currentTarget);
        
        if ($this.hasClass('pressed')) {
            self.drawingTool.finish();
        } else {
            if (self.shape) {
                self.shape.setMap(null);
                self.shape = null;
            }
            
            initDrawingTool();
            
            self.drawingTool.drawPolygon(null, {
                poligonOptions: config.catalogo.CONFIG_SHAPE_AREA
            });
        }
        
        $this.toggleClass('pressed');
    }
    
    function enableFilters() {
		$(self.elements.bottomToolbar).find('.desplegable').removeClass('disabled');
	}
	
	function disableFilters() {
		$(self.elements.bottomToolbar).find('.desplegable').addClass('disabled');
	}
    
    function tamanhoArea(ne, sw) {
        
        var nw = new google.maps.LatLng(ne.lat(), sw.lng()),
            se = new google.maps.LatLng(sw.lat(), ne.lng());
        
        return google.maps.geometry.spherical.computeArea([ne, nw, sw, se]);
    }
    
    
    
    function loadAvistamentos(data) {
		
		var $panel = $(self.elements.panel),
            $listado = $(self.elements.listado),
			$vacio = $(self.elements.vacio),
			$listaxe,
			step,
            shapes;
        
        self.especies = [];
		
		$listado.hide().html(data);
        //catalogo.mapa.showPanel($panel);
        
        //self.shapes.clear();
        
        self.listaAvistamentos = [];
        self.especies = [];
		
		if ($listado.find('li').length > 0) {
			$listado.slideDown(config.ANIMATION_SPEED, function () {
				if ($panel.data('visible')) {
                    window.setTimeout(function () { catalogo.mapa.updatePanel($panel); });
				}
            });
			
			$listaxe = $listado.find(self.elements.ul);
			step = $listado.find('article').eq(0).outerWidth(true);
			
			$listaxe.miniSlider({
				stepWidth: step
			});
			
			$listado.find(self.elements.prev).on('click', function () {
                var position = parseInt($listaxe.data('miniSlider').$tray.css('left').replace('px', ''), 10) + step;
				$listaxe.miniSlider('goto', -1 * position + 'px');
			});
            
			$listado.find(self.elements.next).on('click', function () {
                var position = parseInt($listaxe.data('miniSlider').$tray.css('left').replace('px', ''), 10) - step;
				$listaxe.miniSlider('goto', -1 * position + 'px');
			});
			
            $(self.elements.avistamento).each(function (i, item) {
                var $this = $(item),
                    codigo = $this.attr('data-codigo'),
                    especie = $this.attr('data-especie');
                
                self.listaAvistamentos.push(codigo);
                self.especies.push(especie);
            });
			
            $vacio.slideUp(config.ANIMATION_SPEED);
            
            //enableExport();
		} else {
            $vacio.slideDown(config.ANIMATION_SPEED, function () {
				if ($panel.data('visible')) {
					window.setTimeout(function () { catalogo.mapa.updatePanel($panel); });
				}
            });
		}
		
		enableFilters();
		updatePuntos(self.requestData);
		//updateEspecies();
	}
    
    function updateAvistamentos(data) {
        
        self.listaAvistamentos = [];
        
        if (self.requestAvistamento) {
            self.requestAvistamento.abort();
        }
        
        
        // Make the request
        self.requestAvistamento = $.ajax({
            url: config.catalogo.URL_AVISTAMENTOS_AREA,
            type: 'get',
            dataType: 'html',
            data: data,
            cache: false,
            success: function (data, status, xhr) {
                loadAvistamentos(data);
            },
            error: function (xhr, status, error) {
                if (status !== 'abort') {
                    alert(i18n.catalogo.ERROR_AVISTAMENTO_AREA);
                }
            },
            complete: function () {
                catalogo.mapa.mapIsBusy(false);
                self.requestAvistamento = null;
            }
        });
        
       
    }
    
    function loadEspecies(data) {
        
        var $listado = $(self.elements.listadoEspecies + ' ul'),
            $senEspecies = $(self.elements.senEspecies);
        
        $listado.html(data);
        
        if ($listado.find('li').length > 0) {
            
            $senEspecies.hide();
            
            catalogo.mapa.showPanel($(self.elements.panel));
            catalogo.mapa.showPanel($(self.elements.panelEspecies));
            
        } else {
            $senEspecies.show();
        }
    }
    
    function updateEspecies(data) {
        
        var $listado = $(self.elements.listadoEspecies + ' ul'),
            $senEspecies = $(self.elements.senEspecies);
        
        catalogo.mapa.mapIsBusy(true);
        
        $senEspecies.hide();
        $listado.html('<li><i class="icon-spin icon-spinner"></i> ' + i18n.LOADING  + '</li>');
        
        if (self.requestEspecies) {
            self.requestEspecies.abort();
        }
        
        // Make the request
        self.requestEspecies = $.ajax({
            url: config.catalogo.URL_ESPECIES_CODIGO,
            type: 'get',
            dataType: 'html',
            data: data,
            cache: false,
            success: function (data, status, xhr) {
                loadEspecies(data);
            },
            error: function (xhr, status, error) {
                if (status !== 'abort') {
                    alert(i18n.catalogo.ERROR_ESPECIES_CODIGO);
                    
                    $listado.html('');
                    $senEspecies.show();
                }
            },
            complete: function () {
                catalogo.mapa.mapIsBusy(false);
                self.requestEspecies = null;
            }
        });
    }
    
    function loadPuntos(data) {
        
        var bounds,
            i,
            list = [],
            shapes;
        
        self.shapes.clear();
        
        if (data.puntos) {
            list.push({
                type: 'marker',
                items: data.puntos
            });
        }
        
        if (data.centroides1) {
            list.push({
                type: 'polygon',
                items: data.centroides1,
                size: 1000
            });
        }
        
        if (data.centroides10) {
            list.push({
                type: 'polygon',
                items: data.centroides10,
                size: 10000
            });
        }
        
        self.shapesEspecie = {};
        self.listaPuntos = [];
        
        shapes = self.shapes.load(list, null, function (shape) {
            
            var avistamento = shape.context,
                $avistamento = $(self.elements.avistamento + '[data-codigo="' + avistamento + '"]'),
                especie = $avistamento.attr('data-especie');
            
            self.listaPuntos.push(shape.code);
            
            especie = especie || 'sen-identificar';
            
            if (!self.shapesEspecie[especie]) {
                self.shapesEspecie[especie] = [];
            }
                
            self.shapesEspecie[especie].push(shape);
        });
        
        if (self.shapesEspecie['sen-identificar']) {
            $(self.elements.listadoEspecies + ' ul').prepend('<li class="selected"><span class="especie" data-codigo="sen-identificar" data-name="' + i18n.catalogo.SEN_IDENTIFICAR + '" title="' + i18n.catalogo.SEN_IDENTIFICAR + '">' + i18n.catalogo.SEN_IDENTIFICAR + '</span></li>');
        }
        
        if (list.length) {
            enableExport();
        } else {
            disableExport();
        }
    }
    
    function updatePuntos(data) {
        
        if (self.requestPuntos) {
            self.requestPuntos.abort();
        }
        
        // Make the request
        self.requestPuntos = $.ajax({
            url: config.catalogo.URL_PUNTOS_AREA,
            type: 'get',
            dataType: 'json',
            data: data,
            cache: false,
            success: function (data, status, xhr) {
                loadPuntos(data);
            },
            error: function (xhr, status, error) {
                if (status !== 'abort') {
                    alert(i18n.catalogo.ERROR_ESPECIES_CODIGO);
                }
            },
            complete: function () {
                catalogo.mapa.mapIsBusy(false);
                self.requestPuntos = null;
            }
        });
    }
    
    function enableExport() {
        var $group = $(self.elements.exportGroup),
            $button = $group.find('button');
        
        $group.removeClass('disabled');
        $button.removeAttr('disabled');
    }
    
    function disableExport() {
        var $group = $(self.elements.exportGroup),
            $button = $group.find('button');
        
        $group.addClass('disabled');
        $button.attr('disabled', 'disabled');
    }
    
    function select(fit) {
        var $panel = $(self.elements.panel),
            bounds,
            path,
            i,
            latLng,
            ne,
            sw,
            data = {},
            $territorio = $(self.elements.territorio),
            $provincia = $(self.elements.provincia),
            $concello = $(self.elements.concello);

        if (self.shape) {
            if (self.shape.getBounds) {
                bounds = self.shape.getBounds();
                ne = bounds.getNorthEast();
                sw = bounds.getSouthWest();
                
                if (tamanhoArea(ne, sw) > config.catalogo.MAX_KM_PER_AREA) {
                    alert(i18n.catalogo.ERROR_AREA_GRANDE);
                    return;
                }
        
                data.ne = {lat: ne.lat(), lng: ne.lng()};
                data.sw = {lat: sw.lat(), lng: sw.lng()};
            } else {
                path = self.shape.getPath();
                data.points = [];
                bounds = new google.maps.LatLngBounds();
                
                for (i = 0; i < path.getLength(); i += 1) {
                    latLng = path.getAt(i);
                    data.points.push({lat: latLng.lat(), lng: latLng.lng()});
                    bounds.extend(latLng);
                }
            }
        }

        $(self.elements.bottomToolbar).find('.desplegable').each(function (i, item) {
			var $item = $(item),
				value = $item.attr('data-value'),
				name = $item.attr('data-name');

			data[name] = value;
		});
        
        if ($concello.val()) {
            data.concello = $concello.val();
        }
        
        if ($provincia.val()) {
            data.provincia = $provincia.val();
        }
        
        if ($territorio.val()) {
            data.territorio = $territorio.val();
        }
        
        disableFilters();
        disableExport();
        
        self.requestData = data;
        
        catalogo.mapa.hidePanel($panel);
        catalogo.mapa.hidePanel($(self.elements.panelEspecies));
        catalogo.mapa.mapIsBusy(true);
        
        updateAvistamentos(data);
        updateEspecies(data);
    }
    
    function hideEspecie(especie) {
        
        var i,
            shape,
            total,
            j,
            corner;
        
        for (i = 0; i < self.shapesEspecie[especie].length; i += 1) {
            shape = self.shapesEspecie[especie][i];
            
            if (shape.corner) {
                
                if (!self.hiddenCorners[shape.corner]) {
                    self.hiddenCorners[shape.corner] = 0;
                }
                
                self.hiddenCorners[shape.corner] += 1;
                
                corner = self.shapes.getCorner(shape.corner);
                total = corner.length - self.hiddenCorners[shape.corner];
                
                if (total <= 0) {
                    corner[0].object.setMap(null);
                }
                
            } else {
                
                shape.object.setMap(null);
            }
        }
    }
    
    function viewEspecie(especie) {
        
        var i,
            shape,
            total,
            j,
            corner;
        
        for (i = 0; i < self.shapesEspecie[especie].length; i += 1) {
            shape = self.shapesEspecie[especie][i];
            
            if (shape.corner) {
                
                
                
                self.hiddenCorners[shape.corner] -= 1;
                
                corner = self.shapes.getCorner(shape.corner);
                
                corner[0].object.setMap(self.map.innerMap);
                
                
            } else {
                
                shape.object.setMap(self.map.innerMap);
            }
        }
    }
    
    function moved() {
        
        if (self.timer) {
            window.clearTimeout(self.timer);
        }
        
        self.timer = window.setTimeout(select, config.catalogo.AREA_MOVE_TIMER);
    }
    
    function toggleEspecie(e) {
        
        var $this = $(e.currentTarget),
            codigo = $this.attr('data-codigo'),
            $parent = $this.parent();
        
        if ($parent.hasClass('selected')) {
            hideEspecie(codigo);
        } else {
            viewEspecie(codigo);
        }
        
        $this.parent().toggleClass('selected');
    }
    
    function getInputs(prefix, data) {
        
        var $form = $(self.elements.exportForm),
            code,
            subcode
            i;
        
        for (code in data) {
            if (data[code].constructor === Array) {
                for (var i = 0; i < data[code].length; i++) {
                    getInputs(prefix + '[' + code + '][' + i + ']', data[code][i]);
                }
            } else if (data[code].constructor === Object) {
                getInputs(prefix + '[' + code + ']', data[code]);
            } else {
                $form.append('<input type="hidden" name="' + prefix + '[' + code + ']" value="' + data[code] + '"/>');
            }
        }
    }
    
    function exportar(name, url, items) {
        var $form = $(self.elements.exportForm),
            i,
            code,
            subcode;
        
        $form.html('');
        $form.attr('action', url);
        
        for (i = 0; i < items.length; i += 1) {
            $form.append('<input type="hidden" name="' + name + '[]" value="' + items[i] + '"/>');
        }
        
        getInputs('data', self.requestData);
        
        $form.submit();
    }
    
    function exportarEspecies(e) {
        var $this = $(e.currentTarget);
        
        exportar('especies', $this.attr('href'), self.especies);
        return false;
    }
    
    function exportarObservacions(e) {
        var $this = $(e.currentTarget);
        
        exportar('avistamentos', $this.attr('href'), self.listaAvistamentos);
        return false;
    }
    
    function checkLocation() {
        var $territorio = $(self.elements.territorio),
            $provincia = $(self.elements.provincia),
            $concello = $(self.elements.concello),
            $button = $(self.elements.buttonPan);
        
        if ($provincia.val() || $concello.val()) {
            $button.removeAttr('disabled');
        } else {
            $button.attr('disabled', 'disabled');
        }
    }
    
    function updateLocation(e) {
        
        select();
        
        if (!self.shape) {
            panToLocation(e);
        }
    }
    
    function panToLocation(e) {
        var $this = $(e.currentTarget),
            $territorio = $(self.elements.territorio),
            $provincia = $(self.elements.provincia),
            $concello = $(self.elements.concello),
            address = '',
            geocoder = new google.maps.Geocoder();
        
        $this.attr('data-text', $this.html());
        $this.html(i18n.LOADING);
        
        address += $territorio.val();
        
        if ($provincia.val()) {
            address += ' ' + $provincia.val();
        }
        
        if ($concello.val()) {
            address += ' ' + $concello.val();
        }
        
        geocoder.geocode({ 'address': address}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                self.map.innerMap.setCenter(results[0].geometry.location);
                
                if ($concello.val()) {
                    self.map.innerMap.setZoom(12);
                } else {
                    self.map.innerMap.setZoom(9);
                }
            } else {
                alert(i18n.catalogo.ERROR_DIRECCION_AREA + ' (' + status + ')');
            }
            
            $this.html($this.attr('data-text'));
        });
    }
    
    function initDrawingTool() {
        
        // Drawing tool
        self.drawingTool = new mapas.DrawingTool(catalogo.mapa.mapa, {
            rectangleOptions: config.catalogo.CONFIG_SHAPE_AREA,
            polygonOptions: config.catalogo.CONFIG_SHAPE_AREA
        });
        
        self.drawingTool.on('drawing.end', function () {
            $('.pressed').removeClass('pressed');
        });

        self.drawingTool.on('drawing.deleted', function () {
            self.shape = null;
        });

        self.drawingTool.on('drawing.created', function (e) {
            self.shape = e.overlay;
            self.shape.setOptions(config.catalogo.CONFIG_SHAPE_AREA);
            google.maps.event.addListener(self.shape, 'bounds_changed', moved);
            select(true);
        });
        
        self.drawingTool.on('drawing.deleted', function (e) {
            self.shape = null;
            select();
        });
        
        self.drawingTool.on('drawing.change', function (evt, shape) {
            self.shape = shape;
            select(false);
        });
        
        self.drawingTool.on('drawing.move', function (evt, shape) {
            self.shape = shape;
            select(false);
        });
    }
    
    self.map = null;
    
    self.drawingTool = null;
    
    self.shapesList = null;
    
    self.shape = null;
    
    self.timer = null;
    
    self.listaAvistamentos = [];
    
    self.listaPuntos = [];
    
    self.centroids1 = {};
    
    self.centroids10 = {};
    
    self.especies = [];
    
    self.shapesEspecie = {};
    
    self.hiddenCorners = {};
    
    self.requestAvistamento = null;
    
    self.requestEspecies = null;
    
    self.requestPuntos = null;
    
    self.elements = {
        botonArea: '#seleccionar-area',
        botonPoligono: '#seleccionar-poligono',
        panel: '.panel.row-avistamentos',
        panelEspecies: '.panel.row-listado',
        vacio: '#container-listaxe-avistamentos p.sen-especie',
		listado: '#listaxe-avistamentos',
        bottomToolbar: '#mapa-toolbar-bottom',
        
        ul: '.listaxe',
        next: '.next',
		prev: '.previous',
        
        avistamento: 'article.especie-avistamento',
        
        listadoEspecies: '.listado-especies',
        senEspecies: '.sen-especies',
        especie: '.especie',
        
        exportForm: '#form-exportacion',
        exportGroup: '#export-btn-group',
        exportarEspecies: '#exportar-especies',
        exportarObservacions: '#exportar-observacions',
        
        territorio: '#territorio',
        provincia: '#provincia',
        concello: '#concello',
        buttonPan: '#btn-pan'
    };
    
    self.init = function () {
        
        self.map = catalogo.mapa.mapa;
        
        initDrawingTool();
        
        avistamento.common.infoboxMixin.apply(this);
        self.avistamentos.init(i18n.catalogo, self.map);
        
        common.map.mapDataMixin.apply(this);
        self.shapes.init({markerIcon: config.catalogo.ICON_MARKER, markerSize: {width: 14, height: 14}, shapeColor: config.catalogo.COLOR_SHAPE});
        
        self.shapes.on('click', self.avistamentos.showInfo);
        

        $(self.elements.bottomToolbar).find('.desplegable').on('change', function () { select(); });

        $(self.elements.botonPoligono).on('click', clickPoligono);
        $(self.elements.listadoEspecies).on('click', self.elements.especie, toggleEspecie);
        
        $(self.elements.exportarEspecies).on('click', exportarEspecies);
        $(self.elements.exportarObservacions).on('click', exportarObservacions);
        
        $(self.elements.territorio).on('change', checkLocation);
        $(self.elements.provincia).on('change', checkLocation);
        $(self.elements.concello).on('change', checkLocation);
        
        $(self.elements.buttonPan).on('click', updateLocation);//panToLocation);
    };
    
    return self;
	
}());