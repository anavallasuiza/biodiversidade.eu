/*jslint browser:true, plusplus:true, devel:true */
/*global $, confirm, alert, Note */

(function () {
    
    'use strict';
    
	window.Note = function (id) {
		this.id = id;
		this.data = {};
		this.points = [];
		this.type = localStorage.getItem('Note-type-' + id);

		this.load();
	};

	window.Note.getNotes = function (instanced) {
		var notes = [];

		$.each(localStorage, function (name, value) {
            
            var type, id;
            
			if (name.indexOf('Note-data-') === 0) {
				id = name.substr(10);

				if (instanced) {
					notes.push(new Note(id));
				} else {
					type = localStorage.getItem('Note-type-' + id);

					value = JSON.parse(value);

					notes.push({
						type: type,
						id: id,
						title: value.title
					});
				}
			}
		});

		return notes;
	};

	window.Note.prototype = {
		setDefaultPoint: function (text) {
			this.defaultPoint = {text: text};
		},
		getData: function () {
			return this.data;
		},
		setData: function (data) {
            
            var i;
            
			if ($.isArray(data)) {
				this.data = {};

				for (i = data.length - 1; i >= 0; i--) {
					this.data[data[i].name] = data[i].value;
				}
			} else {
				this.data = data || {};
			}
		},
		getPoints: function () {
			return this.points;
		},
		setPoints: function (points) {
			this.points = points || [];
		},
		getPointData: function (id) {
			return this.points[id];
		},
		setPointData: function (id, data) {
			if (id === undefined) {
				this.points.push(data);

				return this.points.length - 1;
			}

			this.points[id] = data;

			return id;
		},
		removePointData: function (id) {
			this.points.splice(id, 1);
		},
		save: function () {
			var data = JSON.stringify(this.getData()),
                points = JSON.stringify(this.getPoints());

			localStorage.setItem('Note-data-' + this.id, data);
			localStorage.setItem('Note-points-' + this.id, points);
			localStorage.setItem('Note-type-' + this.id, this.type);
		},
		load: function () {
			var data = localStorage.getItem('Note-data-' + this.id),
                points = localStorage.getItem('Note-points-' + this.id);

			this.setData(JSON.parse(data));
			this.setPoints(JSON.parse(points));
		},
		remove: function () {
			localStorage.removeItem('Note-data-' + this.id);
			localStorage.removeItem('Note-type-' + this.id);
		},
		attachForm: function ($form) {
			var that = this,
                data;

			this.$form = $form.on('submit', function (e) {
				var data = $form.serializeArray();
				
				that.setData(data);
				that.save();

				window.history.back();

				return false;
			});

			$form.find('.remove').on('click', function () {
				if (confirm('Estas seguro que queres eliminar esta nota?')) {
					that.remove();
					window.history.back();
				}
			});

			data = this.getData();

			$form[0].reset();

			if (data) {
				$form.find(':input').each(function () {
					var $this = $(this),
						name = $this.attr('name');

					if (data[name]) {
						$this.val(data[name]);
					}
				});
			}
		},
		attachPointsList: function ($pointsList) {
			this.$pointsList = $pointsList;
			this.refreshPointsList();
		},
		refreshPointsList: function () {
			var that = this,
				html = '',
				points = this.getPoints(),
                text;

			if (!points.length && this.defaultPoint) {
				points = [this.defaultPoint];
			}

			$.each(points, function (k, point) {
                text = point.text || (point['lonlat-coords'] ? point['lonlat-coords'].latitude + ' / ' + point['lonlat-coords'].longitude : (point['mgrs-coords'] || '&nbsp;--&nbsp;'));
				html += '<li><a href="#popup-punto-' + that.type + '" data-rel="popup" data-position-to="window" data-id="' + k + '">' + text + '</a></li>';
			});

			this.$pointsList.html(html).listview('refresh');
		},
		close: function () {
			this.$form.find('.remove').off('click');
			this.$form.off('submit');
		}
	};


	window.NotePoint = function (note, id) {
		this.id = id;
		this.note = note;
		this.data = {};

		this.load();
	};

	window.NotePoint.prototype = {
		getData: function () {
			if (this.data["lonlat-coords"] && (typeof this.data["lonlat-coords"] === 'string')) {
				this.data["lonlat-coords"] = JSON.parse(this.data["lonlat-coords"]);
			}

			return this.data;
		},
		setData: function (data) {
            
            var i;
            
			if ($.isArray(data)) {
				this.data = {};

				for (i = data.length - 1; i >= 0; i--) {
					this.data[data[i].name] = data[i].value;
				}
			} else {
				this.data = data || {};
			}
		},
		save: function () {
			this.id = this.note.setPointData(this.id, this.getData());
		},
		load: function () {
			this.setData(this.note.getPointData(this.id));
		},
		remove: function () {
			this.note.removePointData(this.id);
		},
		attachForm: function ($form) {
			var that = this,
                $lonlatCoordsInput = $form.find('input[name="lonlat-coords"]'),
				$lonlatCoords = $form.find('.lonlat-coords').empty(),
				$mgrsType = $form.find('select[name="mgrs-type"]'),
				$mgrsCoords = $form.find('input[name="mgrs-coords"]'),
                data;

			this.$form = $form.on('submit', function (e) {
				var data = $form.serializeArray();
				that.setData(data);
				that.save();

				window.history.back();

				return false;
			});

			$form.find('.remove').on('click', function (e) {
                
                var $this = $(e.currentTarget);
                
                if (confirm($this.attr('data-confirm'))) {
                    that.remove();
                    window.history.back();
                }
			});

			$form.find('.btn-xeolocalizacion').geolocate({
				before: function () {
					$.mobile.loading('show');
				},
				success: function (coords) {
					$.mobile.loading('hide');
					$lonlatCoordsInput.val(JSON.stringify(coords.coords));
					$lonlatCoords.html(coords.coords.latitude + ' / ' + coords.coords.longitude);
					$mgrsType.val('');
					$mgrsCoords.val('');
				},
				error: function (error) {
					$.mobile.loading('hide');
					alert(error);
				}
			});

			$mgrsType.add($mgrsCoords).on('change', function () {
				$lonlatCoordsInput.val('');
				$lonlatCoords.empty();
			});

			data = this.getData();

			$form[0].reset();

			if (data) {
				$form.find(':input').each(function () {
					var $this = $(this),
						name = $this.attr('name');

					if (data[name]) {
						$this.val((typeof data[name] === 'string') ? data[name] : JSON.stringify(data[name]));
					}
				});
				if (data["lonlat-coords"]) {
					$lonlatCoords.html(data["lonlat-coords"].latitude + ' / ' + data["lonlat-coords"].longitude);
				}
			}
		},
		close: function () {
			this.$form.find('.remove').off('click');
			this.$form.off('submit');
			this.note.refreshPointsList();
		}
	};
}());
