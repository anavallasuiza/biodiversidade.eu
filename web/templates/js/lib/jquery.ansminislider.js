(function($) {
	$.fn.miniSlider = function (method) {

		var methods = {
			init: function (options) {
				var common_settings = $.extend({}, this.miniSlider.defaults, options);

				return this.each(function() {
					var $element = $(this), element = this, settings = $.extend({}, common_settings), offset = 0;

					if ($element.data('miniSlider')) {
						return;
					}

					$element.css({
						overflow: 'hidden'
					});

					settings.$tray = $element.children(settings.filter).first();

					if (settings.offset) {
						offset = '-' + settings.offset + 'px';
					}

					settings.$tray.css({
						position: 'relative',
						float: 'left',
						left: offset
					});

					if (settings.scroll) {
						settings.$window.css('overflow-x', 'scroll');
					}

					$element.data('miniSlider', settings);
				});
			},
			goto: function (steps) {
				return this.each(function () {
					var $this = $(this);
					if ($this.is(':hidden')) {
						return;
					}

					var settings = $this.data('miniSlider');
					var position = parseInt(settings.$tray.css('left'), 10);
					var duration = settings.duration * 2;
					var thisWidth = $this.width();
					var trayWidth = settings.$tray.width();

					if (trayWidth < thisWidth) {
						return;
					}
					
					steps = steps || 1;

					if ((typeof steps === 'string') && steps.indexOf('%') !== -1) {
						position -= (thisWidth / 100) * parseInt(steps, 10);
					} else if ((typeof steps === 'string') && steps.indexOf('px') !== -1) {
						position = parseInt(steps, 10) * -1;
					} else {
						position -= (steps * settings.stepWidth);
						duration = steps * settings.duration;
						
						if (duration < 0) {
							duration *= -1;
						}
					}

					if ((position + (trayWidth - thisWidth)) <= 0) {
						position = -(trayWidth - thisWidth);
					} else if (position > settings.start) {
						position = settings.start;
					}

					settings.$tray.animate({
						left: position
					}, {
						duration: duration,
						easing: settings.easing
					});
				});
			},
			center: function (index, offset) {
				return this.each(function () {
					var $this = $(this);
					var settings = $this.data('miniSlider');
					var thisWidth = $this.width();
					var posLeft = (index * settings.stepWidth) - (thisWidth/2) + offset;

					$this.miniSlider('goto', posLeft + 'px');
				});
			},
			gotoEnd: function () {
				return this.each(function () {
					var $this = $(this);
					var settings = $this.data('miniSlider');

					position = -(settings.$tray.outerWidth() - $this.width());
					settings.$tray.css('left', position);
				});
			},
			gotoFirst: function () {
				return this.each(function () {
					$(this).data('miniSlider').$tray.css('left', 0);
				});
			},
			option: function (name, value) {
				return this.each(function () {
					var $this = $(this);
					var settings = $this.data('miniSlider');

					settings[name] = value;
				});
			},
			hasOverflow: function () {
				var $this = $(this);

				if ($this.width() < $this.data('miniSlider').$tray.width()) {
					return true;
				}

				return false;
			}
		}

		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method "' +  method + '" does not exist in miniSlider plugin!');
		}
	}

	$.fn.miniSlider.defaults = {
		scroll: false,
		filter: '*',
		stepWidth: 100,
		duration: 200,
		easing: 'linear',
		offset: 0,
		start: 0
	};
})(jQuery);
