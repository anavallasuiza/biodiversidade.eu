/*jslint browser:true */
/*global avistamento, google, $, mapas, config, common, i18n, alert, confirm, console, espazo, CKEDITOR */

// Load empty base module if not present
if (!espazo) {
	var espazo = {};
}

/**
 * Modulo avistamentos
 */
espazo.editar = (function () {
    
    'use strict';
    
    var self = {};
    
    /**
	 * Get current position ok callback. Shows coordinates on map and inputs
	 * @method
	 * @private
	 * @param {Object} position Position object form geolocation API
	 */
	function geolocateOK(position) {

		var $button = $(self.elements.geolocate),
            latLng;
		
        latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		
        self.mapa.fit([latLng], 15);
		
		$button.html($button.data('content'));
	}
	
	/**
	 * Get current position error callback. Shows error message
	 * @method
	 * @private
	 * @param {Object} error
	 */
	function geolocateKO(error) {
		var $button = $(self.elements.xeolocaliza);
		
		alert(config.GEOLOCATION_ERROR);
		$button.html($button.data('content'));
	}
	
	/**
	 * Click handler for geolocate button. Shows loading indicator and invoke the geolocation api.
	 * @method
	 * @private
	 */
	function geolocate(evt) {
		var $this = $(evt.currentTarget);
			
		$this.data('content', $this.html());
		$this.html('<i class="icon-spin icon-spinner"></i>');
		
		navigator.geolocation.getCurrentPosition(geolocateOK, geolocateKO, {'enableHighAccuracy': true, 'timeout': 60000, 'maximumAge': 0});
	}
    
    function closeInfo() {
        if (self.info) {
            self.info.close();
        }
    }
    
    function setPressedButton($button) {
        
        // Remove pressed
        $(self.elements.drawingOptions).find('.pressed').removeClass('pressed');
        
        // Set new pressed
        $button.addClass('pressed');
    }
    
    function clickOption(e) {
        
        var $this = $(e.currentTarget),
            result;
        
        if ($this.hasClass('pressed') === false) {
        
            setPressedButton($this);
            result = true;
        }
        
        return result;
    }
    
    function endShape(e) {
        
        var $defaultButton = $(self.elements.drawingDefault);
        
        if ($defaultButton.hasClass('presses') === false) {
            setPressedButton($defaultButton);
        }
    }
    
    function enableRemove(e) {
        $(self.elements.drawingDelete).removeAttr('disabled');
    }
    
    function disableRemove(e) {
        $(self.elements.drawingDelete).attr('disabled', 'disabled');
    }
    
    function removeShapeFromList(id) {
        closeInfo();
        delete self.shapesList.shapes[id];
    }
    
    function addInputs(shape) {
        
        var $form = $(self.elements.form),
            i,
            longPoints,
            point;
        
        $form.append($('<input type="hidden" name="shapes[' + shape.id + '][code]" value="' + shape.code  + '"/>'));
        $form.append($('<input type="hidden" name="shapes[' + shape.id + '][type]" value="' + shape.type  + '"/>'));
        $form.append($('<input type="hidden" name="shapes[' + shape.id + '][tipo]" value="' + shape.tipo  + '"/>'));
        
        if (shape.type === self.shapesList.types.MARKER) {
            $form.append($('<input type="hidden" name="shapes[' + shape.id + '][nome]" value="' + shape.nome  + '"/>'));
            $form.append($('<input type="hidden" name="shapes[' + shape.id + '][texto]" value="' + shape.texto  + '"/>'));
        }
        
        for (i = 0, longPoints = shape.points.length; i < longPoints; i += 1) {
            point = shape.points[i];
            $form.append($('<input type="hidden" name="shapes[' + shape.id + '][points][' + i + '][lat]" value="' + point.latitude  + '"/>'));
            $form.append($('<input type="hidden" name="shapes[' + shape.id + '][points][' + i + '][lng]" value="' + point.longitude  + '"/>'));
        }
    }
    
    function beforeSumit() {
        
        var shapes,
            id,
            shape,
            $descricion,
            instance;
        
        // Update point location
        self.shapesList.updatePoints();
        
        shapes = self.shapesList.shapes;
        
        for (id in shapes) {
            if (shapes.hasOwnProperty(id)) {
                shape = shapes[id];
                
                addInputs(shape);
            }
        }
    }
    
    function updatePoi(e) {
        
        var $this = $(e.currentTarget),
            $form = $(self.elements.infobox).find('.poi-form'),
            id = $form.attr('data-id'),
            $nome = $form.find(self.elements.poiNome),
            $texto = $form.find(self.elements.poiTexto);
        
        self.shapesList.shapes[id].nome = $nome.val();
        self.shapesList.shapes[id].texto = CKEDITOR.instances[$texto.attr('name')].getData();
    }
    
    
    
    function showInfo(shape) {
        
        var data = {text: 'Lorem ipsum'},
            info,
            latLng,
            $text;
        
        closeInfo();
        
        $text = $(self.elements.poiForm).clone(true).removeAttr('id').removeClass('hidden');
        $text.attr('data-id', shape.id);
        data.text = $text[0].outerHTML;
        
        if (!shape.getPosition) {
            
            latLng = self.shapesList.getPolygonCenter(shape);
            
            data.lat = latLng.lat();
            data.lng = latLng.lng();
        }
        
        self.info = self.mapa.showInfo(
            data,
            shape,
            {
                infoBoxClearance: config.espazo.INFOBOX_CLEARANCE,
                pixelOffset: config.espazo.PIXEL_OFFSET,
                closeBox: config.espazo.CLOSE_BOX,
                title: ''
            }
        );
        
        self.info.on('draw', function () {
            
            var instance,
                $infoBox,
                $poiNome,
                $poiTexto,
                $form = $(self.elements.infobox).find('.poi-form'),
                id = $form.attr('data-id');
        
            $infoBox = $(self.elements.infobox);
            $poiNome = $infoBox.find(self.elements.poiNome);
            $poiTexto = $infoBox.find(self.elements.poiTexto);
            
            instance = CKEDITOR.instances[$poiTexto.attr('name')];
            
            
            if (instance) {
                instance.destroy();
                $poiNome.off('change', updatePoi);
            }
            
            $poiTexto.val(self.shapesList.shapes[id].texto);
            $poiNome.val(self.shapesList.shapes[id].nome);
            
            instance = CKEDITOR.replace($poiTexto[0], {
                toolbarGroups: config.CKEDITOR_TOOLBARS,
                height: 150,
                removePlugins: 'elementspath',
                resize_enabled: false
            });
            
            $poiNome.on('change', updatePoi);
            instance.on('change', updatePoi);
            
        });
        
        self.info.on('beforeRemove', function () {
            var instance,
                $infoBox,
                $poiNome,
                $poiTexto;
        
            $infoBox = $(self.elements.infobox);
            $poiNome = $infoBox.find(self.elements.poiNome);
            $poiTexto = $infoBox.find(self.elements.poiTexto);
            
            instance = CKEDITOR.instances[$poiTexto.attr('name')];
            
            
            if (instance) {
                instance.destroy();
                $poiNome.off('change', updatePoi);
            }
        });
        
    }
    
    /**
     * Initialize the loaded shapes, i.e with event handlers or attributes
     * @method
     * @public
     * @param {Object} shape
     */
    function initializeShapes(shape) {
        
        var type = self.shapesList.getType(shape),
            tipoPOI;
        
        if (type === self.shapesList.types.MARKER) {
            
            google.maps.event.addListener(shape, 'click', function (evt) { showInfo(shape);  });
            google.maps.event.trigger(shape, 'click');
            
            tipoPOI = $('*[data-icon="' + shape.getIcon().url + '"]').attr('data-code');
            self.shapesList.shapes[shape.id].tipo = tipoPOI;
        }
    }

    function loadKML() {
        
        var $kml = $(self.elements.kml),
            url = $kml.attr('data-value');
        
        if (url) {
            self.kml = new google.maps.KmlLayer(url, {
                suppressInfoWindows: false,
                clickable: true,
                preserveViewport: false
            });
            
            self.kml.setMap(self.mapa.innerMap);
            
        } else if (self.kml) {
            self.kml.setMap(null);
        }
    }

    self.mapa = null;
    
    self.shapesList = null;
    
    self.drawingTool = null;
    
    self.info = null;
    
    self.kml = null;

    self.elements = {
        form: '#form-espazo',
        
        map: '.mapa',
        descricion: '#descricion',
        mapLabels: '#toggle-labels',
        geolocate: '#xeolocalizame',
        kml: '#kml',
        
        drawingOptions: '.drawing-options',
        drawingDefault: '#drawing-default',
        drawingDelete: '#drawing-delete',
        drawingMarkers: '.btn-marker',
        drawingPolygon: '#drawing-polygon',
        
        infobox: '.infoBox',
        poiForm: '#poi-form',
        poiNome: '.poi-nome',
        poiTexto: '.poi-texto'
        
    };
    
    self.init = function () {
        
        var instance,
            markers,
            polygons,
            polylines;
        
        // Create the map
		self.mapa = common.map.init('ameaza', document.querySelector(self.elements.map), config.espazo);
        
        // Map labels
        $(self.elements.mapLabels).on('change', function (e) { self.mapa.showLabels(e.currentTarget.checked); }).trigger('change');
        
        // Init the ck for the description field
        instance = CKEDITOR.replace(document.querySelector(self.elements.descricion), {toolbarGroups: config.CKEDITOR_TOOLBARS});
        $(self.elements.descricion).attr('required', 'required');
        
        instance.on('change', function () {
            if (instance.getData().trim()) {
                $(self.elements.descricion).removeAttr('required');
            } else {
                $(self.elements.descricion).attr('required', 'required');
            }
        });
        
        
        
        $(self.elements.geolocate).on('click', geolocate);
        
        // Shapes logic
        self.shapesList = new mapas.Shapes(self.mapa, {
            markerIcon: config.espazo.ICON_MARKER,
            markerSize: {width: 16, height: 26},
            shapeColor: config.espazo.COLOR_SHAPE
        });
        
        // Drawing tool
        self.drawingTool = new mapas.DrawingTool(self.mapa, {
            defaultColor: config.espazo.COLOR_SHAPE,
            defaultMarker: config.espazo.ICON_MARKER,
            selectedMarker: config.espazo.ICON_MARKER_SELECTED,
            sizeMarker: {width: 16, height: 26}
        });
        
        self.shapesList.on('shapes.new', function (shape) {
            closeInfo();
            self.drawingTool.loadShape(shape.object);
            initializeShapes(shape.object);
        });
        
        self.drawingTool.on('drawing.end', endShape);
        self.drawingTool.on('drawing.selected', enableRemove);
        self.drawingTool.on('drawing.deselected', disableRemove);
        self.drawingTool.on('drawing.deleted', disableRemove);
        self.drawingTool.on('drawing.deleted', removeShapeFromList);
        self.drawingTool.on('drawing.created', function (e) {
            closeInfo();
            self.shapesList.loadShapeObject(e.overlay);
            initializeShapes(e.overlay);
        });
        
        // laod data into the map
        markers = $(self.elements.form).attr('data-markers');
        if (markers) {
            self.shapesList.loadShapes(JSON.parse(markers.replace(/\'/ig, '"')));
        }
        
        polygons = $(self.elements.form).attr('data-polygons');
        if (polygons) {
            self.shapesList.loadShapes(JSON.parse(polygons.replace(/\'/ig, '"')));
        }
            
        polylines = $(self.elements.form).attr('data-polylines');
        if (polylines) {
            self.shapesList.loadShapes(JSON.parse(polylines.replace(/\'/ig, '"')));
        }
        
        // UI Buttons
        $(self.elements.drawingOptions).on('click', 'button:not(#drawing-delete)', clickOption);
        $(self.elements.drawingDelete).on('click', function () { closeInfo(); self.drawingTool.remove(); });
        $(self.elements.drawingDefault).on('click', function () { closeInfo(); self.drawingTool.finish(); });
        $(self.elements.drawingMarkers).on('click', function (e) { closeInfo(); self.drawingTool.drawPoint($(e.currentTarget).attr('data-icon')); });
        $(self.elements.drawingPolygon).on('click', function () { closeInfo(); self.drawingTool.drawPolygon(); });

        loadKML();
        
        if (!self.kml) {
            self.shapesList.fitMap(16);
        }

        $(self.elements.form).on('submit', beforeSumit);
    };
    
    // Load module on ready and return public interface
	$(document).ready(self.init);
	return self;
    
}());