/**
 * @name InfoBox
 * @version 1.1.12 [December 11, 2012]
 * @author Gary Little (inspired by proof-of-concept code from Pamela Fox of Google)
 * @copyright Copyright 2010 Gary Little [gary at luxcentral.com]
 * @fileoverview InfoBox extends the Google Maps JavaScript API V3 <tt>OverlayView</tt> class.
 *  <p>
 *  An InfoBox behaves like a <tt>google.maps.InfoWindow</tt>, but it supports several
 *  additional properties for advanced styling. An InfoBox can also be used as a map label.
 *  <p>
 *  An InfoBox also fires the same events as a <tt>google.maps.InfoWindow</tt>.
 */

/*!
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*jslint browser:true */
/*global google */

/**
 * @name InfoBoxOptions
 * @class This class represents the optional parameter passed to the {@link InfoBox} constructor.
 * @property {string|Node} content The content of the InfoBox (plain text or an HTML DOM node).
 * @property {boolean} [disableAutoPan=false] Disable auto-pan on <tt>open</tt>.
 * @property {number} maxWidth The maximum width (in pixels) of the mapas.InfoBox. Set to 0 if no maximum.
 * @property {Size} pixelOffset The offset (in pixels) from the top left corner of the InfoBox
 *  (or the bottom left corner if the <code>alignBottom</code> property is <code>true</code>)
 *  to the map pixel corresponding to <tt>position</tt>.
 * @property {LatLng} position The geographic location at which to display the mapas.InfoBox.
 * @property {number} zIndex The CSS z-index style value for the mapas.InfoBox.
 *  Note: This value overrides a zIndex setting specified in the <tt>boxStyle</tt> property.
 * @property {string} [boxClass="infoBox"] The name of the CSS class defining the styles for the InfoBox container.
 * @property {Object} [boxStyle] An object literal whose properties define specific CSS
 *  style values to be applied to the mapas.InfoBox. Style values defined here override those that may
 *  be defined in the <code>boxClass</code> style sheet. If this property is changed after the
 *  InfoBox has been created, all previously set styles (except those defined in the style sheet)
 *  are removed from the InfoBox before the new style values are applied.
 * @property {string} closeBoxMargin The CSS margin style value for the close box.
 *  The default is "2px" (a 2-pixel margin on all sides).
 * @property {string} closeBoxURL The URL of the image representing the close box.
 *  Note: The default is the URL for Google's standard close box.
 *  Set this property to "" if no close box is required.
 * @property {Size} infoBoxClearance Minimum offset (in pixels) from the InfoBox to the
 *  map edge after an auto-pan.
 * @property {boolean} [isHidden=false] Hide the InfoBox on <tt>open</tt>.
 *  [Deprecated in favor of the <tt>visible</tt> property.]
 * @property {boolean} [visible=true] Show the InfoBox on <tt>open</tt>.
 * @property {boolean} alignBottom Align the bottom left corner of the InfoBox to the <code>position</code>
 *  location (default is <tt>false</tt> which means that the top left corner of the InfoBox is aligned).
 * @property {string} pane The pane where the InfoBox is to appear (default is "floatPane").
 *  Set the pane to "mapPane" if the InfoBox is being used as a map label.
 *  Valid pane names are the property names for the <tt>google.maps.MapPanes</tt> object.
 * @property {boolean} enableEventPropagation Propagate mousedown, mousemove, mouseover, mouseout,
 *  mouseup, click, dblclick, touchstart, touchend, touchmove, and contextmenu events in the InfoBox
 *  (default is <tt>false</tt> to mimic the behavior of a <tt>google.maps.InfoWindow</tt>). Set
 *  this property to <tt>true</tt> if the InfoBox is being used as a map label.
 */

/**
 * Creates an InfoBox with the options specified in {@link InfoBoxOptions}.
 * Call <tt>mapas.InfoBox.open</tt> to add the box to the map.
 * @constructor
 * @param {InfoBoxOptions} [opt_opts]
 */
