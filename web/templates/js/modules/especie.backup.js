/*jslint browser:true */
/*global especie, google, $, mapas, config, common, i18n, alert, confirm, console */

// Load empty base module if not present
if (!especie) {
	var especie = {};
}

/**
 * Modulo avistamentos
 */
especie.backup = (function () {
    
    "use strict";
    
    var self = {};
    
    self.elements = {
    };
    
    self.init = function () {
    };
    
    $(document).ready(self.init);
	return self;
}());