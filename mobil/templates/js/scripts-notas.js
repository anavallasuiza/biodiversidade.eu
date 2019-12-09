(function () {
	var idNote,
		idPoint;

	var PageShow = {
		"page-notas": function (event) {
			idNote = undefined;
			
			var $lista = this.find('#notas-lista'),
				notes = Note.getNotes();

			if (notes.length) {
				var items = '';

				$.each(notes, function (k, note) {
					items += '<li><a href="#page-' + note.type + '" data-id="' + note.id + '">' + note.title + '</a></li>';
				});

				$lista.show().find('> ul').html(items);
			} else {
				$lista.hide().find('> ul').empty();
			}

			$lista.find('> ul').listview('refresh');
		},
		"page-observacion": function (event) {
			var note = new Note(idNote || $.now());
			
			note.type = 'observacion';

            note.attachForm(this.find('form.edicion-nota'));

            var $pointsList = this.find('#observacion-puntos');
            note.attachPointsList($pointsList);

            $pointsList.on('click', 'a', function () {
                idPoint = $(this).data('id');
            });

            $('#observacion-novo-punto').click(function () {
                idPoint = undefined;
            });

            this.data('note', note);

			/*note.setDefaultPoint('--- Crear punto ---');
			note.attachForm(this.find('form.edicion-nota'));
			note.attachPointsList(this.find('#observacion-puntos'));

			this.data('note', note);*/
		},
		"page-ameaza": function (event) {
			var note = new Note(idNote || $.now());

			note.type = 'ameaza';

			note.attachForm(this.find('form.edicion-nota'));

			var $pointsList = this.find('#ameaza-puntos');
			note.attachPointsList($pointsList);

			$pointsList.on('click', 'a', function () {
				idPoint = $(this).data('id');
			});

			$('#ameaza-novo-punto').click(function () {
				idPoint = undefined;
			});

			this.data('note', note);
		},
		"page-rota": function (event) {
			var note = new Note(idNote || $.now());

			note.type = 'rota';

			note.attachForm(this.find('form.edicion-nota'));

			var $pointsList = this.find('#rota-puntos');
			note.attachPointsList($pointsList);

			$pointsList.on('click', 'a', function () {
				idPoint = $(this).data('id');
			});

			$('#rota-novo-punto').click(function () {
				idPoint = undefined;
			});

			this.data('note', note);
		}
	};

	var PageHide = {
		"page-observacion": function (event) {
			var note = this.data('note');

			if (note) {
				note.close();
			}
		},
		"page-ameaza": function (event) {
			var note = this.data('note');

			if (note) {
				note.close();
			}
		},
		"page-rota": function (event) {
			var note = this.data('note');

			if (note) {
				note.close();
			}
		}
	};

	var PageCreate = {
		"page-notas": function (event) {
			var $lista = this.find('#notas-lista');

			$lista.on('click', 'a', function () {
				idNote = $(this).data('id');
			});

			$('.sincronizar').click(function () {
				var data = [];

				$.each(Note.getNotes(true), function (k, note) {
					data.push({
						"data": note.getData(),
						"type": note.type,
						"points": note.getPoints(),
						"id": note.id
					});
				});

				$.mobile.loader('show');

                console.log(data);

				$.ajax({
					url: $(this).data('href'),
					method: "post",
	                cache: false,
					data: {
						phpcan_action: "sincronizar",
						datos: JSON.stringify(data)
					},
					success: function (data) {
	                    if (data) { 
	                        var result = JSON.parse(data);
	                        
	                        if (result.goto) {
	                            window.location.href = result.goto;
	                            return false;
	                        }
	                    }
	                    
						alert('Datos sincronizados correctamente');

						$.mobile.loader('hide');
					},
					error: function () {
						alert('Erro sincronizando os datos');
						$.mobile.loader('hide');
					}
				});
			});
		},
		"page-observacion": function (event) {
			var that = this;

			this.find('#popup-punto-observacion').on('popupafteropen', function () {
				var $this = $(this),
					note = that.data('note'),
					point = new NotePoint(note, idPoint);

				point.attachForm($this.find('form.edicion-punto'));

				$this.data('point', point);

			}).on('popupafterclose', function () {
				var point = $(this).data('point');

				if (point) {
					point.close();
				}
			});
		},
		"page-ameaza": function (event) {
			var that = this;

			this.find('#popup-punto-ameaza').on('popupafteropen', function () {
				var $this = $(this),
					note = that.data('note'),
					point = new NotePoint(note, idPoint);

				point.attachForm($this.find('form.edicion-punto'));

				$this.data('point', point);

			}).on('popupafterclose', function () {
				var point = $(this).data('point');

				if (point) {
					point.close();
				}
			});
		},
		"page-rota": function (event) {
			var that = this;

			this.find('#popup-punto-rota').on('popupafteropen', function () {
				var $this = $(this),
					note = that.data('note'),
					point = new NotePoint(note, idPoint);

				point.attachForm($this.find('form.edicion-punto'));

				$this.data('point', point);

			}).on('popupafterclose', function () {
				var point = $(this).data('point');

				if (point) {
					point.close();
				}
			});
		}
	};

	$(document).on('pagebeforeshow pagebeforecreate pagebeforehide', function (event, ui) {
		var $target = $(event.target),
			id = $target.attr('id');

		switch (event.type) {
			case 'pagebeforeshow':
				if (PageShow[id]) {
					PageShow[id].call($target, event);
				}
				break;

			case 'pagebeforehide':
				if (PageHide[id]) {
					PageHide[id].call($target, event, ui);
				}
				break;

			case 'pagebeforecreate':
				if (PageCreate[id]) {
					PageCreate[id].call($target, event);
				}
				break;
		}
	});
})();
