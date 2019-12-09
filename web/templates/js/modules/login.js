/*jslint browser:true */
/*global google, $, mapas, config, common, console */

/**
 * Login module
 */
var login = (function () {
    'use strict';

    var self = {};

    function showRegisterForm(e) {

        $(self.elements.userLogin).slideUp(function () {
            $(self.elements.newUser).slideDown(function () {
                $(self.elements.buttonLogin).remove();
                $(self.elements.inputs).prop('required', 'required');

                $.fancybox.reposition();
            });
        });

        e.preventDefault();
    }

    self.elements = {
        linkRexistro: '#btn-rexistro',
        buttonLogin: '#btn-login',
        newUser: '#new-user',
        userLogin: '#user-login',
        inputs: '.formulario-login input:visible:not([type="checkbox"])',
        admins: '#rexistro-lista-admins',
        buttonImport: '#bt-import-data',
        importContentForm: '.info-solicitar-importar'
    };

    self.init = function () {
        $(document.body).on('click', self.elements.linkRexistro, showRegisterForm);

        $(document.body).on('click', self.elements.buttonImport, function(e){
            if ($(this).is(':checked')) {
                $(self.elements.importContentForm).css('display', 'block');
            } else {
                $(self.elements.importContentForm).css('display', 'none');
            }
        });
    };

    $(document).ready(self.init);
	return self;
}());
