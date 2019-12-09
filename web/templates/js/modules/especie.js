/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, alert, confirm, CKEDITOR, console */

/**
 * Modulo avistamentos
 */
var especie = (function () {

    "use strict";

    var self = {};

    function leaveWarning() {

        if ($(self.elements.section).hasClass('editable')) {
            return i18n.LEAVE_NO_SAVE;
        }
    }

    function createTagList($parent, id, value) {

        var $result = $('<input type="text" id="' + id + '" value="' + value  + '">'),
            defaults;

        $parent.html('').append($result);

        defaults = $.extend({}, config.especie.TAGLIST_DEFAULT);
        defaults.tags = value;
        defaults.width = '100%';
        $result.select2(defaults);
    }

    function valueIsInput($item) {

        var tagName = $item.prop('tagName');
        tagName = tagName ? tagName.toLowerCase() : '';

        return (tagName === 'input' || tagName === 'select' || tagName === 'textarea');
    }

    function getFieldName($item) {
        return $item.attr('placeholder') || $item.attr('data-placeholder') || $item.attr('title') || $item.attr('data-name');
    }

    function getCurrentData() {

        var i,
            longFields,
            result = {},
            $item,
            value,
            placeholder;

        for (i = 0, longFields = self.fields.length; i < longFields; i += 1) {

            $item = $('#' + self.fields[i]);

            if (valueIsInput($item)) {

                if ($item.attr('type') === 'checkbox') {
                    value = $item.prop('checked') ? 1 : 0;
                } else {
                    value = $item.val();
                }

            } else if (CKEDITOR.instances[self.fields[i]]) {
                value = CKEDITOR.instances[self.fields[i]].getData();
            } else {

                placeholder = $item.attr('data-placeholder');

                if (placeholder === $item.html().trim()) {
                    value = '';
                } else {
                    value = $item.attr('data-value') || $item.html();
                }
            }

            result[self.fields[i]] = value;
        }

        return result;
    }

    function setCurrentData() {
        var id,
            i,
            longFields,
            $item;

        for (i = 0, longFields = self.fields.length; i < longFields; i += 1) {

            id = self.fields[i];
            $item = $('#' + id);

            if ($item.length > 0) {
                if ($item.prop('tagName').toLowerCase() === 'select') {

                    $item.select2('val', self.data[id]);

                } else if (valueIsInput($item)) {

                    if ($item.prop('type') === 'checkbox') {

                        if (self.data[id]) {
                            $item.attr('checked', 'checked');
                        } else {
                            $item.removeAttr('checked');
                        }

                    } else {
                        $item.val(self.data[id]);
                    }

                    $item.trigger('change');

                } else {

                    $item.html(self.data[id]);
                    $item.trigger('change');
                }
            }
        }
    }

    function formatEditables(e) {

        var $this = $(e.currentTarget);

        window.setTimeout(function () {
            $this.html(e.currentTarget.innerText);
        }, 0);
    }

    function finishMediaTransition() {

        var $media = $(self.elements.media);

        $media.css({
            '-webkit-transition': 'none',
            '-moz-transition': 'none',
            '-o-transition': 'none',
            '-ms-transition': 'none',
            'transition': 'none'
        });

        $media.css('height', 'auto');
    }

    function startEdit() {
        var $buttonEditar = $(self.elements.botonEditar),
            $buttonGardar = $(self.elements.botonGardar),

            $textoNome = $(self.elements.textoNome),
            $textoAutor = $(self.elements.textoAutor),
            $textoSub = $(self.elements.textoSub),
            $textoSubAutor = $(self.elements.textoSubAutor),
            $textoVar = $(self.elements.textoVar),
            $textoVarAutor = $(self.elements.textoVarAutor),

            $media = $(self.elements.media),

            $external = $(self.elements.external),
            $lsid = $(self.elements.lsid),

            $extraNomes = $(self.elements.extraNomes),
            $extraComun = $extraNomes.find(self.elements.extraComun),
            $extraSinonimos = $extraNomes.find(self.elements.extraSinonimos),

            $textosFicha = $(self.elements.textosFicha),

            $textoValidacion = $(self.elements.textoValidacion),
            $datosValidacion = $(self.elements.datosValidados);

        $buttonEditar.html('<i class="' + $buttonEditar.data('icon') + '"></i> ' + $buttonEditar.data('cancel'));
        $buttonGardar.removeClass('hidden');

        $textoNome.trocar(config.especie.TROCAR_DEFAULTS);
        $textoAutor.trocar(config.especie.TROCAR_DEFAULTS);
        $textoSub.trocar(config.especie.TROCAR_DEFAULTS);
        $textoSubAutor.trocar(config.especie.TROCAR_DEFAULTS);
        $textoVar.trocar(config.especie.TROCAR_DEFAULTS);
        $textoVarAutor.trocar(config.especie.TROCAR_DEFAULTS);

        $textoSub.parent().removeClass('hidden');
        $textoVar.parent().removeClass('hidden');

        $(self.elements.textoTipoSub).each(function (i, item) {
            var $element = $(item);

            $element.html($element.attr('data-name'));
        });

        $(self.elements.infoNome).height(30);

        $(self.elements.listaCategorias).addClass('editable');

        $media.one('transitionend', finishMediaTransition);
        $media.one('webkitTransitionEnd', finishMediaTransition);
        $media.one('otransitionend', finishMediaTransition);

        $(self.elements.media).addClass('editable');
        $media.height($media.children().eq(1).outerHeight());

        $lsid.trocar(config.especie.TROCAR_DEFAULTS);

        createTagList($extraComun, 'nome_comun', $extraComun.html().trim() ? $extraComun.html().split(/,/ig) : []);
        createTagList($extraSinonimos, 'sinonimos', $extraSinonimos.html() ? $extraSinonimos.html().split(/,/ig) : []);

        if ($datosValidacion.length) {
            $textoValidacion.addClass('hidden');
            $datosValidacion.removeClass('hidden');
        }

        $textosFicha.attr('contentEditable', true);
        $textosFicha.each(function (i, item) {

            if (CKEDITOR.instances[item.id]) {
                CKEDITOR.instances[item.id].destroy();
            }

            CKEDITOR.inline(item, {
				toolbarGroups: [
					{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'paragraph',   groups: [ 'list', 'indent', 'align' ] },
                    { name: 'links' }
				]
			});
        });

        // Save the current data for the specie
        self.data = getCurrentData();

        $('.trocar-editable').on('blur', formatEditables);
        $('.trocar-editable').bind('paste', formatEditables);

        $(window).on('beforeunload', leaveWarning);
    }

    function clearValidation() {

        $(self.elements.validation).hide().find('ul').html('');
        $('span.error').remove();
    }

    function finishEdit() {
        var $buttonEditar = $(self.elements.botonEditar),
            $buttonGardar = $(self.elements.botonGardar),

            $textoNome = $(self.elements.textoNome),
            $textoAutor = $(self.elements.textoAutor),
            $textoSub = $(self.elements.textoSub),
            $textoSubAutor = $(self.elements.textoSubAutor),
            $textoVar = $(self.elements.textoVar),
            $textoVarAutor = $(self.elements.textoVarAutor),

            $media = $(self.elements.media),

            $external = $(self.elements.external),
            $lsid = $(self.elements.lsid),

            $extraNomes = $(self.elements.extraNomes),
            $extraComun = $extraNomes.find(self.elements.extraComun),
            $extraSinonimos = $extraNomes.find(self.elements.extraSinonimos),

            $ameaza = $(self.elements.nivelAmeaza),

            $textoProtexida = $(self.elements.textoProtexida),
            $protexida = $(self.elements.checkProtexida),

            $textosFicha = $(self.elements.textosFicha),
            instance,

            $textoValidacion = $(self.elements.textoValidacion),
            $datosValidacion = $(self.elements.datosValidados);

        $buttonEditar.html('<i class="' + $buttonEditar.data('icon') + '"></i> ' + $buttonEditar.data('edit'));
        $buttonGardar.addClass('hidden');

        $('.trocar-editable').off('blur', formatEditables);
        $('.trocar-editable').unbind('paste', formatEditables);

        $textoNome.trocar('destroy');
        $textoAutor.trocar('destroy');
        $textoSub.trocar('destroy');
        $textoSubAutor.trocar('destroy');
        $textoVar.trocar('destroy');
        $textoVarAutor.trocar('destroy');

        if (!$textoSub.html().trim()) {
            $textoSub.parent().addClass('hidden');
        }

        if (!$textoVar.html().trim()) {
            $textoVar.parent().addClass('hidden');
        }

        $(self.elements.textoTipoSub).each(function (i, item) {
            var $element = $(item);

            if ($element.next().html().trim() !== '') {
                $element.html($element.attr('data-name'));
            } else {
                $element.html('');
            }
        });

        $textoValidacion.removeClass('hidden');
        $datosValidacion.addClass('hidden');

        if ($datosValidacion.find('input:checked').length) {
            $textoValidacion.find(self.elements.textoValidada).removeClass('hidden');
            $textoValidacion.find(self.elements.textoNoValidada).addClass('hidden');
        } else {
            $textoValidacion.find(self.elements.textoValidada).addClass('hidden');
            $textoValidacion.find(self.elements.textoNoValidada).removeClass('hidden');
        }

        $(self.elements.infoNome).height(0);

        $(self.elements.listaCategorias).removeClass('editable');

        $media.css({
            '-webkit-transition': 'all 1s',
            '-moz-transition': 'all 1s',
            '-o-transition': 'all 1s',
            '-ms-transition': 'all 1s',
            'transition': 'all 1s'
        });

        $(self.elements.media).removeClass('editable');
        $media.removeAttr('style');

        $lsid.trocar('destroy');

        if ($lsid.html().trim()) {
            $external.removeClass('empty');
        } else {
            $external.addClass('empty');
        }

        $extraComun.html($extraComun.find('input').select2('val').join ? $extraComun.find('input').select2('val').join(',') : '');

        if ($extraComun.html()) {
            $extraComun.parent().removeClass('empty');
        } else {
            $extraComun.parent().addClass('empty');
        }

        $extraSinonimos.html($extraSinonimos.find('input').select2('val').join ? $extraSinonimos.find('input').select2('val').join(',') : '');
        if ($extraSinonimos.html()) {
            $extraSinonimos.parent().removeClass('empty');
        } else {
            $extraSinonimos.parent().addClass('empty');
        }

        $ameaza.find(self.elements.textoAmeaza).html($ameaza.find(self.elements.selectAmeaza + ' option:selected').html() || i18n.especie.SIN_AMEAZA);

        for (instance in CKEDITOR.instances) {
            if (CKEDITOR.instances.hasOwnProperty(instance)) {
                CKEDITOR.instances[instance].destroy();
            }
		}

        if ($protexida.prop('checked')) {
            $textoProtexida.find('strong').html($protexida.attr('value'));
            $textoProtexida.parent().removeClass('empty');
        } else {
            $textoProtexida.find('strong').html('');
            $textoProtexida.parent().addClass('empty');
        }

        $textosFicha.attr('contentEditable', false);

        clearValidation();
        $(window).off('beforeunload', leaveWarning);
    }

    function toggleEditar() {

        var $section = $(self.elements.section);

        if ($section.hasClass('editable')) {
            setCurrentData();
            finishEdit();
        } else {
            startEdit();
        }

        $section.toggleClass('editable');
    }

    function historicoAnterior() {

        var $list = $(self.elements.listadoCambios),
            $current = $(self.elements.listadoCambios + ' ul:visible'),
            $next = $current.next(),
            height = $current.outerHeight();

        if ($next.length) {
            $current.addClass('hidden');
            $next.removeClass('hidden');

            if (height > $next.outerHeight()) {
                $next.css('min-height', height + 'px');
            }
        }

        if (!$next.next().length) {
            $(self.elements.historicoAnterior).hide();
        } else {
            $(self.elements.historicoAnterior).show();
        }

        $(self.elements.historicoSiguiente).show();

        return false;
    }

    function historicoSiguiente() {
        var $list = $(self.elements.listadoCambios),
            $current = $(self.elements.listadoCambios + ' ul:visible'),
            $prev = $current.prev(),
            height = $current.outerHeight();

        if ($prev.length) {
            $current.addClass('hidden');
            $prev.removeClass('hidden');

            if (height > $prev.outerHeight()) {
                $prev.css('min-height', height + 'px');
            }
        }

        if (!$prev.prev().length) {
            $(self.elements.historicoSiguiente).hide();
        } else {
            $(self.elements.historicoSiguiente).show();
        }

        $(self.elements.historicoAnterior).show();

        return false;
    }

    /**
     * Handle the change in the tabs
     * @private
     * @param {Event} e Dispatched event
     */
    function cambioTab(e) {

        var $this = $(e.currentTarget),
            $content = $this.find('> section:visible');

        if ($(self.elements.section).hasClass('editable')) {

            $content.find(self.elements.textosFicha).each(function (i, item) {

                item.contentEditable = true;

                if (CKEDITOR.instances[item.id]) {
                    CKEDITOR.instances[item.id].destroy();
                }

                CKEDITOR.inline(item, {
                    toolbarGroups: [
                        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph',   groups: [ 'list', 'indent', 'align' ] },
                        { name: 'links' }
                    ]
                });
            });
        }
    }

    function showError($item, errorMessage) {

        var $validation = $(self.elements.validation),
            $list = $validation.find('ul'),
            $li,
            $label,
            $span;

        $validation.show();

        $li = $('<li data-id="' + $item.attr('id') + '"><a href="#' + $item.attr('id') + '">' + getFieldName($item) + '</a> - ' + errorMessage + '</li>');
        $list.append($li);

        $label = $('label[for="' + $item.attr('id')  + '"]');
        $span = $('<span class="error">' + errorMessage  + '</span>');

        if ($label.length) {
            $label.append($span);
        } else {
            $item.parent().append($span);
        }
    }

    function validate() {

        var i,
            longFields,
            $item,
            item,
            value,
            isInput,
            valid = true;

        clearValidation();

        // Validate imagelist
        valid = common.forms.checkValidity.call($(self.elements.media).find('*[name]'));

        // Validate inline fields
        for (i = 0, longFields = self.fields.length; i < longFields; i += 1) {

            $item = $('#' + self.fields[i]);
            isInput = valueIsInput($item);

            value = isInput ? $item.val() : $item.html();

            if ((!value || value === '') && $item.data('required')) {
                valid = false;
                showError($item, i18n.especie.CAMPO_OBRIGATORIO);

            } else if (isInput) {

                item = $item[0];

                if (item.checkValidity() === false) {
                    valid = false;
                    showError($item, item.validationMessage);
                }
            }
        }

        if (!valid) {
            $(self.elements.validation).show();
            $.scrollTo($(self.elements.validation), config.ANIMATION_SPEED, {offset: {top: -10}});
        } else {
            clearValidation();
        }

        return valid;
    }

    /**
     *
     */
    function prepareForm() {

        var i,
            longFields,
            id,
            result = {},
            $input,
            $form = $(self.elements.form);

        $form.find(self.elements.formInput).remove();
        $form.find(self.elements.formIframe).remove();

        for (i = 0, longFields = self.fields.length; i < longFields; i += 1) {

            id = self.fields[i];

            $input = $('<input type="hidden" class="form-input" name="especies[' + id + ']" value=""/>');
            $input.val(self.data[id] || self.data[id] === 0 ? self.data[id] : '');
            $input.appendTo($form);
        }

        $input = $('<input type="hidden" class="form-input" name="origin" value="iframe"/>');
        $input.appendTo($form);

        $input = $('<input type="hidden" class="form-input" name="phpcan_action" value="especie-gardar"/>');
        $input.appendTo($form);

        $('<iframe id="form-iframe" name="form-iframe" class="hidden"></iframe>').insertAfter($form);
        $form.attr('target', 'form-iframe');

        return true;
    }

    /**
     *
     */
    function showLoading() {
        $(self.elements.overlay).removeClass('hidden');
    }

    function hideLoading() {
        $(self.elements.overlay).addClass('hidden');
    }

    function save(e) {
        var $input,
            $this = $(e.currentTarget),
            $form = $(self.elements.form);

        if (!$(self.elements.section).hasClass('editable')) {
            alert(i18n.especie.ERROR_FORM_NO_EDITABLE);
            return false;
        }

        if ($this.attr('id') !== $form.attr('id')) {
            if (validate()) {
                $input = $(self.elements.imagelist).find('.imagelist-new input');
                self.data  = getCurrentData();

                prepareForm();
                showLoading();

                $input.attr('disabled', 'disabled');

                $form.submit();

                $input.removeAttr('disabled');

            } else {
                $.scrollTo($(self.elements.validation), config.ANIMATION_SPEED, {offset: {top: -10}});
            }

            return false;
        }
    }

    function checkDataEspecie(especie) {

        var url = $(self.elements.article).attr('data-url'),
            path,
            i,
            longImaxes,
            imaxe,
            urlImaxes;

        if (url !== especie.url) {

            path = config.especie.URL_BASE_ESPECIE + especie.url;

            if (history.pushState) {
                history.pushState({}, especie.nome, path);
            } else {
                location.href = path;
            }
        }

        // Reload the full gallery with and ajax request
        urlImaxes = config.especie.URL_BASE_GALERIA + url;

        $.ajax({
            url: urlImaxes,
            type: 'get',
            dataType: 'html',
            data: {},
            cache: false,
            success: function (data, status, xhr) {
                $(self.elements.media).replaceWith($(data));

                // Reinitialize controls
                common.ui.loadGallery();
                common.forms.loadImagelist();
            },
            error: function (xhr, status, error) {
                alert(i18n.especie.ERROR_UPDATE_IMAGES);
            },
            complete: function () {
                hideLoading();
                toggleEditar();
            }
        });
    }

    function handleMessage(e) {

        var evt = e.originalEvent,
            data;

        // Just handle meesage for this domain
        if (evt.origin === window.location.origin) {

            // REmove newlines and all that stuff and then parse
            data = JSON.parse(evt.data.replace(/\n|\r|\t/ig, ''));

            if (data.result === 'ko') {
                alert(data.message);
                hideLoading();
                toggleEditar();
            } else if (data.result === 'ok') {
                checkDataEspecie(data.especie);
            } else {
                alert(i18n.especie.ERROR_GARDAR_ESPECIE);
                hideLoading();
                toggleEditar();
            }
        }
    }



    function cambioProtexida(e) {
        var $this = $(e.currentTarget),
            $catalogo = $(self.elements.catalogoProteccions),
            $texto = $(self.elements.textoProteccions),
            $select = $(self.elements.selectProteccions);

        if ($this.prop('checked')) {
            $catalogo.removeClass('hidden');

        } else {
            $catalogo.addClass('hidden');
            $texto.find('strong').html('');
        }
    }

    function cambioProteccions(e) {
        var $this = $(e.currentTarget),
            $catalogo = $(self.elements.catalogoProteccions),
            $texto = $(self.elements.textoProteccions),
            data = $this.select2('data'),
            i,
            longData,
            textos = [];

        if (data.length) {
            $catalogo.removeClass('empty');

            for (i = 0, longData = data.length; i < longData; i += 1) {
                textos.push(data[i].text.trim());
            }

            $texto.html(textos.join(', '));
        } else {
            $catalogo.addClass('empty');
            $texto.html('');
        }
    }



    self.elements = {
        botonEditar: '#boton-editar',
        botonGardar: '.boton-gardar',
        botonCancelar: '.boton-cancelar',

        section: 'section.content',
        form: '#editar-especie',
        article: '#editar-especie',

        nome: '#nome-especie',
        textoNome: '.texto-nome',
        textoAutor: '.texto-autor',
        textoSub: '.texto-sub',
        textoSubAutor: '.texto-sub-autor',
        textoVar: '.texto-var',
        textoVarAutor: '.texto-var-autor',
        textoTipoSub: '.texto-tipo-sub',

        infoNome: '#info-nome',

        listaCategorias: '.especie-clasificacion',
        categoriaNome: '.nome-categoria',
        categoriaSelect: '.select-categoria',

        media: '.media',
        imagelist: '.imagelist',
		imagelistLi: '.imagelist li',
		imagelistRemoved: '.imagelist-removed',

        external: '#external',
        lsid: '#lsid_name',

        extraNomes: '#extra-nomes',
        extraComun: '.item-nomes-comuns div',
        extraSinonimos: '.item-sinonimos div',

        nivelAmeaza: 'div.nivel-ameaza',
        textoAmeaza: '.texto-ameaza',
        selectAmeaza: '#nivel-de-ameaza',

        textoProtexida: '.proteccion .texto-proteccions',
        checkProtexida: '#protexida',
        catalogoProteccions: '.catalogos-proteccion',
        textoProteccions: '#proteccions-tipos-texto',
        selectProteccions: '#proteccions',

        historico: '.historico',
        listadoCambios: '.listado-cambios',
        historicoAnterior: '#historico-anterior',
        historicoSiguiente: '#historico-siguiente',

        tabsTextos: '.tabs',
        textosFicha: '.texto-ficha',

        validation: '#validation',
        textoValidacion: '.texto-validacion',
        datosValidados: '.datos-validados',
        textoValidada: '.estado.solucionada',
        textoNoValidada: '.estado.activa',

        formInput: 'input.form-input[type="hidden"]',
        formIframe: '#form-iframe',

        overlay: '.overlay-save'
    };

    self.fields = [
        'nome_cientifico',
        'autor',
        'subespecie',
        'subespecie_autor',
        'variedade',
        'variedade_autor',
        'sinonimos',
        'nome_comun',
        'lsid_name',
        'grupos',
        'clases',
        'ordes',
        'familias',
        'xeneros',
        'protexida',
        'proteccions',
        'descricion',
        'cromosomas',
        'fenoloxia',
        'distribucion',
        'habitat',
        'poboacion',
        'ameazas',
        'conservacion',
        'agradecementos',
        'observacions',
        'bibliografia'
    ];

    self.data = {};

    self.version = {};

    self.init = function () {

        $(self.elements.botonEditar).on('click', toggleEditar);
        $(self.elements.botonGardar).on('click', save);
        $(self.elements.form).on('submit', save);
        $(self.elements.botonCancelar).on('click', toggleEditar);

        $(self.elements.historicoAnterior).on('click', historicoAnterior);
        $(self.elements.historicoSiguiente).on('click', historicoSiguiente);

        $(self.elements.listadoCambios + ' ul:visible').css('min-height', $(self.elements.listadoCambios + ' ul:visible').outerHeight());
        $(self.elements.tabsTextos).on('tabShow', cambioTab);

        $(self.elements.checkProtexida).on('change', cambioProtexida).trigger('change');
        $(self.elements.selectProteccions).on('change', cambioProteccions).trigger('change');

        $(self.elements.categoriaSelect + ' select').each(function (i, item) {

            var $item = $(item);

            $item.on('change', function (e) {
                var $this = $(e.currentTarget),
                    $parent = $item.parents('li').eq(0),
                    $nome = $parent.find(self.elements.categoriaNome);

                $nome.html('<i class="icon-caret-right"></i> ' + $this.val());
            });
        });

        $(window).on('message', handleMessage);
    };

    self.isValid = function () {
        return validate();
    };

    self.saved = function () {

        finishEdit();
    };

    $(document).ready(self.init);
	return self;
}());