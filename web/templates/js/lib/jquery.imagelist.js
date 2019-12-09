


(function($){

	"use strict";

	var FIGURE_CLASS = 'imagelist-picker';
	var FIGURE_NEW_CLASS = 'imagelist-new';

	var INPUT_CLASS = 'imagelist-input';
	var INPUT_NAME = 'imaxes[imaxe]';

	var CONTAINER_CLASS = 'imagelist-container';
	var IMAGE_PREVIEW_CLASS = 'imagelist-preview';
	var MESSAGE_CLASS = 'imagelist-message';

	var ACTIONS_CLASS = 'imagelist-actions';
	var BUTTON_PREVIEW_CLASS = 'imagelist-button-preview';
	var BUTTON_DELETE_CLASS = 'imagelist-button-delete';

	var CURRENT_ITEMS_CLASS = 'imagelist-current-item';

	var EVENT_ITEM_NEW_CREATED = 'imagelist.new-created';

	var IMAGES_DELETED_NAME = 'imaxes[borrar]';

	var newText = '<span>Add image</span>';

	var previewText = '<span>Preview</span>';
	var previewClass = '';

	var deleteText = '<span>Delete</span>';
	var deleteClass = '';

	var $newContent;
	var $base;

	var getNewItem = function() {

		var $element = $('<li><figure class="' + FIGURE_CLASS + ' ' + FIGURE_NEW_CLASS + '">' +
			'<input class="' + INPUT_CLASS + '" name="' + INPUT_NAME + '[]" type="file"/>' +
			'<div class="' + CONTAINER_CLASS + '">' +
				'<img class="' + IMAGE_PREVIEW_CLASS + '" />' + //onerror="imaxeErro(event);"/>' +
				'<div class="' + MESSAGE_CLASS + '">' +
					newText +
				'</div>' +
			'</div>' +
		'</figure></li>');

		$element.append($newContent.clone().children());

		

		return $element;
	};

	var changeCurrent = function($item) {

		var $img = $item.find('img');

		var $element = getNewItem(); 
		$element.find('.' + IMAGE_PREVIEW_CLASS).replaceWith($img);

		$item.html($element.find('figure').html());
		$item.find('.' + MESSAGE_CLASS).hide();
		$item.find('img').addClass(IMAGE_PREVIEW_CLASS);

		$item.addClass(CURRENT_ITEMS_CLASS);
		
		var $actions = $('<div class="' + ACTIONS_CLASS + '">' +
			'<button type="button" class="' + previewClass + ' ' + BUTTON_PREVIEW_CLASS + '">' + previewText + '</button>' + 
			'<button type="button" class="' + deleteClass + ' ' + BUTTON_DELETE_CLASS + '">' + deleteText + '</a>' + 
		'</div>');

		$actions.insertAfter($item);

		$base.trigger(EVENT_ITEM_NEW_CREATED, [$item]);
	};

	var changeNew = function(evt) {

		var $actions = $('<div class="' + ACTIONS_CLASS + '">' +
			'<button type="button" class="' + previewClass + ' ' + BUTTON_PREVIEW_CLASS + '">' + previewText + '</button>' + 
			'<button type="button" class="' + deleteClass + ' ' + BUTTON_DELETE_CLASS + '">' + deleteText + '</a>' + 
		'</div>');

		var $newItem = $base.find('.' + FIGURE_NEW_CLASS);
		$actions.insertAfter($newItem);

		var height = $actions.outerHeight();
		$actions.height(0);

		$actions.animate({'height': height + 'px'});

		$base.trigger(EVENT_ITEM_NEW_CREATED, [$newItem.parents('li').eq(0)]);

		$newItem.removeClass(FIGURE_NEW_CLASS);

		// Create the empty new image element
		var $newItem = getNewItem(newText)
		$base.append($newItem);

		var itemHeight = $newItem.outerHeight();
		$newItem.height(0);
		$newItem.animate({'height': itemHeight + 'px'});
	};

	var preview = function(e) {

		var target = e.target;
					
		if (target.files) {

			var archivo = target.files[0];
			var reader = new FileReader();
			var imagen = target.parentNode.querySelector('img');
			
			var nombre = archivo.name.split(/\./g);
			var extension = nombre[nombre.length - 1].toLowerCase();
			
			reader.onload = (function(file, img){
				
					return function(e){
						
							var newImg = document.createElement('img');
							newImg.src = e.target.result;
							newImg.title = file.name;
							newImg.className += IMAGE_PREVIEW_CLASS;
							
							img.parentNode.querySelector('.' + MESSAGE_CLASS).style.display = 'none';
							img.parentNode.replaceChild(newImg, img);

						};
				})(archivo, imagen);
			
			reader.readAsDataURL(archivo);

		} else {

			target.parentNode.querySelector('img').className = 'not-found';	
			
			var mensaje = target.parentNode.querySelector('.' + MESSAGE_CLASS);
			
			var ruta = target.value.split(/[\\\/]/g);
			mensaje.innerHTML = ruta[ruta.length - 1];
			mensaje.style.display = 'block';
		}
	};

	var remove = function(evt) {

		var $this = $(this);
		var $li = $this.parents('li').eq(0);

		if ($li.find('> figure').hasClass(CURRENT_ITEMS_CLASS)) {

			$li.append('<input type="hidden" name="' + IMAGES_DELETED_NAME + '[]" value="' + $li.data('id') + '">');
			
			//$li.addClass('removed');
			
			$li.hide();
			$li.trigger('imagelist.removed', [$li]);
		} else {
			$li.remove();
		}
	};

	var restore = function() {

		var $li = $(this);

		$li.find('input[type="hidden"][name="' + IMAGES_DELETED_NAME + '[]"]').remove();
		//$li.removeClass('removed');
		$li.show();

		$li.trigger('imagelist.restored', [$li]);		
	};

	var init = function(config) {

		var $this = $(this);
		$base = $this;
		$this.data('imaglist-config', config);

		if (config.newText) {
			newText = config.newText;
		}

		if (config.previewText) {
			previewText = config.previewText;
		}

		if (config.previewClass) {
			previewClass = config.previewClass;
		}

		if (config.deleteText) {
			deleteText = config.deleteText;
		}

		if (config.deleteClass) {
			deleteClass = config.deleteClass;
		}

		$newContent = $this.find('.imagelist-new').clone();
		$this.find('.imagelist-new').remove();

		// Inject image selection logic to each li
		$this.find('li > figure').each(function(){
			var $self = $(this);

			$self.addClass(FIGURE_CLASS);
			changeCurrent($self);
		});

		// Create the empty new image element
		var $newItem = getNewItem(config.newText)
		$this.append($newItem);

		if (config.preview) {
			$this.on('click', '.' + BUTTON_PREVIEW_CLASS, function(evt){
				var $this = $(this);
				var $img = $this.parents('li').eq(0).find('.' + IMAGE_PREVIEW_CLASS);

				config.preview.call(this, evt, $img)
			});
		}

		$this.on('change', '.' + INPUT_CLASS, preview);

		$this.on('change', '.' + FIGURE_NEW_CLASS + ' .' + INPUT_CLASS, changeNew);

		$this.on('click', '.' + BUTTON_DELETE_CLASS , remove);
	};

	var methods = {
		'restore': function($item) {
			restore.call($item);
		}
	};



	$.fn.imagelist = function (method) {

		if (methods[method]) {
			return methods[method].apply($(this), Array.prototype.slice.call(arguments, 1));
		} else if (method && method.constructor === Object) {
			return init.apply(this, arguments);
		}
	};

})(jQuery);

