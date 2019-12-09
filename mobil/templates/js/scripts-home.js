$(document).on('pageinit', function (event, ui) {
	if (!$(event.target).is('#page-home')) {
		return;
	}
	var $btnXeolocalizar = $('#buscar-proximos button');

	$btnXeolocalizar.geolocate({
		before: function (event) {
			$.mobile.loading('show');
		},
		success: function (result) {
			var href = this.data('href') + '?latitude=' + result.coords.latitude + '&longitude=' + result.coords.longitude;

			$.mobile.loading('hide');

			$.mobile.changePage(href, {
				transition: 'slide'
			});
		}
	});

	if (navigator.onLine === false) {
		$btnXeolocalizar.button('disable');
	}

	window.addEventListener('offline', function () {
		$btnXeolocalizar.button('disable');
	});
	window.addEventListener('online', function () {
		$btnXeolocalizar.button('enable');
	});
});
