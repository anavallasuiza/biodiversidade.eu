/*jslint browser:true */
/*global especie, google, $, mapas, config, common, i18n, alert, confirm, console */

// Load empty base module if not present
if (!especie) {
	var especie = {};
}

/**
 * Modulo avistamentos
 */
especie.crear = (function () {
    
    "use strict";
    
    var self = {},
        checkEspecie = false;
    
    function confirmacion() {
        
        if ($(self.elements.listado).val()) {
            window.location.href = config.especie.URL_ESPECIE + $(self.elements.listado).val();
        } else {
            alert(i18n.especie.VALIDACION_EXISTENTE);
        }

        return false;
    }
    
    function showForm() {
        
        $(self.elements.confirmacion).fadeOut(config.ANIMATION_SPEED, function () {
            $(self.elements.form).fadeIn(config.ANIMATION_SPEED);
        });
    }
    
    function cambioTipo(e) {
        
        var $this = $(e.currentTarget),
            $nome = $(self.elements.nomeSubespecie);
        
        if ($this.val()) {
            $nome.attr('required', 'required');
        } else {
            $nome.removeAttr('required');
        }
    }
    
    function cambioNomeSubespecie(e) {
        var $this = $(e.currentTarget),
            $autor = $(self.elements.autorSubespecie);
        
        if ($this.val()) {
            $autor.attr('required', 'required');
        } else {
            $autor.removeAttr('required');
        }
    }
    
    function cambioNomeVariedade(e) {
        var $this = $(e.currentTarget),
            $autor = $(self.elements.autorVariedade);
        
        if ($this.val()) {
            $autor.attr('required', 'required');
        } else {
            $autor.removeAttr('required');
        }
    }
    
    function autocompleteEspecie(e, ui) {
        
        var autor = ui.item.autor;
        
        if (autor) {
            $(self.elements.autorEspecie).val(autor);
        }
    }
    
    function autocompleteSubespecie(e, ui) {
        var autor = ui.item.autor;
        
        if (autor) {
            $(self.elements.autorSubespecie).val(autor);
        }
    }
    
    function crearEspecieOk() {
        checkEspecie = true;
        $(self.elements.botonGardar).click();
    }
    
    function checkEspecieTpo(e) {
        
        var $overlay = $(self.elements.overlay),
            subespecie = $(self.elements.nomeSubespecie).val(),
            variedade = $(self.elements.nomeVariedade).val();
        
        if (!checkEspecie && (subespecie || variedade)) {
        
            $overlay.removeClass('hidden');
            
            $.ajax({
                url: config.especie.URL_ESPECIE_TIPO,
                type: 'get',
                dataType: 'json',
                data: {nome: $(self.elements.nomeEspecie).val() },
                cache: false,
                success: function (data, status, xhr) {
                    if (data.id) {
                        crearEspecieOk();
                    } else {
                        confirm(i18n.especie.CREAR_ESPECIE_TIPO, crearEspecieOk);
                    }
                },
                error: function (xhr, status, error) {
                    alert(i18n.especie.ERROR_CHECK_ESPECIE);
                },
                complete: function () {
                    $overlay.addClass('hidden');
                }
            });
            
            return false;
        }
        
        return true;
    }
    
    function updateXeneros(e) {
        
        var $this = $(e.currentTarget);
        
        if ($this.val()) {
            $(self.elements.xeneroCompleto).parent().addClass('hidden');
            $(self.elements.xenero).parent().removeClass('hidden');
        } else {
            $(self.elements.xeneroCompleto).parent().removeClass('hidden');
            $(self.elements.xenero).parent().addClass('hidden');
        }
        
        $(self.elements.xeneroCompleto).select2('val', '');
    }
    
    function updateCategorias(e) {
        var $this = $(e.currentTarget),
            $xeneroCompleto = $(self.elements.xeneroCompleto),
            $grupo = $(self.elements.grupo),
            $clase = $(self.elements.clase),
            $orde = $(self.elements.orde),
            $familia = $(self.elements.familia),
            $xenero = $(self.elements.xenero);
        
        if ($xeneroCompleto.val()) {
            $xenero.attr('data-selected', $xeneroCompleto.attr('data-xenero'));
            $familia.attr('data-selected', $xeneroCompleto.attr('data-familia'));
            $orde.attr('data-selected', $xeneroCompleto.attr('data-orde'));
            $clase.attr('data-selected', $xeneroCompleto.attr('data-clase'));
            $grupo.select2('val', $xeneroCompleto.attr('data-grupo')).trigger('change');
        } else {
            $xenero.removeAttr('data-selected');
            $familia.removeAttr('data-selected');
            $orde.removeAttr('data-selected');
            $clase.removeAttr('data-selected');
            $grupo.select2('val', '').trigger('change');
        }
    }
    
    self.elements = {
        existe: '#xa-existe-especie',
        nonExiste: '#non-existe-especie',
        
        listado: '#listaxe-especies-existentes',
        confirmacion: '#confirmacion-especie',
        form: '#formulario-especie',
        
        nomeEspecie: '#nome-especie',
        autorEspecie: '#autor-especie',
        
        nomeSubespecie: '#nome-subespecie',
        autorSubespecie: '#autor-subespecie',
        
        nomeVariedade: '#nome-variedade',
        autorVariedade: '#autor-variedade',
        
        nomeComun: '#nome-comun',
        overlay: '.overlay',
        
        botonGardar: '#boton-gardar',
        
        grupo: '#grupo',
        clase: '#clase',
        orde: '#orde',
        familia: '#familia',
        xenero: '#xenero',
        xeneroCompleto: '#xenero-completo'
    };
    
    self.init = function () {
        $(self.elements.existe).on('click', confirmacion);
        $(self.elements.nonExiste).on('click', showForm);
        
        $(self.elements.nomeEspecie).on("autocompleteselect", autocompleteEspecie);
        
        $(self.elements.nomeVariedade).on('change', cambioNomeVariedade);
        
        $(self.elements.nomeComun).select2(config.especie.TAGLIST_DEFAULT);
        
        $(self.elements.xenero).select2('enable');
        $(self.elements.familia).on('change', updateXeneros);
        
        $(self.elements.xeneroCompleto).on('change', updateCategorias);
        
        $(self.elements.form).on('submit', checkEspecieTpo);
    };
    
    $(document).ready(self.init);
	return self;
}());