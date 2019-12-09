$(document).on('pageinit', function (event, ui) {
	$(this).on('click', 'div[data-role="content"] a', function () {
		window.open(this.href);
		return false;
	});
});
