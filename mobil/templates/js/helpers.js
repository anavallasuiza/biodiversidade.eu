$.fn.geolocate = function (options) {
	if (!navigator.geolocation) {
		console.error('Geolocation not supported');
		return this;
	}

	if ($.isFunction(options)) {
		options = {success: options};
	}

	options = $.extend({success: $.noop, error: $.noop, before: $.noop}, options);

    // console.log(this);

	return this.each(function () {
		var $this = $(this);
		var success = $.proxy(options.success, $this);
		var error = $.proxy(options.error, $this);
		error = function (error) {
			console.log(error);
		};

		$this.click(function (event) {
			options.before.call($this, event);

			navigator.geolocation.getCurrentPosition(success, error);

			return false;
		});
	});
};
