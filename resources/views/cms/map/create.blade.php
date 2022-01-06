@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($map))
    {!! Form::model($map, ['method' => 'put', 'url' => \Locales::route('map/update'), 'id' => 'edit-map-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('map/store'), 'id' => 'create-map-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}
    {!! Form::hidden('lat', null, ['id' => 'input-lat']) !!}
    {!! Form::hidden('lng', null, ['id' => 'input-lng']) !!}

    <div class="form-group{!! ($errors->has('name') ? ' has-error has-feedback' : '') !!}">
        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
        @if ($errors->has('name'))<span class="glyphicon glyphicon-remove form-control-feedback"></span>@endif
    </div>

    <div class="form-group{!! ($errors->has('color') ? ' has-error has-feedback' : '') !!}">
        {!! Form::label('input-color', trans(\Locales::getNamespace() . '/forms.colorLabel')) !!}
        {!! Form::multiselect('color', $multiselect['color'], ['id' => 'input-color', 'class' => 'form-control']) !!}
        @if ($errors->has('color'))<span class="glyphicon glyphicon-remove form-control-feedback"></span>@endif
    </div>

    <div class="form-group{!! ($errors->has('order') ? ' has-error has-feedback' : '') !!}">
        {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderLabel')) !!}
        {!! Form::text('order', null, ['id' => 'input-order', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.orderPlaceholder')]) !!}
        @if ($errors->has('order'))<span class="glyphicon glyphicon-remove form-control-feedback"></span>@endif
    </div>

    <div class="form-group{!! ($errors->has('content') ? ' has-error has-feedback' : '') !!}">
        {!! Form::label('input-content', trans(\Locales::getNamespace() . '/forms.contentLabel')) !!}
        {!! Form::textarea('content', null, ['id' => 'input-content', 'class' => 'form-control ckeditor', 'placeholder' => trans(\Locales::getNamespace() . '/forms.contentPlaceholder')]) !!}
        @if ($errors->has('content'))<span class="glyphicon glyphicon-remove form-control-feedback"></span>@endif
    </div>

    <div class="form-group">
        <div id="map"></div>
    </div>

    @if (isset($map))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}

    <script>
    @section('script')
        unikat.multiselect = {
            'input-color': {
                multiple: false,
            },
        };

        var marker;
        var markers = {!! json_encode($markers) !!};
        var markersBounds = [];

        var map = L.map('map', {
            center: [0, 0],
            zoom: 0
        });

        L.tileLayer('https://www.{{ config('app.domain') }}/tiles/{z}/{y}/{x}.jpg', {
            minZoom: 0,
            maxZoom: 5,
            noWrap: true,
        }).addTo(map);

        map.fitWorld();

        $.each(markers, function(key, value) {
            var marker = L.marker([value.lat, value.lng], {
                title: value.name,
                icon: L.ExtraMarkers.icon({
                    icon: 'number',
                    markerColor: value.color ? value.color : 'red',
                    number: value.order,
                }),
            }).addTo(map);

            markersBounds.push([value.lat, value.lng]);

            marker.bindPopup(value.content ? '<div class="infoWindow">' + value.content + '</div>' : value.name);
        });

        @if (isset($map))
        marker = createMarker({
            lat: {{ $map->lat }},
            lng: {{ $map->lng }},
            title: '{!! $map->name !!}',
            color: '{{ $map->color }}',
            number: {{ $map->order }},
        });

        markersBounds.push([{{ $map->lat }}, {{ $map->lng }}]);

        marker.on('dragend', function(event) {
            saveMarker(event.target.getLatLng());
        });

        map.on('click', function(event) {
            marker.setLatLng(event.latlng);
            saveMarker(event.latlng);
        });
        @else
        map.on('click', function(event) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = createMarker({
                lat: event.latlng.lat,
                lng: event.latlng.lng,
                title: '',
                color: 'cyan',
                number: 0,
            });

            marker.on('dragend', function(event) {
                saveMarker(event.target.getLatLng());
            });

            saveMarker(event.latlng);
        });
        @endif

        if (markers.length) {
            map.fitBounds(markersBounds);
        }

        function createMarker(data) {
            return L.marker([data.lat, data.lng], {
                title: data.title,
                draggable: true,
                icon: L.ExtraMarkers.icon({
                    icon: 'number',
                    markerColor: data.color,
                    number: data.number,
                }),
            }).addTo(map);
        }

        function saveMarker(latlng) {
            $('#input-lat').val(latlng.lat);
            $('#input-lng').val(latlng.lng);
        }

        {{--var marker;
        var markers = {!! json_encode($markers) !!};
        var infowindow = new google.maps.InfoWindow();
        var TILE_SIZE = 256;
        var mapMinZoom = 1;
        var mapMaxZoom = 4;
        var mapDefaultZoom;
        if ($(window).width() <= 512) {
            mapDefaultZoom = 0;
        } else if ($(window).width() <= 1024) {
            mapDefaultZoom = 1;
        } else if ($(window).width() <= 2048) {
            mapDefaultZoom = 2;
        } else {
            mapDefaultZoom = 3;
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

                return 'https://www.{{ config('app.domain') }}/tiles/' + zoom + '/' + y + '/' + x + '.png';
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
            zoom: mapDefaultZoom,
            minZoom: mapMinZoom,
            maxZoom: mapMaxZoom,
        });

        map.mapTypes.set('maptiler', maptiler);

        $.each(markers, function(key, value) {
            var m = new google.maps.Marker({
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

            m.addListener('click', info);
        });

        @if (isset($map))
        marker = new google.maps.Marker({
            position: new google.maps.LatLng({{ $map->lat }}, {{ $map->lng }}),
            map: map,
            title: '{{ $map->name }}',
            draggable: true,
            icon: '{{ \App\Helpers\autover('/img/' . \Locales::getNamespace() . '/marker-blue.png') }}',
        });
        @endif

        google.maps.event.addDomListener(window, 'resize', function(event) {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);
        });

        function info() {
            infowindow.setContent(this.content);
            infowindow.open(map, this);
        }

        google.maps.event.addListener(map, 'click', function(event) {
            if (marker) {
                marker.setMap(null);
            }

            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                draggable: true,
                icon: '{{ \App\Helpers\autover('/img/' . \Locales::getNamespace() . '/marker-blue.png') }}',
            });

            // var worldPoint = map.getProjection().fromLatLngToPoint(event.latLng);
            $('#input-lat').val(event.latLng.lat());
            $('#input-lng').val(event.latLng.lng());

            marker.addListener('dragend', function(event) {
                // var worldPoint = map.getProjection().fromLatLngToPoint(event.latLng);
                $('#input-lat').val(event.latLng.lat());
                $('#input-lng').val(event.latLng.lng());
            });
        });--}}
    @show
    </script>
</div>
@endsection
