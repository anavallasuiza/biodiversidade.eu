/*jslint browser:true */
/*global google, $, mapas */

/**
 * Collection of misc functions used in "mapas.js"
 */
mapas.utils = {

    //TODO: Add the hability to add various hanlders using space separated event types
    //TODO: Add the same namespace feature for event names that jquery does (its cool)
    EventsMixin: function () {

        'use strict';

        var listeners = [];

        this.on = function (type, handler) {

            if (!listeners[type]) {
                listeners[type] = {};
            }

            if (!listeners[type][handler.toString()]) {
                listeners[type][handler.toString()] = handler;
            }

        };

        this.off = function (type, handler) {

            if (listeners[type] && listeners[type][handler.toString()]) {
                delete listeners[type][handler.toString()];
            }
        };

        this.trigger = function (type, context) {
            var handlers = listeners[type],
                code,
                result;

            for (code in handlers) {
                if (handlers.hasOwnProperty(code)) {

                    if (context && context.constructor !== Array) {
                        context = [context];
                    }

                    result = handlers[code].apply(this, context);

                    if (result === false) {
                        return false;
                    }
                }
            }
        };
    },

    /**
     * DOM event hanlding polyfill
     */
    on: function (node, event, handler) {

        'use strict';

        if (node.addEventListener) {
            node.addEventListener(event, handler);
        } else {
            node.attachEvent('on' + event, handler);
        }
    },

    /**
     * DOM event hanlding polyfill
     */
    off: function (node, event, handler) {

        'use strict';

        if (node.addEventListener) {
            node.removeEventListener(event, handler);
        } else {
            node.detachEvent('on' + event, handler);
        }
    },

    /**
     * indexOf Polyfill
     */
    indexOf: function (searchElement, index) {

        'use strict';

		var t, len, n, k;

		if (!this) {
			throw new TypeError();
		}

		t = Object(this);
		len = t.length >>> 0;

		if (len === 0) {
			return -1;
		}

		n = 0;
		if (arguments.length > 1) {
			n = Number(index);
			if (n != n) { // shortcut for verifying if it's NaN
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}

		for (k = ((n >= 0) ? n : Math.max(len - Math.abs(n), 0)); k < len; k += 1) {
			if (t.hasOwnProperty(k) && t[k] === searchElement) {
				return k;
			}
		}

		return -1;
	},

	/**
	 * Check if a namespace exist and optionally create it
	 * @method
	 *
	 * @param {Array} nameList Array with the names of the namespace
	 * @param {Boolean} create If true it will create the namespace if it does not exists
	 * @param {Object} Parent of tyhe namespace we want to check (default is window)
	 *
	 * @return {Boolean} True or false. If
	 */
	checkNamespace: function (nameList, create, parent) {

		"use strict";

		var level = parent || window, result = true, i, length;

		for (i = 0, length = nameList.length; i < length; i++) {
			if (!level[nameList[i]]) {
				if (create) {
					level[nameList[i]] = {};
				} else {
					result = false;
					break;
				}
			}

			level = level[nameList[i]];
		}

		return result;
	},

    getCentroidPoints: function (lat, lng, size) {

        var utm,
            sw,
            latLngSW,
            se,
            latLngSE,
            nw,
            latLngNW,
            ne,
            latLngNE,
            points = [];


        utm = mapas.utils.converter.latLngToUTM(lat, lng);

        sw = {
            easting: Math.floor(utm.easting / size) * size,
            northing: Math.floor(utm.northing / size) * size,
            zone: utm.zone,
            hemisphere: utm.hemisphere
        };

        latLngSW = mapas.utils.converter.utmToLatLng(sw);

        se = {
            easting: sw.easting + size,
            northing: sw.northing,
            zone: utm.zone,
            hemisphere: utm.hemisphere
        };

        latLngSE = mapas.utils.converter.utmToLatLng(se);

        nw = {
            easting: sw.easting,
            northing: sw.northing + size,
            zone: utm.zone,
            hemisphere: utm.hemisphere
        };

        latLngNW = mapas.utils.converter.utmToLatLng(nw);

		ne = {
			easting: sw.easting + size,
			northing: sw.northing + size,
			zone: sw.zone,
			hemisphere: sw.hemisphere
		};

        latLngNE = mapas.utils.converter.utmToLatLng(ne);

        points.push({latitude: latLngSW.lat, longitude: latLngSW.lng});
        points.push({latitude: latLngSE.lat, longitude: latLngSE.lng});
        points.push({latitude: latLngNE.lat, longitude: latLngNE.lng});
        points.push({latitude: latLngNW.lat, longitude: latLngNW.lng});

        return points;
    },

    /**
     * Get the sw corners for a given utm ot lat/lng and sizes
     * @method
     * @public
     * @param {Object} data
     * @param {Array} sizes
     */
    getCellSWCorner: function(data, size) {

        var result = {},
            utm,
            sw,
            i,
            size;

		utm = data.utm || mapas.utils.converter.latLngToUTM(data.lat, data.lng);

        sw = {
            easting: Math.floor(utm.easting / size) * size,
            northing: Math.floor(utm.northing / size) * size,
            zone: utm.zone,
            hemisphere: utm.hemisphere
        };

		return sw;
	},

    /**
     * Get the corner code for a given utm corner coordinate
     * @method
     * @public
     * @param {Object} corner
     */
    getCornerCode: function (corner) {
		return corner.zone + '-' + corner.hemisphere + '-' + corner.easting + '-' + corner.northing;
	},

    parseMGRS: function(mgrs) {

        var expr = /^([0-9]{1,2})([A-Z])([A-Z])([A-Z])([0-9]{2,})([A-Z])?$/,
            result;

        mgrs = mgrs.toUpperCase(); // Always uppercase

        // If it doesnt match regexp we throw an error
        if (!expr.test(mgrs)) {
            throw new Error('MGRS forma incorrect. ' + mgrs);
        }

        var matches = expr.exec(mgrs);

        var gridNumber = matches[1]; // 29
        var gridLetter = matches[2]; // T
        var eastingLetter = matches[3]; // N
        var northingLetter = matches[4]; //H
        var numbers = matches[5]; //1234512345

        // If there are not even we throw an error
        if (numbers.length % 2 !== 0) {
            throw new Error('MGRS format incorrect. Numbers are not even. ' + mgrs);
        }

        var easting = numbers.substr(0, numbers.length / 2);
        var northing = numbers.substr(numbers.length / 2);

        result = {
            zone: gridNumber,
            letter: gridLetter,
            eastingLetter: eastingLetter,
            northingLetter: northingLetter,
            easting: easting,
            northing: northing
        };

        return result;
    },

    truncateMGRS: function (mgrs, size) {

        var result;

        mgrs = mapas.utils.parseMGRS(mgrs);

        if (size >= 10000) {
            result = mgrs.zone + mgrs.letter + mgrs.eastingLetter + mgrs.northingLetter + mgrs.easting.substr(0, 1) + mgrs.northing.substr(0, 1);
        } else if (size >= 1000) {
            result = mgrs.zone + mgrs.letter + mgrs.eastingLetter + mgrs.northingLetter + mgrs.easting.substr(0, 2) + mgrs.northing.substr(0, 2);
        } else {
            result = mgrs.zone + mgrs.letter + mgrs.eastingLetter + mgrs.northingLetter + mgrs.easting + mgrs.northing;
        }

        return result;
    },

    getMGRSCentroid: function(mgrs) {

        var result,
            data,
            coordsLength = 5,
            middle,
            easting,
            northing;

        data = mapas.utils.parseMGRS(mgrs);

        if (data.easting.length === 5) {
            result = mgrs;
        } else {

            middle = 5;

            if (data.easting.length < 4) {
                middle = (Math.pow(10, (coordsLength - data.easting.length) - 1) * 5);
            }

            easting = data.easting + middle;
            northing = data.northing + middle;

            result = data.zone + data.letter + data.eastingLetter + data.northingLetter + easting + northing;
        }

        return result;
    },

	// Collection of convertion methods. Ie latlng to utm
	converter: {

		// List of supported datums datums, each one needs the equator radius and the flattering
		datums: {
			'WGS84': {
				eqRadius: 6378137.0,
				flattening: 298.2572236
			},
			'ED50': {
				eqRadius: 6378388,
				flattening: 297.00000000
			},
			'NAD83': {
				eqRadius: 6378137.0,
				flattening: 298.2572236
			},
			'GRS80': {
				eqRadius: 6378137.0,
				flattening: 298.2572215
			}
		},


		latLngToUTM: function(latitude, longitude, datumCode) {

			"use strict";

			// Datum
			var datum = this.datums[datumCode || 'WGS84'];

			if (!datum) {
				throw new Error('Datum ' + datumCode + ' not supported.');
			}

			// Vars
			// -----------------------------

			var k0 = 0.9996;							// Scale on central meridian
			var a = datum.eqRadius;						// Equatorial radius, meters.
			var f = 1 / datum.flattening;				// Polar flattening.
			var b = a * (1 - f);						// Polar axis.
			var e = Math.sqrt(1 - (b / a) * (b / a)); 	// Eccentricity
			var k = 1;									//local scale

			//Calculate Intermediate Terms
			var e0 = e / Math.sqrt(1 - e * e);		// Called e prime in reference
			var esq = (1 - (b / a) * (b / a));		// e squared for use in expansions
			var e0sq = e * e / (1 - e * e);			// e0 squared - always even powers

			var drad = Math.PI/180; //Convert degrees to radians

			// -----------------------------

			var latd = parseFloat(latitude);
			var lngd = parseFloat(longitude);

			// Convert latitude and longitude to radian
			var phi = latd * drad;
			var lng = lngd * drad;

			// Calculate utm zone
			var utmz = 1 + Math.floor((lngd + 180) / 6);

			//Latitude zone: A-B S of -80, C-W -80 to +72, X 72-84, Y,Z N of 84
			var latz = 0;

			if (latd > -80 && latd < 72) {
				latz = Math.floor((latd + 80) / 8) + 2;
			} else if (latd > 72 && latd < 84) {
				latz = 21;
			} else if (latd > 84) {
				latz = 23;
			}

			//Central meridian of zone
			var zcm = 3 + 6 * (utmz - 1) - 180;

			var N = a / Math.sqrt(1 - Math.pow(e * Math.sin(phi), 2));

			var T = Math.pow(Math.tan(phi), 2);

			var C = e0sq * Math.pow(Math.cos(phi), 2);

			var A = (lngd - zcm) * drad * Math.cos(phi);

			//Calculate M
			var M = phi * (1 - esq * (1 / 4 + esq * (3 / 64 + 5 * esq / 256)));
			M = M - Math.sin(2 * phi) * (esq * (3 / 8 + esq * (3 / 32 + 45 * esq / 1024)));
			M = M + Math.sin(4 * phi) * (esq * esq * (15 / 256 + esq * 45 / 1024));
			M = M - Math.sin(6 * phi) * (esq * esq * esq * (35 / 3072));
			M = M * a; 	//Arc length along standard meridian

			var M0 = 0;	//M0 is M for some origin latitude other than zero. Not needed for standard UTM

			// Calculate UTM Values
			var x = k0 * N * A * (1 + A * A * ((1 - T + C) / 6 + A * A * (5 - 18 * T + T * T + 72 * C - 58 * e0sq) / 120));	//Easting relative to CM
			x = x + 500000;	//Easting standard

			var y = k0 * (M - M0 + N * Math.tan(phi) * (A * A * (1 / 2 + A * A * ((5 - T + 9 * C + 4 * C * C) / 24 + A * A * (61 - 58 * T + T * T + 600 * C - 330 * e0sq) / 720))));	//Northing from equator
			var yg = y + 10000000;	//yg = y global, from S. Pole

			if (y < 0) {
				y = 10000000 + y;
			}

			var hem = phi < 0 ? 'S' : 'N';

			var result = {
				'easting': Math.round(10 * x) / 10,
				'northing': Math.round(10 * y) / 10,
				'zone': utmz,
				'hemisphere': hem		};

			return result;
		},

		utmToLatLng : function(easting, northing, zone, hemisphere, datumCode) {

			"use strict";

			if (arguments.length < 4) {

				var utmObject = easting;
				datumCode = northing;

				easting = utmObject.easting;
				northing = utmObject.northing;
				zone = utmObject.zone;
				hemisphere = utmObject.hemisphere;
			}

			// Datum
			var datum = this.datums[datumCode || 'WGS84'];

			if (!datum) {
				throw new Error('Datum ' + datumCode + ' not supported.');
			}

			// Vars
			// -----------------------------

			var k0 = 0.9996;							// Scale on central meridian
			var a = datum.eqRadius;						// Equatorial radius, meters.
			var f = 1 / datum.flattening;				// Polar flattening.
			var b = a * (1 - f);						// Polar axis.
			var e = Math.sqrt(1 - (b / a) * (b / a)); 	// Eccentricity
			var k = 1;									//local scale

			//Calculate Intermediate Terms
			var e0 = e / Math.sqrt(1 - e * e);			// Called e prime in reference
			var esq = (1 - (b / a) * (b / a));			// e squared for use in expansions
			var e0sq = e * e / (1 - e * e);				// e0 squared - always even powers

			var drad = Math.PI / 180; 					//Convert degrees to radians

			var x = easting;
			var y = northing;

			// Easting checks
			if (x < 160000 || x > 840000) {
				if (console && console.warn) {
					console.warn('Outside permissible range of easting values. Results may be unreliable. Use with caution!');
				}
			}

			// Northing checks
			if (y < 0) {
				if (console && console.warn) {
					console.warn('Negative values not allowed. Results may be unreliable. Use with caution!');
				}
			} else if (y > 10000000) {
				if (console && console.warn) {
					console.warn('Northing may not exceed 10,000,000. Results may be unreliable. Use with caution!');
				}
			}

			var utmz = parseFloat(zone);

			// Central meridian of zone
			var zcm = 3 + 6 * (utmz - 1) - 180;

			// Called e1 in USGS PP 1395 also
			var e1 = (1 - Math.sqrt(1 - e * e)) / (1 + Math.sqrt(1 - e * e));

			// In case origin other than zero lat - not needed for standard UTM
			var M0 = 0;

			// Arc length along standard meridian.
			var M = M0 + y / k0;

			if (hemisphere === 'S') {
				M = M0 + (y - 10000000) / k;
			}

			var mu = M / (a * (1 - esq * (1 / 4 + esq * (3 / 64 + 5 * esq / 256))));

			//Footprint Latitude
			var phi1 = mu + e1 * (3 / 2 - 27 * e1 * e1 / 32) * Math.sin(2 * mu) + e1 * e1 * (21 / 16 - 55 * e1 * e1 / 32) * Math.sin(4 * mu);
				phi1 = phi1 + e1 * e1 * e1 * (Math.sin(6 * mu) * 151 / 96 + e1 * Math.sin(8 * mu) * 1097 / 512);

			var C1 = e0sq * Math.pow(Math.cos(phi1), 2);
			var T1 = Math.pow(Math.tan(phi1), 2);
			var N1 = a / Math.sqrt(1 - Math.pow(e * Math.sin(phi1), 2));
			var R1 = N1 * (1 - e * e) / (1 - Math.pow(e * Math.sin(phi1), 2));
			var D = (x - 500000) / (N1 * k0);

			var phi = (D * D) * (1 / 2 - D * D * (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e0sq) / 24);
				phi = phi + Math.pow(D, 6) * (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 -252 * e0sq - 3 * C1 * C1) / 720;
				phi = phi1 - (N1 * Math.tan(phi1) / R1) * phi;




			var lng = D * (1 + D * D * ((-1 - 2 * T1 - C1) / 6 + D * D * (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e0sq + 24 * T1 * T1) / 120)) / Math.cos(phi1);

			var latitude = Math.floor(1000000 * phi / drad) / 1000000;
			var longitude = zcm + lng / drad;

			return {
				'lat': latitude,
				'lng': longitude
			};
		},

		mgrsToUTM: function(mgrs) {

			var expr = /^([0-9]{1,2})([A-Z])([A-Z])([A-Z])([0-9]{2,})([A-Z])?$/;

			//AllDGLetrs = "ABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUV";
			var letters = "ABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUVABCDEFGHJKLMNPQRSTUV";

			//DigraphLetrsE = "ABCDEFGHJKLMNPQRSTUVWXYZ";
			var lettersEasting = "ABCDEFGHJKLMNPQRSTUVWXYZ";

			//DigraphLetrsN = "ABCDEFGHJKLMNPQRSTUV";
			var lettersNorthing = "ABCDEFGHJKLMNPQRSTUV";

			// Parse the mgrs
			// -----------------------

			mgrs = mgrs.toUpperCase(); // Always uppercase

			// If it doesnt match regexp we throw an error
			if (!expr.test(mgrs)) {
				throw new Error('MGRS forma incorrect. ' + mgrs);
			}

			var matches = expr.exec(mgrs);

			var gridNumber = matches[1]; // 29
			var gridLetter = matches[2]; // T
			var eastingLetter = matches[3]; // N
			var northingLetter = matches[4]; //H
			var numbers = matches[5]; //1234512345

			// If there are not even we throw an error
			if (numbers.length % 2 !== 0) {
				throw new Error('MGRS format incorrect. Numbers are not even. ' + mgrs);
			}

			var easting = parseInt(numbers.substr(0, numbers.length / 2));
			var northing = parseInt(numbers.substr(numbers.length / 2));

			// -----------------------

			// Extra validations
			// -------------------------

			// Check grid zone number range
			if (gridNumber < 1 || gridNumber > 60) {
				throw new Error('MGRS format incorrect. Grid number should be between 1 and 60. ' + mgrs);
			}

			// Check grid zone letter
			if (gridLetter === 'I' || gridLetter === 'O') {
				throw new Error('MGRS format incorrect. I and O grid zones not allowed. ' + mgrs);
			}

			// Check easting letter
			if (lettersEasting.indexOf(eastingLetter) < 0){
				throw new Error('MGRS format incorrect. Easting letter not in ' + lettersEasting + '. ' + mgrs);
			}

			// Check northing letter
			if (lettersNorthing.indexOf(northingLetter) < 0) {
				throw new Error('MGRS format incorrect. Northing letter not in ' + lettersNorthing + '. ' + mgrs);
			}

			// Check easting and northing range
			if (easting < 0 || easting > 100000 || northing < 0 || northing > 100000) {
				throw new Error('MGRS format incorrect. Northing and easting should be between 0 and 100000. ' + mgrs);
			}

			var eastingIndex = lettersEasting.indexOf(eastingLetter);
			var northingIndex = lettersNorthing.indexOf(northingLetter);

			// Correction for even zones
			if (gridNumber / 2 == Math.floor(gridNumber/2)) {
				northingIndex = northingIndex - 5;
			}

			//Check Compatibility of Zones and Digraph (Northing and Easting letters)
			//Check Long Zone
			//Zone 1: 1-8; Zone 2: 9-16; Zone 3: 17-24

			if ((Math.floor(eastingIndex / 8)) != ((gridNumber - 1) - 3 * Math.floor((gridNumber - 1) / 3))) {
				throw new Error('MGRS format incorrect. Longitude zone and easting letter are inconsistent. ' + mgrs);
			}

			var eastingBase = 100000 * (1 + lettersEasting.indexOf(eastingLetter) - 8 * Math.floor(lettersEasting.indexOf(eastingLetter) / 8));

			//Now Latitude Zones
			//N Lat: 100km band = 8.88(northingIndex-12) to 8.88(northingIndex-11)
			var latitudeBand = lettersEasting.indexOf(gridLetter); // Digraph letters E use same set as Lat zone designations

			var latitudeBandBottom = 8 * latitudeBand - 96;
			var latitudeBandTop = 8 * latitudeBand - 88;

			//Lat Band C starts at -80 but is index 2 in the letters list, hence -80-16 = -96, etc.

			if (latitudeBand < 2) {
				latitudeBandBottom = -90;
				latitudeBandTop = -80;
			}

			if (latitudeBand == 21) {
				latitudeBandBottom = 72;
				latitudeBandTop = 84;
			}

			if (latitudeBand > 21) {
				latitudeBandBottom = 84;
				latitudeBandTop = 90;
			}


			//One degree = 10000km/90, lat band = 8 degrees = 80000/90 = 889km
			var bottomLetter = Math.floor(100 + 1.11 * latitudeBandBottom);
			var topLetter = Math.round(100 + 1.11 * latitudeBandTop);

			//Adjust for even zones
			var latitudeBandLetters = letters.slice(bottomLetter, (topLetter + 1));

			//Deal with even zones
			if (gridNumber / 2 == Math.floor(gridNumber / 2)) {
				latitudeBandLetters = letters.slice(bottomLetter + 5, topLetter + 6);
			}

			if(latitudeBandLetters.indexOf(northingLetter) < 0) {
				throw new Error('MGRS format incorrect. Latitude zone and northing letter are inconsistent. ' + mgrs);
			}

			var northingBase = 100000 * (bottomLetter + latitudeBandLetters.indexOf(northingLetter));


			// Results
			// ---------------------

			var x = parseInt(eastingBase) + parseInt(easting);
			var y = parseInt(northingBase) + parseInt(northing);

			if (y > 10000000) {
				y = y - 10000000;
			}

			if (northingBase >= 1e+7){
				y = northingBase + northing - 1e+7;
			}

			var hemisphere = 'N';

			//Southern Hemisphere
			if (northingBase < 1e+7) {
				hemisphere = 'S';
			}

			var result = {
				'easting': x,
				'northing': y,
				'zone': gridNumber,
				'hemisphere': hemisphere
			};

			return result;
		},

		mgrsToLatLong: function(mgrs, datum) {

			var utm = mapas.utils.converter.mgrsToUTM(mgrs);

			return mapas.utils.converter.utmToLatLng(utm, datum);
		},

		latLongToMGRS: function(latitude, longitude, datum) {
			"use strict";

			var lettersEasting = "ABCDEFGHJKLMNPQRSTUVWXYZ";
			var lettersNorthing = "ABCDEFGHJKLMNPQRSTUV";

			var utm = mapas.utils.converter.latLngToUTM(latitude, longitude, datum);

			//Latitude zone: A-B S of -80, C-W -80 to +72, X 72-84, Y,Z N of 84
			var latitudeZone = 0;


			if (latitude > -80 && latitude < 72) {
				latitudeZone = Math.floor((latitude + 80) / 8) + 2;
			} else if (latitude > 72 && latitude < 84) {
				latitudeZone = 21;
			} else if (latitude > 84){
				latitudeZone = 23;
			}

            //Inputs y utmz
            //alert(utmz);
            var letr = Math.floor((utm.zone - 1) * 8 + (utm.easting) / 100000);
            letr = letr - 24 * Math.floor(letr / 24) - 1;

            var digraph = lettersEasting.charAt(letr);

            //First (Easting) Character Found
            letr = Math.floor(utm.northing / 100000);

            //Odd zones start with A at equator, even zones with F
            if (utm.zone / 2 == Math.floor(utm.zone / 2)) { letr = letr + 5; }
            letr = letr - 20 * Math.floor(letr / 20);
            digraph = digraph + lettersNorthing.charAt(letr);

            var digitsEasting = Math.round(utm.easting - 100000 * Math.floor(utm.easting / 100000)).toString();
            var digitsNorthing = Math.round(utm.northing - 100000 * Math.floor(utm.northing / 100000)).toString();

            if (digitsEasting.length < 5) {
                digitsEasting  = (new Array(5 - digitsEasting.length + 1)).join('0') + digitsEasting;
            }

            if (digitsNorthing.length < 5) {
                digitsNorthing  = (new Array(5 - digitsNorthing.length + 1)).join('0') + digitsNorthing;
            }

			var result = utm.zone + lettersEasting[latitudeZone] + digraph + '' + digitsEasting + '' + digitsNorthing;

			return result;
		}
	}
};