mapas.InfoBox = function(options) {

	"use strict";

	var self = this;

	/**
	 * Reference to the instance of the class
	 * @type {mapas.InfoBox}
	 */
	var myself = mapas.InfoBox;

	/**
	 * Prototype for the parent class for tyhe grid (google.maps.OvarlayView)
	 * @type {Object}
	 */
	var parent = null;

	/**
	 * List fo listeners for the object
	 * @type {Object}
	 */
	var listeners = {};

	/**
	 * This handler prevents an event in the InfoBox from being passed on to the map.
	 * @param  {[type]} e [description]
	 * @return {[type]}   [description]
	 */
	function cancelHandler(e) {
		
		e.cancelBubble = true;

		if (e.stopPropagation) {
			e.stopPropagation();
		}
	};

	/**
	 * This handler ignores the current event in the InfoBox and conditionally prevents 
	 * the event from being passed on to the map. It is used for the contextmenu event.
	 * @param  {[type]} e [description]
	 * @return {[type]}   [description]
	 */
	function ignoreHandler(e) {

		e.returnValue = false;

		if (e.preventDefault) {
			e.preventDefault();
		}

		if (!self.enableEventPropagation_) {
			cancelHandler(e);
		}
	};

	function updateNodeStyle() {

		var i, boxStyle;

		if (self.node) {

			// Apply style values from the style sheet defined in the boxClass parameter:
			self.node.className = self.options.boxClass;

			// Clear existing inline style values:
			self.node.style.cssText = "";

			// Apply style values defined in the boxStyle parameter:
			boxStyle = self.options.boxStyle;

			for (i in boxStyle) {
				if (boxStyle.hasOwnProperty(i)) {
					self.node.style[i] = boxStyle[i];
				}
			}

			// Apply required styles:
			self.node.style.position = 'absolute';
			self.node.style.visibility = 'hidden';

			if (self.options.zIndex !== null) {
				self.node.style.zIndex = self.options.zIndex;
			}
		}
	};

	function closeBox(e) {

		// 1.0.3 fix: Always prevent propagation of a close box click to the map:
		e.cancelBubble = true;

		if (e.stopPropagation) {
			e.stopPropagation();
		}

		/**
		 * This event is fired when the InfoBox's close box is clicked.
		 * @name InfoBox#closeclick
		 * @event
		 */
		google.maps.event.trigger(self, "closeclick");

		self.close();
	};

	/**
	 * Crate close box and append it to the ndoe
	 * @return {[type]} [description]
	 */
	function createCloseBox() {
		
		if (!self.options.closeBox || !self.node) {
			return false;
		}		

		if (self.node.querySelector('.close-box')) {
			self.node.removeChild(self.node.querySelector('.close-box'));
		}
		
		if (typeof self.options.closeBox.nodeType === 'undefined') {

			var img = document.createElement('img');
			img.src = self.options.closeBox;
			img.className = 'close-box';

			self.node.appendChild(img);

		} else {
			self.options.closeBox.className += ' close-box';
			self.node.appendChild(self.options.closeBox);
		}		

		/*
		img  = "<img";
		img += " src='" + this.closeBoxURL_ + "'";
		img += " align=right"; // Do this because Opera chokes on style='float: right;'
		img += " style='";
		img += " position: relative;"; // Required by MSIE
		img += " cursor: pointer;";
		img += " margin: " + this.closeBoxMargin_ + ";";
		img += "'>";
		*/
	};
	
	function createTitle() {
		
		var title;
		
		if (!self.options.title || !self.node) {
			return false;
		}
		
		if (self.node.querySelector('.infobox-title')) {
			self.node.removeChild(self.node.querySelector('.infobox-title'));
		}
		
		title = document.createElement('h1');
		title.innerHTML = self.options.title;
		title.className = 'infobox-title';
		
		self.node.appendChild(title);
	}

	function panBox(disablePan) {

		var map, bounds;
		var xOffset = 0, yOffset = 0;

		if (!disablePan) {

			map = self.getMap();

			if (map instanceof google.maps.Map) { // Only pan if attached to map, not panorama

				if (!map.getBounds().contains(self.options.position)) {
					// Marker not in visible area of map, so set center
					// of map to the marker position first.
					map.setCenter(self.options.position);
				}

				bounds = map.getBounds();

				var mapDiv = map.getDiv();
				var mapWidth = mapDiv.offsetWidth;
				var mapHeight = mapDiv.offsetHeight;

				var iwOffsetX = self.options.pixelOffset.width;
				var iwOffsetY = self.options.pixelOffset.height;
				var iwWidth = self.node.offsetWidth;
				var iwHeight = self.node.offsetHeight;

				var padX = self.options.infoBoxClearance.width;
				var padY = self.options.infoBoxClearance.height;
				var pixels = self.getProjection().fromLatLngToContainerPixel(self.options.position);

				if (pixels.x < (-iwOffsetX + padX)) {
					xOffset = pixels.x + iwOffsetX - padX;
				} else if ((pixels.x + iwWidth + iwOffsetX + padX) > mapWidth) {
					xOffset = pixels.x + iwWidth + iwOffsetX + padX - mapWidth;
				}

				if (self.options.alignBottom) {

					if (pixels.y < (-iwOffsetY + padY + iwHeight)) {
						yOffset = pixels.y + iwOffsetY - padY - iwHeight;
					} else if ((pixels.y + iwOffsetY + padY) > mapHeight) {
						yOffset = pixels.y + iwOffsetY + padY - mapHeight;
					}

				} else {

					if (pixels.y < (-iwOffsetY + padY)) {
						yOffset = pixels.y + iwOffsetY - padY;
					} else if ((pixels.y + iwHeight + iwOffsetY + padY) > mapHeight) {
						yOffset = pixels.y + iwHeight + iwOffsetY + padY - mapHeight;
					}

				}

				if (!(xOffset === 0 && yOffset === 0)) {

					// Move the map to the shifted center.
					var c = map.getCenter();
					map.panBy(xOffset, yOffset);
				}
			}
		}

		return false;
	};

	function updateContent() {

		if (self.listeners.close) {
			google.maps.event.removeListener(self.listeners.close);
			self.listeners.close = null;
		}

		createCloseBox();
		createTitle();

		if (typeof self.options.content.nodeType === 'undefined') {
			self.node.innerHTML += self.options.content;
		} else {
			self.node.appendChild(self.options.content);
		}

		// Add click hanlder to close box
		self.listeners.close = google.maps.event.addDomListener(self.node.querySelector('.close-box'), "click", closeBox);

		self.trigger('update');
	};

	function createNode() {

		var i, events, bw;

		if (!self.node) {

			self.node = document.createElement("div");
			updateNodeStyle();

			updateContent();	

			// Add the InfoBox DIV to the DOM
			self.getPanes()[self.options.pane].appendChild(self.node);

			// The maxwidth, just use css
			
			if (self.options.maxWidth) {
				self.node.style.maxWidth = self.options.maxWidth + 'px';
			}	

			panBox(self.options.disableAutoPan);

			if (!self.options.enableEventPropagation) {

	  			self.listeners.toPropagate = [];

				// Cancel event propagation.
				events = ["mousedown", "mouseover", "mouseout", "mouseup", "click", "dblclick", "touchstart", "touchend", "touchmove", "mousemove"];

	  			for (i = 0; i < events.length; i++) {
					self.listeners.toPropagate.push(google.maps.event.addDomListener(self.node, events[i], cancelHandler));
	  			}
	  
				// Workaround for Google bug that causes the cursor to change to a pointer
				// when the mouse moves over a marker underneath mapas.InfoBox.
	  			self.listeners.toPropagate.push(google.maps.event.addDomListener(self.node, "mouseover", function (e) {
					self.node.style.cursor = "default";
	  			}));
			}

			self.listeners.context = google.maps.event.addDomListener(self.node, "contextmenu", ignoreHandler);

			/**
			 * This event is fired when the DIV containing the InfoBox's content is attached to the DOM.
			 * @name InfoBox#domready
			 * @event
			 */
			google.maps.event.trigger(self, "domready");
		}
	};

	function init(options) {
		
		options = options || {};

		self.options = {}

		// Common with google.maps.InfoWindow
		self.options.content = options.content || "";
		self.options.disableAutoPan = options.disableAutoPan || false;
		self.options.maxWidth = options.maxWidth || 0;
		self.options.pixelOffset = options.pixelOffset || new google.maps.Size(0, 0);
		self.options.position = options.position || new google.maps.LatLng(0, 0);
		self.options.zIndex = options.zIndex || null;

		// Additional options (unique to InfoBox):
		
		// Title
		self.options.title = options.title || '';

		// Style options
		self.options.boxClass = options.boxClass || "infoBox";
		self.options.boxStyle = options.boxStyle || {};
		self.options.closeBoxMargin = options.closeBoxMargin || "2px";

		// Not defined for default, empty for no close button
		self.options.closeBox = options.closeBox || "http://www.google.com/intl/en_us/mapfiles/close.gif";

		self.options.visible = options.visible || true;

		// Position of the infobox
		self.options.alignBottom = options.alignBottom || false;
		self.options.infoBoxClearance = options.infoBoxClearance || new google.maps.Size(1, 1);
		self.options.pane = options.pane || "floatPane";
		self.options.enableEventPropagation = options.enableEventPropagation || false;

		// Base node for the infobox
		self.node = null;

		// Listeners
		self.listeners = {
			close: null,
			move: null,
			context: null,
			toPropagate: [], // list of listeners to propagate
			resize: null
		};

		self.listeners.resize = google.maps.event.addListener(self, "resize", function () {
  			self.draw();
		});
	}

	/**
	 * Draws the InfoBox based on the current map projection and zoom level.
	 */
	self.draw = function () {

		createNode();

		var pixels = self.getProjection().fromLatLngToDivPixel(self.options.position);

		self.node.style.left = (pixels.x + self.options.pixelOffset.width) + "px";

		if (self.options.alignBottom) {
			self.node.style.bottom = -(pixels.y + self.options.pixelOffset.height) + "px";
		} else {
			self.node.style.top = (pixels.y + self.options.pixelOffset.height) + "px";
		}

		if (self.options.visible) {
			self.node.style.visibility = 'visible';
		} else {
			self.node.style.visibility = 'hidden';
		}

		if (!self.options.disableAutoPan) {
			panBox();
		}

		self.trigger('draw');
	};

	self.onRemove = function () {
		if (self.node) {
            
            self.trigger('beforeRemove');
            
			self.node.parentNode.removeChild(self.node);
			self.node = null;
		}

		self.trigger('remove');
	};

	self.setOptions = function (options) {

		if (options.boxClass) { // Must be first
			self.options.boxClass = options.boxClass;
			//updateNodeStyle();
		}

		if (options.boxStyle) { // Must be second
			self.options.boxStyle = options.boxStyle;
			//updateNodeStyle();
		}

		if (options.content) {
			self.setContent(options.content);
		}

		if (options.disableAutoPan !== null) {
			self.option.disableAutoPan = options.disableAutoPan;
		}

		if (options.maxWidth) {
			self.options.maxWidth = options.maxWidth;
			//updateNodeStyle();
		}

		if (options.pixelOffset) {
			self.options.pixelOffset = options.pixelOffset;
		}

		if (options.alignBottom !== null) {
			self.options.alignBottom = options.alignBottom;
		}

		if (options.position) {
			self.options.setPosition(options.position);
		}

		if (options.zIndex) {
			self.setZIndex(options.zIndex);
		}

		if (options.closeBoxMargin) {
			self.options.closeBoxMargin = options.closeBoxMargin;
			updateNodeStyle();
		}

		if (options.closeBox !== null) {
			self.options.closeBox = options.closeBox;
			//updateContent ???? 
			//self.setContent(options.content);
		}

		if (options.infoBoxClearance) {
			self.options.infoBoxClearance = options.infoBoxClearance;
		}

		if (options.visible !== null) {
			self.options.visible = options.visible;
		}

		if (options.enableEventPropagation !== null) {
			self.options.enableEventPropagation = options.enableEventPropagation;
		}

		if (self.node) {
			self.draw();
		}
	};

	self.setContent = function (content) {

		self.options.content = content;

		if (self.node) {
			updateContent();
		}

		/**
		 * This event is fired when the content of the InfoBox changes.
		 * @name InfoBox#content_changed
		 * @event
		 */
		google.maps.event.trigger(self, "content_changed");
	};
	
	self.setTitle = function (title) {

		self.options.title = title;

		if (self.node) {
			updateContent();
		}

		/**
		 * This event is fired when the content of the InfoBox changes.
		 * @name InfoBox#content_changed
		 * @event
		 */
		google.maps.event.trigger(self, "content_changed");
	};

	self.setPosition = function (latlng) {

		self.options.position = latlng;

		if (self.node) {
			self.draw();
		}

		/**
		 * This event is fired when the position of the InfoBox changes.
		 * @name InfoBox#position_changed
		 * @event
		 */
		google.maps.event.trigger(self, "position_changed");
	};

	self.setZIndex = function (index) {

		self.options.zIndex = index;

		if (self.node) {
			self.node.style.zIndex = index;
		}

		/**
		 * This event is fired when the zIndex of the InfoBox changes.
		 * @name InfoBox#zindex_changed
		 * @event
		 */
		google.maps.event.trigger(self, "zindex_changed");
	};

	self.setVisible = function (visible) {

		self.visible = visible;

		if (self.node) {
			self.node.style.visibility = (visible ? 'visible' : 'hidden');
		}
	};

	self.getContent = function() {
		return self.options.content;
	};
	
	self.getTitle = function() {
		return self.options.title;
	};

	self.getPosition = function() {
		return self.options.position;
	};

	self.getZIndex = function() {
		return self.options.zIndex;
	};

	self.getVisible = function() {
	  return visible;
	};

	self.show = function() {

		self.options.visible = true;

		if (self.node) {
			self.node.style.visibility = 'visible';
		}
	};

	self.hide = function () {

		self.options.visible = false;

		if (self.node) {
			self.ndoe.style.visibility = 'hidden';
		}
	};

	self.open = function(map, anchor) {

		if (anchor) {

			self.options.position = anchor.getPosition();
			self.listeners.move = google.maps.event.addListener(anchor, "position_changed", function () {
	  			self.setPosition(self.getPosition());
			});
		}

		self.setMap(map);

		if (self.node) {
			panBox();
		}
	};

	self.close = function () {

		var i;

		if (self.listeners.close) {

			google.maps.event.removeListener(self.listeners.close);
			self.listeners.close = null;
		}

		if (self.listeners.toPropagate) {

			for (i = 0; i < self.listeners.toPropagate.length; i++) {
				google.maps.event.removeListener(self.listeners.toPropagate[i]);
			}

			self.listeners.toPropagate = [];
		}

		if (self.listeners.close) {
			google.maps.event.removeListener(self.listeners.close);
			self.listeners.close = null;
		}

		if (self.listeners.context) {
			google.maps.event.removeListener(self.listeners.context);
			self.listeners.context = null;
		}

		self.setMap(null);
	};

	self.update = function() {
		updateContent();
		self.draw();
	};

	self.on = function (type, handler) {
		
		if (!listeners[type]) {
			listeners[type] = {};
		}
		
		if (!listeners[type][handler.toString()]) {
			listeners[type][handler.toString()] = handler;
		}
		
	};
	
	self.off = function (type, handler) {
		
		if (listeners[type] && listeners[type][handler.toString()]) {
			delete listeners[type][handler.toString()];
		}
	};
	
	self.trigger = function (type, context) {
		var handlers = listeners[type],
			code;
		
		for (code in handlers) {
			if (handlers.hasOwnProperty(code)) {
				handlers[code].call(this, context);
			}
		}
	};

	// Call the constructor
	init.apply(this, arguments);
}

// When libraries are ready extend the overlayview with the grid
mapas.actionOnLibraryLoad.push(function(){
	mapas.InfoBox.prototype = new google.maps.OverlayView;
	mapas.InfoBox.parent = google.maps.OverlayView.prototype;
	mapas.InfoBox.constructor = mapas.InfoBox;
});