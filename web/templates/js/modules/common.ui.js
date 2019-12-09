/*jslint browser:true */
/*global google, $, jQuery, mapas, common, config, catalogo, Blob, BlobBuilder, console, alert, confirm, i18n */

/**
 * Common ui module, auto initializes
 */
common.ui = (function () {

	"use strict";

	var self = {}, init;

	/**
	 * Tab plugin
	 */
	function tabs () {
		return this.each(function (i, value) {
			var $this = $(value),
				$tabs = $this.find('> ul a, > ul .tab'),
				$contents = $this.find('> :not(ul)').hide(),
				$tab;

			$tabs.on('click', function () {
				var $this = $(this),
					id = $this.attr('href'),
					selector = $this.data('selector');

				if (selector) {
					$contents.not(selector).hide().trigger('tabHide');
					$contents.filter(selector).show().trigger('tabShow');
				} else {
					$contents.not(id).hide().trigger('tabHide');
					$contents.filter(id).show().trigger('tabShow');
				}

				$tabs.removeClass('selected');
				$this.addClass('selected');

				if (this.tagName === 'A') {
					return false;
				}
			});

			if (location.hash) {
				$tab = $tabs.filter('[href=' + location.hash + ']');
			}

			if (!$tab || !$tab.length) {
				$tab = $tabs.first();
			}

			$tab.click();
		});
	}

	/**
	 * Tree plugin
	 */
	function tree() {
		return this.each(function (i, value) {
			var $tree = $(value);

			$tree.find('li:not(.selected)').each(function (i, value) {
				var $ul = $(value).find(' > ul');
				$ul.hide();
			});

			$tree.on('click', 'li > span:not(.request)', function () {
				var $this = $(this),
					$ul = $this.siblings('ul');

				if ($ul.is(':visible')) {
					$ul.slideUp('fast');
					$this.parent().removeClass('selected');
					$this.find('i.icon-caret-down').removeClass('icon-caret-down').addClass('icon-caret-right');
				} else {
					$ul.slideDown('fast');
					$this.parent().addClass('selected');
					$this.find('i.icon-caret-right').addClass('icon-caret-down').removeClass('icon-caret-right');
				}
			});

			$tree.on('click', 'li > span.request:not(.cargando, .custom)', function (evt) {
				var $this = $(this),
					$ul = $this.next('ul'),
					$li = $this.parent('li'),
					codigo = $this.attr('data-codigo'),
					url = $this.attr('data-url'),
					$div,
					requestData,
					treeParams,
					key;

				if ($li.hasClass('selected')) {
					$ul.animate({'height': 0}, {
						queue: true,
						duration: 'fast',
						complete: function () {
							$this.parents('li').eq(0).removeClass('selected');
							$ul.html('');
							$ul.css('height', 'auto');

							$this.find('i.icon-caret-down').removeClass('icon-caret-down').addClass('icon-caret-right');
						}
					});

					return false;
				}

				$this.find('i.icon-caret-right').addClass('icon-caret-down').removeClass('icon-caret-right');
				$li.addClass('selected');

				$ul.html('<li class="cargando"><span><i class="icon-spinner icon-spin"></i> Cargando</span></li>');
				$ul.wrap('<div></div>');

				$div = $ul.parent();
				$div.css({'overflow': 'hidden', 'height': '0px'});

				$ul.show();

				$div.animate({'height': $ul.find('li').outerHeight() + 'px'}, {queue: true, duration: 'fast', complete: function () {}});

				requestData = {
					codigo: codigo
				};

				if ($tree.data('params')) {
					treeParams = $tree.data('params');

					for (key in treeParams) {
						if (treeParams.hasOwnProperty(key)) {
							requestData[key] = treeParams[key];
						}
					}
				}

				$.ajax({
					url: url,
					type: 'get',
					dataType: 'html',
					data: requestData,
					success: function (data, status, xhr) {

						$ul.html(data);

						// Show ul
						// ----------------

						var $dummy = $ul.clone().width($ul.width()).css('visibility', 'hidden');
						$dummy.find('li.cargando').remove();
						$(document.body).append($dummy);

						$div.animate({'height': $dummy.height() + 'px'}, {
							queue: true,
							duration: 'fast',
							complete: function () {
								$ul.unwrap().show();
							}
						});

						$dummy.remove();
						$tree.trigger('tree.load-data', [$this]);
					},
					error: function (xhr, status, error) {
						alert(i18n.TREE_ERROR);
					},
					complete: function () {
						$ul.find('li.cargando').remove();
					}
				});
			});
		});
	}

	function alert(text, type) {
		var $html = $('<div class="modal-alert alert-type-' + (type || 'info') + '">' + text + '<nav><button class="btn">' + i18n.ACCEPT + '</button></nav></div>');

		$html.find('button').on('click', function () {
			$.fancybox.close();
		});

		$.fancybox.open({
			href: $html,
			type: 'inline',
			modal: true,
			maxWidth: 300,
            minHeight: 20,
			closeBtn: true
		});
	}

	function confirm(text, ok, cancel) {
		var $html = $('<div class="modal-confirm">' + text + '<nav>' +
			'<button class="btn btn-cancel">'  + i18n.CANCEL +  '</button>' +
			'<button class="btn btn-ok">' + i18n.ACCEPT + '</button>' +
			'</nav></div>');

		$html.find('button').on('click', function (evt) {

			$.fancybox.close();

			if ($(evt.target).hasClass('btn-ok') && ok && ok.constructor === Function) {
				ok();
			} else if (cancel && cancel.constructor === Function) {
				cancel();
			}
		});

		$.fancybox({
			href: $html,
			type: 'inline',
			modal: true,
			closeBtn: false,
            minHeight: 20,
			afterClose: function () {}
		});
	}

	/**
	 * Select and image from the elastislide carousel
	 */
	function selectImage(evt) {
		var $this = $(evt.currentTarget);

		$('.main-imaxe a').attr('href', $this.attr('href'));
		$('.main-imaxe a img').attr('src', $this.data('recorte'));
        $('.main-imaxe small').html($this.parent().children('small').html());

		return false;
	}

	function showModal(evt) {
		$.fancybox({
			padding: 0,
			width: 300,
			modal: true,
			type: 'ajax',
			href: evt.currentTarget.href,
			helpers: {
				overlay: {
					showEarly: false
				}
			}
		});

		return false;
	}

	function submitDataTable() {
		var $form = $(this).closest('form'),
			$oTable = $form.find('table.datatable').dataTable(),
			inputs = '';

		if ($oTable.length) {

			$('input, select', $oTable.fnGetNodes()).each(function () {
				var checkbox = $(this).is(':checkbox, :radio');

				if ($(this).is(':hidden') && (!checkbox || (checkbox && $(this).is(':checked')))) {
					inputs += '<input type="hidden" name="' + $(this).attr('name') + '" value="' + $(this).attr('value') + '" />';
				}
			});

			$(inputs).appendTo($form);
		}

		return true;
	}

	function mostrarDesplegable(evt) {
		var $this = $(evt.currentTarget),
			$ul = $this.find('ul'),
			$icon = $this.find('i');

		if ($this.hasClass('disabled')) {
			return false;
		}

		if ($ul.is(':visible')) {
			$ul.hide();
			$icon.attr('class', 'right icon-caret-down');
		} else {
			$ul.focus().show();
			$icon.attr('class', 'right icon-caret-up');
		}
	}

	function ocultarDesplegable() {
		$(this).hide();
	}

	function seleccionarDesplegable(evt) {
		var $this = $(evt.target),
			$parent = $this.parents('.desplegable').eq(0),
			$ul = $parent.find('ul'),
			$span = $parent.find(' > span');

		$parent.attr('data-value', $this.attr('data-value'));
		$span.html($this.html());

		$parent.click();
		$parent.trigger('change');

		return false;
	}

	function parentChanged() {
		var $this = $(this);

		if ($this.attr('data-selected')) {
			$this.select2('val', $this.attr('data-selected'));
			$this.removeAttr('disabled').select2('enable');
			$this.trigger('change');
		}
	}

	function initSelects(i, value) {
		var $this = $(value);

		$this.select2({
			'width': 'resolve',
			'allowClear': true,
			'minimumResultsForSearch': 5
		});

        $this.on('select2-removed', function (e) {
            var $element = $(e.currentTarget);
            $element.removeAttr('data-selected');
            $element.removeAttr('data-values');
        });

		if ((($this.val() && $this.val().constructor !== Array) || $this.attr('data-selected'))) {
			$this.select2('data', {id: ($this.val() || $this.attr('data-selected')), text: $this.attr('data-text')}, true);
			$this.removeAttr('disabled').select2('enable');
			$this.trigger('change');
		} else if ($this.attr('data-values') && $this.attr('data-values').constructor !== Array) {
			$this.select2('val', JSON.parse($this.attr('data-values').replace(/'/ig, '"')));
		}
	}

	function confirmLink() {
		var $this = $(this);

		confirm($this.data('confirm'), function () {
			window.location.href = $this.attr('href');
		});

		return false;
	}

	function slideLinks(e) {
		$($(this).attr('href')).slideToggle();
		e.preventDefault();
	}

    function initGallery() {
        // Lightbox photo galleries
		$('a.gallery').colorbox({
			photo: true
		});

		// Configure and apply elastislide plugin
		$.Elastislide.defaults = config.ELASTISLIDE;
		$('#carousel').elastislide();
		$('#carousel').on('click', 'a', selectImage);

		// Anslider for galleries
		$('.slider').ansSlider({ buttons: '.boton-galeria' });
    }

    function viewMorePages(e) {
        var $this = $(e.currentTarget),
            $paxinacion = $this.parents('paxinacion').eq(0);

        return false;
    }

    function viewLessPages(e) {
        var $this = $(e.currentTarget),
            $paxinacion = $this.parents('paxinacion').eq(0);

        return false;
    }

    function goToPage(e) {
        var $this = $(e.currentTarget),
            $paxinacion = $this.parents('.paxinacion').eq(0),
            $list,
            $items,
            $current,
            currentPage,
            page,
            $new;

        if ($paxinacion.attr('data-list')) {
            $list = $('#' + $paxinacion.attr('data-list'));
            $items = $list.find('> *');
            $current = $list.find('> *:visible').eq(0);
            currentPage = $current.index() + 1;
            page = $this.attr('data-page');

            if (currentPage !== parseInt(page)) {
                if (page === 'prev') {
                    page = currentPage <= 1 ? 1 : currentPage - 1;
                } else if (page === 'next') {
                    page = currentPage >= $items.length ? $items.length : currentPage + 1;
                }

                $new = $items.eq(page - 1);

                $list.animate({'height': $current.outerHeight() + 'px'}, config.ANIMATION_SPEED);

                $current.fadeOut(config.ANIMATION_SPEED);

                $new.fadeIn(config.ANIMATION_SPEED, function () {
                    $list.animate({'height': $new.outerHeight() + 'px'}, config.ANIMATION_SPEED);
                });

                $paxinacion.find('a.selected').removeClass('selected');
                $paxinacion.find('a[data-page="' + page  + '"]').addClass('selected');

                if (page <= 1) {
                    $paxinacion.find('a[data-page="prev"]').addClass('selected');
                } else if (page >= $items.length) {
                    $paxinacion.find('a[data-page="next"]').addClass('selected');
                }
            } else {
                $list.animate({'height': $current.outerHeight() + 'px'}, config.ANIMATION_SPEED);
            }
        }

        return false;
    }

    function initEqualHeight() {
        $('[data-equalheight]').each(function() {
            var $this = $(this);
            var $elements = $($this.data('equalheight'), $this);

            $elements.height(Math.max.apply(null, $elements.map(function () { return $(this).height();}).get()));
        });
    }

    function checkAll () {
        var selector = $(this).val();

        if (selector === '') {
            return false;
        }

        var $base = $(selector);

        if ($base.length === 0) {
            return false;
        }

        var $form = $base.closest('form');
        var $checkbox = $('input[type="checkbox"]', $base);

        if ($checkbox.length === 0) {
            return true;
        }

        $checkbox.prop('checked', this.checked);
    }

    function applyFilterTab () {
    	var $this = $(this),
    		tabs = $this.closest('.tabs');

    	if (tabs.length === 0) {
    		return true;
    	}

    	var action = ($this.attr('action') || ''),
    		id = $this.closest('[id]').attr('id');

    	action = action.replace(/^[^#]*#/, '');

    	$this.attr('action', action + '#' + id);

    	return true;
    }

	/**
	 * Module initialization
	 */
	init = function () {
		// Create and apply tabs plugin
		jQuery.fn.tabs = tabs;
		$('.tabs:not(.tabs-page)').tabs();

		// Create and apply tree plugin
		jQuery.fn.tree = tree;
		$('.tree').tree();
		$('.tree li.selected span').trigger('click');

		// Alert plugin
		window._alert = window.alert;
		window.alert = alert;

		// Confirm plugin
		window._confirm = window.confirm;
		window.confirm = confirm;

		initGallery();

		// Ajax modal
		$('html').on('click', '.modal-ajax', showModal);
		$('html').on('click', '.ajax-close', function () { $.fancybox.close(); });

		// DataTable
		if ($.fn.dataTableExt) {
			$('table.datatable').dataTable({ 'aaSorting': [] });
		}

		if ($.fn.dataTable) {
			$('form.form-datatable button[type=submit]').on('click', submitDataTable);
		}

		// Depslegables html
		$('div.desplegable').on('click', mostrarDesplegable);
		$('div.desplegable ul').on('blur', ocultarDesplegable);
		$('div.desplegable ul li').on('click', seleccionarDesplegable);

		// Selects
		$('select').on('parent-changed', parentChanged);
		$('select').each(initSelects);

		// Enlaces con confirm
		$('a[data-confirm]').on('click', confirmLink);

		// Enlaces uqe despliegan contenido
		$(document).on('click', 'a.expand', slideLinks);

        $('.paxinacion-cliente').on('click', '.more-pages', viewMorePages);
        $('.paxinacion-cliente').on('click', '.less-pages', viewLessPages);
        $('.paxinacion-cliente').on('click', '[data-page]', goToPage);
        $('.paxinacion-cliente *[data-page="1"]').eq(0).trigger('click');

        $('input[type="checkbox"].checkall').on('click', checkAll);

        $('form.filtro').on('submit', applyFilterTab);

        $('.ckeditor').each(function (i, item) {
            var $item = $(item),
                required = $item.prop('required'),
                instance = CKEDITOR.replace(item, {toolbarGroups: config.CKEDITOR_TOOLBARS});

            if (required) {
                $item.attr('required', 'required');
                instance.on('change', function () {
                    if (instance.getData().trim()) {
                        $item.removeAttr('required');
                    } else {
                        $item.attr('required', 'required');
                    }
                });
            }
        });

        $(window).load(function () {
            initEqualHeight();
        });
	};

    self.loadGallery = function () {
        initGallery();
    };

	//init.apply(self);
	$(document).ready(function () { init.apply(self); });

	return self;
}());