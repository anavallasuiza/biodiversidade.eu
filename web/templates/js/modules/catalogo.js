/*jslint browser:true */
/*global google, $, mapas, config, common, console */

/**
 * Base module
 */
var catalogo = (function () {
	
	'use strict';
	
	/**
	 * Public interface
	 */
	var self = {};
	
	function scroll() {
		
		var scrollTop = $(window).scrollTop(),
			$body = $('body'),
			$topHeader;
		
		if (scrollTop > 5) {

			if (self.smallHeader === false) {

				$topHeader  = $('div.top-header');

				$body.addClass('compact-header');
				self.smallHeader = true;
			}

		} else if (self.smallHeader) {
			$body.removeClass('compact-header');
			self.smallHeader = false;
		}
	}
    
    function finishTransition(e) {
        var $this = $(e.currentTarget);
        
        if ($(document.body).hasClass('compact-header')) {
            $('html:not(:animated),body:not(:animated)').animate({scrollTop: 27 }, config.ANIMATION_SPEED);
        } else {
            $('html:not(:animated),body:not(:animated)').animate({scrollTop: 0 }, config.ANIMATION_SPEED);
        }
    }
	
	/**
	 * List of the elements that we are goind to use in the module
	 */
	self.elements = {
		
	};
	
	self.smallHeader = false;
	
	/**
	 * Initialization method
	 */
	self.init = function () {
		
		// Start the list module
		if (catalogo.listado) {
			catalogo.listado.init();
		}
		
		// Start the map module
		if (catalogo.mapa) {
			catalogo.mapa.init();
		}
		
		// Start the species modusle
		if (catalogo.especies) {
			catalogo.especies.init();
		}
		
		// Start the species modusle
		if (catalogo.avistamentos) {
			catalogo.avistamentos.init();
		}
		
		// Start the points module
		if (catalogo.puntos) {
			catalogo.puntos.init();
		}
		
        // Start the points module
		if (catalogo.area) {
			catalogo.area.init();
		}
		
		// Scroll header
		if (catalogo.mapa) {
            
            $('nav.main').on('transitionend webkitTransitionEnd otransitionend', finishTransition);
            
			$('body').addClass('fixed-header');
			$('section.catalogo-mapa > header').addClass('content-header').appendTo($('div.top-header'));
			
			$(window).on('scroll', scroll);
		}
		
		if (window.location.hash) {
			$(window).trigger('hashchange');
		}
	};
	
	$(document).ready(self.init);
	return self;
	
}());