var GoogleMaps;

function initMap () {
    var $locations = $('[data-map-location]');

    var mapOptions = {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 11
    }

    if ($locations.length > 0) {
        if ($locations.length === 1) {
            var location = $locations.first().data('map-location').split(',');

            mapOptions.center = new google.maps.LatLng(location[0], location[1]);

            if (typeof location[2] !== 'undefined') {
                mapOptions.zoom = parseInt(location[2]);
            }
        } else {
            mapOptions.center = new google.maps.LatLng(0, 0);
        }
    }

    GoogleMaps = new google.maps.Map(document.getElementById('map'), mapOptions);

    GoogleMaps.$locations = $locations;

    if ($locations.length > 0) {
        setLocations();
    }
}

function setLocations () {
    var bounds = new google.maps.LatLngBounds();
    var infoWindow = new google.maps.InfoWindow();

    GoogleMaps.$locations.each(function () {
        var $this = $(this);
        var location = $this.data('map-location').split(',');
        var myLatLng = new google.maps.LatLng(location[0], location[1]);

        if (GoogleMaps.$locations.length > 1) {
            bounds.extend(myLatLng);
            GoogleMaps.fitBounds(bounds);
        }

        var marker = new google.maps.Marker({
            content: $this.html(),
            position: myLatLng,
            map: GoogleMaps
        });

        google.maps.event.addListener(marker, 'click', function () {
            if (marker.content.length === 0) {
                return;
            }

            infoWindow.close();
            infoWindow.setContent(marker.content);
            infoWindow.open(GoogleMaps, marker);
        });
    });
}

$(document).ready(function () {
    if ($('#map').length && $('#map').is(':visible')) {
        google.maps.event.addDomListener(window, 'load', initMap);
    }

    var $placemarks = $('.list-placemarks');

    $placemarks.on('click', '> a', function (e) {
        e.preventDefault();

        var id = $(this).attr('href').match(/[0-9]+$/)[0];

        if (!GoogleMaps.geoXmlDoc.placemarks[id].marker.getMap()) {
            GoogleMaps.geoXmlDoc.placemarks[id].marker.setMap(GoogleMaps);
        }

        GoogleMaps.panTo(GoogleMaps.geoXmlDoc.placemarks[id].marker.getPosition());

        google.maps.event.trigger(GoogleMaps.geoXmlDoc.placemarks[id].marker, 'click');
    });
});
