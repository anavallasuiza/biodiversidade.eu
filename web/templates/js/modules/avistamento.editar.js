/*jslint browser:true */
/*global avistamento, google, $, mapas, config, common, i18n, alert, confirm, console */

// Load empty base module if not present
if (!avistamento) {
	var avistamento = {};
}

/**
 * Modulo avistamentos
 */
avistamento.editar = (function () {

	'use strict';

	/**
	 * Public interface
	 * @property {Object}
	 * @private
	 */
	var self = {};


	//-- Private
	//-----------------------

    function geocoderLoading() {
        var $tab = $(avistamento.editar.elements.tabs + ' > section:visible'),
            $direccion = $tab.find('span.direccion-aproximada');

        $direccion.html('<i class="icon-spin icon-spinner"></i> ' + i18n.LOADING);
    }

    function geocoderKo(status) {

        var $tab = $(avistamento.editar.elements.tabs + ' > section:visible'),
            $direccion = $tab.find('span.direccion-aproximada'),
            $button = $tab.find(self.elements.butonUseAddres);

        $button.attr('disabled', 'disabled');

        if (status !== google.maps.GeocoderStatus.ZERO_RESULTS) {
            alert(i18n.avistamento.ERROR_GEOCODER + ' (' + status  + ')');
        }

        $direccion.html('');
    }

    function getGeocoderDataByType(components, type) {

        var i,
            component;

        for (i = 0; i < components.length; i += 1) {
            component = components[i];

            if (component.types.indexOf('political') >= 0 && component.types.indexOf(type) >= 0) {
                return component;
            }
        }
    }

    function getPointElevation(latLng, callback, error) {

        var elevator = new google.maps.ElevationService(),
            data = {'locations': [latLng]};

        elevator.getElevationForLocations(data, function (results, status) {

            if (status === google.maps.ElevationStatus.OK) {
                if (results[0]) {
                    callback.call(self, Math.round(results[0].elevation));
                } else {
                    alert(i18n.avistamento.ERROR_ELEVACION);
                }
            } else {
                alert(i18n.avistamento.ERROR_ELEVACION + '(' + status + ')');
            }
        });
    }

    function formatComponent(component, comunidade) {

        var result = component,
            sustitucion,
            i;

        if (comunidade) {
            result = result.replace(config.avistamento.FILTRO_COMUNIDADE_PT, '').trim();
        } else {
            result = result.replace(/^(A\s|as\s|o\s|os\s|la\s|las\s|el\s|los\s)/ig, '').trim();
        }

        for (i = 0; i < config.avistamento.SUSTITUCIONS_DIRECCION.length; i += 1) {
            sustitucion = config.avistamento.SUSTITUCIONS_DIRECCION[i];
            result = result.replace(new RegExp(sustitucion.from), sustitucion.to);
        }

        return result;
    }

    function getGeocoderResult(result) {

        var components = result.address_components,
            i,
            provincia,
            concello,
            data = {};

        data.pais = getGeocoderDataByType(components, 'country').long_name;
        provincia = getGeocoderDataByType(components, data.pais === 'Portugal' ? 'administrative_area_level_1' : 'administrative_area_level_2');

        if (provincia) {
            data.provincia = formatComponent(provincia.long_name, true);
        }

        concello = getGeocoderDataByType(components, 'locality');

        if (concello) {
            data.concello = formatComponent(concello.long_name);
        }

        return data;
    }

    function geocoderOk(results) {

        var $tab = $(avistamento.editar.elements.tabs + ' > section:visible'),
            $direccion = $tab.find('span.direccion-aproximada'),
            $button = $tab.find(self.elements.butonUseAddres);

        $button.removeAttr('disabled');
        $direccion.html(results[0].formatted_address);
        $direccion.data('components', getGeocoderResult(results[0]));
    }

	/**
	 * Event handler for the unknown specie check change event
	 * @method
	 * @private
	 *
	 * @param {Event} evt
	 */
	function desconocida(evt) {

		var $this = $(evt.currentTarget),
			$campoNome = $('#nome-desconhecida'),
			$campoEspecie = $('#selector-especie'),
			$nome = $('#especie_desconhecida'),
			$especie = $('#especie');

		if ($this.prop('checked')) {
			$campoNome.slideDown('fast');
			$campoEspecie.slideUp('fast');

			$nome.attr('required', 'required').removeAttr('disabled');
			$especie.removeAttr('required').attr('disabled', 'disabled');

			common.forms.checkValidity.call($especie); // ELiminamos da lista de erros

		} else {
			$campoNome.slideUp('fast');
			$campoEspecie.slideDown('fast');

			$especie.attr('required', 'required').removeAttr('disabled');
			$nome.removeAttr('required').attr('disabled', 'disabled');

			common.forms.checkValidity.call($nome); // Eliminamos da lista de erros
		}
	}


	function hidePointErrors() {

		$(self.elements.pointFormErrors).hide();
		$(self.elements.pointFormErrors + ' ul').html('');
	}

	//-- Geolocation

	/**
	 * Geolocation tab change. Enables and disables input and required conditions and updates the map
	 * @method
	 * @private
	 *
	 * @param {Event} evt
	 */
	function geolocationTypeChanged(evt) {

		var $this = $(evt.currentTarget).parents('.tabs').eq(0);

		/*
		$this.find('> section *[name]').attr('disabled', 'disabled').removeAttr('required');
		$this.find('> section:visible *[name]').attr('required', 'required').removeAttr('disabled');
		$this.find('> section *[name]').trigger('change');
		*/

		hidePointErrors();

		if (self.mapa) {
			self.mapa.resize();
		}
	}

	function updateMap() {

        var tipo = $(self.elements.tabs + ' > section:visible').attr('id');

        $(self.elements.map).parent().show();

		if (self.mapa) {
			self.mapa.resize();

            self.shapes.clear();

            if (tipo === 'latlong') {
                updateMarker(null, true);
            } else if (tipo === 'utm') {
                updateUTM(null, true);
            } else if (tipo === 'mgrs') {
                updateMGRS(null, true);
            }
		}
	}

	/**
	 * Update the map marker
	 */
	function updateMarker(latLng, preserveViewport) {

		var marker = {},
			lat,
			lng;

		if (self.mapa) {
			self.shapes.clear();

			if (!latLng || !latLng.lat) {

				lat = $('#latitude').val();
				lng = $('#lonxitude').val();

				if (!lat || !lng || !parseFloat(lat) || !parseFloat(lng)) {
					return false;
				}

				latLng = new google.maps.LatLng($('#latitude').val(), $('#lonxitude').val());
			} else {
				$(self.elements.latitude).val(latLng.lat());
				$(self.elements.longitude).val(latLng.lng());
			}

            geocoderLoading();
            self.mapa.getLocationFromLatLng(latLng, geocoderOk, geocoderKo);

            /*
			config.avistamento.MAP_MARKER.call(marker);
			marker.latLng = latLng;
			self.mapa.loadMarkers([marker]);
            */
            self.shapes.loadShape({
                id: 'marker',
                type: 'marker',
                points: [{latitude: latLng.lat(), longitude: latLng.lng() }]
            });

			if (!preserveViewport) {
				self.mapa.innerMap.panTo(latLng);
				self.mapa.innerMap.setZoom(10);
			}

            getPointElevation(latLng, function (elevation) {
                $(self.elements.altitude).val(elevation);
            });
		}
	}

	/**
	 * Get current position ok callback. Shows coordinates on map and inputs
	 * @method
	 * @private
	 * @param {Object} position Position object form geolocation API
	 */
	function geolocateOK(position) {

		var $button = $(self.elements.xeolocaliza),
            tipo = $(self.elements.tabs + ' > section:visible').attr('id'),
            latLng;

        latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

        if (tipo === 'latlong') {
            updateMarker(latLng);
        } else if (tipo === 'utm') {
            updateUTM(latLng);
        } else if (tipo === 'mgrs') {
            updateMGRS(latLng);
        }

		$button.html($button.data('content'));
	}

	/**
	 * Get current position error callback. Shows error message
	 * @method
	 * @private
	 * @param {Object} error
	 */
	function geolocateKO(error) {
		var $button = $(self.elements.xeolocaliza);

		alert(config.GEOLOCATION_ERROR);
		$button.html($button.data('content'));
	}

	/**
	 * Click handler for geolocate button. Shows loading indicator and invoke the geolocation api.
	 * @method
	 * @private
	 */
	function geolocate(evt) {
		var $this = $(evt.currentTarget);

		$this.data('content', $this.html());
		$this.html('<i class="icon-spin icon-spinner"></i> ' + i18n.CALCULATING);

		navigator.geolocation.getCurrentPosition(geolocateOK, geolocateKO, {'enableHighAccuracy': true, 'timeout': 60000, 'maximumAge': 0});
	}


	//-- Point list

	function changeMapType(evt) {
		self.mapa.setType(config.avistamento.MAP_TYPES[evt.currentTarget.value]);
	}

    function updateUTM(latLng, preserveViewport) {

		var marker = {},
			lat,
			lng,
            utm,
            utm_datum,
            utm_fuso,
            utm_sur,
            utm_x,
            utm_y,
            ll;

		if (self.mapa) {
			self.shapes.clear();

			if (!latLng || !latLng.lat) {

                utm_datum = $(self.elements.utm_datum).val().toUpperCase();
                utm_fuso = parseInt($(self.elements.utm_fuso).val(), 10);
                utm_sur = $(self.elements.utm_fuso).is(':checked') ? 'S' : 'N';
                utm_x = parseInt($(self.elements.utm_x).val(), 10);
                utm_y = parseInt($(self.elements.utm_y).val(), 10);

                if (!utm_datum || !utm_fuso || !utm_x || !utm_y) {
					return false;
				}

                ll = mapas.utils.converter.utmToLatLng(utm_x, utm_y, utm_fuso, utm_sur, utm_datum);
				latLng = new google.maps.LatLng(ll.lat, ll.lng);

			} else {

                utm = mapas.utils.converter.latLngToUTM(latLng.lat(), latLng.lng());

                $(self.elements.utm_datum).val('wgs84');
                $(self.elements.utm_fuso).val(utm.zone);
                $(self.elements.utm_sur).prop('checked', utm.hemisphere === 'S' ? true : false);
                $(self.elements.utm_x).val(parseInt(utm.easting, 10));
                $(self.elements.utm_y).val(parseInt(utm.northing, 10));
			}

            geocoderLoading();
            self.mapa.getLocationFromLatLng(latLng, geocoderOk, geocoderKo);

            self.shapes.loadShape({
                id: 'marker',
                type: 'marker',
                points: [{latitude: latLng.lat(), longitude: latLng.lng()}]
            });

			if (!preserveViewport) {
				self.mapa.innerMap.panTo(latLng);
				self.mapa.innerMap.setZoom(12);
			}

            getPointElevation(latLng, function (elevation) {
                $(self.elements.utm_altitude).val(elevation);
            });
		}
    }

    function updateMGRS(latLng, preserveViewport) {

        var marker = {},
			lat,
			lng,
            mgrs,
            mgrs_datum,
            mgrs_coords,
            center,
            ll,
            points,
            size = 1000;

        if (self.mapa) {
			self.shapes.clear();

			if (!latLng || !latLng.lat) {

                mgrs_datum = $(self.elements.mgrs_datum).val().toUpperCase();
                mgrs_coords = $(self.elements.mgrs).val();

                if (!mgrs_datum || !mgrs_coords) {
					return false;
				}

                size = mgrs_coords.length >= 8 ? 1000 : 10000;

                center = mapas.utils.getMGRSCentroid(mgrs_coords);

                ll = mapas.utils.converter.mgrsToLatLong(center, mgrs_datum);

                points = mapas.utils.getCentroidPoints(ll.lat, ll.lng, size);
                latLng = new google.maps.LatLng(ll.lat, ll.lng);

			} else {

                mgrs = mapas.utils.converter.latLongToMGRS(latLng.lat(), latLng.lng());

                mgrs = mapas.utils.truncateMGRS(mgrs, 1000);

                $(self.elements.mgrs_datum).val('wgs84');
                $(self.elements.mgrs).val(mgrs);

                points = mapas.utils.getCentroidPoints(latLng.lat(), latLng.lng(), 1000);
			}

            geocoderLoading();
            self.mapa.getLocationFromLatLng(latLng, geocoderOk, geocoderKo);

            self.shapes.loadShape({
                id: 'polygon',
                type: 'polygon',
                points: points
            });

			if (!preserveViewport) {
				self.mapa.innerMap.panTo(latLng);
				self.mapa.innerMap.setZoom(size === 10000 ? 9 : 12);
			}

            getPointElevation(latLng, function (elevation) {
                $(self.elements.mgrs_altitude).val(elevation);
            });
		}
    }

	function loadMap() {

		var $toolbar = $(self.elements.mapToolbar);

		if (!self.mapa) {

			self.mapa = common.map.init('latlng', document.querySelector(self.elements.map), config.avistamento);

			self.mapa.on('click', function (evt) {

                var tipo = $(self.elements.tabs + ' > section:visible').attr('id');

                if (tipo === 'latlong') {
                    updateMarker(evt.latLng, true);
                } else if (tipo === 'utm') {
                    updateUTM(evt.latLng, true);
                } else if (tipo === 'mgrs') {
                    updateMGRS(evt.latLng, true);
                }
            });

			self.mapa.addElementToToolbar($toolbar[0], google.maps.ControlPosition.TOP_LEFT);

            self.shapes = new mapas.Shapes(self.mapa, {
                markerIcon: config.avistamento.ICON_MARKER,
                markerSize: {width: 14, height: 13},
                shapeColor: config.avistamento.COLOR_SHAPE
            });

			// Toolbar buttons
			$(self.elements.mapLabels).on('change', function (e) { self.mapa.showLabels(e.currentTarget.checked); }).trigger('change');

			$(self.elements.latitude + ', ' + self.elements.longitude).change(function () { updateMarker(); }).eq(0).trigger('change');
            $(self.elements.utm_datum + ', ' + self.elements.utm_fuso + ', ' +
                self.elements.utm_sur + ', ' + self.elements.utm_x + ', ' + self.elements.utm_y).change(function () { updateUTM(); }).eq(0).trigger('change');
            $(self.elements.mgrs_datum + ', ' + self.elements.mgrs).change(function () { updateMGRS(); }).eq(0).trigger('change');
		}
	}

	/**
	 * Show the point editing form
	 * @method
	 * @private
	 */
	function showForm() {

		var $form = $(self.elements.pointForm),
            $point = $('.novo-punto');

        $point.slideUp(config.ANIMATION_SPEED);

		$form.slideDown(config.ANIMATION_SPEED, function () {
			self.mapa.resize();
		});

		$form.find('*[name]').removeAttr('disabled');
		$form.find('select').select2('enable', true);

		loadMap();
	}

	/**
	 * Hide the point editing form
	 * @method
	 * @private
	 */
	function hideForm() {
		var $form = $(self.elements.pointForm),
            $point = $('.novo-punto');

		$form.slideUp(config.ANIMATION_SPEED);
        $point.slideDown(config.ANIMATION_SPEED);

		$form.find('*[name]').attr('disabled', 'disabled');
		$form.find('select').select2('enable', false);

		hidePointErrors();

		$(self.elements.pointList).find('span.selected').removeClass('selected');
	}

	/**
	 * Click handler for rremove point button. Mark a point to be remove form list
	 * @method
	 * @private
	 * @param {Event} evt
	 */
	function removePoint(evt) {
		var $this = $(evt.currentTarget),
			$li = $this.parents('li').eq(0),
			action = $li.find(self.elements.inputPointAction).val();

		if ($li.data('id') && $li.find(self.elements.inputPointAction).val() !== 'new') {

			$li.data('previous-actions', action);
			$li.find(self.elements.inputPointAction).val('deleted');
			$li.addClass('deleted');

			$li.find(self.elements.removePointButton).hide();
			$li.find(self.elements.restorePointButton).show();
		} else {

			$li.remove();
		}

		hideForm();
	}

	/**
	 * Click handler for restore point button. Mark a point to be remove form list
	 * @method
	 * @private
	 * @param {Event} evt
	 */
	function restorePoint(evt) {
		var $this = $(evt.currentTarget),
			$li = $this.parents('li').eq(0);


		$li.find(self.elements.inputPointAction).val($li.data('previous-actions'));
		$li.removeClass('deleted');

		$li.find(self.elements.removePointButton).show();
		$li.find(self.elements.restorePointButton).hide();
	}

	/**
	 * Load the point data in the edit form
	 * @method
	 * @private
	 * @param {jQuery} $item Point span
	 */
	function loadPointData($item) {

		var $li = $item.parents('li').eq(0),
			type,
			$form = $(self.elements.pointForm);

		// Save the point id in the form
		$form.data('point', $li.data('id'));

		// Get the type to know wich data we need to load
		type = $li.find(self.elements.inputType).val();

		// Show corresponding tab
		$form.find('*[data-type="' + type + '"]').trigger('click');

		// Load data based on the type
		if (type === 'mgrs') {

			$(self.elements.mgrs).val($li.find(self.elements.inputMGRS).val());
			$(self.elements.mgrs_datum).select2('val', $li.find(self.elements.inputDatum).val());
            $(self.elements.mgrs_altitude).val($li.find(self.elements.inputMGRSAltitude).val());

		} else if (type === 'utm') {

			$(self.elements.utm_datum).select2({width: 'resolve'});
			$(self.elements.utm_datum).select2('val', $li.find(self.elements.inputDatum).val());
			$(self.elements.utm_fuso).val($li.find(self.elements.inputUTMFuso).val());
			$(self.elements.utm_sur).prop('checked', $li.find(self.elements.inputUTMSur).val() ? true : false);
			$(self.elements.utm_x).val($li.find(self.elements.inputUTMX).val());
			$(self.elements.utm_y).val($li.find(self.elements.inputUTMY).val());
            $(self.elements.utm_altitude).val($li.find(self.elements.inputUTMAltitude).val());

		} else {

			$(self.elements.latitude).val($li.find(self.elements.inputLatitude).val());
			$(self.elements.longitude).val($li.find(self.elements.inputLongitude).val());
            $(self.elements.altitude).val($li.find(self.elements.inputAltitude).val());
		}
	}

	/**
	 * Click handler for button name. Shows editing formfor that point.
	 * @method
	 * @private
	 * @param {Event} evt
	 */
	function editPoint(evt) {
		var $this = $(evt.currentTarget),
			$form = $(self.elements.pointForm),
            tipo;

		$this.addClass('selected');

		$form.data('point', '');
		$form.find('*[name]:not(.hidden)').val('');

		loadPointData($this);
		showForm();


		$form.find('.tab.selected').trigger('click');

		$.scrollTo($('#form-edicion-puntos'), config.ANIMATION_SPEED);

        tipo = $(self.elements.tabs + ' > section:visible').attr('id');

        if (tipo === 'latlong') {
            updateMarker();
        } else if (tipo === 'utm') {
            updateUTM();
        } else if (tipo === 'mgrs') {
            updateMGRS();
        }

		return false;
	}

	/**
	 * Click hanlder for the add point button
	 * @method
	 * @private
	 * @param {Event} evt
	 */
	function addPoint(evt) {
		var $this = $(evt.currentTarget),
			$form = $(self.elements.pointForm);

		showForm();

		$form.data('point', '');
		$form.find('*[name]:not(.hidden)').val('');
		$form.find('.tabs .tab').eq(0).trigger('click');
		$form.find('select').select2({width: 'resolve'});

		updateMarker();

		return false;
	}

	/**
	 * Cancel the editing or creation of a point
	 * @method
	 * @private
	 * @param {Event} evt
	 */
	function cancelEditPoint(evt) {
		var $this = $(evt.currentTarget),
			$form = $(self.elements.pointForm);

		hideForm();
	}

	function createInput(index, name, className, value) {
		var $input;

		$input = $('<input type="hidden" />');
		$input.attr('name', 'xeolocalizacion[' + index + '][' + name + ']');
		$input.addClass(className);
		$input.val(value);

		return $input;
	}

	function updatePointData($item, $li, modified) {
		var $span,
			$action,
			type,
			$type,
			$mgrs,
			$datum,
			$utmFuso,
			$utmSur,
			$utmX,
			$utmY,
			$lat,
			$long,
			text,
			index,
			previousAction;

		if (!$li) {

			index = $(self.elements.pointList).find('ul li').length - 1;

			$li = $('<li class="point-item"></li>');
			$li.attr('data-id', 'new-' + $(self.elements.pointList).find('inpout.point-acion[value="new"]').length);

			$span = $('<span></span>');
			$span.appendTo($li);
			$('<button type="button" class="btn remove-point"><i class="icon-trash"></i></button>').appendTo($li);
			$('<button type="button" class="btn restore-point hidden"><i class="icon-remove"></i></button>').appendTo($li);
			$li.insertBefore($(self.elements.pointList).find('.novo-punto'));

		} else {
			index = $li.index();
			previousAction = $li.find(self.elements.inputPointAction).val();
			$li.find('input[type="hidden"]:not(.point-id)').remove();
			$span = $li.find('span');
		}

		$li.append(createInput(index, 'action', 'point-action', modified && previousAction !== 'new' ? 'modified' : 'new'));

		type = $item.find('input[name="xeolocalizacion_tipo"]:checked').val();
		$li.append(createInput(index, 'tipo', 'point-type', type));



		if (type === 'mgrs') {
			$li.append(createInput(index, 'datum', 'point-datum', $item.find(self.elements.mgrs_datum).val()));
			$li.append(createInput(index, 'mgrs', 'point-mgrs', $item.find(self.elements.mgrs).val()));
            $li.append(createInput(index, 'altitude', 'point-mgrs-altitude', $item.find(self.elements.mgrs_altitude).val()));

			text = 'Mgrs ' + $(self.elements.mgrs_datum).find('option:selected').html() + ' - ' + $(self.elements.mgrs).val();

		} else if (type === 'utm') {
			$li.append(createInput(index, 'datum', 'point-datum', $item.find(self.elements.utm_datum).val()));
			$li.append(createInput(index, 'utm_fuso', 'point-utm-fuso', $item.find(self.elements.utm_fuso).val()));
			$li.append(createInput(index, 'utm_sur', 'point-utm-sur', $item.find(self.elements.utm_sur).prop('checked') ? '1' : ''));
			$li.append(createInput(index, 'utm_x', 'point-utm-x', $item.find(self.elements.utm_x).val()));
			$li.append(createInput(index, 'utm_y', 'point-utm-y', $item.find(self.elements.utm_y).val()));
            $li.append(createInput(index, 'altitude', 'point-utm-altitude', $item.find(self.elements.utm_altitude).val()));

			text = 'UTM ' + $(self.elements.utm_datum).find('option:selected').html() + ' - ' + $(self.elements.utm_fuso).val() + ($(self.elements.utm_sur).prop('checked') ? 'S' : 'N') + ' ' + $(self.elements.utm_x).val() + ' ' + $(self.elements.utm_y).val();
		} else {
			$li.append(createInput(index, 'datum', 'point-datum', $item.find(self.elements.latlong_datum).val()));
			$li.append(createInput(index, 'latitude', 'point-latitude', $item.find(self.elements.latitude).val()));
			$li.append(createInput(index, 'lonxitude', 'point-longitude', $item.find(self.elements.longitude).val()));
            $li.append(createInput(index, 'altitude', 'point-altitude', $item.find(self.elements.altitude).val()));

			text = 'Latlong ' + $(self.elements.latitude).val() + ', ' + $(self.elements.longitude).val();
		}

		$span.html(text);

		return $li;
	}

	function saveEditPoint(evt) {
		var $this = $(evt.currentTarget),
			$form = $(self.elements.pointForm),
			$elements,
			$list,
			id = $form.data('point'),
			$li,
			className = 'new',
			modified;

		$elements = $form.find('section:visible *[name]');
		$list =  $(self.elements.pointFormErrors + ' ul');

		if (common.forms.checkValidity.call($elements, null, $list) === false) {
			$(self.elements.pointFormErrors).show();
			return false;
		}

		hidePointErrors();

		if (id) {
			$li = $(self.elements.pointList + ' li[data-id="' + id + '"]');
			modified = true;
			className = 'modified';
		}

		$li = updatePointData($form, $li, modified);
		$li.addClass(className);

		hideForm();

		$.scrollTo($li, config.ANIMATION_SPEED);
	}

    function checkMain(evt) {
        var $this = $(evt.currentTarget),
            $hiddens = $(self.elements.hiddenMain),
            $parent = $this.parents('li').eq(0),
            $hiddenToCheck = $this.parent().children('input[type="hidden"]');

        $hiddens.val('');
        $hiddenToCheck.val('1');
    }

    function checkData() {

        var $items = $(self.elements.pointList + ' ul li.point-item'),
            $xeolocalizacions = $(self.elements.xeolocalizacions),
            $validation = $(self.elements.validation),
            $label = $xeolocalizacions.find('label').eq(0);

        $label.find('span.error').remove();
        $validation.find('li[data-id="' + $label.attr('id')  + '"]').remove();

        if (!$items.length) {
            $validation.find('ul').append('<li data-id="' + $label.attr('id') + '"><a href="#' + $label.attr('id') + '">' + $label.html() + '</a> - ' + i18n.avistamento.ERROR_PUNTOS_OBLIGATORIOS + '</li>');
            $label.append('<span class="error">' + i18n.avistamento.ERROR_PUNTOS_OBLIGATORIOS + '</span>');

            if (!$validation.is(':visible')) {
                $validation.show();
            }

            $.scrollTo($validation, config.ANIMATION_SPEED, {offset: {top: -10}});

            return false;
        }
    }

    function getUTMLocation() {

        var datum = $(self.elements.utm_datum).val(),
            fuso = parseInt($(self.elements.utm_fuso).val(), 10),
            hemisferio = $(self.elements.utm_sur).is(':checked') ? 'S' : 'N',
            x = parseInt($(self.elements.utm_x).val(), 10),
            y = parseInt($(self.elements.utm_y).val(), 10),
            latLng,
            $tab = $(avistamento.editar.elements.tabs + '> section:visible'),
            $direccion = $tab.find('span.direccion-aproximada');

        if (datum && fuso && x && y) {

            latLng = mapas.utils.converter.utmToLatLng(x, y, fuso, hemisferio, datum.toUpperCase());

            geocoderLoading();
            self.mapa.getLocationFromPoint(latLng.lat, latLng.lng, geocoderOk, geocoderKo);
        } else {
            $direccion.html('');
        }
    }

    function loadAddressData(data) {

        var $territorio = $(self.elements.territorio),
            $provincia = $(self.elements.provincia),
            $concello = $(self.elements.concello);

        if (data.territorio && data.territorio.nome) {
            $territorio.select2('val', data.territorio.url).trigger('change');
            $.scrollTo($territorio, config.ANIMATION_SPEED, {offset: { top: -10 }});
        }

        if (data.provincia && data.provincia.nome) {
            $provincia.on('parent-changed', function () {
                $(this).select2('val', data.provincia.nome.url).trigger('change');
            });
        }

        if (data.concello && data.concello.nome) {
            $concello.on('parent-changed', function () {
                $(this).select2('val', data.concello.nome.url).trigger('change');
            });
        }
    }

    function useAddress(e) {
        var $this = $(e.currentTarget),
            $direccion = $this.parent().find('span.direccion-aproximada');

        $this.attr('data-text', $this.html());
        $this.html('<i class="icon-spinner icon-spin"></i> ' + i18n.LOADING);

        // Make the request
        $.ajax({
            url: config.avistamento.URL_GET_DIRECCION,
            type: 'get',
            dataType: 'json',
            data: $direccion.data('components'),
            cache: false,
            success: function (data, status, xhr) {

                if (data) {
                    loadAddressData(data);
                }
            },
            error: function (xhr, status, error) {
                alert(i18n.avistamento.ERROR_DIRECCION);
            },
            complete: function () {
                $this.html($this.attr('data-text'));
            }
        });
    }

	//-- Public
	//-----------------------

	/**
	 * Lat/Long map
	 * @property {mapas.Map}
	 * @public
	 */
	self.map = null;

    /**
     * List of shapes
     * @property {mapas.Shapes}
     * @public
     */
    self.shapes = null;

	/**
	 * List of selectors for the page
	 * @property {Object}
	 * @public
	 */
	self.elements = {
		form: '.content form',
        validation: '#validation',

        territorio: '#territorio',
        provincia: '#provincia',
        concello: '#concello',

		especiesDesconhecida: '#especie-desconhecida',
        xeolocalizacions: '#avistamento-xeolocalizacion',
		tabs: '#avistamento-xeolocalizacion .tabs',
		tabInputs: '#avistamento-xeolocalizacion .tabs .tab input[type="radio"]',

		imagelist: '.imagelist',
		imagelistLi: '.imagelist li',
		imagelistRemoved: '.imagelist-removed',

		xeolocaliza: '#xeolocalizame',

		latitude: '#latitude',
		longitude: '#lonxitude',
        altitude: '#altitude',
		latlong_datum: '#latlong_datum',

		utm_datum: '#utm_datum',
		utm_fuso: '#utm_fuso',
		utm_sur: '#utm_sur',
		utm_x: '#utm_x',
		utm_y: '#utm_y',
        utm_altitude: '#utm_altitude',

		mgrs_datum: '#mgrs_datum',
		mgrs: '#mgrs_coords',
        mgrs_altitude: '#mgrs_altitude',

		inputPointItem: 'input.point-item',
		inputPointId: 'input.point-id',
		inputPointAction: 'input.point-action',

		inputType: 'input.point-type',
		inputDatum: 'input.point-datum',

		inputUTMFuso: 'input.point-utm-fuso',
		inputUTMSur: 'input.point-utm-sur',
		inputUTMX: 'input.point-utm-x',
		inputUTMY: 'input.point-utm-y',
        inputUTMAltitude: 'input.point-utm-altitude',

		inputLatitude: 'input.point-latitude',
		inputLongitude: 'input.point-longitude',
        inputAltitude: 'input.point-altitude',

		inputMGRS: 'input.point-mgrs',
        inputMGRSAltitude: 'input.point-mgrs-altitude',

		pointList: '.point-list',
		removePoint: '.point-list .remove-point',
		addPoint: '#engadir-punto',

		pointForm: 'section.editar-puntos',
		pointFormErrors: '#editar-puntos-errores',
		saveEditPoint: 'button.guardar-punto',
		cancelEditPoint: 'button.cancelar-punto',

		removePointButton: 'button.remove-point',
		restorePointButton: 'button.restore-point',

		map: '.map',
		mapToolbar: '#mapa-toolbar-top',
		mapZoomMinus: '#zoom-minus',
		mapZoomPlus: '#zoom-plus',
		mapType: '#map-type',
		mapLabels: '#toggle-labels',

		buttonGardar: '#button-gardar',

        checkboxMain: 'label.check-imaxe-principal input[type="radio"]',
        hiddenMain: 'label.check-imaxe-principal input[type="hidden"]',

        butonUseAddres: '.use-address'
	};

	/**
	 * Initialization method
	 * @method
	 * @public
	 */
	self.init = function () {

		// Especie desconhecida
		$(self.elements.especiesDesconhecida).on('change', desconocida).trigger('change');

		// Geolocation tabs
		$(self.elements.tabs).on('tabShow', updateMap);
		$(self.elements.tabInputs).on('click', geolocationTypeChanged).filter('[checked]').trigger('click');


		// Geolocation inputs
		$(self.elements.latitude + ', ' + self.elements.longitude).on('change', updateMarker).trigger('change');

		// Geolocation
		if (navigator.hasOwnProperty('geolocation')) {
			$(self.elements.xeolocaliza).removeClass('hidden');
			$(self.elements.xeolocaliza).on('click', geolocate);
		}

		// Point list
		$(self.elements.pointList).on('click', self.elements.removePointButton, removePoint);
		$(self.elements.pointList).on('click', self.elements.restorePointButton, restorePoint);
		$(self.elements.pointList).on('click', 'ul li span', editPoint);
		$(self.elements.addPoint).on('click', addPoint);

		$(self.elements.saveEditPoint).on('click', saveEditPoint);
		$(self.elements.cancelEditPoint).on('click', cancelEditPoint);

		$(self.elements.buttonGardar).on('click', hideForm);
		$(self.elements.form).on('submit', hideForm);

        $(self.elements.form).on('click', self.elements.checkboxMain, checkMain);
        $(self.elements.form).on('submit', checkData);

        $(self.elements.butonUseAddres).on('click', useAddress);
	};

	// Load module on ready and return public interface
	$(document).ready(self.init);
	return self;
}());