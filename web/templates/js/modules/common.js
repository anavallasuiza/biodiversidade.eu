/*jslint browser:true */
/*global google, $, mapas, common, config, catalogo, Blob, BlobBuilder, console */

/**
 * Common module, auto initializes
 */
var common = (function () {

	'use strict';

	var self = {}, init;

	var indexOf = function (searchElement, index) {

		var t, len, n, k;

		if (!this) {
			throw new TypeError();
		}

		t = Object(this);
		len = t.length >>> 0;

		if (len === 0) {
			return -1;
		}

		n = 0;
		if (arguments.length > 1) {
			n = Number(index);
			if (n != n) { // shortcut for verifying if it's NaN
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}

		for (k = ((n >= 0) ? n : Math.max(len - Math.abs(n), 0)); k < len; k += 1) {
			if (t.hasOwnProperty(k) && t[k] === searchElement) {
				return k;
			}
		}

		return -1;
	};

	function hashScroll() {
		var anchor = location.hash || $('li.tabs a').first().attr('href');
		$('a[href=' + anchor + ']').click();

        scrollToHash();
	}

	function scrollToHash() {

		var $target = $(window.location.hash),
            $element,
			offset = parseInt($target.attr('data-offset'), 10) || -10;

        // TODO: Replace the select2 class with something cooler and fancier
        if (!$target.is(':visible') || $target.hasClass('select2-offscreen')) {
            $element = $target;
            $target = $('label[for="' + window.location.hash.replace('#', '') + '"]');

            if (!$target.length) {
                $target = $element.parent();
            }
        }

		if ($target.length && $target.is(':visible')) {
			$.scrollTo($target, config.ANIMATION_SPEED, {offset: {top: offset}});
			return false;
		}
	}

	/**
	 * Module initialization
	 */
	init = function () {

		// Load indexOf fallback 4 ie
		window.indexOf = indexOf;

		// Ajax navegation
		$(window).on('popstate', hashScroll);

		// Scroll to hash if we have new one
		$(window).on('hashchange', scrollToHash);

	};

	init.apply(self);
	return self;

}());