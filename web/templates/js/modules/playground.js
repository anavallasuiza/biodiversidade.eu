/*jslint browser:true */
/*global google, $, jQuery, mapas, common, config, catalogo, Blob, BlobBuilder, console, alert, confirm, i18n */

/**
 * Playgrouynd module
 */
var playground = (function () {
    
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
    
    self.mapa = null;
    
    self.drawingTool = null;
    
    self.shapesList = null;
    
    self.elements = {
        map: '.mapa',
        mapLabels: '#toggle-labels',
        
        drawingOptions: '.drawing-options',
        
        drawingDefault: '#drawing-default',
        drawingDelete: '#drawing-delete',
        drawingMarker: '#drawing-marker',
        drawingAlert: '#drawing-alert',
        drawingCircle: '#drawing-circle',
        drawingLine: '#drawing-line',
        drawingPolygon: '#drawing-polygon',
        drawingPolygonBlue: '#drawing-polygon-blue',
        drawingRectangle: '#drawing-rectangle'
    };
    
    function init() {
        
        var shapes = [
            {
                id: 'point-loremipsum',
                code: 'loremipsum',
                type: 'marker',
                text: 'I am a point',
                points: [
                    {latitude: 41.8, longitude: -6.77}
                ]
            },
            {
                id: 'point-loremipsum2',
                code: 'loremipsum2',
                type: 'marker',
                text: 'I am a point',
                points: [
                    {latitude: 41.8, longitude: -7.00}
                ]
            },
            {
                id: 'polygon-loremipsum',
                code: 'loremipsum',
                type: 'polygon',
                text: 'I am a point',
                points: [
                    {latitude: 42.8, longitude: -7.77},
                    {latitude: 42.9, longitude: -7.77},
                    {latitude: 42.9, longitude: -7.87},
                    {latitude: 42.8, longitude: -7.87}
                ]
            }
        ];
        
        // Map logic
		self.mapa = common.map.init('playground', document.querySelector(self.elements.map), config.playground);
        $(self.elements.mapLabels).on('change', function (e) { self.mapa.showLabels(e.currentTarget.checked); }).trigger('change');
        
        // Shapes logic
        self.shapesList = new mapas.Shapes(self.mapa, {
            markerIcon: config.playground.ICON_AMEAZA,
            markerSize: {width: 14, height: 13},
            shapeColor: config.playground.COLOR_SHAPE
        });
        
        
        // Drawing logic
        self.drawingTool = new mapas.DrawingTool(self.mapa, {
            defaultColor: config.playground.COLOR_SHAPE,
            selectedColor: config.playground.COLOR_SHAPE_SELECTED,
            //defaultMarker: config.playground.ICON_MARKER,
            defaultMarker: config.playground.ICON_AMEAZA,
            selectedMarker: config.playground.ICON_AMEAZA_SELECTED,
            sizeMarker: {width: 14, height: 13}
        });
        
        
        //Events
        self.drawingTool.on('drawing.end', endShape);
        self.drawingTool.on('drawing.selected', enableRemove);
        self.drawingTool.on('drawing.deselected', disableRemove);
        self.drawingTool.on('drawing.deleted', disableRemove);
        self.drawingTool.on('drawing.deleted', removeShapeFromList);
        self.drawingTool.on('drawing.created', function(e){ self.shapesList.loadShapeObject(e.overlay)  })
        
        
        self.shapesList.on('shapes.new', function (shape) { self.drawingTool.loadShape(shape.object);  });
        
        // UI
        
        $(self.elements.drawingOptions).on('click', 'button:not(#drawing-delete)', clickOption);
        $(self.elements.drawingDelete).on('click', function () {self.drawingTool.remove(); });
        $(self.elements.drawingDefault).on('click', function () {self.drawingTool.finish(); });
        $(self.elements.drawingMarker).on('click', function () {self.drawingTool.drawPoint(); });
        $(self.elements.drawingAlert).on('click', function () {self.drawingTool.drawPoint(config.playground.ICON_AMEAZA_2); });
        $(self.elements.drawingCircle).on('click', function () {self.drawingTool.drawCircle(); });
        $(self.elements.drawingLine).on('click', function () {self.drawingTool.drawLine(); });
        $(self.elements.drawingPolygon).on('click', function () {self.drawingTool.drawPolygon(); });
        $(self.elements.drawingPolygonBlue).on('click', function () {self.drawingTool.drawPolygon('blue'); });
        $(self.elements.drawingRectangle).on('click', function () {self.drawingTool.drawRectangle(); });
        
        self.shapesList.loadShapes(shapes);
    }
    
    $(document).ready(init);
    return self;
    
}());
    