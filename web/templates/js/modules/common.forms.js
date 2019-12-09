/*jslint browser:true */
/*global google, $, jQuery, mapas, common, config, catalogo, Blob, BlobBuilder, console, alert, confirm, i18n */

/**
 * Common ui module, auto initializes
 */
common.forms = (function () {
	"use strict";
	
	var self = {};
	
	function checkForm(evt) {
		var $target = $('#validation'),
            valid;
		
		$target.hide();

		valid = self.checkValidity.call($(evt.target).find('*[name]:not([disabled])'));

		if (!valid) {
			$target.show();
            $.scrollTo($('#validation'), config.ANIMATION_SPEED, {offset: {top: -10}});
		}

		return valid;
	}
	
	function formatNivelAmeaza(state) {
		if (!state.id) { // optgroup
            return state.text;
        }

		return '<i class="nivel-ameaza n' + state.id + '"></i>&nbsp;' + state.text;
	}
	
	/**
	 * Cargamos los datos del select en fucniona del parent
	 * @param {String} value Valor del select parents
	 * @param {String} url Url de la peticion de carga de los datos
	 * @param {Object} data Datos de la petición de carga
	 */
	function loadChildSelect(value, url, data, $parent) {
		var $child = $(this);

		$child.html('<option value=""></option>').val('').attr('disable', 'disable').trigger('change');
		$child.select2('enable', false);

        if (!$parent.val() && !$parent.attr('data-selected')) {
        
            $child.select2('val', '');
            $child.removeAttr('data-selected').trigger('change');

            return;
        }

        if (!value) {
            return;
        }

        $child.html('<option value="">' + i18n.LOADING + '</option>').data('placeholder', '');

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            data: data,
            success: function (data) {
                var url, nome, i, longData;
                
                $child.html('<option value=""></option>');
                
                for (i = 0, longData = data.length; i < longData; i += 1) {
                    if (data[i].url) {
                        url = data[i].url;
                        nome = data[i].nome;
                    } else {
                        url = data[i].nome.url;
                        nome = data[i].nome.title;
                    }

                    $child.append('<option value="' + url + '">' + nome + '</option>');
                }

                $child.trigger('parent-changed');
            },
            error: function () {
                $child.html('<option value=""></option>');
            },
            complete: function () {
                $child.removeAttr('disable');
                $child.select2('enable');
            }
        });
	}
	
	function initSelectEspecies(i, value) {
		var $this = $(value), multiple = $this.data('multiple') ? true : false;

		$this.select2({
			'minimumInputLength': 3,
			'width': 'resolve',
			'multiple': multiple,
			'allowClear': true,
			'ajax': {
				'url': config.URL_ESPECIES,
				'type': 'GET',
				'dataType': 'json',
				'data': function (term, page) {
					return { 'q': term, 'phpcan_exit_mode': 'json' };
				},
				'results': function (data, page) {
					return {results: data};
				}
			},
			'formatInputTooShort': function (term, minLength) {
				return i18n.PLEASE_ENTER + (minLength - term.length) + i18n.MORE_CHARS;
			},
			'dropdownCssClass': "bigdrop",
			'initSelection': function (element, callback) {
				var $this = $(element),
					values = $this.attr('data-values'),
					data,
					i,
					longValues;

				if (values) {
                    values = JSON.parse(values.replace(/\'/ig, '"'));

					for (data = [], i = 0, longValues = values.length; i < longValues; i += 1) {
						data.push({id: values[i].url, text: values[i].nome});
					}
				}

				callback(data);
			}
		});
        
        $this.on('select2-removed', function (e) {
            var $element = $(e.currentTarget);
            $element.removeAttr('data-selected');
            $element.removeAttr('data-values');
        });

		if (($this.val() || $this.attr('data-selected'))) {
			$this.select2('data', {id: ($this.val() || $this.attr('data-selected')), text: $this.attr('data-text')}, true);
			$this.removeAttr('disabled').select2('enable');
			$this.trigger('change');
		} else if ($this.attr('data-values') && $this.attr('data-values').constructor !== Array) {
			$this.select2('data', JSON.parse($this.attr('data-values').replace(/'/ig, '"')));
		}
	}
	
    function initSelectXeneros(i, value) {

        var $this = $(value);
        
		$this.select2({
			'minimumInputLength': 3,
			'width': 'resolve',
			'allowClear': true,
			'ajax': {
				'url': config.URL_XENEROS,
				'type': 'GET',
				'dataType': 'json',
				'data': function (term, page) {
					return { 'q': term, 'phpcan_exit_mode': 'json' };
				},
				'results': function (data, page) {
					return {results: data};
				}
			},
			'formatInputTooShort': function (term, minLength) {
				return i18n.PLEASE_ENTER + (minLength - term.length) + i18n.MORE_CHARS;
			},
			'dropdownCssClass': "bigdrop",
            'formatSelection': function (object) {
                
                console.log(object);
                $this.attr('data-grupo', object.grupos.url);
                $this.attr('data-clase', object.clases.url);
                $this.attr('data-orde', object.ordes.url);
                $this.attr('data-familia', object.familias.url);
                $this.attr('data-xenero', object.url);
                
                return object.text;
            }
		});
	}
    
	function changedFamilias() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_LISTADO_XENEROS,
			data = {'codigo': $this.val(), 'phpcan_exit_mode': 'json'};

        loadChildSelect.call($child, $this.val(), url, data, $this);
	}
	
	function changedOrdes() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_LISTADO_FAMILIAS,
			data = { 'codigo': $this.val(), 'phpcan_exit_mode': 'json' };

        loadChildSelect.call($child, $this.val(), url, data, $this);
	}
	
	function changedClasses() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_LISTADO_ORDES,
			data = { 'codigo': $this.val(), 'phpcan_exit_mode': 'json' };

        loadChildSelect.call($child, $this.val(), url, data, $this);
	}
	
	function changedReinos() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_LISTADO_CLASES,
			data = { 'codigo': $this.val(), 'phpcan_exit_mode': 'json'};

        loadChildSelect.call($child, $this.val(), url, data, $this);
	}
    
    function changedGrupos() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_LISTADO_CLASES,
			data = { 'codigo': $this.val(), 'phpcan_exit_mode': 'json'};

        loadChildSelect.call($child, $this.val(), url, data, $this);
	}
	
	function changedProvincias() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_CONCELLOS + $this.val(),
			data = { 'phpcan_exit_mode': 'json'};

		loadChildSelect.call($child, $this.val(), url, data, $this);
	}
	
	function changedTerritorios() {
		var $this = $(this),
			$child = $($this.data('child')),
			url = config.URL_PROVINCIAS + $this.val(),
			data = { 'phpcan_exit_mode': 'json'};

		loadChildSelect.call($child, $this.val(), url, data, $this);
	}
	
	function selectFile(evt) {
		var $input = $(evt.currentTarget),
			$div = $input.parent(),
			$link = $div.prev('a');
		
		if (!$link.length) {
			$link = $('<a class="upload-button-link">').insertBefore($div);
		}
		
		$link.attr('href', '#');
		$link.html($input.val());
	}
	
	function removeSelectedFile(evt) {
		var $button = $(evt.currentTarget),
			$parent = $button.parent();
		
		$parent.toggleClass('deleted');

		return false;
	}
    
    function gotoError(e) {
        var $this = $(e.currentTarget);
        
        if (window.history.pushState) {
            history.pushState({}, "", $this.attr('href'));
            $(window).trigger('hashchange');
            return false;
        }
    }
    
    //-- Imagelist    
	/**
	 * Reload select2 controls when the new item is created
	 * @method
	 * @private
	 * @param {Event} evt
	 * @param {jQuery} $element Current eleemtn being added
	 */
	function newImage(evt, $element) {
		var $licenza = $element.find('select.licenza');
		
		$element.find('.select2-container').remove();
		$element.find('select').select2({
			'width': 'resolve',
			'allowClear': true,
			'minimumResultsForSearch': 5
		});
		
		$element.attr('id', $licenza.attr('id') + '-element-' + $('.imagelist li').index($element));
		$licenza.attr('id', $licenza.attr('id') + '-' + $('.imagelist li').index($element));
		$licenza.attr('data-anchor', $element.attr('id'));

		$licenza.attr('required', 'required');
	}
	
	/**
	 * Preview the image being selected in full size modal window
	 * @method
	 * @private
	 * @param {Event} evt
	 * @param {jQuery} $img Image for the current item
	 */
	function previewImage(evt, $img) {
		$.fancybox($img.data('src') || $img.attr('src'));

		return false;
	}
	
	/**
	 * Remove image form the list
	 * @method
	 * @private
	 */
	function removeImage(evt, $item) {
		var $this = $item,
			$img = $this.find('figure img').clone(),
			$removed = $('<li class="image-removed" data-id="' + $this.data('id') + '"><figure></figure></li>'),
			$removedList = $('ul.imagelist-removed');
		
		$removed.find('figure').append($img);
		$removed.append('<a href="#" class="restaurar-imaxe">' + i18n.RESTORE_IMAGELIST  + '</a>');

		$removedList.append($removed);
		$removedList.parent().show();
	}
	
	/**
	 * Restore a removed image to the list
	 * @method
	 * @private
	 */
	function restoreImaxe(evt) {
		var $this = $(evt.currentTarget),
			$parent = $this.parents('li').eq(0),
			$removedList = $('.imaxes-eliminadas');

		$.fn.imagelist('restore', $('.imagelist li[data-id="' + $parent.data('id') + '"]'));

		$parent.remove();

		if ($removedList.find('li').length <= 0) {
			$removedList.hide();
		}

		return false;
	}
	
	/**
	 * After restore handler. Remove overlay after image is restore.
	 * @method
	 * @private
	 */
	function imageRestored(evt, $item) {
		$item.find('.overlay').remove();
	}
	
	/**
	 * Click handler for restore button. Invoke restore method for imagelist
	 * @method
	 * @private
	 */
	function imageRestoredButton(evt) {
		var $this = $(evt.currentTarget),
			$parent = $this.parents('li').eq(0);

		$.fn.imagelist('restore', $parent);
	}

    function imageCreated(e, $li) {
        $li.find('[disabled]').removeAttr('disabled');
    }
    
    function changeMainImage() {
        $(self.elements.imagelistLi).each(function (i, item) {
            var $item = $(item),
                $check = $item.find('.check-imaxe-principal'),
                checked = $check.find('input[type="radio"]').is(':checked');
            
            $check.find('input[type="hidden"]').val(checked ? '1' : '0');
        });
    }

    function initImagelist() {
        $(self.elements.imagelist).imagelist({
			newText: '<i class="icon-plus-sign"></i>',
			previewText: '<i class="icon-search"></i> <span>' + i18n.VIEW + '</span>',
			previewClass: 'btn',
			preview: previewImage,
			deleteText: '<i class="icon-trash"></i> <span>' + i18n.DELETE +  '</span>',
			deleteClass: 'btn'
		});
        
        // Imagelist events
		$(self.elements.imagelist).on('imagelist.new-created', newImage);
        $(self.elements.imagelist).on('imagelist.new-created', imageCreated);
		$(self.elements.imagelistLi).on('imagelist.removed', removeImage);
		$(self.elements.imagelistLi).on('imagelist.restored', imageRestored);
		
		$(self.elements.imagelistRemoved).on('click', 'a.restaurar-imaxe', restoreImaxe);
		$(self.elements.imagelistLi).on('click', 'button.restore-imagelist', imageRestoredButton);
        $(self.elements.imagelist).on('click', '.check-imaxe-principal input', changeMainImage);
    }
    
    function initAutocomplete(i, element) {
        var $element = $(element);

        $element.autocomplete({
            source: $element.attr('data-url'),
            delay: 150,
            minLength: 1,
            select: function (event, ui) {}
        });
    }
    
    function initSuggest(i, value) {
		var $this = $(value);

		$this.select2({
			minimumInputLength: 3,
            multiple: ($this.attr('multiple') ? true : false),
            width: 'resolve',
            ajax: {
                url: $this.data('url'),
                dataType: 'json',
                data: function (term) {
                    var data = {};
                    data[$this.data('search')] = term;
                    return data;
                },
                results: function (data) {
                    return {results: data};
                }
            }
        });

        if ($this.data('values') && ($this.data('values').constructor !== Array)) {
            $this.select2('data', JSON.parse($this.data('values').replace(/'/ig, '"')));
        }
	}
    
    function addItemToList(e) {
        var $this = $(e.currentTarget),
            $new = $this.parent(),
            css,
            $content = $new.find('.item-content'),
            $clone,
            $li;
        
        css = $new.attr('class').replace('new-item', '');
        
        $clone = $content.clone(true);
        $clone.wrap('<li class="item-created ' + css + '"/>');
        $clone.find('[name][disabled]').removeAttr('disabled');
        
        
        $clone.parent().insertBefore($new);
    }
    
    function removeItemFromList(e) {
        var $this = $(e.currentTarget),
            $li = $this.parents('li').eq(0);
        
        if ($li.hasClass('.item-created')) {
            $li.remove();
        } else {
            $li.hide();
            $li.find('.input-deleted').removeAttr('disabled');
        }
    }
    
    function initItemList(i, item) {
        
        var $this = $(item),
            $new = $this.find('.new-item'),
            $content = $new.find('.item-content'),
            $button = $new.find('button.item-add');
        
        $button.on('click', addItemToList);
        $this.on('click', 'button.item-remove', removeItemFromList);
        $this.find('.input-deleted').attr('disabled', 'disabled');
    }
	
	/**
	 * Modules initialization funtion
	 */
	function init() {
		// Initialize and apply custom form validation
		$('form.custom-validation').attr('novalidate', 'novalidate');
		$('form.custom-validation').on('submit validate', checkForm);
        
        $('.validation-errors').on('click', 'a', gotoError);
		
		// Date && datetime pickers
		$('input.datepicker').datepicker(config.DATEPICKER);
		$('input.datetimepicker').datetimepicker(config.DATETIMEPICKER);
		
		// Select de nivel de ameaza
		$('select.nivel-ameaza').select2({
			'width': 'resolve',
			'allowClear': true,
			'minimumResultsForSearch': 5,
			'formatResult': formatNivelAmeaza,
			'formatSelection': formatNivelAmeaza,
			'escapeMarkup': function (m) { return m; }
		});
		
		// Select de listaxe de especies
		$('.listaxe-especies').each(initSelectEspecies);
        $('.select-xeneros-completo').each(initSelectXeneros);
		
		// Selects de categorias
		$('.select-familias').on('change', changedFamilias);
		$('.select-ordes').on('change', changedOrdes);
		$('.select-clases').on('change', changedClasses);
		//$('.select-reinos').on('change', changedReinos).trigger('change');
        $('.select-grupos').on('change', changedGrupos).trigger('change');
		
		// Selects localizacion
		$('select.select-provincias').on('change', changedProvincias);
		$('select.select-territorios').on('change', changedTerritorios).trigger('change');
        
        $('.suggest').each(initSuggest);
        
		$('input[type="file"]').fileinput({
			uploadText: '<i class="icon-search"></i> ' + i18n.SELECT_FILE
		});
        
        $('input.autocomplete[data-url]').each(initAutocomplete);
        
        // Apply autofocus
        $(window).on('load', function () {
            window.setTimeout(function () { $('[autofocus]:visible').eq(0).focus(); }, 10);
        });
        
        initImagelist();
        
        $('.item-list').each(initItemList);
	}
	
    //Public properties
    self.elements = {
        imagelist: '.imagelist',
		imagelistLi: '.imagelist li',
		imagelistRemoved: '.imagelist-removed'
    };
    
	// Public methods
	
    self.loadImagelist = function () {
        initImagelist();
    };
    
	self.checkValidity = function (evt, list, messages) {
		var valid = true,
			$elements = $(this),
            $validation = (messages || $('#validation')),
			$list = (list || $('#validation ul.errors')),
			$parent,
			$span,
			$label,
			anchor,
			$clon,
			text;

		$elements.each(function (i, value) {
			var $this = $(value),
				anchor = $this.data('anchor') || $this.attr('id'),
				$parent = $this.parent();
			
			$list.find('li[data-id="' + $this.attr('id') + '"]').remove();
            
            //TODO: Instead of a global search i should look for the label and if there is one just search in the parent
			$('span.error[data-id="' + anchor + '"]').remove();

			$this.off('change', self.checkValidity);
						
			if (this.checkValidity() === false) {
				$span = $('<span class="error" data-id="' + anchor + '">' + this.validationMessage + '</span>');

				$label = $('label[for="' + $this.attr('id') + '"]');

				if ($label.length) {
					$label.append($span);
					
					$clon = $label.clone();
					$clon.find('span.error').remove();

					text = $clon.html();
				} else {
					$span.insertAfter($this);

					text = $this.attr('placeholder') || $this.attr('data-placeholder');
				}

				$list.append('<li data-id="' + $this.attr('id') + '"><a href="#' + anchor + '">' + text + '</a> - ' + this.validationMessage + '</li>');

				$this.on('change', self.checkValidity);

				valid = false;
			}
            
            if ($list.find('li').length > 0) {
                $validation.show();
            } else {
                $validation.hide();
            }
		});
        
        if (!valid) {
            $elements.eq(0).parents('form').eq(0).trigger('invalid-form');
        } else {
            $elements.eq(0).parents('form').eq(0).trigger('valid-form');
        }

		return valid;
	};
	
	$(document).ready(function () {
        init.apply(self);
    });

	return self;
}());