/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, alert, confirm, CKEDITOR, console */

/**
 * Modulo avistamentos
 */
var proxecto = (function () {
    
    'use strict';
    
    var self = {};
    
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
    
    self.elements = {
        tabs: '.tabs',
        avistamentos: '#avistamentos',
        mapaAvistamentos: '#avistamentos-mapa',
        listaxe: '.listaxe',
        paxinacion: '.paxinacion-cliente',
        map: '.map',
        mapLabels: '#toggle-labels',
        avistamento: 'article.especie-avistamento'
    };
    
    self.init = function () {
        
        if ($(self.elements.map).length) {
            // Create the map
            self.map = common.map.init('proxecto', document.querySelector(self.elements.map), config.proxecto);
            
            // Map labels
            $(self.elements.mapLabels).on('change', function (e) { self.map.showLabels(e.currentTarget.checked); }).trigger('change');
            
            avistamento.common.infoboxMixin.apply(self);
            self.avistamentos.init(i18n.catalogo, self.map);
            
            common.map.mapDataMixin.apply(self);
            self.shapes.init({markerIcon: config.proxecto.ICON_MARKER, markerSize: {width: 14, height: 14}, shapeColor: config.proxecto.COLOR_SHAPE});
            
            self.shapes.on('click', self.avistamentos.showInfo);
        }
        
        $(self.elements.avistamentos).on('tabShow', function () {
            $(self.elements.paxinacion + ' *[data-page="1"]').eq(0).trigger('click'); 
        });
        
        $(self.elements.mapaAvistamentos).on('tabShow', function () { self.map.resize();self.shapes.list.fitMap(); });
        
        $(self.elements.avistamentos).find(self.elements.avistamento).each(loadAvistamentos);
    };
    
    $(document).ready(self.init);
    return self;
    
}());
