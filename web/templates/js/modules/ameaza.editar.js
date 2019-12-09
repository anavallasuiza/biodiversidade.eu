/*jslint browser:true */
/*global google, $, mapas, config, common, console, CKEDITOR, alert */

// Load empty base module if not present
if (!window.ameaza) {
	var ameaza = {};
}

/**
 * Base module
 */
ameaza.editar = (function () {
	
	'use strict';
    
    var self = {};
    
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
        delete self.shapesList.shapes[id];
    }
    
    function addInputs(shape) {
        
        var $form = $(self.elements.form),
            i,
            longPoints,
            point;
        
        $form.append($('<input type="hidden" name="shapes[' + shape.id + '][code]" value="' + shape.code  + '"/>'));
        $form.append($('<input type="hidden" name="shapes[' + shape.id + '][type]" value="' + shape.type  + '"/>'));
        
        for (i = 0, longPoints = shape.points.length; i < longPoints; i += 1) {
            point = shape.points[i];
            $form.append($('<input type="hidden" name="shapes[' + shape.id + '][points][' + i + '][lat]" value="' + point.latitude  + '"/>'));
            $form.append($('<input type="hidden" name="shapes[' + shape.id + '][points][' + i + '][lng]" value="' + point.longitude  + '"/>'));
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

    function beforeSumit() {
        
        var shapes,
            id,
            shape,
            $deleted = $(self.elements.kml).parents('.file-uploader').find('.deleted');
        
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
    
    self.kml = null;

    self.elements = {
        form: '.content.wrapper form',
        titulo: '#titulo',
        descricion: '#descricion',
        data: '#data',
        territorio: '#territorio',
        provincia: '#provincia',
        concello: '#concello',
        lugar: '#lugar',
        nivel: '#nivel',
        tipo: '#tipo',
        especie: '#especie',
        proxectos: '#proxectos',
        map: '.mapa',
        kml: '#kml',
        
        geolocate: '.xeo',
        
        drawingOptions: '.drawing-options',
        drawingDefault: '#drawing-default',
        drawingDelete: '#drawing-delete',
        drawingMarker: '#drawing-marker',
        drawingPolygon: '#drawing-polygon'
    };
    
    self.init = function () {
        
        var markers,
            polygons,
            polylines;
        
        // Create the map
		self.mapa = common.map.init('ameaza', document.querySelector(self.elements.map), config.ameaza);
        
        // Map labels
        $(self.elements.mapLabels).on('change', function (e) { self.mapa.showLabels(e.currentTarget.checked); }).trigger('change');
        
        // Init the ck for the description field
        CKEDITOR.replace(document.querySelector(self.elements.descricion), {
            toolbarGroups: [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'align' ] },
                { name: 'links' }
            ]
        });
        
        $(self.elements.geolocate).on('click', geolocate);
        
        // Shapes logic
        self.shapesList = new mapas.Shapes(self.mapa, {
            markerIcon: config.ameaza.ICON_MARKER,
            markerSize: {width: 14, height: 13},
            shapeColor: config.ameaza.COLOR_SHAPE
        });
        
        // Drawing tool
        self.drawingTool = new mapas.DrawingTool(self.mapa, {
            defaultColor: config.ameaza.COLOR_SHAPE,
            defaultMarker: config.ameaza.ICON_MARKER,
            selectedMarker: config.ameaza.ICON_MARKER_SELECTED,
            sizeMarker: {width: 14, height: 13}
        });
        
        self.shapesList.on('shapes.new', function (shape) { self.drawingTool.loadShape(shape.object);  });
        
        self.drawingTool.on('drawing.end', endShape);
        self.drawingTool.on('drawing.selected', enableRemove);
        self.drawingTool.on('drawing.deselected', disableRemove);
        self.drawingTool.on('drawing.deleted', disableRemove);
        self.drawingTool.on('drawing.deleted', removeShapeFromList);
        self.drawingTool.on('drawing.created', function (e) { self.shapesList.loadShapeObject(e.overlay);  });
        
        
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
        
        self.shapesList.fitMap(14);
        
        // UI Buttons
        $(self.elements.drawingOptions).on('click', 'button:not(#drawing-delete)', clickOption);
        $(self.elements.drawingDelete).on('click', function () {self.drawingTool.remove(); });
        $(self.elements.drawingDefault).on('click', function () {self.drawingTool.finish(); });
        $(self.elements.drawingMarker).on('click', function () {self.drawingTool.drawPoint(); });
        $(self.elements.drawingPolygon).on('click', function () {self.drawingTool.drawPolygon(); });
        
        loadKML();

        if (!self.kml) {
            self.shapesList.fitMap(16);
        }

        $(self.elements.form).on('submit', beforeSumit);
    };
    
    $(document).ready(self.init);
	return self;
}());
