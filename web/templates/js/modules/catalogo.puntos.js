/*jslint browser:true */
/*global google, $, mapas, common, config, catalogo, Blob, BlobBuilder, console, i18n */

/**
 * Point data module
 */
catalogo.puntos = (function () {
	
	'use strict';
	
	/**
	 * Public interface for the module
	 * @property {Object}
	 * @private
	 */
	var self = {};
	
	/**
	 * Reload the grid
	 * @method
	 * @private
	 */
	function reloadGrid() {
		self.grid.clearCells();
		self.grid.reload();
	}
	
	function mouseover(evt, x, y) {
		//this.toFront();
		this.animate({'fill-opacity': 1/*, transform: 's1.2'*/}, 100, 'linear');
	}
	
	function mouseout(evt, x, y) {
		this.animate({'fill-opacity': 0.5/*, transform: 's1'*/}, 100, 'linear');
	}
	
	function getInfoContent(item) {
		var $clon = $(item.dom).clone();
		
		// Make links open in new tab
		//$clon.find('a').attr('target', '_blank');
		
		//Add class
		$clon.addClass('tooltip-avistamento');
		$clon.attr('id', item.id);
		
		$clon.wrap('<div class="item"></div>');
		
		return $clon;
	}
	
	function updateInfo() {
		var info = this;
	}
	
	function getListContent(id, items) {
		
		var $base,
			$content,
			item,
			i;
		
		$base = $('<div id="' + id + '" class="item-list">');
		$content = $('<div>');
		
		for (i = 0; i < items.length; i += 1) {
			item = items[i];
			
			if ($content.find('[data-codigo="' + item.dom.attr('id') + '"]').length <= 0) {
				$content.append(getInfoContent(item));
			}
		}
		
		$base.append($content);
		
		$base.miniSlider({stepWidth: 300});
		
		return $base;
	}
	
	function showInfo() {
		
		var i,
			items = this.context,
			item,
			$item,
			$closeIcon = $('<i class="icon-remove"></i>'),
			content,
			$node,
			$content,
			$base,
			info,
			title,
			$nav,
			$next,
			$prev,
			$navtext,
			parentCode,
			parent,
			$tabs,
			lat,
			lng,
			children;
		
		parentCode = this.parent;

		$node = $('<div>');
		
		if (items.constructor !== Array) {
		
			item = items;
			$node.append(getInfoContent(item));
			
		} else {
			
			item = items[0];
			
			
			$node.append(getListContent('centroids1000', items));
			
			if (parentCode && self.cells[10000][parentCode]) {
				
				$tabs = $('<ul class="tabs"><li><a href="#centroids1000">' +
							i18n.catalogo.CENTROIDES_1000 +
						  '</a></li><li><a href="#centroids10000">' +
							i18n.catalogo.CENTROIDES_10000 +
						  '</a></li></ul>');
				
				$node.prepend($tabs);
		
				parent = self.cells[10000][parentCode];

			    $node.append(getListContent('centroids10000', parent.data));
			
			    $node.tabs();
			}
			
			$node.on('tabShow', function () {
				if (catalogo.mapa.mapa.info && catalogo.mapa.mapa.info.node) {
					catalogo.mapa.mapa.info.update();
				}
			});
			
			children = $node.find('.item-list > div').children().length;
			
			if (children > 1) {
				$nav = $('<nav>');
				$next = $('<button class="next-item btn">').on('click', function () {
					$('.infoBox .item-list:visible').miniSlider('goto', 1);
				}).html('<i class="icon-angle-right"></i>').appendTo($nav);
				
				$prev = $next.clone().removeClass('next-item').addClass('previous-item').html('<i class="icon-angle-left"></i>').on('click', function () {
					$('.infoBox .item-list:visible').miniSlider('goto', -1);
				}).appendTo($nav);
				
				$navtext = $('<span class="nav-text"></span>').html(i18n.catalogo.NAV_INFOBOX).appendTo($nav);
				
				$node.append($nav);
			}
			
			//title = i18n.catalogo.TITLE_INFOBOX + items.length;
			title = i18n.catalogo.TITLE_INFOBOX + children;
		}
		
		lat = item.latitude;
		lng = item.lonxitude;
		
		if (this.data('center')) {
			lat = this.data('center').lat;
			lng = this.data('center').lng;
		}
		
		
		info = catalogo.mapa.mapa.showInfo({
			text: $node[0],
			lat: lat,
			lng: lng
		}, null,  {
			infoBoxClearance: new google.maps.Size(350, 60),
			pixelOffset: new google.maps.Size(-156, -24),
			closeBox: $closeIcon[0],
			title: title
		});
		
		info.on('draw', updateInfo);
	}
	
	function drawPoint(especie, punto) {
		
		var point = self.grid.drawPoint(punto.latitude, punto.lonxitude, 6, {
			fill: especie.color,
			stroke: especie.color,
			'stroke-width': 1,
			'fill-opacity': 0.5
		}, punto);
		
		return point;
	}
	
	function drawCell(especies, centroide, size, corner) {
		
		var cell,
			data,
			code,
			especie;
		
		data = {'style': []};
		
		for (especie in especies) {
			
			if (especies.hasOwnProperty(especie)) {
				data.style.push({
				    'fill': especies[especie].color,
				    'stroke': especies[especie].color,
				    'stroke-width': 1,
				    'fill-opacity': 0.5
				});
			}
		}
				
		if (!corner) {
			data.lat = centroide.latitude;
			data.lng = centroide.lonxitude;
		}
		
		cell = self.grid.drawMultiCell(corner, size, data, centroide);
		//cell = self.grid.drawCell(data, size, centroide, corner);
		
		return cell;
	}
	
	function drawData(especie, type, data, size) {
		
		var item;
		
		if (type === 'point') {
			
			item = drawPoint(especie, data);
			self.puntos.push(item);
			
		} else if (type === 'point-cell') {

			item = drawCell(especie, data, size);
			self.puntos.push(item);
			
		} else if (type === 'centroid1') {
			
			item = drawCell(especie, data, size);
			self.centroides1.push(item);
		
		} else if (type === 'centroid10') {
			
			item = drawCell(especie, data, size);
			self.centroides10.push(item);
			
		} else {
			throw new Error('The data type ' + type + ' is not implemented!');
		}
		
		item.mouseover(mouseover);
		item.mouseout(mouseout);
		item.click(showInfo);
	}
	
	function getCornerCode(corner) {
		return corner.zone + '-' + corner.hemisphere + '-' + corner.easting + '-' + corner.northing;
	}
	
	function prepareCells(especie, type, data, size) {
		
		var sizes = [10000],
			corners,
			code,
			key;
		
		if (size === 1000) {
			sizes = [10000, 1000];
		}
		
		data.lat = data.latitude;
		data.lng = data.lonxitude;
		
		corners = self.grid.getCellCorners(data, sizes);
		
		key = parseInt(size, 10);
		
		
		code = getCornerCode(corners[key]);
		
		if (!self.cells[key][code]) {
			self.cells[key][code] = {corner: corners[key], length: 0, especies: {}, data: [], types: {}};
			
			if (size === 1000) {
				self.cells[key][code].parent = getCornerCode(corners[10000]);
			}
		}
		
		if (!self.cells[key][code].especies[especie.code]) {
			self.cells[key][code].especies[especie.code] = especie;
			self.cells[key][code].length += 1;
		}
		
		data.type = type;
		data.size = size;
		
		self.cells[key][code].data.push(data);
		self.cells[key][code].types[type] = true;
		
		//self.cells[key][code].especies[especie.code].push(especie);
	}
	
	function drawCells() {
		
		var i,
		    j,
			sizes,
			size,
			code,
			cellData,
			cell;
		
		sizes = [10000, 1000];
		
		for (i = 0; i < sizes.length; i += 1) {
			
			size = parseInt(sizes[i], 10);

			for (code in self.cells[size]) {
				
				if (self.cells[size].hasOwnProperty(code)) {
					cellData = self.cells[size][code];
					
					cell = drawCell(cellData.especies, cellData.data, size, cellData.corner);
					
					if (cell.push) {
					    for (j = 0; j < cell.length; j += 1) {
					        cell[j].code = code;
					        cell[j].parent = cellData.parent;
					    }
					} else {
						cell.code = code;
						cell.parent = cellData.parent;
                    }
					
					if (parseInt(size, 10) === 1000) {
						self.cells1.push(cell);
						
						cell.mouseover(mouseover);
						cell.mouseout(mouseout);
						
					} else {
						self.cells10.push(cell);
						
						if (cell.push) {
							for (j = 0; j < cell.length; j += 1) {
								cell[j].attr('fill-opacity', '0.2');
							}
						} else {
							cell.attr('fill-opacity', '0.2');
						}
					}
					
					
					cell.click(showInfo);
				}
			}
				
		}
		
		self.cells1.toFront();
	}
				   
	function getSmallCellSize() {
		var size;
	
		// If the map is zoom out render 1km2 centroides as 10km2 centroides so we can see them
		size = 1000;
		if (catalogo.mapa.mapa.innerMap.getZoom() < config.catalogo.ZOOM_CENTROIDES1 || self.gridSize === '10km') {
			size = 10000;
		}
	
		return size;
	}
	
	function loadPoints() {
		var code,
			especie,
			i,
			size,
			type,
			method;
		
		type = (catalogo.puntos.visualization === 'grid') ? 'point-cell' : 'point';
		
		method = drawData;
		if (type === 'point-cell') {
			method = prepareCells;
		}
		
		for (code in catalogo.especies.especies) {
			
			if (catalogo.especies.especies.hasOwnProperty(code)) {
				
				especie = catalogo.especies.especies[code];
				
				if (especie.puntos) {
					for (i = 0; i < especie.puntos.length; i += 1) {
						method.call(self, especie, type, especie.puntos[i], getSmallCellSize());
					}
				}
			}
		}
	}
	
	function loadCentroids1() {
		var code,
			especie,
			i,
			size;
		
		for (code in catalogo.especies.especies) {
			
			if (catalogo.especies.especies.hasOwnProperty(code)) {
				
				especie = catalogo.especies.especies[code];
				
				if (especie.centroides1) {

					for (i = 0; i < especie.centroides1.length; i += 1) {
						prepareCells(especie, 'centroid1', especie.centroides1[i], getSmallCellSize());
					}
				}
			}
		}
	}
	
	function loadCentroids10() {
		
		var code,
			especie,
			i,
			size;
		
		size = 10000;
		
		for (code in catalogo.especies.especies) {
			
			if (catalogo.especies.especies.hasOwnProperty(code)) {
				
				especie = catalogo.especies.especies[code];
				
				if (especie.centroides10) {
					
					for (i = 0; i < especie.centroides10.length; i += 1) {
						prepareCells(especie, 'centroid1', especie.centroides10[i], size);
					}
				}
			}
		}
	}
	
	function updateExport() {
		var $export = $(catalogo.mapa.elements.exportGrids),
			content,
			blob,
			bb;
		
		content = '<' + '?xml version="1.0" encoding="utf-8"?>' + $(self.grid.node).html();

		//Remove the opacities
		content = content.replace(/opacity: (\d)(\.\d)*/ig, 'opacity: 1');
		content = content.replace(/opacity="(\d)(\.\d)*/ig, 'opacity="1');
		
		content = content.replace(/fill-opacity: (\d)(\.\d)*/ig, 'opacity: 1');
		content = content.replace(/fill-opacity="(\d)(\.\d)*/ig, 'opacity="1');

		window.URL = window.URL || window.webkitURL;
		window.BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder || window.MSBlobBuilder;

		if (window.Blob) {
			blob = new Blob([content], {type: 'image/svg+xml'});
		} else if (window.BlobBuilder) {
			
			bb = new BlobBuilder();
			bb.append(content);
			blob = bb.getBlob('image/svg+xml');
		}

		if (window.URL && window.URL.createObjectURL && blob) {
			$export.attr('download', 'grid.svg');
			$export.attr('target', '_blank');
			$export.attr('href', window.URL.createObjectURL(blob));
			$export.removeClass('disabled');
			
			$export.off('click');
		}
	}
	
	/**
	 * Update the point data with the sightings loaded
	 * @method
	 * @private
	 */
	function update() {
		
		var especies = catalogo.especies.especies,
			code,
			especie;
		
		if (self.grid && self.grid.paper) {
			self.grid.clearCells();
			self.grid.clearPoints();
			
			self.cells = {
				10000: {},
				1000: {}
			};
			
			// Initialize arrays
			self.puntos = self.grid.paper.set();
			self.centroides1 = self.grid.paper.set();
			self.centroides10 = self.grid.paper.set();
			self.shapes = self.grid.paper.set();
			self.especies = {};
			
			self.cells1 = self.grid.paper.set();
			self.cells10 = self.grid.paper.set();
			
			
			
			if (catalogo.puntos.visualization !== 'points') {
                
                if (catalogo.puntos.visualization !== 'grid' || !self.gridSize || self.gridSize === 'all') {
                    loadPoints();
                    loadCentroids10();
                    loadCentroids1();
                } else if (self.gridSize === '10km') {
                    loadCentroids10();
                    loadPoints();
                    loadCentroids1();
                } else if (self.gridSize === '1km') {
                    loadPoints();
                    loadCentroids1();
                }
                    
				drawCells();
			} else {
                loadPoints();
            }
			
			updateExport();
		}
	}
	
	/**
	 * List of the data for each specie, including points, centroides and shapes
	 * @property {Object}
	 * @public
	 */
	self.especies = {};
	
	/**
	 * List of the points for the map
	 * @property {Raphael.set}
	 * @public
	 */
	self.puntos = null;
	
	/**
	 * List of the 1km2 centroids for the map
	 * @property {Raphael.set}
	 * @public
	 */
	self.centroides1 = null;
	
	/**
	 * List of the 10km2 centroids for the map
	 * @property {Raphael.set}
	 * @public
	 */
	self.centroides10 = null;
	
	/**
	 * List of the shapes for the map (kml files)
	 * @property {Raphael.set}
	 * @public
	 */
	self.shapes = null;
	
	/**
	 * Grid object
	 * @property {mapas.UTMGrid}
	 * @public
	 */
	self.grid = null;
	
	/**
	 * List of the cells of the grid
	 */
	self.cells = {};
	
	
	self.cells1 = null;
	
	
	self.cells10 = null;
    
    self.gridSize = 'all';
	
	/**
	 * List of the points for the map
	 * @property {Object}
	 * @public
	 */
	self.elements = {
		
	};
	
	/**
	 * Initialization method
	 * @method
	 * @public
	 */
	self.init = function () {
		
		var gridConfig = config.catalogo.GRID_CONFIG;
		
		// After draw callback
		gridConfig.afterDraw = update;
			
		// Initialize grid
		self.grid = mapas.makeGrid("catalogo", 'utm', gridConfig);
		self.grid.setMap(catalogo.mapa.mapa);
		
		// Remove transition
		self.grid.transition.speed = 0;
		
		// Hanlde sightning update
		catalogo.avistamentos.on('update', update);
	};
	
	/**
	 * Shows or hides the full UTM grid
	 * @method
	 * @public
	 *
	 * @param {Boolean} value True to show, false to hide
	 */
	self.showGrid = function (value) {
		
		self.grid.drawGuides = value;
		reloadGrid();
	};
	
	self.update = function () {
		update();
	};
	
	return self;
	
}());