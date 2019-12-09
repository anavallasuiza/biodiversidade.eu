/*jslint browser:true */
/*global jQuery */

(function ($) {

	"use strict";

	var methods = {},
		BASE_CLASS = 'file-uploader',
		BUTTON_CLASS = 'upload-button',
		PREVIOUS_CLASS = 'previous-file',
		DELETED_CLASS = 'deleted',
		LINK_CLASS = 'upload-button-link',
		REMOVE_CLASS = 'upload-button-remove';
	
	function selectFile(evt) {
		
		var $input = $(evt.currentTarget),
			$base = $input.parents('.' + BASE_CLASS),
			$div = $base.find('.' + PREVIOUS_CLASS),
			$link = $div.find('a'),
			$checkbox = $base.find('input[type="checkbox"]');
		
		$div.show();
		
		$checkbox.attr('disabled', 'disabled');
		$div.removeClass(DELETED_CLASS);
		
		$link.attr('href', '#');
		$link.html($input.val());
        $link.on('click', function () { return false; });
	}
	
	function removeSelectedFile(evt) {
		var $button = $(evt.currentTarget),
            $base = $button.parents('.' + BASE_CLASS),
            $file = $base.find('input[type="file"]'),
			$parent = $button.parent(),
            $link = $parent.find('a'),
			$checkbox = $parent.find('input[type="checkbox"]');
		
        if ($file.attr('data-value')) {
            if ($parent.hasClass(DELETED_CLASS)) {
                $checkbox.removeAttr('checked');
                $checkbox.attr('disabled', 'disabled');
            } else {
                $checkbox.attr('checked', 'checked');
                $checkbox.removeAttr('disabled');
            }
            
            $parent.toggleClass(DELETED_CLASS);
        } else {
            $parent.hide();
            $parent.addClass(DELETED_CLASS);
            
            $checkbox.removeAttr('disabled', 'disabled');
            $link.html('');
            $file.replaceWith($file.clone(true));
        }
        
		return false;
	}
	
	function init(params) {
		
		this.each(function () {
		
			var $self = $(this),
				fileUrl = $self.data('value'),
				fileText = $self.data('text'),
				config = $.extend({}, $self.fileinput.config, params),
				$button,
				$base,
				$previous,
				$link,
				$remove,
				removeName,
				$checkbox,
				$fileButton,
                dontRemove = $self.attr('data-nodelete');
			
			// Set the html
			$self.wrap('<div class="' + BASE_CLASS + '"><div class="' + BUTTON_CLASS + '"></div></div>');
			
			$button = $self.parent();
			$base = $button.parent();
			
			$previous = $('<div class="' + PREVIOUS_CLASS + '"></div>').appendTo($base);
			$link = $('<a href="#" class="' + LINK_CLASS + '" target="_blank"></a>').appendTo($previous);
			
            if (!dontRemove) {
                $remove = $('<label class="' + REMOVE_CLASS + ' ' + config.removeButtonClass + '"></label>').appendTo($previous);
                
                $remove.append($(config.removeText).addClass('remove-icon'));
                $remove.append($(config.restoreText).addClass('restore-icon'));
                
                removeName = config.removeName || $self.attr('name');
                $checkbox = $('<input type="checkbox" name="' + removeName + '" value="' + config.removeValue + '" disabled/>');
                
                $checkbox.hide().appendTo($remove);
            }
			
			if (!fileUrl) {
				$previous.hide();
			} else {
				$link.attr('href', fileUrl);
				$link.html(fileText || fileUrl);
			}
			
			$fileButton = $('<button class="' + config.uploadButtonClass + '">' + config.uploadText + '</button>').insertBefore($self);
			
			// Event listeners
			$self.on('change', selectFile);
            
            if (!dontRemove) {
                $remove.on('click', removeSelectedFile);
            }
		});
	}
	
	$.fn.fileinput = function (method) {

		if (methods[method]) {
			return methods[method].apply($(this), Array.prototype.slice.call(arguments, 1));
		} else if (!method || (method && method.constructor === Object)) {
			return init.apply(this, arguments);
		}
	};
	
	$.fn.fileinput.config = {
		uploadText: '<i class="icon-upload"></i> Upload file',
		removeText: '<i class="icon-trash"></i>',
		restoreText: '<i class="icon-undo"></i>',
		
		uploadButtonClass: 'btn',
		removeButtonClass: 'btn',
		
		removeName: '',
		removeValue: 1
	};

}(jQuery));