/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, alert, confirm, console, espazo, CKEDITOR */

/**
 * Modulo avistamentos
 */
var comparador = (function () {
    
    'use strict';
    
    var self = {};
    
    function initSortable() {
        $(self.elements.listadoFotos).sortable({
            'axis': 'y',
            'containment': 'parent',
            'handle': self.elements.handle,
            'placeholder': 'row-placeholder',
            'forcePlaceholderSize': true
        });
    }
    
    function loadDefaultImage(e) {
        
        var $this = $(e.currentTarget);
        
        $this.attr('src', config.TRANSPARENT_IMAGE);
        $this.addClass('broken-image');
    }
    
    function hoverSlider(e) {
        
        var $this = $(e.currentTarget);
        
        return false;
    }
    
    function updateIndex(e, $target) {
        var $this = $(e.currentTarget);
        
        $this.parent().find(self.elements.actualIndex).html($target.index() + 1);
    }
    
    function initializeSlider(i, item) {
        
        var $item = $(item);
        
        $item.find('div').eq(0).ansSlider({
            width: $item.find('img').eq(0).outerWidth(),
            buttons: function () {
                return this.parent().parent().find(self.elements.prev + ',' + self.elements.next);
            }
        });
        
        $item.on('ansSliderBeforeChangeSlide', updateIndex);
    }
    
    function removeRow(e) {
        var $this = $(e.currentTarget),
            $li = $this.parents('li').eq(0);
        
        $li.remove();
    }
    
    function showModalEspecie() {
	
		$.fancybox.open({
			href: $(self.elements.modalEspecie),
			type: 'inline',
			modal: true,
			maxWidth: 300,
            minHeight: 20,
			closeBtn: false
		});
    }
    
    function addEspecie(e) {
        
        var $this = $(e.currentTarget),
            especie = $(self.elements.especie).val(),
            reino = $(self.elements.especie).attr('data-reino');
        
        $this.attr('data-text', $this.html());
        $this.html('<i class="icon-spin icon-spinner"></i> ' + i18n.LOADING);
        
        // Make the request
        $.ajax({
            url: config.comparador.URL_GET_ESPECIE + reino + '/' + especie,
            type: 'get',
            dataType: 'html',
            data: {},
            cache: false,
            success: function (data, status, xhr) {
                
                if (data) {
                    $(self.elements.listadoFotos).append(data);
                    $(self.elements.listadoFotos).find('li:last-child ' + self.elements.images).each(initializeSlider);
                    initSortable();
                }
                
                $.fancybox.close();
            },
            error: function (xhr, status, error) {
                alert(i18n.comparador.ERROR_ESPECIE);
            },
            complete: function () {
                $this.html($this.attr('data-text'));
            }
        });
    }
    
    function showModalImage(e) {
        var $this = $(e.currentTarget);
        
        $.colorbox({
            href: $this.attr('data-src'),
			photo: true,
            open: true,
            maxWidth: '99%',
            maxHeight: '99%'
		});
    }
    
    function addImageToOverlay(e) {
        var $this = $(e.currentTarget),
            url = $this.attr('data-src'),
            $clone,
            $parent;
        
        self.showOverlay();
        
        if (self.images.indexOf(url) < 0) {
        
            self.images.push(url);
            
            $clone = $this.clone().removeAttr('width').removeAttr('height');
            
            $clone.wrap('<div class="compared-image"></div>');
            $parent = $clone.parent().css({'position': 'absolute', 'top': 0, 'left': 0});
            
            $clone.on('load', function () {
                
                $parent.resizable({aspectRatio: true}).draggable({containment: "parent" }).appendTo($(self.elements.overlayItems));
            });
            
            $clone.attr('src', url);
            
            /*
            $parent.width($this.outerWidth());
            $parent.height($this.outerHeight());
            */
            
            $parent.append('<div class="image-data">' +
                               '<div><span>' + $this.attr('data-especie') + '</span> - <span>' + $this.attr('data-tipo') + '</span></div>' +
                               '<span class="button-eliminar"><i class="icon-remove"></i></span>' +
                           '</div>');
        }
    }
    
    function removeImageFromOverlay(e) {
        var $this = $(e.currentTarget),
            $parent = $this.parents(self.elements.imageCompared).eq(0),
            $img = $parent.find('img').eq(0),
            index;
        
        index = self.images.indexOf($img.attr('data-src'));
        self.images.splice(index, 1);
        
        $parent.remove();
        
        if (self.images.length <= 0) {
            self.hideOverlay();
        }
    }
    
    function selectImage(e) {
        var $this = $(e.currentTarget);
        
        if (self.$selected) {
            self.$selected.css({'z-index': 0});
        }
        
        self.$selected = $this;
        $this.css({'z-index': 1});
    }
    
    /**
     * Images loaded into the overlay
     */
    self.images = [];
    
    self.$selected = null;
    
    self.elements = {
        comparador: '.comparador',
        listadoFotos: '.comparador .listado',
        images: 'div.images',
        
        handle: '.info .move',
        
        prev: '.previous',
        next: '.next',
        
        remove: '.remove',
        
        engadirEspecie: '#engadir-especie',
        modalEspecie: '#modal-especie',
        especie: '#especie',
        
        especieCancelar: '.modal-cancelar',
        especieAceptar: '.modal-aceptar',
        
        actualIndex: '.actual-index',
        
        overlay: '.overlay-comparador',
        overlayItems: '.overlay-items',
        imageCompared: '.compared-image',
        cerrarOverlay: '#cerrar-overlay',
        imageData: '.image-data',
        botonEliminar: '.button-eliminar'
    };
    
    self.init = function () {
        
        initSortable();
        
        $(self.elements.images).each(function (i, item) {
            var $this = $(item);
            
            $this.height($this.find('img').eq(0).outerHeight());
            $this.width($this.find('img').eq(0).outerWidth());
        });
        
        $(self.elements.images).each(initializeSlider);
        
        $(self.elements.comparador).on('click', self.elements.remove, removeRow);
        
        $(self.elements.engadirEspecie).on('click', showModalEspecie);
        
        $(self.elements.especie).select2({
			'minimumInputLength': 3,
			'width': 'resolve',
			'allowClear': true,
			'ajax': {
				'url': config.URL_ESPECIES,
				'type': 'GET',
				'dataType': 'json',
				'data': function (term, page) {
					return { 'q': term, 'phpcan_exit_mode': 'json', 'reino': $(self.elements.especie).attr('data-reino') };
				},
				'results': function (data, page) {
					return {results: data};
				}
			},
			'formatInputTooShort': function (term, minLength) {
				return i18n.PLEASE_ENTER + (minLength - term.length) + i18n.MORE_CHARS;
			},
			'dropdownCssClass': "bigdrop"
		});
        
        $(self.elements.modalEspecie).on('click', self.elements.especieCancelar, function () { $.fancybox.close(); });
        $(self.elements.modalEspecie).on('click', self.elements.especieAceptar, addEspecie);
        
        $(self.elements.comparador).on('click', 'img:not(.empty)', addImageToOverlay);
        $(self.elements.cerrarOverlay).on('click', self.hideOverlay);
        
        $(document.body).on('click', self.elements.botonEliminar, removeImageFromOverlay);
        $(document.body).on('mousedown', self.elements.imageCompared, selectImage);
    };
    
    self.showOverlay = function () {
        var $overlay = $(self.elements.overlay);
        
        $overlay.height($(document.body).outerHeight());
        $overlay.removeClass('hidden');
    };
    
    self.hideOverlay = function () {
        $(self.elements.overlay).addClass('hidden');
    };
    
    // Load module on ready and return public interface
	$(document).ready(self.init);
    
    return self;
}());