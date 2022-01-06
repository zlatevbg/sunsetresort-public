<div class="magnific-popup-map">
    <div id="map"></div>

    <script>
    var jsFiles = [];
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/leaflet.js') }}');
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/leaflet.extra-markers.js') }}');

    Modernizr.load([
        {
            load: jsFiles,
            complete: function() {
                var markers = {!! json_encode($markers) !!};
                var markersBounds = [];

                var map = L.map('map', {
                    center: [0, 0],
                    zoom: 0
                });

                L.tileLayer('https://{{ \Locales::getDomain()->slug }}.{{ config('app.domain') }}/tiles/{z}/{y}/{x}.jpg', {
                    minZoom: 0,
                    maxZoom: 5,
                    noWrap: true,
                }).addTo(map);

                $.each(markers, function(key, value) {
                    var marker = L.marker([value.lat, value.lng], {
                        title: value.name,
                        icon: L.ExtraMarkers.icon({
                            icon: 'number',
                            markerColor: value.color ? value.color : 'red',
                            number: (key + 1),
                        }),
                    }).addTo(map);

                    markersBounds.push([value.lat, value.lng]);

                    marker.bindPopup(value.content ? '<div class="infoWindow">' + value.content + '</div>' : value.name);
                });

                map.setZoom(map.getBoundsZoom(markersBounds));
                // map.fitBounds(markersBounds);

                {{--var markersBounds = new google.maps.LatLngBounds();
                var markers = {!! json_encode($markers) !!};
                var infowindow = new google.maps.InfoWindow();
                var TILE_SIZE = 256;
                var mapMinZoom = 1;
                var mapMaxZoom = 4;
                var windowWidth = $(window).width();

                if (windowWidth < 1024) {
                    mapMinZoom = 2;
                } else if (windowWidth < 2048) {
                    mapMinZoom = 3;
                } else {
                    mapMinZoom = 4;
                }

                var maptiler = new google.maps.ImageMapType({
                    getTileUrl: function(coord, zoom) {
                        var tilesCount = Math.pow(2, zoom);
                        var x = coord.x;
                        var y = coord.y;

                        if (x >= tilesCount || x < 0) {
                            // x = (x % tilesCount + tilesCount) % tilesCount; // repeat horizontally
                            return null; // return "blank.png";
                        }

                        if (y >= tilesCount || y < 0) {
                            // y = (y % tilesCount + tilesCount) % tilesCount; // repeat vertically
                            return null; // return "blank.png";
                        }

                        return 'https://{{ \Locales::getDomain()->slug }}.{{ config('app.domain') }}/tiles/' + zoom + '/' + y + '/' + x + '.png';
                    },
                    tileSize: new google.maps.Size(TILE_SIZE, TILE_SIZE),
                    minZoom: mapMinZoom,
                    maxZoom: mapMaxZoom,
                });

                var map = new google.maps.Map(document.getElementById('map'), {
                    disableDefaultUI: true,
                    scrollwheel: false,
                    zoomControl: true,
                    mapTypeId: 'maptiler',
                    backgroundColor: 'rgb(255, 255, 255)',
                    center: new google.maps.LatLng(0, 0),
                    zoom: mapMinZoom,
                    minZoom: mapMinZoom,
                    maxZoom: mapMaxZoom,
                });

                map.mapTypes.set('maptiler', maptiler);

                map.addListener('zoom_changed', function() {
                    var center = map.getCenter();
                    var width;
                    var height;

                    switch (map.getZoom()) {
                        case 1:
                            width = 512;
                            break;
                        case 2:
                            width = 1024;
                            break;
                        case 3:
                            width = 2048;
                            break;
                        case 4:
                            width = 4096;
                            break;
                        default:
                            break;
                    }

                    if (width > windowWidth) {
                        width = '100%';
                    }

                    $('.map-wrapper').css({width: width});
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(center);
                });

                $.each(markers, function(key, value) {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(value.lat, value.lng),
                        map: map,
                        title: value.name,
                        content: value.content ? '<div class="infoWindow">' + value.content + '</div>' : value.name,
                        label: {
                            text: String(key + 1),
                            color: 'white',
                            fontWeight: 'bold',
                        },
                    });

                    markersBounds.extend(new google.maps.LatLng(value.lat, value.lng));

                    marker.addListener('click', info);
                });

                map.fitBounds(markersBounds);

                google.maps.event.addDomListener(window, 'resize', function(event) {
                    var center = map.getCenter();
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(center);
                });

                function info() {
                    infowindow.setContent(this.content);
                    infowindow.open(map, this);
                }--}}
            }
        }
    ]);
    </script>
</div>
