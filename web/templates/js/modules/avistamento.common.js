/*jslint browser:true */
/*global google, $, mapas, config, common, i18n, config, console, alert, confirm */

if (!window.avistamento) {
    var avistamento = {};
}

/**
 * Modulo avistamentos
 */
avistamento.common = {
    
    infoboxMixin: function () {
        
        'use strict';
        
        var self = this,
            i18n,
            map;
        
        function getInfoItem(shape) {
        
            var code = shape.avistamento || shape.context,
                $item = $(self.elements.avistamento + '[data-codigo="' + code + '"]'),
                $clon = $item.clone(),
                $parent;
            
            $clon.wrap('<div class="item tooltip-avistamento datos-avistamento"></div>');
            $parent = $clon.parent();
            $parent.attr('id', $item.attr('id'));
            
            return $parent;
        }
        
        function setInfoItems($node, shapes) {
            
            var i,
                longShapes,
                shape,
                $base,
                $content;
            
            for (i = 0, longShapes = shapes.length; i < longShapes; i += 1) {
                shape = shapes[i];
                
                $base = $node.find('#centroids' + shape.size);
                
                if ($base.length) {
                    $content = $base.find('> div');
                } else {
                    $content = $node;
                }
                
                if (!$base.find('[data-codigo="' + shape.code + '"]').length) {
                    $content.append(getInfoItem(shape));
                }
            }
            
            $node.find('.item-list').miniSlider({stepWidth: 300});
            
        }
        
        function initInfoTabs($node, size) {
            
            var $tabs;
            
            
            if (size) {
                $node.prepend($('<div id="centroids' + size + '" class="item-list"><div></div></div>'));
            } else {
                
                $tabs = $('<ul class="tabs"><li><a href="#centroids1000">' +
                              i18n.CENTROIDES_1000 +
                              '</a></li><li><a href="#centroids10000">' +
                              i18n.CENTROIDES_10000 +
                              '</a></li></ul>' +
                              '<div id="centroids1000" class="item-list"><div></div></div>' +
                             '<div id="centroids10000" class="item-list"><div></div></div>');
                
                $node.prepend($tabs);
                $node.tabs();
                
                $node.on('tabShow', function () {
                    if (map.info && map.info.node) {
                        map.info.update();
                    }
                });
            }
        }
        
        function initInfoSlider($node) {
            
            var children = $node.find('.item-list > div').children().length,
                $nav,
                $next,
                $prev,
                $navText;
                
            if (children > 1) {
                $nav = $('<nav>');
                $next = $('<button class="next-item btn">').on('click', function () {
                    $('.infoBox .item-list:visible').miniSlider('goto', 1);
                }).html('<i class="icon-angle-right"></i>').appendTo($nav);
                
                $prev = $next.clone().removeClass('next-item').addClass('previous-item').html('<i class="icon-angle-left"></i>').on('click', function () {
                    $('.infoBox .item-list:visible').miniSlider('goto', -1);
                }).appendTo($nav);
                
                $navText = $('<span class="nav-text"></span>').html(i18n.NAV_INFOBOX).appendTo($nav);
                
                $node.append($nav);
            }
            
            return children;
        }
        
        self.avistamentos = {
            
            init: function (texts, mapa) {
                i18n = texts;
                map = mapa;
            },
        
            showInfo: function (e, shape) {
            
                var corner = shape.corner,
                    $node = $('<div>'),
                    $avistamento,
                    info,
                    $tabs,
                    parents,
                    items = [],
                    children,
                    title,
                    lat,
                    lng;
                
                map.closeInfo();
                
                if (!corner) {
                    $node.append(getInfoItem(shape));
                    title = i18n.TITLE_INFOBOX_POINT;
                } else {
                    
                    parents = self.shapes.getCorner(shape.parent);
                    
                    if (shape.size === 1000 && parents) {
                        initInfoTabs($node);
                        items = items.concat(parents);
                    } else {
                        initInfoTabs($node, shape.size);
                    }
                     
                    items = items.concat(self.shapes.getCorner(shape.corner));
                    $node.append(setInfoItems($node, items));
                    
                    children = initInfoSlider($node);
                    title = i18n.TITLE_INFOBOX + children;
                }
                
                lat = e ? e.latLng.lat() : shape.points[0].latitude;
                lng = e ? e.latLng.lng() : shape.points[0].longitude;
                
                map.showInfo(
                    {text: $node[0], lat: e.latLng.lat(), lng: e.latLng.lng()},
                    null,
                    {infoBoxClearance: new google.maps.Size(350, 60), pixelOffset: new google.maps.Size(-156, -24), closeBox: $('<i class="icon-remove"></i>')[0], title: title}
                );
            }
        };
    }
};